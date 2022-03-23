<?php
require_once MOLLA_LIB . '/lib/molla-color-lib.php';
$molla_color_lib = MollaColorLib::getInstance();

/* Primary color */
$colors['primary_color'] = molla_option( 'primary_color' );
$primary_dark_color      = $molla_color_lib->darken( $colors['primary_color'], 10 );

if ( ( isset( $is_rtl ) && $is_rtl ) || ( ! isset( $is_rtl ) && is_rtl() ) ) {
	$left   = 'right';
	$right  = 'left';
	$before = 'after';
	$after  = 'before';
} else {
	$left   = 'left';
	$right  = 'right';
	$before = 'before';
	$after  = 'after';
}

?>

.has-primary-color {
	color: <?php echo esc_attr( $colors['primary_color'] ); ?>;
}
.has-primary-background-color {
	background-color: <?php echo esc_attr( $colors['primary_color'] ); ?>;
}
.products .product .block-type .countdown-section {
	background-color: rgba(<?php echo esc_attr( $molla_color_lib->hexToRGB( $colors['primary_color'] ) ); ?>, .85);
	border-color: rgba(<?php echo esc_attr( $molla_color_lib->hexToRGB( $colors['primary_color'] ) ); ?>, .1);
}

.text-primary,
.paypal-link:hover,
.paypal-link:focus {
	color: <?php echo esc_attr( $colors['primary_color'] ); ?> !important;
}
.bg-primary {
	background-color: <?php echo esc_attr( $colors['primary_color'] ); ?> !important;
}
::selection,
.menu > li > a:before,
.tip,
.btn-more:hover,
.icon-box .icon-box-icon.icon-stacked i,
.dropdown-menu-wrapper:hover .dropdown-toggle,
.dropdown-menu-wrapper.show .dropdown-toggle,
.btn-secondary:active,
.btn-secondary:hover,
.btn-secondary:focus,
.btn:hover,
.btn:focus,
.alert-primary,
.format-video.post-empty-video .entry-meta:after,
.woocommerce #respond input#submit:hover,
.woocommerce a.button:hover,
.woocommerce button.button:hover,
.woocommerce input.button:hover,
.product-category .btn:hover,
.woocommerce #respond input#submit.alt,
.woocommerce a.button.alt,
.woocommerce button.button.alt,
.woocommerce input.button.alt,
.cat-inner-link .cat-content:hover .cat-link,
.cat-inner-link .cat-content:focus .cat-link,
input[type='radio']:checked ~ label:after,
.product-action a.btn-product:hover,
.product-action a.btn-product:focus,
.btn-product-icon:hover,
.btn-product-icon:focus,
.btn-product-zoom:hover,
.woocommerce .product .product-intro form .button:hover,
.product-action-vertical a:hover,
.product-action-vertical a:focus,
.product-classic .btn-product:hover,
.product-classic .btn-product:focus,
.product-light a.btn-product:hover,
.product-light a.btn-product:focus,
.product-dark a.btn-product:hover,
.product-dark a.btn-product:focus,
.product-dark .product-action-vertical a:hover,
.product-list .btn-product:hover.btn-cart:hover,
.product-list .btn-product:hover.btn-cart:focus,
.product-list .btn-product:focus.btn-cart:hover,
.product-list .btn-product:focus.btn-cart:focus,
.product.product-simple .btn-product-icon:hover,
.product.product-simple .btn-product-icon:focus,
.product.product-simple .yith-wcwl-add-to-wishlist a:hover,
.product.product-simple .yith-wcwl-add-to-wishlist a:focus,
.product-action-vertical a.btn-expandable span,
.product-card .product-action .btn-product,
.product-card .product-action a.btn-quickview:hover,
.product-card .product-action a.btn-quickview:focus,
.product-card .product-action .yith-wcwl-add-to-wishlist a:hover,
.product-card .product-action .yith-wcwl-add-to-wishlist a:focus,
.post.sticky .entry-title:after,
.woocommerce-product-gallery__image .sp-actions-wrapper .sp-action:hover,
.woocommerce-product-gallery__image .sp-actions-wrapper .sp-action:focus,
.shop-icon [class*='count'],
.cart-canvas .button.checkout,
.cart-canvas .button.wc-forward:not(.checkout):hover,
.cart-canvas .button.wc-forward:not(.checkout):focus,
.widget_shopping_cart_content a.wc-forward:not(.checkout),
.widget_shopping_cart_content a.checkout:hover,
.widget_shopping_cart_content a.checkout:focus,
input[type="submit"],
[class*="wp-block"] button,
.btn-primary,
.owl-full .owl-dots .owl-dot.active span,
.owl-full .owl-dots .owl-dot:hover span,
.mobile-menu-container .nav-border-anim .nav-link:before,
.section-scroll-nav li:hover a,
.section-scroll-nav .active a,
.hotspot-wrapper:hover .hotspot-inner,
.progress-bar .progress-size {
	background-color: <?php echo esc_attr( $colors['primary_color'] ); ?>;
}

