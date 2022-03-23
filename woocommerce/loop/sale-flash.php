<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/sale-flash.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$product_style = wc_get_loop_prop( 'product_style' );
$labels        = wc_get_loop_prop( 'product_labels' );
$label_style   = wc_get_loop_prop( 'product_label_type' );


if ( molla_is_product() || apply_filters( 'molla_is_single_product_widget', false ) ) {
	$label_style = molla_option( 'product_label_type' );
}

if ( '' === $labels ) {
	$labels = molla_option( 'product_labels' );
}

if ( ! is_array( $labels ) ) {
	return;
}

if ( 'circle' == $label_style ) {
	$label_class = 'product-label label-circle';
} elseif ( 'polygon' == $label_style ) {
	$label_class = 'product-label label-polygon';
} else {
	$label_class = 'product-label';
}

echo '<div class="product-labels">';

foreach ( $labels as $label ) {
	if ( 'featured' == $label && $product->is_featured() ) {
		echo apply_filters(
			'molla_woocommerce_featured_flash',
			'<span class="' . $label_class . ' label-hot">' . esc_html__( 'Top', 'molla' ) . '</span>',
			$post,
			$product
		);
	}
	if ( 'onsale' == $label && $product->is_on_sale() ) {
		$sales_html = esc_html__( 'Sale', 'molla' );

		$label_sale_format = molla_option( 'label_sale_format' );

		if ( $label_sale_format && false !== strpos( $label_sale_format, '%s' ) ) {
			$percent = 0;
			if ( $product->get_regular_price() ) {
				$percent = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
			} elseif ( 'variable' == $product->get_type() && $product->get_variation_regular_price() ) {
				$percent = round( ( ( $product->get_variation_regular_price() - $product->get_variation_sale_price() ) / $product->get_variation_regular_price() ) * 100 );
			}

			$sales_html = str_replace( '%s', $percent, $label_sale_format );
		}

		echo apply_filters( 'molla_woocommerce_sale_flash', '<span class="' . $label_class . ' label-sale">' . esc_html( $sales_html ) . '</span>', $post, $product );
	}
	if ( 'outstock' == $label && ! wc_get_loop_prop( 'out_stock_style' ) ) {
		if ( null == $product->get_stock_quantity() && 'outofstock' == $product->get_stock_status() ) {
			// if ( 'polygon' == $label_style ) {
			// 	$out_html = 'Sold Out';
			// } elseif ( 'circle' == $label_style ) {
			// 	$out_html = 'Out';
			// } else {
			// 	$out_html = 'Out of Stock';
			// }
			// translators: %s represents out stock html
			//          echo apply_filters( 'molla_woocommerce_outstock_flash', '<span class="' . $label_class . ' label-out">' . sprintf( esc_html( '%s', 'molla' ), $out_html ) . '</span>', $post, $product );
			echo apply_filters( 'molla_woocommerce_outstock_flash', '<span class="' . $label_class . ' label-out">' . ( $label_style ? esc_html__( 'Out', 'molla' ) : esc_html__( 'Out of Stock', 'molla' ) ) . '</span>', $post, $product );
		} elseif ( $product->get_stock_quantity() && 'polygon' == $label_style ) {
			if ( 0 == $product->get_stock_quantity() ) {
				echo apply_filters( 'molla_woocommerce_outstock_flash', '<span class="' . $label_class . ' label-out">' . esc_html__( 'Sold Out', 'molla' ) . '</span>', $post, $product );
			} elseif ( $product->get_stock_quantity() < 5 ) {
				// translators: %d represents left quantity
				echo apply_filters( 'molla_woocommerce_outstock_flash', '<span class="' . $label_class . ' label-out">' . sprintf( esc_html__( 'Only %d Left', 'molla' ), $product->get_stock_quantity() ) . '</span>', $post, $product );
			}
		}
	}
	if ( 'new' == $label && strtotime( $product->get_date_created() ) > strtotime( apply_filters( 'molla_new_product_period', '-' . (int) molla_option( 'new_product_period' ) . ' day' ) ) ) {
		echo apply_filters( 'molla_woocommerce_new_flash', '<span class="' . $label_class . ' label-new">' . esc_html__( 'New', 'molla' ) . '</span>', $post, $product );
	}
	if ( 'hurry' == $label && molla_product_is_in_low_stock() ) {
		echo apply_filters( 'molla_woocommerce_hurry_flash', '<span class="' . $label_class . ' label-hurry">' . esc_html__( 'Hurry!', 'molla' ) . '</span>', $post, $product );
	}
}

$custom_labels = json_decode( get_post_meta( $product->get_id(), 'molla_custom_labels', true ), true );

if ( is_array( $custom_labels ) ) {

	foreach ( $custom_labels as $custom_label ) {
		$style_escaped = ' style=" ';
		$html          = '';
		if ( ! empty( $custom_label['color'] ) ) {
			$style_escaped .= 'color:' . esc_attr( $custom_label['color'] ) . ';';
		} else {
			$style_escaped .= 'color: #fff;';
		}
		if ( ! empty( $custom_label['bgColor'] ) ) {
			$style_escaped .= 'background-color:' . esc_attr( $custom_label['bgColor'] . ';' );
			$style_escaped .= 'border-color:' . esc_attr( $custom_label['bgColor'] );
		} else {
			$style_escaped .= 'background-color: ' . esc_attr( molla_option( 'primary_color' ) . ';' );
			$style_escaped .= 'border-color: ' . esc_attr( molla_option( 'primary_color' ) );
		}
		$style_escaped .= '"';
		$html          .= '<span class="' . $label_class . ' label-custom"' . $style_escaped . '>';
		$html          .= molla_strip_script_tags( $custom_label['label'] );
		$html          .= '</span>';
		echo molla_filter_output( $html );
	}
}

echo '</div>';

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
