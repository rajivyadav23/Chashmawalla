<?php
/**
 * Molla Template Hooks
 */

 /**
  * Init actions
  */
add_action( 'init', 'molla_set_cookie' );
add_action( 'wp', 'molla_set_layout', 10 );

/**
 * Pring Page Header
 */
add_action( 'wp_head', 'molla_add_page_header_action' );

/**
 * Top & bottom ... blocks
 */
add_action( 'wp_head', 'molla_page_block_actions' );

/**
 * Custom Contact Form Validation
 */
add_action( 'wpcf7_init', 'molla_wpcf7_add_form_tag_submit', 20, 0 );
add_filter( 'wpcf7_form_novalidate', 'molla_wpcf7_validate_arg' );

/**
 * Custom Category Widget List
 */
add_filter( 'widget_categories_args', 'molla_widget_categories_args', 10, 2 );

/**
 * Sidebar action
 */
add_action( 'molla_sidebar_control', 'molla_print_sidebar_overlay', 10 );

// No result message
add_action( 'molla_empty_message', 'molla_empty_message' );
