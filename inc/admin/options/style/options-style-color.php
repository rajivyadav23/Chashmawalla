<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Style_Color_Options' ) ) :

	class Molla_Style_Color_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'style_color',
				array(
					'title' => esc_html__( 'Color', 'molla' ),
					'panel' => 'style',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'primary_color',
					'label'     => esc_html__( 'Primary Color', 'molla' ),
					'section'   => 'style_color',
					'default'   => molla_defaults( 'primary_color' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'secondary_color',
					'label'     => esc_html__( 'Secondary Color', 'molla' ),
					'section'   => 'style_color',
					'default'   => molla_defaults( 'secondary_color' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'alert_color',
					'label'     => esc_html__( 'Alert Color', 'molla' ),
					'section'   => 'style_color',
					'default'   => molla_defaults( 'alert_color' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'dark_color',
					'label'     => esc_html__( 'Dark Color', 'molla' ),
					'section'   => 'style_color',
					'default'   => molla_defaults( 'dark_color' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'light_color',
					'label'     => esc_html__( 'Light Color', 'molla' ),
					'section'   => 'style_color',
					'default'   => molla_defaults( 'light_color' ),
					'transport' => 'postMessage',
				)
			);
		}
	}
endif;

return new Molla_Style_Color_Options();

