<?php

if ( ! function_exists( 'molla_rtl_params' ) ) :
	function molla_rtl_params( $is_rtl, $param ) {
		if ( $is_rtl ) {
			if ( 'left' == $param ) {
				return 'right';
			}
			if ( 'right' == $param ) {
				return 'left';
			}
		}
		return $param;
	}
endif;

if ( ! function_exists( 'molla_strip_script_tags' ) ) :
	function molla_strip_script_tags( $content ) {
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = preg_replace( '/<script.*?\/script>/s', '', $content ) ? : $content;
		$content = preg_replace( '/<style.*?\/style>/s', '', $content ) ? : $content;
		return $content;
	}
endif;

if ( ! function_exists( 'molla_strip_tags' ) ) :
	function molla_strip_tags( $content ) {
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = preg_replace( '/<script.*?\/script>/s', '', $content ) ? : $content;
		$content = preg_replace( '/<style.*?\/style>/s', '', $content ) ? : $content;
		$content = strip_tags( $content );
		return $content;
	}
endif;

if ( ! function_exists( 'molla_pagination' ) ) :
	function molla_pagination( $query = '', $pos = '' ) {
		global $wp_query, $wp_rewrite;

		$prev_icon = 'icon-long-arrow-left';
		$next_icon = 'icon-long-arrow-right';

		if ( ! $query ) {
			$query = $wp_query;
		}

		if ( $query->max_num_pages < 2 ) {
			return;
		}

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );

		$args = apply_filters(
			'molla_pagination_args',
			array(
				'current'   => $paged,
				'total'     => $query->max_num_pages,
				'mid_size'  => 1,
				'type'      => 'list',
				'prev_text' => '<i class="' . $prev_icon . '"></i>' . esc_html__( 'Prev', 'molla' ),
				'next_text' => esc_html__( 'Next', 'molla' ) . '<i class="' . $next_icon . '"></i>',
			)
		);

		$pagination_html = paginate_links( $args );
		if ( 1 === (int) $paged ) {
			$prev = "<ul class='page-numbers'>";
			if ( molla_option( 'show_disabled_paginate' ) ) {
				$prev .= '<li class="disabled">' . sprintf(
					'<a class="prev page-numbers" href="%s">%s</a>',
					'#',
					$args['prev_text']
				) . '</li>';
			}
			$pagination_html = str_replace( "<ul class='page-numbers'>", $prev, $pagination_html );
		} elseif ( (int) $paged === (int) $query->max_num_pages ) {
			$next = '</ul>';
			if ( molla_option( 'show_disabled_paginate' ) ) {
				$next = '<li class="disabled">' . sprintf(
					'<a class="next page-numbers" href="%s">%s</a>',
					'#',
					$args['next_text']
				) . '</li>' . $next;
			}
			$pagination_html = str_replace( '</ul>', $next, $pagination_html );
		}

		echo '<div class="pagination' . ( $pos ? esc_attr( ' d-flex justify-content-' . $pos ) : '' ) . '">' . $pagination_html . '</div>';
	}
endif;

