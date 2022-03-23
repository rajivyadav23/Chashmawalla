<?php

if ( ( isset( $is_rtl ) && $is_rtl ) || ( ! isset( $is_rtl ) && is_rtl() ) ) {
	$left  = 'right';
	$right = 'left';
} else {
	$left  = 'left';
	$right = 'right';
}

$gutter          = molla_option( 'grid_gutter_width' );
$content_width   = molla_option( 'content_box_width' );
$container_width = molla_option( 'container_width' );
$content_bg      = molla_option( 'content_bg' );
$frame_bg        = molla_option( 'frame_bg' );
$body_layout     = molla_option( 'body_layout' );
$content_box_pd  = molla_option( 'content_box_padding' );
$sidebar_width   = molla_option( 'sidebar_width' );
$header_content  = '';
$footer_content  = '';

$container_width = $container_width ? (int) $container_width : 1188;

$content_box_pd = $content_box_pd ? $content_box_pd : 30;
$unit           = trim( preg_replace( '/(|-)[0-9.]/', '', $content_box_pd ) );
if ( ! $unit ) {
	$content_box_pd .= 'px';
}

if ( molla_option( 'header_width_op' ) ) {
	$header_content = molla_option( 'header_width' );
}
if ( molla_option( 'footer_width_op' ) ) {
	$footer_content = molla_option( 'footer_width' );
}
?>

.blog-entry-wrapper .posts {
	margin-top: -<?php echo (int) $gutter / 2; ?>px;
}

.container,
.alignwide {
	width: <?php echo esc_attr( $container_width . 'px' ); ?>;
}
@media (min-width: <?php echo ( (int) $container_width + 20 ); ?>px) {
	.container,
	.elementor-section.elementor-section-boxed .elementor-container.container {
		padding-left: <?php echo (int) $gutter / 2; ?>px;
		padding-right: <?php echo (int) $gutter / 2; ?>px;
	}
}

@media (min-width: <?php echo ( (int) $container_width + 20 ); ?>px) and (max-width: 1199px) {
	.container-fluid {
		padding-left: <?php echo (int) $gutter / 2; ?>px;
		padding-right: <?php echo (int) $gutter / 2; ?>px;
	}
}

.row > * {
	padding-left: <?php echo (int) $gutter / 2; ?>px;
	padding-right: <?php echo (int) $gutter / 2; ?>px;
}

[id*="gutenberg-block-"],
[class*="wp-block"]  {
	margin-bottom: <?php echo (int) $gutter / 2; ?>px;
}

.inner-wrap > .row {
	flex-grow: 0;
	flex-shrink: 0;
	flex-basis: calc(100% + <?php echo (int) $gutter; ?>px);
	max-width: calc(100% + <?php echo (int) $gutter; ?>px);
}

<?php if ( $header_content ) : ?>
.header .container {
	width: <?php echo esc_attr( $header_content ); ?>
}
<?php endif; ?>

<?php if ( $footer_content ) : ?>
.footer .container {
	width: <?php echo esc_attr( $footer_content ); ?>
}
<?php endif; ?>
.icon-box.icon-box-bordered:before {
	<?php echo molla_filter_output( $right ); ?>: -<?php echo (int) $gutter / 2; ?>px;
}
.grid-item,
.shop-table-wrapper .sidebar-toggle,
.wp-block-gallery .blocks-gallery-item,
.gallery .gallery-item {
	padding: <?php echo (int) $gutter / 2; ?>px;
}
.col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto,
.col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto, .col-xxl, .col-xxl-1, .col-xxl-10, .col-xxl-11, .col-xxl-12, .col-xxl-2, .col-xxl-3, .col-xxl-4, .col-xxl-5, .col-xxl-6, .col-xxl-7, .col-xxl-8, .col-xxl-9, .col-xxl-auto, .col-5col, .col-7col, .col-8col,
.wp-block-column,
.woocommerce form .form-row,
.woocommerce form > p,
.woocommerce form .woocommerce-billing-fields__field-wrapper p,
.woocommerce form .woocommerce-shipping-fields__field-wrapper p,
.woocommerce form .woocommerce-additional-fields__field-wrapper p,
.woocommerce-EditAccountForm.edit-account p,
.woocommerce form .woocommerce-billing-fields__field-wrapper legend,
.woocommerce form .woocommerce-shipping-fields__field-wrapper legend,
.woocommerce form .woocommerce-additional-fields__field-wrapper legend {
	padding: 0 <?php echo (int) $gutter / 2; ?>px;
}
.row:not([class*=" sp-"]),
.u-columns,
.wp-block-columns,
.blocks-gallery-grid,
.gallery[class*='gallery-columns'],
.woocommerce-billing-fields__field-wrapper,
.woocommerce-shipping-fields__field-wrapper,
.woocommerce-additional-fields__field-wrapper,
.woocommerce-address-fields__field-wrapper,
.woocommerce-EditAccountForm,
.wp-block-gallery,
.container .wp-block-gallery.alignwide {
	margin-left: -<?php echo (int) $gutter / 2; ?>px;
	margin-right: -<?php echo (int) $gutter / 2; ?>px;
}
.container .wp-block-gallery.alignwide {
	max-width: calc(100% + <?php echo (int) $gutter; ?>px);
}

