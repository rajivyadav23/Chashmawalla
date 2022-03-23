<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blog Panel
 */

Molla_Option::add_panel(
	'blog',
	array(
		'title'    => esc_html__( 'Blog', 'molla' ),
		'priority' => 8,
	)
);
require_once( MOLLA_OPTIONS . '/blog/options-blog-general.php' );
require_once( MOLLA_OPTIONS . '/blog/options-blog-style.php' );
require_once( MOLLA_OPTIONS . '/blog/options-blog-entry.php' );
require_once( MOLLA_OPTIONS . '/blog/options-blog-single.php' );
