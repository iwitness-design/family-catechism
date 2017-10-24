<?php

namespace FamilyCatechism;

/**
 * Class Metaboxes
 *
 * Handles instatiation Metaboxes
 *
 * @package
 * @since   1.0.0
 */

class Metaboxes {

	protected static $_instance;

	public static function get_instance() {
		if ( ! self::$_instance instanceof Metaboxes ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function __construct() {
		add_action( 'cmb2_admin_init', array( $this, 'cmb2_sample_metaboxes' ) );
	}


	/**
	 * Define the metabox and field configurations.
	 */
	function cmb2_sample_metaboxes() {


		$prefix = 'fc_';

		$question_fields = array(
			'text_answer_author' => 'text',
			'group_videos' => array(
				'video_answer_author' =>'text',
				'video_answer_reference' =>'text',
				'video_answer_youtube_id' =>'text'
			),
			'group_cross_references' => array(
				'cross_reference_type' => 'text',
				'cross_reference_full_reference' => 'text',
				'cross_reference_abbrev_reference' => 'text',
				'cross_reference_notes' => 'text',
				'cross_reference_text' => 'text',
				'cross_reference_link' => 'text',
				'group_cross_reference_videos' => array(
					'cross_reference_video_reference' => 'text',
					'cross_reference_video_talent' => 'text',
					'cross_reference_video_youtube_id' => 'text'
				)
			),
			'group_excercises' => array(
				'excercise_question_start' => 'text',
				'excercise_question_end' => 'text',
				'excercise_question_number' => 'text',
				'excercise_question_is_question' => 'text',
				'excercise_question' => 'text',
				'excercise_answer' => 'text',
			),
			'group_thought_provokers' => array(
				'thought_provoker_question' => 'text',
				'thought_provoker_answer' => 'text',
				'thought_provoker_cross_reference' => 'text'
			),
			'group_images' => array(
				'image_is_category_by_diagram' => 'text',
				'image_aspect_ratio' => 'text',
				'image_identifier' => 'text',
				'image_caption' => 'text',
				'image_filename' => 'text',

			)
		);

		$cmb = new_cmb2_box( array(
			'id'            => 'question_metabox',
			'title'         => __( 'Question Meta', 'cmb2' ),
			'object_types'  => array( 'fc_question', ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true,
		) );

		foreach ($question_fields as $key => $value) {

			if (is_array($value)) {

				$title = $key;
				$title = str_replace ( 'group_', '', $title );
				$title = str_replace ( '_', ' ', $title );
				$title = ucwords($title);

				$cmb_lev2 = new_cmb2_box( array(
					'id'            =>  $key . '_metabox',
					'title'         => __( $title, 'cmb2' ),
					'object_types'  => array( 'fc_question', ), // Post type
					'context'       => 'normal',
					'priority'      => 'high',
					'show_names'    => true,
				) );

				$group_field = $cmb_lev2->add_field( array(
					'id'          => $prefix . $key,
					'type'        => 'group',
					'options'     => array(
						'group_title'   => __( substr($title, strlen($title) * (-1),strlen($title) -1 ) . ' {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
						'add_button'    => __( 'Add Another Entry', 'cmb2' ),
						'remove_button' => __( 'Remove Entry', 'cmb2' ),
					),
				) );

				foreach ($value as $key_lev2 => $value_lev2) {

					if (is_array($value_lev2)) {
						// here is where nested group fields would be nice
					} else {

						$group_field_title = $key_lev2;
						$group_field_title = str_replace ( 'group_', '', $group_field_title );
						$group_field_title = str_replace ( '_', ' ', $group_field_title );
						$group_field_title = ucwords($group_field_title);

						$cmb_lev2->add_group_field( $group_field, array(
							'name' => $group_field_title,
							'id'   => $key_lev2,
							'type' => $value_lev2,
							// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
						) );
					}
				}

			} else {

				$name = ucwords(str_replace('_',' ',$key));

				$cmb->add_field( array(
					'name'       => __( $name, 'cmb2' ),
					'id'         => $prefix . $key,
					'type'       => $value,
				) );
			}
		}
	}
}