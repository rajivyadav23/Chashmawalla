<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * woocommerce shop settings
 */

if ( ! class_exists( 'Molla_Woocommerce_Product_Type_Options' ) ) :

	class Molla_Woocommerce_Product_Type_Options {

		public $attributes = array();

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );

			if ( class_exists( 'WooCommerce' ) ) {
				foreach ( wc_get_attribute_taxonomies() as $key => $value ) {
					$this->attributes[ 'pa_' . $value->attribute_name ] = $value->attribute_label;
				}
			}
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			$colors = array(
				'primary_color'   => esc_html__( 'Primary Color', 'molla' ),
				'secondary_color' => esc_html__( 'Secondary Color', 'molla' ),
				'alert_color'     => esc_html__( 'Alert Color', 'molla' ),
				'light_color'     => esc_html__( 'Light Color', 'molla' ),
				'dark_color'      => esc_html__( 'Dark Color', 'molla' ),
				''                => esc_html__( 'Custom', 'molla' ),
			);

			Molla_Option::add_section(
				'woocommerce_product_type',
				array(
					'title' => esc_html__( 'Product Type', 'molla' ),
					'panel' => 'woocommerce',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_shop_product_type',
					'label'    => '',
					'section'  => 'woocommerce_product_type',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Product Type', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'select',
					'settings' => 'post_product_type',
					'label'    => esc_html__( 'Product Type', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'post_product_type' ),
					'choices'  => array(
						'default'       => esc_html__( 'Default', 'molla' ),
						'classic'       => esc_html__( 'Classic', 'molla' ),
						'list'          => esc_html__( 'List', 'molla' ),
						'simple'        => esc_html__( 'Simple', 'molla' ),
						'popup'         => esc_html__( 'Popup 1', 'molla' ),
						'no-overlay'    => esc_html__( 'Popup 2', 'molla' ),
						'slide'         => esc_html__( 'Slide Over', 'molla' ),
						'light'         => esc_html__( 'Light', 'molla' ),
						'dark'          => esc_html__( 'Dark', 'molla' ),
						'card'          => esc_html__( 'Card', 'molla' ),
						'full'          => esc_html__( 'Banner-Type', 'molla' ),
						'gallery-popup' => esc_html__( 'Gallery-Type', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-buttonset',
					'settings'        => 'product_align',
					'label'           => esc_html__( 'Alignment', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'product_align' ),
					'choices'         => array(
						'left'   => esc_html__( 'Left', 'molla' ),
						'center' => esc_html__( 'Center', 'molla' ),
						'right'  => esc_html__( 'Right', 'molla' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'post_product_type',
							'operator' => '!=',
							'value'    => 'card',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'multicheck',
					'settings' => 'product_show_op',
					'label'    => esc_html__( 'Showing Options', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'product_show_op' ),
					'tooltip'  => "<ul>
									<li><span style='font-weight: bold'>Count Deal</span> shows counter-down until sale date ends in saled products</li>
									<li><span style='font-weight: bold'>Attribute Support</span> shows each product's attributes in several types such as image-swatch, selector, etc...  </li>
								</ul>",
					'choices'  => array(
						'name'      => esc_html__( 'Name', 'molla' ),
						'cat'       => esc_html__( 'Category', 'molla' ),
						'tag'       => esc_html__( 'Tag', 'molla' ),
						'price'     => esc_html__( 'Price', 'molla' ),
						'rating'    => esc_html__( 'Rating', 'molla' ),
						'cart'      => esc_html__( 'Add To Cart', 'molla' ),
						'quickview' => esc_html__( 'Quick View', 'molla' ),
						'wishlist'  => esc_html__( 'Wishlist', 'molla' ),
						'deal'      => esc_html__( 'Count Deal', 'molla' ),
						'quantity'  => esc_html__( 'Quantity', 'molla' ),
						'attribute' => esc_html__( 'Attribute Support', 'molla' ),
						'desc'      => esc_html__( 'Short Description', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'catalog_mode',
					'label'    => esc_html__( 'Enable Catalog Mode', 'molla' ),
					'tooltip'  => esc_html__( 'You can restrict catalog visible options for site visitors following bellow options. In case of this is checked, top option would works for only logged in users.', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'catalog_mode' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'multicheck',
					'settings'        => 'public_product_show_op',
					'label'           => esc_html__( 'Showing Options For Visitors', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'public_product_show_op' ),
					'choices'         => array(
						'name'      => esc_html__( 'Name', 'molla' ),
						'cat'       => esc_html__( 'Category', 'molla' ),
						'price'     => esc_html__( 'Price', 'molla' ),
						'rating'    => esc_html__( 'Rating', 'molla' ),
						'cart'      => esc_html__( 'Add To Cart', 'molla' ),
						'quickview' => esc_html__( 'Quick View', 'molla' ),
						'wishlist'  => esc_html__( 'Wishlist', 'molla' ),
						'deal'      => esc_html__( 'Count Deal', 'molla' ),
						'quantity'  => esc_html__( 'Quantity', 'molla' ),
						'attribute' => esc_html__( 'Attribute Support', 'molla' ),
						'desc'      => esc_html__( 'Short Description', 'molla' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'catalog_mode',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'product_read_more',
					'label'    => esc_html__( 'Show "Read More" instead of "Add to cart"', 'molla' ),
					'tooltip'  => esc_html__( 'When "Add to cart" is disabled, "Read More" button will be displayed instead of it.', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'product_read_more' ),
				)
			);

			if ( count( $this->attributes ) ) {
				Molla_Option::add_field(
					'option',
					array(
						'type'     => 'sortable',
						'settings' => 'product_show_attrs',
						'label'    => esc_html__( 'Showing Attributes', 'molla' ),
						'section'  => 'woocommerce_product_type',
						'default'  => molla_defaults( 'product_show_attrs' ),
						'choices'  => $this->attributes,
					)
				);
			}

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_shop_label',
					'label'    => '',
					'section'  => 'woocommerce_product_type',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Label', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'product_label_type',
					'label'     => esc_html__( 'Type', 'molla' ),
					'section'   => 'woocommerce_product_type',
					'default'   => molla_defaults( 'product_label_type' ),
					'choices'   => array(
						''        => esc_html__( 'Square', 'molla' ),
						'circle'  => esc_html__( 'Circle', 'molla' ),
						'polygon' => esc_html__( 'Polygon', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'sortable',
					'settings' => 'product_labels',
					'label'    => esc_html__( 'Labels', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'tooltip'  => esc_html__( 'You can set global label options here e.g. in shop page. You should set product widget label options in page builder.', 'molla' ),
					'default'  => molla_defaults( 'product_labels' ),
					'choices'  => array(
						'featured' => esc_html__( 'Featured', 'molla' ),
						'new'      => esc_html__( 'New', 'molla' ),
						'onsale'   => esc_html__( 'Sale', 'molla' ),
						'hurry'    => esc_html__( 'Hurry Up', 'molla' ),
						'outstock' => esc_html__( 'Out Stock', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'text',
					'settings' => 'new_product_period',
					'label'    => esc_html__( 'New Product Period ( days )', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'new_product_period' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'text',
					'settings'    => 'label_sale_format',
					'label'       => esc_html__( 'Sale Label Format', 'molla' ),
					'description' => esc_html__( '%s represents discount percent', 'molla' ),
					'section'     => 'woocommerce_product_type',
					'default'     => molla_defaults( 'label_sale_format' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'slider',
					'settings'  => 'stock_limit_count',
					'label'     => esc_html__( 'Stock Limit ( % )', 'molla' ),
					'section'   => 'woocommerce_product_type',
					'tooltip'   => esc_html__( 'In archive & single page, you would see hurry up notification per product that stock is reached to limit. If product\'s \'Low stock threshold\' is not set, this value will be limit.', 'molla' ),
					'default'   => molla_defaults( 'stock_limit_count' ),
					'choices'   => array(
						'min'  => 1,
						'max'  => 99,
						'step' => 1,
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'featured_label_color_mode',
					'label'     => esc_html__( 'Featured Skin', 'molla' ),
					'section'   => 'woocommerce_product_type',
					'default'   => molla_defaults( 'featured_label_color_mode' ),
					'choices'   => $colors,
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'featured_label_color_text',
					'label'           => esc_html__( 'Color', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'featured_label_color_text' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'featured_label_color_mode',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'featured_label_color',
					'label'           => esc_html__( 'Background Color', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'featured_label_color' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'featured_label_color_mode',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'new_label_color_mode',
					'label'     => esc_html__( 'New Skin', 'molla' ),
					'section'   => 'woocommerce_product_type',
					'default'   => molla_defaults( 'new_label_color_mode' ),
					'choices'   => $colors,
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'new_label_color_text',
					'label'           => esc_html__( 'Color', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'new_label_color_text' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'new_label_color_mode',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'new_label_color',
					'label'           => esc_html__( 'Background Color', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'new_label_color' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'new_label_color_mode',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'sale_label_color_mode',
					'label'     => esc_html__( 'Sale Skin', 'molla' ),
					'section'   => 'woocommerce_product_type',
					'default'   => molla_defaults( 'sale_label_color_mode' ),
					'choices'   => $colors,
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'sale_label_color_text',
					'label'           => esc_html__( 'Color', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'sale_label_color_text' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'sale_label_color_mode',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'sale_label_color',
					'label'           => esc_html__( 'Background Color', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'sale_label_color' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'sale_label_color_mode',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'outstock_label_color_mode',
					'label'     => esc_html__( 'Out-Stock Skin', 'molla' ),
					'section'   => 'woocommerce_product_type',
					'default'   => molla_defaults( 'outstock_label_color_mode' ),
					'choices'   => $colors,
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'outstock_label_color_text',
					'label'           => esc_html__( 'Color', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'outstock_label_color_text' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'outstock_label_color_mode',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'outstock_label_color',
					'label'           => esc_html__( 'Background Color', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'outstock_label_color' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'outstock_label_color_mode',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'hurry_label_color_mode',
					'label'     => esc_html__( 'Hurry Up Skin', 'molla' ),
					'section'   => 'woocommerce_product_type',
					'default'   => molla_defaults( 'hurry_label_color_mode' ),
					'choices'   => $colors,
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'hurry_label_color_text',
					'label'           => esc_html__( 'Color', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'hurry_label_color_text' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'hurry_label_color_mode',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'hurry_label_color',
					'label'           => esc_html__( 'Background Color', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'hurry_label_color' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'hurry_label_color_mode',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_shop_product_extra',
					'label'    => '',
					'section'  => 'woocommerce_product_type',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Extra', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'quickview_style',
					'label'    => esc_html__( 'Quick View Popup', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'quickview_style' ),
					'choices'  => array(
						'vertical'   => MOLLA_URI . '/assets/images/customize/quickview1.png',
						'horizontal' => MOLLA_URI . '/assets/images/customize/quickview2.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'toggle',
					'settings' => 'product_hover',
					'label'    => esc_html__( 'Show Product Hover Image', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'product_hover' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'product_deal_type',
					'label'    => esc_html__( 'Sale-Countdown Type', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'product_deal_type' ),
					'choices'  => array(
						'block'  => MOLLA_URI . '/assets/images/customize/countdown1.png',
						'inline' => MOLLA_URI . '/assets/images/customize/countdown2.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'toggle',
					'settings'        => 'product_deal_section_period',
					'label'           => esc_html__( 'Countdown Period Short Text', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'product_deal_section_period' ),
					'active_callback' => array(
						array(
							'setting'  => 'product_deal_type',
							'operator' => '==',
							'value'    => 'block',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'text',
					'settings'        => 'product_deal_label',
					'label'           => esc_html__( 'Countdown Prefix', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'product_deal_label' ),
					'active_callback' => array(
						array(
							'setting'  => 'product_deal_type',
							'operator' => '==',
							'value'    => 'inline',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'product_rating_icon',
					'label'     => esc_html__( 'Star Icon', 'molla' ),
					'section'   => 'woocommerce_product_type',
					'default'   => molla_defaults( 'product_rating_icon' ),
					'transport' => 'postMessage',
					'choices'   => array(
						''       => esc_html__( 'Theme', 'molla' ),
						'custom' => esc_html__( 'Font Awesome', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'select',
					'settings' => 'product_vertical_animate',
					'label'    => esc_html__( 'Vertical Action Animation', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'product_vertical_animate' ),
					'choices'  => array(
						''          => esc_html__( 'FadeIn', 'molla' ),
						'fade-left' => esc_html__( 'FadeInLeft', 'molla' ),
						'fade-up'   => esc_html__( 'FadeInUp', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'quickview_pos',
					'label'    => esc_html__( 'Quick View Position', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'quickview_pos' ),
					'tooltip'  => __( "If default is set, it's position is set according to individual product type.", 'molla' ),
					'choices'  => array(
						''                  => MOLLA_URI . '/assets/images/customize/default.png',
						'after-add-to-cart' => MOLLA_URI . '/assets/images/customize/after-cart.png',
						'inner-thumbnail'   => MOLLA_URI . '/assets/images/customize/inner-vertical.png',
						'center-thumbnail'  => MOLLA_URI . '/assets/images/customize/inner-center.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'wishlist_pos',
					'label'    => esc_html__( 'Wishslist Position', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'wishlist_pos' ),
					'tooltip'  => __( "If default is set, it's position is set according to individual product type.", 'molla' ),
					'choices'  => array(
						''                    => MOLLA_URI . '/assets/images/customize/default.png',
						'after-add-to-cart'   => MOLLA_URI . '/assets/images/customize/after-cart.png',
						'after-product-title' => MOLLA_URI . '/assets/images/customize/after-title.png',
						'inner-thumbnail'     => MOLLA_URI . '/assets/images/customize/inner-vertical.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'wishlist_style',
					'label'    => esc_html__( 'Wishlist Type', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'wishlist_style' ),
					'choices'  => array(
						''               => MOLLA_URI . '/assets/images/customize/wishlist1.png',
						'btn-expandable' => MOLLA_URI . '/assets/images/customize/wishlist2.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'out_stock_style',
					'label'    => esc_html__( 'Out-of-Stock', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'out_stock_style' ),
					'choices'  => array(
						''     => MOLLA_URI . '/assets/images/customize/stock_label.png',
						'text' => MOLLA_URI . '/assets/images/customize/stock_text.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'toggle',
					'settings' => 'disable_product_out',
					'label'    => esc_html__( 'Disable Out-of-Stock Products', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'tooltip'  => esc_html__( 'There will be no mouse event over out of stock products.', 'molla' ),
					'default'  => molla_defaults( 'disable_product_out' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'toggle',
					'settings' => 'product_icon_hide',
					'label'    => esc_html__( 'Hide Icon', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'product_icon_hide' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'toggle',
					'settings' => 'product_label_hide',
					'label'    => esc_html__( 'Hide Label', 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'product_label_hide' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'toggle',
					'settings'    => 'action_icon_top',
					'label'       => esc_html__( 'Icon Position Top', 'molla' ),
					'description' => esc_html__( 'Place icon top of the label in product actions.', 'molla' ),
					'section'     => 'woocommerce_product_type',
					'default'     => molla_defaults( 'action_icon_top' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'select',
					'settings'    => 'divider_type',
					'label'       => esc_html__( 'Divider', 'molla' ),
					'section'     => 'woocommerce_product_type',
					'default'     => molla_defaults( 'divider_type' ),
					'description' => esc_html__( 'For product buttons split.', 'molla' ),
					'choices'     => array(
						''       => esc_html__( 'None', 'molla' ),
						'solid'  => esc_html__( 'Solid', 'molla' ),
						'dotted' => esc_html__( 'Dotted', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'divider_color',
					'label'           => '',
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'divider_color' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'divider_type',
							'operator' => '!=',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_shop_product_typography',
					'label'    => '',
					'section'  => 'woocommerce_product_type',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Style', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'product_custom_style',
					'label'    => esc_html__( 'Enable Custom Style', 'molla' ),
					'tooltip'  => __( "Please enable this options to customize product category, title, and etc's style of each product type.", 'molla' ),
					'section'  => 'woocommerce_product_type',
					'default'  => molla_defaults( 'product_custom_style' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'font_product_cat',
					'label'           => esc_html__( 'Category', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'font_product_cat' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'product_custom_style',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'product_cat_margin',
					'label'           => esc_html__( 'Category Margin', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'         => molla_defaults( 'product_cat_margin' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'product_custom_style',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'font_product_title',
					'label'           => esc_html__( 'Title', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'font_product_title' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'product_custom_style',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'product_title_margin',
					'label'           => esc_html__( 'Title Margin', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'         => molla_defaults( 'product_title_margin' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'product_custom_style',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'font_product_price',
					'label'           => esc_html__( 'Price', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'font_product_price' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'product_custom_style',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'product_price_margin',
					'label'           => esc_html__( 'Price Margin', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'         => molla_defaults( 'product_price_margin' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'product_custom_style',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'new_price_color',
					'label'           => esc_html__( 'New Price', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'new_price_color' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'product_custom_style',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'old_price_color',
					'label'           => esc_html__( 'Old Price', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'default'         => molla_defaults( 'old_price_color' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'product_custom_style',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'product_rating_margin',
					'label'           => esc_html__( 'Ratings Margin', 'molla' ),
					'section'         => 'woocommerce_product_type',
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'         => molla_defaults( 'product_rating_margin' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'product_custom_style',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);
		}
	}
endif;

return new Molla_Woocommerce_Product_Type_Options();

