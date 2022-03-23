<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Performance_Options' ) ) :

	class Molla_Performance_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'performance',
				array(
					'title' => esc_html__( 'Performance', 'molla' ),
					'panel' => 'advanced',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_lazy_load_img',
					'label'    => '',
					'section'  => 'performance',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Lazyload Image', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'lazy_load_img',
					'label'     => esc_html__( 'Enable Image-LazyLoad', 'molla' ),
					'section'   => 'performance',
					'tooltip'   => esc_html__( 'Images will be loaded at the time of appearing itself.', 'molla' ),
					'default'   => molla_defaults( 'lazy_load_img' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'lazy_load_img_back',
					'label'           => esc_html__( 'Lazy-load Background', 'molla' ),
					'section'         => 'performance',
					'description'     => esc_html__( 'Choose color to display before images are loaded.', 'molla' ),
					'default'         => molla_defaults( 'lazy_load_img_back' ),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'lazy_load_img',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_live_search',
					'label'    => '',
					'section'  => 'performance',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Live Search', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'live_search',
					'label'     => esc_html__( 'Enable Live Search', 'molla' ),
					'section'   => 'performance',
					'tooltip'   => esc_html__( 'You will be able to browse search results by dropdown popup.', 'molla' ),
					'default'   => molla_defaults( 'live_search' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_lazy_menu',
					'label'    => '',
					'section'  => 'performance',
					'default'  => '<div class="customize-control-title options-title">' . __( 'Lazyload Menu', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'lazy_load_menu',
					'label'     => __( 'Enable Menu-Lazyload', 'molla' ),
					'section'   => 'performance',
					'tooltip'   => __( 'Menus will be loaded after page loading is completed.', 'molla' ),
					'default'   => molla_defaults( 'lazy_load_menu' ),
					'transport' => 'postMessage',
				)
			);
		}
	}
endif;

return new Molla_Performance_Options();

