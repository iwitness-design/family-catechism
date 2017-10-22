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
		}

		$count_sections = $count_parts = $count_chapters = $count_questions = 0;

		foreach ( $sections as $section ) {
			$count_sections ++;

			WP_CLI::log( sprintf( WP_CLI::colorize( "\n%gSection %s: %s %n" ), $section['Number'], $section['Name'] ) );

			if ( ! $section_id = get_term_by( 'slug', 'section_' . $section['Number'], 'fc_section' ) ) {
				$section_id = wp_insert_term( 'Section ' . $section['Number'], 'fc_section', array(
					'description' => $section['Name'],
					'slug'        => 'section_' . $section['Number']
				) );
			}

			$section_id = $section_id->term_id;

			$parts = $section['Parts']['Part'];

			foreach ( $parts as $part ) {
				$count_parts ++;

				WP_CLI::log( sprintf( WP_CLI::colorize( "\n%y-->Part %s: %s %n" ), $part['Number'], $part['Name'] ) );

				if ( ! $part_id = get_term_by( 'slug', 'section_' . $section['Number'] . '_part_' . $part['Number'], 'fc_section' ) ) {
					$part_id = wp_insert_term( 'Part ' . $part['Number'], 'fc_section', array(
						'description' => $part['Name'],
						'slug'        => 'section_' . $section['Number'] . '_part_' . $part['Number'],
						'parent'      => $section_id
					) );
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

						if ( ! $chapter_id = get_term_by( 'slug', 'section_' . $section['Number'] . '_part_' . $part['Number'] . '_chapter_' . $chapter['Number'], 'fc_section' ) ) {
							wp_insert_term( 'Chapter ' . $chapter['Number'], 'fc_section', array(
								'description' => $chapter['Name'],
								'slug'        => 'section_' . $section['Number'] . '_part_' . $part['Number'] . '_chapter_' . $chapter['Number'],
								'parent'      => $part_id
							) );
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
										'post_title'   => $question['Name'],
										'post_status'  => 'publish',
										'post_type'    => 'fc_question',
										'menu_order'   => empty( $question['Number'] ) ? 0 : absint( $question['Number'] ),
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

									if ( $post = get_page_by_title( $question['Name'], OBJECT, 'fc_question' ) ) {
										$post_array['ID'] = $post->ID;
									}

									if ( ! $post_id = wp_insert_post( $post_array ) ) {
										WP_CLI::error( 'Something went wrong!' );
									}

									$loop_level = new FC_Loop_Level();
									$loop_level->loop_level( $question, $post_id, '' );

								}
							} else { // if this is just a single question section
								$count_questions ++;

								WP_CLI::log( sprintf( WP_CLI::colorize("%p-->-->-->Question: %s %n" ), $question['Name'] ) );

								$post_array = array(
									'post_content' => $questions['Answers']['TextAnswer']['Text'],
									'post_title'   => $questions['Name'],
									'post_status'  => 'publish',
									'post_type'    => 'fc_question',
									'menu_order'   => empty( $question['Number'] ) ? 0 : absint( $question['Number'] ),
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

								if ( $post = get_page_by_title( $question['Name'], OBJECT, 'fc_question' ) ) {
									$post_array['ID'] = $post->ID;
								}

								if ( ! $post_id = wp_insert_post( $post_array ) ) {
									WP_CLI::error( 'Something went wrong!' );
								}

								$loop_level = new FC_Loop_Level();
								$loop_level->loop_level( $question, $post_id, '' );

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

	public static function write_meta( $key, $value, $post_id, $parent_key ) {

		$parent_key = preg_replace( '/(?<!\ )[A-Z]/', '_$0', $parent_key );
		$parent_key = strtolower( $parent_key );

		$key = 'fc' . $parent_key . preg_replace( '/(?<!\ )[A-Z]/', '_$0', $key );
		$key = strtolower( $key );

		update_post_meta( $post_id, $key, $value );

		WP_CLI::debug( $key . '---' );

	}

	protected static function write_term_meta( $item, $term_id ) {

		foreach ( $item as $key => $value ) {

			if ( is_array( $value ) ) {
				if ( count( $value ) == 1 ) {


					$lev2_key_root = key( $value );

					if ( $lev2_key_root !== 'Chapter' ) {
						if ( $lev2_key_root !== 'Question' ) {

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
						}
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


class FC_Loop_Level {

	function loop_level( $level, $post_id, $parent_key ) {

		foreach ( $level as $key => $value ) {

			if ( empty( $value ) ) {
				// do nothing
			} else {
				if ( is_array( $value ) ) {

					if ( count( $value ) == 1 ) {
						foreach ( $value as $second_value ) {
							if ( is_array( $second_value ) ) {
								$value = $second_value;
							}
						}
					}

					if ( is_numeric( $key ) ) {
						$key_text = '_' . $key;
					} else {
						$key_text = $key;
					}

					$parents_keys = $parent_key . $key_text;

					$loop_level = new FC_Loop_Level();
					$loop_level->loop_level( $value, $post_id, $parents_keys );

				} else {
					FC_Importer::write_meta( $key, $value, $post_id, $parent_key );
				}
			}
		}
	}

}