if ( ! function_exists( 'molla_header_presets' ) ) :
	function molla_header_presets() {
		return array(
			'header-1' => array(
				'title'      => esc_html__( 'Header 1', 'molla' ),
				'img'        => 'header-1.jpg',
				'elements'   => array(
					'main_left'     => '[{"menu-icon":""},{"logo":""}]',
					'main_center'   => '[{"search-form":""}]',
					'main_right'    => '[{"shop":""}]',
					'top_left'      => '[{"html":{"html":"<p>Special collection already available.</p><a href=\"#\">&nbsp;Read more ...</a>","el_class":"welcome-msg"}}]',
					'top_right'     => '[{"nav-top":""}]',
					'bottom_left'   => '[{"custom_menu":{"html":"categories","menu_type":true,"menu_title":"Browse Categories","menu_link":"' . ( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : '#' ) . '","menu_active_event":"hover","menu_show_icon":true,"menu_icon":"icon-bars","menu_active_icon":"icon-close","menu_icon_pos":"left","menu_skin":null}},{"divider":""}]',
					'bottom_center' => '[{"main_menu":""}]',
					'bottom_right'  => '[{"divider":""},{"html":{"html":"<i class=\"icon-medapps\"></i><p>Clearance<span class=\"highlight\">&nbsp;Up to 30% Off</span></p>","el_class":"clearance-info"}}]',
				),
				'custom_css' => '.header-top .header-left { overflow: hidden; margin-right: 2rem } .welcome-msg { font-weight: 300; line-height: 1; white-space: nowrap; } .clearance-info i { margin-right: 1.5rem; } .clearance-info p { padding-right: .5rem; font-weight: 500; letter-spacing: -.01em; } p .highlight { color: #333; } .header-center .header-search { width: 100%; } @media screen and (min-width: 768px) { .header-main .header-left { flex-basis: calc((100% + 2rem) / 4); max-width: calc((100% + 2rem) / 4); } .header-bottom .header-left, .header-bottom .header-right { flex-basis: calc((100% + 2rem) / 4 - 2.2rem); max-width: calc((100% + 2rem) / 4 - 2.2rem); } .header-main .header-center { flex-basis: calc((100% + 20px) / 2 - 20px); max-width: calc((100% + 20px) / 2 - 20px); } } .header-bottom .dropdown-menu-wrapper { margin: 0; } .header-bottom .header-left, .header-bottom .header-right { position: relative; } .header-bottom .divider { position: absolute; right: -1px; } .header-bottom .header-right .divider { position: absolute; left: 4px; } .header-bottom .header-center { margin-left: 2.2rem; } .header-bottom .header-right { position: relative; } .menu>li+li { margin-left: 7px; } .header-center .header-search { width: 100%; } @media screen and (max-width: 1039px) { .menu>li+li { margin-left: 1px; } } @media screen and (max-width: 991px) { .header-main.content-divider .inner-wrap { border: none; } .header-center, .header-bottom { display: none; } } .top-menu a { font-weight: 300; letter-spacing: -.01em; }@keyframes show_msg { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } } @media (max-width: 479px) { .welcome-msg { transform: translateX(0); animation: 12s linear 4s infinite show_msg; } }',
			),
			'header-2' => array(
				'title'      => esc_html__( 'Header 2', 'molla' ),
				'img'        => 'header-2.jpg',
				'elements'   => array(
					'main_left'  => '[{"menu-icon":""},{"logo":""},{"main_menu":""}]',
					'main_right' => '[{"search-form":""},{"shop":""}]',
					'top_left'   => '[{"currency_switcher":""},{"lang_switcher":""}]',
					'top_right'  => '[{"nav-top":""}]',
				),
				'custom_css' => '.header-top { font-size: 1.3rem; font-weight: 300; } .header #menu-main-menu { margin-left: 3.5rem; } #menu-main-menu .sf-with-ul>a:after { font-size: 1rem; } #menu-main-menu.sf-arrows >li>a:after { right: 1.5rem; } .menu > li > a, .nav-dropdown li > a { text-transform: uppercase; } .header-dropdown.sf-arrows li>a { padding-right: 2.2rem; } .sf-arrows .sf-with-ul>a:after { font-size: 1.2rem; } .menu>li>a { padding: 3.95rem 1.5rem; } .menu li + li { margin: 0; } .header-main .header-right > * { margin-right: 2.5rem; } .header-main .header-right > *:last-child { margin: 0; } .header-col>* { margin-right: 2.9rem; }',
			),
			'header-3' => array(
				'title'      => esc_html__( 'Header 3', 'molla' ),
				'img'        => 'header-3.jpg',
				'elements'   => array(
					'main_left'  => '[{"menu-icon":""},{"logo":""},{"main_menu":""}]',
					'main_right' => '[{"search-form":""},{"shop":""}]',
				),
				'custom_css' => '.header-search .search-wrapper { background-color: transparent; } .menu > li.current-menu-item > a, .menu > li.current-menu-ancestor > a, .header .form-control, .header-search .btn { color: #fff; } .header .form-control::placeholder { color: #fff; } .header .form-control::-ms-input-placeholder { color: #fff; } .sf-arrows .sf-with-ul>a::after, .sf-arrows .megamenu.menu-item-has-children>a::after { right: 1.5rem; } .menu>li+li { margin-left: .5rem; } .header .menu > li > a:hover, .header .menu > li:hover > a { color: #fff; } .header .menu > li > a:before { background-color: #fff; } .header .menu > li > a:before { bottom: 1.5rem; } .fixed .menu > li > a:before { bottom: .5rem; } .sticky-header:not(.fixed) .dropdown-menu, .sticky-header:not(.fixed) .menu >li:hover>ul, .sticky-header:not(.fixed) .menu >li:hover.megamenu>ul { top: calc(100% - 1rem); } #menu-main-menu { margin-left: 4rem; } @media (min-width: 1600px) { .container-fluid { padding-left: 6rem; padding-right: 6rem; } } .header-search .search-wrapper button.btn { padding: 1.2rem 1rem; } .header-search .form-control { padding-left: 1rem; } .header-search .search-wrapper { min-width: 0; width: 260px; } .header .header-right > *:not(:first-child) { margin-left: 3rem; } .shop-icon.wishlist>a { position: relative; top: -1px; font-size: 2.6rem; } .shop-icon + .shop-icon { margin-left: 2.3rem; }',
			),
			'header-4' => array(
				'title'      => esc_html__( 'Header 4', 'molla' ),
				'img'        => 'header-4.jpg',
				'elements'   => array(
					'main_left'    => '[{"search-form":""}]',
					'main_center'  => '[{"logo":""}]',
					'main_right'   => '[{"shop":""}]',
					'top_left'     => '[{"html":{"html":"<p class=\"welcome-msg\"><a href=\"tel:#\"><i class=\"icon-phone\" style=\"font-size:16px;margin-right:8px\"></i>Call: +0123 456 789</a></p>","el_class":""}}]',
					'bottom_left'  => '[{"menu-icon":""},{"main_menu":""}]',
					'bottom_right' => '[{"html":{"html":"<i class=\"icon-medapps text-primary\" style=\"margin-right:20px;\"></i><p>Clearance Up to 30% Off</p>","el_class":"clearance-info"}}]',
					'top_right'    => '[{"social":""},{"nav-top":""}]',
				),
				'custom_css' => '.top-menu a, .header-top .welcome-msg, .header .social-icons { font-size: 13px; letter-spacing: -0.01em; color: #999; } .header-top .header-dropdown { padding-top: 5px; padding-bottom: 5px; } .header-col .nav-dropdown>* { margin-left: 20px; } .header-top .header-dropdown > li > a { padding-right: 28px; } .header-top .header-dropdown > li > a::after { font-size: 12px; } .header-top .account-links { margin-right: 10px; font-weight: 300; } .header-search .search-wrapper button.btn { flex-basis: auto; padding: 0; font-size: 26px; } .header-search .form-control { padding-left: 5px; font-size: 13px; color: #222; } .header-search .form-control::placeholder { color: #222; font-weight: 400; } .shop-icons .shop-icon .cart-price, .shop-icons .wishlist .custom-label { font-size: 13px; margin-left: 15px; color: #222; font-weight: 400; } .clearance-info p, .header-bottom .menu > li > a { font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; color: #fff; } .header-bottom .menu > li > a { padding-top: 16px; padding-bottom: 16px; } .header-bottom .menu > li > a::before { bottom: 11px; } .header-bottom .menu > li.menu-item > a::after { right: 10px; top: 49%; } .header-bottom .menu > li + li { margin-left: 6px; } .mobile-menu-toggler { color: #fff; padding-left: 0; } @media (max-width: 991px) { .header .social-icons { margin-right: 2rem; } .header-main .header-left { flex: none; } .header .shop-icon + .shop-icon { margin-left: 2rem; } .shop-icons .shop-icon .cart-price, .shop-icons .wishlist .custom-label { margin-left: 5px; } }.header-col > * { margin-right: 2.5rem; }',
			),
			'header-5' => array(
				'title'      => esc_html__( 'Header 5', 'molla' ),
				'img'        => 'header-5.jpg',
				'elements'   => array(
					'main_left'   => '[{"menu-icon":""},{"logo":""}]',
					'main_center' => '[{"main_menu":""}]',
					'main_right'  => '[{"search-form":""},{"shop":""}]',
				),
				'custom_css' => '.menu-skin1>li+li { margin-left: 4px; } .menu-skin1>li:hover>a, .menu-skin1>li.current-menu-item>a, .menu-skin1>li.current-menu-ancestor>a { color: #222; } .menu-skin1.bottom-scale-eff>li>a:before { background: #222; bottom: 1.5rem; } .menu-skin1.sf-arrows>.menu-item>a:after { right: 1rem; } .header-search .search-wrapper { border-right-width: 1px; } .shop-icons { margin-left: 2.4rem; } .shop-icons .icon-heart-o { transform: translateY(-1px); } .shop-icons .icon-shopping-cart { transform: translateX(-2px); } .shop-icon { margin-right: 1px; } .shop-icon i { color: #666; } .shop-icon .cart-price { margin-left: 1rem; font-weight: 600; } .shop-icon [class*="count"] { top: -1px; width: 1.7rem; height: 1.7rem; line-height: 1.7; background-color: #222; }',
			),
			'header-6' => array(
				'title'      => esc_html__( 'Header 6', 'molla' ),
				'img'        => 'header-6.jpg',
				'elements'   => array(
					'main_left'   => '[{"menu-icon":""},{"logo":""}]',
					'main_center' => '[{"main_menu":""}]',
					'main_right'  => '[{"search-form":""},{"shop":""}]',
					'top_left'    => '[{"currency_switcher":""},{"lang_switcher":""}]',
					'top_right'   => '[{"nav-top":""}]',
				),
				'custom_css' => '.header-top .sub-menu { color: #777; } .container-fluid .megamenu-container { position: static; } .megamenu-container .sub-menu { margin: 0 auto !important; left: 0; right: 0; } @media (max-width: 991px) { .header-top .nav-dropdown { color: #777; } } .header-top { font-weight: 300; font-size: 1.3rem; line-height: 1.5; letter-spacing: 0; } .header-col>* { margin-right: 2.9rem; } .header [class$="-count"] { color: #222; } .menu>li+li { margin-left: .6rem; }',
			),
			'header-7' => array(
				'title'      => esc_html__( 'Header 7', 'molla' ),
				'img'        => 'header-7.jpg',
				'elements'   => array(
					'main_left'          => '[{"search-form":""}]',
					'main_center'        => '[{"html":{"html":"Free Delivery For Members","el_class":"notice"}}]',
					'main_right'         => '[{"shop":""}]',
					'bottom_left'        => '[{"menu-icon":""},{"logo":""},{"main_menu":""}]',
					'bottom_center'      => '[]',
					'bottom_right'       => '[{"social":""},{"html":{"html":"Copyright © 2020 Molla Store<br>All Rights Reserved.","el_class":"copyright"}}]',
					'mobile_main_left'   => '[{"menu-icon":""},{"logo":""}]',
					'mobile_main_center' => '[]',
					'mobile_main_right'  => '[{"shop":""}]',
				),
				'custom_css' => '@media (max-width: 991px) { .header-bottom { display: none; } .header .header-col .logo { margin: 0; } } header .notice { color: #222; font-size: 1.3rem; font-weight: 400; letter-spacing: .01em; } header .copyright { color: #777; font-size: 1.3rem; font-weight: 300; } .sticky-header.fixed { box-shadow: 0 3px 6px rgba(51,51,51,0.05); } .header-search .search-wrapper { min-width: 212px; margin-bottom: 1px; } .header-search button.btn.btn-icon { flex-basis: 38px; padding: 0 0 1px 4px; color: #333; font-size: 2.2rem; } .header-search .form-control { padding: 9px 0 7px; color: #777; font-size: 1.3rem; font-weight: 300; letter-spacing: .01em; } .shop-icon.account .icon { margin-top: 1px; } .shop-icon.wishlist { margin: -1px 0 0 2rem; } .shop-icon .wishlist-count, .shop-icon .cart-count { top: -1px; right: -.8rem; width: 1.8rem; height: 1.8rem; line-height: 1.8; } .shop-icon .cart-price { margin-left: 1.8rem; letter-spacing: .01em; } .header .social-icons a.social-icon { display: inline-block; width: 25px; line-height: 4rem; font-size: 1.3rem; }',
			),
		);
	}
