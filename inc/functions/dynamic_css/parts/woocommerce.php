<?php

$bg = molla_option( 'woo_account_background' );

if ( ! is_array( $bg ) || empty( $bg['background-image'] ) && empty( $bg['background-color'] ) ) {
	$bg                     = array();
	$bg['background-image'] = MOLLA_URI . '/assets/images/login-bg.jpg';
	$bg['background-size']  = 'cover';
}

if ( ! empty( $bg['background-color'] ) || ! empty( $bg['background-image'] ) ) :
	?>
	.myaccount-content.logged-out {
		<?php if ( ! empty( $bg['background-color'] ) ) : ?>
		background-color: <?php echo esc_attr( $bg['background-color'] ); ?>;
		<?php else : ?>
		background-color: transparent;
		<?php endif; ?>
		<?php if ( ! empty( $bg['background-image'] ) ) : ?>
		background-image: url('<?php echo esc_url( $bg['background-image'] ); ?>');
		background-repeat: <?php echo esc_attr( ! empty( $bg['background-repeat'] ) ? ( 'repeat-all' == $bg['background-repeat'] ? 'repeat' : $bg['background-repeat'] ) : 'no-repeat' ); ?>;
		background-position: <?php echo esc_attr( ! empty( $bg['background-position'] ) ? $bg['background-position'] : 'left top' ); ?>;
			<?php if ( ! empty( $bg['background-size'] ) ) : ?>
		background-size: <?php echo esc_attr( $bg['background-size'] ); ?>;
			<?php endif; ?>
			<?php if ( ! empty( $bg['background-attachment'] ) ) : ?>
		background-attachment: <?php echo esc_attr( $bg['background-attachment'] ); ?>;
			<?php endif; ?>
		<?php endif; ?>
	}
	<?php
endif;

if ( molla_option( 'woo_pre_order' ) ) :
	?>
	.sticky-bar.fixed .molla-pre-order-date { margin: 0 2rem 0 0 }
	.label-pre-order { font-size: .9em; color: <?php echo esc_attr( isset( $colors ) ? $colors['primary_color'] : molla_option( 'primary_color' ) ); ?> }
	<?php
endif;

if ( 'full' == molla_option( 'post_product_type' ) ) :
	?>
.product.product-full .product-body {
	top: 50%;
}
	<?php
endif;

if ( ( isset( $is_rtl ) && $is_rtl ) || ( ! isset( $is_rtl ) && is_rtl() ) ) {
	$left  = 'right';
	$right = 'left';
	$rtl   = true;
} else {
	$left  = 'left';
	$right = 'right';
	$rtl   = false;
}

$product_custom_style = molla_option( 'product_custom_style' );

if ( $product_custom_style ) :
	$fonts = array(
		'font_product_cat'   => '.products .product-cat',
		'font_product_title' => '.products .product-title',
		'font_product_price' => '.products .product-wrap .product .price',
	);

	$new_price_color = molla_option( 'new_price_color' );
	$old_price_color = molla_option( 'old_price_color' );

	$directions = array(
		'top',
		$right,
		'bottom',
		$left,
	);
	$dimensions = array(
		'cat'    => '',
		'title'  => '',
		'price'  => '',
		'rating' => '',
	);
	$spacings   = $dimensions;

	$font_params = array(
		'font-family',
		'font-weight',
		'font-size',
		'font-style',
		'line-height',
		'letter-spacing',
		'color',
		'text-transform',
	);

	foreach ( $directions as $key ) :
		foreach ( $dimensions as $option => $value ) :
			$val = molla_option( 'product_' . $option . '_margin' )[ $key ];
			if ( '' == $val ) {
				$val = 0;
			}
			if ( $val ) {
				if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $val ) ) ) {
					$val .= 'px';
				}
			}
			$spacings[ $option ] .= $val . ' ';
		endforeach;
	endforeach;

	foreach ( $fonts as $setting => $selector ) :
		$font = molla_option( $setting );
		?>
		<?php echo esc_html( $selector ); ?> {
			<?php echo molla_dynamic_typography( $font ); ?>
			margin: <?php echo esc_attr( $spacings[ str_replace( 'font_product_', '', $setting ) ] ); ?>;
		}
		<?php
	endforeach;
	?>
	.products .ratings-container {
		margin: <?php echo esc_attr( $spacings['rating'] ); ?>;
	}
	.products .price ins {
		color: <?php echo esc_attr( molla_option( 'new_price_color' ) ); ?>;
	}
	.products .price del {
		color: <?php echo esc_attr( molla_option( 'old_price_color' ) ); ?>;
	}
	<?php
endif;

if ( molla_option( 'product_rating_icon' ) ) :
	?>
	.woocommerce .product .star-rating:before,
	.woocommerce .product .star-rating span::before {
		content: "\f005" "\f005" "\f005" "\f005" "\f005";
		font-family: 'Font Awesome 5 Free';
		font-weight: 400;
		font-size: 1rem;
		letter-spacing: .4em;
		line-height: 1.4;
	}
	.woocommerce .product .star-rating span::before {
		font-weight: 900;
	}
	<?php
endif;

if ( molla_option( 'prevent_elevatezoom' ) ):
	?>
	@media (max-width: 991px) {
		.zoomContainer,
		.woocommerce-product-gallery__image img {
			pointer-events: none
		}
	}
	<?php
endif;
?>