.link-underline,
.title-link:hover,
.title-link:focus,
.product.product-list .product-action a:hover span,
.product.product-list .product-action a:focus span {
	box-shadow: 0 1px 0 0 <?php echo esc_attr( $colors['primary_color'] ); ?>;
}
.blog-entry-wrapper .nav-filter .active a,
.entry-summary .posted_in a:hover,
.entry-summary .posted_in a:focus,
.entry-summary .tagged_as a:hover,
.entry-summary .tagged_as a:focus,
.entry-summary .product-size a:hover,
.entry-summary .product-size a:focus,
.entry-summary .product-size a.active,
.entry-summary .yith-wcwl-add-to-wishlist a:hover span,
.entry-summary .yith-wcwl-add-to-wishlist a:focus span,
.review-action .recommend:hover,
.review-action .recommend:focus,
.author-link:hover,
.author-link:focus,
.comment-reply-link:hover,
.comment-reply-link:focus {
	box-shadow: 0 1px 0 <?php echo esc_attr( $colors['primary_color'] ); ?>;
}
a,
.calendar_wrap a,
.footer a:hover,
.footer a:focus,
.menu li > a:hover,
.menu li.active > a,
.menu li.current-menu-item > a,
.menu li.current-menu-ancestor > a,
.custom-header .nav-dropdown a:hover,
.nav-filter a:hover,
.nav-filter a:focus,
.nav-filter .active a,
.comment-content a,
.icon-box-icon i,
.btn.btn-icon:hover,
.btn.btn-icon:focus,
.btn.btn-icon:active,
.btn-primary.btn-outline,
.btn-primary.btn-link,
.btn.btn-link:hover,
.btn.btn-link:focus,
a.search-toggle:hover,
a.search-toggle:focus,
a.search-toggle.active,
.woocommerce #respond input#submit,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
.woocommerce-message a,
.circle-type .social-icon:hover,
.circle-type .social-icon:focus,
.footer .tel,
.menu-item-has-children.open > a,
.menu .megamenu li:not(.menu-item-has-children):hover > a,
.menu li:hover > a,
.menu-item > a:hover,
.mobile-menu-wrapper .active .nav-link,
.mobile-menu-light .mobile-menu li.open > a,
.mobile-menu-light .mobile-menu li.active > a,
.mobile-menu-close:hover,
.mobile-menu-close:focus,
.mobile-menu li a:hover,
.mobile-menu li a:focus,
.mmenu-btn:hover,
.mmenu-btn:focus,
.mobile-cats-menu li a:hover,
.mobile-cats-menu li a:focus,
.menu-vertical li:hover > a,
.menu-vertical li:focus > a,
.menu-vertical li.active > a,
.product-category .btn,
.cat-block:hover .cat-block-title,
.category-list li a:hover,
.breadcrumb-item a:hover,
.breadcrumb-item a:focus,
.card-title a,
.card-title a:before,
.card-title a.collapsed:hover,
.card-title a.collapsed:focus,
.count-wrapper,
.feature-box i,
.feature-box-simple i,
.close:hover,
.close:focus,
.price_slider_wrapper .price_label span,
.nav-thumbs .thumb-label:not(:disabled):hover,
.nav-thumbs .thumb-label:not(:disabled):focus,
.nav-thumbs .thumb-label:not(:disabled).active,
li .page-numbers.current,
li .page-numbers:hover,
li .page-numbers:focus,
.wishlist_table tr td.product-stock-status span.wishlist-in-stock,
.wishlist_table.mobile li .wishlist-in-stock,
.deal-countdown .countdown-amount,
.deal-container.inline-type .countdown-section:after,
.deal-container.inline-type .countdown-period,
.product-title a:hover,
.product-title a:focus,
.shop_table:not(.woocommerce-checkout-review-order-table) td.product-name a:hover,
.shop_table:not(.woocommerce-checkout-review-order-table) td.product-name a:focus,
.woocommerce .download-product a:hover,
.woocommerce .download-product a:focus,
.woocommerce div.product p.price,
.woocommerce div.product span.price,
.product-label-text,
.ratings-text a:hover,
.ratings-text a:focus,
.woocommerce .product .product-intro form .button,
.product-action .btn-product,
.product-list .product-action .btn-product:hover,
.product-list .product-action .btn-product:focus,
.btn-product-icon,
.product-action-vertical a,
.product-classic .btn-product,
.product.product-popup .product-action .btn-product,
.product-popup .icon-hidden .btn-product:hover:before,
.product-popup .icon-hidden .btn-product:focus:before,
.product-no-overlay .product-action .btn-product:hover span,
.product-no-overlay .product-action .btn-product:focus span,
.product-popup .btn-product:hover,
.product-popup .btn-product:focus,
.product-list .product-action a:hover,
.product-list .product-action a:focus,
.product-list .btn-product:hover,
.product-list .btn-product:focus,
.product-list .btn-product:hover.btn-cart,
.product-list .btn-product:focus.btn-cart,
li a.social-icon:hover,
li a.social-icon:focus,
.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover,
.woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
.nav .nav-link:hover,
.nav .nav-link:focus,
.filter-toggler:hover,
.filter-toggler:focus,
.entry-video a:hover,
.entry-video a:focus,
.entry-meta a:hover,
.entry-meta a:focus,
.entry-title a:hover,
.entry-title a:focus,
.entry-cats a:hover,
.entry-cats a:focus,
.entry-media .mejs-overlay-button:hover:before,
.comment-respond .btn,
.cart .product-subtotal .amount,
.cart-canvas .total .amount,
.cart-canvas .quantity .amount,
.shop_table .product-price,
.woocommerce .shop_table .order-total th,
.woocommerce .shop_table .order-total .amount,
.btn.btn-spinner:hover,
.btn.btn-spinner:focus,
.btn.btn-spinner:active,
.widget ul li.cat-parent:not(.collapsed) > a,
.widget .current-cat > a,
.sidebar li a:hover,
.sidebar li a:focus,
.yith-woo-ajax-reset-navigation a.button,
.filter-btn:hover,
.filter-btn:focus,
#filter-price-range,
.shop-toolbox .nav-filter .active a,
.table.table-summary a:hover,
.table.table-summary a:focus,
.table.table-summary .summary-total td,
.payment_method_paypal .about_paypal:hover,
.payment_method_paypal .about_paypal:focus,
.form-box .tab-content .form-footer a:hover,
.form-box .tab-content .form-footer a:focus,
.product.product-list .btn-product.btn-cart,
.product-body .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a,
.product-body .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a,
.product-pager-link:hover a,
.product-pager-link:focus a,
.product-pager-link:hover i,
.product-pager-link:focus i,
.products .product .product-title .yith-wcwl-add-to-wishlist a:hover,
.products .product .product-title .yith-wcwl-add-to-wishlist a:focus,
.entry-summary .posted_in a:hover,
.entry-summary .posted_in a:focus,
.entry-summary .tagged_as a:hover,
.entry-summary .tagged_as a:focus,
.entry-summary .yith-wcwl-add-to-wishlist a:<?php echo molla_filter_output( $before ); ?>,
.entry-summary .yith-wcwl-add-to-wishlist a:hover,
.tab-content a:hover,
.tab-content a:focus,
.review h4 a:hover,
.review h4 a:focus,
.review-action .recommend:hover,
.review-action .recommend:focus,
.sticky-bar .price,
.editor-content a:hover,
.editor-content a:focus,
.entry-tags a:hover,
.entry-tags a:focus,
.entry-author-details h4 a:hover,
.entry-author-details h4 a:focus,
.page-link > a,
.page-link > a:hover,
.page-link > a:focus,
.page-link > a:hover:after,
.page-link > a:focus:after,
.product-thumbnail.dropdown a:hover .product-title,
.product-thumbnail.dropdown a:hover .product-title,
.page-header h3,
.comment-user h4 a:hover,
.comment-user h4 a:focus,
.woocommerce .woocommerce-cart-form .actions>button.button:hover,
.woocommerce .woocommerce-cart-form .actions>button.button:focus,
.shop_table .product-title a:hover,
.shop_table .product-title a:focus,
.header-top a:hover,
.header-top a:focus,
.top-menu span,
.top-menu:hover .top-link > a,
.shop-icon:hover > a,
.shop-icon:focus > a,
.shop-icon:hover > a .custom-label,
.shop-icon:focus > a .custom-label,
.cart-canvas .button.wc-forward:not(.checkout),
.mini_cart_item a:hover,
.mini_cart_item a:focus,
.woocommerce .widget_shopping_cart .cart_list li a:hover,
.woocommerce .widget_shopping_cart .cart_list li a:focus,
.woocommerce.widget_shopping_cart .cart_list li a:hover,
.woocommerce.widget_shopping_cart .cart_list li a:focus,
.widget_shopping_cart_content a.checkout,
.owl-full .owl-nav [class*='owl-'],
.owl-carousel.owl-full .owl-nav [class*='owl-']:hover,
.owl-carousel.owl-full .owl-nav [class*='owl-']:focus,
.owl-simple .owl-nav [class*='owl-']:not(.disabled):hover,
.owl-simple .owl-nav [class*='owl-']:not(.disabled):focus,
.woocommerce .woocommerce-breadcrumb a:hover,
.woocommerce .woocommerce-breadcrumb a:focus,
.woocommerce-MyAccount-navigation ul li.is-active a,
.woocommerce-breadcrumb a:hover,
.woocommerce-breadcrumb a:focus,
.blog-pager .pager-link a,
.blog-pager .pager-link a:hover:after,
.blog-pager .pager-link a:focus:after,
.tagcloud a:hover,
.tagcloud a:focus,
.woocommerce-MyAccount-content a,
.woocommerce-Addresses a,
.elementor-widget-icon-box .elementor-icon-box-title a:hover,
.elementor-widget-icon-box .elementor-icon-box-title a:focus,
.woocommerce .widget_layered_nav ul.yith-wcan-label li a:hover,
.woocommerce-page .widget_layered_nav ul.yith-wcan-label li a:hover,
.woocommerce .widget_layered_nav ul.yith-wcan-label li span:hover,
.woocommerce-page .widget_layered_nav ul.yith-wcan-label li span:hover,
.woocommerce .widget_layered_nav ul.yith-wcan-label li.chosen a,
.woocommerce-page .widget_layered_nav ul.yith-wcan-label li.chosen a {
	color: <?php echo esc_attr( $colors['primary_color'] ); ?>;
}

