<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Blog_General_Options' ) ) :

	class Molla_Blog_General_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'blog_general',
				array(
					'title' => esc_html__( 'General', 'molla' ),
					'panel' => 'blog',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_blog_excerpt',
					'label'    => '',
					'section'  => 'blog_general',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Excerpt', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'blog_excerpt_unit',
					'label'     => esc_html__( 'Excerpt By', 'molla' ),
					'section'   => 'blog_general',
					'default'   => molla_defaults( 'blog_excerpt_unit' ),
					'choices'   => array(
						'word'      => esc_html__( 'Word', 'molla' ),
						'character' => esc_html__( 'Character', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'slider',
					'settings'  => 'blog_excerpt_length',
					'label'     => esc_html__( 'Excerpt Length', 'molla' ),
					'section'   => 'blog_general',
					'default'   => molla_defaults( 'blog_excerpt_length' ),
					'choices'   => array(
						'min' => '1',
						'max' => '500',
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_blog_more',
					'label'    => '',
					'section'  => 'blog_general',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Read More', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'text',
					'settings' => 'blog_more_label',
					'label'    => esc_html__( 'Read More Label', 'molla' ),
					'section'  => 'blog_general',
					'default'  => molla_defaults( 'blog_more_label' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'toggle',
					'settings' => 'blog_more_icon',
					'label'    => esc_html__( 'Enable Read More Icon', 'molla' ),
					'section'  => 'blog_general',
					'default'  => molla_defaults( 'blog_more_icon' ),
				)
			);
		}
	}

endif;

return new Molla_Blog_General_Options();
