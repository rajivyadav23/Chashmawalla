<?php
get_header();

$error_block = molla_option( 'error-block-name' );

if ( function_exists( 'molla_print_custom_post' ) && $error_block ) {
	molla_print_custom_post( 'block', $error_block );
} else {
	molla_404_page();
}

get_footer();
