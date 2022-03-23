<?php
/*
 * ajax hook functions
 */
require_once( MOLLA_FUNCTIONS . '/hooks/ajax/hooks.php' );
require_once( MOLLA_FUNCTIONS . '/hooks/ajax/functions.php' );

/*
 * woocommerce hook functions
 */
if ( ! function_exists( 'molla_init_woo_hooks' ) ) :
	function molla_init_woo_hooks() {
		require_once( MOLLA_FUNCTIONS . '/hooks/woocommerce/hooks.php' );
		require_once( MOLLA_FUNCTIONS . '/hooks/woocommerce/functions.php' );
	}
endif;
if ( class_exists( 'WooCommerce' ) ) :
	add_action( 'init', 'molla_init_woo_hooks', 8 );
endif;

/*
 * yith hook functions
 */
require_once( MOLLA_FUNCTIONS . '/hooks/yith/hooks.php' );
require_once( MOLLA_FUNCTIONS . '/hooks/yith/functions.php' );

/*
 * Theme template hook functions
 */
require_once( MOLLA_FUNCTIONS . '/hooks/template/hooks.php' );
require_once( MOLLA_FUNCTIONS . '/hooks/template/functions.php' );
