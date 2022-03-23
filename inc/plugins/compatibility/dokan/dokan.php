<?php
/**
 * Molla_Dokan class
 *
 * @since 1.0.0
 * @package Molla WordPress Framework
 */
defined( 'ABSPATH' ) || die;

class Molla_Dokan {

	private static $instances = array();

	static function get_instance() {
		$called_class = get_called_class();
		if ( empty( self::$instances[ $called_class ] ) ) {
			self::$instances[ $called_class ] = new $called_class();
		}
		return self::$instances[ $called_class ];
	}

	public function __construct() {
		// Enqueue molla-dokan script
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ), 50 );
	}

	/**
	 * Enqueue Dokan Style
	 *
	 * @since 1.0.0
	 */
	public function enqueue_style() {

		wp_enqueue_style( 'molla-dokan-style', MOLLA_PLUGINS_URI . '/compatibility/dokan/dokan' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array( 'dokan-style' ), MOLLA_VERSION );
	}
}

Molla_Dokan::get_instance();
