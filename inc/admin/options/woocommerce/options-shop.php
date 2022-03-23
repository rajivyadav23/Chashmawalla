<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * woocommerce shop settings
 */

if ( ! class_exists( 'Molla_Woocommerce_Shop_Options' ) ) :

	class Molla_Woocommerce_Shop_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'custom',
					'settings' => 'title_shop_layout',
					'label'    => '',
					'section'  => 'woocommerce_product_catalog',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Page Layout', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'shop_page_layout',
					'label'    => esc_html__( 'Layout', 'molla' ),
					'section'  => 'woocommerce_product_catalog',
					'default'  => molla_defaults( 'shop_page_layout' ),
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
					'settings'        => 'shop_page_width',
					'label'           => esc_html__( 'Page Width', 'molla' ),
					'section'         => 'woocommerce_product_catalog',
					'default'         => molla_defaults( 'shop_page_width' ),
					'choices'         => array(
						'container'       => MOLLA_URI . '/assets/images/customize/container.png',
						'container-fluid' => MOLLA_URI . '/assets/images/customize/container-fluid.png',
						''                => MOLLA_URI . '/assets/images/customize/fullwidth.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'shop_page_layout',
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
					'settings'        => 'shop_sidebar_pos',
					'label'           => esc_html__( 'Sidebar Position', 'molla' ),
					'section'         => 'woocommerce_product_catalog',
					'default'         => molla_defaults( 'shop_sidebar_pos' ),
					'choices'         => array(
						'left'  => MOLLA_URI . '/assets/images/customize/leftsidebar.png',
						'right' => MOLLA_URI . '/assets/images/customize/rightsidebar.png',
						'top'   => MOLLA_URI . '/assets/images/customize/topsidebar.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'shop_page_layout',
							'operator' => '!=',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-buttonset',
					'settings'        => 'shop_sidebar_type',
					'label'           => esc_html__( 'Sidebar Option', 'molla' ),
					'section'         => 'woocommerce_product_catalog',
					'default'         => molla_defaults( 'shop_sidebar_type' ),
					'choices'         => array(
						''               => esc_html__( 'Sticky Sidebar', 'molla' ),
						'filter-sidebar' => esc_html__( 'Toggle Sidebar', 'molla' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'shop_sidebar_pos',
							'operator' => '!=',
							'value'    => 'top',
						),
					),
				)
			);
			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_shop_post_layout',
					'label'    => '',
					'section'  => 'woocommerce_product_catalog',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Product Layout', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'post_layout',
					'label'    => esc_html__( 'Layout', 'molla' ),
					'section'  => 'woocommerce_product_catalog',
					'default'  => molla_defaults( 'post_layout' ),
					'choices'  => array(
						'grid'     => MOLLA_URI . '/assets/images/customize/grid.png',
						'creative' => MOLLA_URI . '/assets/images/customize/creative.png',
					),
				)
			);
			$grid_options = array();
			if ( defined( 'MOLLA_CORE_VERSION' ) ) {
				$grid_options = molla_creative_grid_options();
			}
			$options = array();
			foreach ( $grid_options as $key => $value ) {
				$idx             = $key ? $key : 0;
				$options[ $key ] = $value['image'];
			}
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-image',
					'settings'        => 'post_creative_layout',
					'label'           => esc_html__( 'Creative Layout Mode', 'molla' ),
					'section'         => 'woocommerce_product_catalog',
					'default'         => molla_defaults( 'post_creative_layout' ),
					'choices'         => $options,
					'active_callback' => array(
						array(
							'setting'  => 'woocommerce_shop_page_display',
							'operator' => '==',
							'value'    => 'subcategories',
						),
						array(
							'setting'  => 'post_layout',
							'operator' => '==',
							'value'    => 'creative',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'text',
					'settings'        => 'post_creative_layout_height',
					'label'           => esc_html__( 'Height(px)', 'molla' ),
					'section'         => 'woocommerce_product_catalog',
					'default'         => molla_defaults( 'post_creative_layout_height' ),
					'active_callback' => array(
						array(
							'setting'  => 'woocommerce_shop_page_display',
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'setting'  => 'post_layout',
							'operator' => '==',
							'value'    => 'creative',
						),
						array(
							'setting'  => 'post_creative_layout',
							'operator' => '!=',
							'value'    => 0,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'slider',
					'settings'        => 'catalog_columns',
					'label'           => esc_html__( 'Column', 'molla' ),
					'description'     => esc_html__( 'How many products should be shown per row?', 'molla' ),
					'section'         => 'woocommerce_product_catalog',
					'default'         => molla_defaults( 'catalog_columns' ),
					'choices'         => array(
						'min'  => 1,
						'max'  => 8,
						'step' => 1,
					),
					'active_callback' => array(
						array(
							'setting'  => 'post_product_type',
							'operator' => '!=',
							'value'    => 'list',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'slider',
					'settings'    => 'woocommerce_catalog_columns',
					'label'       => esc_html__( 'Catalog Count per Page', 'molla' ),
					'description' => esc_html__( 'How many products should be shown per page?', 'molla' ),
					'section'     => 'woocommerce_product_catalog',
					'default'     => molla_defaults( 'woocommerce_catalog_columns' ),
					'choices'     => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'select',
					'settings'        => 'shop_view_more_type',
					'label'           => esc_html__( 'View More Type', 'molla' ),
					'section'         => 'woocommerce_product_catalog',
					'default'         => molla_defaults( 'shop_view_more_type' ),
					'choices'         => array(
						'pagination' => esc_html__( 'Pagination', 'molla' ),
						'button'     => esc_html__( 'Load More Button', 'molla' ),
						'scroll'     => esc_html__( 'Infinite Scroll', 'molla' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'woocommerce_shop_page_display',
							'operator' => '==',
							'value'    => '',
						),
					),
				)
			);

		}
	}
endif;

return new Molla_Woocommerce_Shop_Options();

