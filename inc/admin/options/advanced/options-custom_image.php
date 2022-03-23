<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Custom_Image_Size_Options' ) ) :

	class Molla_Custom_Image_Size_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'custom-image-size',
				array(
					'title' => esc_html__( 'Custom Image Size', 'molla' ),
					'panel' => 'advanced',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'dimensions',
					'settings' => 'custom_image_size',
					'label'    => esc_html__( 'Register Custom Image Size (px)', 'molla' ),
					'section'  => 'custom-image-size',
					'default'  => molla_defaults( 'custom_image_size' ),
					'tooltip'  => esc_html__( 'Don\'t forget to regenerate previously uploaded images.', 'molla' ),
				)
			);
		}
	}
endif;

return new Molla_Custom_Image_Size_Options();

