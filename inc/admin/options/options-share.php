<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * layout settings
 */

if ( ! class_exists( 'Molla_Share_Options' ) ) :

	class Molla_Share_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		/**
		 * Customizer Options
		 */
		public function customize_options() {

			Molla_Option::add_section(
				'share',
				array(
					'title'    => esc_html__( 'Share', 'molla' ),
					'priority' => 10,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'sortable',
					'settings'  => 'share_icons',
					'label'     => esc_html__( 'Share Icons', 'molla' ),
					'section'   => 'share',
					'default'   => molla_defaults( 'share_icons' ),
					'choices'   => array(
						'facebook'   => esc_html__( 'Facebook', 'molla' ),
						'linkedin'   => esc_html__( 'LinkedIn', 'molla' ),
						'twitter'    => esc_html__( 'Twitter', 'molla' ),
						'email'      => esc_html__( 'Email', 'molla' ),
						'pinterest'  => esc_html__( 'Pinterest', 'molla' ),
						'googleplus' => esc_html__( 'Google Plus', 'molla' ),
						'vk'         => esc_html__( 'VKontakte', 'molla' ),
						'tumblr'     => esc_html__( 'Tumblr', 'molla' ),
						'whatsapp'   => esc_html__( 'WhatsApp (Only for Mobile)', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-image',
					'settings'  => 'share_icon_type',
					'label'     => esc_html__( 'Type', 'molla' ),
					'section'   => 'share',
					'default'   => molla_defaults( 'share_icon_type' ),
					'choices'   => array(
						''               => MOLLA_URI . '/assets/images/customize/social_icon1.png',
						'colored-simple' => MOLLA_URI . '/assets/images/customize/social_icon2.png',
						'circle'         => MOLLA_URI . '/assets/images/customize/social_icon3.png',
						'colored-circle' => MOLLA_URI . '/assets/images/customize/social_icon4.png',
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-image',
					'settings'  => 'share_icon_size',
					'label'     => esc_html__( 'Size', 'molla' ),
					'section'   => 'share',
					'default'   => molla_defaults( 'share_icon_size' ),
					'choices'   => array(
						''      => MOLLA_URI . '/assets/images/customize/social_icon4.png',
						'small' => MOLLA_URI . '/assets/images/customize/social_icon5.png',
					),
					'transport' => 'postMessage',
				)
			);
		}
	}
endif;

return new Molla_Share_Options();

