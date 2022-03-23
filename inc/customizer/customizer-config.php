<?php

/**
 * Customizer Config
 */

if ( ! class_exists( 'Molla_Customizer_Config' ) ) :

	class Molla_Customizer_Config {

		protected $wp_customize;

		public function __construct() {

			add_action( 'customize_register', array( $this, 'customize_register' ) );
			// Customize Panel Style
			add_action( 'customize_controls_print_styles', array( $this, 'enqueue_customizer_stylesheet' ) );
			add_action( 'customize_controls_print_styles', array( $this, 'customizer_enqueue_stylesheets' ) );

			// Customize Panel Script
			add_action( 'customize_preview_init', array( $this, 'customizer_live_control' ), 12 );

			// Customize Navigator
			add_action( 'customize_controls_print_scripts', array( $this, 'customizer_navigator' ) );

			// Save customizer navigator
			add_action( 'wp_ajax_molla_save_customize_nav', array( $this, 'customizer_nav_save' ) );
			add_action( 'wp_ajax_nopriv_molla_save_customize_nav', array( $this, 'customizer_nav_save' ) );

			// Import/Export customizer options
			add_action( 'wp_ajax_molla_customizer_import_options', array( $this, 'customizer_import' ) );
			add_action( 'wp_ajax_nopriv_molla_customizer_import_options', array( $this, 'customizer_import' ) );
			add_action( 'wp_ajax_molla_customizer_export_options', array( $this, 'customizer_export' ) );
			add_action( 'wp_ajax_nopriv_molla_customizer_export_options', array( $this, 'customizer_export' ) );

			// Reset customizer options
			add_action( 'wp_ajax_molla_customizer_reset_options', array( $this, 'customizer_reset' ) );
			add_action( 'wp_ajax_nopriv_molla_customizer_reset_options', array( $this, 'customizer_reset' ) );

			add_action( 'customize_controls_print_styles', array( $this, 'init_kirki' ) );
		}


		public function customize_register( $wp_customize ) {
			$this->wp_customize = $wp_customize;
		}

		public function enqueue_customizer_stylesheet() {
			if ( is_rtl() ) {
				wp_enqueue_style( 'molla-customizer', MOLLA_CSS . '/admin/customizer-rtl.css' );
			} else {
				wp_enqueue_style( 'molla-customizer', MOLLA_CSS . '/admin/customizer-ltr.css' );
			}
		}


		public function customizer_enqueue_stylesheets() {
			wp_enqueue_script( 'jquery-ui-sortable', MOLLA_JS . '/plugins/jquery-ui.sortable.min.js', array( 'jquery' ), '1.11.4', true );
			wp_enqueue_script( 'molla-customizer-admin-js', MOLLA_JS . '/admin/customizer-admin.min.js', array( 'jquery-ui-sortable' ), '1.0', 'all' );
			wp_enqueue_script( 'isotope-pkgd' );
			wp_enqueue_script( 'owl-carousel' );
			wp_localize_script(
				'molla-customizer-admin-js',
				'customizer_admin_vars',
				array(
					'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
					'nonce'    => wp_create_nonce( 'molla-customizer' ),
				)
			);
		}

		public function customizer_live_control() {
			if ( is_rtl() ) {
				wp_enqueue_style( 'molla-customize-preview', MOLLA_CSS . '/admin/customizer-preview-rtl.css' );
			} else {
				wp_enqueue_style( 'molla-customize-preview', MOLLA_CSS . '/admin/customizer-preview-ltr.css' );
			}

			wp_enqueue_script( 'molla-customizer-preview-js', MOLLA_JS . '/admin/customizer-preview.js', false, true );
		}

		public function customizer_navigator() {
			// Include Customizer Navigation
			require_once( MOLLA_LIB . '/customizer/customizer-nav.php' );
		}

		public function customizer_nav_save() {
			if ( ! check_ajax_referer( 'molla-customizer', 'nonce', false ) ) {
				wp_send_json_error( 'invalid_nonce' );
			}

			if ( isset( $_POST['navs'] ) ) {
				set_theme_mod( 'navigator_items', $_POST['navs'] );
				wp_send_json_success();
			}
		}

		public function customizer_import() {
			if ( ! $this->wp_customize->is_preview() ) {
				wp_send_json_error( 'not_preview' );
			}

			if ( ! check_ajax_referer( 'molla-customizer', 'nonce', false ) ) {
				wp_send_json_error( 'invalid_nonce' );
			}

			if ( isset( $_POST['src'] ) ) {
				$src = wp_normalize_path( $_POST['src'] );

				if ( ! file_exists( $src ) ) {
					wp_send_json_error( 'not_exists' );
				}

				if ( ! is_file( $src ) ) {
					wp_send_json_error( 'invalid_type' );
				}

				ob_start();
				include $src;
				$theme_options = ob_get_clean();

				$theme_options = json_decode( $theme_options, true );

				update_option( 'theme_mods_' . get_option( 'stylesheet' ), $theme_options );
				wp_send_json_success();
			}
		}

		public function customizer_export() {
			if ( ! $this->wp_customize->is_preview() ) {
				wp_send_json_error( 'not_preview' );
			}

			if ( ! check_ajax_referer( 'molla-customizer', 'nonce', false ) ) {
				wp_send_json_error( 'invalid_nonce' );
			}

			if ( isset( $_POST['target'] ) ) {
				$options = json_encode( get_theme_mods(), JSON_UNESCAPED_SLASHES );

				// filesystem
				global $wp_filesystem;
				// Initialize the WordPress filesystem, no more using file_put_contents function
				if ( empty( $wp_filesystem ) ) {
					require_once( ABSPATH . '/wp-admin/includes/file.php' );
					WP_Filesystem();
				}

				$handle = wp_normalize_path( $_POST['target'] );

				if ( ! $wp_filesystem->put_contents( $handle, $options, FS_CHMOD_FILE ) ) {
					@unlink( $handle );
					wp_send_json_error( 'failure' );
				}

				wp_send_json_success();
				exit();
			}

		}

		public function customizer_reset() {
			if ( ! $this->wp_customize->is_preview() ) {
				wp_send_json_error( 'not_preview' );
			}

			if ( ! check_ajax_referer( 'molla-customizer', 'nonce', false ) ) {
				wp_send_json_error( 'invalid_nonce' );
			}

			$settings = $this->wp_customize->settings();

			// remove theme_mod settings registered in customizer
			foreach ( $settings as $setting ) {
				if ( 'theme_mod' == $setting->type ) {
					remove_theme_mod( $setting->id );
				}
			}
			$additional = array(
				'molla_header_builder',
			);
			foreach ( $additional as $setting ) {
				remove_theme_mod( $setting );
			}

			molla_init_header_options();

			wp_send_json_success();
		}

		public function init_kirki() {
			wp_enqueue_script( 'molla-kirki-init', MOLLA_JS . '/admin/kirki-init.min.js', array( 'kirki-script' ), MOLLA_VERSION, false );
		}

	}
endif;

return new Molla_Customizer_Config();
