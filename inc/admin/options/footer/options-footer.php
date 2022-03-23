<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Footer Panel
 */

Molla_Option::add_panel(
	'footer',
	array(
		'title'    => esc_html__( 'Footer', 'molla' ),
		'priority' => 7,
	)
);

require_once( MOLLA_OPTIONS . '/footer/options-footer-layout.php' );
require_once( MOLLA_OPTIONS . '/footer/options-footer-style.php' );
require_once( MOLLA_OPTIONS . '/footer/options-footer-top.php' );
require_once( MOLLA_OPTIONS . '/footer/options-footer-main.php' );
require_once( MOLLA_OPTIONS . '/footer/options-footer-bottom.php' );
