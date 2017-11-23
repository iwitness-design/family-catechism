<?php

class FC_Importer {

	public static $_language = 'English';

	/**
	 *
	 * ## OPTIONS
	 *
	 * <file>
	 * : The file to import
	 *
	 * <language>
	 * : The language to import the content to
	 *
	 * @param $args
	 *
	 * @since  1.0.0
	 *
	 * @author Tanner Moushey
	 */
	public function import( $args ) {

		WP_CLI::log( "\nStarting the import process..." );

		$import_file = $args[0];

		$lang = $args[1];

		if ( ! file_exists( $import_file ) ) {
			WP_CLI::error( 'That file does not exist.' );
		}

		$xml2_file = simplexml_load_file( $import_file );
		$xml2 = json_decode( json_encode( $xml2_file ), true );

		$sections = $xml2['Section'];

		if ( ! $language = get_term_by( 'slug', strtolower( $lang ), 'fc_language' ) ) {
			$language = wp_insert_term( $lang, 'fc_language', array( 'slug' => strtolower( $lang ), ) );
			$language = get_term( $language['term_id'], 'fc_language' );
		}

		$chapter_label = 'Chapter';
		$part_label    = 'Part';
		$section_label = 'Section';

		switch( $language->slug ) {
			case 'filipino' :
				$chapter_label = 'Kabanata';
				$part_label    = 'Bahagi';
				$section_label = 'Seksiyon';
				break;
			case 'chinese' :
				$section_label = '卷';
				$part_label    = '部分';
				$chapter_label = '章';
				break;
			case 'spanish' :
				$chapter_label = 'Capítulo';
				$part_label    = 'Parte';
				$section_label = 'Sección';
				break;
		}
		$count_sections = $count_parts = $count_chapters = $count_questions = 0;

		foreach ( $sections as $section ) {
			$count_sections ++;

			WP_CLI::log( sprintf( WP_CLI::colorize( "\n%gSection %s: %s %n" ), $section['Number'], $section['Name'] ) );

			if ( ! $section_id = get_term_by( 'slug', $language->slug . '_section_' . $section['Number'], 'fc_section' ) ) {
				$section_id = wp_insert_term( $section_label . ' ' . $section['Number'], 'fc_section', array(
					'description' => html_entity_decode( $section['Name'] ),
					'slug'        => $language->slug . '_section_' . $section['Number']
				) );

				$section_id = get_term( $section_id['term_id'], 'fc_section' );
			}

			$section_id = $section_id->term_id;

			$parts = $section['Parts']['Part'];

			foreach ( $parts as $part ) {
				$count_parts ++;

				WP_CLI::log( sprintf( WP_CLI::colorize( "\n%y-->Part %s: %s %n" ), $part['Number'], $part['Name'] ) );

				if ( ! $part_id = get_term_by( 'slug', $language->slug . '_section_' . $section['Number'] . '_part_' . $part['Number'], 'fc_section' ) ) {
					$part_id = wp_insert_term( $part_label . ' ' . $part['Number'], 'fc_section', array(
						'description' => html_entity_decode( $part['Name'] ),
						'slug'        => $language->slug . '_section_' . $section['Number'] . '_part_' . $part['Number'],
						'parent'      => $section_id
					) );
					$part_id = get_term( $part_id['term_id'], 'fc_section' );
				}

				$part_id = $part_id->term_id;

				self::write_term_meta( $part, $part_id );

				$chapters = $part['Chapters']['Chapter'];

				foreach ( $chapters as $chapter ) {
					$count_chapters ++;

					if ( is_array( $chapter ) ) {

						if ( key_exists( 'Name', $chapter ) ) {
							WP_CLI::log( sprintf( WP_CLI::colorize("\n%w-->-->Chapter %s: %s %n" ), $chapter['Number'], $chapter['Name'] ) );
						}

						if ( ! $chapter_id = get_term_by( 'slug', $language->slug . '_section_' . $section['Number'] . '_part_' . $part['Number'] . '_chapter_' . $chapter['Number'], 'fc_section' ) ) {
							$chapter_id = wp_insert_term( $chapter_label . ' ' . $chapter['Number'], 'fc_section', array(
								'description' => html_entity_decode( $chapter['Name'] ),
								'slug'        => $language->slug . '_section_' . $section['Number'] . '_part_' . $part['Number'] . '_chapter_' . $chapter['Number'],
								'parent'      => $part_id
							) );

							$chapter_id = get_term( $chapter_id['term_id'], 'fc_section' );
						}

						$chapter_id = $chapter_id->term_id;

						self::write_term_meta( $chapter, $chapter_id );

						$questions = $chapter['Questions']['Question'];

						if ( ! empty( $questions ) ) {

							if ( is_array( $questions[0] ) ) {

								foreach ( $questions as $question ) {
									$count_questions ++;

									WP_CLI::log( sprintf( WP_CLI::colorize("%p-->-->-->Question %s: %s %n" ), $question['Number'], $question['Name'] ) );

									$post_array = array(
										'post_content' => $question['Answers']['TextAnswer']['Text'],
										'post_title'   => html_entity_decode( $question['Name'] ),
										'post_status'  => 'publish',
										'post_type'    => 'fc_question',
										'menu_order'   => empty( $question['Number'] ) ? 9999 : absint( $question['Number'] ),
										'tax_input'    => array(
											'fc_section' => array(
												$section_id,
												$part_id,
												$chapter_id
											),
											'fc_language' => array( $language->term_id ),
										)
									);

									if ( $post = get_page_by_title( $post_array['post_title'], OBJECT, 'fc_question' ) ) {
										$post_array['ID'] = $post->ID;
									}

									if ( ! $post_id = wp_insert_post( $post_array ) ) {
										WP_CLI::error( 'Something went wrong!' );
									}

									$this->write_meta( $question, $post_id );

								}
							} else { // if this is just a single question section
								$count_questions ++;

								WP_CLI::log( sprintf( WP_CLI::colorize("%p-->-->-->Question %s: %s %n" ), $questions['Number'], $questions['Name'] ) );

								$post_array = array(
									'post_content' => $questions['Answers']['TextAnswer']['Text'],
									'post_title'   => html_entity_decode( $question['Name'] ),
									'post_status'  => 'publish',
									'post_type'    => 'fc_question',
									'menu_order'   => empty( $questions['Number'] ) ? 9999 : absint( $questions['Number'] ),
									'tax_input'    => array(
										'fc_section' => array(
											$section_id,
											$part_id,
											$chapter_id
										),
										'fc_language' => array(
											$language->term_id
										)
									)
								);

								if ( $post = get_page_by_title( $post_array['post_title'], OBJECT, 'fc_question' ) ) {
									$post_array['ID'] = $post->ID;
								}

								if ( ! $post_id = wp_insert_post( $post_array ) ) {
									WP_CLI::error( 'Something went wrong!' );
								}

								$this->write_meta( $questions, $post_id );

							}
						}
					}
				}
			}
		}

		WP_CLI::log( "\n" );
		WP_CLI::success( sprintf( "That's all folks! %s Sections, %s Parts, %s Chapters, and %s Questions processed", $count_sections, $count_parts, $count_chapters, $count_questions ) );
		WP_CLI::log( "\n" );
	}