endif;

function molla_init_header_options() {
	set_theme_mod( 'header_initial', true );
	set_theme_mod(
		'hb_options',
		array(
			'main_left'   => '[{"menu-icon":""},{"logo":""}]',
			'main_center' => '[{"main_menu":""}]',
			'main_right'  => '[{"shop":""}]',
		)
	);
	set_theme_mod(
		'molla_header_builder',
		array(
			'selected_layout' => 'default',
		)
	);
	set_theme_mod(
		'molla_header_builder_layouts',
		array(
			'default' => array(
				'name'       => esc_html__( 'Default', 'molla' ),
				'elements'   => array(
					'main_left'   => '[{"logo":""}]',
					'main_center' => '[{"search-form":""}]',
					'main_right'  => '[{"shop":""}]',
				),
				'custom_css' => '',
			),
		)
	);
}

function molla_get_responsive_cols( $cols ) {
	$result = array();
	if ( $cols > 5 ) {
		$result = array(
			'xxl' => $cols,
			'xl'  => 4,
			'md'  => 3,
			'xs'  => 2,
		);
	} elseif ( $cols > 4 ) {
		$result = array(
			'xl' => $cols,
			'lg' => 4,
			'md' => 3,
			'xs' => 2,
		);
	} elseif ( 4 == $cols ) {
		$result = array(
			'lg' => 4,
			'md' => 3,
			'xs' => 2,
		);
	} elseif ( 3 == $cols ) {
		$result = array(
			'lg' => 3,
			'sm' => 2,
			'xs' => 1,
		);
	} elseif ( 2 == $cols ) {
		$result = array(
			'lg' => 2,
			'xs' => 1,
		);
	} elseif ( 1 == $cols ) {
		$result = array(
			'lg' => 1,
			'xs' => 1,
		);
	}

	return apply_filters( 'molla_filter_reponsive_cols', $result, $cols );
}

