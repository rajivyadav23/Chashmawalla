<?php
/**
 * Woocommerce Template Hooks
 */

// Mini-Cart Popup.
add_filter( 'woocommerce_add_to_cart_fragments', 'molla_woocommerce_header_add_to_cart_fragment' );
add_filter( 'woocommerce_cart_item_name', 'molla_woocommerce_mini_cart_item_name', 10, 4 );
add_filter( 'woocommerce_get_item_data', 'molla_woocommerce_item_data', 10, 2 );

// output layout html before main content
add_action( 'woocommerce_before_main_content', 'molla_output_layout_before_main', 25 );

/*
 * Product Archive
 */


// Product archive count
add_filter( 'loop_shop_per_page', 'molla_woocommerce_catalog_per_page' );

// Remove Woocommerce actions in loop product content
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open' );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
// Product flash
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
// Show category
add_action( 'woocommerce_shop_loop_item_title', 'molla_woocommerce_shop_loop_category', 9 );
add_action( 'woocommerce_shop_loop_item_title', 'molla_woocommerce_shop_loop_tag', 9 );
// Product Countdown
add_action( 'molla_woocommerce_sale_countdown', 'molla_woocommerce_single_product_deal' );
// Product thumbnail
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_filter( 'single_product_archive_thumbnail_size', 'molla_loop_product_thumbnail_size', 10 );
// Product title
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'molla_woocommerce_loop_product_title', 10 );
// Product total sales
add_action( 'woocommerce_after_shop_loop_item_title', 'molla_product_total_sales', 20, 0 );
// Shop pagination
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
// Load more
add_action( 'molla_woocommerce_after_shop_more', 'molla_loop_more_products', 10 );
// Product price
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
// Product rating
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 15 );
add_action( 'woocommerce_after_shop_loop_item_title', 'molla_woocommerce_print_pickable_attrs', 20 );
// Product  Reviews
add_filter( 'woocommerce_product_get_rating_html', 'molla_get_star_rating_html', 10, 3 );
// Attributes in listed products
add_action( 'molla_woocommerce_product_listed_attrs', 'molla_woocommerce_print_pickable_attrs', 10, 2 );
// Archive show option in toolbox
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_before_shop_loop', 'molla_woocommerce_result_count', 20 );
// Archive ordering in toolbox
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_before_shop_loop', 'molla_woocommerce_catalog_ordering', 30 );
// Archive sidebar toggler
add_filter( 'molla_before_breadcrumb', 'molla_woocommerce_cat_filter' );
add_action( 'woocommerce_before_shop_loop', 'molla_woocommerce_filter_btn', 10 );

/*
 * Product Category Archive
 */

if ( molla_is_elementor_preview() && ! has_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open' ) ) {
	add_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
	add_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );
}

// Product category title class
remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
add_action( 'woocommerce_shop_loop_subcategory_title', 'molla_woocommerce_template_loop_category_title', 10 );
// Product category link
add_action( 'molla_woocommerce_shop_link', 'molla_woocommerce_template_loop_category_link' );
// Product category thumbnail
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
add_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
add_filter( 'subcategory_archive_thumbnail_size', 'molla_woocommerce_thumbnail_size' );
// Show subcategories
add_action( 'woocommerce_after_subcategory_title', 'molla_woocommerce_product_subcategories', 10 );

// Filter for Yith Ajax Filter Url Change
add_filter( 'woocommerce_layered_nav_link', 'molla_wc_layered_nav_link' );

/*
 * Single Product
 */