.page-wrapper {
	<?php if ( isset( $content_bg['background-color'] ) && $content_bg['background-color'] ) : ?>
	background-color: <?php echo esc_attr( $content_bg['background-color'] ); ?>;
	<?php else : ?>
	background-color: transparent;
	<?php endif; ?>
	<?php if ( isset( $content_bg['background-image'] ) && $content_bg['background-image'] ) : ?>
	background-image: url('<?php echo esc_url( $content_bg['background-image'] ); ?>');
	background-repeat: <?php echo esc_attr( $content_bg['background-repeat'] ? ( 'repeat-all' == $content_bg['background-repeat'] ? 'repeat' : $content_bg['background-repeat'] ) : 'no-repeat' ); ?>;
	background-position: <?php echo esc_attr( $content_bg['background-position'] ? $content_bg['background-position'] : 'left top' ); ?>;
		<?php if ( $content_bg['background-size'] ) : ?>
	background-size: <?php echo esc_attr( $content_bg['background-size'] ); ?>;
		<?php endif; ?>
		<?php if ( $content_bg['background-attachment'] ) : ?>
	background-attachment: <?php echo esc_attr( $content_bg['background-attachment'] ); ?>;
		<?php endif; ?>
	<?php endif; ?>
}
<?php if ( isset( $content_bg['background-color'] ) ) : ?>
.products.split-line:before {
	background-color: <?php echo esc_attr( $content_bg['background-color'] ); ?>;
}
<?php endif; ?>
.boxed .page-wrapper,
.framed .page-wrapper,
.boxed .sticky-header.fixed,
.framed .sticky-header.fixed {
	width: <?php echo esc_attr( $content_width ? $content_width : '1500' ) . 'px'; ?>;
}
<?php if ( 'boxed' == $body_layout || 'framed' == $body_layout ) : ?>
body {
	<?php if ( isset( $frame_bg['background-color'] ) && $frame_bg['background-color'] ) : ?>
	background-color: <?php echo esc_attr( $frame_bg['background-color'] ); ?>;
	<?php else : ?>
	background-color: transparent;
	<?php endif; ?>
	<?php if ( isset( $frame_bg['background-image'] ) && $frame_bg['background-image'] ) : ?>
	background-image: url('<?php echo esc_url( $frame_bg['background-image'] ); ?>');
	background-repeat: <?php echo esc_attr( $frame_bg['background-repeat'] ? ( 'repeat-all' == $frame_bg['background-repeat'] ? 'repeat' : $frame_bg['background-repeat'] ) : 'no-repeat' ); ?>;
	background-position: <?php echo esc_attr( $frame_bg['background-position'] ? $frame_bg['background-position'] : 'left top' ); ?>;
		<?php if ( $frame_bg['background-size'] ) : ?>
	background-size: <?php echo esc_attr( $frame_bg['background-size'] ); ?>;
		<?php endif; ?>
		<?php if ( $frame_bg['background-attachment'] ) : ?>
	background-attachment: <?php echo esc_attr( $frame_bg['background-attachment'] ); ?>;
		<?php endif; ?>
	<?php endif; ?>
}
	<?php
endif;
?>
.framed .page-wrapper {
	margin: <?php echo esc_attr( $content_box_pd ); ?> auto;
}

<?php if ( $container_width > 1499 ) : ?>
@media (min-width: 1500px) {
	.container .sidebar-wrapper > .col-lg-3 {
		flex: 0 0 20%;
		max-width: 20%;
	}
	.container .sidebar-wrapper > .col-lg-9 {
		flex: 0 0 80%;
		max-width: 80%;
	}
}
<?php endif; ?>

@media (min-width: 992px) {
	.sidebar-wrapper > .col-lg-3 {
		flex: 0 0 <?php echo (int) $sidebar_width; ?>%;
		max-width: <?php echo (int) $sidebar_width; ?>%;
	}
	.sidebar-wrapper > .col-lg-9 {
		flex: 0 0 <?php echo 100 - (int) $sidebar_width; ?>%;
		max-width: <?php echo 100 - (int) $sidebar_width; ?>%;
	}
}

@media (min-width: 992px) {
	.top-sidebar .sidebar-content {
		margin-left: -<?php echo (int) $gutter / 2; ?>px;
		margin-right: -<?php echo (int) $gutter / 2; ?>px;
	}

	.top-sidebar .sidebar-content > * {
		padding-left: <?php echo (int) $gutter / 2; ?>px;
		padding-right: <?php echo (int) $gutter / 2; ?>px;
	}

	.top-sidebar .sidebar-content > *:before {
		left: <?php echo (int) $gutter / 2; ?>px;
		right: <?php echo (int) $gutter / 2; ?>px;
	}

	.top-sidebar .yith-woo-ajax-reset-navigation {
		<?php echo molla_filter_output( $right ); ?>: <?php echo (int) $gutter / 2; ?>px;
	}
}

