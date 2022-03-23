<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define Theme Version
 */

$theme_version = '';
$theme         = wp_get_theme();
if ( is_child_theme() ) {
	$theme = wp_get_theme( $theme->template );
}
$theme_version = $theme->version;
define( 'MOLLA_VERSION', $theme_version );                    // set current version

/**
 * Define variables
 */
define( 'MOLLA_DIR', get_parent_theme_file_path() );                // template directory
define( 'MOLLA_URI', get_parent_theme_file_uri() );                 // template directory uri
define( 'MOLLA_LIB', MOLLA_DIR . '/inc' );                          // library directory
define( 'MOLLA_PLUGINS', MOLLA_DIR . '/inc/plugins' );              // plugins directory
define( 'MOLLA_PLUGINS_URI', MOLLA_URI . '/inc/plugins' );          // plugins uri
define( 'MOLLA_ADMIN', MOLLA_LIB . '/admin' );                      // admin directory
define( 'MOLLA_CLASS', MOLLA_LIB . '/classes' );                    // class directory
define( 'MOLLA_OPTIONS', MOLLA_ADMIN . '/options' );                // theme options directory
define( 'MOLLA_CUSTOM_IMG', MOLLA_URI . '/inc/customizer/img' );    // customizer images
define( 'MOLLA_FUNCTIONS', MOLLA_LIB . '/functions' );              // functions directory
define( 'MOLLA_CSS', MOLLA_URI . '/assets/css' );                   // template css uri
define( 'MOLLA_JS', MOLLA_URI . '/assets/js' );                     // template js uri
define( 'MOLLA_VENDOR', MOLLA_URI . '/assets/vendor' );             // template vendor uri
define( 'MOLLA_PLUGINS_CSS', MOLLA_CSS . '/plugins' );              // template plugin-css uri

// Content Width
if ( ! isset( $content_width ) ) {
	$content_width = 1140;
}

if ( is_rtl() ) {

}

/**
 * Molla functions include
 */
require_once MOLLA_FUNCTIONS . '/functions.php';

require_once MOLLA_LIB . '/init.php';
