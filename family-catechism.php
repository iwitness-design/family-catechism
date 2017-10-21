<?php
/**
 * Plugin Name: Family Catechism
 * Plugin URL: http://iwitnessdesign.com
 * Description: A custom plugin for the Family Catechism web app
 * Version: 1.0.0
 * Author: iWitness Design
 * Author URI: https://iwitnessdesign.com
 * Text Domain: family-catechism
 * Domain Path: languages
 */

class FamilyCatechism {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * @var string
	 */
	protected static $_version = '1.0.0';

	/**
	 * Only make one instance of self
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof self ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'plugins_loaded', array( $this, 'maybe_setup' ), -9999 );
	}

	/**
	 * Includes
	 */
	protected function includes() {

		$test = $this->get_plugin_dir();

		require_once( $this->get_plugin_dir() . '/includes/Taxos.php' );
		require_once( $this->get_plugin_dir() . '/includes/Questions.php' );
		require_once( $this->get_plugin_dir() . 'vendor/autoload.php' );
		require_once( $this->get_plugin_dir() . '/vendor/johnbillion/extended-taxos/extended-taxos.php' );
		require_once( $this->get_plugin_dir() . '/vendor/johnbillion/extended-cpts/extended-cpts.php' );
		require_once( $this->get_plugin_dir() . '/vendor/webdevstudios/cmb2/init.php' );

		Questions::get_instance();
		Taxos::get_instance();
		FamilyCatechism\Shortcodes::get_instance();
	}

	/**
	 * Actions and Filters
	 */
	protected function actions() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/** Actions **************************************/

	/**
	 * Setup the plugin
	 */
	public function maybe_setup() {
		$this->includes();
		$this->actions();
	}

	/**
	 * Load the text domain
	 *
	 * @since  1.0.0
	 */
	public function load_textdomain() {
		// Set filter for plugin's languages directory
		$lang_dir = dirname( plugin_basename( $this->get_plugin_file() ) ) . '/languages/';
		$lang_dir = apply_filters( $this->get_id() . '_languages_directory', $lang_dir );


		// Traditional WordPress plugin locale filter

		$get_locale = get_locale();

		if ( function_exists( 'get_user_locale' ) ) {
			$get_locale = get_user_locale();
		}

		/**
		 * Defines the plugin language locale used.
		 *
		 * @var string $get_locale The locale to use. Uses get_user_locale()` in WordPress 4.7 or greater,
		 *                  otherwise uses `get_locale()`.
		 */
		$locale = apply_filters( 'plugin_locale', $get_locale, $this->get_id() );
		$mofile = sprintf( '%1$s-%2$s.mo', $this->get_id(), $locale );

		// Setup paths to current locale file
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/' . $this->get_id() . '/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			load_textdomain( $this->get_id(), $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			load_textdomain( $this->get_id(), $mofile_local );
		} else {
			load_plugin_textdomain( $this->get_id(), false, $lang_dir );
		}
	}

	/** Helper Methods **************************************/

	/**
	 * Return the version of the plugin
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_version() {
		return self::$_version;
	}

	/**
	 * Returns the plugin name, localized
	 *
	 * @since 1.0.0
	 * @return string the plugin name
	 */
	public function get_plugin_name() {
		return __( 'Family Catechism', $this->get_id() );
	}

	/**
	 * Returns the plugin ID. Used in the textdomain
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_id() {
		return 'family-catechism';
	}

	/**
	 * Get the plugin directory path
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_plugin_dir() {
		return plugin_dir_path( $this->get_plugin_file() );
	}

	/**
	 * Get the plugin directory url
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_plugin_url() {
		return plugin_dir_url( $this->get_plugin_file() );
	}

	/**
	 * Get the plugin file
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_plugin_file() {
		return __FILE__;
	}

}

/**
 * @return FamilyCatechism
 */
function familycatechism() {
	return FamilyCatechism::get_instance();
}

familycatechism();
