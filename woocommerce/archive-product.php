<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

$loadmore = molla_option( 'shop_view_more_type' );

$sidebar_type = molla_option( 'shop_sidebar_type' );

if ( ! molla_is_shop() && molla_is_in_category() ) {
	$term = get_queried_object();
	$meta = get_term_meta( $term->term_id, 'cat_sidebar', true );
	if ( 'default' != $meta ) {
		$sidebar_type = $meta;
	}
}

global $molla_settings;
$sidebar        = $molla_settings['sidebar'];
$sticky_sidebar = ( '' == $sidebar_type || 'top' == $sidebar['pos'] ? true : false ) && $sidebar['active'];

?>

<?php do_action( 'shop_container_before' ); ?>

<?php if ( $sticky_sidebar ) : ?>
<div class="<?php echo molla_sidebar_wrap_classes(); ?>">
	<?php
	if ( 'top' == $sidebar['pos'] ) :
		wp_enqueue_script( 'jquery-cookie' );
		$term_args = array( 'hide_empty' => true );
		if ( molla_is_shop() ) {
			$terms = get_terms( 'product_cat', $term_args );
		} else {
			$term  = get_queried_object();
			$terms = get_terms(
				'product_cat',
				array(
					'parent'     => intval( $term->term_id ),
					'hide_empty' => false,
				)
			);
		}
		$toolbox_html_escaped  = '<div class="toolbox shop-toolbox justify-content-between">';
		$toolbox_html_escaped .= '<div class="toolbox-left"><a href="#" class="filter-toggler">' . esc_html__( 'Filters', 'molla' ) . '</a></div>';
		$toolbox_html_escaped .= '<div class="toolbox-right"><ul class="nav nav-filter product-filter" style=""><li class="active"><a href="#" data-filter="*">' . esc_html__( 'All', 'molla' ) . '</a></li>';
		foreach ( $terms as $term_cat ) {
			if ( 'Uncategorized' == $term_cat->name ) {
				continue;
			}
			$id                    = $term_cat->term_id;
			$name                  = $term_cat->name;
			$slug                  = $term_cat->slug;
			$toolbox_html_escaped .= '<li class="nav-item"><a href="' . esc_url( get_term_link( $id, 'product_cat' ) ) . '" class="' . esc_attr( $slug ) . '" data-filter=".' . esc_attr( $slug ) . '">' . esc_html( $name ) . '</a></li>';
		}
		$toolbox_html_escaped .= '</ul></div>';
		$toolbox_html_escaped .= '</div>';

		echo molla_filter_output( $toolbox_html_escaped );
	endif;
endif;
?>

<?php if ( $sidebar['active'] && 'right' != $sidebar['pos'] ) : ?>
	<aside class="col-lg-3">
		<?php do_action( 'woocommerce_sidebar' ); ?>
	</aside>
<?php endif; ?>

<?php if ( $sticky_sidebar ) : ?>
	<div class="col-lg-9<?php echo esc_attr( 'top' == $sidebar['pos'] ? ' order-last' : '' ); ?>">
	<?php
endif;


do_action( 'page_content_inner_top', 'inner_top' );

if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	?>
	<?php if ( 'top' != $sidebar['pos'] && ! molla_shop_content_is_cat() ) : ?>
	<div class="toolbox">
		<?php do_action( 'woocommerce_before_shop_loop' ); ?>
	</div>
	<?php endif; ?>
	<?php
	woocommerce_product_loop_start();
	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
		wp_reset_postdata();
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	if ( 'pagination' == $loadmore ) :
		do_action( 'woocommerce_after_shop_loop' );
		// Molla Pagination
		molla_pagination( '', molla_option( 'pagination_pos' ) );
	endif;
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

do_action( 'page_content_inner_bottom', 'inner_bottom' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
if ( $sticky_sidebar ) :
	?>
	</div>
	<?php
endif;
?>
	<?php if ( $sidebar['active'] && 'right' == $sidebar['pos'] ) : ?>
	<aside class="col-lg-3">
		<?php do_action( 'woocommerce_sidebar' ); ?>
	</aside>
	<?php endif; ?>

<?php if ( $sticky_sidebar ) : ?>
</div>
	<?php
endif;
if ( $sidebar['active'] ) :
	do_action( 'molla_sidebar_control' );
endif;

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'molla_content_inner_after' );
?>
</div>
<?php

do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
