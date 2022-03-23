<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Initialize TGM plugins
 */
if ( current_user_can( 'manage_options' ) ) {
	class MollaTGMPlugins {

		/**
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		protected $plugins = array(
			array(
				'name'      => 'Molla Core',
				'slug'      => 'molla-core',
				'source'    => MOLLA_PLUGINS . '/molla-core.zip',
				'version'   => '1.3.2',
				'required'  => true,
				'url'       => 'molla-core/molla-core.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/molla_core.png',
			),
			array(
				'name'      => 'Kirki',
				'slug'      => 'kirki',
				'required'  => true,
				'url'       => 'kirki/kirki.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/kirki.png',
			),
			array(
				'name'      => 'Meta-Box',
				'slug'      => 'meta-box',
				'required'  => true,
				'url'       => 'meta-box/meta-box.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/meta_box.png',
			),
			array(
				'name'      => 'Elementor',
				'slug'      => 'elementor',
				'required'  => true,
				'url'       => 'elementor/elementor.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/elementor.png',
			),
			array(
				'name'      => 'Woocommerce',
				'slug'      => 'woocommerce',
				'required'  => true,
				'url'       => 'woocommerce/woocommerce.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/woocommerce.png',
			),
			array(
				'name'      => 'Contact Form 7',
				'slug'      => 'contact-form-7',
				'required'  => false,
				'url'       => 'contact-form-7/wp-contact-form-7.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/contact_form_7.png',
			),
			array(
				'name'      => 'YITH Woocommerce Wishlist',
				'slug'      => 'yith-woocommerce-wishlist',
				'required'  => false,
				'url'       => 'yith-woocommerce-wishlist/init.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/yith_wishlist.png',
			),
			array(
				'name'      => 'YITH Woocommerce Ajax Product Filter',
				'slug'      => 'yith-woocommerce-ajax-navigation',
				'required'  => false,
				'url'       => 'yith-woocommerce-ajax-navigation/init.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/yith_ajax_filter.png',
			),
			array(
				'name'      => 'Dynamic Featured Image',
				'slug'      => 'dynamic-featured-image',
				'required'  => false,
				'url'       => 'dynamic-featured-image/dynamic-featured-image.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/dynamic_featured_image.png',
			),
			array(
				'name'      => 'Regenerate Thumbnails',
				'slug'      => 'regenerate-thumbnails',
				'required'  => false,
				'url'       => 'regenerate-thumbnails/regenerate-thumbnails.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/regenerate_thumbnails.png',
			),
			array(
				'name'      => 'YITH Woocommerce Ajax Search',
				'slug'      => 'yith-woocommerce-ajax-search',
				'required'  => false,
				'url'       => 'yith-woocommerce-ajax-search/init.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/yith_ajax_search.png',
			),
			array(
				'name'      => 'Dokan',
				'slug'      => 'dokan-lite',
				'required'  => false,
				'url'       => 'dokan-lite/dokan.php',
				'image_url' => MOLLA_PLUGINS_URI . '/images/dokan-logo.png',
			),
			array(
				'name'       => 'WP Super Cache',
				'slug'       => 'wp-super-cache',
				'required'   => false,
				'url'        => 'wp-super-cache/wp-cache.php',
				'visibility' => 'speed_wizard',
				'desc'       => 'This plugin generates static html files from your dynamic WordPress blog.',
			),
			array(
				'name'       => 'Fast Velocity Minify',
				'slug'       => 'fast-velocity-minify',
				'required'   => false,
				'url'        => 'fast-velocity-minify/fvm.php',
				'visibility' => 'speed_wizard',
				'desc'       => 'This plugin reduces HTTP requests by merging CSS & Javascript files into groups of files, while attempting to use the least amount of files as possible.',
			),
		);

		public function __construct() {

			/*************************/
			/* TGM Plugin Activation */
			/*************************/
			$plugin = MOLLA_PLUGINS . '/tgm-plugin-activation/class-tgm-plugin-activation.php';
			if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
				require_once $plugin;
			}

			add_action( 'tgmpa_register', array( $this, 'molla_register_required_plugins' ) );

			add_filter( 'tgmpa_notice_action_links', array( $this, 'molla_update_action_links' ), 10, 1 );

		}

		public function molla_register_required_plugins() {
			/**
			 * Array of configuration settings. Amend each line as needed.
			 * If you want the default strings to be available under your own theme domain,
			 * leave the strings uncommented.
			 * Some of the strings are added into a sprintf, so see the comments at the
			 * end of each line for what each argument will be.
			 */
			$config = array(
				'domain'       => 'molla',                     // Text domain - likely want to be the same as your theme.
				'default_path' => '',                          // Default absolute path to pre-packaged plugins
				'menu'         => 'install-required-plugins',  // Menu slug
				'has_notices'  => true,                        // Show admin notices or not
				'is_automatic' => true,                        // Automatically activate plugins after installation or not
				'message'      => '',                          // Message to output right before the plugins table
			);

			tgmpa( $this->plugins, $config );
		}

		public function get_plugins_list() {
			return $this->plugins;
		}
		public function molla_update_action_links( $action_links ) {
			$url = add_query_arg(
				array(
					'page' => 'molla-setup-wizard',
					'step' => 'default_plugins',
				),
				self_admin_url( 'admin.php' )
			);
			foreach ( $action_links as $key => $link ) {
				if ( $link ) {
					$link                 = preg_replace( '/<a([^>]*)href="([^"]*)"/i', '<a$1href="' . esc_url( $url ) . '"', $link );
					$action_links[ $key ] = $link;
				}
			}
			return $action_links;
		}
	}

	$mollaTGMPlugins = new MollaTGMPlugins();

	// disable master slider auto update
	add_filter( 'masterslider_disable_auto_update', '__return_true' );

	if ( ! function_exists( 'is_plugin_activate' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	if ( class_exists( 'WooCommerce' ) ) :
		add_action( 'admin_init', 'molla_include_woo_templates' );

		function molla_include_woo_templates() {
			include_once( WC()->plugin_path() . '/includes/wc-template-functions.php' );
		}
	endif;
}