.woocommerce #respond input#submit:focus,
.woocommerce a.button:focus,
.woocommerce button.button:focus,
.woocommerce input.button:focus,
.woocommerce #respond input#submit.alt:hover,
.woocommerce a.button.alt:hover,
.woocommerce button.button.alt:hover,
.woocommerce input.button.alt:hover,
.woocommerce .product .product-intro form .button:focus,
.wpcf7-submit:active,
.btn:active {
	background-color: <?php echo esc_attr( $primary_dark_color ); ?>;
}

a:hover,
a:focus,
.cat-link:hover,
.cat-link:focus,
.entry-content a:hover,
.entry-content a:focus,
.header-link:hover,
.header-link:focus,
.btn-link.btn-primary:hover,
.btn-link.btn-primary:focus,
.btn-link.btn-primary:active,
.product.product-gallery-popup .btn-product:hover,
.product.product-gallery-popup .btn-product:focus,
.hotspot-product .product .product-action a:hover,
.hotspot-product .product .product-action a:focus,
.woocommerce-message a:hover,
.woocommerce-message a:focus,
.footer .tel:hover,
.reset_variations:hover,
.reset_variations:focus,
.woocommerce .yith-woo-ajax-reset-navigation a.button:hover,
.woocommerce .yith-woo-ajax-reset-navigation a.button:focus,
.yith-wcwl-add-to-wishlist a:hover,
.yith-wcwl-add-to-wishlist a:focus,
.woocommerce-MyAccount-content a:hover,
.woocommerce-MyAccount-content a:focus,
.woocommerce-Addresses a:hover,
.woocommerce-Addresses a:focus,
.logged-in-as a:hover,
.logged-in-as a:focus,
.comment-content a:hover,
.comment-content a:focus {
	color: <?php echo esc_attr( $primary_dark_color ); ?>;
}
.btn-secondary:active,
.btn-secondary:hover,
.btn-secondary:focus,
.btn-alert:hover,
.btn-alert:focus,
.btn-dark:hover,
.btn-dark:focus,
.btn-light:hover,
.btn-light:focus,
.btn.btn-outline:hover,
.btn.btn-outline:focus,
.btn-more:hover,
.woocommerce #respond input#submit,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
.mobile-menu-light .mobile-search .form-control:focus,
.product-category .btn,
.form-control:focus,
input[type='radio']:checked ~ label:before,
.product-action a.btn-product:hover,
.product-action a.btn-product:focus,
.btn-product-icon,
.icon-box .icon-box-icon.icon-framed i,
.comment-respond .btn,
.woocommerce .product .product-intro form .button,
.product-action-vertical .yith-wcwl-add-to-wishlist a,
.product-action-vertical a,
.product-classic .btn-product,
.product-no-overlay .product-action .btn-cart,
.product-light .product-action-vertical a:hover,
.product-dark .product-action-vertical a:hover,
.product.product-list .btn-product.btn-cart,
.product-list .btn-product:hover.btn-cart,
.product-list .btn-product:focus.btn-cart,
.circle-type .social-icon:hover,
.circle-type .social-icon:focus,
.product.product-simple .btn-product-icon:hover,
.product.product-simple .btn-product-icon:focus,
.product.product-simple .yith-wcwl-add-to-wishlist a:hover,
.product.product-simple .yith-wcwl-add-to-wishlist a:focus,
.product-card .product-action a.btn-quickview:hover,
.product-card .product-action a.btn-quickview:focus,
.product-card .product-action .yith-wcwl-add-to-wishlist a:hover,
.product-card .product-action .yith-wcwl-add-to-wishlist a:focus,
.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover,
.woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
.nav-thumbs .thumb-label:not(:disabled):hover,
.nav-thumbs .thumb-label:not(:disabled):focus,
.nav-thumbs .thumb-label:not(:disabled).active,
.nav.nav-pills .nav-item.show .nav-link,
.nav.nav-pills .nav-item.active .nav-link,
.product-image-gallery a:before,
.entry-summary .product-size a:hover,
.entry-summary .product-size a:focus,
.entry-summary .product-size a.active,
.tab-content a:hover,
.tab-content a:focus,
.cart-canvas .button.wc-forward,
.cart-canvas .button.wc-forward:not(.checkout):hover,
.cart-canvas .button.wc-forward:not(.checkout):focus,
.widget_shopping_cart_content a.wc-forward,
.widget_shopping_cart_content a.checkout,
.widget_shopping_cart_content a.checkout:hover,
.widget_shopping_cart_content a.checkout:focus,
.social-icon:hover,
.social-icon:focus,
.btn-primary,
.btn-link:hover,
.btn-link:focus,
.btn-link:active,
.select2-container--open .select2-selection--single,
.woocommerce form .form-row input.input-text:focus,
.woocommerce form .form-row textarea:focus,
input:focus,
textarea:focus,
form input:focus,
form textarea:focus,
.owl-full .owl-dots .owl-dot.active span,
.owl-full .owl-dots .owl-dot span,
.owl-full .owl-dots .owl-dot:hover span,
.section-scroll-nav li:hover a,
.section-scroll-nav .active a,
.woocommerce .widget_layered_nav ul.yith-wcan-label li a:hover,
.woocommerce-page .widget_layered_nav ul.yith-wcan-label li a:hover,
.woocommerce .widget_layered_nav ul.yith-wcan-label li span:hover,
.woocommerce-page .widget_layered_nav ul.yith-wcan-label li span:hover,
.woocommerce .widget_layered_nav ul.yith-wcan-label li.chosen a,
.woocommerce-page .widget_layered_nav ul.yith-wcan-label li.chosen a {
	border-color: <?php echo esc_attr( $colors['primary_color'] ); ?>;
}
blockquote,
blockquote.wp-block-quote {
	border-color: <?php echo esc_attr( $colors['primary_color'] ); ?>;
}
.nav.nav-pills .nav-item.show .nav-link,
.nav-filter .active a {
	border-bottom-color: <?php echo esc_attr( $colors['primary_color'] ); ?>;
}

