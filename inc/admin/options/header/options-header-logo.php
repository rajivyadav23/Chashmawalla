<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * logo & site identify settings
 */

if ( ! class_exists( 'Molla_Site_Identify_Options' ) ) :

	class Molla_Site_Identify_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'title_tagline',
				array(
					'title' => esc_html__( 'Logo & Site Identity', 'molla' ),
					'panel' => 'header',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'image',
					'settings'  => 'site_logo',
					'label'     => esc_html__( 'Logo', 'molla' ),
					'section'   => 'title_tagline',
					'default'   => molla_defaults( 'site_logo' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'image',
					'settings'  => 'site_retina_logo',
					'label'     => esc_html__( 'Retina Logo', 'molla' ),
					'section'   => 'title_tagline',
					'default'   => molla_defaults( 'site_retina_logo' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-buttonset',
					'settings'  => 'logo_case',
					'section'   => 'title_tagline',
					'default'   => 'normal',
					'choices'   => array(
						'normal' => esc_html__( 'Normal', 'molla' ),
						'sticky' => esc_html__( 'Sticky', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'logo_spacing',
					'label'           => esc_html__( 'Logo Spacing', 'molla' ),
					'section'         => 'title_tagline',
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'         => molla_defaults( 'logo_spacing' ),
					'active_callback' => array(
						array(
							'setting'  => 'logo_case',
							'operator' => '==',
							'value'    => 'normal',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'logo_spacing_sticky',
					'label'           => esc_html__( 'Logo Spacing', 'molla' ),
					'section'         => 'title_tagline',
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'         => molla_defaults( 'logo_spacing_sticky' ),
					'active_callback' => array(
						array(
							'setting'  => 'logo_case',
							'operator' => '==',
							'value'    => 'sticky',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimension',
					'settings'        => 'logo_width',
					'section'         => 'title_tagline',
					'label'           => esc_html__( 'Logo width ( px )', 'molla' ),
					'tooltip'         => esc_html__( 'Leave it blank to make it have original width.', 'molla' ),
					'default'         => molla_defaults( 'logo_width' ),
					'active_callback' => array(
						array(
							'setting'  => 'logo_case',
							'operator' => '==',
							'value'    => 'normal',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimension',
					'settings'        => 'logo_width_sticky',
					'section'         => 'title_tagline',
					'label'           => esc_html__( 'Logo width ( px )', 'molla' ),
					'tooltip'         => esc_html__( 'Leave it blank to make it have original width.', 'molla' ),
					'default'         => molla_defaults( 'logo_width_sticky' ),
					'active_callback' => array(
						array(
							'setting'  => 'logo_case',
							'operator' => '==',
							'value'    => 'sticky',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'logo_case',
							'operator' => '==',
							'value'    => 'sticky',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimension',
					'settings'        => 'logo_max_width',
					'section'         => 'title_tagline',
					'label'           => esc_html__( 'Logo max width ( px )', 'molla' ),
					'tooltip'         => esc_html__( 'Leave it blank to make it auto fit inside the logo container.', 'molla' ),
					'default'         => molla_defaults( 'logo_max_width' ),
					'active_callback' => array(
						array(
							'setting'  => 'logo_case',
							'operator' => '==',
							'value'    => 'normal',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimension',
					'settings'        => 'logo_max_width_sticky',
					'section'         => 'title_tagline',
					'label'           => esc_html__( 'Logo max width ( px )', 'molla' ),
					'tooltip'         => esc_html__( 'Leave it blank to make it auto fit inside the logo container.', 'molla' ),
					'default'         => molla_defaults( 'logo_max_width_sticky' ),
					'active_callback' => array(
						array(
							'setting'  => 'logo_case',
							'operator' => '==',
							'value'    => 'sticky',
						),
					),
					'transport'       => 'postMessage',
				)
			);

		}
	}
endif;

return new Molla_Site_Identify_Options();
