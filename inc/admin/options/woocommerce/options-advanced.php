<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * woocommerce shop settings
 */

if ( ! class_exists( 'Molla_Woocommerce_Advanced_Options' ) ) :

	class Molla_Woocommerce_Advanced_Options {

		private $woo_pre_order;

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );

			// if woo_pre_order has changed, save permalink
			if ( get_site_transient( 'pre_order_save_link' ) ) {
				flush_rewrite_rules();
				delete_site_transient( 'pre_order_save_link' );
			} else {
				add_action( 'customize_save', array( $this, 'pre_order_save_link' ) );
				add_action( 'customize_save_after', array( $this, 'pre_order_save_link_after' ) );
			}
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'woocommerce_advanced',
				array(
					'title'    => esc_html__( 'Advanced', 'molla' ),
					'panel'    => 'woocommerce',
					'priority' => 999,
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_image_swatch',
					'label'    => '',
					'section'  => 'woocommerce_advanced',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Image Swatch', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'image_swatch',
					'label'     => esc_html__( 'Enable Product Image Swatch', 'molla' ),
					'tooltip'   => __( "Product attributes ( type is set as 'Pick' ) are shown as images in single product and also visible in product archive while ", 'molla' ) . '<a href="#" data-target="product_show_op" class="customizer-nav-item" data-type="control">' . esc_html__( 'Attribute Support Setting', 'molla' ) . '</a>' . esc_html__( ' is enabled.', 'molla' ),
					'section'   => 'woocommerce_advanced',
					'default'   => molla_defaults( 'image_swatch' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_pre_order',
					'label'    => '',
					'section'  => 'woocommerce_advanced',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Pre-Order', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'woo_pre_order',
					'label'     => esc_html__( 'Enable Pre-Order', 'molla' ),
					'default'   => molla_option( 'woo_pre_order' ),
					'section'   => 'woocommerce_advanced',
					'tooltip'   => esc_html__( 'Pre ordered products are currently empty but cart available at the moment.', 'molla' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'text',
					'settings'        => 'woo_pre_order_label',
					'label'           => esc_html__( 'Pre-Order Label', 'molla' ),
					'description'     => esc_html__( 'This text will be shown on \'Add to Cart\' button.', 'molla' ),
					'section'         => 'woocommerce_advanced',
					'default'         => molla_option( 'woo_pre_order_label' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'woo_pre_order',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'text',
					'settings'        => 'woo_pre_order_msg_date',
					'label'           => esc_html__( 'Available Date Text', 'molla' ),
					'description'     => esc_html__( 'ex: Available date: %1$s (%2$s will be replaced with available date.)', 'molla' ),
					'section'         => 'woocommerce_advanced',
					'default'         => molla_option( 'woo_pre_order_msg_date' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'woo_pre_order',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'text',
					'settings'        => 'woo_pre_order_msg_nodate',
					'label'           => esc_html__( 'No Date Text', 'molla' ),
					'section'         => 'woocommerce_advanced',
					'default'         => molla_option( 'woo_pre_order_msg_nodate' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'woo_pre_order',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);
		}

		/**
		 * Save Pre Order Link
		 */
		public function pre_order_save_link() {
			$this->woo_pre_order = molla_option( 'woo_pre_order' );
		}

		public function pre_order_save_link_after() {
			if ( molla_option( 'woo_pre_order' ) != $this->woo_pre_order ) {
				set_site_transient( 'pre_order_save_link', true, 20 );
			}
		}
	}
endif;

return new Molla_Woocommerce_Advanced_Options();

