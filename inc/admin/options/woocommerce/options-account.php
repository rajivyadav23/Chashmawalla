<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * woocommerce shop settings
 */

if ( ! class_exists( 'Molla_Woocommerce_Account_Options' ) ) :

	class Molla_Woocommerce_Account_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'woocommerce_account',
				array(
					'title' => esc_html__( 'My Account', 'molla' ),
					'panel' => 'woocommerce',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'background',
					'settings'  => 'woo_account_background',
					'label'     => '',
					'section'   => 'woocommerce_account',
					'default'   => molla_defaults( 'woo_account_background' ),
					'transport' => 'postMessage',
				)
			);
		}
	}
endif;

return new Molla_Woocommerce_Account_Options();

