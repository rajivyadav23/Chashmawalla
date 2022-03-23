<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Pagination_Options' ) ) :

	class Molla_Pagination_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'advanced_pagination',
				array(
					'title' => esc_html__( 'Pagination', 'molla' ),
					'panel' => 'advanced',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-buttonset',
					'settings' => 'pagination_pos',
					'label'    => esc_html__( 'Pagination Position', 'molla' ),
					'section'  => 'advanced_pagination',
					'default'  => molla_defaults( 'pagination_pos' ),
					'choices'  => array(
						'start'  => esc_html__( 'Left', 'molla' ),
						'center' => esc_html__( 'Center', 'molla' ),
						'end'    => esc_html__( 'Right', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'toggle',
					'settings' => 'show_disabled_paginate',
					'label'    => esc_html__( 'Show Disabled Paginate', 'molla' ),
					'section'  => 'advanced_pagination',
					'default'  => molla_defaults( 'show_disabled_paginate' ),
				)
			);
		}
	}
endif;

return new Molla_Pagination_Options();

