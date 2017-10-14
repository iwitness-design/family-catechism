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


}