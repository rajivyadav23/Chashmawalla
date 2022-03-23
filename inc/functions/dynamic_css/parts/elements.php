<?php
$divider_color = molla_option( 'breadcrumb_divider_color' );
?>

.woocommerce-breadcrumb.divider-active .full-divider,
.woocommerce-breadcrumb.divider-active .inner-wrap {
	border-color: <?php echo esc_attr( $divider_color ); ?>;
}
