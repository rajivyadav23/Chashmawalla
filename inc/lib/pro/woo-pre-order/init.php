<?php
/**
 * Molla WooCommerce Pre-Order Initialize
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Woo_Pre_Order' ) ) :

	class Molla_Woo_Pre_Order {

		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ), 1001 );

			add_action( 'admin_init', array( $this, 'init_admin' ) );

			add_action( 'wp', array( $this, 'init_view' ) );

			add_action( 'init', array( $this, 'add_myaccount_pre_orders_endpoints' ), 1 );

			// add pro_order products for pre_order elementor wc shortcode.
			add_filter( 'woocommerce_shortcode_products_query', array( $this, 'add_pre_order_items_wc_query' ), 10, 3 );
		}

		public function init_admin() {
			require_once MOLLA_PRO_LIB . '/woo-pre-order/classes/class-molla-pre-order-admin.php';
			new Molla_Pre_Order_Admin;
		}

		public function init_view() {
			if ( ! is_admin() || wp_doing_ajax() ) {
				require_once MOLLA_PRO_LIB . '/woo-pre-order/classes/class-molla-pre-order-view.php';
				new Molla_Pre_Order_View;
			}
			if ( is_account_page() ) {
				require_once MOLLA_PRO_LIB . '/woo-pre-order/classes/class-molla-pre-order-myaccount.php';
				new Molla_Pre_Order_Myaccount;
			}
		}

		public function enqueue_admin() {
			wp_enqueue_script( 'molla-pre-order-admin', MOLLA_PRO_LIB_URI . '/woo-pre-order/pre-order-admin.js', array( 'molla-admin' ), MOLLA_VERSION, true );
		}

		public function add_myaccount_pre_orders_endpoints() {
			add_rewrite_endpoint( 'pre-orders', EP_ROOT | EP_PAGES );
		}

		public function add_pre_order_items_wc_query( $query_args, $attribute, $type ) {
			if ( 'pre_order' == $attribute['visibility'] ) {
				$query_args['meta_key']   = '_molla_pre_order';
				$query_args['meta_value'] = 'yes';
			}
			return $query_args;
		}
	}

	new Molla_Woo_Pre_Order;
endif;
