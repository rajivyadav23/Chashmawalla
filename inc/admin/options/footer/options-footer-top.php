<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Footer_Top_Options' ) ) :

	class Molla_Footer_Top_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'footer_top',
				array(
					'title' => esc_html__( 'Footer Top', 'molla' ),
					'panel' => 'footer',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'footer_top_divider_active',
					'section'   => 'footer_top',
					'label'     => esc_html__( 'Enable Divider', 'molla' ),
					'default'   => molla_defaults( 'footer_top_divider_active' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-buttonset',
					'settings'  => 'footer_top_divider_width',
					'label'     => esc_html__( 'Divider Width', 'molla' ),
					'section'   => 'footer_top',
					'default'   => molla_defaults( 'footer_top_divider_width' ),
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
					'settings'  => 'footer_top_pt',
					'label'     => esc_html__( 'Padding Top ( px )', 'molla' ),
					'section'   => 'footer_top',
					'default'   => molla_defaults( 'footer_top_pt' ),
					'choices'   => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'slider',
					'settings'  => 'footer_top_pb',
					'label'     => esc_html__( 'Padding Bottom ( px )', 'molla' ),
					'section'   => 'footer_top',
					'default'   => molla_defaults( 'footer_top_pb' ),
					'choices'   => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'background',
					'settings'  => 'footer_top_bg',
					'label'     => '',
					'section'   => 'footer_top',
					'default'   => molla_defaults( 'footer_top_bg' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'footer_top_cols',
					'label'     => esc_html__( 'Columns', 'molla' ),
					'section'   => 'footer_top',
					'default'   => molla_defaults( 'footer_top_cols' ),
					'tooltip'   => esc_html__( 'Input Type: 6+2+2+2<br>Sum of columns should be 12.', 'molla' ),
					'transport' => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Footer_Top_Options();
