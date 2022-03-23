<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Molla_Admin {

	private $_checkedPurchaseCode;

	private $activation_url = 'https://d-themes.com/wordpress/molla/dummy/api/includes/verify_purchase.php';
	public function __construct() {

		add_action(
			'admin_menu',
			function() {
				require_once MOLLA_ADMIN . '/panel/panel.php';
			}
		);

		if ( is_admin_bar_showing() ) {
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_wp_toolbar_menu' ) );
		}

		add_action( 'admin_menu', array( $this, 'custom_admin_menu_order' ) );
		add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_theme_update_url' ), 1001 );

		if ( is_admin() ) {
			add_filter( 'pre_set_site_transient_update_themes', array( $this, 'pre_set_site_transient_update_themes' ) );
			add_filter( 'upgrader_pre_download', array( $this, 'upgrader_pre_download' ), 10, 3 );
		}
	}

	public function add_wp_toolbar_menu() {
		if ( current_user_can( 'edit_theme_options' ) ) {
			$molla_toolbar_title = '<span class="ab-icon"></span><span class="ab-label">Molla</span>';
			$this->add_wp_toolbar_menu_item( $molla_toolbar_title, false, esc_url( admin_url( 'admin.php?page=molla' ) ), array( 'class' => 'molla-menu' ), 'molla' );
			$this->add_wp_toolbar_menu_item( esc_html__( 'Theme License', 'molla' ), 'molla', esc_url( admin_url( 'admin.php?page=molla' ) ) );
			$this->add_wp_toolbar_menu_item( esc_html__( 'Change Log', 'molla' ), 'molla', esc_url( admin_url( 'admin.php?page=molla-changelog' ) ) );
			$this->add_wp_toolbar_menu_item( esc_html__( 'Theme Options', 'molla' ), 'molla', esc_url( admin_url( 'customize.php' ) ) );
			// add wizard menus
			$this->add_wp_toolbar_menu_item( esc_html__( 'Setup Wizard', 'molla' ), 'molla', esc_url( admin_url( 'admin.php?page=molla-setup-wizard' ) ) );
			$this->add_wp_toolbar_menu_item( esc_html__( 'Speed Optimize Wizard', 'molla' ), 'molla', esc_url( admin_url( 'admin.php?page=molla-speed-optimize-wizard' ) ) );
			if ( class_exists( 'MollaDemoExporter' ) ) {
				$this->add_wp_toolbar_menu_item( esc_html__( 'Export Molla Demo files', 'molla' ), 'molla', esc_url( admin_url( 'admin.php?page=molla-demos' ) ) );
			}
		}
	}

	public function add_wp_toolbar_menu_item( $title, $parent = false, $href = '', $custom_meta = array(), $custom_id = '' ) {
		global $wp_admin_bar;
		if ( current_user_can( 'edit_theme_options' ) ) {
			if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
				return;
			}
			// Set custom ID
			if ( $custom_id ) {
				$id = $custom_id;
			} else { // Generate ID based on $title
				$id = strtolower( str_replace( ' ', '-', $title ) );
			}
			// links from the current host will open in the current window
			$meta = strpos( $href, home_url() ) !== false ? array() : array( 'target' => '_blank' ); // external links open in new tab/window

			$meta = array_merge( $meta, $custom_meta );
			$wp_admin_bar->add_node(
				array(
					'parent' => $parent,
					'id'     => $id,
					'title'  => $title,
					'href'   => $href,
					'meta'   => $meta,
				)
			);
		}
	}

	public function custom_admin_menu_order() {
		global $menu;

		$admin_menus = array();

		// Change dasbhoard menu order.
		$posts = array();
		$idx   = 0;
		foreach ( $menu as $key => $menu_item ) {
			if ( 'Posts' == $menu_item[0] ) {
				$admin_menus[9] = $menu_item;
			} elseif ( 'separator1' == $menu_item[2] ) {
				$admin_menus[8] = $menu_item;
			} else {
				$admin_menus[ $key ] = $menu_item;
			}
		}

		$menu = $admin_menus;
	}

	public function check_purchase_code() {

		if ( ! $this->_checkedPurchaseCode ) {
			$code         = isset( $_POST['code'] ) ? sanitize_text_field( $_POST['code'] ) : '';
			$code_confirm = $this->get_purchase_code();

			if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) ) {
				preg_match( '/[a-z0-9\-]{1,63}\.[a-z\.]{2,6}$/', parse_url( home_url(), PHP_URL_HOST ), $_domain_tld );
				if ( isset( $_domain_tld[0] ) ) {
					$domain = $_domain_tld[0];
				} else {
					$domain = parse_url( home_url(), PHP_URL_HOST );
				}
				if ( ! $code || $code != $code_confirm ) {
					if ( $code_confirm ) {
						$result = $this->curl_purchase_code( $code_confirm, '', 'remove' );
					}
					if ( 'unregister' === $_POST['action'] && $result && isset( $result['result'] ) && 3 === (int) $result['result'] ) {
						$this->_checkedPurchaseCode = 'unregister';
						$this->set_purchase_code( '' );
						return $this->_checkedPurchaseCode;
					}
				}
				if ( $code ) {
					$result = $this->curl_purchase_code( $code, $domain, 'add' );
					if ( ! $result ) {
						$this->_checkedPurchaseCode = 'invalid';
						$code_confirm               = '';
					} elseif ( isset( $result['result'] ) && 1 === (int) $result['result'] ) {
						$code_confirm               = $code;
						$this->_checkedPurchaseCode = 'verified';
					} else {
						$this->_checkedPurchaseCode = $result['message'];
						$code_confirm               = '';
					}
				} else {
					$code_confirm               = '';
					$this->_checkedPurchaseCode = '';
				}
				$this->set_purchase_code( $code_confirm );
			} else {
				if ( $code && $code_confirm && $code == $code_confirm ) {
					$this->_checkedPurchaseCode = 'verified';
				}
			}
		}
		return $this->_checkedPurchaseCode;
	}

	public function curl_purchase_code( $code, $domain, $act ) {

		require_once MOLLA_PLUGINS . '/importer/importer-api.php';
		$importer_api = new Molla_Importer_API();

		$result = $importer_api->get_response( $this->activation_url . "?item=28487727&code=$code&domain=$domain&siteurl=" . urlencode( home_url() ) . "&act=$act" . ( $importer_api->is_localhost() ? '&local=true' : '' ) );

		if ( ! $result || is_wp_error( $result ) ) {
			return false;
		}
		return $result;
	}

	public function get_purchase_code() {
		if ( $this->is_envato_hosted() ) {
			return SUBSCRIPTION_CODE;
		}
		return get_option( 'envato_purchase_code_28487727' );
	}

	public function is_registered() {
		if ( $this->is_envato_hosted() ) {
			return true;
		}
		return get_option( 'molla_registered' );
	}

	public function set_purchase_code( $code ) {
		update_option( 'envato_purchase_code_28487727', $code );
	}

	public function is_envato_hosted() {
		return defined( 'ENVATO_HOSTED_KEY' ) ? true : false;
	}

	public function get_ish() {
		if ( ! defined( 'ENVATO_HOSTED_KEY' ) ) {
			return false;
		}
		return substr( ENVATO_HOSTED_KEY, 0, 16 );
	}

	function get_purchase_code_asterisk() {
		$code = $this->get_purchase_code();
		if ( $code ) {
			$code = substr( $code, 0, 13 );
			$code = $code . '-****-****-************';
		}
		return $code;
	}

	public function pre_set_site_transient_update_themes( $transient ) {
		if ( ! $this->is_registered() ) {
			return $transient;
		}
		// if ( empty( $transient->checked ) ) {
		// 	return $transient;
		// }

		require_once MOLLA_PLUGINS . '/importer/importer-api.php';
		$importer_api   = new Molla_Importer_API();
		$new_version    = $importer_api->get_latest_theme_version();
		$theme_template = get_template();
		if ( version_compare( wp_get_theme( $theme_template )->get( 'Version' ), $new_version, '<' ) ) {

			$args = $importer_api->generate_args( false );
			if ( $this->is_envato_hosted() ) {
				$args['ish'] = $this->get_ish();
			}

			$transient->response[ $theme_template ] = array(
				'theme'       => $theme_template,
				'new_version' => $new_version,
				'url'         => $importer_api->get_url( 'changelog' ),
				'package'     => add_query_arg( $args, $importer_api->get_url( 'theme' ) ),
			);

		}
		return $transient;
	}

	public function upgrader_pre_download( $reply, $package, $obj ) {

		require_once MOLLA_PLUGINS . '/importer/importer-api.php';
		$importer_api = new Molla_Importer_API();
		if ( strpos( $package, $importer_api->get_url( 'theme' ) ) !== false || strpos( $package, $importer_api->get_url( 'plugins' ) ) !== false ) {
			if ( ! $this->is_registered() ) {
				return new WP_Error( 'not_registerd', sprintf( esc_html__( 'Please %s Molla theme to get access to pre-built demo websites and auto updates.', 'molla' ), '<a href="admin.php?page=molla">' . esc_html__( 'register', 'molla' ) . '</a>' ) );
			}
			$code   = $this->get_purchase_code();
			$domain = $importer_api->generate_args();
			$domain = $domain['domain'];
			$result = $this->curl_purchase_code( $code, $domain, 'add' );
			if ( ! isset( $result['result'] ) || 1 !== (int) $result['result'] ) {
				$message = isset( $result['message'] ) ? $result['message'] : esc_html__( 'Purchase Code is not valid or could not connect to the API server!', 'molla' );
				return new WP_Error( 'purchase_code_invalid', esc_html( $message ) );
			}
		}
		return $reply;
	}

	public function add_theme_update_url() {
		global $pagenow;
		if ( 'update-core.php' == $pagenow ) {

			require_once MOLLA_PLUGINS . '/importer/importer-api.php';
			$importer_api   = new Molla_Importer_API();
			$new_version    = $importer_api->get_latest_theme_version();
			$theme_template = get_template();
			if ( version_compare( MOLLA_VERSION, $new_version, '<' ) ) {
				$url         = $importer_api->get_url( 'changelog' );
				$checkbox_id = md5( wp_get_theme( $theme_template )->get( 'Name' ) );
				wp_add_inline_script( 'molla-admin', 'if (jQuery(\'#checkbox_' . $checkbox_id . '\').length) {jQuery(\'#checkbox_' . $checkbox_id . '\').closest(\'tr\').children().last().append(\'<a href="' . esc_url( $url ) . '" target="_blank">' . esc_js( esc_html__( 'View Details', 'molla' ) ) . '</a>\');}' );
			}
		}
	}

	public function after_switch_theme() {
		if ( $this->is_registered() ) {
			$this->refresh_transients();
		}
		molla_compile_dynamic_css();
	}

	public function refresh_transients() {
		delete_site_transient( 'molla_plugins' );
		delete_site_transient( 'update_themes' );
		unset( $_COOKIE['molla_dismiss_activate_msg'] );
		setcookie( 'molla_dismiss_activate_msg', null, -1, '/' );
	}
}

$GLOBALS['molla_admin'] = new Molla_Admin();
function Molla() {
	global $molla_admin;
	if ( ! $molla_admin ) {
		$molla_admin = new Molla_Admin();
	}
	return $molla_admin;
}
