<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * mobiel menu settings
 */
if ( ! class_exists( 'Molla_Mobile_Menu_Options' ) ) :

	class Molla_Mobile_Menu_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */

		public function customize_options() {

			Molla_Option::add_section(
				'mobile_menu',
				array(
					'title'    => esc_html__( 'Mobile Menu', 'molla' ),
					'panel'    => 'nav_menus',
					'priority' => 0,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-buttonset',
					'settings' => 'mobile_menu_skin',
					'label'    => esc_html__( 'Skin', 'molla' ),
					'section'  => 'mobile_menu',
					'default'  => molla_defaults( 'mobile_menu_skin' ),
					'choices'  => array(
						'mobile-menu-light' => esc_html__( 'Light', 'molla' ),
						''                  => esc_html__( 'Dark', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_select_menu',
					'label'    => '',
					'section'  => 'mobile_menu',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Select Menu', 'molla' ) . '</div>',
				)
			);

			$nav_menus = wp_get_nav_menus();
			$menus     = array();
			foreach ( $nav_menus as $menu ) {
				$menus[ (string) $menu->term_id ] = esc_html( $menu->name );
			}

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'sortable',
					'settings'  => 'mobile_menus',
					'label'     => '',
					'section'   => 'mobile_menu',
					'default'   => molla_defaults( 'mobile_menus' ),
					'choices'   => $menus,
					'transport' => 'postMessage',
				)
			);
		}
	}
endif;

return new Molla_Mobile_Menu_Options();

