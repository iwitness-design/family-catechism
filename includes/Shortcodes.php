<?php
namespace FamilyCatechism;

use FamilyCatechism;

/**
 * Class Shortcodes
 *
 * Handles instatiation Shortcodes
 *
 * @package PLF\CPT
 * @since 1.0.0
 */

class Shortcodes {

	protected static $_instance;

	public static function get_instance() {
		if ( ! self::$_instance instanceof Shortcodes ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	protected function __construct() {
		add_shortcode( 'family-catechism', array( $this, 'family_catechism_cb' ) );
		add_filter( 'plugin_locale', array( $this, 'localization' ), 10, 2 );
	}

	/**
	 * Shortcode template
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 * @author Tanner Moushey
	 */
	public function family_catechism_cb() {
		$this->register_scripts();

		ob_start();
		include( familycatechism()->get_plugin_dir() . 'templates/family-catechism.php' );
		return ob_get_clean();
	}

	/**
	 * Register scripts
	 *
	 * @since  1.0.0
	 *
	 * @author Tanner Moushey
	 */
	protected function register_scripts() {
		wp_enqueue_style( 'fontawesome', familycatechism()->get_plugin_url() . 'dist/css/app.css', array(), familycatechism()->get_version() );
		wp_enqueue_style( familycatechism()->get_id(), familycatechism()->get_plugin_url() . 'dist/css/app.css', array(), familycatechism()->get_version() );
		wp_enqueue_script( familycatechism()->get_id() . '-app', familycatechism()->get_plugin_url() . 'dist/js/app.js', array( 'jquery' ), familycatechism()->get_version() );

		wp_localize_script( familycatechism()->get_id() . '-app', 'fcLang', array(
			'name'              => __( 'Family Catechism', familycatechism()->get_id() ),
			'answer'            => __( 'Answer', familycatechism()->get_id() ),
			'q'                 => __( 'Q', familycatechism()->get_id() ),
			'question'          => __( 'Question', familycatechism()->get_id() ),
			'qnext'             => __( 'Next Question', familycatechism()->get_id() ),
			'qlast'             => __( 'Last Question', familycatechism()->get_id() ),
			'search'            => __( 'Search', familycatechism()->get_id() ),
			'video'             => __( 'Video', familycatechism()->get_id() ),
			'prayer'            => __( 'Prayer', familycatechism()->get_id() ),
			'chapter-prayer'    => __( 'Chapter Prayer', familycatechism()->get_id() ),
			'summary-prayer'    => __( 'Summary Prayer', familycatechism()->get_id() ),
			'scripture'         => __( 'Scripture', familycatechism()->get_id() ),
			'catechism'         => __( 'Catechism', familycatechism()->get_id() ),
			'church-docs'       => __( 'Church Docs', familycatechism()->get_id() ),
			'papal-docs'        => __( 'Papal Docs', familycatechism()->get_id() ),
			'other-docs'        => __( 'Other Docs', familycatechism()->get_id() ),
			'doctrine'          => __( 'Doctrine', familycatechism()->get_id() ),
			'thought-provokers' => __( 'Thought Provokers', familycatechism()->get_id() ),
		    'url'               => get_the_permalink(),
		    'localID'           => empty( $_GET['l'] ) ? get_term_by( 'slug', 'english', Taxos::$_language )->term_id : get_term_by( 'slug', $_GET['l'], Taxos::$_language )->term_id,
		    'local'             => empty( $_GET['l'] ) ? 'english' : $_GET['l'],
		) );
	}

	/**
	 * Load plugin file
	 *
	 * @param $locale
	 * @param $plugin
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 * @author Tanner Moushey
	 */
	public function localization( $locale, $plugin ) {

		if ( $plugin !== familycatechism()->get_id() ) {
			return $locale;
		}

		if ( empty( $_GET['l'] ) ) {
			return $locale;
		}

		switch ( $_GET['l'] ) {
			case 'spanish' :
				return 'es';
			case 'filipino' :
				return 'fil';
			case 'chinese' ;
				return 'zh';
		}

		return $locale;
	}

}