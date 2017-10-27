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
	}

	public function cpt() {

		register_extended_post_type( 'fc_question', array(

			# Add the post type to the site's main RSS feed:
			'show_in_feed' => false,

			'public' => true,

			'show_in_rest' => true,

			'rest_base' => 'questions',

			'rest_controller_class' => '\FamilyCatechism\API\Questions',

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
			),

		    'supports' => array( 'page-attributes', 'editor', 'title' ),

		), array(

			# Override the base names used for labels:
			'singular' => 'Question',
			'plural'   => 'Questions',
			'slug'     => 'fc_question'

		) );

	}

}