<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All admin functions run
 */

if ( is_customize_preview() && class_exists( 'Kirki' ) ) {

	// Include Customizer Settings
	require_once( MOLLA_LIB . '/customizer/customizer-config.php' );
	// Include Customizer Selective Refresh
	require_once( MOLLA_LIB . '/customizer/selective-refresh.php' );

	// Add Options
	require_once( MOLLA_OPTIONS . '/options-general.php' );
	require_once( MOLLA_OPTIONS . '/options-layout.php' );
	require_once( MOLLA_OPTIONS . '/style/options-style.php' );
	require_once( MOLLA_OPTIONS . '/header/options-header.php' );
	require_once( MOLLA_OPTIONS . '/menu/options-menu.php' );
	require_once( MOLLA_OPTIONS . '/options-page-title.php' );
	require_once( MOLLA_OPTIONS . '/footer/options-footer.php' );
	require_once( MOLLA_OPTIONS . '/blog/options-blog.php' );

	if ( class_exists( 'WooCommerce' ) ) {
		require_once( MOLLA_OPTIONS . '/woocommerce/options-woocommerce.php' );
	}

	require_once( MOLLA_OPTIONS . '/options-share.php' );
	require_once( MOLLA_OPTIONS . '/advanced/options-advanced.php' );
}

if ( is_admin() ) {
	// Add Admin Functions
	require_once( MOLLA_ADMIN . '/admin.php' );

	// Add Builder Saver
	require_once( MOLLA_ADMIN . '/builders/builder-save.php' );

	// Add Advanced Options
	if ( ! is_customize_preview() && current_user_can( 'manage_options' ) ) {
		// Setup Wizard
		require_once MOLLA_ADMIN . '/wizard/setup_wizard/setup_wizard.php';
		// Pro v. Speed Optimization Wizard
		require_once MOLLA_ADMIN . '/wizard/speed_optimize_wizard/speed_optimize_wizard.php';
	}

	// Checks if has purchase code
	add_action(
		'admin_init',
		function() {
			if ( isset( $_POST['molla_registration'] ) && check_admin_referer( 'molla-setup-wizard' ) ) {
				update_option( 'molla_register_error_msg', '' );
				$result = Molla()->check_purchase_code();
				if ( 'verified' === $result ) {
					update_option( 'molla_registered', true );
					Molla()->refresh_transients();
				} elseif ( 'unregister' === $result ) {
					update_option( 'molla_registered', false );
					Molla()->refresh_transients();
				} elseif ( 'invalid' === $result ) {
					update_option( 'molla_registered', false );
					update_option( 'molla_register_error_msg', esc_html__( 'There is a problem contacting to the Molla API server. Please try again later.', 'molla' ) );
				} else {
					update_option( 'molla_registered', false );
					update_option( 'molla_register_error_msg', $result );
				}
			}
		}
	);

	add_action(
		'admin_init',
		function() {
			if ( ! Molla()->is_registered() && ( ( 'themes.php' == $GLOBALS['pagenow'] && isset( $_GET['page'] ) ) || empty( $_COOKIE['molla_dismiss_activate_msg'] ) || version_compare( $_COOKIE['molla_dismiss_activate_msg'], MOLLA_VERSION, '<' ) ) ) {
				add_action(
					'admin_notices',
					function() { ?>
				<div class="notice notice-error" style="position: relative;">
						<?php
						echo sprintf( esc_html__( '%1$sPlease %3$s Molla theme to get access to pre-built demo websites and auto updates.%2$s', 'molla' ), '<p>', '</p>', `<a href="<?php echo esc_url( admin_url( 'admin.php?page=molla' ) ); ?>">` . esc_html__( 'register', 'molla' ) . `</a>` );
						echo sprintf( esc_html__( '%1$s%3$sImportant!%4$s One %5$s is valid for only %3$s1 website%4$s. Running multiple websites on a single license is a copyright violation.%2$s', 'molla' ), '<p>', '</p>', '<strong>', '</strong>', `<a target="_blank" href="https://themeforest.net/licenses/standard">` . esc_html__( 'standard license', 'molla' ) . `</a>` );
						?>
					<button type="button" class="notice-dismiss molla-notice-dismiss"><span class="screen-reader-text"><?php esc_html__( 'Dismiss this notice.', 'molla' ); ?></span></button>
				</div>
				<script>
					(function($) {
						var setCookie = function (name, value, exdays) {
							var exdate = new Date();
							exdate.setDate(exdate.getDate() + exdays);
							var val = encodeURIComponent(value) + ((null === exdays) ? "" : "; expires=" + exdate.toUTCString());
							document.cookie = name + "=" + val;
						};
						$(document).on('click.molla-notice-dismiss', '.molla-notice-dismiss', function(e) {
							e.preventDefault();
							var $el = $(this).closest('.notice');
							$el.fadeTo( 100, 0, function() {
								$el.slideUp( 100, function() {
									$el.remove();
								});
							});
							setCookie('molla_dismiss_activate_msg', '<?php echo MOLLA_VERSION; ?>', 30);
						});
					})(window.jQuery);
				</script>
						<?php
					}
				);
			} elseif ( ! Molla()->is_registered() && 'themes.php' == $GLOBALS['pagenow'] ) {
				add_action(
					'admin_footer',
					function() {
						?>
				<script>
					jQuery(window).on('load',function() {
						jQuery('.themes .theme.active .theme-screenshot').after('<div class="notice update-message notice-error notice-alt"><p>Please <a href="<?php echo esc_url( admin_url( 'admin.php?page=molla' ) ); ?>" class="button-link">verify purchase</a> to get updates!</p></div>');
					});
				</script>
						<?php
					}
				);
			}

			// Version compare
			// require_once MOLLA_PLUGINS . '/importer/importer-api.php';
			// $importer_api = new Molla_Importer_API();
			// $new_version  = $importer_api->get_latest_theme_version();
			$new_version = MOLLA_VERSION;
			if ( version_compare( get_theme_mod( 'MOLLA_VERSION', MOLLA_VERSION ), '1.3.2', '<' ) ) {
				$mobile_menus       = molla_option( 'mobile_menus' );
				$mobile_menus_by_id = array();
				if ( is_array( $mobile_menus ) && count( $mobile_menus ) ) {
					foreach ( $mobile_menus as $menu_slug ) {
						$menu                 = get_term_by( 'slug', $menu_slug, 'nav_menu' );
						$mobile_menus_by_id[] = $menu ? (string) $menu->term_id : $menu_slug;
					}
				}
				set_theme_mod( 'mobile_menus', $mobile_menus_by_id );
			}
			if ( version_compare( get_theme_mod( 'MOLLA_VERSION', MOLLA_VERSION ), $new_version, '<' ) ) {
				molla_compile_dynamic_css();
				set_theme_mod( 'MOLLA_VERSION', $new_version );
			}
		}
	);
}
