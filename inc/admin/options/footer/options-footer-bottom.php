<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Footer_Bottom_Options' ) ) :

	class Molla_Footer_Bottom_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'footer_bottom',
				array(
					'title' => esc_html__( 'Footer Bottom', 'molla' ),
					'panel' => 'footer',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'footer_bottom_divider_active',
					'section'   => 'footer_bottom',
					'label'     => esc_html__( 'Enable Divider', 'molla' ),
					'default'   => molla_defaults( 'footer_bottom_divider_active' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-buttonset',
					'settings'  => 'footer_bottom_divider_width',
					'label'     => esc_html__( 'Divider Width', 'molla' ),
					'section'   => 'footer_bottom',
					'default'   => molla_defaults( 'footer_bottom_divider_width' ),
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
					'settings'  => 'footer_bottom_pt',
					'label'     => esc_html__( 'Padding Top ( px )', 'molla' ),
					'section'   => 'footer_bottom',
					'default'   => molla_defaults( 'footer_bottom_pt' ),
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
					'settings'  => 'footer_bottom_pb',
					'label'     => esc_html__( 'Padding Bottom ( px )', 'molla' ),
					'section'   => 'footer_bottom',
					'default'   => molla_defaults( 'footer_bottom_pb' ),
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
					'settings'  => 'footer_bottom_bg',
					'label'     => '',
					'section'   => 'footer_bottom',
					'default'   => molla_defaults( 'footer_bottom_bg' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_footer_bottom_conf',
					'label'    => '',
					'section'  => 'footer_bottom',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Footer Bottom', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'sortable',
					'settings'  => 'footer_bottom_items',
					'label'     => esc_html__( 'Select Items', 'molla' ),
					'section'   => 'footer_bottom',
					'default'   => molla_defaults( 'footer_bottom_items' ),
					'choices'   => array(
						'custom_html' => 'Custom Html',
						'payments'    => 'Payments',
						'widget'      => 'Widget',
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-image',
					'settings'  => 'footer_bottom_dir',
					'label'     => esc_html__( 'Layout', 'molla' ),
					'section'   => 'footer_bottom',
					'default'   => molla_defaults( 'footer_bottom_dir' ),
					'choices'   => array(
						''                 => MOLLA_URI . '/assets/images/customize/footer_bottom1.png',
						' footer-vertical' => MOLLA_URI . '/assets/images/customize/footer_bottom2.png',
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'textarea',
					'settings'    => 'footer_custom_html',
					'section'     => 'footer_bottom',
					'label'       => esc_html__( 'Custom Html', 'molla' ),
					'description' => esc_html__( 'Add any HTML or Shortcode here...', 'molla' ),
					'default'     => molla_defaults( 'footer_custom_html' ),
					'transport'   => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'image',
					'settings'  => 'footer_payment',
					'label'     => esc_html__( 'Payment', 'molla' ),
					'section'   => 'footer_bottom',
					'default'   => molla_defaults( 'footer_payment' ),
					'transport' => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Footer_Bottom_Options();
