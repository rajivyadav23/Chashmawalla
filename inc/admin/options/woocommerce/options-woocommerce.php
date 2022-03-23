<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * woocommerce options
 */

Molla_Option::add_panel(
	'woocommerce',
	array(
		'title'    => esc_html__( 'WooCommerce', 'molla' ),
		'priority' => 9,
	)
);
require_once( MOLLA_OPTIONS . '/woocommerce/options-shop.php' );
require_once( MOLLA_OPTIONS . '/woocommerce/options-product-type.php' );
require_once( MOLLA_OPTIONS . '/woocommerce/options-product-category.php' );
require_once( MOLLA_OPTIONS . '/woocommerce/options-single-product.php' );
require_once( MOLLA_OPTIONS . '/woocommerce/options-account.php' );
require_once( MOLLA_OPTIONS . '/woocommerce/options-advanced.php' );
