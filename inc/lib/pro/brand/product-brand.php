<?php
/**
 * Molla Product Custom Taxonomies
 *
 * @version 1.2.5
 */

class Molla_Product_Brand {

	public function __construct() {

		add_action( 'admin_init', array( $this, 'permalink_fields' ) );
		add_action( 'current_screen', array( $this, 'settings_save' ) );

		if ( get_option( 'product_brand_slug' ) ) {
			return false;
		}

		// Register custom post type and custom taxonomy
		add_action( 'init', array( $this, 'register_brand' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'woocommerce_sortable_taxonomies', array( $this, 'product_brand_sortable' ) );
		add_filter( 'woocommerce_screen_ids', array( $this, 'add_brand_screen_id' ) );

		// Add form
		add_action( 'product_brand_add_form_fields', array( $this, 'add_category_fields' ) );
		add_action( 'product_brand_edit_form_fields', array( $this, 'edit_category_fields' ), 20 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 20, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 20, 3 );

		// Add columns in list table
		add_filter( 'manage_edit-product_brand_columns', array( $this, 'add_brand_columns_tax_page' ) );
		add_filter( 'manage_product_brand_custom_column', array( $this, 'print_brand_column_tax_page' ), 10, 3 );

		// Add columns in products table
		add_filter( 'manage_product_posts_columns', array( $this, 'add_brand_columns_products_page' ), 20 );
		add_filter( 'manage_product_posts_custom_column', array( $this, 'render_columns' ), 20, 2 );

	}

	public function register_brand() {

		if ( ! post_type_exists( 'product' ) ) {
			return;
		}

		$labels = array(
			'name'                       => esc_html__( 'Product brands', 'molla-core' ),
			'singular_name'              => esc_html__( 'Brand', 'molla-core' ),
			'menu_name'                  => esc_html__( 'Brands', 'molla-core' ),
			'search_items'               => esc_html__( 'Search brands', 'molla-core' ),
			'all_items'                  => esc_html__( 'All brands', 'molla-core' ),
			'edit_item'                  => esc_html__( 'Edit brand', 'molla-core' ),
			'update_item'                => esc_html__( 'Update brand', 'molla-core' ),
			'add_new_item'               => esc_html__( 'Add New brand', 'molla-core' ),
			'new_item_name'              => esc_html__( 'New brand Name', 'molla-core' ),
			'popular_items'              => esc_html__( 'Popular brands', 'molla-core' ),
			'view_item'                  => esc_html__( 'View brand', 'molla-core' ),
			'parent_item'                => esc_html__( 'Parent brand', 'molla-core' ),
			'parent_item_colon'          => esc_html__( 'Parent brand:', 'molla-core' ),
			'separate_items_with_commas' => esc_html__( 'Separate brands with commas', 'molla-core' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove brands', 'molla-core' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used brands', 'molla-core' ),
			'not_found'                  => esc_html__( 'No brands found', 'molla-core' ),
		);

		$permalinks         = get_option( 'product_brand_permalinks' );
		$product_brand_base = empty( $permalinks['product_brand_base'] ) ? _x( 'product-brand', 'slug', 'molla-core' ) : $permalinks['product_brand_base'];

		$args = array(
			'hierarchical'          => true,
			'update_count_callback' => '_wc_term_recount',
			'labels'                => $labels,
			'show_ui'               => true,
			'query_var'             => true,
			'rewrite'               => array(
				'slug'         => $product_brand_base,
				'hierarchical' => true,
				'ep_mask'      => EP_PERMALINK,
			),
		);

		register_taxonomy( 'product_brand', array( 'product' ), $args );
	}

	public function enqueue_scripts( $hook ) {
		$screen = get_current_screen();
		if ( 'edit-tags.php' == $hook && 'product_brand' == $screen->taxonomy || 'term.php' == $hook && 'product_brand' == $screen->taxonomy ) {
			wp_enqueue_media();
		}
	}

	public function add_brand_screen_id( $screen_ids ) {
		$screen_ids[] = 'edit-product_brand';
		return $screen_ids;
	}

	public function product_brand_sortable( $taxonomy ) {
		$taxonomy[] = 'product_brand';

		return $taxonomy;

	}

	/**
	 * Settings -> Permalink
	 */
	public function permalink_fields() {

		add_settings_section(
			'molla_brand_section',
			'<span id="brand-options">' . esc_html__( 'Product brand', 'molla-core' ) . '</span>',
			array( $this, 'writing_section_html' ),
			'writing'
		);

		add_settings_field(
			'product_brand_slug',
			'<span class="brand-options">' . esc_html__( 'Product brand', 'molla-core' ) . '</span>',
			array( $this, 'disable_field_html' ),
			'writing',
			'molla_brand_section'
		);
		register_setting(
			'writing',
			'product_brand_slug',
			'intval'
		);

		add_settings_field(
			'product_brand_slug',
			'<label for="product_brand_slug">' . esc_html__( 'Product brand base', 'molla-core' ) . '</label>',
			array( $this, 'product_brand_slug_input' ),
			'permalink',
			'optional'
		);

		register_setting(
			'permalink',
			'product_brand_slug',
			'sanitize_text_field'
		);

	}

	/**
	 * Slug input box
	 */
	public function product_brand_slug_input() {
		$permalinks = get_option( 'product_brand_permalinks' );
		$brand_base = isset( $permalinks['product_brand_base'] ) ? $permalinks['product_brand_base'] : '';
		?>
		<input name="product_brand_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $brand_base ); ?>" placeholder="<?php echo esc_attr_x( 'product-brand', 'slug', 'molla-core' ); ?>" />
		<?php
	}

	/**
	 * Save the settings
	 */
	public function settings_save() {
		if ( ! is_admin() ) {
			return;
		}

		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		if ( 'options-permalink' != $screen->id ) {
			return;
		}

		$permalinks = get_option( 'product_brand_permalinks' );

		if ( isset( $_POST['product_brand_slug'] ) ) {
			$permalinks['product_brand_base'] = $this->sanitize_permalink( trim( $_POST['product_brand_slug'] ) );
		}

		update_option( 'product_brand_permalinks', $permalinks );
	}

	/**
	 * Sanitize permalink
	 */
	private function sanitize_permalink( $value ) {
		global $wpdb;

		$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );

		if ( is_wp_error( $value ) ) {
			$value = '';
		}

		$value = esc_url_raw( $value );
		$value = str_replace( 'http://', '', $value );

		return untrailingslashit( $value );
	}

