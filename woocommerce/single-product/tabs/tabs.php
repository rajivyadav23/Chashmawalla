<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

global $post;

$style    = get_post_meta( $post->ID, 'product_data_type', true );
$size_tab = get_post_meta( $post->ID, 'size_tab_name', true );

if ( ! $style ) {
	$style = molla_option( 'single_product_data_style' );
} else {
	if ( 'tab' == $style ) {
		$style = '';
	}
}

if ( ! empty( $product_tabs ) ) : ?>

	<?php
	if ( ! $style ) :
		?>
	<div class="woocommerce-tabs wc-tabs-wrapper single-product-details">
		<ul class="tabs wc-tabs" role="tablist">
		<?php
	endif;

	if ( $style ) :
		?>
		<div class="accordion accordion-plus single-product-details product-details-accordion" id="product-accordion">
		<?php
	endif;

	$first = 0;

	?>
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<?php
				if ( $style ) :
					?>
				<div class="card card-box card-sm">
					<div class="card-header">
						<h3 class="card-title">
					<?php
				endif;

				if ( ! $style ) :
					?>
				<li class="<?php echo esc_attr( $key ); ?>_tab<?php echo esc_attr( $key == $size_tab || ( ! $size_tab && 'global' == $key ) ? ' size_tab' : '' ); ?>" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
					<?php
				endif;
				?>
					<a href="#tab-<?php echo esc_attr( $key ); ?>" class="nav-link <?php echo esc_attr( $key ) . '_tab' . ( $first ? ' collapsed' : '' ) . esc_attr( $key == $size_tab || ( ! $size_tab && 'global' == $key ) ? ' size_tab' : '' ); ?>" <?php echo esc_attr( $style ? 'data-toggle=collapse aria-expanded=false' : '' ); ?>>
						<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ); ?>
					</a>
				<?php
				if ( ! $style ) :
					?>
				</li>
					<?php
				endif;

				if ( $style ) :
					?>
						</h3>
					</div>
					<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content collapse<?php echo esc_attr( ! $first ? ' show' : '' ); ?>" id="tab-<?php echo esc_attr( $key ); ?>" data-parent="#product-accordion">
						<div class="card-body">
						<?php
						if ( isset( $product_tab['callback'] ) ) {
							call_user_func( $product_tab['callback'], $key, $product_tab );
						}
						?>
						</div>
					</div>
				</div>
					<?php
				endif;
				++ $first;
				?>
			<?php endforeach; ?>
		<?php

		if ( $style ) :
			?>
		</div>
			<?php
		endif;

		if ( ! $style ) :
			?>
		</ul>
			<?php
		endif;

		if ( ! $style ) :
			?>
			<div class="tab-content">
			<?php
			$tab_idx = 0;
			foreach ( $product_tabs as $key => $product_tab ) :
				?>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab tab-pane<?php echo esc_attr( ! $tab_idx ? ' active show' : '' ); ?>" id="tab-<?php echo esc_attr( $key ); ?>" data-id="#tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					$tab_idx ++;
					?>
				</div>
			<?php endforeach; ?>
			</div>
			<?php
		endif;

		do_action( 'woocommerce_product_after_tabs' );

		?>
	<?php
	if ( ! $style ) :
		?>
	</div>
		<?php
	endif;
	?>

	<?php
endif;
