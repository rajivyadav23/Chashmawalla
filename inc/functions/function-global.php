<?php

/**
 * Get Molla option
 *
 * @deprecated in favor of get_theme_mod()
 *
 * @return string
 */
function molla_option( $option ) {
	// Get options
	$ret = get_theme_mod( $option, -1 );
	if ( -1 === $ret ) {
		$ret = molla_defaults( $option );
	}
	return $ret;
}

// filtering output
function molla_filter_output( $output_escaped ) {
	return $output_escaped;
}

// load view
function molla_get_template_part( $slug, $name = null, $args = array() ) {
	global $post;

	if ( empty( $args ) ) {
		return get_template_part( $slug, $name );
	}

	if ( is_array( $args ) ) {
		extract( $args ); // @codingStandardsIgnoreLine
	}

	$templates = array();
	$name      = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "{$slug}-{$name}.php";
	}
	$templates[] = "{$slug}.php";
	$template    = locate_template( $templates );
	$template    = apply_filters( 'molla_get_template_part', $template, $slug, $name );

	if ( $template ) {
		include $template;
	}
}

function molla_sidebar_wrap_classes() {
	$class = 'row sidebar-wrapper';
	if ( molla_option( 'sidebar_sticky' ) ) {
		$class .= ' sticky-sidebar-wrapper';
	}
	return apply_filters( 'molla_sidebar_wrapper_class', $class );
}

function molla_sidebar_classes() {
	global $molla_settings;
	if ( ! isset( $molla_settings['sidebar']['builder'] ) ) {
		if ( class_exists( 'WooCommerce' ) && ( molla_is_shop() || molla_is_in_category() ) ) {
			if ( molla_is_shop() ) {
				if ( molla_option( 'shop_sidebar_type' ) && ( ! molla_option( 'shop_page_layout' ) || 'top' != molla_option( 'shop_sidebar_pos' ) ) ) {
					return ' shop-sidebar sidebar-toggle';
				}
			} else {
				$sidebar_type = molla_option( 'shop_sidebar_type' );
				if ( molla_option( 'shop_page_layout' ) ) {
					$sidebar_pos = molla_option( 'shop_sidebar_pos' );
				} else {
					$sidebar_pos = molla_option( 'sidebar_option' );
				}
				$meta_sidebar_type = get_term_meta( get_queried_object()->term_id, 'cat_sidebar', true );
				$meta_sidebar_pos  = get_term_meta( get_queried_object()->term_id, 'cat_sidebar_pos', true );
				if ( 'default' != $meta_sidebar_type ) {
					$sidebar_type = $meta_sidebar_type;
				}
				if ( 'default' != $meta_sidebar_pos ) {
					$sidebar_pos = $meta_sidebar_pos;
				}
				if ( $sidebar_type && 'top' != $sidebar_pos ) {
					return ' shop-sidebar sidebar-toggle';
				}
			}
			if ( molla_option( 'sidebar_sticky' ) ) {
				return ' shop-sidebar sticky-sidebar';
			}
			return ' shop-sidebar';
		}
	}
	if ( molla_option( 'sidebar_sticky' ) ) {
		return ' sticky-sidebar';
	}
	return '';
}

function molla_google_fonts() {
	return apply_filters(
		'molla_google_font_settings',
		array(
			'font_base',
			'font_heading_h1',
			'font_heading_h2',
			'font_heading_h3',
			'font_heading_h4',
			'font_heading_h5',
			'font_heading_h6',
			'font_paragraph',
			'font_nav',
			'font_placeholder',
			'font_custom_1',
			'font_custom_2',
			'font_custom_3',
			'footer_font',
			'footer_font_heading',
			'footer_font_paragraph',
			'skin1_menu_ancestor_font',
			'skin1_menu_subtitle_font',
			'skin1_menu_item_font',
			'skin2_menu_ancestor_font',
			'skin2_menu_subtitle_font',
			'skin2_menu_item_font',
			'skin3_menu_ancestor_font',
			'skin3_menu_subtitle_font',
			'skin3_menu_item_font',
			'font_product_cat',
			'font_product_title',
			'font_product_price',
		)
	);
}

if ( ! function_exists( 'molla_is_elementor_preview' ) ) :
	function molla_is_elementor_preview() {
		return defined( 'ELEMENTOR_VERSION' ) && (
				( isset( $_REQUEST['action'] ) && ( 'elementor' == $_REQUEST['action'] || 'elementor_ajax' == $_REQUEST['action'] ) ) ||
				isset( $_REQUEST['elementor-preview'] )
			);
	}
endif;

add_filter( 'body_class', 'molla_body_class' );
if ( ! function_exists( 'molla_body_class' ) ) :
	function molla_body_class( $classes ) {
		$body_layout = molla_option( 'body_layout' );
		if ( 'full-width' != $body_layout ) {
			$classes[] = $body_layout;
		}
		if ( class_exists( 'WooCommerce' ) && molla_is_product() ) {
			$classes[] = 'single-product';
		}
		if ( is_customize_preview() ) {
			$classes[] = 'customize-preview';
		}
		return $classes;
	}
endif;

function molla_is_shop() {
	if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_tag() ) ) {
		return true;
	}

	return apply_filters( 'molla_is_shop', isset( $_POST['shop'] ) );
}

function molla_is_product() {
	if ( class_exists( 'WooCommerce' ) && is_product() ) {
		return true;
	}

	return apply_filters( 'molla_is_product', false );
}

function molla_is_in_category() {
	return ( is_archive() && ( 'product' == get_post_type() || 'product_cat' == get_post_type() ) ) || ( class_exists( 'WooCommerce' ) && is_product_category() );
}

function molla_shop_content_is_cat() {
	if ( molla_is_shop() ) {
		if ( 'subcategories' == get_option( 'woocommerce_shop_page_display' ) ) {
			return true;
		}
	} elseif ( molla_is_in_category() ) {
		if ( 'subcategories' == get_option( 'woocommerce_category_archive_display' ) ) {
			return true;
		}
	}
	return false;
}
