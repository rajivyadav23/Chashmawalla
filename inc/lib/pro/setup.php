<?php

define( 'MOLLA_PRO_LIB', MOLLA_LIB . '/lib/pro' );
define( 'MOLLA_PRO_LIB_URI', MOLLA_URI . '/inc/lib/pro' );


// Skeleton Screen
if ( molla_option( 'skeleton_screen' ) && ! molla_ajax() ) {
	require_once( MOLLA_PRO_LIB . '/skeleton/skeleton.php' );
}

// Product Image Swatch
if ( class_exists( 'WooCommerce' ) ) {
	global $pagenow;

	$product_edit_page = ( 'post-new.php' == $pagenow && isset( $_GET['post_type'] ) && 'product' == $_GET['post_type'] ) ||
							( 'post.php' == $pagenow && isset( $_GET['post'] ) && 'product' == get_post_type( $_GET['post'] ) );

	if ( molla_option( 'image_swatch' ) ) {
		if ( is_admin() && ( molla_ajax() || $product_edit_page || !empty( $_POST['action'] ) && 'editpost' == $_POST['action'] ) ) {
			require_once( MOLLA_PRO_LIB . '/image-swatch/admin-image-swatch-tab.php' );
		}
		require_once( MOLLA_PRO_LIB . '/image-swatch/image-swatch.php' );
	}

	if ( is_admin() && ( molla_ajax() || $product_edit_page ) ) {
		require_once( MOLLA_PRO_LIB . '/product-data-addons/product-data-addons-admin.php' );
	}
}

// Molla Studio
if ( ( ( class_exists( 'Vc_Manager' ) && ( ( is_admin() && ( 'post.php' == $GLOBALS['pagenow'] || 'post-new.php' == $GLOBALS['pagenow'] || molla_ajax() ) ) || ( isset( $_REQUEST['vc_editable'] ) && $_REQUEST['vc_editable'] ) ) ) ||
	( defined( 'ELEMENTOR_VERSION' ) && ( molla_is_elementor_preview() || wp_doing_ajax() ) ) ) &&
	( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) ) {
	require_once MOLLA_PRO_LIB . '/studio/studio.php';
}

// Pre Order
if ( class_exists( 'WooCommerce' ) && molla_option( 'woo_pre_order' ) ) {
	require_once MOLLA_PRO_LIB . '/woo-pre-order/init.php';
}



/**
 * Include Product Image Addons such as 360
 * degree viewer and video popup
 */
add_action( 'template_redirect', 'molla_product_image_addons', 10 );
function molla_product_image_addons() {
	// Add option to get theme mod for 360 degree viewer
	if ( molla_is_product() ) {
		require_once MOLLA_PRO_LIB . '/360-degree-viewer/360-degree-viewer.php';
		require_once MOLLA_PRO_LIB . '/product-video-popup/product-video-popup.php';
	}
}
