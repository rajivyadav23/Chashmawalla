<?php

/**
 * Molla Dynamic Style
 *
 * @since      1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( 'internal' == molla_get_dynamic_css_output_mode() ) {
	add_action( 'wp_enqueue_scripts', 'molla_add_dynamic_styles', 25 );
}

add_action( 'wp_enqueue_scripts', 'molla_add_internal_styles', 30 );
add_action( 'molla_body_before_end', 'molla_output_internal_js' );
add_action( 'customize_save_after', 'molla_compile_dynamic_css', 99 );
add_action( 'wp_enqueue_scripts', 'molla_enqueue_dynamic_style', 20 );

add_action( 'molla_demo_imported', 'molla_delete_old_site', 10 );

add_action( 'molla_demo_imported', 'molla_compile_dynamic_css', 20 );

function molla_get_dynamic_css_output_mode() {

	$upload_dir = wp_upload_dir();
	$css_file   = $upload_dir['basedir'] . '/molla_css/dynamic_style.css';
	if ( is_customize_preview() ) {
		return 'internal';
	}
	if ( ! file_exists( $css_file ) ) {
		molla_compile_dynamic_css();
	}
	return 'file';
}

// delete old site molla_styles
function molla_delete_old_site() {
	// filesystem
	global $wp_filesystem;

	// Initialize the WordPress filesystem, no more using file_put_contents function
	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}

	$upload_dir = wp_upload_dir();
	$paths      = array( '/molla_styles' );
	foreach ( $paths as $path ) {
		$style_path = $upload_dir['basedir'] . $path;
		if ( file_exists( $style_path ) ) {
			$wp_filesystem->delete( $style_path, true );
		}
	}
}

/**
 * compile dynamic css when saving theme options
 */
function molla_compile_dynamic_css() {

	// filesystem
	global $wp_filesystem;

	// Initialize the WordPress filesystem, no more using file_put_contents function
	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}

	$upload_dir = wp_upload_dir();
	$style_path = $upload_dir['basedir'] . '/molla_css';
	// Compile dynamic styles
	if ( ! file_exists( $style_path ) ) {
		wp_mkdir_p( $style_path );
	}
	$result = true;

	$dir_ary = array( '', 'rtl' );

	foreach ( $dir_ary as $dir ) {

		$is_rtl = $dir ? true : false;

		$css = molla_get_dynamic_css( $is_rtl );

		$filename = $style_path . '/dynamic_style' . ( $dir ? '_rtl' : '' ) . '.css';
		molla_file_write_enable( $filename );

		$wp_filesystem->put_contents( $filename, $css, FS_CHMOD_FILE );
	}
}

function molla_add_dynamic_styles( $output = false ) {
	ob_start();
	include MOLLA_FUNCTIONS . '/dynamic_css/dynamic_style.php';
	$css = ob_get_clean();

	wp_add_inline_style( 'molla-theme', $css );
}

function molla_enqueue_dynamic_style() {
	// dynamic styles
	if ( 'file' == molla_get_dynamic_css_output_mode() ) {
		wp_deregister_style( 'molla-dynamic-style' );
		if ( is_rtl() ) {
			wp_register_style( 'molla-dynamic-style', wp_upload_dir()['baseurl'] . '/molla_css/dynamic_style_rtl.css', false, false );
		} else {
			wp_register_style( 'molla-dynamic-style', wp_upload_dir()['baseurl'] . '/molla_css/dynamic_style.css', false, false );
		}
	}
}

function molla_add_internal_styles() {

	$page_css = molla_get_internal_css();
	wp_add_inline_style( 'molla-style', $page_css );
}

function molla_output_internal_js() {
	if ( molla_is_elementor_preview() ) {
		return;
	}

	$global_script = molla_option( 'custom_script' );
	if ( $global_script ) {
		echo '<script id="molla_dynamic_custom_script">';
		echo trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', $global_script ) );
		echo '</script>';
	}
	$page_script = get_post_meta( intval( get_the_ID() ), 'page_script', true );
	if ( $page_script ) {
		echo '<script id="page_' . intval( get_the_ID() ) . '_script">';
		echo trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', $page_script ) );
		echo '</script>';
	}
}



/**
 * Molla Dynamic Style Output Functions
 *
 * @since      1.0
 */

function molla_dynamic_typography( $font, $color_inherit = false ) {

	$font_params = array(
		'font-family',
		'font-weight',
		'font-size',
		'font-style',
		'line-height',
		'letter-spacing',
		'color',
		'text-transform',
	);

	foreach ( $font_params as $font_param ) :
		if ( 'color' == $font_param && $color_inherit ) {
			if ( ! $font['color'] ) {
				$font['color'] = 'inherit';
			}
		}
		if ( 'font-family' == $font_param && 'inherit' != $font[ $font_param ] ) {
			echo 'font-family: "' . $font[ $font_param ] . '";';
		} elseif ( isset( $font[ $font_param ] ) && $font[ $font_param ] ) {
			echo esc_attr( $font_param ) . ': ' . $font[ $font_param ] . ';';
		}
	endforeach;
}
