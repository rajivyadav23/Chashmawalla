<?php
/**
 * The default product_layout post-type file.
 *
 * @package molla
 */

do_action( 'molla-single-product_layout' );

global $product;
if ( $product ) {
	wc_get_template_part( 'single-product' );
} else {
	global $post;

	get_header();

	the_content();

	get_footer();
}
