<?php
// Lazy-load Images
if ( molla_option( 'lazy_load_img' ) ) {
	require_once( MOLLA_LIB . '/lib/lazy-load-img.php' );
}

// Live Search
if ( molla_option( 'live_search' ) && ( ! is_admin() || molla_ajax() ) ) {
	require_once( MOLLA_LIB . '/lib/live-search.php' );
}

// Product Brand Attribute
if ( class_exists( 'WooCommerce' ) ) {
	require_once( MOLLA_LIB . '/lib/pro/brand/product-brand.php' );
}

// Pro Version
include_once( MOLLA_LIB . '/lib/pro/setup.php' );