	public function write_meta( $meta, $post_id, $parent_key = '' ) {

		foreach ( (array) $meta as $key => $value ) {

			if( empty( $value ) ) {
				continue;
			}

			if ( is_array( $value ) && 1 == count( $value ) && ! is_numeric( $key ) ) {
				$sub_value = reset( $value );
				$key       = key( $value );
				$value     = $sub_value;
			}

			$meta_key = is_numeric( $key ) ? $parent_key : $parent_key . '_' . strtolower( $key );

			if ( self::is_meta_container( $value ) ) {

				switch ( $meta_key ) {
					case '_answers_videoanswer' :
					case '_crossreference' :
					case '_exercise' :
					case '_thoughtprovoker' :
					case '_image' :
						$value = array( $value );
						break;
					default :
						self::write_meta( $value, $post_id, $meta_key );
						continue;
				}

			}

			$value = self::sanitize_meta( $value );
			update_post_meta( $post_id, 'fc' . $meta_key, $value );
		}

		return;

		$value = self::sanitize_meta( $value );

		$parent_key = preg_replace( '/(?<!\ )[A-Z]/', '_$0', $parent_key );
		$parent_key = strtolower( $parent_key );

		$key = 'fc' . $parent_key . preg_replace( '/(?<!\ )[A-Z]/', '_$0', $key );
		$key = strtolower( $key );

		update_post_meta( $post_id, $key, $value );

		WP_CLI::debug( '---' . $key );

	}


	protected static function is_meta_container( $meta ) {

		if ( ! is_array( $meta ) ) {
			return false;
		}

		foreach( $meta as $key => $value ) {
			if ( ! is_numeric( $key ) ) {
				return true;
			}
		}

		return false;
	}

	public static function sanitize_meta( $value ) {

		if ( is_array( $value ) ) {
			foreach( $value as $key => $val ) {
				unset( $value[ $key ] );
				$value[ esc_attr( strtolower( $key ) ) ] = self::sanitize_meta( $val );
			}
		} else {
			$value = htmlspecialchars_decode( wp_kses_post( $value ) );
		}

		return $value;
	}

	protected static function write_term_meta( $item, $term_id ) {

		foreach ( $item as $key => $value ) {

			if ( is_array( $value ) ) {

				if ( count( $value ) != 1 ) {
					continue;
				}

				$lev2_key_root = key( $value );

				if ( in_array( $lev2_key_root, array( 'Chapter', 'Question' ) ) ) {
					continue;
				}

				foreach ( $value[ $lev2_key_root ] as $lev2_key => $lev2_val ) {

					if ( is_array( $lev2_val ) ) {

						foreach ( $lev2_val as $lev3_key => $lev3_val ) {

							$lev3_key_string = 'fc' . preg_replace( '/(?<!\ )[A-Z]/', '_$0', $lev3_key ) . "_" . $lev2_key;
							$lev3_key_string = strtolower( $lev3_key_string );

							update_term_meta( $term_id, $lev3_key_string, $lev3_val );

							WP_CLI::debug( $lev3_key_string . '---' );

						}
					} else {

						$lev2_key_string = 'fc' . preg_replace( '/(?<!\ )[A-Z]/', '_$0', $lev2_key );
						$lev2_key_string = strtolower( $lev2_key_string );

						update_term_meta( $term_id, $lev2_key_string, $lev2_val );

						WP_CLI::debug( $lev2_key_string . '---' );
					}
				}

			} else {

				$key = 'fc' . preg_replace( '/(?<!\ )[A-Z]/', '_$0', $key );
				$key = strtolower( $key );

				update_term_meta( $term_id, $key, $value );

				WP_CLI::debug( $key . '---' );

			}
		}

	}


}

WP_CLI::add_command( 'fc', 'FC_Importer' );