if ( ! function_exists( 'molla_get_column_class' ) ) :
	function molla_get_column_class( $columns, $array = false ) {

		$result  = array();
		$col_dsk = isset( $columns['xxl'] ) ? (int) $columns['xxl'] : '';
		$cols    = molla_get_responsive_cols( $col_dsk );

		if ( count( $cols ) > 0 && $columns['xs'] && $columns['xs'] != $cols['xs'] ) {
			$cols['sm'] = $cols['xs'];
		}

		$breakpoints = array(
			0    => 'xs',
			576  => 'sm',
			768  => 'md',
			992  => 'lg',
			1200 => 'xl',
			1600 => 'xxl',
		);

		if ( isset( $columns['xl'] ) && $columns['xl'] ) {
			$breakpoints = array_slice( $breakpoints, 0, 5, true );
		}

		$prev = '';

		foreach ( $breakpoints as $size => $breakpoint ) {
			$col = '';
			if ( isset( $columns[ $breakpoint ] ) && '' !== $columns[ $breakpoint ] ) {
				if ( $columns[ $breakpoint ] != $prev ) {
					$col = $columns[ $breakpoint ];
				}
			} elseif ( isset( $cols[ $breakpoint ] ) && $cols[ $breakpoint ] != $prev ) {
				$col = $cols[ $breakpoint ];
			}
			if ( isset( $columns['xl'] ) && $columns['xl'] && 992 == $size ) {
				$col = isset( $columns['xxl'] ) ? $columns['xxl'] : $columns['lg'];
			}
			if ( '' !== $col ) {
				$result[] = 'c-' . $breakpoint . '-' . $col;
				$prev     = $col;
			}
		}
		$result = array_reverse( $result );
		if ( $array ) {
			return $result;
		}
		$result = ' ' . implode( ' ', $result );
		return $result;
	}
endif;

function molla_carousel_options( $args ) {
	$result      = array();
	$breakpoints = array(
		0    => 'xs',
		576  => 'sm',
		768  => 'md',
		992  => 'lg',
		1200 => 'xl',
		1600 => 'xxl',
	);
	if ( isset( $args[1200]['items'] ) && $args[1200]['items'] ) {
		$breakpoints = array_slice( $breakpoints, 0, 5, true );
	}

	$cols = molla_get_responsive_cols( $args[992]['items'] );

	$prev_items = $cols['xs'];
	foreach ( $breakpoints as $size => $breakpoint ) {
		$result[ $size ]['items'] = $prev_items;
		if ( isset( $cols[ $breakpoint ] ) ) {
			$result[ $size ]['items'] = (int) $cols[ $breakpoint ];
		}
		if ( 'lg' != $breakpoint && isset( $args[ $size ]['items'] ) && '' !== $args[ $size ]['items'] ) {
			$result[ $size ]['items'] = (int) $args[ $size ]['items'];
		}

		if ( isset( $args[1200]['items'] ) && $args[1200]['items'] && 'lg' == $breakpoint ) {
			$result[ $size ]['items'] = (int) $args[ $size ]['items'];
		}

		$prev_items = $result[ $size ]['items'];

		if ( 992 < $size ) {
			$result[ $size ]['nav']  = ( isset( $args[992]['nav'] ) && ( 'yes' == $args[992]['nav'] || true === $args[992]['nav'] ) ) ? true : false;
			$result[ $size ]['dots'] = ( isset( $args[992]['dots'] ) && ( 'yes' == $args[992]['dots'] || true === $args[992]['dots'] ) ) ? true : false;
		} else {
			$result[ $size ]['nav']  = ( isset( $args[ $size ]['nav'] ) && ( 'yes' == $args[ $size ]['nav'] || true === $args[ $size ]['nav'] ) ) ? true : false;
			$result[ $size ]['dots'] = ( isset( $args[ $size ]['dots'] ) && ( 'yes' == $args[ $size ]['dots'] || true === $args[ $size ]['dots'] ) ) ? true : false;
		}
	}

	return apply_filters( 'molla_filter_carousel_options', $result );
}

function molla_carousel_responsive_classes( $args ) {
	$class = ' c-xs-' . $args[0]['items'];

	$breakpoints = array(
		0    => 'xs',
		576  => 'sm',
		768  => 'md',
		992  => 'lg',
		1200 => 'xl',
		1600 => 'xxl',
	);

	$prev = $args[0]['items'];

	foreach ( $args as $w => $setting ) {
		if ( isset( $setting['items'] ) && $setting['items'] != $prev ) {
			$class .= ' c-' . $breakpoints[ $w ] . '-' . $setting['items'];
			$prev   = $setting['items'];
		}
	}

	return apply_filters( 'molla_filter_carousel_responsive_classes', $class );
}

if ( ! function_exists( 'molla_get_product_class' ) ) :
	function molla_get_product_class( $style, $align ) {
		$add = '';
		if ( 'no-overlay' == $style || 'slide' == $style || 'popup' == $style ) {
			$add = 'product-popup ';
		}
		$add .= 'product-' . $style;
		if ( 'list' != $style && 'card' != $style ) {
			$add .= ' ' . $align . '-mode';
		}
		return $add;
	}
endif;

if ( ! function_exists( 'molla_get_category_class' ) ) :
	function molla_get_category_class( $style, $align ) {

		if ( $align ) {
			$align = ' text-' . $align . ' ';
		}

		$cat_classes = ' cat-' . $style . $align;

		if ( 'yes' == wc_get_loop_prop( 'overlay_type' ) ) {
			$cat_classes .= ' overlay-hover';
		}

		if ( 'action-slide' == $style ) {
			$cat_classes .= ' cat-link-anim';
		}

		if ( 'yes' == wc_get_loop_prop( 'with_subcat' ) ) {
			$cat_classes .= ' with-subcats';
		}

		return $cat_classes;
	}
endif;


