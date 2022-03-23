<?php
/**
 * Ajax Hooks
 */

// load mobile menu
add_action( 'wp_ajax_molla_load_mobile_menus', 'molla_mobile_menu' );
add_action( 'wp_ajax_nopriv_molla_load_mobile_menus', 'molla_mobile_menu' );

// remove mini-cart item
add_action( 'wp_ajax_molla_cart_item_remove', 'molla_cart_item_remove' );
add_action( 'wp_ajax_nopriv_molla_cart_item_remove', 'molla_cart_item_remove' );

// sign in / sign up form
add_action( 'wp_ajax_molla_account_form', 'molla_ajax_account_form' );
add_action( 'wp_ajax_nopriv_molla_account_form', 'molla_ajax_account_form' );

// sign in ajax validate
add_action( 'wp_ajax_molla_account_login_popup_login', 'molla_account_login_popup_login' );
add_action( 'wp_ajax_nopriv_molla_account_login_popup_login', 'molla_account_login_popup_login' );

// sign up ajax validate
add_action( 'wp_ajax_molla_account_login_popup_register', 'molla_account_login_popup_register' );
add_action( 'wp_ajax_nopriv_molla_account_login_popup_register', 'molla_account_login_popup_register' );

// single product review recommendations
add_action( 'wp_ajax_molla_review-action', 'molla_woocommerce_review_action' );
add_action( 'wp_ajax_nopriv_molla_review-action', 'molla_woocommerce_review_action' );

// load more products
add_action( 'wp_ajax_molla_more_product-action', 'molla_woocommerce_more_product_action' );
add_action( 'wp_ajax_nopriv_molla_more_product-action', 'molla_woocommerce_more_product_action' );

// load more articles
add_action( 'wp_ajax_molla_more_articles-action', 'molla_woocommerce_more_articles_action' );
add_action( 'wp_ajax_nopriv_molla_more_articles-action', 'molla_woocommerce_more_articles_action' );

// product quickview
add_action( 'wp_ajax_molla_quickview-action', 'molla_woocommerce_quickview_action' );
add_action( 'wp_ajax_nopriv_molla_quickview-action', 'molla_woocommerce_quickview_action' );

// lazyload menus
add_action( 'wp_ajax_molla_lazy_load_menus', 'molla_lazy_load_menus' );
add_action( 'wp_ajax_nopriv_molla_lazy_load_menus', 'molla_lazy_load_menus' );

// update minicart item quantity
add_action( 'wp_ajax_molla_update_minicart_qty', 'molla_update_minicart_qty' );
add_action( 'wp_ajax_nopriv_molla_update_minicart_qty', 'molla_update_minicart_qty' );

// print popup builder
add_action( 'wp_ajax_molla_print_popup', 'molla_print_popup' );
add_action( 'wp_ajax_nopriv_molla_print_popup', 'molla_print_popup' );
