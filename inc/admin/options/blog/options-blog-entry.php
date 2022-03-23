<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Blog_Entry_Options' ) ) {
	/**
	* Blog single page option
	*/
	class Molla_Blog_Entry_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'blog_entry',
				array(
					'title' => esc_html__( 'Blog Archive', 'molla' ),
					'panel' => 'blog',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_entry_page_layout',
					'label'    => '',
					'section'  => 'blog_entry',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Page Layout', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'blog_entry_page_layout',
					'label'    => esc_html__( 'Page Layout Mode', 'molla' ),
					'section'  => 'blog_entry',
					'default'  => molla_defaults( 'blog_entry_page_layout' ),
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
					'settings'        => 'blog_entry_page_width',
					'label'           => esc_html__( 'Page Width', 'molla' ),
					'section'         => 'blog_entry',
					'default'         => molla_defaults( 'blog_entry_page_width' ),
					'choices'         => array(
						'container'       => MOLLA_URI . '/assets/images/customize/container.png',
						'container-fluid' => MOLLA_URI . '/assets/images/customize/container-fluid.png',
						''                => MOLLA_URI . '/assets/images/customize/fullwidth.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'blog_entry_page_layout',
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
					'settings'        => 'blog_entry_sidebar',
					'label'           => esc_html__( 'Sidebar Option', 'molla' ),
					'section'         => 'blog_entry',
					'default'         => molla_defaults( 'blog_entry_sidebar' ),
					'choices'         => array(
						'no'    => MOLLA_URI . '/assets/images/customize/nosidebar.png',
						'left'  => MOLLA_URI . '/assets/images/customize/leftsidebar.png',
						'right' => MOLLA_URI . '/assets/images/customize/rightsidebar.png',
					),
					'active_callback' => array(
						array(
							'setting'  => 'blog_entry_page_layout',
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
					'settings' => 'title_entry_type',
					'label'    => '',
					'section'  => 'blog_entry',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Blog', 'molla' ) . '</div>',
				)
			);

			/* Pro Features */
			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'blog_entry_layout',
					'label'    => esc_html__( 'Layout', 'molla' ),
					'section'  => 'blog_entry',
					'default'  => molla_defaults( 'blog_entry_layout' ),
					'choices'  => array(
						'grid'     => MOLLA_URI . '/assets/images/customize/grid.png',
						'creative' => MOLLA_URI . '/assets/images/customize/creative.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'slider',
					'settings' => 'blog_entry_cols',
					'label'    => esc_html__( 'Columns', 'molla' ),
					'section'  => 'blog_entry',
					'default'  => molla_defaults( 'blog_entry_cols' ),
					'choices'  => array(
						'min' => 1,
						'max' => 8,
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'toggle',
					'settings' => 'blog_entry_filter',
					'label'    => esc_html__( 'Show Category Filter', 'molla' ),
					'section'  => 'blog_entry',
					'default'  => molla_defaults( 'blog_entry_filter' ),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-buttonset',
					'settings'        => 'blog_filter_pos',
					'label'           => esc_html__( 'Category Filter Position', 'molla' ),
					'section'         => 'blog_entry',
					'default'         => molla_defaults( 'blog_filter_pos' ),
					'choices'         => array(
						'start'  => esc_html__( 'Left', 'molla' ),
						'center' => esc_html__( 'Center', 'molla' ),
						'end'    => esc_html__( 'Right', 'molla' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'blog_entry_filter',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);
			/* End */

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-image',
					'settings' => 'blog_entry_type',
					'label'    => esc_html__( 'Post type', 'molla' ),
					'section'  => 'blog_entry',
					'default'  => molla_defaults( 'blog_entry_type' ),
					'choices'  => array(
						'default' => MOLLA_URI . '/assets/images/customize/post1.png',
						'mask'    => MOLLA_URI . '/assets/images/customize/post2.png',
						'list'    => MOLLA_URI . '/assets/images/customize/post3.png',
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'radio-buttonset',
					'settings' => 'blog_entry_align',
					'label'    => esc_html__( 'Alignment', 'molla' ),
					'section'  => 'blog_entry',
					'default'  => molla_defaults( 'blog_entry_align' ),
					'choices'  => array(
						'left'   => esc_html__( 'Left', 'molla' ),
						'center' => esc_html__( 'Center', 'molla' ),
						'right'  => esc_html__( 'Right', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'multicheck',
					'settings' => 'blog_entry_visible_op',
					'label'    => esc_html__( 'Visible Options', 'molla' ),
					'section'  => 'blog_entry',
					'default'  => molla_defaults( 'blog_entry_visible_op' ),
					'choices'  => array(
						'f_image'   => esc_html__( 'Featured Image', 'molla' ),
						'date'      => esc_html__( 'Date', 'molla' ),
						'author'    => esc_html__( 'Author', 'molla' ),
						'comment'   => esc_html__( 'Comment', 'molla' ),
						'category'  => esc_html__( 'Category', 'molla' ),
						'content'   => esc_html__( 'Content', 'molla' ),
						'read_more' => esc_html__( 'Read More', 'molla' ),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'slider',
					'settings'        => 'blog_img_width',
					'label'           => esc_html__( 'Featured Image Width (cols)', 'molla' ),
					'section'         => 'blog_entry',
					'default'         => molla_defaults( 'blog_img_width' ),
					'choices'         => array(
						'min' => 1,
						'max' => 11,
					),
					'active_callback' => array(
						array(
							'setting'  => 'blog_entry_type',
							'operator' => '==',
							'value'    => 'list',
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'     => 'select',
					'settings' => 'blog_view_more_type',
					'label'    => esc_html__( 'Blog View More Type', 'molla' ),
					'section'  => 'blog_entry',
					'default'  => molla_defaults( 'blog_view_more_type' ),
					'choices'  => array(
						'pagination' => esc_html__( 'pagination', 'molla' ),
						'button'     => esc_html__( 'Load More Button', 'molla' ),
						'scroll'     => esc_html__( 'Infinite Scroll', 'molla' ),
					),
				)
			);
		}
	}
}

new Molla_Blog_Entry_Options;