if ( ! function_exists( 'molla_get_cat_content_class' ) ) :
	/**
	 * Get category content classes
	 */
	function molla_get_cat_content_class( $style ) {
		$content_class = '';
		$t_x_pos       = strlen( wc_get_loop_prop( 't_x_pos' ) ) ? wc_get_loop_prop( 't_x_pos' ) : -1;
		$t_y_pos       = strlen( wc_get_loop_prop( 't_y_pos' ) ) ? wc_get_loop_prop( 't_y_pos' ) : -1;

		if ( 'expand' == $style ) {
			$content_class = 'cat-content-overlay';
		} elseif ( 'action-popup' == $style ) {
			$content_class = 'cat-content-static';
		}

		if ( 'default' == $style ||
			'action-slide' == $style ||
			'back-clip' == $style ||
			'inner-link' == $style ||
			'fade-up' == $style ||
			'fade-down' == $style ) {

			if ( -1 != $t_x_pos ) {
				$content_class .= ' t-x-' . $t_x_pos;
			}
			if ( -1 != $t_x_pos ) {
				$content_class .= ' t-y-' . $t_y_pos;
			}
		}

		if ( wc_get_loop_prop( 'hide_count' ) ) {
			$content_class .= ' hidden-count';
		}
		return $content_class;
	}
endif;

if ( ! function_exists( 'molla_get_loop_columns' ) ) :
	/**
	 * Get loop columns
	 */
	function molla_get_loop_columns() {
		$cols          = '';
		$product_style = molla_option( 'post_product_type' );
		if ( class_exists( 'WooCommerce' ) && is_product_category() ) {
			$term = get_queried_object();
			$cols = get_term_meta( $term->term_id, 'product_columns', true );
		}
		if ( ! empty( $_COOKIE['layout_type'] ) ) {
			$param = $_COOKIE['layout_type'];
		}
		if ( ! empty( $_GET['layout_type'] ) ) {
			$param = sanitize_title( $_GET['layout_type'] );
		}
		if ( isset( $param ) ) {
			if ( 1 == strlen( $param ) ) {
				$cols = $param;
				if ( 'list' == molla_option( 'post_product_type' ) ) {
					$product_style = 'default';
				}
			} else {
				$product_style = 'list';
			}
		}
		if ( ! $cols ) {
			$cols = molla_option( 'catalog_columns' );
		}
		return apply_filters(
			'molla_get_loop_columns',
			array(
				'columns' => $cols,
				'style'   => $product_style,
			)
		);
	}
endif;

if ( ! function_exists( 'molla_get_human_time' ) ) :
	function molla_get_human_time( $time ) {

		$time = time() - $time;
		$time = ( $time < 1 ) ? 1 : $time;

		$tokens = array(
			31536000 => 'year',
			2592000  => 'month',
			604800   => 'week',
			86400    => 'day',
			3600     => 'hour',
			60       => 'minute',
			1        => 'second',
		);

		foreach ( $tokens as $unit => $text ) {
			if ( $time < $unit ) {
				continue;
			}
			$units = floor( $time / $unit );
			return $units . ' ' . $text . ( ( $units > 1 ) ? 's' : '' );
		}
	}
endif;

if ( ! function_exists( 'molla_sort_by_priority' ) ) :
	function molla_sort_by_priority( $arg1, $arg2 ) {
		if ( $arg1['priority'] == $arg2['priority'] ) {
			return 0;
		}
		return $arg1['priority'] < $arg2['priority'] ? -1 : 1;
	}
endif;

if ( ! function_exists( 'molla_ajax' ) ) :
	function molla_ajax() {

		if ( wp_doing_ajax() || isset( $_REQUEST['vcv-ajax'] ) ) {
			return true;
		}

		return apply_filters( 'molla_filter_doing_ajax', false );
	}
endif;

if ( ! function_exists( 'molla_template_out_of_stock' ) ) :
	function molla_template_out_of_stock() {
		echo '<button class="btn btn-block btn-outline-primary-2 disabled" disabled>Out of Stock</button>';
	}
endif;

if ( ! function_exists( 'molla_get_dynamic_css' ) ) :
	function molla_get_dynamic_css( $is_rtl = false, $dynamic_part = false ) {
		ob_start();
		$elems = array();
		if ( $dynamic_part ) {
			$elems[] = $dynamic_part;
		} else {
			$elems = apply_filters(
				'molla_dynamic_elements',
				array(
					'header',
					'footer',
					'color',
					'typography',
					'blog',
					'menu_skin',
					'elements',
					'layout',
					'woocommerce',
				)
			);
		}
		foreach ( $elems as $e ) {
			include MOLLA_FUNCTIONS . '/dynamic_css/parts/' . $e . '.php';
		}

		$css = ob_get_clean();
		if ( $dynamic_part ) {
			return molla_minify_css( wp_strip_all_tags( wp_specialchars_decode( $css ) ), molla_option( 'minify_css_js' ) );
		}
		$css .= molla_option( 'custom_css' );
		$css  = wp_strip_all_tags( wp_specialchars_decode( $css ) );
		return molla_minify_css( $css, molla_option( 'minify_css_js' ) );
	}
endif;

if ( ! function_exists( 'molla_get_internal_css' ) ) :
	function molla_get_internal_css() {

		if ( molla_is_elementor_preview() ) {
			$page_css = '';
		} else {
			$page_css = get_post_meta( intval( get_the_ID() ), 'page_css', true );
		}

		if ( ! molla_is_elementor_preview() || 'header' != get_post_type() ) {
			$header_css = isset( molla_option( 'molla_header_builder' )['custom_css'] ) ? molla_option( 'molla_header_builder' )['custom_css'] : '';
		} else {
			$header_css = '';
		}

		global $molla_settings;
		if ( isset( $molla_settings['header']['builder'] ) ) {
			$header_css = '';
		} else {
			$header_op = $molla_settings['header'];

			// Header css using page layout
			if ( isset( $header_op['id'] ) ) {
				$header_css = $header_op['custom_css'];
			}

			// Header css per page
			if ( isset( $header_op['bg'] ) && count( $header_op['bg'] ) ) {
				$style = '';
				ob_start();
				foreach ( $header_op['bg'] as $selector => $bg ) {
					echo '.' . $selector . '{';
					foreach ( $bg as $key => $val ) {
						echo esc_attr( $key ) . ':' . $val . ';';
					}
					echo '}';
				}
				$style       = ob_get_clean();
				$header_css .= $style;
			}
		}

		if ( ! molla_is_elementor_preview() || 'sidebar' != get_post_type() ) {
			if ( ! empty( $molla_settings['sidebar']['width'] ) ) {
				$sidebar_css = '@media (min-width: 992px) {
									.page-content .sidebar-wrapper >.col-lg-3 {
										flex: 0 0 ' . $molla_settings['sidebar']['width'] . '%;
										max-width: ' . $molla_settings['sidebar']['width'] . '%;
									}
									.page-content .sidebar-wrapper >.col-lg-9 {
										flex: 0 0 calc(100% - ' . $molla_settings['sidebar']['width'] . '% );
										max-width: calc(100% - ' . $molla_settings['sidebar']['width'] . '% );
									}
								}';
			} else {
				$sidebar_css = '';
			}
		} else {
			$sidebar_css = '';
		}

		$page_css = $header_css . $sidebar_css . $page_css;
		return molla_minify_css( $page_css, molla_option( 'minify_css_js' ) );
	}