@media (max-width: <?php echo ( (int) $container_width + 19 ); ?>px) and (min-width: 480px) {
	.container,
	.container-fluid,
	.elementor-section.elementor-section-boxed .elementor-container.container {
		padding-left: 20px;
		padding-right: 20px;
	}
	.wp-block-columns.alignwide {
		max-width: calc(100% - <?php echo 40 - (int) $gutter; ?>px)
	<?php if ( $gutter - 40 > 0 ) : ?>
		position: relative;
		left: -<?php echo ( (int) $gutter - 40 ) / 2; ?>px;
	<?php endif; ?>
	}
}

@media (max-width: 479px) {
	.wp-block-columns.alignwide {
		max-width: calc(100% + <?php echo (int) $gutter - 20; ?>px)
	<?php if ( $gutter - 20 > 0 ) : ?>
		position: relative;
		left: -<?php echo ( (int) $gutter - 20 ) / 2; ?>px;
	<?php endif; ?>
	}
}

<?php if ( defined( 'ELEMENTOR_VERSION' ) ) : ?>
	.elementor-section.elementor-section-boxed > .elementor-container,
	.elementor-section.elementor-section-boxed .elementor-container.container {
		max-width: <?php echo (int) $container_width . 'px'; ?>;
	}
	.elementor-section.elementor-section-boxed > .elementor-column-gap-no {
		max-width: <?php echo ( (int) $container_width - (int) $gutter ) . 'px'; ?>;
	}
	.elementor-section.elementor-section-boxed > .elementor-column-gap-narrow {
		max-width: <?php echo ( (int) $container_width - (int) $gutter + 10 ) . 'px'; ?>;
	}
	.elementor-section.elementor-section-boxed > .elementor-column-gap-extended {
		max-width: <?php echo ( (int) $container_width - (int) $gutter + 30 ) . 'px'; ?>;
	}
	.elementor-section.elementor-section-boxed > .elementor-column-gap-wide {
		max-width: <?php echo ( (int) $container_width - (int) $gutter + 40 ) . 'px'; ?>;
	}
	.elementor-section.elementor-section-boxed > .elementor-column-gap-wider {
		max-width: <?php echo ( (int) $container_width - (int) $gutter + 60 ) . 'px'; ?>;
	}
	
	.elementor-column-gap-default > .elementor-row > .elementor-column > .elementor-element-populated,
	.elementor-column-gap-default > .elementor-column > .elementor-element-populated, {
		padding: <?php echo (int) $gutter / 2; ?>px;
	}

	.elementor-section > .elementor-column-gap-default,
	.elementor-section.elementor-section-boxed .elementor-top-section.elementor-section-boxed > .elementor-container {
		width: calc(100% + <?php echo (int) $gutter; ?>px);
		margin-left: -<?php echo (int) $gutter / 2; ?>px;
		margin-right: -<?php echo (int) $gutter / 2; ?>px;
	}

	@media (max-width: <?php echo ( (int) $container_width + 19 ); ?>px) and (min-width: 480px) {
		.full-inner .elementor-top-section.elementor-section-boxed > .elementor-column-gap-default {
			width: calc(100% - 40px + <?php echo (int) $gutter; ?>px);
		}
		.full-inner .elementor-top-section.elementor-section-boxed > .elementor-column-gap-no {
			width: calc(100% - 40px);
		}
		.full-inner .elementor-top-section.elementor-section-boxed > .elementor-column-gap-narrow {
			width: calc(100% - 30px);
		}
		.full-inner .elementor-top-section.elementor-section-boxed > .elementor-column-gap-extended {
			width: calc(100% - 10px);
		}
		.full-inner .elementor-top-section.elementor-section-boxed > .elementor-column-gap-wide {
			width: 100%;
		}
		.full-inner .elementor-top-section.elementor-section-boxed > .elementor-column-gap-wider {
			width: calc(100% + 20px);
		}
	}

	@media (max-width: 479px) {
		.full-inner .elementor-top-section.elementor-section-boxed > .elementor-column-gap-default {
			width: calc(100% - 20px + <?php echo (int) $gutter; ?>px);
		}
	}

	@media (min-width: 1200px) {
		.elementor-section.elementor-section-boxed > .container-fluid.elementor-column-gap-default {
			padding-left: calc(30px - <?php echo esc_attr( $gutter / 2 ); ?>px);
			padding-right: calc(30px - <?php echo esc_attr( $gutter / 2 ); ?>px);
		}
	}

	@media (min-width: 1600px) {
		.elementor-section.elementor-section-boxed > .container-fluid.elementor-column-gap-default {
			padding-left: calc(70px - <?php echo esc_attr( $gutter / 2 ); ?>px);
			padding-right: calc(70px - <?php echo esc_attr( $gutter / 2 ); ?>px);
		}
	}

<?php endif; ?>
