<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Import_Export_Options' ) ) :

	class Molla_Import_Export_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'import_export',
				array(
					'title' => esc_html__( 'Import & Export Options', 'molla' ),
					'panel' => 'advanced',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'text',
					'settings'    => 'import_src',
					'label'       => esc_html__( 'Import Theme Option', 'molla' ),
					'section'     => 'import_export',
					'description' => esc_html__( 'Please input file path to import. Source file should be typed text.', 'molla' ),
					'transport'   => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'custom',
					'settings' => 'molla_import_options',
					'label'    => '',
					'section'  => 'import_export',
					'default'  => '<input type="button" class="button button-primary molla_import_options" value="' . esc_html__( 'Import', 'molla' ) . '"/>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'text',
					'settings'    => 'export_src',
					'label'       => esc_html__( 'Export Theme Option', 'molla' ),
					'section'     => 'import_export',
					'description' => esc_html__( 'Please input export file path.', 'molla' ),
					'transport'   => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'custom',
					'settings' => 'molla_export_options',
					'label'    => '',
					'section'  => 'import_export',
					'default'  => '<input type="button" class="button button-primary molla_export_options" value="' . esc_html__( 'Export', 'molla' ) . '"/>',
				)
			);
		}
	}
endif;

return new Molla_Import_Export_Options();

