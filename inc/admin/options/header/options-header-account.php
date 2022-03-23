<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Header_Log_Form_Options' ) ) :

	class Molla_Header_Log_Form_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'header_account',
				array(
					'title' => esc_html__( 'Account', 'molla' ),
					'panel' => 'header',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'log_icon_class',
					'label'     => '',
					'section'   => 'header_account',
					'default'   => molla_defaults( 'log_icon_class' ),
					'label'     => esc_html__( 'Icon Class', 'molla' ),
					'transport' => 'postMessage',
					'tooltip'   => esc_html__( 'Please visit ', 'molla' ) . '<a href="https://d-themes.com/wordpress/molla/elements/elements-list/element-icons/" target="_blank">' . esc_html__( 'Molla Icon Store', 'molla' ) . '</a>.',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'log_in_label',
					'label'     => '',
					'section'   => 'header_account',
					'default'   => molla_defaults( 'log_in_label' ),
					'label'     => esc_html__( 'Log In Label', 'molla' ),
					'transport' => 'postMessage',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'register_label',
					'label'     => '',
					'section'   => 'header_account',
					'default'   => molla_defaults( 'register_label' ),
					'label'     => esc_html__( 'Register Label', 'molla' ),
					'transport' => 'postMessage',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'log_out_label',
					'label'     => '',
					'section'   => 'header_account',
					'default'   => molla_defaults( 'log_out_label' ),
					'label'     => esc_html__( 'Log Out Label', 'molla' ),
					'transport' => 'postMessage',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'show_register_label',
					'label'     => '',
					'section'   => 'header_account',
					'default'   => molla_defaults( 'show_register_label' ),
					'label'     => esc_html__( 'Show Register Label', 'molla' ),
					'transport' => 'postMessage',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'social_login',
					'label'     => '',
					'section'   => 'header_account',
					'default'   => molla_defaults( 'social_login' ),
					'label'     => esc_html__( 'Enable Social Login', 'molla' ),
					'transport' => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Header_Log_Form_Options();
