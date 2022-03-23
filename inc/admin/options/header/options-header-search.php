<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Header_Search_Options' ) ) :

	class Molla_Header_Search_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'header_search',
				array(
					'title' => esc_html__( 'Search Form', 'molla' ),
					'panel' => 'header',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-buttonset',
					'settings'  => 'header_search_style',
					'label'     => esc_html__( 'Search Box Types', 'molla' ),
					'section'   => 'header_search',
					'default'   => molla_defaults( 'header_search_style' ),
					'choices'   => array(
						'header-search-visible' => esc_html__( 'Static', 'molla' ),
						''                      => esc_html__( 'Toggle', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-buttonset',
					'settings'  => 'search_content_type',
					'label'     => esc_html__( 'Search Content Type', 'molla' ),
					'section'   => 'header_search',
					'default'   => molla_defaults( 'search_content_type' ),
					'choices'   => array(
						'all'     => esc_html__( 'All', 'molla' ),
						'post'    => esc_html__( 'Post', 'molla' ),
						'product' => esc_html__( 'Product', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'search_by_categories',
					'label'     => esc_html__( 'Search By Categories', 'molla' ),
					'section'   => 'header_search',
					'default'   => molla_defaults( 'search_by_categories' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'search_placeholder',
					'label'     => esc_html__( 'Placeholder', 'molla' ),
					'section'   => 'header_search',
					'default'   => molla_defaults( 'search_placeholder' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'color',
					'settings'  => 'header_search_border_color',
					'label'     => esc_html__( 'Border Color', 'molla' ),
					'section'   => 'header_search',
					'default'   => molla_defaults( 'header_search_border_color' ),
					'transport' => 'postMessage',
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'dimensions',
					'settings'  => 'header_search_border_width',
					'label'     => esc_html__( 'Border Width', 'molla' ),
					'section'   => 'header_search',
					'tooltip'   => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'", 'molla' ),
					'default'   => molla_defaults( 'header_search_border_width' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'dimension',
					'settings'    => 'header_search_border_radius',
					'label'       => esc_html__( 'Border Radius', 'molla' ),
					'section'     => 'header_search',
					'transport'   => 'postMessage',
					'tooltip'     => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'", 'molla' ),
					'description' => esc_html__( 'Leave it black to set as default value 0px.', 'molla' ),
					'default'     => molla_defaults( 'header_search_border_radius' ),
					'transport'   => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-image',
					'settings'  => 'header_search_btn_type',
					'label'     => esc_html__( 'Button Type', 'molla' ),
					'section'   => 'header_search',
					'default'   => molla_defaults( 'header_search_btn_type' ),
					'choices'   => array(
						''         => MOLLA_URI . '/assets/images/customize/search_btn1.png',
						'btn-icon' => MOLLA_URI . '/assets/images/customize/search_btn2.png',
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'header_search_icon_left',
					'label'     => esc_html__( 'Place Button at First', 'molla' ),
					'section'   => 'header_search',
					'default'   => molla_defaults( 'header_search_icon_left' ),
					'transport' => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Header_Search_Options();