endif;

if ( ! function_exists( 'molla_import_options' ) ) :
	function molla_import_theme_options( $plugin_options, $imported_options ) {
		update_option( 'theme_mods_' . get_option( 'stylesheet' ), $imported_options );
	}
endif;

if ( ! function_exists( 'molla_load_google_font' ) ) :
	function molla_load_google_font() {
		$gfont        = array();
		$gfont_weight = array( 300, 400, 500, 600, 700 );
		$fonts        = molla_google_fonts();

		foreach ( $fonts as $op ) {
			$font        = ( isset( molla_option( $op )['font-family'] ) && 'inherit' != molla_option( $op )['font-family'] ) ? urlencode( molla_option( $op )['font-family'] ) : '';
			$font_weight = isset( molla_option( $op )['font-weight'] ) ? molla_option( $op )['font-weight'] : '';

			if ( $font && ! in_array( $font, $gfont ) ) {
				$gfont[] = $font;
			}
			if ( $font_weight && ! in_array( $font_weight, $gfont_weight ) ) {
				$gfont_weight[] = $font_weight;
			}
		}

		$gfont_weight    = implode( ',', $gfont_weight );
		$font_family     = '';
		$font_family_arr = array();
		foreach ( $gfont as $font ) {
			$font_family_arr[] = str_replace( ' ', '+', $font ) . ':' . $gfont_weight;
		}
		if ( ! empty( $font_family_arr ) ) {
			$font_family = implode( '%7C', $font_family_arr );
		}
		if ( $font_family ) {

			$custom_font_args = array(
				'family' => $font_family,
			);

			$subsets = [
				'ru_RU' => 'cyrillic',
				'bg_BG' => 'cyrillic',
				'he_IL' => 'hebrew',
				'el'    => 'greek',
				'vi'    => 'vietnamese',
				'uk'    => 'cyrillic',
				'cs_CZ' => 'latin-ext',
				'ro_RO' => 'latin-ext',
				'pl_PL' => 'latin-ext',
			];
			$locale  = get_locale();

			if ( isset( $subsets[ $locale ] ) ) {
				$custom_font_args['subset'] = $subsets[ $locale ];
			}

			if ( ! molla_option( 'google_webfont' ) ) {
				$google_font_url = add_query_arg( $custom_font_args, '//fonts.googleapis.com/css' );
				wp_register_style( 'molla-google-fonts', esc_url( $google_font_url ) );
				wp_enqueue_style( 'molla-google-fonts' );
			} else {
				?>
				<script type="text/javascript">
					WebFontConfig = {
						google: { families: [ '<?php echo esc_js( $custom_font_args['family'] ); ?>' ] }
					};
					(function(d) {
						var wf = d.createElement('script'), s = d.scripts[0];
						wf.src = '<?php echo MOLLA_JS; ?>/webfont.js';
						wf.async = true;
						s.parentNode.insertBefore(wf, s);
					})(document);
				</script>
				<?php
			}
		}
	}
endif;

if ( ! function_exists( 'molla_file_write_enable' ) ) :
	function molla_file_write_enable( $filename ) {
		if ( is_writable( dirname( $filename ) ) == false ) {
			@chmod( dirname( $filename ), 0755 );
		}
		if ( file_exists( $filename ) ) {
			if ( is_writable( $filename ) == false ) {
				@chmod( $filename, 0755 );
			}
			@unlink( $filename );
		}
	}
endif;

if ( ! function_exists( 'molla_minify_css' ) ) :
	function molla_minify_css( $style, $method = false ) {
		if ( ! $style ) {
			return;
		}

		if ( ! $method ) {
			return $style;
		}

		// Change ::before, ::after to :before, :after
		$style = str_replace( array( '::before', '::after' ), array( ':before', ':after' ), $style );

		$style = preg_replace( '/(\/\*).+(\*\/)/', '', $style ); // remove statements
		$style = preg_replace( '/\s+/', ' ', $style );
		$style = preg_replace( '/;(?=\s*})/', '', $style );
		$style = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $style );
		$style = preg_replace( '/ (,|;|\{|})/', '$1', $style );
		$style = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $style );
		$style = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $style );

		// Trim
		$style = trim( $style );
		return $style;
	}
endif;

