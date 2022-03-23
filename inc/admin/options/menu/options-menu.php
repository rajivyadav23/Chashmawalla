<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header Panel
 */

Molla_Option::add_panel(
	'nav_menus',
	array(
		'title'    => esc_html__( 'Menus', 'molla' ),
		'priority' => 5,
	)
);

require_once( MOLLA_OPTIONS . '/menu/options-main-menu.php' );
require_once( MOLLA_OPTIONS . '/menu/options-mobile-menu.php' );
require_once( MOLLA_OPTIONS . '/menu/options-menu-skin.php' );
