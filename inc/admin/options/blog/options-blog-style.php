<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Blog_Style_Options' ) ) {
	/**
	* Blog single page option
	*/
	class Molla_Blog_Style_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'blog_style',
				array(
					'title' => esc_html__( 'Style', 'molla' ),
					'panel' => 'blog',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_entry_style',
					'label'    => '',
					'section'  => 'blog_style',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Archive', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'blog_shadow_hover',
					'label'     => esc_html__( 'Enable Box Shadow on Hover', 'molla' ),
					'section'   => 'blog_style',
					'default'   => molla_defaults( 'blog_shadow_hover' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'entry_body_padding',
					'label'     => esc_html__( 'Content Padding', 'molla' ),
					'section'   => 'blog_style',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'   => molla_defaults( 'entry_body_padding' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_entry_meta',
					'label'     => esc_html__( 'Meta', 'molla' ),
					'section'   => 'blog_style',
					'default'   => molla_defaults( 'font_entry_meta' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'entry_meta_margin',
					'label'     => esc_html__( 'Meta Margin', 'molla' ),
					'section'   => 'blog_style',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'   => molla_defaults( 'entry_meta_margin' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_entry_title',
					'label'     => esc_html__( 'Title', 'molla' ),
					'section'   => 'blog_style',
					'default'   => molla_defaults( 'font_entry_title' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'entry_title_margin',
					'label'     => esc_html__( 'Title Margin', 'molla' ),
					'section'   => 'blog_style',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'   => molla_defaults( 'entry_title_margin' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_entry_cat',
					'label'     => esc_html__( 'Category', 'molla' ),
					'section'   => 'blog_style',
					'default'   => molla_defaults( 'font_entry_cat' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'entry_cat_margin',
					'label'     => esc_html__( 'Category Margin', 'molla' ),
					'section'   => 'blog_style',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'   => molla_defaults( 'entry_cat_margin' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_entry_excerpt',
					'label'     => esc_html__( 'Excerpt', 'molla' ),
					'section'   => 'blog_style',
					'default'   => molla_defaults( 'font_entry_excerpt' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'entry_excerpt_margin',
					'label'     => esc_html__( 'Excerpt Margin', 'molla' ),
					'section'   => 'blog_style',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'   => molla_defaults( 'entry_excerpt_margin' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_entry_view_more',
					'label'     => esc_html__( 'View More', 'molla' ),
					'section'   => 'blog_style',
					'default'   => molla_defaults( 'font_entry_view_more' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'entry_view_more_margin',
					'label'     => esc_html__( 'View More Margin', 'molla' ),
					'section'   => 'blog_style',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'   => molla_defaults( 'entry_view_more_margin' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_single_style',
					'label'    => '',
					'section'  => 'blog_style',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Single Post', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_single_meta',
					'label'     => esc_html__( 'Meta', 'molla' ),
					'section'   => 'blog_style',
					'default'   => molla_defaults( 'font_single_meta' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'single_meta_margin',
					'label'     => esc_html__( 'Meta Margin', 'molla' ),
					'section'   => 'blog_style',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'   => molla_defaults( 'single_meta_margin' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_single_title',
					'label'     => esc_html__( 'Title', 'molla' ),
					'section'   => 'blog_style',
					'default'   => molla_defaults( 'font_single_title' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'single_title_margin',
					'label'     => esc_html__( 'Title Margin', 'molla' ),
					'section'   => 'blog_style',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'   => molla_defaults( 'single_title_margin' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'typography',
					'settings'  => 'font_single_cat',
					'label'     => esc_html__( 'Category', 'molla' ),
					'section'   => 'blog_style',
					'default'   => molla_defaults( 'font_single_cat' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'single_cat_margin',
					'label'     => esc_html__( 'Category Margin', 'molla' ),
					'section'   => 'blog_style',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'default'   => molla_defaults( 'single_cat_margin' ),
					'transport' => 'postMessage',
				)
			);
		}
	}
}

new Molla_Blog_Style_Options;
