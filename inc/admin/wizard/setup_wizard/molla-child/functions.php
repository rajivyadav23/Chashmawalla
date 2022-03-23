<?php

add_action( 'wp_enqueue_scripts', 'molla_child_css', 1001 );

// Load CSS
function molla_child_css() {
	// molla child theme styles
	wp_deregister_style( 'styles-child' );
	wp_register_style( 'styles-child', esc_url( get_stylesheet_directory_uri() ) . '/style.css' );
	wp_enqueue_style( 'styles-child' );
}