	/**
	 * Category thumbnail fields
	 */
	public function add_category_fields() {
		?>
		<div class="form-field term-thumbnail-wrap">
			<label><?php esc_html_e( 'Thumbnail', 'molla-core' ); ?></label>

			<div id="product_brand_thumbnail" style="float: left; margin-right: 10px;">
				<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
			<div style="line-height: 60px;">
				<input type="hidden" id="product_brand_thumbnail_id" name="product_brand_thumbnail_id" />
				<button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'molla-core' ); ?></button>
				<button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'molla-core' ); ?></button>
			</div>
			<script type="text/javascript">

				// Only show the "remove image" button when needed
				if ( ! jQuery( '#product_brand_thumbnail_id' ).val() ) {
					jQuery( '.remove_image_button' ).hide();
				}

				// Uploading files
				var file_frame;

				jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php esc_html_e( 'Choose an image', 'molla-core' ); ?>',
						button: {
							text: '<?php esc_html_e( 'Use image', 'molla-core' ); ?>'
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
						var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

						jQuery( '#product_brand_thumbnail_id' ).val( attachment.id );
						jQuery( '#product_brand_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
						jQuery( '.remove_image_button' ).show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery( document ).on( 'click', '.remove_image_button', function() {
					jQuery( '#product_brand_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
					jQuery( '#product_brand_thumbnail_id' ).val( '' );
					jQuery( '.remove_image_button' ).hide();
					return false;
				});

				jQuery( document ).ajaxComplete( function( event, request, options ) {
					if ( request && 4 === request.readyState && 200 === request.status
						&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

						var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
						if ( ! res || res.errors ) {
							return;
						}
						// Clear Thumbnail fields on submit
						jQuery( '#product_brand_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
						jQuery( '#product_brand_thumbnail_id' ).val( '' );
						jQuery( '.remove_image_button' ).hide();
						return;
					}
				} );

			</script>
			<div class="clear"></div>
		</div>
		<?php
	}

	/**
	 * Edit category thumbnail field
	 */
	public function edit_category_fields( $term ) {

		$thumbnail_id = absint( get_term_meta( $term->term_id, 'brand_thumbnail_id', true ) );

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			$image        = wc_placeholder_img_src();
			$thumbnail_id = attachment_url_to_postid( $image );
		}
		?>
		<tr class="form-field term-thumbail-wrap">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'molla-core' ); ?></label></th>
			<td>
				<div id="product_brand_thumbnail" style="float: left; margin-right: 10px;">
					<img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" />
				</div>
				<div style="line-height: 60px;">
					<input type="hidden" id="product_brand_thumbnail_id" name="product_brand_thumbnail_id" value="<?php echo esc_attr( $thumbnail_id ); ?>" />
					<button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'molla-core' ); ?></button>
					<button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'molla-core' ); ?></button>
				</div>
				<script type="text/javascript">

					// Only show the "remove image" button when needed
					if ( '0' === jQuery( '#product_brand_thumbnail_id' ).val() ) {
						jQuery( '.remove_image_button' ).hide();
					}

					// Uploading files
					var file_frame;

					jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php esc_html_e( 'Choose an image', 'molla-core' ); ?>',
							button: {
								text: '<?php esc_html_e( 'Use image', 'molla-core' ); ?>'
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
							var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

							jQuery( '#product_brand_thumbnail_id' ).val( attachment.id );
							jQuery( '#product_brand_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
							jQuery( '.remove_image_button' ).show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery( document ).on( 'click', '.remove_image_button', function() {
						jQuery( '#product_brand_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
						jQuery( '#product_brand_thumbnail_id' ).val( '' );
						jQuery( '.remove_image_button' ).hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Add thumbnail column to brands list
	 */
	public function add_brand_columns_tax_page( $columns ) {

		$new_columns = array();

		if ( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
			unset( $columns['cb'] );
		}

		$new_columns['thumb'] = esc_html__( 'Image', 'molla-core' );

		$columns           = array_merge( $new_columns, $columns );
		$columns['handle'] = '';

		return $columns;
	}

	/**
	 * Set thumbnail column of brands list
	 */
	public function print_brand_column_tax_page( $columns, $column, $id ) {

		if ( 'thumb' == $column ) {

			$thumbnail_id = get_term_meta( $id, 'brand_thumbnail_id', true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = wc_placeholder_img_src();
			}

			$image = str_replace( ' ', '%20', $image );

			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'molla-core' ) . '" class="wp-post-image" height="48" width="48" />';

		}

		if ( 'handle' === $column ) {
			$columns .= '<input type="hidden" name="term_id" value="' . esc_attr( $id ) . '" />';
		}

		return $columns;
	}

	/**
	 * Add product brand table heading in product edit page
	 */
	public function add_brand_columns_products_page( $columns ) {

		$columns = array();

		$show_columns          = array();
		$show_columns['cb']    = '<input type="checkbox" />';
		$show_columns['thumb'] = '<span class="wc-image tips" data-tip="' . esc_attr__( 'Image', 'molla-core' ) . '">' . esc_html__( 'Image', 'molla-core' ) . '</span>';
		$show_columns['name']  = esc_html__( 'Name', 'molla-core' );

		if ( wc_product_sku_enabled() ) {
			$show_columns['sku'] = esc_html__( 'SKU', 'molla-core' );
		}

		if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
			$show_columns['is_in_stock'] = esc_html__( 'Stock', 'molla-core' );
		}

		$show_columns['price']         = esc_html__( 'Price', 'molla-core' );
		$show_columns['product_cat']   = esc_html__( 'Categories', 'molla-core' );
		$show_columns['product_brand'] = esc_html__( 'Brands', 'molla-core' );
		$show_columns['product_tag']   = esc_html__( 'Tags', 'molla-core' );
		$show_columns['featured']      = '<span class="wc-featured parent-tips" data-tip="' . esc_attr__( 'Featured', 'molla-core' ) . '">' . esc_html__( 'Featured', 'molla-core' ) . '</span>';
		$show_columns['date']          = esc_html__( 'Date', 'molla-core' );

		return array_merge( $show_columns, $columns );

	}

	/**
	 * Add product brand column renderer
	 */
	public function render_columns( $column, $post_id ) {
		if ( $post_id && 'product_brand' == $column ) {
			$this->render_product_brand_column( $post_id );
		}
	}

	/**
	 * Render columm: product_brand
	 */
	public function render_product_brand_column( $post_id ) {
		$terms = get_the_terms( $post_id, 'product_brand' );
		if ( ! $terms ) {
			echo '<span class="na">&ndash;</span>';
		} else {
			$termlist = array();
			foreach ( $terms as $term ) {
				$termlist[] = '<a href="' . esc_url( admin_url( 'edit.php?product_brand=' . $term->slug . '&post_type=product' ) ) . ' ">' . esc_html( $term->name ) . '</a>';
			}

			echo apply_filters( 'molla_woo_admin_product_term_list', implode( ', ', $termlist ), 'product_brand', $post_id, $termlist, $terms ); // WPCS: XSS ok.
		}
	}

	/**
	 * Save_category_fields function
	 */
	public function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( isset( $_POST['product_brand_thumbnail_id'] ) && 'product_brand' === $taxonomy && function_exists( 'update_woocommerce_term_meta' ) ) {
			update_term_meta( $term_id, 'brand_thumbnail_id', absint( $_POST['product_brand_thumbnail_id'] ) );
		}
	}

	/**
	 * Add writing setting section
	 */
	public function writing_section_html() {
		?>
		<p>
			<?php esc_html_e( 'Use these settings to disable custom types of content on your site', 'molla-core' ); ?>
		</p>
		<?php
	}

	/**
	 * Display a checkbox
	 */
	public function disable_field_html() {
		?>

		<label for="<?php echo esc_attr( 'product_brand_slug' ); ?>">
			<input name="<?php echo esc_attr( 'product_brand_slug' ); ?>" id="<?php echo esc_attr( 'product_brand_slug' ); ?>" <?php checked( get_option( 'product_brand_slug' ), true ); ?> type="checkbox" value="1" />
			<?php esc_html_e( 'Disable Brand for this site.', 'molla-core' ); ?>
		</label>

		<?php
	}

}
new Molla_Product_Brand;
