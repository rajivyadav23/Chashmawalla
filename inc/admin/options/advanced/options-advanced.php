<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header Panel
 */

Molla_Option::add_panel(
	'advanced',
	array(
		'title'    => esc_html__( 'Advanced', 'molla' ),
		'priority' => 11,
	)
);

require_once( MOLLA_OPTIONS . '/advanced/options-performance.php' );
require_once( MOLLA_OPTIONS . '/advanced/options-pagination.php' );
require_once( MOLLA_OPTIONS . '/advanced/options-error-404.php' );
require_once( MOLLA_OPTIONS . '/advanced/options-import_export.php' );
require_once( MOLLA_OPTIONS . '/advanced/options-reset.php' );
require_once( MOLLA_OPTIONS . '/advanced/options-custom_image.php' );

