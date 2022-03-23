<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Header_Shop_Icon_Options' ) ) :

	class Molla_Header_Shop_Icon_Options {

		public function __construct() {
			add_action( 'init', array( $this, 'customize_options' ) );
		}

		public function customize_options() {

			Molla_Option::add_section(
				'header_shop_icons',
				array(
					'title' => esc_html__( 'Shop Icons', 'molla' ),
					'panel' => 'header',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'sortable',
					'settings'    => 'shop_icons',
					'label'       => esc_html__( 'Shop Icons', 'molla' ),
					'description' => esc_html__( 'Check items to show.', 'molla' ),
					'section'     => 'header_shop_icons',
					'default'     => molla_defaults( 'shop_icons' ),
					'choices'     => array(
						'account'  => 'Account',
						'wishlist' => 'Wishlist',
						'cart'     => 'Cart',
					),
					'transport'   => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'        => 'dimension',
					'settings'    => 'shop_icons_spacing',
					'label'       => esc_html__( 'Spacing', 'molla' ),
					'section'     => 'header_shop_icons',
					'transport'   => 'postMessage',
					'description' => esc_html__( 'Leave it black to set as default value 3rem.', 'molla' ),
					'tooltip'     => __( "Please remember following units px, rem etc. If you omit unit, it is set as 'px'", 'molla' ),
					'default'     => molla_defaults( 'shop_icons_spacing' ),
					'transport'   => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'checkbox',
					'settings'  => 'shop_icons_divider',
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'shop_icons_divider' ),
					'label'     => esc_html__( 'Show Divider', 'molla' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'cart_canvas_type',
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'cart_canvas_type' ),
					'label'     => esc_html__( 'Enable Off-Canvas Cart Type', 'molla' ),
					'transport' => 'postMessage',
				)
			);
			
			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'select',
					'settings'        => 'cart_canvas_open',
					'label'           => esc_html__( 'Open Mini-cart when', 'molla' ),
					'section'         => 'header_shop_icons',
					'default'         => molla_defaults( 'cart_canvas_open' ),
					'choices'         => array(
						''                    => 'Cart icon is clicked',
						'after-product-added' => 'Product is added to cart',
					),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'cart_canvas_type',
							'operator' => '==',
							'value'    => true,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'radio-image',
					'settings'  => 'shop_icon_type',
					'label'     => esc_html__( 'Shop Icon Type', 'molla' ),
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'shop_icon_type' ),
					'choices'   => array(
						'type-full'    => MOLLA_URI . '/assets/images/customize/shop_count1.png',
						'count-linear' => MOLLA_URI . '/assets/images/customize/shop_count2.png',
					),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'shop_icon_label_hide',
					'label'     => esc_html__( 'Hide Shop Icon Label', 'molla' ),
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'shop_icon_label_hide' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'            => 'radio-image',
					'settings'        => 'shop_icon_label_type',
					'label'           => esc_html__( 'Label Type', 'molla' ),
					'section'         => 'header_shop_icons',
					'default'         => molla_defaults( 'shop_icon_label_type' ),
					'choices'         => array(
						''     => MOLLA_URI . '/assets/images/customize/shop_label1.png',
						'hdir' => MOLLA_URI . '/assets/images/customize/shop_label2.png',
					),
					'transport'       => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'shop_icon_label_hide',
							'operator' => '==',
							'value'    => false,
						),
					),
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'shop_icon_label_account',
					'label'     => esc_html__( 'Account Label', 'molla' ),
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'shop_icon_label_account' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'shop_icon_label_wishlist',
					'label'     => esc_html__( 'Wishlist Label', 'molla' ),
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'shop_icon_label_wishlist' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'shop_icon_label_cart',
					'label'     => esc_html__( 'Cart Label', 'molla' ),
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'shop_icon_label_cart' ),
					'transport' => 'postMessage',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'shop_icon_class_account',
					'label'     => esc_html__( 'Account Icon', 'molla' ),
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'shop_icon_class_account' ),
					'transport' => 'postMessage',
					'tooltip'   => esc_html__( 'Please visit ', 'molla' ) . '<a href="https://d-themes.com/wordpress/molla/elements/elements-list/element-icons/" target="_blank">' . esc_html__( 'Molla Icon Store', 'molla' ) . '</a>.',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'shop_icon_class_wishlist',
					'label'     => esc_html__( 'Wishlist Icon', 'molla' ),
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'shop_icon_class_wishlist' ),
					'transport' => 'postMessage',
					'tooltip'   => esc_html__( 'Please visit ', 'molla' ) . '<a href="https://d-themes.com/wordpress/molla/elements/elements-list/element-icons/" target="_blank">' . esc_html__( 'Molla Icon Store', 'molla' ) . '</a>.',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'text',
					'settings'  => 'shop_icon_class_cart',
					'label'     => esc_html__( 'Cart Icon', 'molla' ),
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'shop_icon_class_cart' ),
					'transport' => 'postMessage',
					'tooltip'   => esc_html__( 'Please visit ', 'molla' ) . '<a href="https://d-themes.com/wordpress/molla/elements/elements-list/element-icons/" target="_blank">' . esc_html__( 'Molla Icon Store', 'molla' ) . '</a>.',
				)
			);

			Molla_Option::add_field(
				'option',
				array(
					'type'      => 'toggle',
					'settings'  => 'shop_icon_cart_price_show',
					'label'     => esc_html__( 'Show Cart Totals', 'molla' ),
					'section'   => 'header_shop_icons',
					'default'   => molla_defaults( 'shop_icon_cart_price_show' ),
					'transport' => 'postMessage',
				)
			);
		}
	}

endif;

return new Molla_Header_Shop_Icon_Options();
