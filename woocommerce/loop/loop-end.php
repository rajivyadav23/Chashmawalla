<?php
/**
 * Product Loop End
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-end.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( wc_get_loop_prop( 'is_custom_creative' ) ) :
	?>
	<div class="grid-space"></div>
	<?php
endif;

if ( isset( $GLOBALS['molla_loop_index'] ) ) {
	$insert_id       = wc_get_loop_prop( 'banner_insert' );
	$repeaters       = wc_get_loop_prop( 'repeaters' );
	$current_id      = $GLOBALS['molla_loop_index'];
	$wrap_attr       = ' data-grid-idx=' . $current_id;
	$wrap_class_temp = '';
	if ( isset( $repeaters['ids'][ $current_id ] ) ) {
		$wrap_class_temp = ' ' . $repeaters['ids'][ $current_id ];
	}

	// Print banner in products
	if ( 'last' == $insert_id || (int) $insert_id >= $current_id ) {
		$html = wc_get_loop_prop( 'product_banner' );
		if ( $html ) {
			$wrap_class = 'product-wrap';
			$add_class  = $wrap_class_temp . ' product-banner-wrap';

			if ( isset( $repeaters['ids'][0] ) ) {
				$add_class .= ' ' . $repeaters['ids'][0];
			}

			echo '<div class="' . esc_attr( $wrap_class . $add_class ) . '"' . $wrap_attr . '>' . $html . '</div>';

			$GLOBALS['molla_loop_index'] ++;
			wc_set_loop_prop( 'product_banner', '' );
		}
	}
}

?>
</div>

<?php
$label    = wc_get_loop_prop( 'view_more_label' );
$icon     = wc_get_loop_prop( 'view_more_icon' );
$loadmore = wc_get_loop_prop( 'load_more' );

global $molla_settings;

if ( class_exists( 'WooCommerce' ) && molla_is_shop() || molla_is_in_category() ) {

	$extra_atts = array();
	$loadmore   = molla_option( 'shop_view_more_type' );

	$sidebar_pos = $molla_settings['sidebar']['pos'];

	if ( molla_is_shop() ) {
		if ( 'top' == $sidebar_pos ) {
			$extra_atts['columns'] = molla_option( 'catalog_columns' );
		}
	} else {
		if ( 'top' == $sidebar_pos ) {
			$term                   = get_queried_object();
			$extra_atts['columns']  = get_term_meta( $term->term_id, 'product_columns', true );
			$extra_atts['category'] = $term->term_id;
		}
	}

	if ( 'pagination' != $loadmore ) {

		$extra_atts['per_page'] = molla_option( 'woocommerce_catalog_columns' );

		$label = esc_html__( 'MORE PRODUCTS', 'molla' );
		$icon  = 'icon-refresh';

		wc_set_loop_prop( 'extra_atts', $extra_atts );
	}
}


if ( ( molla_is_shop() || molla_is_in_category() ) && ! wc_get_loop_prop( 'widget' ) ) { // shop, category
	if ( molla_is_shop() ) {
		$elem = get_option( 'woocommerce_shop_page_display' );
	} else {
		$elem = get_option( 'woocommerce_category_archive_display' );
	}
	if ( '' != $elem ) {
		$loadmore = false;
	}
}

if ( $loadmore && 'pagination' != $loadmore && $GLOBALS['woocommerce_loop']['current_page'] < $GLOBALS['woocommerce_loop']['total_pages'] ) :
	?>
	<div class="more-container text-center">
	<?php
		echo apply_filters( 'molla_more_product_html', '<a href="#" class="btn btn-more more-product' . ( 'scroll' == $loadmore ? ' d-none' : '' ) . '"><span>' . $label . '</span>' . ( $icon ? ( '<i class="' . esc_attr( $icon ) . '"></i>' ) : '' ) . '</a>' );
	?>
	</div>
	<?php
endif;
