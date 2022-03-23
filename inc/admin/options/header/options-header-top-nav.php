<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Header_Top_Nav_Options' ) ) :

	class Molla_Header_Top_Nav_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'header_responsive_group',
				array(
					'title' => esc_html__( 'Responsive Group', 'molla' ),
					'panel' => 'header',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'sortable',
					'settings'    => 'top_nav_items',
					'label'       => esc_html__( 'Visible Items', 'molla' ),
					'description' => esc_html__( 'Check items to show.', 'molla' ),
					'section'     => 'header_responsive_group',
					'default'     => molla_defaults( 'top_nav_items' ),
					'tooltip'     => esc_html__( 'These items will be shown normally in desktop but in mobile will be one grouped items.', 'molla' ),
					'choices'     => apply_filters(
						'molla_theme_op_nav_group',
						array(
							'top_nav'           => 'Top Navigation',
							'inline-wishlist'   => 'Wishlist Link',
							'currency_switcher' => 'Currency Switcher',
							'lang_switcher'     => 'Langauge Switcher',
							'login-form'        => 'Login Form',
						)
					),
					'transport'   => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Header_Top_Nav_Options();
