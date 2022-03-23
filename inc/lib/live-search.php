<?php
/**
 * Molla Live Search
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Live_Search' ) ) :

	class Molla_Live_Search {
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'add_script' ) );
			add_action( 'wp_ajax_molla_ajax_search_posts', array( $this, 'ajax_search' ) );
			add_action( 'wp_ajax_nopriv_molla_ajax_search_posts', array( $this, 'ajax_search' ) );
		}

		public function add_script() {
			wp_enqueue_script( 'molla-live-search', MOLLA_JS . '/live-search.min.js', false, MOLLA_VERSION, true );
			wp_localize_script(
				'molla-live-search',
				'molla_live_search',
				array(
					'ajax_url' => esc_js( admin_url( 'admin-ajax.php' ) ),
					'nonce'    => wp_create_nonce( 'molla-live-search-nonce' ),
				)
			);
		}

		public function ajax_search() {
			check_ajax_referer( 'molla-live-search-nonce', 'nonce' );

			$query  = apply_filters( 'molla_ajax_search_query', sanitize_text_field( $_REQUEST['query'] ) );
			$posts  = array();
			$result = array();
			$args   = array(
				's'                   => $query,
				'orderby'             => '',
				'post_status'         => 'publish',
				'posts_per_page'      => 50,
				'ignore_sticky_posts' => 1,
				'post_password'       => '',
				'suppress_filters'    => false,
			);

			if ( isset( $_REQUEST['post_type'] ) ) {
				if ( class_exists( 'WooCommerce' ) && 'post' != $_REQUEST['post_type'] ) {
					$posts = $this->search_products( 'product', $args );
					$posts = array_merge( $posts, $this->search_products( 'sku', $args ) );
					$posts = array_merge( $posts, $this->search_products( 'tag', $args ) );
				}
				if ( 'product' != $_REQUEST['post_type'] ) {
					$posts = array_merge( $posts, $this->search_posts( $args, $query ) );
				}
			}

			foreach ( $posts as $post ) {
				if ( class_exists( 'WooCommerce' ) && ( 'product' === $post->post_type || 'product_variation' === $post->post_type ) ) {
					$product       = wc_get_product( $post );
					$product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ) );

					$result[] = array(
						'type'  => 'Product',
						'id'    => $product->get_id(),
						'value' => $product->get_title(),
						'url'   => esc_url( $product->get_permalink() ),
						'img'   => esc_url( $product_image[0] ),
						'price' => $product->get_price_html(),
					);
				} else {
					$result[] = array(
						'type'  => get_post_type( $post->ID ),
						'id'    => $post->ID,
						'value' => get_the_title( $post->ID ),
						'url'   => esc_url( get_the_permalink( $post->ID ) ),
						'img'   => esc_url( get_the_post_thumbnail_url( $post->ID, 'thumbnail' ) ),
						'price' => '',
					);
				}
			}

			wp_send_json( array( 'suggestions' => $result ) );
		}

		private function search_posts( $args, $query, $post_type = array( 'post' ) ) {
			$args['s']         = $query;
			$args['post_type'] = apply_filters( 'molla_ajax_search_post_type', $post_type );
			$args              = $this->search_add_category_args( $args );

			$search_query   = http_build_query( $args );
			$search_funtion = apply_filters( 'molla_ajax_search_function', 'get_posts', $search_query, $args );

			return ( 'get_posts' === $search_funtion || ! function_exists( $search_funtion ) ? get_posts( $args ) : $search_funtion( $search_query, $args ) );
		}

		private function search_products( $search_type, $args ) {
			$args['post_type']  = 'product';
			$args['meta_query'] = WC()->query->get_meta_query(); // WPCS: slow query ok.
			$args               = $this->search_add_category_args( $args );

			switch ( $search_type ) {
				case 'product':
					$args['s'] = apply_filters( 'molla_ajax_search_products_query', sanitize_text_field( $_REQUEST['query'] ) );
					break;
				case 'sku':
					$query                = apply_filters( 'molla_ajax_search_products_by_sku_query', sanitize_text_field( $_REQUEST['query'] ) );
					$args['s']            = '';
					$args['post_type']    = array( 'product', 'product_variation' );
					$args['meta_query'][] = array(
						'key'   => '_sku',
						'value' => $query,
					);
					break;
				case 'tag':
					$args['s']           = '';
					$args['product_tag'] = apply_filters( 'molla_ajax_search_products_by_tag_query', sanitize_text_field( $_REQUEST['query'] ) );
					break;
			}

			$search_query   = http_build_query( $args );
			$search_funtion = apply_filters( 'molla_ajax_search_function', 'get_posts', $search_query, $args );

			return 'get_posts' === $search_funtion || ! function_exists( $search_funtion ) ? get_posts( $args ) : $search_funtion( $search_query, $args );
		}

		private function search_add_category_args( $args ) {
			if ( isset( $_REQUEST['cat'] ) && $_REQUEST['cat'] && '0' != $_REQUEST['cat'] ) {
				if ( 'product' == molla_option( 'search_content_type' ) ) {
					$args['tax_query']   = array();
					$args['tax_query'][] = array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => sanitize_text_field( $_REQUEST['cat'] ),
					);
				} elseif ( 'post' == molla_option( 'search_content_type' ) ) {
					$args['category'] = get_terms( array( 'slug' => sanitize_text_field( $_REQUEST['cat'] ) ) )[0]->term_id;
				}
			}
			return $args;
		}
	}
	new Molla_Live_Search;
endif;
