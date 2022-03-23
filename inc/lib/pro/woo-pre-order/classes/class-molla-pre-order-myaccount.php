<?php
/**
 * Molla WooCommerce class to show Pre-Orders tab and labels in My Account page
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Pre_Order_Myaccount' ) ) :
	class Molla_Pre_Order_Myaccount {
		/**
		 * Constructor
		 */
		public function __construct() {
			// add pre-order label to orders in orders page
			add_action( 'woocommerce_my_account_my_orders_column_order-status', array( $this, 'add_pre_order_label_on_orders_page' ) );

			// add Pre-Orders tab
			add_filter( 'woocommerce_account_menu_items', array( $this, 'add_pre_order_tab' ), 10, 2 );
			add_action( 'woocommerce_account_pre-orders_endpoint', array( $this, 'pre_orders_content' ) );
		}

		public function add_pre_order_label_on_orders_page( $order ) {
			$order = wc_get_order( $order );
			if ( 'yes' == $order->get_meta( '_molla_pre_order', true ) ) {
				echo esc_html( wc_get_order_status_name( $order->get_status() ) );
				echo '<div class="label-pre-order">' . esc_html__( 'Has Pre-Order Item', 'molla' ) . '</div>';
			} else {
				echo esc_html( wc_get_order_status_name( $order->get_status() ) );
			}
		}

		public function add_pre_order_tab( $items, $endpoints ) {
			if ( $this->get_all_pre_orders_query()->have_posts() ) {
				$logout_endpoint = $items['customer-logout'];
				unset( $items['customer-logout'] );
				$items['pre-orders']      = esc_html__( 'Pre-Orders', 'molla' );
				$items['customer-logout'] = $logout_endpoint;
			}
			return $items;
		}

		public function pre_orders_content() {
			$orders_query = $this->get_all_pre_orders_query();
			if ( $orders_query->have_posts() ) :
				?>
				<table class="shop_table molla-pre-orders responsive">
					<thead>
						<tr>
							<th class="product-thumbnail">&nbsp;</th>
							<th class="product-name"><?php esc_html_e( 'Product Name', 'molla' ); ?></th>
							<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'molla' ); ?></th>
							<th class="product-order"><?php esc_html_e( 'Order', 'molla' ); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					while ( $orders_query->have_posts() ) {
						$orders_query->the_post();
						$order = wc_get_order( get_the_ID() );
						$items = $order->get_items();
						foreach ( $items as $item_id => $item ) {
							$product = $item->get_product();
							if ( ! $product ) {
								continue;
							}
							if ( isset( $item['_molla_pre_order_item'] ) && 'yes' == $item['_molla_pre_order_item'] ) {
								echo '<tr>';
									echo '<td class="product-thumbnail">';
								if ( $product->is_visible() ) {
									echo '<a href="' . esc_url( $product->get_permalink() ) . '">' . $product->get_image( 'thumbnail' ) . '</a>';
								} else {
									echo molla_strip_script_tags( $product->get_image() );
								}
									echo '</td>';
									echo '<td class="product-name">';
									$qty          = $item->get_quantity();
									$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

								if ( $refunded_qty ) {
									$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
								} else {
									$qty_display = esc_html( $qty );
								}

									$qty_display_escaped = apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $qty_display ) . '</strong>', $item );

								if ( $product->is_visible() ) {
									echo '<a href="' . esc_url( $product->get_permalink() ) . '">' . esc_html( $product->get_title() ) . molla_filter_output( $qty_display_escaped ) . '</a>';
								} else {
									echo esc_html( $product->get_title() ) . molla_filter_output( $qty_display_escaped );
								}
									echo '<p class="molla-pre-order-date">' . $this->get_available_date_html_escaped( $item->get_product()->get_id() ) . '</p>';
								if ( $order instanceof WC_Data ) {
									wc_display_item_meta( $item );
									wc_display_item_downloads( $item );
								} else {
									$order->display_item_meta( $item );
									$order->display_item_downloads( $item );
								}
									echo '</td>';
									echo '<td>';
										echo molla_filter_output( $order->get_formatted_line_subtotal( $item ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo '</td>';
									echo '<td>';
										echo '<a href="' . esc_url( $order->get_view_order_url() ) . '">' . esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ) . '</a>';
									echo '</td>';
								echo '</tr>';
							}
						}
					}
					wp_reset_postdata();
					?>
					</tbody>
				</table>
				<?php
				wp_reset_postdata();
			endif;
		}

		private function get_all_pre_orders_query() {
			$args = array(
				'post_type'   => wc_get_order_types(),
				'post_status' => array_keys( wc_get_order_statuses() ),
				'numberposts' => - 1,
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'key'   => '_customer_user',
						'value' => get_current_user_id(),
					),
					array(
						'key'   => '_molla_pre_order',
						'value' => 'yes',
					),
				),
			);
			return new WP_Query( $args );
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
