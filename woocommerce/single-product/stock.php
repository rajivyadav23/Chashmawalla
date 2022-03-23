<?php
/**
 * Single Product stock.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/stock.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<p class="stock <?php echo esc_attr( $class ); ?>"><?php echo wp_kses_post( $availability ); ?></p>
<?php

if ( false !== strpos( $class, 'in-stock' ) || false !== strpos( $class, 'out-of-stock' ) ) {
	if ( false !== strpos( $class, 'in-stock' ) ) {
		$stock = $product->get_stock_quantity();
		$total = $stock + $product->get_total_sales();
		$width = ( $stock / $total ) * 100;
	} else {
		$width = 0;
	}
	echo '<div class="progress-bar stock-progress" data-toggle="progress" data-width="' . $width . '"><div class="progress-size negative"></div></div>';
}
