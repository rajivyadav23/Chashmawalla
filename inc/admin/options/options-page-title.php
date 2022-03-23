<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Elements_Options' ) ) :

	class Molla_Elements_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */
		public function customize_options() {

			Molla_Option::add_section(
				'page_title',
				array(
					'title'    => esc_html__( 'Page Title Bar', 'molla' ),
					'priority' => 6,
				)
			);
			
			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_page_header_custom',
					'label'    => '',
					'section'  => 'page_title',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Page Header', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'page_header_show',
					'label'    => esc_html__( 'Show Page Header', 'molla' ),
					'section'  => 'page_title',
					'default'  => molla_defaults( 'page_header_show' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'background',
					'settings' => 'page_header_bg',
					'label'    => '',
					'section'  => 'page_title',
					'default'  => molla_defaults( 'page_header_bg' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'page_header_parallax',
					'label'    => esc_html__( 'Enable Parallax', 'molla' ),
					'section'  => 'page_title',
					'default'  => molla_defaults( 'page_header_parallax' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'slider',
					'settings'        => 'page_header_plx_speed',
					'label'           => esc_html__( 'Parallax Speed', 'molla' ),
					'section'         => 'page_title',
					'default'         => (int) molla_defaults( 'page_header_plx_speed' ),
					'choices'         => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					),
					'active_callback' => array(
						array(
							'setting'  => 'page_header_parallax',
							'operator' => '==',
							'value'    => true,
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'page_header_content',
					'label'    => esc_html__( 'Type', 'molla' ),
					'section'  => 'page_title',
					'default'  => molla_defaults( 'page_header_content' ),
					'choices'  => array(
						''           => MOLLA_URI . '/assets/images/customize/page_header1.png',
						'subtitle'   => MOLLA_URI . '/assets/images/customize/page_header2.png',
						'breadcrumb' => MOLLA_URI . '/assets/images/customize/page_header3.png',
					),
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_breadcrumb',
					'label'    => '',
					'section'  => 'page_title',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Breadcrumb', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'breadcrumb_show',
					'label'    => esc_html__( 'Show Breadcrumb', 'molla' ),
					'section'  => 'page_title',
					'default'  => molla_defaults( 'breadcrumb_show' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'select',
					'settings' => 'breadcrumb_width',
					'label'    => esc_html__( 'Breadcrumb Width', 'molla' ),
					'section'  => 'page_title',
					'default'  => molla_defaults( 'breadcrumb_width' ),
					'choices'  => array(
						''                => esc_html__( 'Page Width', 'molla' ),
						'container'       => esc_html__( 'Container', 'molla' ),
						'container-fluid' => esc_html__( 'Container-Fluid', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'breadcrumb_divider_active',
					'label'     => esc_html__( 'Show Breadcrumb Divider', 'molla' ),
					'section'   => 'page_title',
					'default'   => molla_defaults( 'breadcrumb_divider_active' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-buttonset',
					'settings'  => 'breadcrumb_divider_width',
					'label'     => esc_html__( 'Breadcrumb Divider Width', 'molla' ),
					'section'   => 'page_title',
					'default'   => molla_defaults( 'breadcrumb_divider_width' ),
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
					'type'      => 'color',
					'settings'  => 'breadcrumb_divider_color',
					'label'     => esc_html__( 'Divider Color', 'molla' ),
					'choices'   => array(
						'alpha' => true,
					),
					'section'   => 'page_title',
					'default'   => molla_defaults( 'breadcrumb_divider_color' ),
					'transport' => 'postMessage',
				)
			);
		}
	}
endif;

return new Molla_Elements_Options();

