<?php

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$log_in_label        = isset( $log_in_label ) ? $log_in_label : molla_option( 'log_in_label' );
$register_label      = isset( $register_label ) ? $register_label : molla_option( 'register_label' );
$log_out_label       = isset( $log_out_label ) ? $log_out_label : molla_option( 'log_out_label' );
$log_icon_class      = isset( $log_icon_class ) ? $log_icon_class : molla_option( 'log_icon_class' );
$show_register_label = isset( $show_register_label ) ? $show_register_label : molla_option( 'show_register_label' );
$delimiter           = isset( $delimiter ) ? $delimiter : ' / ';
$custom_class        = isset( $custom_class ) ? $custom_class : '';

$html = '<' . ( isset( $tag ) && 'li' == $tag ? 'li' : 'div' ) . ' class="account-links' . ( $custom_class ? ( ' ' . $custom_class ) : '' ) . '">';
if ( $log_icon_class ) {
	$log_icon_class = '<i class="' . esc_attr( $log_icon_class ) . '"></i>';
}
if ( is_user_logged_in() ) {
	$logout_link = '';
	if ( class_exists( 'WooCommerce' ) ) {
		$logout_link = wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) );
	} else {
		$logout_link = wp_logout_url( get_home_url() );
	}
	$html .= '<a class="header-link logout-link" href="' . esc_url( $logout_link ) . '">' . $log_icon_class;
	$html .= $log_out_label . '</a>';
} else {
	$login_link    = '';
	$register_link = '';
	if ( class_exists( 'WooCommerce' ) ) {
		$login_link    = wc_get_page_permalink( 'myaccount' );
		$register_link = wc_get_page_permalink( 'myaccount' );
	} else {
		$login_link    = wp_login_url( get_home_url() );
		$active_signup = get_site_option( 'registration', 'none' );
		$active_signup = apply_filters( 'wpmu_active_signup', $active_signup );
		if ( 'none' != $active_signup ) {
			$register_link = wp_registration_url( get_home_url() );
		}
	}
	$html .= '<a class="header-link login-link" href="' . esc_url( $login_link ) . '">' . $log_icon_class;
	$html .= $log_in_label . ( $show_register_label ? ( $delimiter . $register_label ) : '' ) . '</a>';
}
$html .= '</' . ( isset( $tag ) && 'li' == $tag ? 'li' : 'div' ) . '>';
echo molla_strip_script_tags( $html );
