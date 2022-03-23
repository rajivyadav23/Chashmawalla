<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All theme functions run
 */

// Theme admin
if ( current_user_can( 'manage_options' ) ) {
	require MOLLA_CLASS . '/molla-options.php';
	require MOLLA_ADMIN . '/admin-init.php';

	/**
	 * Install Plugins
	 */
	require_once MOLLA_PLUGINS . '/plugins.php';
}

/**
 * Compatiblity with 3rd party plugins
 */
if ( defined( 'ELEMENTOR_VERSION' ) || defined( 'ELEMENTOR_PRO_VERSION' ) ) {
	require_once MOLLA_PLUGINS . '/compatibility/elementor.php';
}

// Custom menu walker
require_once( MOLLA_LIB . '/walker/molla-nav-field.php' );
require_once( MOLLA_LIB . '/walker/molla-nav-walker.php' );

require_once( MOLLA_LIB . '/lib/setup.php' );
