<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * woocommerce shop settings
 */

if ( ! class_exists( 'Molla_Woocommerce_Product_Cat_Type_Options' ) ) :

	class Molla_Woocommerce_Product_Cat_Type_Options {

		public $attributes = array();

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'woocommerce_product_cat_type',
				array(
					'title' => esc_html__( 'Product Category Type', 'molla' ),
					'panel' => 'woocommerce',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_shop_product_cat_type',
					'label'    => '',
					'section'  => 'woocommerce_product_cat_type',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Product Category Type', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'select',
					'settings' => 'post_category_type',
					'label'    => esc_html__( 'Category Type', 'molla' ),
					'section'  => 'woocommerce_product_cat_type',
					'default'  => molla_defaults( 'post_category_type' ),
					'choices'  => array(
						'default'      => esc_html__( 'Default', 'molla' ),
						'float'        => esc_html__( 'Float', 'molla' ),
						'block'        => esc_html__( 'Block', 'molla' ),
						'action-popup' => esc_html__( 'Action-popup', 'molla' ),
						'action-slide' => esc_html__( 'Action-slide', 'molla' ),
						'back-clip'    => esc_html__( 'Back-clip', 'molla' ),
						'fade-up'      => esc_html__( 'Fade-up', 'molla' ),
						'fade-down'    => esc_html__( 'Fade-down', 'molla' ),
						'expand'       => esc_html__( 'Expand', 'molla' ),
						'inner-link'   => esc_html__( 'Content-inner-link', 'molla' ),
					),
				)
			);
		}
	}
endif;

return new Molla_Woocommerce_Product_Cat_Type_Options();

