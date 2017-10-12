<?php

class Init {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the Init
	 *
	 * @return Init
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof Init ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		require_once FAMCAT_MU_INCLUDE_PATH . '/autoload.php';
		require_once FAMCAT_MU_INCLUDE_PATH . '/johnbillion/extended-cpts/extended-cpts.php';
		require_once FAMCAT_MU_INCLUDE_PATH . '/johnbillion/extended-taxos/extended-taxos.php';
		require_once FAMCAT_MU_INCLUDE_PATH . '/webdevstudios/cmb2/init.php';

	}
}