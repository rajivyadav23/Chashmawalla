<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Header_Main_Options' ) ) :

	class Molla_Header_Main_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'header_main',
				array(
					'title' => esc_html__( 'Header Main', 'molla' ),
					'panel' => 'header',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'header_main_divider_active',
					'section'   => 'header_main',
					'label'     => esc_html__( 'Enable Divider', 'molla' ),
					'default'   => molla_defaults( 'header_main_divider_active' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'header_main_divider_color',
					'label'     => esc_html__( 'Divider Color', 'molla' ),
					'choices'   => array(
						'alpha' => true,
					),
					'section'   => 'header_main',
					'default'   => molla_defaults( 'header_main_divider_color' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-buttonset',
					'settings'  => 'header_main_divider_width',
					'label'     => esc_html__( 'Divider Width', 'molla' ),
					'section'   => 'header_main',
					'default'   => molla_defaults( 'header_main_divider_width' ),
					'choices'   => array(
						''     => esc_html__( 'Content Width', 'molla' ),
						'full' => esc_html__( 'Full Width', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'slider',
					'settings'  => 'header_main_height',
					'label'     => esc_html__( 'Padding Top & Bottom ( px )', 'molla' ),
					'section'   => 'header_main',
					'default'   => (int) molla_defaults( 'header_main_height' ),
					'choices'   => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'header_main_color',
					'label'     => esc_html__( 'Color', 'molla' ),
					'section'   => 'header_main',
					'default'   => molla_defaults( 'header_main_color' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'background',
					'settings'  => 'header_main_bg',
					'label'     => '',
					'section'   => 'header_main',
					'default'   => molla_defaults( 'header_main_bg' ),
					'transport' => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Header_Main_Options();
