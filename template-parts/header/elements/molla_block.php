<?php
if ( isset( $args ) && isset( $args->html ) ) {
	$atts['name'] = $args->html;
	if ( function_exists( 'molla_print_custom_post' ) ) {
		molla_print_custom_post( 'block', $atts['name'] );
	} else {
		echo '<strong>Plugin not installed.</strong>';
	}
}
