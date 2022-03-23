<?php

/**
 * Theme Setup
 */

function molla_setup() {
	// add woocommerce support
	add_theme_support( 'woocommerce' );

	// add title tag support
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-header', array() );
	add_theme_support( 'custom-background', array() );

	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'post-thumbnails' );

	add_theme_support( 'post-formats', array( 'video' ) );

	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );

	add_theme_support( 'wc-product-gallery-lightbox' );

	add_editor_style();

	// translation
	load_theme_textdomain( 'molla', MOLLA_DIR . '/languages' );
	load_child_theme_textdomain( 'molla', get_stylesheet_directory() . '/languages' );

	// Editor color palette.
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => esc_html__( 'Primary', 'molla' ),
				'slug'  => 'primary',
				'color' => molla_option( 'primary_color' ),
			),
			array(
				'name'  => esc_html__( 'Secondary', 'molla' ),
				'slug'  => 'secondary',
				'color' => molla_option( 'secondary_color' ),
			),
			array(
				'name'  => esc_html__( 'Alert', 'molla' ),
				'slug'  => 'alert',
				'color' => molla_option( 'alert_color' ),
			),
			array(
				'name'  => esc_html__( 'Dark', 'molla' ),
				'slug'  => 'dark',
				'color' => molla_option( 'dark_color' ),
			),
			array(
				'name'  => esc_html__( 'Light', 'molla' ),
				'slug'  => 'light',
				'color' => molla_option( 'light_color' ),
			),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);

	// register menus
	register_nav_menus(
		array(
			'main_menu'         => esc_html__( 'Main Menu', 'molla' ),
			'secondary_menu'    => esc_html__( 'Secondary Menu', 'molla' ),
			'top_nav'           => esc_html__( 'Top Navigation', 'molla' ),
			'lang_switcher'     => esc_html__( 'Language Switcher', 'molla' ),
			'currency_switcher' => esc_html__( 'Currency Switcher', 'molla' ),
		)
	);

	// add image sizes
	add_image_size( 'molla_post-grid', 400, 267, true );
	$size = molla_option( 'custom_image_size' );
	if ( isset( $size['Width'] ) && $size['Width'] && isset( $size['Height'] ) && $size['Height'] ) {
		add_image_size( 'Molla Custom', (int) $size['Width'], (int) $size['Height'], true );
	}
}
add_action( 'after_setup_theme', 'molla_setup' );

/**
 * Setup Theme Widgets
 */


/**
 * Setup Molla Styles and Scripts
 */

function molla_style() {
	global $molla_settings;
	$molla_settings['page_blocks'] = molla_get_page_blocks();

	// Disable font awesome of YITH. review rating of core plugin widget
	wp_dequeue_style( 'yith-wcwl-font-awesome' );
	wp_deregister_style( 'yith-wcwl-font-awesome' );

	do_action( 'molla_before_theme_style' );

	// Enqueue plugin styles.
	wp_enqueue_style( 'animate', MOLLA_VENDOR . '/animate/animate.min.css' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'plugins', MOLLA_PLUGINS_CSS . '/plugins-rtl.css' );
	} else {
		wp_enqueue_style( 'plugins', MOLLA_PLUGINS_CSS . '/plugins.css' );
	}

	// Enqueue theme styles.
	if ( molla_option( 'minify_font_icons' ) ) {
		wp_enqueue_style( 'font-awesome-all', MOLLA_VENDOR . '/font-awesome/optimized/css/optimized.css' );
		wp_enqueue_style( 'molla-font-icon', MOLLA_VENDOR . '/molla-fonts/css/font-icons-optimized.css' );
	} else {
		wp_enqueue_style( 'font-awesome-all', MOLLA_VENDOR . '/font-awesome/all/css/all.min.css' );
		wp_enqueue_style( 'molla-font-icon', MOLLA_VENDOR . '/molla-fonts/css/font-icons.css' );
	}

	$exist_flag = false;
	if ( ! is_customize_preview() && ! ( defined( 'ELEMENTOR_VERSION' ) && molla_is_elementor_preview() ) ) {
		if ( is_singular() && file_exists( wp_normalize_path( wp_upload_dir()['basedir'] . '/molla_styles/page-' . get_the_ID() . '-style' . ( is_rtl() ? '-rtl' : '' ) . '.css' ) ) ) {
			// Enqueue minified page css with only used widgets
			wp_enqueue_style( 'molla-theme', wp_normalize_path( wp_upload_dir()['baseurl'] . '/molla_styles/page-' . get_the_ID() . '-style' . ( is_rtl() ? '-rtl' : '' ) . '.css' ), array(), MOLLA_VERSION );
			$exist_flag = true;
		}
	}
	// Enqueue theme styles.
	if ( ! $exist_flag ) {
		wp_enqueue_style( 'molla-theme', MOLLA_CSS . '/frontend/theme' . ( is_rtl() ? '-rtl' : '' ) . '.css', array(), MOLLA_VERSION );
	}

	wp_enqueue_style( 'molla-dynamic-style' );

	molla_load_google_font();

	do_action( 'molla_before_custom_style' );

	// custom styles
	wp_enqueue_style( 'molla-style', MOLLA_URI . '/style.css' );
}

