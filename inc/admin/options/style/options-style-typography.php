<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Style_Typography_Options' ) ) :

	class Molla_Style_Typography_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'style_typography',
				array(
					'title' => esc_html__( 'Typography', 'molla' ),
					'panel' => 'style',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_base',
					'label'    => '',
					'section'  => 'style_typography',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Base', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_base',
					'label'     => '',
					'section'   => 'style_typography',
					'default'   => molla_defaults( 'font_base' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_base_mobile',
					'label'    => '',
					'section'  => 'style_typography',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Base Mobile', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'typography',
					'settings'    => 'font_base_mobile',
					'label'       => '',
					'section'     => 'style_typography',
					'description' => esc_html__( 'This works in mobile device under 576px.', 'molla' ),
					'default'     => molla_defaults( 'font_base_mobile' ),
					'transport'   => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_heading',
					'label'    => '',
					'section'  => 'style_typography',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'heading', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'font_heading_select',
					'label'     => esc_html__( 'Heading', 'molla' ),
					'section'   => 'style_typography',
					'default'   => 'h1',
					'choices'   => array(
						'h1' => esc_html__( 'H1', 'molla' ),
						'h2' => esc_html__( 'H2', 'molla' ),
						'h3' => esc_html__( 'H3', 'molla' ),
						'h4' => esc_html__( 'H4', 'molla' ),
						'h5' => esc_html__( 'H5', 'molla' ),
						'h6' => esc_html__( 'H6', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'font_heading_h1',
					'label'           => '',
					'section'         => 'style_typography',
					'default'         => molla_defaults( 'font_heading_h1' ),
					'active_callback' => array(
						array(
							'setting'  => 'font_heading_select',
							'operator' => '==',
							'value'    => 'h1',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'font_heading_h2',
					'label'           => '',
					'section'         => 'style_typography',
					'default'         => molla_defaults( 'font_heading_h2' ),
					'active_callback' => array(
						array(
							'setting'  => 'font_heading_select',
							'operator' => '==',
							'value'    => 'h2',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'font_heading_h3',
					'label'           => '',
					'section'         => 'style_typography',
					'default'         => molla_defaults( 'font_heading_h3' ),
					'active_callback' => array(
						array(
							'setting'  => 'font_heading_select',
							'operator' => '==',
							'value'    => 'h3',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'font_heading_h4',
					'label'           => '',
					'section'         => 'style_typography',
					'default'         => molla_defaults( 'font_heading_h4' ),
					'active_callback' => array(
						array(
							'setting'  => 'font_heading_select',
							'operator' => '==',
							'value'    => 'h4',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'font_heading_h5',
					'label'           => '',
					'section'         => 'style_typography',
					'default'         => molla_defaults( 'font_heading_h5' ),
					'active_callback' => array(
						array(
							'setting'  => 'font_heading_select',
							'operator' => '==',
							'value'    => 'h5',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'font_heading_h6',
					'label'           => '',
					'section'         => 'style_typography',
					'default'         => molla_defaults( 'font_heading_h6' ),
					'active_callback' => array(
						array(
							'setting'  => 'font_heading_select',
							'operator' => '==',
							'value'    => 'h6',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'font_heading_spacing',
					'label'     => esc_html__( 'Margin', 'molla' ),
					'section'   => 'style_typography',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.<br>Default bottom value is '1.4rem'.", 'molla' ),
					'default'   => molla_defaults( 'font_heading_spacing' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_paragraph',
					'label'    => '',
					'section'  => 'style_typography',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'paragraph', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_paragraph',
					'label'     => '',
					'section'   => 'style_typography',
					'default'   => molla_defaults( 'font_paragraph' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'font_paragraph_spacing',
					'label'     => esc_html__( 'Margin', 'molla' ),
					'section'   => 'style_typography',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.<br>Default bottom value is '1.5rem'.", 'molla' ),
					'default'   => molla_defaults( 'font_paragraph_spacing' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_nav',
					'label'    => '',
					'section'  => 'style_typography',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'anchor', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'font_nav_color',
					'label'     => '',
					'section'   => 'style_typography',
					'default'   => molla_defaults( 'font_nav_color' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'font_nav_spacing',
					'label'     => esc_html__( 'Padding', 'molla' ),
					'section'   => 'style_typography',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'   => molla_defaults( 'font_nav_spacing' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_placeholder',
					'label'    => '',
					'section'  => 'style_typography',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'placeholder', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_placeholder',
					'label'     => '',
					'section'   => 'style_typography',
					'default'   => molla_defaults( 'font_placeholder' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_custom_fonts',
					'label'    => '',
					'section'  => 'style_typography',
					'tooltip'  => esc_html__( 'Select fonts to load more from Google.', 'molla' ),
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Custom Fonts', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_custom_1',
					'label'     => esc_html__( 'Font 1', 'molla' ),
					'section'   => 'style_typography',
					'default'   => molla_defaults( 'font_custom_1' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_custom_2',
					'label'     => esc_html__( 'Font 2', 'molla' ),
					'section'   => 'style_typography',
					'default'   => molla_defaults( 'font_custom_2' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_custom_3',
					'label'     => esc_html__( 'Font 3', 'molla' ),
					'section'   => 'style_typography',
					'default'   => molla_defaults( 'font_custom_3' ),
					'transport' => 'postMessage',
				)
			);
		}
	}
endif;

return new Molla_Style_Typography_Options();

