<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Error_404_Options' ) ) :

	class Molla_Error_404_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'error',
				array(
					'title' => esc_html__( 'Error 404', 'molla' ),
					'panel' => 'advanced',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'text',
					'settings' => 'error-block-name',
					'label'    => esc_html__( '404 Page Block ID or Slug', 'molla' ),
					'section'  => 'error',
					'tooltip'  => esc_html__( 'Please input block name using for 404 page.', 'molla' ),
					'default'  => molla_defaults( 'error-block-name' ),
				)
			);

		}
	}
endif;

return new Molla_Error_404_Options();

