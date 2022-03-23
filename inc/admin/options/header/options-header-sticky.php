<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Header_Sticky_Options' ) ) :

	class Molla_Header_Sticky_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'header_sticky',
				array(
					'title' => esc_html__( 'Sticky Header', 'molla' ),
					'panel' => 'header',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'header_sticky_show',
					'label'     => esc_html__( 'Enable Sticky Header', 'molla' ),
					'section'   => 'header_sticky',
					'default'   => molla_defaults( 'header_sticky_show' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_sticky_layout',
					'label'    => '',
					'section'  => 'header_sticky',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Layout', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'slider',
					'settings'  => 'header_top_sticky_height',
					'label'     => esc_html__( 'Top Padding ( px )', 'molla' ),
					'section'   => 'header_sticky',
					'default'   => (int) molla_defaults( 'header_top_sticky_height' ),
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
					'type'      => 'slider',
					'settings'  => 'header_main_sticky_height',
					'label'     => esc_html__( 'Main Padding ( px )', 'molla' ),
					'section'   => 'header_sticky',
					'default'   => (int) molla_defaults( 'header_main_sticky_height' ),
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
					'type'      => 'slider',
					'settings'  => 'header_bottom_sticky_height',
					'label'     => esc_html__( 'Bottom Padding ( px )', 'molla' ),
					'section'   => 'header_sticky',
					'default'   => (int) molla_defaults( 'header_bottom_sticky_height' ),
					'choices'   => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_sticky_conf',
					'label'    => '',
					'section'  => 'header_sticky',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Sticky header', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'header_top_in_sticky',
					'label'     => esc_html__( 'Header top - in sticky', 'molla' ),
					'section'   => 'header_sticky',
					'default'   => molla_defaults( 'header_top_in_sticky' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'header_main_in_sticky',
					'label'     => esc_html__( 'Header main - in sticky', 'molla' ),
					'section'   => 'header_sticky',
					'default'   => molla_defaults( 'header_main_in_sticky' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'header_bottom_in_sticky',
					'label'     => esc_html__( 'Header bottom - in sticky', 'molla' ),
					'section'   => 'header_sticky',
					'default'   => molla_defaults( 'header_bottom_in_sticky' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_skin',
					'label'    => '',
					'section'  => 'header_sticky',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Skin', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'background',
					'settings'  => 'header_top_bg_sticky',
					'label'     => esc_html__( 'Header Top', 'molla' ),
					'section'   => 'header_sticky',
					'default'   => molla_defaults( 'header_top_bg_sticky' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'background',
					'settings'  => 'header_main_bg_sticky',
					'label'     => esc_html__( 'Header Main', 'molla' ),
					'section'   => 'header_sticky',
					'default'   => molla_defaults( 'header_main_bg_sticky' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'background',
					'settings'  => 'header_bottom_bg_sticky',
					'label'     => esc_html__( 'Header Bottom', 'molla' ),
					'section'   => 'header_sticky',
					'default'   => molla_defaults( 'header_bottom_bg_sticky' ),
					'transport' => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Header_Sticky_Options();
