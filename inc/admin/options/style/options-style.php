<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Style panel
 */

Molla_Option::add_panel(
	'style',
	array(
		'title'    => esc_html__( 'Style', 'molla' ),
		'priority' => 3,
	)
);

require_once( MOLLA_OPTIONS . '/style/options-style-color.php' );
require_once( MOLLA_OPTIONS . '/style/options-style-typography.php' );
require_once( MOLLA_OPTIONS . '/style/options-custom-css.php' );
