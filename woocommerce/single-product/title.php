<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package   WooCommerce/Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$title = get_the_title();

if ( strlen( $title ) ) {

	$tag = apply_filters( 'molla_single_product_title_tag', 'h2' );

	if ( apply_filters( 'molla_is_single_product_widget_title', false ) ) {
		$title = '<a href="' . get_the_permalink() . ' ">' . $title . '</a>';
	}

	echo  '<' . esc_html( $tag ) . '  class="product_title entry-title">' . molla_filter_output( $title ) . '</' . esc_html( $tag ) . '>';
}
