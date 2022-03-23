<?php

/**
 * Molla_Product_Data_Addons class
 *
 * @version 1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Molla_Product_Data_Addons' ) ) {
	class Molla_Product_Data_Addons {

		public function __construct() {
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_data_tab' ), 101 );
			add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_data_panel' ), 99 );

			// Save 'molla Extra Options'
			add_action( 'wp_ajax_molla_save_product_extra_options', array( $this, 'save_extra_options' ) );
			add_action( 'wp_ajax_nopriv_molla_save_product_extra_options', array( $this, 'save_extra_options' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1001 );
		}

		public function add_product_data_tab( $tabs ) {
			$tabs['molla_data_addons'] = array(
				'label'    => esc_html__( 'Molla Extra Options', 'molla' ),
				'target'   => 'molla_data_addons',
				'priority' => 90,
			);
			return $tabs;
		}

		public function add_product_data_panel() {
			global $thepostid;
			?>
			<div id="molla_data_addons" class="panel woocommerce_options_panel wc-metaboxes-wrapper hidden">
				<div class="options_group">
					<div class="wc-metabox wc-metabox-template" style="display: none;">
						<h3>
							<select class="custom_label_type" class="molla_label_type" name="molla_label_type" hidden>
								<option value=""><?php esc_html_e( 'Text', 'molla' ); ?></option>
								<option value="image"><?php esc_html_e( 'Image', 'molla' ); ?></option>
							</select>
							<div class="text-controls">
								<input type="text" placeholder="Label" class="label_text"  name="label_text">
								<label><?php esc_html_e( 'Color:', 'molla' ); ?></label>
								<input type="text" class="label-color-picker label_color" name="label_color" value="">
								<label><?php esc_html_e( 'Bg Color:', 'molla' ); ?></label>
								<input type="text" class="label-color-picker label_bgcolor" name="label_bgcolor" value="">
							</div>
							<div class="image-controls" style="display: none;">
								<input type="text" class="label_image" name="label_image" value="">
								<input class="btn_upload_img button" type="button" value="Upload Image">
								<input type="text" class="label_image_id" name="label_image_id" value="" hidden>
							</div>
							<a href="#" class="delete"><?php esc_html_e( 'Remove', 'molla' ); ?></a>
						</h3>
					</div>
					<div class="form-field molla_custom_labels">
						<label><?php esc_html_e( 'Custom Labels', 'molla' ); ?></label>
						<button type="button" class="button add_custom_label" id="molla_add_custom_label"><?php esc_html_e( 'Add a Label', 'molla' ); ?></button>
						<?php echo wc_help_tip( __( 'Add custom labels for this product. Custom labels will be shown just after theme supported labels.', 'molla' ) ); ?>
						<div class="wc-metaboxes ui-sortable">
						<?php
						$molla_custom_labels = get_post_meta( $thepostid, 'molla_custom_labels', true );
						$molla_custom_labels = json_decode( $molla_custom_labels, true );
						if ( is_array( $molla_custom_labels ) && count( $molla_custom_labels ) ) :
							foreach ( $molla_custom_labels as $custom_label ) :
								?>
								<div class="wc-metabox wc-metabox-template">
									<h3>
										<select class="custom_label_type molla_label_type" name="molla_label_type" hidden>
											<option value="" <?php selected( $custom_label['type'], '' ); ?>><?php esc_html_e( 'Text', 'molla' ); ?></option>
											<option value="image" <?php selected( $custom_label['type'], 'image' ); ?>><?php esc_html_e( 'Image', 'molla' ); ?></option>
										</select>
										<div class="text-controls" <?php echo ( ! $custom_label['type'] ? '' : 'style="display: none;"' ); ?>>
											<input type="text" placeholder="Label" class="label_text"  name="label_text" value="<?php echo ( isset( $custom_label['label'] ) ? esc_attr( $custom_label['label'] ) : '' ); ?>">
											<label><?php esc_html_e( 'Color:', 'molla' ); ?></label>
											<input type="text" class="label-color-picker" name="label_color" value="<?php echo ( isset( $custom_label['color'] ) ? esc_attr( $custom_label['color'] ) : '' ); ?>">
											<label><?php esc_html_e( 'Bg Color:', 'molla' ); ?></label>
											<input type="text" class="label-color-picker" name="label_bgcolor" value="<?php echo ( isset( $custom_label['bgColor'] ) ? esc_attr( $custom_label['bgColor'] ) : '' ); ?>">
										</div>
										<div class="image-controls" <?php echo ( ! $custom_label['type'] ? 'style="display: none;"' : '' ); ?>>
											<input type="text" class="label_image" name="label_image" value="<?php echo ( isset( $custom_label['img_url'] ) ? esc_attr( $custom_label['img_url'] ) : '' ); ?>">
											<input class="btn_upload_img button" type="button" value="Upload Image">
											<input type="text" class="label_image_id" name="label_image_id" value="<?php echo ( isset( $custom_label['img_id'] ) ? esc_attr( $custom_label['img_id'] ) : '' ); ?>" hidden>
										</div>
										<a href="#" class="delete"><?php esc_html_e( 'Remove', 'molla' ); ?></a>
									</h3>
								</div>
								<?php
							endforeach;
						endif;
						?>
						</div>
					</div>
				</div>

				<div class="toolbar">
					<button type="button" class="button save_molla_product_options button-primary"><?php esc_html_e( 'Save options', 'molla' ); ?></button>
				</div>
			</div>
			<?php
		}

		public function enqueue_scripts() {
			wp_enqueue_script( 'molla-product-data-addons', MOLLA_PRO_LIB_URI . '/product-data-addons/product-data-addons-admin.js', array(), MOLLA_VERSION, true );
			//	wp_enqueue_script( 'molla-admin-walker', MOLLA_JS . '/admin/walker.min.js', array( 'jquery-core' ), MOLLA_VERSION, true );
			wp_localize_script(
				'molla-product-data-addons',
				'molla_product_data_addon_vars',
				array(
					'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
					'post_id'  => get_the_ID(),
					'nonce'    => wp_create_nonce( 'molla-product-editor' ),
				)
			);
		}

		public function save_extra_options() {
			if ( ! check_ajax_referer( 'molla-product-editor', 'nonce', false ) ) {
				wp_send_json_error( 'invalid_nonce' );
			}
			$post_id = $_POST['post_id'];

			// Save custom labels
			$molla_custom_labels = isset( $_POST['molla_custom_labels'] ) ? $_POST['molla_custom_labels'] : '';
			if ( count( $molla_custom_labels ) ) {
				update_post_meta( $post_id, 'molla_custom_labels', wp_slash( json_encode( $molla_custom_labels ) ) );
			} else {
				delete_post_meta( $post_id, 'molla_custom_labels' );
			}

			// // Save virtual buy time
			// $molla_virtual_buy_time = isset( $_POST['molla_virtual_buy_time'] ) ? molla_strip_script_tags( $_POST['molla_virtual_buy_time'] ) : '';
			// if ( $molla_virtual_buy_time ) {
			// 	update_post_meta( $post_id, 'molla_virtual_buy_time', $molla_virtual_buy_time );
			// } else {
			// 	delete_post_meta( $post_id, 'molla_virtual_buy_time' );
			// }

			// Save molla virtual buy time text
			// $molla_virtual_buy_time_text = isset( $_POST['molla_virtual_buy_time_text'] ) ? molla_strip_script_tags( $_POST['molla_virtual_buy_time_text'] ) : '';
			// if ( $molla_virtual_buy_time_text ) {
			// 	update_post_meta( $post_id, 'molla_virtual_buy_time_text', $molla_virtual_buy_time_text );
			// } else {
			// 	delete_post_meta( $post_id, 'molla_virtual_buy_time_text' );
			// }

			wp_send_json_success();
			die();
		}
	}
}

new Molla_Product_Data_Addons;
