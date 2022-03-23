<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MOLLA_SPEED_OPTIMIZE_WIZARD', MOLLA_LIB . '/admin/wizard/speed_optimize_wizard' ); // setup wizard directory

if ( ! class_exists( 'Molla_Speed_Optimize_Wizard' ) ) :
	/**
	* Molla Theme Speed Wizard
	*/
	class Molla_Speed_Optimize_Wizard {

		protected $version = '1.0';

		protected $theme_name = '';

		protected $step = '';

		protected $steps = array();

		public $page_slug;

		protected $tgmpa_instance;

		protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

		protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';

		protected $page_url;

		private static $instance = null;

		protected $files;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			$this->current_theme_meta();
			$this->init_actions();
		}

		public function current_theme_meta() {
			$current_theme    = wp_get_theme();
			$this->theme_name = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
			$this->page_slug  = 'molla-speed-optimize-wizard';
			$this->page_url   = 'admin.php?page=' . $this->page_slug;
		}

		public function init_actions() {
			add_action( 'upgrader_post_install', array( $this, 'upgrader_post_install' ), 10, 2 );

			if ( apply_filters( $this->theme_name . '_enable_speed_optimize_wizard', false ) ) {
				return;
			}

			if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
				add_action( 'init', array( $this, 'get_tgmpa_instanse' ), 30 );
				add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
			}

			add_action( 'admin_menu', array( $this, 'admin_menus' ) );
			add_action( 'wp_ajax_molla_speed_wizard_remove_widgets', array( $this, 'remove_widgets' ) );

			if ( isset( $_GET['page'] ) && $this->page_slug === $_GET['page'] ) {
				add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
				add_action( 'admin_init', array( $this, 'init_wizard_steps' ), 30 );
				add_action( 'admin_init', array( $this, 'speed_wizard' ), 30 );
			}
		}

		public function upgrader_post_install( $return, $theme ) {
			if ( is_wp_error( $return ) ) {
				return $return;
			}
			if ( get_stylesheet() != $theme ) {
				return $return;
			}
			update_option( 'molla_speed_complete', false );

			return $return;
		}

		public function admin_menus() {
			add_submenu_page( 'molla', esc_html__( 'Speed Optimize Wizard', 'molla' ), esc_html__( 'Speed Optimize Wizard', 'molla' ), 'manage_options', $this->page_slug, array( $this, 'view_speed_wizard' ) );
		}

		public function admin_redirects() {
			ob_start();

			if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) || get_option( 'molla_speed_complete', false ) ) {
				return;
			}
			delete_transient( '_' . $this->theme_name . '_activation_redirect' );
			wp_safe_redirect( admin_url( $this->page_url ) );
			exit;
		}

		/**
		 * Display speed optimize wizard
		 */
		public function speed_wizard() {

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
			wp_register_script( 'jquery-blockUI', MOLLA_JS . '/plugins/jquery.blockUI.min.js', false, true );
			wp_enqueue_script( 'molla-setup-wizard', MOLLA_JS . '/admin/setup-wizard.min.js', array( 'jquery', 'jquery-blockUI' ), true );
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

			ob_start();

		}

		public function view_speed_wizard() {
			$this->speed_wizard_header();
			$this->speed_wizard_steps();
			$this->speed_wizard_contents();
			$this->speed_wizard_footer();
		}

		public function speed_wizard_header() {
			include MOLLA_SPEED_OPTIMIZE_WIZARD . '/views/header.php';
		}

		/**
		 * Output setup wizard step links
		 */
		public function speed_wizard_steps() {
			include MOLLA_SPEED_OPTIMIZE_WIZARD . '/views/speed-step.php';
		}

		/**
		 * Output setup wizard contents
		 */
		public function speed_wizard_contents() {
			$show_content = true;
			if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
				$show_content = call_user_func( $this->steps[ $this->step ]['handler'] );
			}
			if ( $show_content ) {
				$this->speed_wizard_step_content();
			}
		}

		/**
		 * Output the content for the current step
		 */
		public function speed_wizard_step_content() {
			isset( $this->steps[ $this->step ] ) ? call_user_func( $this->steps[ $this->step ]['view'] ) : false;
		}

		public function speed_wizard_footer() {
			include MOLLA_SPEED_OPTIMIZE_WIZARD . '/views/footer.php';
		}

		public function get_tgmpa_instanse() {
			$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		}

		public function set_tgmpa_url() {

			$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
			$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_speed_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );

			$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && 'themes.php' !== $this->tgmpa_instance->parent_slug ) ? 'admin.php' : 'themes.php';

			$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_speed_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );
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
				if ( ! isset( $plugin['visibility'] ) || 'speed_wizard' != $plugin['visibility'] || $instance->$plugin_func_name( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
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

		public function init_wizard_steps() {
			$this->steps = array(
				'introduction' => array(
					'step_id' => 1,
					'name'    => esc_html__( 'Welcome', 'molla' ),
					'view'    => array( $this, 'speed_wizard_welcome' ),
					'handler' => '',
				),
			);

			$this->steps['widgets'] = array(
				'step_id' => 2,
				'name'    => esc_html__( 'Widgets', 'molla' ),
				'view'    => array( $this, 'speed_wizard_widgets' ),
				'handler' => '',
			);

			$this->steps['lazylaod'] = array(
				'step_id' => 3,
				'name'    => esc_html__( 'Lazyload', 'molla' ),
				'view'    => array( $this, 'speed_wizard_lazyload' ),
				'handler' => array( $this, 'speed_wizard_lazyload_save' ),
			);

			$this->steps['minify'] = array(
				'step_id' => 4,
				'name'    => esc_html__( 'Minify', 'molla' ),
				'view'    => array( $this, 'speed_wizard_minify' ),
				'handler' => array( $this, 'speed_wizard_minify_save' ),
			);

			if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
				$this->steps['plugins'] = array(
					'step_id' => 5,
					'name'    => esc_html__( 'Plugins', 'molla' ),
					'view'    => array( $this, 'speed_wizard_plugins' ),
					'handler' => '',
				);
			};

			$this->steps['next_steps'] = array(
				'step_id' => 6,
				'name'    => esc_html__( 'Ready!', 'molla' ),
				'view'    => array( $this, 'speed_wizard_ready' ),
				'handler' => '',
			);

			$this->steps = apply_filters( $this->theme_name . '_theme_speed_wizard_steps', $this->steps );
		}

		// View for each step content
		public function speed_wizard_welcome() {
			include MOLLA_SPEED_OPTIMIZE_WIZARD . '/views/steps/welcome.php';
		}

		public function speed_wizard_widgets() {
			include MOLLA_SPEED_OPTIMIZE_WIZARD . '/views/steps/widgets.php';
		}

		public function speed_wizard_lazyload() {
			include MOLLA_SPEED_OPTIMIZE_WIZARD . '/views/steps/lazyload.php';
		}

		public function speed_wizard_lazyload_save() {
			check_admin_referer( 'molla-setup-wizard' );

			if ( isset( $_POST['lazyload'] ) ) {
				set_theme_mod( 'lazy_load_img', 'true' === $_POST['lazyload'] );
			}
			if ( isset( $_POST['skeleton'] ) ) {
				set_theme_mod( 'skeleton_screen', 'true' === $_POST['skeleton'] );
			}
			if ( isset( $_POST['webfont'] ) ) {
				set_theme_mod( 'google_webfont', 'true' === $_POST['webfont'] );
			}

			wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
			die();
		}

		public function speed_wizard_minify() {
			include MOLLA_SPEED_OPTIMIZE_WIZARD . '/views/steps/minify.php';
		}

		public function speed_wizard_minify_save() {
			check_admin_referer( 'molla-setup-wizard' );

			// Minify all css files generated by Molla Theme
			if ( isset( $_POST['css_js'] ) ) {
				if ( get_theme_mod( 'minify_css_js' ) != ( 'true' === $_POST['css_js'] ) ) {

				}

				set_theme_mod( 'minify_css_js', 'true' === $_POST['css_js'] );
				molla_compile_dynamic_css();
			}

			if ( isset( $_POST['font_icons'] ) ) {
				set_theme_mod( 'minify_font_icons', 'true' === $_POST['font_icons'] );
			}

			wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
			die();
		}

		public function speed_wizard_plugins() {
			include MOLLA_SPEED_OPTIMIZE_WIZARD . '/views/steps/plugins.php';
		}

		public function speed_wizard_ready() {
			include MOLLA_SPEED_OPTIMIZE_WIZARD . '/views/steps/ready.php';
		}

		private function _get_widgets() {
			return array(
				'banners'          => 'banner',
				'slider'           => 'slider',
				'cards'            => 'accordion',
				'tabs'             => 'tab',
				'titles'           => 'heading',
				'blog'             => 'blog',
				'countdown'        => 'countdown',
				'counters'         => 'count_to',
				'products'         => 'products',
				'product-category' => 'product category',
				'member'           => 'member',
				'testimonials'     => 'testimonial',
			);
		}

		private function _get_used_blocks( $widgets, $results ) {
			// Search blocks in page content
			foreach ( $widgets as $widget ) {
				$setting = $widget['settings'];
				if ( 'widget' == $widget['elType'] && 'molla_modal' == $widget['widgetType'] && $setting['lightbox_block_name'] ) {
					$results[] = molla_get_post_id_by_name( 'block', sanitize_text_field( $setting['lightbox_block_name'] ) );
				}
				if ( 'widget' == $widget['elType'] && 'molla_block' == $widget['widgetType'] && $setting['name'] ) {
					$results[] = molla_get_post_id_by_name( 'block', sanitize_text_field( $setting['name'] ) );
				}
				if ( is_array( $widget['elements'] ) ) {
					$results = $this->_get_used_blocks( $widget['elements'], $results );
				}
			}

			return $results;
		}

		private function _get_used_space_grid( $widgets, $results, $builder ) {
			foreach ( $widgets as $widget ) {
				if ( 'elementor' == $builder ) {
					$setting = $widget['settings'];
					if ( isset( $setting['spacing'] ) && ! in_array( $setting['spacing']['size'], $results['spacing'] ) ) { // spacing
						$results['spacing'][] = $setting['spacing']['size'];
					}

					$css_classes = '';
					if ( isset( $setting['css_classes'] ) ) {
						$css_classes .= $setting['css_classes'] . ' ';
					}
					if ( isset( $setting['_css_classes'] ) ) {
						$css_classes .= $setting['_css_classes'] . ' ';
					}
					if ( isset( $setting['banner_item_list'] ) ) {
						foreach( $setting['banner_item_list'] as $banner_item ) {
							if( isset( $banner_item['banner_item_aclass'] ) ) {
								$css_classes .= $banner_item['banner_item_aclass'] . ' ';
							}
						}
					}
					if ( 'shortcode' == $widget['widgetType'] ) {
						$shortcode = $setting['shortcode'];
						ob_start();
						echo do_shortcode( $shortcode );
						$shortcode    = ob_get_clean();
						$css_classes .= $shortcode;
					}

					if ( $css_classes ) { // margin, padding, col-*
						preg_match_all( '/(mt-|mr-|mb-|ml-|pt-|pr-|pb-|pl-)(|sm-|md-|lg-|xl-)(1?[0-9])/', $css_classes, $margin, PREG_SET_ORDER );

						if ( ! empty( $margin ) ) {
							foreach ( $margin as $mp ) {
								$key = str_replace( array( "'", '"', ' ' ), '', $mp[0] );
								$mp  = str_replace( array( "'", '"', ' ', '-' ), '', $mp );

								if ( ! in_array( $key, array_keys( $results['margin'][ $mp[2] ] ) ) ) {
									$results['margin'][ $mp[2] ][ $key ] = $mp[3];
								}
							}
						}

						preg_match_all( '/col-(|xs-|sm-|md-|lg-|xl-)(auto|10|11|12|[0-9])/', $css_classes, $cols, PREG_SET_ORDER );

						if ( ! empty( $cols ) ) {
							foreach ( $cols as $c ) {
								if ( $c[1] ) {
									$c[1] = substr( $c[1], 0, -1 );
								}

								if ( ! in_array( $c[2], array_keys( $results['cols'][ $c[1] ] ) ) ) {
									$results['cols'][ $c[1] ][] = $c[2];
								}
							}
						}
					}

					$columns = array();
					// grid classes like c-xs-*, c-*, ...
					if ( ( 'section' == $widget['elType'] && isset( $setting['section_layout_mode'] ) && ( 'slider' == $setting['section_layout_mode'] || 'creative' == $setting['section_layout_mode'] ) ) ||
						( 'column' == $widget['elType'] && isset( $setting['column_type'] ) && 'slider' == $setting['column_type'] ) ) {
						$columns['xxl'] = isset( $setting['cols'] ) ? $setting['cols'] : 3;
						$columns['xl']  = isset( $setting['cols_upper_desktop'] ) ? $setting['cols_upper_desktop'] : '';
						$columns['md']  = isset( $setting['cols_tablet'] ) ? $setting['cols_tablet'] : '';
						$columns['sm']  = isset( $setting['cols_mobile'] ) ? $setting['cols_mobile'] : '';
						$columns['xs']  = isset( $setting['cols_under_mobile'] ) ? $setting['cols_under_mobile'] : '';
					} elseif ( 'widget' == $widget['elType'] && ( 'molla_product' == $widget['widgetType'] || 'molla_product_category' == $widget['widgetType'] || 'molla_blog' == $widget['widgetType'] ) || 'molla_image_carousel' == $widget['widgetType'] ) {
						$columns['xxl'] = isset( $setting['columns'] ) ? $setting['columns'] : 4;
						$columns['xl']  = isset( $setting['cols_upper_desktop'] ) ? $setting['cols_upper_desktop'] : '';
						$columns['md']  = isset( $setting['columns_tablet'] ) ? $setting['columns_tablet'] : '';
						$columns['sm']  = isset( $setting['columns_mobile'] ) ? $setting['columns_mobile'] : '';
						$columns['xs']  = isset( $setting['cols_under_mobile'] ) ? $setting['cols_under_mobile'] : '';
					}
					if ( ! empty( $columns ) ) {
						$grids = ltrim( molla_get_column_class( $columns ) );
						$grids = explode( ' ', rtrim( $grids ) );

						foreach ( $grids as $grid ) {
							$g = explode( '-', $grid );
							if ( ! in_array( $g[2], $results['grid'][ $g[1] ] ) ) {
								$results['grid'][ $g[1] ][] = $g[2];
							}
						}
					}

					if ( is_array( $widget['elements'] ) ) {
						$results = $this->_get_used_space_grid( $widget['elements'], $results, $builder );
					}
				} elseif ( 'gutenberg' == $builder ) {
					$setting = json_decode( substr( $widget[0], stripos( $widget[0], '{' ) ), true );

					if ( isset( $setting['spacing'] ) && ! in_array( $setting['spacing'], $results['spacing'] ) ) { // spacing
						$results['spacing'][] = $setting['spacing'];
					}
					if ( isset( $setting['className'] ) ) { // margin, padding
						preg_match_all( '/(mt-|mr-|mb-|ml-|pt-|pr-|pb-|pl-)(|sm-|md-|lg-|xl-)(1?[0-9])/', $setting['className'], $margin, PREG_SET_ORDER );
						if ( ! empty( $margin ) ) {
							foreach ( $margin as $mp ) {
								$key = str_replace( array( "'", '"', ' ' ), '', $mp[0] );
								$mp  = str_replace( array( "'", '"', ' ', '-' ), '', $mp );

								if ( ! in_array( $key, array_keys( $results['margin'][ $mp[2] ] ) ) ) {
									$results['margin'][ $mp[2] ][ $key ] = $mp[3];
								}
							}
						}

						preg_match_all( '/col-(|xs-|sm-|md-|lg-|xl-)(auto|10|11|12|[0-9])/', $setting['className'], $cols, PREG_SET_ORDER );

						if ( ! empty( $cols ) ) {
							foreach ( $cols as $c ) {
								if ( $c[1] ) {
									$c[1] = substr( $c[1], 0, -1 );
								}

								if ( ! in_array( $c[2], array_keys( $results['cols'][ $c[1] ] ) ) ) {
									$results['cols'][ $c[1] ][] = $c[2];
								}
							}
						}
					}
					if ( isset( $setting['x_pos'] ) && ! in_array( $setting['x_pos'], $results['x_pos'] ) ) { // x_pos
						$results['x_pos'][] = $setting['x_pos'];
					}
					if ( isset( $setting['y_pos'] ) && ! in_array( $setting['y_pos'], $results['y_pos'] ) ) { // y_pos
						$results['y_pos'][] = $setting['y_pos'];
					}
				}
			}

			return $results;
		}

		private function _use_sidebar( $id ) {
			if ( class_exists( 'WooCommerce' ) && wc_get_page_id( 'shop' ) == $id ) { // if it is product archive page, return
				return true;
			}

			// check if page uses sidebar
			$id       = molla_get_page_layout( get_post( $id ) );
			$use      = 'no' != molla_option( 'sidebar_option' );
			$template = get_post_meta( $id, '_wp_page_template', true );
			if ( $template ) {
				if ( false !== strpos( $template, 'sidebar' ) ) {
					$use = true;
				}
			} else {
				$meta_pos = get_post_meta( $id, 'sidebar_pos' );
				if ( count( $meta_pos ) ) {
					if ( 'no' != $meta_pos[0] ) {
						$use = true;
					}
				}
			}

			return is_active_sidebar( 'main-sidebar' ) && $use;
		}

		private function _used_blocks( $id ) {
			global $post;
			$new_post = get_post( $id );
			$post     = $new_post;
			setup_postdata( $new_post );

			$builder_options = json_decode( get_theme_mod( 'builders' ), true );
			$used_blocks     = array();
			$using_builders  = array();

			$result = false;

			// builder blocks
			
			$super = array();
			if ( get_option('show_on_front') ) {
				if ( $id == get_option( 'page_on_front' ) ) {
					if ( isset( $builder_options['single'] ) && isset( $builder_options['single']['front'] ) ) {
						$using_builders = $builder_options['single']['front'];
					}
				}
			} else {
				$using_builders = json_decode( get_post_meta( $id, 'molla_builders', true ), true );
				if ( isset( $using_builders['single'] ) ) {
					$using_builders = $using_builders['single'];
				}

				// Super Conditionals of Singular
				$super      = array();
				$taxonomies = get_post_taxonomies();

				foreach ( $taxonomies as $tax ) {
					$super = array_merge( $super, wp_get_post_terms( $id, $tax, array( 'fields' => 'ids' ) ) );
				}
			}

			$using_builders = molla_get_super_conditional_builders( false, $using_builders, $super, $builder_options );

			foreach( $using_builders as $key => $val ) {
				if ( ! is_array( $val ) ) {
					$used_blocks[] = $val;
				} else {
					if ( 'sidebar' == $key ) {
						$used_blocks[] = $val['sidebar_id'];
					} else {
						$used_blocks[] = $val['popup_id'];
					}
				}
			}
				
			// header blocks
			$used_blocks = array_merge( $used_blocks, get_theme_mod( '_molla_blocks_header_top', array() ) );
			$used_blocks = array_merge( $used_blocks, get_theme_mod( '_molla_blocks_header_main', array() ) );
			$used_blocks = array_merge( $used_blocks, get_theme_mod( '_molla_blocks_header_bottom', array() ) );
				
			// blocks in page-content
			$page_blocks = get_post_meta( $id, '_molla_blocks_page_content' );
			if ( ! empty( $page_blocks ) ) {
				foreach ( $page_blocks[0] as $block ) {
					$used_blocks[] = $block;
				}
			}

			// sidebar
			$sidebar_blocks = get_theme_mod( '_molla_blocks_sidebar', array() );
			if ( ! empty( $sidebar_blocks ) ) {
				foreach ( $sidebar_blocks as $sidebar_id => $block_ids ) {
					if ( ! empty( $block_ids ) && ( 0 === strpos( $sidebar_id, 'footer-' ) ) ) {
						$used_blocks = array_merge( $used_blocks, $block_ids );
					}
				}
				if ( $this->_use_sidebar( $id ) ) {
					$used_blocks = array_merge( $used_blocks, $sidebar_blocks[ 'main-sidebar' ] );
				}
			}

			$used_blocks[] = get_post_meta( $id, 'content_top_block' );
			$used_blocks[] = get_post_meta( $id, 'content_inner_top_block' );
			$used_blocks[] = get_post_meta( $id, 'content_inner_bottom_block' );
			$used_blocks[] = get_post_meta( $id, 'content_bottom_block' );

			if ( defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $id, '_elementor_edit_mode', true ) ) {
				$widgets     = json_decode( get_post_meta( $id, '_elementor_data', true ), true );
				$page_blocks = $this->_get_used_blocks( $widgets, array() );
				$used_blocks = array_merge( $used_blocks, $page_blocks );
			}

			wp_reset_postdata();

			return $used_blocks;
		}

		public function remove_widgets() {
			if ( ! check_ajax_referer( 'molla_setup_wizard_nonce', 'wpnonce' ) || empty( $_POST['id'] ) ) {
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => esc_html__(
							'No Page Found',
							'molla'
						),
					)
				);
			}

			// compile molla theme style file
			$id         = $_POST['id'];
			$is_success = false;
			$sidebar    = $this->_use_sidebar( $id );
			$unused     = $_POST['unused'];
			if ( null === $unused ) {
				$unused = array();
			}

			update_post_meta( $id, 'removed_widgets', json_encode( $unused ) );

			$upload_dir = wp_upload_dir();
			$style_path = $upload_dir['basedir'] . '/molla_styles';
			if ( ! file_exists( $style_path ) ) {
				wp_mkdir_p( $style_path );
			} else {
				if ( file_exists( $style_path . '/page-' . $id . '-style.css' ) ) { // if css file already exists, delete it.
					wp_delete_file( $style_path . '/page-' . $id . '-style.css' );
				}
				if ( file_exists( $style_path . '/page-' . $id . '-style-rtl.css' ) ) { // if css file already exists, delete it.
					wp_delete_file( $style_path . '/page-' . $id . '-style-rtl.css' );
				}
			}

			// filesystem
			global $wp_filesystem;
			// Initialize the WordPress filesystem, no more using file_put_contents function
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}

			if ( ! empty( $unused ) ) {
				if ( ! class_exists( 'scssc' ) ) {
					require_once MOLLA_LIB . '/plugins/scssphp/scss.inc.php';
				}

				ob_start();
				require MOLLA_SPEED_OPTIMIZE_WIZARD . '/config_scss.php';
				$_config_scss = ob_get_clean();

				$scss = new scssc();
				$scss->setImportPaths( MOLLA_DIR . '/assets/sass/frontend' );

				$scss->setFormatter( 'scss_formatter_crunched' );
				//$scss->setFormatter( 'scss_formatter' );

				try {
					$rtl = false;
					$css = $scss->compile( '$rtl: 0; $dir: ltr !default; ' . $_config_scss );
					//$css = $_config_scss;

					// RTL
					$rtl     = true;
					$css_rtl = $scss->compile( '$rtl: 1; $dir: rtl !default; ' . $_config_scss );
					//$css_rtl = $_config_scss;

					$filename     = $style_path . '/page-' . $id . '-style.css';
					$filename_rtl = $style_path . '/page-' . $id . '-style-rtl.css';

					// check file mode and make it writable.
					if ( is_writable( dirname( $filename ) ) == false ) {
						@chmod( dirname( $filename ), 0755 );
					}
					if ( file_exists( $filename ) ) {
						if ( is_writable( $filename ) == false ) {
							@chmod( $filename, 0755 );
						}
						@unlink( $filename );
					}

					$wp_filesystem->put_contents( $filename, molla_minify_css( $css ), FS_CHMOD_FILE );

					// RTL
					if ( is_writable( dirname( $filename_rtl ) ) == false ) {
						@chmod( dirname( $filename_rtl ), 0755 );
					}
					if ( file_exists( $filename_rtl ) ) {
						if ( is_writable( $filename_rtl ) == false ) {
							@chmod( $filename_rtl, 0755 );
						}
						@unlink( $filename_rtl );
					}

					$wp_filesystem->put_contents( $filename_rtl, molla_minify_css( $css_rtl ), FS_CHMOD_FILE );

					$is_success = true;
				} catch ( Exception $e ) {
				}
			} else {
				$is_success = true;

				$filename     = $style_path . '/page-' . $id . '-style.css';
				$filename_rtl = $style_path . '/page-' . $id . '-style-rtl.css';

				$wp_filesystem->delete( $filename, true );
				$wp_filesystem->delete( $filename_rtl, true );
			}

			wp_send_json(
				array(
					'done'    => $is_success,
					'id'      => $id,
					'message' => esc_html__(
						'Success',
						'molla'
					),
				)
			);
			exit;
		}
	}
endif;

add_action( 'after_setup_theme', 'molla_theme_speed_optimize_wizard', 10 );

if ( ! function_exists( 'molla_theme_speed_optimize_wizard' ) ) :
	function molla_theme_speed_optimize_wizard() {
		$instance = Molla_Speed_Optimize_Wizard::get_instance();
	}
endif;