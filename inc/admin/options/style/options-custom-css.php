<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Custom_CSS_Options' ) ) :

	class Molla_Custom_CSS_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'custom_css',
				array(
					'title' => esc_html__( 'Additional CSS & Script', 'molla' ),
					'panel' => 'style',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'section'   => 'custom_css',
					'type'      => 'code',
					'settings'  => 'custom_css',
					'label'     => esc_html__( 'CSS code', 'molla' ),
					'default'   => '',
					'transport' => 'postMessage',
					'choices'   => array(
						'language' => 'css',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'section'   => 'custom_css',
					'type'      => 'code',
					'settings'  => 'custom_script',
					'label'     => esc_html__( 'JS code', 'molla' ),
					'default'   => '',
					'transport' => 'postMessage',
					'choices'   => array(
						'language' => 'javascript',
					),
				)
			);
		}
	}
endif;

return new Molla_Custom_CSS_Options();


