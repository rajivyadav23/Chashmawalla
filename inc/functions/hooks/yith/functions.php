<?php
/**
 * Yith Plugin Functions
 */

// remove Yith default wishlist icon
function molla_remove_yith_wcwl_button_icon() {
	return apply_filters( 'molla_yith_wcwl_button_icon', '' );
}

// change Yith Wishlist Edit title Icon
function molla_yith_wcwl_edit_title_icon() {
	return apply_filters( 'molla_yith_wcwl_edit_title_icon', '<i class="fa fa-pen"></i>' );
}

// remove added wishlist icon
function molla_remove_yith_wcwl_button_added_icon() {
	return apply_filters( 'molla_yith_wcwl_button_added_icon', '' );
}

// change browse wishlist label
function molla_browse_wishlist_label( $label ) {
	$label = '<span>' . $label . '</span>';
	return apply_filters( 'molla_browse_wishlist_label', $label );
}

if ( ! function_exists( 'molla_yith_compatible_color' ) ) :
	function molla_yith_compatible_color( $title, $instance, $id ) {
		if ( isset( $instance['type'] ) ) {
			global $wc_product_attributes;

			if ( 'color' == $instance['type'] || 'label' == $instance['type'] ) {
				$wc_product_attributes[ 'pa_' . $instance['attribute'] ]->attribute_type_tmp = $wc_product_attributes[ 'pa_' . $instance['attribute'] ]->attribute_type;
			}

			if ( 'color' == $instance['type'] ) {
				$wc_product_attributes[ 'pa_' . $instance['attribute'] ]->attribute_type = 'colorpicker';
			} elseif ( 'label' == $instance['type'] ) {
				$wc_product_attributes[ 'pa_' . $instance['attribute'] ]->attribute_type = 'label';
			}
		}
		return sanitize_text_field( $title );
	}
endif;

if ( ! function_exists( 'molla_term_name' ) ) :
	function molla_term_name( $term_name, $term ) {

		$pick_tax = array();
		$data     = '';
		$count    = '';

		if ( $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
			foreach ( $attribute_taxonomies as $tax ) {
				if ( ( 'pick' == $tax->attribute_type || ( isset( $tax->attribute_type_tmp ) && 'pick' == $tax->attribute_type_tmp ) ) && strpos( wc_attribute_taxonomy_name( $tax->attribute_name ), 'pa_' ) !== false ) {
					$pick_tax[] = wc_attribute_taxonomy_name( $tax->attribute_name );
				}
			}
		}

		if ( in_array( $term->taxonomy, $pick_tax ) ) {
			$term_id    = get_terms( array( 'slug' => $term->slug ) )[0]->term_id;
			$attr_label = get_term_meta( $term_id, 'attr_label', true );
			$attr_color = get_term_meta( $term_id, 'attr_color', true );
			if ( $attr_color ) {
				$data = ' style="background-color: ' . $attr_color . '; border-color: ' . $attr_color . ';"';
			}
		}
		$count = '<span class="item-count">' . get_terms( array( 'slug' => $term->slug ) )[0]->count . '</span>';

		$term_name = '<span class="checkbox' . ( $data ? ' custom' : '' ) . '"' . $data . '></span><span class="label">' . $term_name . '</span>' . $count;

		return $term_name;
	}
endif;

if ( ! function_exists( 'molla_enable_reset_filters' ) ) :
	function molla_enable_reset_filters() {
		return true;
	}
endif;

if ( ! function_exists( 'molla_yith_reset_filter_link' ) ) {
	function molla_yith_reset_filter_link( $link ) {
		return explode( '?', remove_query_arg( 'molla_vars' ) )[0];
	}
}

if ( ! function_exists( 'molla_woocommerce_shop_filter' ) ) :
	function molla_woocommerce_shop_filter( $args ) {
		$shop_sidebar_type = molla_option( 'shop_sidebar_type' );
		if ( ! molla_is_shop() && molla_is_in_category() ) {
			$meta = get_term_meta( get_queried_object()->term_id, 'cat_sidebar', true );
			if ( 'default' != $meta ) {
				$shop_sidebar_type = $meta;
			}
		}
		$args .= ( $shop_sidebar_type ? '<a href="#" class="filter-close">' : '' ) . '<label class="label-filter">' . ( $shop_sidebar_type ? '<i class="icon-close"></i>' : '' ) . esc_html__( 'Filters', 'molla' ). ':</label>' . ( $shop_sidebar_type ? '</a>' : '' );
		return $args;
	}
endif;


if ( ! function_exists( 'molla_single_product_wishlist_pos' ) ) :
	/**
	 * Add wishlist button position to the single variation form
	 */
	function molla_single_product_wishlist_pos( $arg ) {
		$arg['add-to-cart']       = array(
			'hook'     => 'woocommerce_after_add_to_cart_button',
			'priority' => 31,
		);
		$arg['after_add_to_cart'] = array(
			'hook'     => 'woocommerce_after_add_to_cart_button',
			'priority' => 31,
		);
		if ( ! is_user_logged_in() && molla_option( 'catalog_mode' ) && ! in_array( 'wishlist', molla_option( 'public_product_show_op' ) ) ) {
			$arg = array();
		}
		return $arg;
	}
endif;

if ( ! function_exists( 'molla_yith_wcwl_template_part' ) ) :
	function molla_yith_wcwl_template_part( $views, $template, $template_part, $template_layout, $var ) {
		$views = array(
			"wishlist-{$template}{$template_layout}{$template_part}.php",
			"wishlist-{$template}{$template_part}.php",
		);
		return $views;
	}
endif;

if ( ! function_exists( 'molla_wishlist_params' ) ) :
	function molla_wishlist_params( $additional ) {
		$additional['share_atts']['share_facebook_icon']  = '<i class="icon-facebook-f"></i>';
		$additional['share_atts']['share_twitter_icon']   = '<i class="icon-twitter"></i>';
		$additional['share_atts']['share_pinterest_icon'] = '<i class="icon-pinterest"></i>';
		$additional['share_atts']['share_email_icon']     = '<i class="icon-envelope"></i>';
		$additional['share_atts']['share_whatsapp_icon']  = '<i class="icon-whatsapp"></i>';
		return $additional;
	}
endif;

if ( ! function_exists( 'molla_yith_wcan_reset_filter_link' ) ) :
	function molla_yith_wcan_reset_filter_link() {
		return esc_url( get_permalink( wc_get_page_id( 'shop' ) ) );
	}
endif;