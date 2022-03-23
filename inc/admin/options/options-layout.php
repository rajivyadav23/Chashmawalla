<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Layout_Options' ) ) :

	class Molla_Layout_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			$boxed_active = array(
				array(
					'setting'  => 'body_layout',
					'operator' => '!=',
					'value'    => 'full-width',
				),
			);

			Molla_Option::add_section(
				'layout',
				array(
					'title'    => esc_html__( 'Layout', 'molla' ),
					'priority' => 2,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-image',
					'settings'  => 'body_layout',
					'label'     => esc_html__( 'Layout Mode', 'molla' ),
					'section'   => 'layout',
					'default'   => molla_defaults( 'body_layout' ),
					'choices'   => array(
						'full-width' => MOLLA_URI . '/assets/images/customize/full.png',
						'boxed'      => MOLLA_URI . '/assets/images/customize/boxed.png',
						'framed'     => MOLLA_URI . '/assets/images/customize/framed.png',
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'page_width',
					'label'    => esc_html__( 'Page Width', 'molla' ),
					'section'  => 'layout',
					'default'  => molla_defaults( 'page_width' ),
					'choices'  => array(
						'container'       => MOLLA_URI . '/assets/images/customize/container.png',
						'container-fluid' => MOLLA_URI . '/assets/images/customize/container-fluid.png',
						''                => MOLLA_URI . '/assets/images/customize/fullwidth.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'sidebar_option',
					'label'    => esc_html__( 'Sidebar Option', 'molla' ),
					'section'  => 'layout',
					'default'  => molla_defaults( 'sidebar_option' ),
					'choices'  => array(
						'no'    => MOLLA_URI . '/assets/images/customize/nosidebar.png',
						'left'  => MOLLA_URI . '/assets/images/customize/leftsidebar.png',
						'right' => MOLLA_URI . '/assets/images/customize/rightsidebar.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'text',
					'settings'    => 'container_width',
					'label'       => esc_html__( 'Container Width ( px )', 'molla' ),
					'section'     => 'layout',
					'transport'   => 'postMessage',
					'description' => esc_html__( 'Leave it black to set as default value.', 'molla' ),
					'tooltip'     => esc_html__( 'Default width is 1188px.', 'molla' ),
					'default'     => molla_defaults( 'container_width' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'grid_gutter_width',
					'label'     => esc_html__( 'Grid Gutter Width ( px )', 'molla' ),
					'tooltip'   => esc_html__( 'This is the space between column and column. If you leave it blank 0px is set.', 'molla' ),
					'section'   => 'layout',
					'transport' => 'postMessage',
					'default'   => molla_defaults( 'grid_gutter_width' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'background',
					'settings'  => 'content_bg',
					'label'     => esc_html__( 'Content Background', 'molla' ),
					'section'   => 'layout',
					'default'   => molla_defaults( 'content_bg' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'title_boxed',
					'label'           => '',
					'section'         => 'layout',
					'default'         => '<div class="options-title">' . esc_html__( 'Boxed / Framed', 'molla' ) . '</div>',
					'active_callback' => $boxed_active,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'text',
					'settings'        => 'content_box_width',
					'label'           => esc_html__( 'Site boxed/framed Width ( px )', 'molla' ),
					'section'         => 'layout',
					'tooltip'         => esc_html__( 'Default width is 1500px.', 'molla' ),
					'default'         => molla_defaults( 'content_box_width' ),
					'active_callback' => $boxed_active,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimension',
					'settings'        => 'content_box_padding',
					'label'           => esc_html__( 'Frame Padding', 'molla' ),
					'section'         => 'layout',
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.<br>Default value is '30px'.", 'molla' ),
					'default'         => molla_defaults( 'content_box_padding' ),
					'active_callback' => array(
						array(
							'setting'  => 'body_layout',
							'operator' => '==',
							'value'    => 'framed',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'background',
					'settings'        => 'frame_bg',
					'label'           => esc_html__( 'Frame Background', 'molla' ),
					'section'         => 'layout',
					'default'         => molla_defaults( 'frame_bg' ),
					'active_callback' => $boxed_active,
					'transport'       => 'postMessage',
				)
			);
		}
	}
endif;

return new Molla_Layout_Options();