add_action( 'wp_enqueue_scripts', 'molla_style', 20 );

function molla_scripts() {

	// Register sripts
	wp_register_script( 'jquery-fitvids', MOLLA_JS . '/plugins/jquery.fitvids.min.js', array(), '1.1', true );
	wp_register_script( 'jquery-lazyload', MOLLA_JS . '/plugins/jquery.lazyload.js', array(), '1.9.7', true );

	// Enqueue theme scripts.
	wp_register_script( 'isotope-pkgd', MOLLA_JS . '/plugins/isotope.pkgd.min.js', array( 'jquery-core', 'imagesloaded' ), '3.0.6', true );
	wp_register_script( 'owl-carousel', MOLLA_JS . '/plugins/owl.carousel.min.js', array( 'jquery-core', 'imagesloaded' ), '2.3.4', true );
	wp_register_script( 'jquery-cookie', MOLLA_JS . '/plugins/jquery.cookie.min.js', array(), '1.4.1', true );
	//wp_register_script( 'jquery-elevateZoom', MOLLA_JS . '/plugins/jquery.elevatezoom.js', array(), '3.0.8', true );
	wp_register_script( 'threesixty-slider', MOLLA_JS . '/plugins/threesixty.min.js', array(), '2.0.5', true );
	wp_register_script( 'jquery-waypoints', MOLLA_JS . '/plugins/jquery.waypoints.min.js', array(), '4.0.1', true );
	wp_register_script( 'jquery-countTo', MOLLA_JS . '/plugins/jquery.countTo.min.js', array(), true, true );
	wp_register_script( 'jquery-vide', MOLLA_JS . '/plugins/jquery.vide.min.js', array(), '0.5.1', true );
	wp_register_script( 'jquery-parallax', MOLLA_JS . '/plugins/jquery.parallax.min.js', array(), true, true );
	wp_register_script( 'bootstrap-bundle', MOLLA_JS . '/bootstrap.bundle.min.js', array(), '4.3.1', true );
	wp_register_script( 'bootstrap-input-spinner', MOLLA_JS . '/bootstrap-input-spinner.js', array(), true, true );
	wp_register_script( 'molla-interactive-section-scroll', MOLLA_JS . '/interactive-section-scroll.min.js', array(), MOLLA_VERSION, true );
	wp_register_script( 'molla-infinite-scroll', MOLLA_JS . '/infinite-scroll.min.js', array(), MOLLA_VERSION, true );
	wp_register_script( 'molla-theme', MOLLA_JS . '/theme.min.js', array(), MOLLA_VERSION, true );
	wp_register_script( 'molla-sticky', MOLLA_JS . '/sticky.js', array( 'jquery' ), MOLLA_VERSION, true );

	wp_enqueue_script( 'jquery-appear', MOLLA_JS . '/plugins/jquery.appear.js', array(), true, true );
	wp_enqueue_script( 'jquery-plugin', MOLLA_JS . '/plugins/jquery.plugin.min.js', array(), true, true );
	wp_enqueue_script( 'jquery-countdown', MOLLA_JS . '/plugins/jquery.countdown.min.js', array(), '2.1.0', true );
	wp_enqueue_script( 'molla-sticky' );
	wp_enqueue_script( 'molla-main', MOLLA_JS . '/main.js', array( 'jquery' ), MOLLA_VERSION, true );
	wp_enqueue_script( 'jquery-magnific-popup', MOLLA_JS . '/plugins/jquery.magnific-popup.min.js', array( 'jquery-core', 'imagesloaded' ), '1.1.0', true );
	wp_enqueue_script( 'zoom' );
	wp_enqueue_script( 'wc-single-product' );
	wp_enqueue_script( 'wc-add-to-cart-variation' );

	if ( molla_option( 'cart_canvas_type' ) ) {
		wp_enqueue_script( 'bootstrap-input-spinner' );
	}
	if ( class_exists( 'WooCommerce' ) ) {
		if ( molla_is_product() || is_page( 'cart' ) ) {
			wp_enqueue_script( 'bootstrap-input-spinner' );
		}
		if ( molla_is_product() ) {
			wp_enqueue_script( 'owl-carousel' );
			//          wp_enqueue_script( 'jquery-elevateZoom' );
			wp_enqueue_script( 'jquery-magnific-popup' );
			wp_enqueue_script( 'bootstrap-bundle' );
		}
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	global $molla_settings;

	if ( isset( $molla_settings['popup'] ) ) {
		wp_enqueue_script( 'jquery-cookie' );
	}

	wp_localize_script(
		'molla-main',
		'theme',
		apply_filters(
			'molla_add_var_main_js',
			array(
				'ajax_url'        => esc_js( admin_url( 'admin-ajax.php' ) ),
				'assets_url'      => esc_js( MOLLA_URI . '/assets/' ),
				'wc_url'          => esc_js( class_exists( 'WooCommerce' ) ? WC()->plugin_url() . '/assets/' : '' ),
				'nonce'           => wp_create_nonce( 'molla_theme_action_nonce' ),
				'lazyload'        => molla_option( 'lazy_load_img' ),
				'quickview'       => molla_option( 'quickview_style' ),
				'prevent_elevate' => molla_option( 'prevent_elevatezoom' ),
				'pre_order'       => class_exists( 'WooCommerce' ) && is_product() && molla_option( 'woo_pre_order' ),
				'popup'           => ( ! molla_is_elementor_preview() && isset( $molla_settings['popup'] ) ) ? $molla_settings['popup'] : '',
			)
		)
	);
}

add_action( 'wp_enqueue_scripts', 'molla_scripts' );

//Plugins
// Dokan Functions
if ( class_exists( 'WeDevs_Dokan' ) ) {
	require_once( MOLLA_PLUGINS . '/compatibility/dokan/dokan.php' );
}

// WCFM Functions
if ( class_exists( 'WCFM' ) ) {
	require_once( MOLLA_PLUGINS . '/compatibility/wcfm/wcfm.php' );
}


// Admin style & script
if ( is_admin() ) {
	// Load scripts in the WP admin
	add_action( 'admin_enqueue_scripts', 'molla_admin_scripts' );

	add_action(
		'enqueue_block_editor_assets',
		function() {
			if ( is_rtl() ) {
				wp_enqueue_style( 'molla_style_editor', MOLLA_CSS . '/frontend/style-editor-rtl.css' );
			} else {
				wp_enqueue_style( 'molla_style_editor', MOLLA_CSS . '/frontend/style-editor-ltr.css' );
			}
			ob_start();
			include MOLLA_FUNCTIONS . '/dynamic_css/parts/gutenberg.php';

			$style = wp_strip_all_tags( ob_get_clean() );
			if ( function_exists( 'molla_minify_css' ) ) {
				$style = molla_minify_css( $style, molla_option( 'minify_css_js' ) );
			}
			wp_add_inline_style( 'molla_style_editor', $style );

			molla_load_google_font();
		}
	);
}

function molla_admin_scripts() {
	wp_enqueue_media();

	wp_enqueue_script( 'wp-color-picker' );

	// Enqueue theme styles.
	if ( molla_option( 'minify_font_icons' ) ) {
		wp_enqueue_style( 'font-awesome-all', MOLLA_VENDOR . '/font-awesome/optimized/css/optimized.css' );
		wp_enqueue_style( 'molla-font-icon', MOLLA_VENDOR . '/molla-fonts/css/font-icons-optimized.css' );
	} else {
		wp_enqueue_style( 'font-awesome-all', MOLLA_VENDOR . '/font-awesome/all/css/all.min.css' );
		wp_enqueue_style( 'molla-font-icon', MOLLA_VENDOR . '/molla-fonts/css/font-icons.css' );
	}

	global $pagenow;
	if ( isset( $_GET['page'] ) && 'admin.php' == $pagenow && 0 === strpos( $_GET['page'], 'molla' ) ) {
		wp_enqueue_style( 'molla-admin-fonts', '//fonts.googleapis.com/css?family=Poppins%3A400%2C500%2C600%2C700' );
	}

	if ( is_rtl() ) {
		wp_enqueue_style( 'molla-admin', MOLLA_CSS . '/admin/admin-rtl.css' );
		wp_enqueue_style( 'molla-walker', MOLLA_CSS . '/admin/walker-rtl.css' );
		wp_enqueue_style( 'molla-metabox', MOLLA_CSS . '/admin/metabox-rtl.css' );
	} else {
		wp_enqueue_style( 'molla-admin', MOLLA_CSS . '/admin/admin-ltr.css' );
		wp_enqueue_style( 'molla-walker', MOLLA_CSS . '/admin/walker-ltr.css' );
		wp_enqueue_style( 'molla-metabox', MOLLA_CSS . '/admin/metabox-ltr.css' );
	}

	// Enqueue admin scripts.
	wp_enqueue_script( 'molla-admin', MOLLA_JS . '/admin/admin.min.js', array( 'jquery' ), MOLLA_VERSION, true );
	wp_enqueue_script( 'molla-metabox', MOLLA_JS . '/admin/metabox.min.js', array(), MOLLA_VERSION, true );

	wp_localize_script(
		'molla-admin',
		'theme',
		apply_filters(
			'molla_add_var_admin_js',
			array(
				'page_width' => molla_option( 'page_width' ),
				'post_width' => molla_option( 'blog_single_page_width' ),
			)
		)
	);
}
