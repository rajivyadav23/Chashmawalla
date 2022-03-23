<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Blog_Single_Options' ) ) {
	/**
	* Blog single page option
	*/
	class Molla_Blog_Single_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'blog_single',
				array(
					'title' => esc_html__( 'Single Post', 'molla' ),
					'panel' => 'blog',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_single_page_layout',
					'label'    => '',
					'section'  => 'blog_single',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Page Layout', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'checkbox',
					'settings' => 'single_blog_page_header',
					'label'    => esc_html__( 'Show Page Header', 'molla' ),
					'section'  => 'blog_single',
					'default'  => molla_defaults( 'single_blog_page_header' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'blog_single_page_layout',
					'label'    => esc_html__( 'Page Layout Mode', 'molla' ),
					'section'  => 'blog_single',
					'default'  => molla_defaults( 'blog_single_page_layout' ),
					'choices'  => array(
						''       => MOLLA_URI . '/assets/images/customize/default.png',
						'custom' => MOLLA_URI . '/assets/images/customize/plus.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-image',
					'settings'        => 'blog_single_page_width',
					'label'           => esc_html__( 'Page Width', 'molla' ),
					'section'         => 'blog_single',
					'default'         => molla_defaults( 'blog_single_page_width' ),
					'choices'         => array(
						'container'       => MOLLA_URI . '/assets/images/customize/container.png',
						'container-fluid' => MOLLA_URI . '/assets/images/customize/container-fluid.png',
						''                => MOLLA_URI . '/assets/images/customize/fullwidth.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'blog_single_page_layout',
							'operator' => '!=',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-image',
					'settings'        => 'blog_single_sidebar',
					'label'           => esc_html__( 'Sidebar Option', 'molla' ),
					'section'         => 'blog_single',
					'default'         => molla_defaults( 'blog_single_sidebar' ),
					'choices'         => array(
						'no'    => MOLLA_URI . '/assets/images/customize/nosidebar.png',
						'left'  => MOLLA_URI . '/assets/images/customize/leftsidebar.png',
						'right' => MOLLA_URI . '/assets/images/customize/rightsidebar.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'blog_single_page_layout',
							'operator' => '!=',
							'value'    => '',
						),
					),
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_single_',
					'label'    => '',
					'section'  => 'blog_single',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Single Post', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'blog_single_featured_image',
					'label'     => esc_html__( 'Enable featured image', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'blog_single_featured_image' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'blog_single_meta',
					'label'     => esc_html__( 'Enable meta', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'blog_single_meta' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'blog_single_category',
					'label'     => esc_html__( 'Enable category', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'blog_single_category' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'blog_single_tag',
					'label'     => esc_html__( 'Enable Tags', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'blog_single_tag' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'blog_single_share',
					'label'     => esc_html__( 'Enable Share Icons', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'blog_single_share' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'blog_single_author_box',
					'label'     => esc_html__( 'Enable Blog Author Box', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'blog_single_author_box' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'blog_single_prev_next_nav',
					'label'     => esc_html__( 'Enable Prev/Next Navigation', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'blog_single_prev_next_nav' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'blog_single_related',
					'label'     => esc_html__( 'Enable Related Posts', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'blog_single_related' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'blog_single_featured_image_type',
					'label'    => esc_html__( 'Featured Image Position', 'molla' ),
					'section'  => 'blog_single',
					'default'  => molla_defaults( 'blog_single_featured_image_type' ),
					'choices'  => array(
						'inner-content' => MOLLA_URI . '/assets/images/customize/blog_img1.png',
						'outer-content' => MOLLA_URI . '/assets/images/customize/blog_img2.png',
						'fullwidth'     => MOLLA_URI . '/assets/images/customize/blog_img3.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'blog_single_share_pos',
					'label'     => esc_html__( 'Share Icons Position', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'blog_single_share_pos' ),
					'choices'   => array(
						''             => esc_html__( 'Not Sticky', 'molla' ),
						'sticky-left'  => esc_html__( 'Sticky In Left', 'molla' ),
						'sticky-right' => esc_html__( 'Sticky In Right', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_related_posts',
					'label'    => '',
					'section'  => 'blog_single',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Related Posts', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-buttonset',
					'settings'  => 'related_posts_sort_by',
					'label'     => esc_html__( 'Sort By', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'related_posts_sort_by' ),
					'choices'   => array(
						'random'        => esc_html__( 'Random', 'molla' ),
						'date'          => esc_html__( 'Date', 'molla' ),
						'id'            => esc_html__( 'ID', 'molla' ),
						'modified_date' => esc_html__( 'Modified Date', 'molla' ),
						'comment_count' => esc_html__( 'Comment Count', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-buttonset',
					'settings'        => 'related_posts_sort_order',
					'label'           => esc_html__( 'Sort Order', 'molla' ),
					'section'         => 'blog_single',
					'default'         => molla_defaults( 'related_posts_sort_order' ),
					'choices'         => array(
						'desc' => esc_html__( 'Desc', 'molla' ),
						'asc'  => esc_html__( 'Asc', 'molla' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'related_posts_sort_by',
							'operator' => '!=',
							'value'    => 'random',
						),
					),
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'slider',
					'settings'  => 'related_posts_cols',
					'label'     => esc_html__( 'Columns', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'related_posts_cols' ),
					'choices'   => array(
						'min' => 1,
						'max' => 8,
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'slider',
					'settings'  => 'related_posts_padding',
					'label'     => esc_html__( 'Padding ( px )', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'related_posts_padding' ),
					'choices'   => array(
						'min'  => 0,
						'max'  => 40,
						'step' => 5,
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'related_posts_nav',
					'label'     => esc_html__( 'Show nav', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'related_posts_nav' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'related_posts_dot',
					'label'     => esc_html__( 'Show dots', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'related_posts_dot' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'related_posts_loop',
					'label'     => esc_html__( 'Loop enable', 'molla' ),
					'section'   => 'blog_single',
					'default'   => molla_defaults( 'related_posts_loop' ),
					'transport' => 'postMessage',
				)
			);
		}
	}
}

new Molla_Blog_Single_Options;
