<?php
$items  = molla_option( 'footer_bottom_items' );
$length = count( $items );
$active = false;

if ( ( in_array( 'custom_html', $items ) && molla_option( 'footer_custom_html' ) ) ||
	( in_array( 'payments', $items ) && molla_option( 'footer_payment' ) ) ||
	( in_array( 'widget', $items ) && is_active_sidebar( 'footer-bottom-widget' ) ) ) {

	$active = true;
}
if ( $active ) :
	?>
<div class="footer-bottom<?php echo esc_attr( molla_option( 'footer_bottom_divider_active' ) ? ( molla_option( 'footer_bottom_divider_width' ) ? ' full-divider' : ' content-divider' ) : '' ) . esc_attr( ' ' . molla_option( 'footer_bottom_dir' ) ); ?>">
	<div class="<?php echo esc_attr( isset( $width ) && $width ? ( ' ' . $width ) : '' ); ?>">
		<div class="inner-wrap">
		<?php

		if ( in_array( 'custom_html', $items ) ) :
			$class = 'footer-';
			if ( array_search( 'custom_html', $items ) == 0 ) {
				$class .= 'left';
			} elseif ( array_search( 'custom_html', $items ) == 1 ) {
				$class .= 'center';
			} elseif ( array_search( 'custom_html', $items ) == 2 ) {
				$class .= 'right';
			}

			?>
			<div class="<?php echo esc_attr( $class ); ?>">
				<?php echo do_shortcode( molla_option( 'footer_custom_html' ) ); ?>
			</div>
			<?php
		endif;
		if ( in_array( 'payments', $items ) && molla_option( 'footer_payment' ) ) :
			$class = 'footer-';
			if ( array_search( 'payments', $items ) == 0 ) {
				$class .= 'left';
			} elseif ( array_search( 'payments', $items ) == 1 ) {
				$class .= 'center';
			} elseif ( array_search( 'payments', $items ) == 2 ) {
				$class .= 'right';
			}
			?>
			<div class="<?php echo esc_attr( $class ); ?>">
				<img src="<?php echo esc_attr( molla_option( 'footer_payment' ) ); ?>" class="footer-payments" alt="payments">
			</div>
			<?php
		endif;
		if ( in_array( 'widget', $items ) && is_active_sidebar( 'footer-bottom-widget' ) ) :
			$class = 'footer-';
			if ( array_search( 'widget', $items ) == 0 ) {
				$class .= 'left';
			} elseif ( array_search( 'widget', $items ) == 1 ) {
				$class .= 'center';
			} elseif ( array_search( 'widget', $items ) == 2 ) {
				$class .= 'right';
			}
			?>
			<div class="<?php echo esc_attr( $class ); ?>">
				<?php dynamic_sidebar( 'footer-bottom-widget' ); ?>
			</div>
			<?php
		endif;
		?>
		</div>
	</div>
</div>
	<?php
endif;
