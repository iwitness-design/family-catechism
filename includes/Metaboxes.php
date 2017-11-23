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

		add_action( 'cmb2_render_array', array( $this, 'render_callback_for_array' ), 10, 5 );
		add_filter( 'cmb2_types_esc_array', array( $this, 'esc_callback_for_array' ), 10, 2 );
		add_filter( 'cmb2_sanitize_array', array( $this, 'sanitize_array_callback' ), 10, 2 );
	}


	/**
	 * Define the metabox and field configurations.
	 */
	function cmb2_sample_metaboxes() {


		$prefix = 'fc_';

		$question_fields = array(
			'answers_textanswer_answeredby' => 'text',
			'answers_textanswer_text'       => 'textarea',
			'crossreferencesother'          => 'textarea',
			'prayer'                        => 'textarea',
			'answers_videoanswer'           => array(
				'answeredby' => 'text',
				'reference'  => 'text',
				'youtubeid'  => 'text'
			),
			'crossreference'                => array(
				'type'            => 'text',
				'referencefull'   => 'text',
				'referenceabbrev' => 'text',
				'text'            => 'textarea',
				'referencenotes'  => 'array', // array
				'subsections'     => 'array', // array
				'link'            => 'text',
				'videos'          => 'array', // array
			),
			//			'group_cross_reference_videos' => array(
			//				'cross_reference_number'          => 'text',
			//				'cross_reference_video_reference' => 'text',
			//				'cross_reference_video_talent'    => 'text',
			//				'cross_reference_video_youtube_id' => 'text'
			//			),
			'exercise'                      => array(
				'questionstart'  => 'text',
				'questionend'    => 'text',
				'exercisenumber' => 'text',
				'isquestion'     => 'text',
				'question'       => 'text',
				'answer'         => 'text',
				'action'         => 'array', // array
			),
			'thoughtprovoker'               => array(
				'question'        => 'text',
				'answer'          => 'text',
				'crossreferences' => 'array' // array
			),
			'image'                         => array(
				'iscatechismbydiagram' => 'text',
				'aspectratio'          => 'text',
				'identifier'           => 'text',
				'caption'              => 'text',
				'filename'             => 'text',
			)
		);

		$cmb = new_cmb2_box( array(
			'id'           => 'question_metabox',
			'title'        => __( 'Question Meta', 'cmb2' ),
			'object_types' => array( 'fc_question', ), // Post type
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		) );

		foreach ( $question_fields as $key => $value ) {

			if ( is_array( $value ) ) {

				$title = $key;
				$title = str_replace( 'group_', '', $title );
				$title = str_replace( '_', ' ', $title );
				$title = ucwords( $title );

				$cmb_lev2 = new_cmb2_box( array(
					'id'           => $key . '_metabox',
					'title'        => __( $title, 'cmb2' ),
					'object_types' => array( 'fc_question', ), // Post type
					'context'      => 'normal',
					'priority'     => 'high',
					'show_names'   => true,
				    'closed'       => true,
				) );

				$group_field = $cmb_lev2->add_field( array(
					'id'      => $prefix . $key,
					'type'    => 'group',
					'options' => array(
						'group_title'   => __( substr( $title, strlen( $title ) * ( - 1 ), strlen( $title ) - 1 ) . ' {#}', 'cmb2' ),
						// since version 1.1.4, {#} gets replaced by row number
						'add_button'    => __( 'Add Another Entry', 'cmb2' ),
						'remove_button' => __( 'Remove Entry', 'cmb2' ),
					),
				) );

				foreach ( $value as $key_lev2 => $value_lev2 ) {

					if ( is_array( $value_lev2 ) ) {
						// here is where nested group fields would be nice
					} else {

						$group_field_title = $key_lev2;
						$group_field_title = str_replace( 'group_', '', $group_field_title );
						$group_field_title = str_replace( '_', ' ', $group_field_title );
						$group_field_title = ucwords( $group_field_title );

						$cmb_lev2->add_group_field( $group_field, array(
							'name' => $group_field_title,
							'id'   => $key_lev2,
							'type' => $value_lev2,
							// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
						) );
					}
				}

			} else {

				$name = ucwords( str_replace( '_', ' ', $key ) );

				$cmb->add_field( array(
					'name' => __( $name, 'cmb2' ),
					'id'   => $prefix . $key,
					'type' => $value,
				) );
			}
		}
	}

	/**
	 * @param \CMB2_Field $field
	 * @param $escaped_value
	 * @param $object_id
	 * @param $object_type
	 * @param \CMB2_Types $field_type_object
	 *
	 * @since  1.0.0
	 *
	 * @author Tanner Moushey
	 */
	public function render_callback_for_array( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		echo $field_type_object->textarea();
	}

	/**
	 * Custom escaping for the array field
	 *
	 * @param $value
	 * @param $meta_value
	 *
	 * @since  1.0.0
	 *
	 * @return false|string
	 * @author Tanner Moushey
	 */
	public function esc_callback_for_array( $value, $meta_value ) {
		if ( empty( $meta_value ) ) {
			return '';
		}

		if ( ! ( is_array( $meta_value ) || is_object( $meta_value ) ) ) {
			return $meta_value;
		}

		return wp_json_encode( $meta_value, JSON_PRETTY_PRINT );
	}

	/**
	 * Sanitization callback for the array field
	 *
	 * @param $override_value
	 * @param $value
	 *
	 * @since  1.0.0
	 *
	 * @return array|mixed|object
	 * @author Tanner Moushey
	 */
	public function sanitize_array_callback( $override_value, $value ) {
		if ( empty( $value ) ) {
			return $value;
		}

		if ( ! $override_value = json_decode( stripslashes( wp_kses_post( $value ) ) ) ) {
			$override_value = $value;
		}

		return $override_value;
	}

}