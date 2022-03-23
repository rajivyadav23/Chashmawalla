<?php

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$cls = '';
if ( molla_option( 'shop_icon_label_hide' ) ) {
	$cls .= 'label-hidden ';
}

$shop_icon_type = molla_option( 'shop_icon_type' );
$account_class  = molla_option( 'shop_icon_class_account' );
$cart_class     = molla_option( 'shop_icon_class_cart' );
$wishlist_class = molla_option( 'shop_icon_class_wishlist' );

$cls .= $shop_icon_type . ' ' . molla_option( 'shop_icon_label_type' );

$divider_active = molla_option( 'shop_icons_divider' );

$items = molla_option( 'shop_icons' );
$first = true;
?>
<div class="shop-icons">
<?php
foreach ( $items as $elem ) {
	if ( $divider_active ) {
		if ( false == $first ) {
				echo '<span class="divider"></span>';
		}
		if ( true == $first ) {
			$first = false;
		}
	}
	if ( 'account' == $elem ) {
		if ( class_exists( 'WooCommerce' ) ) {
			$account_link = wc_get_page_permalink( 'myaccount' );
		}
		?>
		<div class="shop-icon account <?php echo esc_attr( $cls ); ?>">
			<a href="<?php echo esc_url( $account_link ); ?>">
				<div class="icon">
					<i class="<?php echo esc_attr( $account_class ? $account_class : 'icon-user' ); ?>"></i>
				</div>
				<p class="custom-label"><?php echo esc_html( molla_option( 'shop_icon_label_account' ) ); ?></p>
			</a>
		</div>
		<?php
	} elseif ( 'cart' == $elem ) {
		$cart_canvas  = molla_option( 'cart_canvas_type' );
		$cart_classes = 'cart-popup widget_shopping_cart';
		$cart_action  = molla_option( 'cart_canvas_open' );
		if ( $cart_canvas ) {
			$cart_classes .= ' cart-canvas canvas-container';
			$cart_classes .= $cart_action ? ' after-added-product' : ' cart-link-click';
		} else {
			$cart_classes .= ' dropdown-menu with-arrows';
		}
		?>
		<div class="shop-icon dropdown cart cart-dropdown <?php echo esc_attr( $cls . ( molla_option( 'shop_icon_cart_price_show' ) ? '' : ' price-hidden' ) ); ?>">
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'cart' ) : '#' ); ?>" class="dropdown-toggle">
				<div class="icon">
					<i class="<?php echo esc_attr( $cart_class ? $cart_class : 'icon-shopping-cart' ); ?>"></i>
					<?php if ( 'count-linear' != $shop_icon_type ) : ?>
						<span class="cart-count">0</span>
					<?php endif; ?>
				</div>
				<p class="custom-label"><?php echo esc_html( molla_option( 'shop_icon_label_cart' ) ); ?></p>
			</a>
			<?php if ( 'count-linear' == $shop_icon_type ) : ?>
				<span class="cart-count">0</span>
			<?php endif; ?>
			<span class="cart-price"></span>
			<div class="<?php echo esc_attr( $cart_classes ); ?>">
			<?php if ( $cart_canvas ) : ?>
				<div class="cart-canvas-header">
					<h4><?php esc_html_e( 'Shopping Cart', 'molla' ); ?></h4>
					<a href="#" class="canvas-close"><?php esc_html_e( 'Close', 'molla' ); ?><i class="icon-close"></i></a>
				</div>
			<?php endif; ?>
				<div class="widget_shopping_cart_content">
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<div class="cart-loading"></div>
				<?php else : ?>
					<ul class="cart_list"><li class="empty"><?php esc_html_e( 'Woocommerce is not installed.', 'molla' ); ?></li></ul>
				<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		if ( $cart_canvas ) {
			echo '<div class="sidebar-overlay canvas-overlay"></div>';
		}
	} elseif ( 'wishlist' == $elem ) {
		if ( class_exists( 'YITH_WCWL' ) ) :
			$wc_link  = YITH_WCWL()->get_wishlist_url();
			$wc_count = yith_wcwl_count_products();
			?>
			<div class="shop-icon wishlist <?php echo esc_attr( $cls ); ?>">
				<a href="<?php echo esc_url( $wc_link ); ?>">
					<div class="icon">
						<i class="<?php echo esc_attr( $wishlist_class ? $wishlist_class : 'icon-heart-o' ); ?>"></i>

						<?php if ( 'count-linear' != $shop_icon_type ) : ?>
							<span class="wishlist-count"><?php echo esc_html( $wc_count ); ?></span>
						<?php endif; ?>
					</div>
					<p class="custom-label"><?php echo esc_html( molla_option( 'shop_icon_label_wishlist' ) ); ?></p>
				</a>
				<?php if ( 'count-linear' == $shop_icon_type ) : ?>
					<span class="wishlist-count"><?php echo esc_html( $wc_count ); ?></span>
				<?php endif; ?>
			</div>
			<?php
		endif;
	}
}
?>
</div>
<?php
