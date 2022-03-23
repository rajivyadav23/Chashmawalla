<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * woocommerce shop settings
 */

if ( ! class_exists( 'Molla_Woocommerce_Single_Product_Options' ) ) :

	class Molla_Woocommerce_Single_Product_Options {

		public $product_layouts = array( '' => 'None' );

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );

			$posts = get_posts(
				array(
					'post_type'   => 'product_layout',
					'post_status' => 'publish',
				)
			);
			foreach ( $posts as $p ) {
				$this->product_layouts[ $p->post_name ] = $p->post_title;
			}
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'single_product',
				array(
					'title' => esc_html__( 'Single Product', 'molla' ),
					'panel' => 'woocommerce',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_single_product_page_layout',
					'label'    => '',
					'section'  => 'single_product',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Page Layout', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'single_product_page_header',
					'label'    => esc_html__( 'Show Page Header', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_product_page_header' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'single_product_page_layout',
					'label'    => esc_html__( 'Page Layout Mode', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_product_page_layout' ),
					'choices'  => array(
						''       => MOLLA_URI . '/assets/images/customize/default.png',
						'custom' => MOLLA_URI . '/assets/images/customize/plus.png',
					),
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-image',
					'settings'        => 'single_product_header_width',
					'label'           => esc_html__( 'Header Width', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_header_width' ),
					'choices'         => array(
						'container'       => MOLLA_URI . '/assets/images/customize/container.png',
						'container-fluid' => MOLLA_URI . '/assets/images/customize/container-fluid.png',
						''                => MOLLA_URI . '/assets/images/customize/fullwidth.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_page_layout',
							'operator' => '!=',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-image',
					'settings'        => 'single_product_width',
					'label'           => esc_html__( 'Page Width', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_width' ),
					'choices'         => array(
						'container'       => MOLLA_URI . '/assets/images/customize/container.png',
						'container-fluid' => MOLLA_URI . '/assets/images/customize/container-fluid.png',
						''                => MOLLA_URI . '/assets/images/customize/fullwidth.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_page_layout',
							'operator' => '!=',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-image',
					'settings'        => 'single_product_sidebar',
					'label'           => esc_html__( 'Sidebar Option', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_sidebar' ),
					'choices'         => array(
						'no'    => MOLLA_URI . '/assets/images/customize/nosidebar.png',
						'left'  => MOLLA_URI . '/assets/images/customize/leftsidebar.png',
						'right' => MOLLA_URI . '/assets/images/customize/rightsidebar.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_page_layout',
							'operator' => '!=',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-image',
					'settings'        => 'single_product_footer_width',
					'label'           => esc_html__( 'Footer Width', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_footer_width' ),
					'choices'         => array(
						'container'       => MOLLA_URI . '/assets/images/customize/container.png',
						'container-fluid' => MOLLA_URI . '/assets/images/customize/container-fluid.png',
						''                => MOLLA_URI . '/assets/images/customize/fullwidth.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_page_layout',
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
					'settings' => 'title_single_product_layout',
					'label'    => '',
					'section'  => 'single_product',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Product Layout', 'molla' ) . '</div>',
				)
			);

			$single_options = array(
				''               => esc_html__( 'Default', 'molla' ),
				'gallery'        => esc_html__( 'Gallery', 'molla' ),
				'extended'       => esc_html__( 'Extended Info', 'molla' ),
				'sticky'         => esc_html__( 'Sticky Info', 'molla' ),
				'boxed'          => esc_html__( 'Boxed With Sidebar', 'molla' ),
				'full'           => esc_html__( 'Fullwidth With Sidebar', 'molla' ),
				'masonry_sticky' => esc_html__( 'Masonry Sticky Info', 'molla' ),
				'custom'         => esc_html__( 'Custom Layout', 'molla' ),
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'select',
					'settings' => 'single_product_layout',
					'label'    => esc_html__( 'Single Product Type', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_product_layout' ),
					'choices'  => $single_options,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-image',
					'settings'        => 'single_product_image',
					'label'           => esc_html__( 'Product Image Layout', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_image' ),
					'choices'         => array(
						'vertical'   => MOLLA_URI . '/assets/images/customize/quickview1.png',
						'horizontal' => MOLLA_URI . '/assets/images/customize/quickview2.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_layout',
							'operator' => '!=',
							'value'    => 'custom',
						),
						array(
							'setting'  => 'single_product_layout',
							'operator' => '!=',
							'value'    => 'gallery',
						),
						array(
							'setting'  => 'single_product_layout',
							'operator' => '!=',
							'value'    => 'sticky',
						),
						array(
							'setting'  => 'single_product_layout',
							'operator' => '!=',
							'value'    => 'masonry_sticky',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'select',
					'settings'        => 'single_product_layout_slug',
					'label'           => esc_html__( 'Select Custom Layout', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_layout_slug' ),
					'choices'         => $this->product_layouts,
					'active_callback' => array(
						array(
							'setting'  => 'single_product_layout',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'slider',
					'settings'        => 'single_product_image_wrap_col',
					'label'           => esc_html__( 'Product Image Width', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_image_wrap_col' ),
					'choices'         => array(
						'min'  => 1,
						'max'  => 12,
						'step' => 1,
					),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_layout',
							'operator' => '!=',
							'value'    => 'gallery',
						),
						array(
							'setting'  => 'single_product_layout',
							'operator' => '!=',
							'value'    => 'custom',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-buttonset',
					'settings'        => 'single_product_image_slider_nav',
					'label'           => esc_html__( 'Nav & Dot Position', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_image_slider_nav' ),
					'choices'         => array(
						'owl-nav-inside' => esc_html__( 'Inner', 'molla' ),
						''               => esc_html__( 'Outer', 'molla' ),
						'owl-nav-top'    => esc_html__( 'Top', 'molla' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_layout',
							'operator' => '==',
							'value'    => 'gallery',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'select',
					'settings'        => 'single_product_image_slider_nav_type',
					'label'           => esc_html__( 'Nav Type', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_image_slider_nav_type' ),
					'choices'         => array(
						''                => esc_html__( 'Type 1', 'molla' ),
						'owl-full'        => esc_html__( 'Type 2', 'molla' ),
						'owl-nav-rounded' => esc_html__( 'Type 3', 'molla' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_layout',
							'operator' => '==',
							'value'    => 'gallery',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'slider',
					'settings'        => 'single_product_image_col',
					'label'           => esc_html__( 'Columns', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_image_col' ),
					'choices'         => array(
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_layout',
							'operator' => '==',
							'value'    => 'gallery',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'slider',
					'settings'        => 'single_product_image_slider_spacing',
					'label'           => esc_html__( 'Spacing', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_image_slider_spacing' ),
					'choices'         => array(
						'min'  => 0,
						'max'  => 40,
						'step' => 1,
					),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_layout',
							'operator' => '==',
							'value'    => 'gallery',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'prevent_elevatezoom',
					'label'    => esc_html__( 'Prevent Image-zoom in Mobile', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'prevent_elevatezoom' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'single_sticky_bar_show',
					'label'    => esc_html__( 'Show Sticky Add-to-cart', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_sticky_bar_show' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'toggle',
					'settings'        => 'single_product_center',
					'label'           => esc_html__( 'Enable Center Mode', 'molla' ),
					'section'         => 'single_product',
					'default'         => molla_defaults( 'single_product_center' ),
					'tooltip'         => esc_html__( 'Single product summary alignment.', 'molla' ),
					'active_callback' => array(
						array(
							'setting'  => 'single_product_layout',
							'operator' => '!=',
							'value'    => 'gallery',
						),
						array(
							'setting'  => 'single_product_layout',
							'operator' => '!=',
							'value'    => 'custom',
						),
					),
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_single_product_summary',
					'label'    => '',
					'section'  => 'single_product',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Product Data', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-buttonset',
					'settings' => 'single_product_data_style',
					'label'    => esc_html__( 'Product Data', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_product_data_style' ),
					'choices'  => array(
						''          => esc_html__( 'Tab', 'molla' ),
						'accordion' => esc_html__( 'Accordion', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'text',
					'settings' => 'single_product_tab_title',
					'label'    => esc_html__( 'Global Tab Title', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_product_tab_title' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'text',
					'settings' => 'single_product_tab_block',
					'label'    => esc_html__( 'Global Tab Block Name', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_product_tab_block' ),
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'single_product_sale_notification',
					'label'    => '',
					'section'  => 'single_product',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Product Sale Notification', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'text',
					'settings' => 'single_product_limit_time',
					'label'    => esc_html__( 'Time Limit for Sale Product (day)', 'molla' ),
					'section'  => 'single_product',
					'default'  => '1',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_single_product_related',
					'label'    => '',
					'section'  => 'single_product',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Related Products', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'single_related_show',
					'label'    => esc_html__( 'Show related products', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_related_show' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'single_related_layout_type',
					'label'    => esc_html__( 'Layout Type', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_related_layout_type' ),
					'choices'  => array(
						''         => MOLLA_URI . '/assets/images/customize/grid.png',
						'creative' => MOLLA_URI . '/assets/images/customize/creative.png',
						'slider'   => MOLLA_URI . '/assets/images/customize/slider.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'slider',
					'settings' => 'single_related_cols',
					'label'    => esc_html__( 'Columns', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_related_cols' ),
					'choices'  => array(
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'number',
					'settings' => 'single_related_count',
					'label'    => esc_html__( 'Count', 'molla' ),
					'section'  => 'single_product',
					'default'  => molla_defaults( 'single_related_count' ),
					'choices'  => array(
						'min'  => 1,
						'max'  => 20,
						'step' => 1,
					),
				)
			);

		}
	}
endif;

return new Molla_Woocommerce_Single_Product_Options();

