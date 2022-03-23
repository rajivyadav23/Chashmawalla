<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Menu_Skin_Options' ) ) :

	class Molla_Menu_Skin_Options {


		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			$skin1 = array(
				array(
					'setting'  => 'menu_skins',
					'operator' => '==',
					'value'    => 'skin-1',
				),
			);
			$skin2 = array(
				array(
					'setting'  => 'menu_skins',
					'operator' => '==',
					'value'    => 'skin-2',
				),
			);
			$skin3 = array(
				array(
					'setting'  => 'menu_skins',
					'operator' => '==',
					'value'    => 'skin-3',
				),
			);

			Molla_Option::add_section(
				'style_menu',
				array(
					'title'    => esc_html__( 'Menu Skins', 'molla' ),
					'panel'    => 'nav_menus',
					'priority' => 0,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'select',
					'settings'  => 'menu_skins',
					'label'     => esc_html__( 'Skins', 'molla' ),
					'section'   => 'style_menu',
					'default'   => 'skin-1',
					'choices'   => array(
						'skin-1' => esc_html__( 'Skin 1', 'molla' ),
						'skin-2' => esc_html__( 'Skin 2', 'molla' ),
						'skin-3' => esc_html__( 'Skin 3', 'molla' ),
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin1_title_menu_general',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'General', 'molla' ) . '</div>',
					'active_callback' => $skin1,
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'background',
					'settings'        => 'skin1_menu_bg',
					'label'           => esc_html__( 'Menu Background', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_bg' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'background',
					'settings'        => 'skin1_menu_dropdown_bg',
					'label'           => esc_html__( 'Dropdown Background', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_dropdown_bg' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'toggle',
					'settings'        => 'skin1_menu_arrow',
					'label'           => esc_html__( 'Show arrow', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_arrow' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'toggle',
					'settings'        => 'skin1_menu_divider',
					'label'           => esc_html__( 'Show Dividers', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_divider' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin1_menu_divider_color',
					'label'           => esc_html__( 'Divider Color', 'molla' ),
					'choices'         => array(
						'alpha' => true,
					),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_divider_color' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'select',
					'settings'        => 'skin1_menu_effect',
					'label'           => esc_html__( 'Effects', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_effect' ),
					'choices'         => array(
						''                           => esc_html__( 'No Effect', 'molla' ),
						'scale-eff'                  => esc_html__( 'Top Scale', 'molla' ),
						'scale-eff bottom-scale-eff' => esc_html__( 'Bottom Scale', 'molla' ),
					),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin1_title_menu_ancestor',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'Top Level menu item', 'molla' ) . '</div>',
					'active_callback' => $skin1,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'skin1_menu_ancestor_font',
					'label'           => esc_html__( 'Typography', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_ancestor_font' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin1_menu_ancestor_color',
					'label'           => esc_html__( 'Hover & Active Color', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_ancestor_color' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin1_menu_ancestor_margin',
					'label'           => esc_html__( 'Margin', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_ancestor_margin' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin1_menu_ancestor_padding',
					'label'           => esc_html__( 'Padding', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_ancestor_padding' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin1_title_menu_subtitle',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'Megamenu Heading', 'molla' ) . '</div>',
					'active_callback' => $skin1,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'skin1_menu_subtitle_font',
					'label'           => esc_html__( 'Typography', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_subtitle_font' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);
			
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin1_menu_subtitle_color',
					'label'           => esc_html__( 'Hover Color', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_subtitle_color' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin1_menu_subtitle_margin',
					'label'           => esc_html__( 'Margin', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_subtitle_margin' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin1_menu_subtitle_padding',
					'label'           => esc_html__( 'Padding', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_subtitle_padding' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin1_title_menu_item',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'Sub Menu Item', 'molla' ) . '</div>',
					'active_callback' => $skin1,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'skin1_menu_item_font',
					'label'           => esc_html__( 'Typography', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_item_font' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin1_menu_item_color',
					'label'           => esc_html__( 'Hover Color', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_item_color' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin1_menu_item_margin',
					'label'           => esc_html__( 'Margin', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_item_margin' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin1_menu_item_padding',
					'label'           => esc_html__( 'Padding', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin1_menu_item_padding' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin1,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin2_title_menu_general',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'General', 'molla' ) . '</div>',
					'active_callback' => $skin2,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'background',
					'settings'        => 'skin2_menu_bg',
					'label'           => esc_html__( 'Menu Background', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_bg' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'background',
					'settings'        => 'skin2_menu_dropdown_bg',
					'label'           => esc_html__( 'Dropdown Background', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_dropdown_bg' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'toggle',
					'settings'        => 'skin2_menu_arrow',
					'label'           => esc_html__( 'Show arrow', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_arrow' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'toggle',
					'settings'        => 'skin2_menu_divider',
					'label'           => esc_html__( 'Show Dividers', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_divider' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin2_menu_divider_color',
					'label'           => esc_html__( 'Divider Color', 'molla' ),
					'choices'         => array(
						'alpha' => true,
					),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_divider_color' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'select',
					'settings'        => 'skin2_menu_effect',
					'label'           => esc_html__( 'Effects', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_effect' ),
					'choices'         => array(
						''                           => esc_html__( 'No Effect', 'molla' ),
						'scale-eff'                  => esc_html__( 'Top Scale', 'molla' ),
						'scale-eff bottom-scale-eff' => esc_html__( 'Bottom Scale', 'molla' ),
					),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin2_title_menu_ancestor',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'Top Level menu item', 'molla' ) . '</div>',
					'active_callback' => $skin2,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'skin2_menu_ancestor_font',
					'label'           => esc_html__( 'Typography', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_ancestor_font' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin2_menu_ancestor_color',
					'label'           => esc_html__( 'Hover & Active Color', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_ancestor_color' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin2_menu_ancestor_margin',
					'label'           => esc_html__( 'Margin', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_ancestor_margin' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin2_menu_ancestor_padding',
					'label'           => esc_html__( 'Padding', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_ancestor_padding' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin2_title_menu_subtitle',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'Megamenu Heading', 'molla' ) . '</div>',
					'active_callback' => $skin2,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'skin2_menu_subtitle_font',
					'label'           => esc_html__( 'Typography', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_subtitle_font' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin2_menu_subtitle_color',
					'label'           => esc_html__( 'Hover Color', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_subtitle_color' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin2_menu_subtitle_margin',
					'label'           => esc_html__( 'Margin', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_subtitle_margin' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin2_menu_subtitle_padding',
					'label'           => esc_html__( 'Padding', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_subtitle_padding' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin2_title_menu_item',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'Sub Menu Item', 'molla' ) . '</div>',
					'active_callback' => $skin2,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'skin2_menu_item_font',
					'label'           => esc_html__( 'Typography', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_item_font' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);
			
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin2_menu_item_color',
					'label'           => esc_html__( 'Hover Color', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_item_color' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin2_menu_item_margin',
					'label'           => esc_html__( 'Margin', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_item_margin' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin2_menu_item_padding',
					'label'           => esc_html__( 'Padding', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin2_menu_item_padding' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin2,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin3_title_menu_general',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'General', 'molla' ) . '</div>',
					'active_callback' => $skin3,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'background',
					'settings'        => 'skin3_menu_bg',
					'label'           => esc_html__( 'Menu Background', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_bg' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'background',
					'settings'        => 'skin3_menu_dropdown_bg',
					'label'           => esc_html__( 'Dropdown Background', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_dropdown_bg' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'toggle',
					'settings'        => 'skin3_menu_arrow',
					'label'           => esc_html__( 'Show arrow', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_arrow' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'toggle',
					'settings'        => 'skin3_menu_divider',
					'label'           => esc_html__( 'Show Dividers', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_divider' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin3_menu_divider_color',
					'label'           => esc_html__( 'Divider Color', 'molla' ),
					'choices'         => array(
						'alpha' => true,
					),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_divider_color' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'select',
					'settings'        => 'skin3_menu_effect',
					'label'           => esc_html__( 'Effects', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_effect' ),
					'choices'         => array(
						''                           => esc_html__( 'No Effect', 'molla' ),
						'scale-eff'                  => esc_html__( 'Top Scale', 'molla' ),
						'scale-eff bottom-scale-eff' => esc_html__( 'Bottom Scale', 'molla' ),
					),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin3_title_menu_ancestor',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'Top Level Menu Item', 'molla' ) . '</div>',
					'active_callback' => $skin3,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'skin3_menu_ancestor_font',
					'label'           => esc_html__( 'Typography', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_ancestor_font' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin3_menu_ancestor_color',
					'label'           => esc_html__( 'Hover & Active Color', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_ancestor_color' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin3_menu_ancestor_margin',
					'label'           => esc_html__( 'Margin', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_ancestor_margin' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin3_menu_ancestor_padding',
					'label'           => esc_html__( 'Padding', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_ancestor_padding' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin3_title_menu_subtitle',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'Megamenu Heading', 'molla' ) . '</div>',
					'active_callback' => $skin3,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'skin3_menu_subtitle_font',
					'label'           => esc_html__( 'Typography', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_subtitle_font' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);
			
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin3_menu_subtitle_color',
					'label'           => esc_html__( 'Hover Color', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_subtitle_color' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin3_menu_subtitle_margin',
					'label'           => esc_html__( 'Margin', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_subtitle_margin' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin3_menu_subtitle_padding',
					'label'           => esc_html__( 'Padding', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_subtitle_padding' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'',
				array(
					'type'            => 'custom',
					'settings'        => 'skin3_title_menu_item',
					'label'           => '',
					'section'         => 'style_menu',
					'default'         => '<div class="customize-control-title options-title">' . esc_html__( 'Sub Menu Item', 'molla' ) . '</div>',
					'active_callback' => $skin3,
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'typography',
					'settings'        => 'skin3_menu_item_font',
					'label'           => esc_html__( 'Typography', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_item_font' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);
			
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'color',
					'settings'        => 'skin3_menu_item_color',
					'label'           => esc_html__( 'Hover Color', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_item_color' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin3_menu_item_margin',
					'label'           => esc_html__( 'Margin', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_item_margin' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'dimensions',
					'settings'        => 'skin3_menu_item_padding',
					'label'           => esc_html__( 'Padding', 'molla' ),
					'section'         => 'style_menu',
					'default'         => molla_defaults( 'skin3_menu_item_padding' ),
					'tooltip'         => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'.", 'molla' ),
					'active_callback' => $skin3,
					'transport'       => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Menu_Skin_Options();
