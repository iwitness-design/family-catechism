<?php

namespace FamilyCatechism;

use WP_REST_Server;

/**
 * Class Questions
 *
 * Handles instatiation Questions
 *
 * @package PLF\CPT
 * @since   1.0.0
 */
class Questions {

	protected static $_instance;

	public static function get_instance() {
		if ( ! self::$_instance instanceof Questions ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	protected function __construct() {
		add_action( 'init', array( $this, 'cpt' ) );
		add_action( 'cmb2_init', array( $this, 'meta_router' ) );
	}

	public function cpt() {

		register_extended_post_type( 'fc_question', array(

			# Add the post type to the site's main RSS feed:
			'show_in_feed' => false,

			'public' => true,

			'show_in_rest' => true,

			'rest_base' => 'questions',

			'menu_icon'     => 'dashicons-format-status',

			# Show all posts on the post type archive:
			'archive'       => array(
				'nopaging' => true
			),

			# Add some custom columns to the admin screen:
			'admin_cols'    => array(
				'number'   => array(
					'title'    => 'Question Number',
					'meta_key' => 'fc_number',
					'default'  => 'ASC',
					'orderby'  => 'meta_value_num'
				),
				'language' => array(
					'taxonomy' => Taxos::$_language
				)
			),

			# Add a dropdown filter to the admin screen:
			'admin_filters' => array(
				'language' => array(
					'title'    => 'Language',
					'taxonomy' => Taxos::$_language,
				)
			)

		), array(

			# Override the base names used for labels:
			'singular' => 'Question',
			'plural'   => 'Questions',
			'slug'     => 'fc_question'

		) );

	}

	public function meta_router() {
		$this->answer_meta();
	}

	protected function answer_meta() {
		$cmb = new_cmb2_box( array(
			'id'           => 'fc_answer_meta',
			'title'        => __( 'Answer', 'cmb2' ),
			'object_types' => array( 'fc_question', ), // Post type
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true, // Show field names on the left
			'show_in_rest' => WP_REST_Server::READABLE,
		) );

		$cmb->add_field( array(
			'name'         => __( 'Question Number', familycatechism()->get_id() ),
			'desc'         => __( 'The number for this question', familycatechism()->get_id() ),
			'id'           => 'fc_number',
			'type'         => 'text',
			'attributes'   => array(
				'type'    => 'number',
				'pattern' => '\d*',
			),
		) );
	}

	protected function video_meta() {
	}

}