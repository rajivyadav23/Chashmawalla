<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Main_Menu_Options' ) ) :

	class Molla_Main_Menu_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'main_menu',
				array(
					'title'    => esc_html__( 'Main Menu', 'molla' ),
					'panel'    => 'nav_menus',
					'priority' => 0,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'main_menu_skin',
					'label'     => esc_html__( 'Skin', 'molla' ),
					'section'   => 'main_menu',
					'tooltip'   => sprintf( '%1$s%2$s%3$s%4$s', esc_html__( 'You can set menu skins in ', 'molla' ), '<a href="#" data-target="menu_skins" class="customizer-nav-item" data-type="control">', esc_html__( 'Menu Skins', 'molla' ), '</a>', esc_html__( ' panel', 'molla' ) ),
					'default'   => molla_defaults( 'main_menu_skin' ),
					'choices'   => array(
						'skin1' => esc_html__( 'Skin 1', 'molla' ),
						'skin2' => esc_html__( 'Skin 2', 'molla' ),
						'skin3' => esc_html__( 'Skin 3', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Main_Menu_Options();
