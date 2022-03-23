<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header Panel
 */

Molla_Option::add_panel(
	'header',
	array(
		'title'    => esc_html__( 'Header', 'molla' ),
		'priority' => 4,
	)
);

require_once( MOLLA_OPTIONS . '/header/options-header-general.php' );
require_once( MOLLA_OPTIONS . '/header/options-header-logo.php' );
require_once( MOLLA_OPTIONS . '/header/molla-header-builder.php' );
require_once( MOLLA_OPTIONS . '/header/options-header-top.php' );
require_once( MOLLA_OPTIONS . '/header/options-header-main.php' );
require_once( MOLLA_OPTIONS . '/header/options-header-bottom.php' );
require_once( MOLLA_OPTIONS . '/header/options-header-sticky.php' );

require_once( MOLLA_OPTIONS . '/header/options-header-search.php' );
require_once( MOLLA_OPTIONS . '/header/options-header-account.php' );
require_once( MOLLA_OPTIONS . '/header/options-header-shop_icons.php' );
require_once( MOLLA_OPTIONS . '/header/options-header-social_links.php' );
require_once( MOLLA_OPTIONS . '/header/options-header-top-nav.php' );
