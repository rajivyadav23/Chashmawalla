<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MOLLA_SETUP_WIZARD', MOLLA_LIB . '/admin/wizard/setup_wizard' ); // setup wizard directory

if ( ! class_exists( 'Molla_Setup_Wizard' ) ) :
	/**
	* Molla Theme Setup Wizard
	*/
	class Molla_Setup_Wizard {

		protected $version = '1.0';

		protected $theme_name = '';

		protected $step = '';

		protected $steps = array();

		public $page_slug;

		protected $tgmpa_instance;

		protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

		protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';

		protected $page_url;

		protected $molla_url = 'https://d-themes.com/wordpress/molla/';

		protected $demo;

		protected $path_demo = '';

		protected $path_demo_base = '';

		private static $instance = null;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			$this->current_theme_meta();
			$this->init_setup_wizard();
		}

		public function current_theme_meta() {
			$current_theme    = wp_get_theme();
			$this->theme_name = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
			$this->page_slug  = 'molla-setup-wizard';
			$this->page_url   = 'admin.php?page=' . $this->page_slug;
			$this->path_demo  = wp_normalize_path( MOLLA_SETUP_WIZARD . '/demos/' );
		}

		public function init_setup_wizard() {
			add_action( 'upgrader_post_install', array( $this, 'upgrader_post_install' ), 10, 2 );

			if ( apply_filters( $this->theme_name . '_enable_setup_wizard', false ) ) {
				return;
			}

			if ( ! is_child_theme() ) {
				add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
			}

			if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
				add_action( 'init', array( $this, 'get_tgmpa_instanse' ), 30 );
				add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
			}

			add_action( 'admin_menu', array( $this, 'admin_menus' ) );
			add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
			add_action( 'admin_init', array( $this, 'init_wizard_steps' ), 30 );
			add_action( 'admin_init', array( $this, 'setup_wizard_enqueue' ), 30 );
			// Plugin Install
			add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
			add_action( 'wp_ajax_molla_setup_wizard_plugins', array( $this, 'ajax_plugins' ) );
			//Demo Import
			add_action( 'wp_ajax_molla_reset_menus', array( $this, 'reset_menus' ) );
			add_action( 'wp_ajax_molla_reset_widgets', array( $this, 'reset_widgets' ) );
			add_action( 'wp_ajax_molla_import_dummy', array( $this, 'import_dummy' ) );
			add_action( 'wp_ajax_molla_import_dummy_step_by_step', array( $this, 'import_dummy_step_by_step' ) );
			add_action( 'wp_ajax_molla_import_widgets', array( $this, 'import_widgets' ) );
			add_action( 'wp_ajax_molla_import_icons', array( $this, 'import_icons' ) );
			add_action( 'wp_ajax_molla_import_options', array( $this, 'import_options' ) );
			add_action( 'wp_ajax_molla_delete_tmp_dir', array( $this, 'delete_tmp_dir' ) );
			add_action( 'wp_ajax_molla_download_demo_file', array( $this, 'download_demo_file' ) );

			add_filter( 'wp_import_existing_post', array( $this, 'import_override_contents' ), 10, 2 );
			add_action( 'import_start', array( $this, 'import_dummy_start' ) );
			add_action( 'import_end', array( $this, 'import_dummy_end' ) );
		}

		public function upgrader_post_install( $return, $theme ) {
			if ( is_wp_error( $return ) ) {
				return $return;
			}
			if ( get_stylesheet() != $theme ) {
				return $return;
			}
			update_option( 'molla_setup_complete', false );

			return $return;
		}

		public function admin_menus() {
			add_submenu_page( 'molla', esc_html__( 'Setup Wizard', 'molla' ), esc_html__( 'Setup Wizard', 'molla' ), 'manage_options', $this->page_slug, array( $this, 'view_setup_wizard' ) );
		}

		public function switch_theme() {
			set_transient( '_' . $this->theme_name . '_activation_redirect', 1 );
		}

		public function admin_redirects() {
			ob_start();

			if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) || get_option( 'molla_setup_complete', false ) ) {
				return;
			}
			delete_transient( '_' . $this->theme_name . '_activation_redirect' );
			wp_safe_redirect( admin_url( $this->page_url ) );
			exit;
		}

		public function get_tgmpa_instanse() {
			$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		}

		public function set_tgmpa_url() {

			$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
			$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );

			$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && 'themes.php' !== $this->tgmpa_instance->parent_slug ) ? 'admin.php' : 'themes.php';

			$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );

		}

		public function init_wizard_steps() {
			$this->steps = array(
				'introduction' => array(
					'step_id' => 1,
					'name'    => esc_html__( 'Welcome', 'molla' ),
					'view'    => array( $this, 'setup_wizard_welcome' ),
					'handler' => array( $this, 'molla_setup_wizard_welcome_save' ),
				),
			);

			$this->steps['updates'] = array(
				'step_id' => 2,
				'name'    => esc_html__( 'Activate', 'molla' ),
				'view'    => array( $this, 'setup_wizard_updates' ),
				'handler' => '',
			);

			$this->steps['status'] = array(
				'step_id' => 3,
				'name'    => esc_html__( 'Status', 'molla' ),
				'view'    => array( $this, 'setup_wizard_status' ),
				'handler' => array( $this, 'molla_setup_wizard_status_save' ),
			);

			$this->steps['customize'] = array(
				'step_id' => 4,
				'name'    => esc_html__( 'Child Theme', 'molla' ),
				'view'    => array( $this, 'setup_wizard_customize' ),
				'handler' => '',
			);

			if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
				$this->steps['default_plugins'] = array(
					'step_id' => 5,
					'name'    => esc_html__( 'Plugins', 'molla' ),
					'view'    => array( $this, 'setup_wizard_default_plugins' ),
					'handler' => '',
				);
			}
			$this->steps['demo_content'] = array(
				'step_id' => 6,
				'name'    => esc_html__( 'Demo Content', 'molla' ),
				'view'    => array( $this, 'setup_wizard_demo_content' ),
				'handler' => array( $this, 'molla_setup_wizard_demo_content_save' ),
			);

			$this->steps['support'] = array(
				'step_id' => 7,
				'name'    => esc_html__( 'Support', 'molla' ),
				'view'    => array( $this, 'setup_wizard_support' ),
				'handler' => '',
			);

			$this->steps['next_steps'] = array(
				'step_id' => 8,
				'name'    => esc_html__( 'Ready!', 'molla' ),
				'view'    => array( $this, 'setup_wizard_ready' ),
				'handler' => '',
			);

			$this->steps = apply_filters( $this->theme_name . '_theme_setup_wizard_steps', $this->steps );
		}

		// enqueue style & script
		public function setup_wizard_enqueue() {

			if ( empty( $_GET['page'] ) || $this->page_slug !== $_GET['page'] ) {
				return;
			}

			$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

			// Style
			if ( is_rtl() ) {
				wp_enqueue_style( 'molla-plugins', MOLLA_PLUGINS_CSS . '/plugins-rtl.css' );
				wp_enqueue_style( 'molla-setup_wiard', MOLLA_CSS . '/admin/wizard-rtl.css' );
			} else {
				wp_enqueue_style( 'molla-plugins', MOLLA_PLUGINS_CSS . '/plugins.css' );
				wp_enqueue_style( 'molla-setup_wiard', MOLLA_CSS . '/admin/wizard-ltr.css' );
			}
			wp_enqueue_style( 'wp-admin' );
			wp_enqueue_media();

			// Script
			wp_register_script( 'isotope-pkgd', MOLLA_JS . '/plugins/isotope.pkgd.min.js', false, true );
			wp_register_script( 'jquery-blockUI', MOLLA_JS . '/plugins/jquery.blockUI.min.js', false, true );
			wp_register_script( 'jquery-magnific-popup', MOLLA_JS . '/plugins/jquery.magnific-popup.min.js', false, true );
			wp_enqueue_script( 'molla-setup-wizard', MOLLA_JS . '/admin/setup-wizard.min.js', array( 'jquery', 'isotope-pkgd', 'jquery-blockUI', 'jquery-magnific-popup' ), true );
			wp_enqueue_script( 'molla-sticky', MOLLA_JS . '/sticky.min.js', false, true );
			wp_enqueue_script( 'media' );

			wp_localize_script(
				'molla-setup-wizard',
				'molla_setup_wizard_params',
				array(
					'tgm_plugin_nonce' => array(
						'update'  => wp_create_nonce( 'tgmpa-update' ),
						'install' => wp_create_nonce( 'tgmpa-install' ),
					),
					'tgm_bulk_url'     => esc_url( admin_url( $this->tgmpa_url ) ),
					'wpnonce'          => wp_create_nonce( 'molla_setup_wizard_nonce' ),
				)
			);
		}

		/**
		 * Display setup wizard
		 */
		public function view_setup_wizard() {
			$this->setup_wizard_header();
			$this->setup_wizard_steps();
			$this->setup_wizard_contents();
			$this->setup_wizard_footer();
		}

		public function setup_wizard_header() {
			include MOLLA_SETUP_WIZARD . '/views/header.php';
		}

		/**
		 * Output setup wizard step links
		 */
		public function setup_wizard_steps() {
			include MOLLA_SETUP_WIZARD . '/views/setup-step.php';
		}

		/**
		 * Output setup wizard contents
		 */
		public function setup_wizard_contents() {
			$show_content = true;
			if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
				$show_content = call_user_func( $this->steps[ $this->step ]['handler'] );
			}
			if ( $show_content ) {
				$this->setup_wizard_step_content();
			}
		}

		/**
		 * Output the content for the current step
		 */
		public function setup_wizard_step_content() {
			isset( $this->steps[ $this->step ] ) ? call_user_func( $this->steps[ $this->step ]['view'] ) : false;
		}

		public function setup_wizard_footer() {
			include MOLLA_SETUP_WIZARD . '/views/footer.php';
		}

		/**
		 * Output the step contents
		 */
		public function setup_wizard_welcome() {
			include MOLLA_SETUP_WIZARD . '/views/steps/welcome.php';
		}
		public function setup_wizard_updates() {
			include MOLLA_SETUP_WIZARD . '/views/steps/updates.php';
		}
		public function setup_wizard_status() {
			?>

			<aside class="content-left status">
			</aside>
			<div class="content-right">
				<h2><?php esc_html_e( 'System Status', 'molla' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Check your current server performance.', 'molla' ); ?></p>
				<?php include MOLLA_SETUP_WIZARD . '/views/steps/status.php'; ?>
				<p class="molla-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next" data-callback="install_plugins"><?php esc_html_e( 'Continue', 'molla' ); ?></a>
				</p>
			</div>
			<?php
		}
		public function setup_wizard_customize() {
			include MOLLA_SETUP_WIZARD . '/views/steps/customize.php';
		}
		public function setup_wizard_default_plugins() {

			tgmpa_load_bulk_installer();
			if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
				die( 'Failed to find TGM' );
			}
			$url     = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'molla-setup-wizard' );
			$plugins = $this->_get_plugins();

			$method = '';
			$fields = array_keys( $_POST );

			if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields ) ) ) {
				return true;
			}

			if ( ! WP_Filesystem( $creds ) ) {
				request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
				return true;
			}

			include MOLLA_SETUP_WIZARD . '/views/steps/plugins.php';
		}
		public function setup_wizard_demo_content() {
			$url    = wp_nonce_url( add_query_arg( array( 'demo_content' => 'go' ) ), 'molla-setup-wizard' );
			$method = '';
			$fields = array_keys( $_POST );
			if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields ) ) ) {
				return true;
			}

			if ( ! WP_Filesystem( $creds ) ) {
				request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
				return true;
			}
			include MOLLA_SETUP_WIZARD . '/views/steps/demo.php';
		}
		public function setup_wizard_support() {
			include MOLLA_SETUP_WIZARD . '/views/steps/support.php';
		}
		public function setup_wizard_ready() {
			include MOLLA_SETUP_WIZARD . '/views/steps/ready.php';
		}

		/**
		 * Save actions
		 */
		public function molla_setup_wizard_welcome_save() {
			check_admin_referer( 'molla-setup-wizard' );
			return false;
		}

		public function molla_setup_wizard_status_save() {
			check_admin_referer( 'molla-setup-wizard' );
		}

		public function molla_setup_wizard_demo_content_save() {

			check_admin_referer( 'molla-setup-wizard' );

			$new_logo_id = (int) $_POST['new_logo_id'];

			if ( $new_logo_id ) {
				$image = wp_get_attachment_image_src( $new_logo_id, 'full' );
				set_theme_mod( 'site_logo', $image[0] );
			}

			wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
			die();
		}

		/**
		 * Create child theme
		 */
		private function _make_child_theme( $new_theme_title ) {

			$parent_theme_title    = 'Molla';
			$parent_theme_template = 'molla';
			$parent_theme_name     = get_stylesheet();
			$parent_theme_dir      = get_stylesheet_directory();

			$new_theme_name = sanitize_title( $new_theme_title );
			$theme_root     = get_theme_root();

			$new_theme_path = $theme_root . '/' . $new_theme_name;
			if ( ! file_exists( $new_theme_path ) ) {
				wp_mkdir_p( $new_theme_path );

				$plugin_folder = get_parent_theme_file_path( 'inc/admin/wizard/setup_wizard/molla-child/' );

				ob_start();
				require $plugin_folder . 'style.css.php';
				$css = ob_get_clean();

				// filesystem
				global $wp_filesystem;
				// Initialize the WordPress filesystem, no more using file_put_contents function
				if ( empty( $wp_filesystem ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
					WP_Filesystem();
				}

				if ( ! $wp_filesystem->put_contents( $new_theme_path . '/style.css', $css, FS_CHMOD_FILE ) ) {
					echo '<p class="lead success">';
					esc_html_e( 'Directory permission required for /wp-content/themes.', 'molla' );
					echo '</p>';
					return;
				}

				// Copy functions.php
				copy( $plugin_folder . 'functions.php', $new_theme_path . '/functions.php' );

				// Copy screenshot
				copy( $plugin_folder . 'screenshot.jpg', $new_theme_path . '/screenshot.jpg' );

				// Make child theme an allowed theme (network enable theme)
				$allowed_themes[ $new_theme_name ] = true;
			}

			// Switch to theme
			if ( $parent_theme_template !== $new_theme_name ) {
				echo '<p class="lead success">';
				printf(
					wp_kses(
						__( 'Child Theme %1$s created and activated!<br />Folder is located in wp-content/themes/%2$s', 'molla' ),
						array(
							'br' => array(),
						)
					),
					'<strong>' . esc_html( $new_theme_title ) . '</strong>',
					'<strong>' . esc_html( $new_theme_name ) . '</strong>'
				);
				echo '</p>';
				switch_theme( $new_theme_name, $new_theme_name );
			}
		}

		/**
		 * Install plugins
		 */
		public function tgmpa_load( $status ) {
			return is_admin() || current_user_can( 'install_themes' );
		}

		private function _get_plugins() {
			$instance         = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
			$plugin_func_name = 'is_plugin_active';
			$plugins          = array(
				'all'      => array(), // Meaning: all plugins which still have open actions.
				'install'  => array(),
				'update'   => array(),
				'activate' => array(),
			);

			foreach ( $instance->plugins as $slug => $plugin ) {
				if ( $instance->$plugin_func_name( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
					continue;
				} else {
					$plugins['all'][ $slug ] = $plugin;

					if ( ! $instance->is_plugin_installed( $slug ) ) {
						$plugins['install'][ $slug ] = $plugin;
					} else {
						if ( false !== $instance->does_plugin_have_update( $slug ) ) {
							$plugins['update'][ $slug ] = $plugin;
						}

						if ( $instance->can_plugin_activate( $slug ) ) {
							$plugins['activate'][ $slug ] = $plugin;
						}
					}
				}
			}
			return $plugins;
		}
		public function ajax_plugins() {
			if ( ! check_ajax_referer( 'molla_setup_wizard_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => esc_html__(
							'No Slug Found',
							'molla'
						),
					)
				);
			}
			$json = array();
			// send back some json we use to hit up TGM
			$plugins = $this->_get_plugins();
			// what are we doing with this plugin?
			foreach ( $plugins['activate'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => esc_url( admin_url( $this->tgmpa_url ) ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-activate',
						'action2'       => -1,
						'message'       => esc_html__( 'Activating Plugin', 'molla' ),
					);
					break;
				}
			}
			foreach ( $plugins['update'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => esc_url( admin_url( $this->tgmpa_url ) ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-update',
						'action2'       => -1,
						'message'       => esc_html__( 'Updating Plugin', 'molla' ),
					);
					break;
				}
			}
			foreach ( $plugins['install'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => esc_url( admin_url( $this->tgmpa_url ) ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-install',
						'action2'       => -1,
						'message'       => esc_html__( 'Installing Plugin', 'molla' ),
					);
					break;
				}
			}

			if ( $json ) {
				$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
				wp_send_json( $json );
			} else {
				wp_send_json(
					array(
						'done'    => 1,
						'message' => esc_html__(
							'Success',
							'molla'
						),
					)
				);
			}
			exit;
		}


		/**
		 * Step links
		 */
		public function get_step_link( $step ) {
			return add_query_arg( 'step', $step, admin_url( 'admin.php?page=' . $this->page_slug ) );
		}
		public function get_next_step_link() {
			$keys = array_keys( $this->steps );
			return add_query_arg( 'step', $keys[ array_search( $this->step, array_keys( $this->steps ) ) + 1 ], remove_query_arg( 'translation_updated' ) );
		}

		/**
		 * Demo Import
		 */
		private function get_demo_file( $demo = false ) {
			if ( ! $demo ) {
				$demo = ( isset( $_POST['demo'] ) && $_POST['demo'] ) ? sanitize_text_field( $_POST['demo'] ) : 'landing';
			}
			$this->demo = $demo;

			// Return demo file path
			require_once MOLLA_PLUGINS . '/importer/importer-api.php';
			$importer_api = new Molla_Importer_API( $demo );

			$demo_file_path = $importer_api->get_remote_demo();
			if ( ! $demo_file_path ) {
				echo json_encode(
					array(
						'process' => 'error',
						'message' => __(
							'Remote API error.',
							'molla'
						),
					)
				);
				die();
			} elseif ( is_wp_error( $demo_file_path ) ) {
				echo json_encode(
					array(
						'process' => 'error',
						'message' => $demo_file_path->get_error_message(),
					)
				);
				die();
			}
			return $demo_file_path;
		}

		private function get_file_data( $path ) {
			$data = false;
			$path = wp_normalize_path( $path );
			// filesystem
			global $wp_filesystem;
			// Initialize the WordPress filesystem, no more using file_put_contents function
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}
			if ( $wp_filesystem->exists( $path ) ) {
				$data = $wp_filesystem->get_contents( $path );
			}
			return $data;
		}

		public function download_demo_file() {
			if ( ! check_ajax_referer( 'molla_setup_wizard_nonce', 'wpnonce', false ) ) {
				die();
			}
			$this->get_demo_file();
			echo json_encode( array( 'process' => 'success' ) );
			die();
		}

		/**
		 * Delete temporary directory
		 */
		function delete_tmp_dir() {
			if ( ! check_ajax_referer( 'molla_setup_wizard_nonce', 'wpnonce', false ) ) {
				die();
			}
			$demo = ( isset( $_POST['demo'] ) && $_POST['demo'] ) ? sanitize_text_field( $_POST['demo'] ) : 'landing';

			// Importer remote API
			require_once MOLLA_PLUGINS . '/importer/importer-api.php';
			$importer_api = new Molla_Importer_API( $demo );

			$importer_api->delete_temp_dir();
			die();
		}

		function reset_menus() {
			if ( ! check_ajax_referer( 'molla_setup_wizard_nonce', 'wpnonce' ) ) {
				die();
			}
			if ( current_user_can( 'manage_options' ) ) {
				$import_shortcodes = ( isset( $_POST['import_shortcodes'] ) && 'true' == $_POST['import_shortcodes'] ) ? true : false;
				if ( $import_shortcodes ) {
					$menus = array( 'Main Menu', 'Secondary Menu', 'Top Navigation', 'Currency Switcher', 'Language Switcher', 'Footer Nav 1', 'Footer Nav 2', 'Footer Nav 3' );
				} else {
					$menus = array( 'Main Menu', 'Secondary Menu', 'Top Navigation', 'Currency Switcher', 'Language Switcher', 'Footer Nav 1', 'Footer Nav 2', 'Footer Nav 3' );
				}

				foreach ( $menus as $menu ) {
					wp_delete_nav_menu( $menu );
				}
				esc_html_e( 'Successfully reset menus!', 'molla' );
			}
			die;
		}

		function reset_widgets() {
			if ( ! check_ajax_referer( 'molla_setup_wizard_nonce', 'wpnonce' ) ) {
				die();
			}
			if ( current_user_can( 'manage_options' ) ) {
				ob_start();
				$sidebars_widgets = retrieve_widgets();
				foreach ( $sidebars_widgets as $area => $widgets ) {
					foreach ( $widgets as $key => $widget_id ) {
						$pieces       = explode( '-', $widget_id );
						$multi_number = array_pop( $pieces );
						$id_base      = implode( '-', $pieces );
						$widget       = get_option( 'widget_' . $id_base );
						unset( $widget[ $multi_number ] );
						update_option( 'widget_' . $id_base, $widget );
						unset( $sidebars_widgets[ $area ][ $key ] );
					}
				}

				update_option( 'sidebars_widgets', $sidebars_widgets );
				ob_clean();
				ob_end_clean();
				esc_html_e( 'Successfully reset widgets!', 'molla' );
			}
			die;
		}

		function import_dummy() {
			if ( ! check_ajax_referer( 'molla_setup_wizard_nonce', 'wpnonce', false ) ) {
				die();
			}
			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				define( 'WP_LOAD_IMPORTERS', true ); // we are loading importers
			}
			if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
				require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			}
			if ( ! class_exists( 'WP_Import' ) ) { // if WP importer doesn't exist
				require_once MOLLA_PLUGINS . '/importer/wordpress-importer.php';
			}

			if ( current_user_can( 'manage_options' ) && class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) { // check for main import class and wp import class

				$demo                        = ( isset( $_POST['demo'] ) && $_POST['demo'] ) ? sanitize_text_field( $_POST['demo'] ) : 'landing';
				$process                     = ( isset( $_POST['process'] ) && $_POST['process'] ) ? sanitize_text_field( $_POST['process'] ) : 'import_start';
				$demo_path                   = $this->get_demo_file();
				$importer                    = new WP_Import();
				$theme_xml                   = $demo_path . '/content.xml';
				$importer->fetch_attachments = true;

				$this->import_before_functions( $demo );

				// ob_start();
				$response = $importer->import( $theme_xml, $process );
				// ob_end_clean();
				if ( 'import_start' == $process && $response ) {
					echo json_encode(
						array(
							'process' => 'importing',
							'count'   => 0,
							'index'   => 0,
							'message' => esc_html__(
								'Importing posts',
								'molla'
							),
						)
					);
				} else {
					$this->import_after_functions( $demo );
				}
			}
			die();
		}

		function import_override_contents( $post_exists, $post ) {
			$override_contents = ( isset( $_POST['override_contents'] ) && 'true' == $_POST['override_contents'] ) ? true : false;
			if ( ! $override_contents || ( $post_exists && get_post_type( $post_exists ) != 'revision' ) ) {
				return $post_exists;
			}

			// remove posts which have same ID
			$processed_duplicates = get_option( 'molla_import_processed_duplicates', array() );
			if ( in_array( $post['post_id'], $processed_duplicates ) ) {
				return false;
			}
			$old_post = get_post( $post['post_id'] );
			if ( $old_post ) {
				if ( $old_post->post_type == $post['post_type'] && ( 'page' == $post['post_type'] || 'block' == $post['post_type'] || 'member' == $post['post_type'] || 'portfolio' == $post['post_type'] || 'event' == $post['post_type'] || 'post' == $post['post_type'] || 'product' == $post['post_type'] ) ) {
					return $post['post_id'];
				}

				if ( defined( 'ELEMENTOR_VERSION' ) && 'kit' == get_post_meta( $post['post_id'], '_elementor_template_type', true ) ) {
					$_GET['force_delete_kit'] = true;
				}
				wp_delete_post( $post['post_id'], true );
				if ( isset( $_GET['force_delete_kit'] ) ) {
					unset( $_GET['force_delete_kit'] );
				}
			}

			// remove posts which have same title and slug
			global $wpdb;

			$post_title = wp_unslash( sanitize_post_field( 'post_title', $post['post_title'], 0, 'db' ) );
			$post_name  = wp_unslash( sanitize_post_field( 'post_name', $post['post_name'], 0, 'db' ) );

			$query  = "SELECT ID FROM $wpdb->posts WHERE 1=1";
			$args   = array();
			$query .= ' AND post_title = %s';
			$args[] = $post_title;
			$query .= ' AND post_name = %s';
			$args[] = $post_name;

			$old_post = (int) $wpdb->get_var( $wpdb->prepare( $query, $args ) );

			if ( $old_post && get_post_type( $old_post ) == $post['post_type'] ) {
				if ( 'page' == $post['post_type'] || 'block' == $post['post_type'] || 'member' == $post['post_type'] || 'portfolio' == $post['post_type'] || 'event' == $post['post_type'] || 'post' == $post['post_type'] || 'product' == $post['post_type'] ) {
					$processed_duplicates[] = $old_post;
					update_option( 'molla_import_processed_duplicates', $processed_duplicates );
					return $old_post;
				}

				if ( defined( 'ELEMENTOR_VERSION' ) && 'kit' == get_post_meta( $old_post, '_elementor_template_type', true ) ) {
					$_GET['force_delete_kit'] = true;
				}
				wp_delete_post( $old_post, true );
				if ( isset( $_GET['force_delete_kit'] ) ) {
					unset( $_GET['force_delete_kit'] );
				}
			}

			return false;
		}

		function import_dummy_start() {
			$process = ( isset( $_POST['process'] ) && $_POST['process'] ) ? sanitize_text_field( $_POST['process'] ) : 'import_start';
			if ( current_user_can( 'manage_options' ) && 'import_start' == $process ) {
				delete_option( 'molla_import_processed_duplicates' );
			}

			if ( class_exists( 'WC_Comments' ) ) {
				remove_action( 'wp_update_comment_count', array( 'WC_Comments', 'clear_transients' ) );
			}
		}

		function import_dummy_end() {
			if ( current_user_can( 'manage_options' ) && isset( $_POST['action'] ) && 'molla_import_dummy' === $_POST['action'] ) {
				ob_end_clean();
				ob_start();
				echo json_encode(
					array(
						'process' => 'complete',
						'message' => esc_html__(
							'Imported posts',
							'molla'
						),
					)
				);
				ob_end_flush();
				ob_start();
			}

			if ( class_exists( 'WC_Comments' ) ) {
				add_action( 'wp_update_comment_count', array( 'WC_Comments', 'clear_transients' ) );
			}
		}

		function import_dummy_step_by_step() {
			if ( ! check_ajax_referer( 'molla_setup_wizard_nonce', 'wpnonce' ) ) {
				die();
			}
			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				define( 'WP_LOAD_IMPORTERS', true ); // we are loading importers
			}

			if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
				$wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
				include $wp_importer;
			}

			if ( ! class_exists( 'Molla_WP_Import' ) ) { // if WP importer doesn't exist
				$wp_import = MOLLA_PLUGINS . '/importer/molla-wordpress-importer.php';
				include $wp_import;
			}

			if ( current_user_can( 'manage_options' ) && class_exists( 'WP_Importer' ) && class_exists( 'Molla_WP_Import' ) ) { // check for main import class and wp import class

				$process   = ( isset( $_POST['process'] ) && $_POST['process'] ) ? sanitize_text_field( $_POST['process'] ) : 'import_start';
				$demo      = ( isset( $_POST['demo'] ) && $_POST['demo'] ) ? sanitize_text_field( $_POST['demo'] ) : 'landing';
				$index     = ( isset( $_POST['index'] ) && $_POST['index'] ) ? (int) $_POST['index'] : 0;
				$demo_path = $this->get_demo_file();

				$importer                    = new Molla_WP_Import();
				$theme_xml                   = $demo_path . '/content.xml';
				$importer->fetch_attachments = true;

				if ( 'import_start' == $process ) {
					$this->import_before_functions( $demo );
				}

				$loop = (int) ( ini_get( 'max_execution_time' ) / 60 );
				if ( $loop < 1 ) {
					$loop = 1;
				}
				if ( $loop > 10 ) {
					$loop = 10;
				}
				$i = 0;
				while ( $i < $loop ) {
					$response = $importer->import( $theme_xml, $process, $index );
					if ( isset( $response['count'] ) && isset( $response['index'] ) && $response['count'] && $response['index'] && $response['index'] < $response['count'] ) {
						$i++;
						$index = $response['index'];
					} else {
						break;
					}
				}

				echo json_encode( $response );
				ob_start();
				if ( 'complete' == $response['process'] ) {
					$this->import_after_functions( $demo );
				}
				ob_end_clean();
			}
			die();
		}

		function import_widgets() {
			if ( ! check_ajax_referer( 'molla_setup_wizard_nonce', 'wpnonce' ) ) {
				die();
			}
			if ( current_user_can( 'manage_options' ) ) {
				// Import widgets
				$demo_path   = $this->get_demo_file();
				$widget_data = $this->get_file_data( $demo_path . '/widget_data.json' );
				$this->import_widget_data( $widget_data );
				esc_html_e( 'Successfully imported widgets!', 'molla' );
				flush_rewrite_rules();
			}
			die();
		}

		function import_options() {
			if ( ! check_ajax_referer( 'molla_setup_wizard_nonce', 'wpnonce' ) ) {
				die();
			}
			if ( current_user_can( 'manage_options' ) ) {
				$demo_path = $this->get_demo_file();
				ob_start();
				include $demo_path . '/theme_options.php';
				$theme_options = ob_get_clean();

				ob_start();
				$theme_options = str_replace( 'IMPORT_SITE_URL', get_home_url(), $theme_options );
				$options       = json_decode( $theme_options, true );
				ob_clean();
				ob_end_clean();
				try {
					molla_import_theme_options( false, $options );
					esc_html_e( 'Successfully imported theme options!', 'molla' );
				} catch ( Exception $e ) {
					esc_html_e( 'Successfully imported theme options! Please compile default css files by publishing options in customize panel.', 'molla' );
				}
			}
			die();
		}

		// Parsing Widgets Function
		// Reference: http://wordpress.org/plugins/widget-settings-importexport/
		private function import_widget_data( $widget_data ) {
			$json_data = $widget_data;
			$json_data = json_decode( $json_data, true );

			$sidebar_data = $json_data[0];
			$widget_data  = $json_data[1];

			foreach ( $widget_data as $widget_data_title => $widget_data_value ) {
				$widgets[ $widget_data_title ] = array();
				foreach ( $widget_data_value as $widget_data_key => $widget_data_array ) {
					if ( is_int( $widget_data_key ) ) {
						$widgets[ $widget_data_title ][ $widget_data_key ] = 'on';
					}
				}
			}
			unset( $widgets[''] );

			foreach ( $sidebar_data as $title => $sidebar ) {
				$count = count( $sidebar );
				for ( $i = 0; $i < $count; $i++ ) {
					$widget               = array();
					$widget['type']       = trim( substr( $sidebar[ $i ], 0, strrpos( $sidebar[ $i ], '-' ) ) );
					$widget['type-index'] = trim( substr( $sidebar[ $i ], strrpos( $sidebar[ $i ], '-' ) + 1 ) );
					if ( ! isset( $widgets[ $widget['type'] ][ $widget['type-index'] ] ) ) {
						unset( $sidebar_data[ $title ][ $i ] );
					}
				}
				$sidebar_data[ $title ] = array_values( $sidebar_data[ $title ] );
			}

			foreach ( $widgets as $widget_title => $widget_value ) {
				foreach ( $widget_value as $widget_key => $widget_value ) {
					$widgets[ $widget_title ][ $widget_key ] = $widget_data[ $widget_title ][ $widget_key ];
				}
			}

			$sidebar_data = array( array_filter( $sidebar_data ), $widgets );
			$this->parse_import_data( $sidebar_data );
		}
		private function parse_import_data( $import_array ) {
			global $wp_registered_sidebars;
			$sidebars_data    = $import_array[0];
			$widget_data      = $import_array[1];
			$current_sidebars = get_option( 'sidebars_widgets' );
			$new_widgets      = array();

			foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :

				foreach ( $import_widgets as $import_widget ) :
					// if the sidebar exists
					if ( isset( $wp_registered_sidebars[ $import_sidebar ] ) ) :
						$title               = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
						$index               = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
						$current_widget_data = get_option( 'widget_' . $title );
						$new_widget_name     = $this->get_new_widget_name( $title, $index );
						$new_index           = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

						if ( ! empty( $new_widgets[ $title ] ) && is_array( $new_widgets[ $title ] ) ) {
							while ( array_key_exists( $new_index, $new_widgets[ $title ] ) ) {
								$new_index++;
							}
						}
						$current_sidebars[ $import_sidebar ][] = $title . '-' . $new_index;
						if ( array_key_exists( $title, $new_widgets ) ) {
							$new_widgets[ $title ][ $new_index ] = $widget_data[ $title ][ $index ];
							$multiwidget                         = $new_widgets[ $title ]['_multiwidget'];
							unset( $new_widgets[ $title ]['_multiwidget'] );
							$new_widgets[ $title ]['_multiwidget'] = $multiwidget;
						} else {
							$current_widget_data[ $new_index ] = $widget_data[ $title ][ $index ];
							$current_multiwidget               = ( isset( $current_widget_data['_multiwidget'] ) ) ? $current_widget_data['_multiwidget'] : '';
							$new_multiwidget                   = isset( $widget_data[ $title ]['_multiwidget'] ) ? $widget_data[ $title ]['_multiwidget'] : false;
							$multiwidget                       = ( $current_multiwidget != $new_multiwidget ) ? $current_multiwidget : 1;
							unset( $current_widget_data['_multiwidget'] );
							$current_widget_data['_multiwidget'] = $multiwidget;
							$new_widgets[ $title ]               = $current_widget_data;
						}

					endif;
				endforeach;
			endforeach;

			if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
				update_option( 'sidebars_widgets', $current_sidebars );

				foreach ( $new_widgets as $title => $content ) {
					update_option( 'widget_' . $title, $content );
				}

				return true;
			}

			return false;
		}
		private function get_new_widget_name( $widget_name, $widget_index ) {
			$current_sidebars = get_option( 'sidebars_widgets' );
			$all_widget_array = array();
			foreach ( $current_sidebars as $sidebar => $widgets ) {
				if ( ! empty( $widgets ) && is_array( $widgets ) && 'wp_inactive_widgets' != $sidebar ) {
					foreach ( $widgets as $widget ) {
						$all_widget_array[] = $widget;
					}
				}
			}
			while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
				$widget_index++;
			}
			$new_widget_name = $widget_name . '-' . $widget_index;
			return $new_widget_name;
		}
		private function importer_get_page_by_title( $page_title, $output = OBJECT ) {
			global $wpdb;
			$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id and $wpdb->postmeta.meta_key = %s ) WHERE $wpdb->posts.post_title = %s AND $wpdb->posts.post_type = %s order by $wpdb->postmeta.meta_value desc limit 1", 'molla_imported_date', $page_title, 'page' ) );

			if ( $page ) {
				return get_post( $page, $output );
			}
		}
		private function import_before_functions( $demo ) {

			// update woocommerce image sizes
			$post_grid = array(
				'width'  => '400',   // px
				'height' => '267',   // px
				'crop'   => 1,       // true
			);

			// Image sizes
			add_image_size( 'molla_post-grid', $post_grid['width'], $post_grid['height'], $post_grid['crop'] );

		}

		private function import_after_functions( $demo ) {
			delete_option( 'molla_import_processed_duplicates' );

			$woopages = array(
				'woocommerce_shop_page_id'      => 'Shop',
				'woocommerce_cart_page_id'      => 'Cart',
				'woocommerce_checkout_page_id'  => 'Checkout',
				'woocommerce_myaccount_page_id' => 'My Account',
			);

			foreach ( $woopages as $woo_page_name => $woo_page_title ) {
				$woopage = get_page_by_title( $woo_page_title );
				if ( isset( $woopage ) && $woopage->ID ) {
					update_option( $woo_page_name, $woopage->ID ); // Front Page
				}
			}

			// We no longer need to install pages
			$notices = array_diff( get_option( 'woocommerce_admin_notices', array() ), array( 'install', 'update' ) );
			update_option( 'woocommerce_admin_notices', $notices );
			delete_option( '_wc_needs_pages' );
			delete_transient( '_wc_activation_redirect' );

			// Set imported menus to registered theme locations
			$locations = get_theme_mod( 'nav_menu_locations' ); // registered menu locations in theme
			$menus     = wp_get_nav_menus(); // registered menus

			if ( $menus ) {
				foreach ( $menus as $menu ) { // assign menus to theme locations
					if ( 'Main Menu' == $menu->name ) {
						$locations['main_menu'] = $menu->term_id;
					} elseif ( 'Secondary Menu' == $menu->name ) {
						$locations['secondary_menu'] = $menu->term_id;
					} elseif ( 'Top Navigation' == $menu->name ) {
						$locations['top_nav'] = $menu->term_id;
					} elseif ( 'Language Switcher' == $menu->name ) {
						$locations['lang_switcher'] = $menu->term_id;
					} elseif ( 'Currency Switcher' == $menu->name ) {
						$locations['currency_switcher'] = $menu->term_id;
					}
				}
			}

			set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations

			// Set reading options
			$homepage   = $this->importer_get_page_by_title( 'Home' );
			$posts_page = $this->importer_get_page_by_title( 'Blog' );

			if ( ( $homepage && $homepage->ID ) || ( $posts_page && $posts_page->ID ) ) {
				update_option( 'show_on_front', 'page' );
				if ( $homepage && $homepage->ID ) {
					update_option( 'page_on_front', $homepage->ID ); // Front Page
				}
				if ( $posts_page && $posts_page->ID ) {
					update_option( 'page_for_posts', $posts_page->ID ); // Blog Page
				}
			}
			update_option( 'elementor_disable_color_schemes', true );
			update_option( 'elementor_disable_typography_schemes', true );
			update_option( '_elementor_settings_update_time', time() );
			update_option( 'permalink_structure', '/%year%/%monthnum%/%day%/%postname%/' );

			do_action( 'molla_demo_imported' );
			flush_rewrite_rules();
		}

		// Demos
		public function molla_demo_filters() {
			return array(
				'all'         => esc_html__( 'Show All', 'molla' ),
				'fashion'     => esc_html__( 'Fashion', 'molla' ),
				'furniture'   => esc_html__( 'Furniture', 'molla' ),
				'electronics' => esc_html__( 'Electronics', 'molla' ),
				'sports'      => esc_html__( 'Sports', 'molla' ),
				'medical'     => esc_html__( 'Medical', 'molla' ),
				'food'        => esc_html__( 'Food', 'molla' ),
				'market'      => esc_html__( 'Market', 'molla' ),
				'other'       => esc_html__( 'Other', 'molla' ),
				'rtl'         => esc_html__( 'RTL', 'molla' ),
			);
		}

		public function molla_demo_types() {
			return array(
				'demo-1'   => array(
					'alt'     => 'Demo 1',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/1.jpg',
					'filter'  => 'all elementor furniture',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-2'   => array(
					'alt'     => 'Demo 2',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/2.jpg',
					'filter'  => 'all elementor furniture',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-3'   => array(
					'alt'     => 'Demo 3',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/3.jpg',
					'filter'  => 'all elementor electronics market',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-4'   => array(
					'alt'     => 'Demo 4',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/4.jpg',
					'filter'  => 'all elementor electronics market',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-5'   => array(
					'alt'     => 'Demo 5',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/5.jpg',
					'filter'  => 'all elementor fashion',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-6'   => array(
					'alt'     => 'Demo 6',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/6.jpg',
					'filter'  => 'all elementor fashion',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-7'   => array(
					'alt'     => 'Demo 7',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/7.jpg',
					'filter'  => 'all elementor fashion',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-8'   => array(
					'alt'     => 'Demo 8',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/8.jpg',
					'filter'  => 'all elementor fashion',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-9'   => array(
					'alt'     => 'Demo 9',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/9.jpg',
					'filter'  => 'all elementor fashion',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-10'  => array(
					'alt'     => 'Demo 10',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/10.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-11'  => array(
					'alt'     => 'Demo 11',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/11.jpg',
					'filter'  => 'all elementor furniture',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-12'  => array(
					'alt'     => 'Demo 12',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/12.jpg',
					'filter'  => 'all elementor fashion',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-13'  => array(
					'alt'     => 'Demo 13',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/13.jpg',
					'filter'  => 'all elementor electronics market',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-14'  => array(
					'alt'     => 'Demo 14',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/14.jpg',
					'filter'  => 'all elementor electronics market',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-15'  => array(
					'alt'     => 'Demo 15',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/15.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-16'  => array(
					'alt'     => 'Demo 16',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/16.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-17'  => array(
					'alt'     => 'Demo 17',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/17.jpg',
					'filter'  => 'all elementor fashion',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-18'  => array(
					'alt'     => 'Demo 18',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/18.jpg',
					'filter'  => 'all elementor fashion',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-19'  => array(
					'alt'     => 'Demo 19',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/19.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-20'  => array(
					'alt'     => 'Demo 20',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/20.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-21'  => array(
					'alt'     => 'Demo 21',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/21.jpg',
					'filter'  => 'all elementor sports',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-22'  => array(
					'alt'     => 'Demo 22',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/22.jpg',
					'filter'  => 'all elementor market',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-23'  => array(
					'alt'     => 'Demo 23',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/23.jpg',
					'filter'  => 'all elementor fashion',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-24'  => array(
					'alt'     => 'Demo 24',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/24.jpg',
					'filter'  => 'all elementor sports',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-25'  => array(
					'alt'     => 'Demo 25',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/25.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-26'  => array(
					'alt'     => 'Demo 26',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/26.jpg',
					'filter'  => 'all elementor market food',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-27'  => array(
					'alt'     => 'Demo 27',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/27.jpg',
					'filter'  => 'all elementor fashion',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-28'  => array(
					'alt'     => 'Demo 28',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/28.jpg',
					'filter'  => 'all elementor market food',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-29'  => array(
					'alt'     => 'Demo 29',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/29.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-30'  => array(
					'alt'     => 'Demo 30',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/30.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-31'  => array(
					'alt'     => 'Demo 31',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/31.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-32'  => array(
					'alt'     => 'Demo 32',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/32.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-33'  => array(
					'alt'     => 'Demo 33',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/33.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-34'  => array(
					'alt'     => 'Demo 34',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/34.jpg',
					'filter'  => 'all elementor market',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-35'  => array(
					'alt'     => 'Demo 35',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/35.jpg',
					'filter'  => 'all elementor other',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-36'  => array(
					'alt'     => 'Demo 36',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/36.jpg',
					'filter'  => 'all elementor sports',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-37'  => array(
					'alt'     => 'Demo 37',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/37.jpg',
					'filter'  => 'all elementor medical',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-38'  => array(
					'alt'     => 'Demo 38',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/38.jpg',
					'filter'  => 'all elementor medical',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-39'  => array(
					'alt'     => 'Demo 39',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/39.jpg',
					'filter'  => 'all elementor food',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-40'  => array(
					'alt'     => 'Demo 40',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/40.jpg',
					'filter'  => 'all elementor market food',
					'plugins' => array( 'woocommerce' ),
				),
				'demo-rtl' => array(
					'alt'     => 'Demo 1 RTL',
					'img'     => MOLLA_URI . '/assets/images/setup_wizard/demos/rtl.jpg',
					'filter'  => 'all elementor furniture rtl',
					'plugins' => array( 'woocommerce' ),
				),
			);
		}
	}
endif;

add_action( 'after_setup_theme', 'molla_theme_setup_wizard', 10 );

if ( ! function_exists( 'molla_theme_setup_wizard' ) ) :
	function molla_theme_setup_wizard() {
		$instance = Molla_Setup_Wizard::get_instance();
	}
endif;
