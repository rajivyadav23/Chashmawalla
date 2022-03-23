<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_General_Options' ) ) :

	class Molla_General_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */
		public function customize_options() {

			Molla_Option::add_section(
				'general',
				array(
					'title'    => esc_html__( 'General', 'molla' ),
					'priority' => 1,
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_loading_overlay',
					'label'    => '',
					'section'  => 'general',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Loading Overlay', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'toggle',
					'settings'    => 'loading_overlay',
					'label'       => esc_html__( 'Enable Loading-Overlay', 'molla' ),
					'section'     => 'general',
					'description' => esc_html__( 'Loading overlay control is shown until whole page is loaded.', 'molla' ),
					'default'     => molla_defaults( 'loading_overlay' ),
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_skeleton',
					'label'    => '',
					'section'  => 'general',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Skeleton Screen', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'toggle',
					'settings'    => 'skeleton_screen',
					'label'       => esc_html__( 'Enable Skeleton Screens', 'molla' ),
					'section'     => 'general',
					'description' => esc_html__( 'Skeleton screen is applied for sidebar and several post types.', 'molla' ),
					'default'     => molla_defaults( 'skeleton_screen' ),
					'transport'   => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_sidebar',
					'label'    => '',
					'section'  => 'general',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Sidebar', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'slider',
					'settings'  => 'sidebar_width',
					'label'     => esc_html__( 'Sidebar ( % )', 'molla' ),
					'section'   => 'general',
					'tooltip'   => esc_html__( 'Upper 1200px screen size, sidebar width is 20% to prevent large width.', 'molla' ),
					'default'   => molla_defaults( 'sidebar_width' ),
					'choices'   => array(
						'min'  => 1,
						'max'  => 99,
						'step' => 1,
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'sidebar_sticky',
					'label'     => esc_html__( 'Enable Sticky Sidebar', 'molla' ),
					'section'   => 'general',
					'default'   => molla_defaults( 'sidebar_sticky' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_google_map',
					'label'    => '',
					'section'  => 'general',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Google Map API', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'google_map_key',
					'label'     => esc_html__( 'Google Map API Key', 'molla' ),
					'section'   => 'general',
					'default'   => molla_defaults( 'google_map_key' ),
					'transport' => 'postMessage',
				)
			);

		}
	}
endif;

return new Molla_General_Options();
