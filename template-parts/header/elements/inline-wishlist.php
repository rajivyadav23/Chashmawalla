<?php
if ( class_exists( 'YITH_WCWL' ) ) :
	$wc_link        = YITH_WCWL()->get_wishlist_url();
	$wc_count       = yith_wcwl_count_products();
	$label          = isset( $label ) ? $label : molla_option( 'shop_icon_label_wishlist' );
	$wishlist_class = isset( $wishlist_class ) ? $wishlist_class : molla_option( 'shop_icon_class_wishlist' );
	$classes        = 'wishlist-inline';
	if ( isset( $custom_class ) && $custom_class ) {
		$classes .= ' ' . $custom_class;
	}
	?>
	<li class="<?php echo esc_attr( $classes ); ?>">
		<a href="<?php echo esc_url( $wc_link ); ?>">
			<i class="<?php echo esc_attr( $wishlist_class ? $wishlist_class : 'icon-heart-o' ); ?>"></i>
			<?php echo esc_html( $label ); ?>
			<span class="wishlist-count">(<?php echo esc_html( $wc_count ); ?>)</span>
		</a>
	</li>
	<?php
endif;