if ( ! function_exists( 'molla_get_page_blocks' ) ) :
	function molla_get_page_blocks() {

		global $molla_settings;

		$used_blocks = array();

		// header blocks
		$used_blocks = array_merge( $used_blocks, get_theme_mod( '_molla_blocks_header_top', array() ) );
		$used_blocks = array_merge( $used_blocks, get_theme_mod( '_molla_blocks_header_main', array() ) );
		$used_blocks = array_merge( $used_blocks, get_theme_mod( '_molla_blocks_header_bottom', array() ) );
		if ( isset( $molla_settings['header']['builder'] ) ) {
			$used_blocks[] = $molla_settings['header']['builder'];
		}

		// sidebar blocks
		$sidebar_blocks = get_theme_mod( '_molla_blocks_sidebar', array() );
		if ( ! empty( $sidebar_blocks ) ) {
			foreach ( $sidebar_blocks as $sidebar_id => $block_ids ) {
				if ( ! empty( $block_ids ) && ( 0 === strpos( $sidebar_id, 'footer-' ) ) ) {
					$used_blocks = array_merge( $used_blocks, $block_ids );
				}
			}
			if ( $molla_settings['sidebar']['active'] && isset( $molla_settings['sidebar']['name'] ) && isset( $sidebar_blocks[ $molla_settings['sidebar']['name'] ] ) ) {
				$used_blocks = array_merge( $used_blocks, $sidebar_blocks[ $molla_settings['sidebar']['name'] ] );
			}
		}
		if ( isset( $molla_settings['sidebar']['builder'] ) ) {
			$used_blocks[] = $molla_settings['sidebar']['builder'];
		}
		if ( isset( $molla_settings['footer']['builder'] ) ) {
			$used_blocks[] = $molla_settings['footer']['builder'];
		}

		// popups
		if ( isset( $molla_settings['popup'] ) ) {
			$used_blocks[] = $molla_settings['popup']['popup_id'];
		}

		// blocks in page-content
		$page_blocks = get_post_meta( get_the_ID(), '_molla_blocks_page_content' );
		if ( ! empty( $page_blocks ) ) {
			foreach ( $page_blocks[0] as $block ) {
				$used_blocks[] = $block;
			}
		}

		// meta-box blocks
		if ( ! empty( $molla_settings['meta_box'] ) ) {
			$locations = array(
				'top',
				'inner_top',
				'inner_bottom',
				'bottom',
			);

			foreach ( $locations as $location ) {
				if ( isset( $molla_settings['meta_box'][ $location ] ) ) {
					$used_blocks[] = $molla_settings['meta_box'][ $location ];
				}
			}
		}
		return array_fill_keys(
			array_unique( $used_blocks, SORT_NUMERIC ),
			array(
				'css' => false,
				'js'  => false,
			)
		);
	}
endif;

if ( ! function_exists( 'molla_using_elementor_block' ) ) :
	function molla_using_elementor_block() {
		global $molla_settings;
		foreach ( $molla_settings['page_blocks'] as $block => $enqueued ) {
			if ( molla_is_elementor_block( $block ) ) {
				return apply_filters( 'molla_using_elementor_block', true );
			}
		}
		if ( class_exists( 'WooCommerce' ) && is_product() ) {

		}
		return apply_filters( 'molla_using_elementor_block', false );
	}
endif;

if ( ! function_exists( 'molla_is_elementor_block' ) ) :
	function molla_is_elementor_block( $id ) {
		$elements_data = get_post_meta( $id, '_elementor_data', true );
		if ( get_post_meta( $id, '_elementor_edit_mode', true ) && $elements_data ) {
			return true;
		}
		return false;
	}
endif;

add_action( 'molla_save_used_widget', 'molla_save_used_widget' );

if ( ! function_exists( 'molla_save_used_widget' ) ) :
	function molla_save_used_widget( $widget ) {
		if ( ! is_singular() || molla_is_product() ) {
			return;
		}

		global $post;

		$used = get_post_meta( $post->ID, 'used_widgets' );
		if ( ! empty( $used ) ) {
			$used = json_decode( $used[0], true );
		}
		if ( ! in_array( $widget, $used ) ) {
			$used[] = $widget;
			update_post_meta( $post->ID, 'used_widgets', json_encode( $used ) );
		}
	}
endif;

function molla_nextend_social_login( $social ) {
	$res = '';
	if ( class_exists( 'NextendSocialLogin', false ) ) {
		$res = NextendSocialLogin::isProviderEnabled( $social );
	} else {
		if ( 'facebook' == $social ) {
			$res = defined( 'NEW_FB_LOGIN' );
		} elseif ( 'google' == $social ) {
			$res = defined( 'NEW_GOOGLE_LOGIN' );
		} elseif ( 'twitter' == $social ) {
			$res = defined( 'NEW_TWITTER_LOGIN' );
		}
	}

	return apply_filters( 'molla_nextend_social_login', $res, $social );
}
if ( ! function_exists( 'molla_get_page_layout' ) ) :
	function molla_get_page_layout( $post_arg = '' ) {
		$id = '';
		if ( molla_is_shop() || molla_is_in_category() ) {
			$id = wc_get_page_id( 'shop' );
		} elseif ( $post_arg ) {
			$id = $post_arg->ID;
		}

		$layout_mode = get_post_meta( $id, 'page_layout_mode' );
		if ( is_array( $layout_mode ) && count( $layout_mode ) ) {
			$layout_mode = $layout_mode[0];
		} else {
			$layout_mode = '';
		}
		if ( $layout_mode ) {
			return molla_get_post_id_by_name( 'page_layout', sanitize_text_field( $layout_mode ) );
		}
		return $id;
	}
endif;

if ( ! function_exists( 'molla_get_post_id_by_name' ) ) {
	function molla_get_post_id_by_name( $post_type, $name ) {
		global $wpdb;

		if ( is_numeric( $name ) ) {
			$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND ID = %s", $post_type, $name ) );
			if ( $id ) {
				return $id;
			}
			return $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'block' AND ID = %s", $post_type, $name ) );
		}
		$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_name = %s", $post_type, $name ) );
		if ( $id ) {
			return $id;
		}
		return $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'block' AND post_name = %s", $post_type, $name ) );
	}
}

function molla_get_menu_class( $menu_args, $menu = false ) {
	$skin     = '';
	$arrow    = '';
	$divider  = '';
	$effect   = '';
	$vertical = false;
	if ( is_array( $menu_args ) ) {
		$skin     = isset( $menu_args['skin'] ) ? $menu_args['skin'] : '';
		$arrow    = isset( $menu_args['arrow'] ) ? $menu_args['arrow'] : '';
		$divider  = isset( $menu_args['divider'] ) ? $menu_args['divider'] : '';
		$effect   = isset( $menu_args['effect'] ) ? $menu_args['effect'] : '';
		$vertical = isset( $menu_args['vertical'] ) ? $menu_args['vertical'] : false;
	} elseif ( $menu_args ) {
		$skin    = $menu_args;
		$arrow   = molla_option( $skin . '_menu_arrow' );
		$divider = molla_option( $skin . '_menu_divider' );
		$effect  = molla_option( $skin . '_menu_effect' );
	}

	$class = 'menu' . ( $menu ? ( ' custom_menu-' . $menu ) : '' ) . ( $skin ? ( ' menu-' . $skin ) : '' ) . ( $vertical ? ' menu-vertical' : '' ) . ( $arrow ? ' sf-arrows' : '' ) . ( $divider ? ' sf-dividers' : '' ) . ( $effect ? ( ' ' . $effect ) : '' );

	if ( molla_option( 'lazy_load_menu' ) && ! is_customize_preview() ) {
		$class .= ' lazy-menu';
	}

	return apply_filters( 'molla_nav_menu_classes', $class, $skin );
}

