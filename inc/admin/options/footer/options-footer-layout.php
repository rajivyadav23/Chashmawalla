<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Footer_Layout_Options' ) ) :

	class Molla_Footer_Layout_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'footer_layout',
				array(
					'title' => esc_html__( 'Layout', 'molla' ),
					'panel' => 'footer',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-image',
					'settings'  => 'footer_width',
					'section'   => 'footer_layout',
					'label'     => esc_html__( 'Width', 'molla' ),
					'default'   => molla_defaults( 'footer_width' ),
					'choices'   => array(
						'container'       => MOLLA_URI . '/assets/images/customize/container.png',
						'container-fluid' => MOLLA_URI . '/assets/images/customize/container-fluid.png',
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'text',
					'settings'    => 'footer_tooltip_label',
					'label'       => esc_html__( 'Tooltip Label', 'molla' ),
					'section'     => 'footer_layout',
					'default'     => molla_defaults( 'footer_tooltip_label' ),
					'description' => esc_html__( 'Footer tooltip is shown when your page sections are set as scrollable section.', 'molla' ),
					'transport'   => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_scroll_top',
					'label'    => '',
					'section'  => 'footer_layout',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Scroll To Top', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'scroll_top_icon',
					'label'     => esc_html__( 'Icon', 'molla' ),
					'section'   => 'footer_layout',
					'default'   => molla_defaults( 'scroll_top_icon' ),
					'tooltip'   => esc_html__( 'Please visit ', 'molla' ) . '<a href="https://d-themes.com/wordpress/molla/elements/elements-list/element-icons/" target="_blank">' . esc_html__( 'Molla Icon Store', 'molla' ) . '</a>.',
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'     => 'custom',
					'settings' => 'title_footer_block',
					'label'    => '',
					'section'  => 'footer_layout',
					'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Footer Block', 'molla' ) . '</div>',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'text',
					'settings'    => 'footer_block_name',
					'label'       => esc_html__( 'Block Name', 'molla' ),
					'section'     => 'footer_layout',
					'description' => esc_html__( 'Please input block id or slug using for footer.', 'molla' ),
					'tooltip'     => esc_html__( 'You should set this option to use block for footer.', 'molla' ),
					'default'     => molla_defaults( 'footer_block_name' ),
				)
			);
		}
	}

endif;

return new Molla_Footer_Layout_Options();
