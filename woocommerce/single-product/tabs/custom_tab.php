<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Show Custom Tabs
 */

if ( isset( $tab_name ) ) {

	$tab_title = '';

	if ( 'global' == $tab_name || 'block' == $tab_name ) {
		if ( 'global' == $tab_name ) {
			$tab_title = molla_option( 'single_product_tab_title' );
			$tab_name  = molla_option( 'single_product_tab_block' );
		} else {
			$tab_title = get_post_meta( get_the_ID(), 'tab_content_title', true );
			$tab_name  = get_post_meta( get_the_ID(), 'tab_content_block', true );
		}

		echo apply_filters( 'molla_custom_tab_title', ( $tab_title ? ( '<h2>' . $tab_title . '</h2>' ) : '' ), $tab_name );

		if ( function_exists( 'molla_print_custom_post' ) ) {
			molla_print_custom_post( 'block', $tab_name );
		}
	} else {
		$tab_title = get_post_meta( get_the_ID(), 'tab_title_' . $tab_name, true );
		echo apply_filters( 'molla_custom_tab_title', ( $tab_title ? ( '<h2>' . $tab_title . '</h2>' ) : '' ), $tab_name );

		$content = get_post_meta( get_the_ID(), 'tab_content_' . $tab_name, true );
		echo molla_strip_script_tags( $content );
	}
}
