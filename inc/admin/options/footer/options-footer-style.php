<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Footer_Style_Options' ) ) :

	class Molla_Footer_Style_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'footer_style',
				array(
					'title' => esc_html__( 'Style', 'molla' ),
					'panel' => 'footer',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'footer_divider_color',
					'label'     => esc_html__( 'Divider Color', 'molla' ),
					'choices'   => array(
						'alpha' => true,
					),
					'section'   => 'footer_style',
					'default'   => molla_defaults( 'footer_divider_color' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'background',
					'settings'  => 'footer_bg',
					'label'     => esc_html__( 'Footer Background', 'molla' ),
					'section'   => 'footer_style',
					'default'   => molla_defaults( 'footer_bg' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_footer_style_typography',
					'label'    => '',
					'section'  => 'footer_style',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Typography', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'footer_font',
					'label'     => esc_html__( 'Base', 'molla' ),
					'section'   => 'footer_style',
					'default'   => molla_defaults( 'footer_font' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'footer_font_heading',
					'label'     => esc_html__( 'Heading', 'molla' ),
					'section'   => 'footer_style',
					'default'   => molla_defaults( 'footer_font_heading' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'footer_font_paragraph',
					'label'     => esc_html__( 'Paragraph & Link', 'molla' ),
					'section'   => 'footer_style',
					'default'   => molla_defaults( 'footer_font_paragraph' ),
					'transport' => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Footer_Style_Options();
