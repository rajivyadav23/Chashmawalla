<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Molla_Admin_Panel {

	public function __construct() {
		if ( is_admin() && current_user_can( 'edit_theme_options' ) ) {
			$title = 'Molla';

			add_menu_page( 'Molla', 'Molla', 'administrator', 'molla', array( $this, 'welcome_screen' ), 'dashicons-molla-logo', '2' );
			add_submenu_page( 'molla', esc_html__( 'Theme license', 'molla' ), esc_html__( 'Theme License', 'molla' ), 'administrator', 'molla', array( $this, 'welcome_screen' ) );
			add_submenu_page( 'molla', esc_html__( 'Change Log', 'molla' ), esc_html__( 'Change Log', 'molla' ), 'administrator', 'molla-changelog', array( $this, 'changelog' ) );
			add_submenu_page( 'molla', esc_html__( 'Theme Options', 'molla' ), esc_html__( 'Theme Options', 'molla' ), 'administrator', 'customize.php' );
		}
	}



	public function welcome_screen() {
		require_once MOLLA_ADMIN . '/panel/views/welcome.php';
	}

	public function changelog() {
		require_once MOLLA_ADMIN . '/panel/views/changelog.php';
	}
}

new Molla_Admin_Panel();
