<?php
/**
 * Yith Plugin Hooks
 */

// remove Yith default wishlist icon
add_filter( 'yith_wcwl_button_icon', 'molla_remove_yith_wcwl_button_icon' );

// change Yith Wishlist Edit title Icon
add_filter( 'yith_wcwl_edit_title_icon', 'molla_yith_wcwl_edit_title_icon' );

// remove added wishlist icon
add_filter( 'yith_wcwl_button_added_icon', 'molla_remove_yith_wcwl_button_added_icon' );

// change browse wishlist label
add_filter( 'yith_wcwl_browse_wishlist_label', 'molla_browse_wishlist_label' );

// set color values with theme's
add_filter( 'yith_widget_title_ajax_navigation', 'molla_yith_compatible_color', 10, 3 );

add_filter( 'yith_wcan_term_name_to_show', 'molla_term_name', 10, 2 );

// Remove products count in dropdown filter since 1.3.2
add_filter( 'yith_wcan_force_show_count', '__return_false' );

add_filter( 'yith_woocommerce_reset_filters_attributes', 'molla_enable_reset_filters' );

add_filter( 'yith_woocommerce_reset_filter_link', 'molla_yith_reset_filter_link' );

add_filter( 'yith_wcan_before_reset_widget', 'molla_woocommerce_shop_filter' );

add_filter( 'yith_wcwl_positions', 'molla_single_product_wishlist_pos' );

// Stop YITH reloading view
add_filter( 'yith_wcwl_template_part_hierarchy', 'molla_yith_wcwl_template_part', 10, 5 );

add_filter( 'yith_wcwl_wishlist_params', 'molla_wishlist_params' );

// Set YITH WCAN Reset button's link to shop page.
add_filter( 'yith_woocommerce_reset_filter_link', 'molla_yith_wcan_reset_filter_link' );