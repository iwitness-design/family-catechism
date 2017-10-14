<?php
/**
 * Class Questions
 *
 * Handles instatiation Questions
 *
 * @package PLF\CPT
 * @since 1.0.0
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
		$this->cpt();
	}

	protected function cpt() {
		add_action( 'init', function() {

			register_extended_post_type( 'fc_question', array(

				# Add the post type to the site's main RSS feed:
				'show_in_feed' => true,

				'menu_icon' => 'dashicons-format-status',

				# Show all posts on the post type archive:
				'archive' => array(
					'nopaging' => true
				),

				# Add some custom columns to the admin screen:
				'admin_cols' => array(

					'published' => array(
						'title'       => 'Published',
						'meta_key'    => 'published_date',
						'date_format' => 'd/m/Y'
					),
					'genre' => array(
						'taxonomy' => 'genre'
					)
				),

				# Add a dropdown filter to the admin screen:
				'admin_filters' => array(
					'genre' => array(
						'taxonomy' => 'genre'
					)
				)

			), array(

				# Override the base names used for labels:
				'singular' => 'Question',
				'plural'   => 'Questions',
				'slug'     => 'fc_question'

			) );

		} );

	}

}