function molla_product_is_in_low_stock( $product = false ) {
	if ( ! $product ) {
		global $product;
	}

	if ( $product->get_manage_stock() ) {
		$limit     = molla_option( 'stock_limit_count' );
		$remaining = $product->get_stock_quantity();
		if ( ! $remaining ) {
			return false;
		}
		$limit = $product->get_low_stock_amount();
		if ( ! $limit ) {
			$total = $product->get_stock_quantity() + $product->get_total_sales();
			$limit = (int) $total / 100 * $limit;
		}
		if ( $remaining <= $limit ) {
			return true;
		}
	}
	return false;
}


if ( ! function_exists( 'molla_get_slider_classes' ) ) {
	function molla_get_slider_classes( $settings ) {

		wp_enqueue_script( 'owl-carousel' );

		$class = 'owl-carousel owl-theme owl-simple';

		if ( isset( $settings['product_slider_nav_type'] ) && 'full' == $settings['product_slider_nav_type'] ) {
			$class .= ' owl-full';
		} else {
			if ( isset( $settings['product_slider_nav_type'] ) && 'rounded' == $settings['product_slider_nav_type'] ) {
				$class .= ' owl-nav-rounded';
			}
			if ( isset( $settings['product_slider_nav_pos'] ) && 'owl-nav-inside' == $settings['product_slider_nav_pos'] ) {
				$class .= ' owl-nav-inside';
			} elseif ( isset( $settings['product_slider_nav_pos'] ) && 'owl-nav-top' == $settings['product_slider_nav_pos'] ) {
				$class .= ' owl-nav-top';
			}
		}

		if ( isset( $settings['slider_nav_show'] ) && 'yes' !== $settings['slider_nav_show'] ) {
			$class .= ' owl-nav-show';
		}

		if ( isset( $settings['slider_item_height'] ) && 'yes' == $settings['slider_item_height'] ) {
			$class .= ' carousel-equal-height';
		}

		return $class;
	}
}

if ( ! function_exists( 'molla_get_slider_attrs' ) ) {

	/**
	 * Get slider data attribute from settings array
	 *
	 * @since 1.0
	 *
	 * @param array $settings Slider settings array from elementor widget.
	 *
	 * @return string slider data attribute
	 */
	function molla_get_slider_attrs( $settings, $col_cnt, $self = '' ) {

		$breakpoints = array(
			'min' => 0,
			'xs'  => 320,
			'sm'  => 576,
			'md'  => 768,
			'lg'  => 992,
			'xl'  => 1200,
		);

		$extra_options = array();

		$margin = isset( $settings['spacing']['size'] ) ? $settings['spacing']['size'] : '';

		if ( $margin > 0 ) { // default is 0
			$extra_options['margin'] = $margin;
		}

		if ( isset( $settings['slider_auto_play'] ) && 'yes' == $settings['slider_auto_play'] ) { // default is false
			$extra_options['autoplay']           = true;
			$extra_options['autoplayHoverPause'] = true;
			$extra_options['loop']               = true;
		}
		if ( isset( $settings['slider_auto_play_time'] ) && 10000 !== (int) $settings['slider_auto_play_time'] ) { // default is 5000
			$extra_options['autoplayTimeout'] = (int) $settings['autoplay_timeout'];
		}

		if ( isset( $settings['slider_loop'] ) && 'no' == $settings['slider_loop'] ) {
			$extra_options['loop'] = false;
		}

		$responsive = array();
		foreach ( $col_cnt as $w => $c ) {
			$responsive[ $breakpoints[ $w ] ] = array(
				'items' => $c,
			);
		}

		if ( isset( $responsive[ $breakpoints['md'] ] ) && ! $responsive[ $breakpoints['md'] ] ) {
			$responsive[ $breakpoints['md'] ] = array();
		}
		if ( isset( $responsive[ $breakpoints['lg'] ] ) && ! $responsive[ $breakpoints['lg'] ] ) {
			$responsive[ $breakpoints['lg'] ] = array();
		}
		if ( isset( $responsive[ $breakpoints['xl'] ] ) && ! $responsive[ $breakpoints['xl'] ] ) {
			$responsive[ $breakpoints['xl'] ] = array();
		}

		if ( isset( $settings['slider_nav'] ) ) {
			$responsive[ $breakpoints['xl'] ]['nav'] = ( 'yes' == $settings['slider_nav'] );
			$responsive[ $breakpoints['lg'] ]['nav'] = ( 'yes' == $settings['slider_nav'] );
			$extra_options['nav']                    = $responsive[ $breakpoints['lg'] ]['nav'];
		}
		if ( isset( $settings['slider_dots'] ) ) {
			$responsive[ $breakpoints['xl'] ]['dots'] = ( 'yes' == $settings['slider_dots'] );
			$responsive[ $breakpoints['lg'] ]['dots'] = ( 'yes' == $settings['slider_dots'] );
			$extra_options['dots']                    = $responsive[ $breakpoints['lg'] ]['dots'];
		}
		if ( isset( $settings['slider_nav_tablet'] ) ) {
			$extra_options['nav'] = $responsive[ $breakpoints['md'] ]['nav'] = ( 'yes' == $settings['slider_nav_tablet'] );
		}
		if ( isset( $settings['slider_dots_tablet'] ) ) {
			$extra_options['dots'] = $responsive[ $breakpoints['md'] ]['dots'] = ( 'yes' == $settings['slider_dots_tablet'] );
		}
		if ( isset( $settings['slider_nav_mobile'] ) ) { // default is false
			$extra_options['nav'] = ( 'yes' == $settings['slider_nav_mobile'] );
		}
		if ( isset( $settings['slider_dots_mobile'] ) ) { // default is true
			$extra_options['dots'] = ( 'yes' == $settings['slider_dots_mobile'] );
		}

		$extra_options['responsive'] = $responsive;

		return $extra_options;
	}
}

