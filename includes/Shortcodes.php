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
	}

	public function family_catechism_cb() {
		$this->register_scripts();

		ob_start();
		include( familycatechism()->get_plugin_dir() . 'templates/family-catechism.php' );
		return ob_get_clean();
	}

	protected function register_scripts() {
		wp_enqueue_style( familycatechism()->get_id(), familycatechism()->get_plugin_url() . 'dist/app.css', array(), familycatechism()->get_version() );
		wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

//		wp_enqueue_script( familycatechism()->get_id() . '-manifest', familycatechism()->get_plugin_url() . 'assets/js/manifest.js', array(), familycatechism()->get_version() );
//		wp_enqueue_script( familycatechism()->get_id() . '-vendor', familycatechism()->get_plugin_url() . 'assets/js/vendor.js', array(), familycatechism()->get_version() );
		wp_enqueue_script( familycatechism()->get_id() . '-app', familycatechism()->get_plugin_url() . 'dist/app.js', array(), familycatechism()->get_version() );

		wp_localize_script( familycatechism()->get_id() . '-app', 'fcLang', array(
			'name'   => __( 'Family Catechism', familycatechism()->get_id() ),
			'answer' => __( 'Answer', familycatechism()->get_id() ),
			'q'      => __( 'Q', familycatechism()->get_id() ),
			'qnext'  => __( 'Next Question', familycatechism()->get_id() ),
			'qlast'  => __( 'Last Question', familycatechism()->get_id() ),
			'search'  => __( 'Search', familycatechism()->get_id() ),
			'video'  => __( 'Video', familycatechism()->get_id() ),
			'prayer'  => __( 'Prayer', familycatechism()->get_id() ),
			'scripture'  => __( 'Scripture', familycatechism()->get_id() ),
			'catechism'  => __( 'Catechism', familycatechism()->get_id() ),
			'church-docs'  => __( 'Church Docs', familycatechism()->get_id() ),
			'papal-docs'  => __( 'Papal Docs', familycatechism()->get_id() ),
			'other-docs'  => __( 'Other Docs', familycatechism()->get_id() ),
			'doctrine'  => __( 'Doctrine', familycatechism()->get_id() ),
			'thought-provokers'  => __( 'Thought Provokers', familycatechism()->get_id() ),
		) );
	}

}