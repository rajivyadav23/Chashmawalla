<?php
/**
 * Molla WooCommerce class to show Pre-Order buttons on shop and product pages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Pre_Order_View' ) ) :
	class Molla_Pre_Order_View {
		public function __construct() {
			if ( ! molla_option( 'woo_pre_order' ) ) {
				return;
			}

			// add pre-order label to the "add to cart" buttons and cart item
			add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'pre_order_label' ), 10, 2 );
			add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'pre_order_label' ), 20, 2 );
			add_action( 'woocommerce_after_cart_item_name', array( $this, 'pre_order_product_cart_label' ), 75 );
			add_action( 'molla_woocommerce_minicart_after_product_name', array( $this, 'pre_order_product_cart_label' ) );

			// add molla-pre-order css class to the "add to cart" link
			add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_pre_order_class' ), 10, 2 );

			// display pre order available date
			add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'display_pre_order_date' ) );
			add_filter( 'woocommerce_available_variation', array( $this, 'add_variable_pre_order_data' ), 10, 3 );

			// add pre_order status to the order item
			add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'add_pre_order_status' ), 10, 2 );
			add_action( 'woocommerce_new_order_item', array( $this, 'add_order_item_meta' ), 10, 2 );

			add_action( 'woocommerce_order_item_meta_start', array( $this, 'add_pre_order_label_on_single_order_page' ), 10, 3 );
		}

		public function pre_order_label( $text, $product ) {
			if ( $product->is_in_stock() && 'yes' == get_post_meta( $product->get_id(), '_molla_pre_order', true ) ) {
				$pre_order_label = molla_option( 'woo_pre_order_label' );
				return empty( $pre_order_label ) ? esc_html__( 'Pre-Order Now', 'molla' ) : esc_html( $pre_order_label );
			}

			return $text;
		}

		public function pre_order_product_cart_label( $cart_item ) {
			if ( is_a( $cart_item, 'WC_Product' ) ) {
				$product_id = $cart_item->get_id();
			} else {
				$product_id = ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'];
			}
			if ( 'yes' != get_post_meta( $product_id, '_molla_pre_order', true ) ) {
				return;
			}
			echo '<div class="label-pre-order">' . esc_html__( 'Pre-Ordered', 'molla' ) . '</div>';
		}

		public function display_pre_order_date() {
			global $product;
			if ( $product->is_type( 'simple' ) && 'yes' == get_post_meta( $product->get_id(), '_molla_pre_order', true ) ) {
				$this->get_available_date_html_escaped( $product->get_id(), true );
			}
		}

		public function add_variable_pre_order_data( $vars, $self, $variation ) {
			if ( 'yes' == get_post_meta( $variation->get_id(), '_molla_pre_order', true ) ) {

				$pre_order_label               = molla_option( 'woo_pre_order_label' );
				$available_date_escaped        = $this->get_available_date_html_escaped( $variation->get_id() );
				$vars['molla_pre_order']       = true;
				$vars['molla_pre_order_label'] = empty( $pre_order_label ) ? esc_js( esc_html__( 'Pre-Order Now', 'molla' ) ) : esc_js( $pre_order_label );

				if ( $available_date_escaped ) {
					$vars['molla_pre_order_date'] = '<p class="molla-pre-order-date">' . $available_date_escaped . '</p>';
				}
			}
			return $vars;
		}

		public function add_pre_order_class( $link, $product ) {
			if ( $product->is_purchasable() && $product->is_in_stock() && 'yes' == get_post_meta( $product->get_id(), '_molla_pre_order', true ) ) {
				return str_replace( 'add_to_cart_button', 'add_to_cart_button molla-pre-order', $link );
			}
			return $link;
		}

		public function add_pre_order_status( $order_id, $post ) {
			$order = wc_get_order( $order_id );
			$items = $order->get_items();
			foreach ( $items as $key => $item ) {
				if ( ! empty( $item['variation_id'] ) ) {
					$product_id = $item['variation_id'];
				} else {
					$product_id = $item['product_id'];
				}

				if ( 'yes' == get_post_meta( $product_id, '_molla_pre_order', true ) ) {
					update_post_meta( $order_id, '_molla_pre_order', 'yes' );
					$product = wc_get_product( $product_id );

					/* translators: Order Item name */
					$order->add_order_note( sprintf( esc_html__( 'Item %s is Pre-Ordered', 'molla' ), esc_html( $product->get_formatted_name() ) ) );

					break;
				}
			}
		}

		public function add_order_item_meta( $item_id, $item ) {
			if ( 'line_item' != $item->get_type() ) {
				return;
			}
			$product = $item->get_product();
			if ( ! $product ) {
				return;
			}
			if ( 'yes' == get_post_meta( $product->get_id(), '_molla_pre_order', true ) ) {
				wc_add_order_item_meta( $item_id, '_molla_pre_order_item', 'yes' );
				$date = get_post_meta( $product->get_id(), '_molla_pre_order_date', true );
				if ( $date ) {
					wc_add_order_item_meta( $item_id, '_molla_pre_order_item_date', $date );
				}
			}
		}

		public function add_pre_order_label_on_single_order_page( $item_id, $item, $order ) {
			if ( isset( $item['molla_pre_order_item'] ) && 'yes' == $item['molla_pre_order_item'] ) {
				echo '<div class="label-pre-order">' . esc_html__( 'Pre-Ordered', 'molla' ) . ' (' . $this->get_available_date_html_escaped( $item->get_product()->get_id() ) . ')</div>';
			}
		}

		private function get_available_date_html_escaped( $product_id, $echo = false ) {
			$available_date = get_post_meta( $product_id, '_molla_pre_order_date', true );
			if ( $available_date && strtotime( $available_date ) + 24 * HOUR_IN_SECONDS >= current_time( 'timestamp' ) ) {
				$available_date = date_i18n( wc_date_format(), strtotime( $available_date ) );

				/* translators: available date */
				$msg_date               = molla_option( 'woo_pre_order_msg_date' );
				$available_date_escaped = sprintf( esc_js( empty( $msg_date ) ? esc_html__( 'Available Date: %s', 'molla' ) : $msg_date ), '<span>' . esc_js( apply_filters( 'molla_pre_order_available_date', $available_date, $product_id ) ) . '</span>' );
			} else {
				$msg_nodate             = molla_option( 'woo_pre_order_msg_nodate' );
				$available_date_escaped = empty( $msg_nodate ) ? '' : esc_js( $msg_nodate );
			}
			if ( $available_date_escaped ) {
				if ( $echo ) {
					echo '<p class="molla-pre-order-date">' . $available_date_escaped . '</p>';
				} else {
					return $available_date_escaped;
				}
			}
			return false;
		}
	}
endif;
