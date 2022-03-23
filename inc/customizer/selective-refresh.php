<?php

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'molla_customizer_refresh_partials' );

function molla_customizer_refresh_partials( $wp_customize ) {
	$wp_customize->selective_refresh->add_partial(
		'dynamic_css',
		array(
			'selector'        => '#molla-theme-inline-css',
			'settings'        => array(
				'primary_color',
				'secondary_color',
				'font_heading_spacing',
				'font_paragraph_spacing',
				'font_nav_spacing',
				'container_width',
				'grid_gutter_width',
				'skin1_menu_subtitle_color',
				'skin1_menu_item_color',
				'skin2_menu_subtitle_color',
				'skin2_menu_item_color',
				'skin3_menu_subtitle_color',
				'skin3_menu_item_color',
				'entry_body_padding',
				'blog_shadow_hover',
				'header_top_bg_sticky',
				'header_main_bg_sticky',
				'header_bottom_bg_sticky',
				'product_rating_icon',
				'custom_css',
			),
			'render_callback' => function() {
				echo molla_get_dynamic_css( false, 'customize-preview' ) . molla_get_dynamic_css();
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'internal_css',
		array(
			'selector'            => '#molla-style-inline-css',
			'container_inclusive' => false,
			'settings'            => array(
				'molla_header_builder[custom_css]',
			),
			'render_callback'     => function() {
				echo molla_get_internal_css();
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'custom_script',
		array(
			'selector'            => '#molla_dynamic_custom_script',
			'container_inclusive' => false,
			'settings'            => array(
				'custom_script',
			),
			'render_callback'     => function() {
				echo trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', molla_option( 'custom_script' ) ) );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'header_logo',
		array(
			'selector'            => '.header .logo',
			'container_inclusive' => true,
			'settings'            => array(
				'site_logo',
				'site_retina_logo',
			),
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/elements/logo' );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'header',
		array(
			'selector'            => '.header',
			'settings'            => array(
				'hb_options[top_left]',
				'hb_options[top_center]',
				'hb_options[top_right]',
				'hb_options[main_left]',
				'hb_options[main_center]',
				'hb_options[main_right]',
				'hb_options[bottom_left]',
				'hb_options[bottom_center]',
				'hb_options[bottom_right]',
				'hb_options[mobile_top_left]',
				'hb_options[mobile_top_center]',
				'hb_options[mobile_top_right]',
				'hb_options[mobile_main_left]',
				'hb_options[mobile_main_center]',
				'hb_options[mobile_main_right]',
				'hb_options[mobile_bottom_left]',
				'hb_options[mobile_bottom_center]',
				'hb_options[mobile_bottom_right]',
				'main_menu_skin',
			),
			'container_inclusive' => true,
			'render_callback'     => function() {
				molla_get_template_part( 'template-parts/header/header' );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'header_nav_group',
		array(
			'selector'            => '.header .top-menu',
			'container_inclusive' => true,
			'settings'            => array(
				'top_nav_items',
			),
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/elements/nav-top' );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'header_search_form',
		array(
			'selector'        => '.header-search',
			'settings'        => array(
				'search_content_type',
				'search_by_categories',
			),
			'render_callback' => function() {
				get_search_form( array( 'aria_label' => 'header' ) );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'header_social_icons',
		array(
			'selector'            => '.header .social-icons',
			'container_inclusive' => true,
			'settings'            => array(
				'header_social_links',
				'facebook',
				'linkedin',
				'twitter',
				'instagram',
				'youtube',
				'pinterest',
				'tumblr',
				'whatsapp',
				'header_social_type',
				'header_social_size',
			),
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/elements/social' );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'header_shop_icons',
		array(
			'selector'            => '.header .shop-icons',
			'container_inclusive' => true,
			'settings'            => array(
				'shop_icons',
				'shop_icon_type',
			),
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/elements/shop' );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'footer_top',
		array(
			'selector'            => '.footer .footer-top',
			'container_inclusive' => true,
			'settings'            => array(
				'footer_top_cols',
			),
			'render_callback'     => function() {
				molla_get_template_part( 'template-parts/footer/footer', 'top', array( 'width' => molla_option( 'footer_width' ) ) );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'footer_main',
		array(
			'selector'            => '.footer .footer-main',
			'container_inclusive' => true,
			'settings'            => array(
				'footer_main_cols',
			),
			'render_callback'     => function() {
				molla_get_template_part( 'template-parts/footer/footer', 'main', array( 'width' => molla_option( 'footer_width' ) ) );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'footer_bottom',
		array(
			'selector'            => '.footer .footer-bottom',
			'container_inclusive' => true,
			'settings'            => array(
				'footer_bottom_items',
				'footer_payment',
				'footer_custom_html',
			),
			'render_callback'     => function() {
				molla_get_template_part( 'template-parts/footer/footer', 'bottom', array( 'width' => molla_option( 'footer_width' ) ) );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'share_icons',
		array(
			'selector'            => '.social-icons:not(.yith-wcwl-share) .social-icons',
			'container_inclusive' => true,
			'settings'            => array(
				'share_icons',
				'share_icon_type',
				'share_icon_size',
			),
			'render_callback'     => function() {
				get_template_part( 'template-parts/partials/share' );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_related_posts',
		array(
			'selector'            => '.related-posts',
			'container_inclusive' => true,
			'settings'            => array(
				'related_posts_sort_by',
				'related_posts_sort_order',
				'related_posts_cols',
				'related_posts_padding',
				'related_posts_nav',
				'related_posts_dot',
				'related_posts_loop',
			),
			'render_callback'     => function() {
				get_template_part( 'template-parts/posts/partials/post', 'related' );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_entries',
		array(
			'selector'            => '.posts',
			'container_inclusive' => true,
			'settings'            => array(
				'blog_entry_type',
				'blog_excerpt_unit',
				'blog_excerpt_length',
			),
			'render_callback'     => function() {
				get_template_part( 'template-parts/posts/loop/loop', 'content' );
			},
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'single_post',
		array(
			'selector'            => '.post-single',
			'container_inclusive' => true,
			'settings'            => array(
				'blog_single_featured_image',
				'blog_single_meta',
				'blog_single_category',
				'blog_single_author_box',
				'blog_single_prev_next_nav',
				'blog_single_tag',
				'blog_single_share',
				'blog_single_related',
				'blog_single_share_pos',
			),
			'render_callback'     => function() {
				get_template_part( 'template-parts/posts/single/single', 'content' );
			},
		)
	);
}