<?php
/* Secondary color */
$colors['secondary_color'] = molla_option( 'secondary_color' );
?>

.has-secondary-color,
.btn-secondary.btn-outline,
.btn-secondary.btn-link,
.testimonial .numeric:before {
	color: <?php echo esc_attr( $colors['secondary_color'] ); ?>;
}
.product-sales-percent,
.has-secondary-background-color {
	background-color: <?php echo esc_attr( $colors['secondary_color'] ); ?>;
}

.bg-secondary {
	background-color: <?php echo esc_attr( $colors['secondary_color'] ); ?> !important;
}
.text-secondary {
	color: <?php echo esc_attr( $colors['secondary_color'] ); ?> !important;
}
.product-card .product-action .btn-product:hover,
.product-card .product-action .btn-product:focus,
input[type="submit"]:hover,
input[type="submit"]:focus,
[class*="wp-block"] button:hover,
[class*="wp-block"] button:focus,
.btn-primary:active,
.cart-canvas .button.checkout:hover,
.cart-canvas .button.checkout:focus,
.widget_shopping_cart_content a.wc-forward:not(.checkout):hover,
.widget_shopping_cart_content a.wc-forward:not(.checkout):focus,
.btn-video:hover,
.btn-video:focus,
.btn-primary:not(.btn-outline):hover,
.btn-primary:not(.btn-outline):focus,
.btn-primary:not(.btn-outline):active,
.btn-secondary {
	background-color: <?php echo esc_attr( $colors['secondary_color'] ); ?>;
}
.product-card .product-action .btn-product:hover,
.product-card .product-action .btn-product:focus,
input[type="submit"]:hover,
input[type="submit"]:focus,
[class*="wp-block"] button:hover,
[class*="wp-block"] button:focus,
.cart-canvas .button.checkout:hover,
.cart-canvas .button.checkout:focus,
.widget_shopping_cart_content a.wc-forward:not(.checkout):hover,
.widget_shopping_cart_content a.wc-forward:not(.checkout):focus,
.btn-primary:active,
.btn-primary:hover,
.btn-primary:focus,
.btn-primary:focus:active,
.btn-secondary {
	border-color: <?php echo esc_attr( $colors['secondary_color'] ); ?>;
}

