<?php
/**
 * Molla Lazy Load Images
 *
 * @author     Molla Themes
 * @category   Library
 * @since      1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_LazyLoad_Images' ) ) :
	class Molla_LazyLoad_Images {

		static function init() {
			add_action( 'wp_head', array( __CLASS__, 'setup' ), 99 );
		}
		static function setup() {
			add_filter( 'the_content', array( __CLASS__, 'img_lazyload_data' ), 9999 );
			add_filter( 'post_thumbnail_html', array( __CLASS__, 'img_lazyload_data' ), 11 );
			add_filter( 'woocommerce_product_get_image', array( __CLASS__, 'img_lazyload_data' ), 11 );
			add_filter( 'get_avatar', array( __CLASS__, 'img_lazyload_data' ), 11 );
			add_filter( 'woocommerce_single_product_image_html', array( __CLASS__, 'img_lazyload_data' ), 9999 );
			add_filter( 'woocommerce_single_product_image_thumbnail_html', array( __CLASS__, 'img_lazyload_data' ), 9999 );
			add_filter( 'molla_lazy_load_images', array( __CLASS__, 'img_lazyload_data' ), 9999 );
			add_filter( 'molla_product_hover_image_html', array( __CLASS__, 'img_lazyload_data' ), 9999 );

			wp_enqueue_script( 'jquery-lazyload' );
		}
		static function img_lazyload_data( $content ) {

			if ( is_feed() || is_preview() ) {
				return $content;
			}

			$matches = array();
			preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $matches );

			$search  = array();
			$replace = array();

			foreach ( $matches[0] as $img_html ) {
				if ( false !== strpos( $img_html, 'data-original' ) || false !== strpos( $img_html, 'data-src' ) || preg_match( "/src=['\"]data:image/is", $img_html ) ) {
					continue;
				}

				$lazy_image = get_parent_theme_file_uri( 'assets/images/lazy.png' );

				// replace the src and add the data-oi
				$replace_html = '';
				$style        = '';

				if ( preg_match( '/width=["\']/i', $img_html ) && preg_match( '/height=["\']/i', $img_html ) ) {
					preg_match( '/width=(["\'])(.*?)["\']/is', $img_html, $match_width );
					preg_match( '/height=(["\'])(.*?)["\']/is', $img_html, $match_height );
					if ( isset( $match_width[2] ) && $match_width[2] && isset( $match_height[2] ) && $match_height[2] ) {
						$style = 'padding-top: ' . round( $match_height[2] / $match_width[2] * 100 ) . '%;';
					} else {
						continue;
					}
				} else {
					continue;
				}

				$replace_html = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . esc_url( $lazy_image ) . '" data-src=', $img_html );
				$replace_html = preg_replace( '/<img(.*?)srcset=/is', '<img$1srcset="' . esc_url( $lazy_image ) . ' 100w" data-srcset=', $replace_html );
				if ( $style ) {
					if ( preg_match( '/style=["\']/i', $replace_html ) ) {
						$replace_html = preg_replace( '/style=(["\'])(.*?)["\']/is', 'style=$1' . $style . '$2$1', $replace_html );
					} else {
						$replace_html = preg_replace( '/<img/is', '<img style="' . $style . '"', $replace_html );
					}
				}

				if ( preg_match( '/class=["\']/i', $replace_html ) ) {
					$replace_html = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1molla-lazyload $2$1', $replace_html );
				} else {
					$replace_html = preg_replace( '/<img/is', '<img class="molla-lazyload"', $replace_html );
				}

				array_push( $search, $img_html );
				array_push( $replace, $replace_html );
			}

			$search  = array_unique( $search );
			$replace = array_unique( $replace );

			$content = str_replace( $search, $replace, $content );

			return $content;
		}
	}

	if ( ! is_admin() && ! is_customize_preview() && ! molla_ajax() ) {
		add_action( 'init', array( 'Molla_LazyLoad_Images', 'init' ) );
	}

	add_filter(
		'wp_lazy_loading_enabled',
		function( $default, $tag_name ) {
			if ( 'img' === $tag_name ) {
				return false;
			}
			return $default;
		},
		10,
		2
	);
endif;
