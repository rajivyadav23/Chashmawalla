<?php

if ( ! class_exists( 'Molla_Image_Swatch_Tab' ) ) {
	class Molla_Image_Swatch_Tab {

		public function __construct() {
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_data_tab' ), 99 );
			add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_data_panel' ), 99 );
			add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_meta' ), 1, 2 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1001 );
		}

		public function add_product_data_tab( $tabs ) {
			$tabs['swatch'] = array(
				'label'    => esc_html__( 'Image Swatch', 'molla' ),
				'target'   => 'swatch_product_options',
				'class'    => array( 'show_if_variable' ),
				'priority' => 80,
			);
			return $tabs;
		}

		public function add_product_data_panel() {
			global $product_object;

			$attrs = array_filter(
				$product_object->get_attributes(),
				function( $attr ) {
					return true === $attr->get_variation();
				}
			);

			$swatch_options = wc_get_product( $product_object->get_Id() )->get_meta( 'swatch_options', true );
			if ( $swatch_options ) {
				$swatch_options = json_decode( $swatch_options, true );
			}
			?>
			<div id="swatch_product_options" class="panel woocommerce_options_panel wc-metaboxes-wrapper">
				<div class="wc-metaboxes">
				<?php
				if ( ! count( $attrs ) ) :
					?>

					<div id="message" class="inline notice molla-wc-message">
						<p><?php printf( esc_html__( 'Before you can add image swatch you need to add some %1$slist%2$s variation attributes on the %1$sAttributes%2$s tab.', 'molla' ), '<strong>', '</strong>' ); ?></p>
						<p><a class="button-primary" href="<?php echo esc_url( apply_filters( 'woocommerce_docs_url', 'https://docs.woocommerce.com/document/variable-product/', 'product-variations' ) ); ?>" target="_blank"><?php esc_html_e( 'Learn more', 'molla' ); ?></a></p>
					</div>

					<?php
				else :
					?>
					<div class="inline notice woocommerce-message"><p><?php esc_html_e( 'This will replace product image with following uploaded image when attribute button is clicked.', 'molla' ); ?></p></div>
					<?php
					foreach ( $attrs as $attribute ) {

						if ( 'pick' != $attribute->get_taxonomy_object()->attribute_type ) {
							continue;
						}

						$metabox_class = array();

						if ( $attribute->is_taxonomy() ) {
							$metabox_class[] = 'taxonomy';
							$metabox_class[] = $attribute->get_name();
						}

						$swatch_type = $swatch_options && isset( $swatch_options[ $attribute->get_name() ] ) ? $swatch_options[ $attribute->get_name() ]['type'] : 'default';
						?>
						<div data-taxonomy="<?php echo esc_attr( $attribute->get_taxonomy() ); ?>" class="woocommerce_attribute wc-metabox closed <?php echo esc_attr( implode( ' ', $metabox_class ) ); ?>" rel="<?php echo esc_attr( $attribute->get_position() ); ?>">
							<h3><?php echo wc_attribute_label( $attribute->get_name() ); ?></h3>
							<div class="woocommerce_attribute_data wc-metabox-content hidden">
								<div class="swatch_type_select">
									<label>Show Type</label>
									<select class="swatch_type" id="swatch_options[<?php echo esc_attr( $attribute->get_name() ); ?>][type]" name="swatch_options[<?php echo esc_attr( $attribute->get_name() ); ?>][type]">
										<option value="default" <?php selected( $swatch_type, 'default' ); ?>><?php esc_html_e( 'Default', 'molla' ); ?></option>
										<option value="image" <?php selected( $swatch_type, 'image' ); ?> selected><?php esc_html_e( 'Image', 'molla' ); ?></option>
									</select>
								</div>
								<table class="product_custom_swatches">
									<thead>
										<th class="attribute_swatch_label">
											<?php esc_html_e( 'Attribute', 'molla' ); ?>
										</th>
										<th class="attribute_swatch_image">
											<?php esc_html_e( 'Image', 'molla' ); ?>
										</th>
									</thead>

									<tbody>
									<?php
									foreach ( $attribute->get_options() as $option ) {
										$term = get_term( $option );
										if ( $term ) {
											$attr_label = $term->name;
										} else {
											$attr_label = $option;
											$option     = preg_replace( '/\s+/', '_', $option );
										}
										$src    = wc_placeholder_img_src();
										$src_id = $swatch_options && isset( $swatch_options[ $attribute->get_name() ] ) ? $swatch_options[ $attribute->get_name() ][ $option ] : '';
										if ( $src_id ) {
											$src = wp_get_attachment_image_src( $src_id )[0];
										}
										?>
											<tr>
												<td class="attribute_swatch_label">
													<strong><?php echo esc_html( $attr_label ); ?></strong>
												</td>
												<td class="attribute_swatch_image" id="<?php echo esc_attr( $option ); ?>">
													<img src="<?php echo esc_url( $src ); ?>" alt="<?php esc_attr_e( 'Thumbnail Preview', 'molla' ); ?>" width="32" height="32">
													<input class="upload_image_url" type="hidden" id="swatch_options[<?php echo esc_attr( $attribute->get_name() ); ?>][<?php echo esc_attr( $option ); ?>]" name="swatch_options[<?php echo esc_attr( $attribute->get_name() ); ?>][<?php echo esc_attr( $option ); ?>]" value="<?php echo esc_attr( $src_id ); ?>" />
													<button type="submit" class="button_upload_image button" id="<?php echo esc_attr( $option ); ?>" rel="<?php echo esc_attr( $option ); ?>"><?php esc_html_e( 'Upload/Add image', 'molla' ); ?></button>
													<button type="submit" class="button_remove_image button" id="<?php echo esc_attr( $option ); ?>" rel="<?php echo esc_attr( $option ); ?>"><?php esc_html_e( 'Remove image', 'molla' ); ?></button>
													<p>( Please upload large image for product. Swatches will use cropped 32x32 image. )</p>
												</td>
											</tr>
											<?php
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
						<?php
					}
				endif;
				?>
				</div>
			</div>
			<?php
		}

		public function enqueue_scripts() {
			wp_enqueue_media();

			wp_enqueue_script( 'molla-swatch-admin-js', MOLLA_PRO_LIB_URI . '/image-swatch/swatch-admin.js', array(), MOLLA_VERSION, true );

			wp_localize_script(
				'molla-swatch-admin-js',
				'lib_swatch_admin',
				array(
					'placeholder' => esc_js( wc_placeholder_img_src() ),
				)
			);
		}

		public function save_product_meta( $post_id, $post ) {
			$product = wc_get_product( $post_id );

			if ( 'variable' != $_POST['product-type'] ) {
				return;
			}

			$swatch_options = isset( $_POST['swatch_options'] ) ? $_POST['swatch_options'] : false;

			if ( $swatch_options && is_array( $swatch_options ) ) {
				$product->update_meta_data( 'swatch_options', json_encode( $swatch_options ) );
			} else {
				$product->delete_meta_data( 'swatch_options' );
			}

			$product->save_meta_data();
		}
	}
}

new Molla_Image_Swatch_Tab;