<?php
/* Alert color */
$colors['alert_color'] = molla_option( 'alert_color' );
$alert_light_color     = $molla_color_lib->lighten( $colors['alert_color'], 30 );
?>

.has-alert-color,
.btn-alert.btn-outline,
.btn-alert.btn-link,
.product .stock strong {
	color: <?php echo esc_attr( $colors['alert_color'] ); ?>;
}
.btn-alert,
.has-alert-background-color {
	background-color: <?php echo esc_attr( $colors['alert_color'] ); ?>;
}
.btn-alert.btn-outline {
	border-color: <?php echo esc_attr( $colors['alert_color'] ); ?>;
}

.product .progress-bar {
	background-color: <?php echo esc_attr( $alert_light_color ); ?>;
}
.product .progress-size {
	background-color: <?php echo esc_attr( $colors['alert_color'] ); ?>;
}

<?php
/* Dark color */
$colors['dark_color'] = molla_option( 'dark_color' );
?>

.has-dark-color,
.btn-dark.btn-outline,
.btn-dark.btn-link {
	color: <?php echo esc_attr( $colors['dark_color'] ); ?>;
}
.btn-dark,
.has-dark-background-color {
	background-color: <?php echo esc_attr( $colors['dark_color'] ); ?>;
}
.btn-dark.btn-outline {
	border-color: <?php echo esc_attr( $colors['dark_color'] ); ?>;
}

