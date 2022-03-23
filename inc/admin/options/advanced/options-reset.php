<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Reset_Options' ) ) :

	class Molla_Reset_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'reset_op',
				array(
					'title' => esc_html__( 'Reset Options', 'molla' ),
					'panel' => 'advanced',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'custom_reset_options',
					'label'    => '',
					'section'  => 'reset_op',
					'default'  => '<button name="Reset" id="molla-reset-options" class="button-primary button" title="' . esc_html__( 'Reset Theme Options', 'molla' ) . '">' . esc_html__( 'Reset Theme Options', 'molla' ) . '</button>',
				)
			);
		}
	}
endif;

return new Molla_Reset_Options();

