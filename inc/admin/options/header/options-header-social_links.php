<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Header_Share_Options' ) ) :

	class Molla_Header_Share_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'header_social_links',
				array(
					'title' => esc_html__( 'Social Links', 'molla' ),
					'panel' => 'header',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'sortable',
					'settings'  => 'header_social_links',
					'label'     => esc_html__( 'Check Items to Show', 'molla' ),
					'section'   => 'header_social_links',
					'default'   => molla_defaults( 'header_social_links' ),
					'choices'   => apply_filters(
						'molla_theme_op_nav_group',
						array(
							'facebook'  => esc_html__( 'Facebook', 'molla' ),
							'linkedin'  => esc_html__( 'LinkedIn', 'molla' ),
							'twitter'   => esc_html__( 'Twitter', 'molla' ),
							'instagram' => esc_html__( 'Instagram', 'molla' ),
							'youtube'   => esc_html__( 'Youtube', 'molla' ),
							'pinterest' => esc_html__( 'Pinterest', 'molla' ),
							'tumblr'    => esc_html__( 'Tumblr', 'molla' ),
							'whatsapp'  => esc_html__( 'Whatsapp', 'molla' ),
						)
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'facebook',
					'section'   => 'header_social_links',
					'label'     => esc_html__( 'Facebook', 'molla' ),
					'default'   => molla_defaults( 'facebook' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'linkedin',
					'section'   => 'header_social_links',
					'label'     => esc_html__( 'LinkedIn', 'molla' ),
					'default'   => molla_defaults( 'linkedin' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'twitter',
					'section'   => 'header_social_links',
					'label'     => esc_html__( 'Twitter', 'molla' ),
					'default'   => molla_defaults( 'twitter' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'instagram',
					'section'   => 'header_social_links',
					'label'     => esc_html__( 'Instagram', 'molla' ),
					'default'   => molla_defaults( 'instagram' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'youtube',
					'section'   => 'header_social_links',
					'label'     => esc_html__( 'Youtube', 'molla' ),
					'default'   => molla_defaults( 'youtube' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'pinterest',
					'section'   => 'header_social_links',
					'label'     => esc_html__( 'Pinterest', 'molla' ),
					'default'   => molla_defaults( 'pinterest' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'tumblr',
					'section'   => 'header_social_links',
					'label'     => esc_html__( 'Tumblr', 'molla' ),
					'default'   => molla_defaults( 'tumblr' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'whatsapp',
					'section'   => 'header_social_links',
					'label'     => esc_html__( 'WhatsApp', 'molla' ),
					'default'   => molla_defaults( 'whatsapp' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-image',
					'settings'  => 'header_social_type',
					'label'     => esc_html__( 'Type', 'molla' ),
					'section'   => 'header_social_links',
					'default'   => molla_defaults( 'header_social_type' ),
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
					'settings'  => 'header_social_size',
					'label'     => esc_html__( 'Size', 'molla' ),
					'section'   => 'header_social_links',
					'default'   => molla_defaults( 'header_social_size' ),
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

return new Molla_Header_Share_Options();
