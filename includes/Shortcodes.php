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
		wp_enqueue_style( familycatechism()->get_id(), familycatechism()->get_plugin_url() . 'assets/css/app.css', array(), familycatechism()->get_version() );

		wp_enqueue_script( familycatechism()->get_id() . '-manifest', familycatechism()->get_plugin_url() . 'assets/js/manifest.js', array(), familycatechism()->get_version() );
		wp_enqueue_script( familycatechism()->get_id() . '-vendor', familycatechism()->get_plugin_url() . 'assets/js/vendor.js', array(), familycatechism()->get_version() );
		wp_enqueue_script( familycatechism()->get_id() . '-app', familycatechism()->get_plugin_url() . 'assets/js/app.js', array(), familycatechism()->get_version() );
	}

}