// Single product woocommerce actions
add_action( 'wp_head', 'molla_single_product_config_actions' );
// Single product init
add_action( 'molla_single_product_init', 'molla_woocommerce_single_product_actions', 10, 2 );
// Single product wrappper class
add_filter( 'woocommerce_single_product_image_gallery_classes', 'molla_single_product_wrapper_class' );
// Single product image size
add_filter( 'woocommerce_gallery_image_html_attachment_image_params', 'molla_single_product_image_size', 10, 4 );
// Single product image params
add_filter( 'woocommerce_gallery_image_html_attachment_image_params', 'molla_attachment_image_params' );
// Single product image html
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'molla_single_product_image_thumbnail_html', 10, 4 );
// Single product label
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
//add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
// remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
// add_action( 'woocommerce_before_single_product_summary', 'molla_wc_show_product_images', 20 );
add_action( 'molla_woocommerce_after_single_image', 'woocommerce_show_product_sale_flash' );
// Single product share
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
// Single product summary
add_action( 'woocommerce_single_product_summary', 'molla_woocommerce_single_product_deal', 25 );
// Single product summary quantity
add_action( 'woocommerce_before_quantity_input_field', 'molla_add_woocommerce_quantity_label' );
// Single product attribute options
add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'molla_variation_dropdown_args' );
// Single product attribute options html
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'molla_variation_dropdown_html', 10, 2 );
// Product attributes html
add_filter( 'woocommerce_display_product_attributes', 'molla_woocommerce_display_product_attrs', 10, 2 );
// Single product metas
add_action( 'woocommerce_product_meta_start', 'molla_woocommerce_product_meta_wrap_start' );
add_action( 'woocommerce_product_meta_end', 'molla_woocommerce_product_meta_wrap_end' );
// Single product cart
add_action( 'woocommerce_before_add_to_cart_button', 'molla_sticky_add_to_cart_start', 1 );
add_action( 'woocommerce_after_add_to_cart_button', 'molla_sticky_add_to_cart_end', 100 );
// Single product data
add_filter( 'woocommerce_product_description_heading', 'molla_product_description_heading' );
add_filter( 'woocommerce_product_additional_information_heading', 'molla_product_additional_info_heading' );
add_filter( 'woocommerce_reviews_title', 'molla_woocommerce_reviews_title', 10, 3 );
add_action( 'molla_woocommerce_review_author', 'molla_review_author', 9 );
add_filter( 'woocommerce_product_tabs', 'molla_woocommerce_product_custom_tabs' );
// Next & Prev buttons
add_filter( 'molla_after_breadcrumb', 'molla_single_product_next_prev_nav', 30 );
// Stock Notification
if ( in_array( 'hurry', molla_option( 'product_labels' ) ) ) {
	add_filter( 'woocommerce_get_availability', 'molla_single_product_stock_param', 10, 2 );
}
// Related Products Count
add_filter( 'woocommerce_output_related_products_args', 'molla_product_related_count' );

/**
 * Cart
 */

// Shipping label
add_action( 'woocommerce_before_shipping_calculator', 'molla_woocommerce_shipping_label' );
// Cross-sells products
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10 );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

/**
 * Checkout
 */

// Checkout fields
add_filter( 'woocommerce_checkout_fields', 'molla_woocommerce_checkout_fields' );

/**
 * My account
 */
add_action( 'wp', 'molla_add_action_to_my_account' );
add_action( 'woocommerce_before_lost_password_form', 'molla_woocommerce_pass_form_wrap_before', 5 );
add_action( 'woocommerce_before_reset_password_form', 'molla_woocommerce_pass_form_wrap_before', 5 );
add_action( 'woocommerce_after_lost_password_form', 'molla_woocommerce_pass_form_wrap_after', 99 );
add_action( 'woocommerce_after_reset_password_form', 'molla_woocommerce_pass_form_wrap_after', 99 );
add_action( 'woocommerce_before_account_navigation', 'molla_dashboard_nav_wrap_start' );
add_action( 'woocommerce_after_account_navigation', 'molla_dashboard_nav_wrap_end' );
add_action( 'woocommerce_before_account_content', 'molla_dashboard_content_wrap_start' );
add_action( 'woocommerce_after_account_content', 'molla_dashboard_content_wrap_end' );


/**
 * Woocommerce breadcrumb
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'wp_head', 'molla_breadcrumb_action' );
add_filter( 'woocommerce_breadcrumb_defaults', 'molla_woocommerce_breadcrumb_args' );

/**
 * Custom Product Category Widget List
 */
add_filter( 'woocommerce_product_categories_widget_args', 'molla_product_cat_widget_args' );

/**
 * Products by brands
 */

add_filter( 'woocommerce_shortcode_products_query', 'molla_woocommerce_products_in_custom_tax', 20, 2 );
