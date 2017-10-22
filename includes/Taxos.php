<?php
namespace FamilyCatechism;

/**
 * Created by PhpStorm.
 * User: Dustin
 * Date: 10/12/2017
 * Time: 7:27 PM
 */
class Taxos {

	/**
	 * The section taxonomy slug
	 *
	 * @var string
	 */
	public static $_section = 'fc_section';

	/**
	 * The language taxonomy slug
	 *
	 * @var string
	 */
	public static $_language = 'fc_language';

	protected static $_instance;

	public static function get_instance() {
		if ( ! self::$_instance instanceof Taxos ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Taxos constructor.
	 */
	protected function __construct() {
		$this->section();
		$this->language();
	}

	/** Create Taxonomies *********************/

	/**
	 * Create the fc_section Taxonomy
	 *
	 * @since  1.0.0
	 *
	 * @author Dustin Phillips
	 */
	protected function section() {

		add_action( 'init', function () {

			register_extended_taxonomy( self::$_section, 'fc_question', array(

				'hierarchical' => true,

				# Add a custom column to the admin screen:
				'admin_cols' => array(
					'updated' => array(
						'title'       => __( 'Updated', 'plf-mu' ),
						'meta_key'    => 'updated_date',
						'date_format' => 'd/m/Y'
					),
				),

			), array(

				# Override the base names used for labels:
				'singular' => __( 'Section', 'fc-mu' ),
				'plural'   => __( 'Sections', 'fc-mu' ),
				'slug'     => 'fc_section'

			) );

		} );

	}

	protected function language() {

		add_action( 'init', function () {

			register_extended_taxonomy( self::$_language, 'fc_question', array(

				'meta_box'   => 'radio',
				'hierarchical' => false,

				# Add a custom column to the admin screen:
				'admin_cols' => array(
					'updated' => array(
						'title'       => __( 'Updated', 'plf-mu' ),
						'meta_key'    => 'updated_date',
						'date_format' => 'd/m/Y'
					),
				),

			), array(

				# Override the base names used for labels:
				'singular' => __( 'Language', 'fc-mu' ),
				'plural'   => __( 'Languages', 'fc-mu' ),
				'slug'     => 'fc_language'

			) );

		} );

	}
}