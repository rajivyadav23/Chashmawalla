<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Header_General_Options' ) ) :

	class Molla_Header_General_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {
			Molla_Option::add_section(
				'header_general',
				array(
					'title' => esc_html__( 'General', 'molla' ),
					'panel' => 'header',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-image',
					'settings'  => 'header_width',
					'section'   => 'header_general',
					'label'     => esc_html__( 'Width', 'molla' ),
					'default'   => molla_defaults( 'header_width' ),
					'choices'   => array(
						'container'       => MOLLA_URI . '/assets/images/customize/container.png',
						'container-fluid' => MOLLA_URI . '/assets/images/customize/container-fluid.png',
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'header_side',
					'label'     => esc_html__( 'Select Side Header', 'molla' ),
					'section'   => 'header_general',
					'default'   => molla_defaults( 'header_side' ),
					'tooltip'   => esc_html__( 'Side header is sticky in left.', 'molla' ),
					'choices'   => array(
						''       => esc_html__( 'No Side Header', 'molla' ),
						'top'    => esc_html__( 'Header Top', 'molla' ),
						'main'   => esc_html__( 'Header Main', 'molla' ),
						'bottom' => esc_html__( 'Header Bottom', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-buttonset',
					'settings'        => 'header_side_menu_type',
					'label'           => esc_html__( 'Side Menu Type', 'molla' ),
					'section'         => 'header_general',
					'default'         => molla_defaults( 'header_side_menu_type' ),
					'choices'         => array(
						''       => esc_html__( 'Default', 'molla' ),
						'expand' => esc_html__( 'Expand', 'molla' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'header_side',
							'operator' => '!=',
							'value'    => '',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'header_divider_color',
					'label'     => esc_html__( 'Divider Color', 'molla' ),
					'choices'   => array(
						'alpha' => true,
					),
					'section'   => 'header_general',
					'default'   => molla_defaults( 'header_divider_color' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'header_position_fixed',
					'label'     => esc_html__( 'Position fixed header', 'molla' ),
					'section'   => 'header_general',
					'default'   => molla_defaults( 'header_position_fixed' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'header_color',
					'label'     => esc_html__( 'Color', 'molla' ),
					'section'   => 'header_general',
					'default'   => molla_defaults( 'header_color' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'background',
					'settings'  => 'header_bg',
					'label'     => '',
					'section'   => 'header_general',
					'default'   => molla_defaults( 'header_bg' ),
					'transport' => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Header_General_Options();
