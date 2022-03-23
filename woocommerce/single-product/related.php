<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( $related_products && molla_option( 'single_related_show' ) ) : ?>

	<section class="related products">

		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', esc_html__( 'Related products', 'woocommerce' ) );

		if ( $heading ) :
			?>
			<h2 class="title text-<?php echo apply_filters( 'molla_single_product_title_align', 'center' ); ?>"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<?php

		wc_set_loop_prop( 'layout_mode', molla_option( 'single_related_layout_type' ) );
		wc_set_loop_prop( 'columns', molla_option( 'single_related_cols' ) );
		wc_set_loop_prop( 'product_style', molla_option( 'post_product_type' ) );
		wc_set_loop_prop( 'elem', 'product' );
		wc_set_loop_prop( 'slider_nav', array( true, false, false ) );
		wc_set_loop_prop( 'slider_dot', array( false, true, true ) );

		?>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $related_products as $related_product ) : ?>

					<?php
					$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

					wc_get_template_part( 'content', 'product' );
					?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</section>

	<?php
endif;

wp_reset_postdata();