<?php
/* Light color */
$colors['light_color'] = molla_option( 'light_color' );
?>

.has-light-color,
.btn-light.btn-outline,
.btn-light.btn-link {
	color: <?php echo esc_attr( $colors['light_color'] ); ?>;
}
.btn-light,
.has-light-background-color {
	background-color: <?php echo esc_attr( $colors['light_color'] ); ?>;
}
.btn-light.btn-outline {
	border-color: <?php echo esc_attr( $colors['light_color'] ); ?>;
}

.btn:active,
.btn-link.btn-primary:hover,
.btn-link.btn-primary:focus,
.btn-link.btn-primary:active {
	border-color: <?php echo esc_attr( $primary_dark_color ); ?>;
}

<?php
$labels = array(
	'featured' => 'hot',
	'sale'     => 'sale',
	'new'      => 'new',
	'outstock' => 'out',
	'hurry'    => 'hurry',
);
foreach ( $labels as $label => $selector ) {
	$mode = molla_option( $label . '_label_color_mode' );
	?>
	.product .product-label.label-<?php echo esc_attr( $selector ); ?> {
	<?php if ( $mode ) : ?>
		background-color: <?php echo esc_attr( $colors[ $mode ] ); ?>;
		border-color: <?php echo esc_attr( $colors[ $mode ] ); ?>
	<?php else : ?>
		color: <?php echo esc_attr( molla_option( $label . '_label_color_text' ) ); ?>;
		background-color: <?php echo esc_attr( molla_option( $label . '_label_color' ) ); ?>;
		border-color: <?php echo esc_attr( molla_option( $label . '_label_color' ) ); ?>
	<?php endif; ?>
	}
	<?php
}
?>
.molla-lazyload,
.molla-lazyload-back {
	background-color: <?php echo esc_attr( molla_option( 'lazy_load_img_back' ) ); ?>
}

<?php
$divider_color = molla_option( 'divider_color' );
?>
.products .product .product-action > * + * {
	border-color: <?php echo esc_attr( $divider_color ); ?>;
}
