<?php

$footer_top_pt    = (int) molla_option( 'footer_top_pt' );
$footer_top_pb    = (int) molla_option( 'footer_top_pb' );
$footer_main_pt   = (int) molla_option( 'footer_main_pt' );
$footer_main_pb   = (int) molla_option( 'footer_main_pb' );
$footer_bottom_pt = (int) molla_option( 'footer_bottom_pt' );
$footer_bottom_pb = (int) molla_option( 'footer_bottom_pb' );
$divider_color    = molla_option( 'footer_divider_color' );

$backgrounds = array(
	'.footer'        => 'footer_bg',
	'.footer-top'    => 'footer_top_bg',
	'.footer-main'   => 'footer_main_bg',
	'.footer-bottom' => 'footer_bottom_bg',
);

foreach ( $backgrounds as $selector => $setting ) :
	$bg = molla_option( $setting );
	if ( ! empty( $bg['background-color'] ) || ! empty( $bg['background-image'] ) ) :
		?>
		<?php echo esc_html( $selector ); ?> {
		<?php if ( ! empty( $bg['background-color'] ) ) : ?>
		background-color: <?php echo esc_attr( $bg['background-color'] ); ?>;
		<?php elseif ( -1 !== strpos( $selector, '.footer-' ) ) : ?>
		background-color: inherit;
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
endforeach;

$fonts = array(
	'footer'                                            => 'footer_font',
	'.footer h1, .footer h2, .footer h3, .footer h4, .footer h5, .footer h6, .footer .widget-title' => 'footer_font_heading',
	'.footer p, .footer .widget li, .footer .menu li a' => 'footer_font_paragraph',
);

foreach ( $fonts as $selector => $setting ) :
	$font = molla_option( $setting );
	?>
	<?php echo esc_html( $selector ); ?> {
		<?php
		foreach ( $font as $key => $value ) :
			if ( 'variant' != $key && 'font-backup' != $key && $value ) {
				echo esc_attr( $key ) . ':' . esc_attr( $value ) . ';';
			}
		endforeach;
		?>
	}
	<?php
endforeach;
?>

.footer-top .inner-wrap {
	padding-top: <?php echo (int) $footer_top_pt; ?>px;
	padding-bottom: <?php echo (int) $footer_top_pb; ?>px;
}

.footer-main .inner-wrap {
	padding-top: <?php echo (int) $footer_main_pt; ?>px;
	padding-bottom: <?php echo (int) $footer_main_pb; ?>px;
}

.footer-bottom .inner-wrap {
	padding-top: <?php echo (int) $footer_bottom_pt; ?>px;
	padding-bottom: <?php echo (int) $footer_bottom_pb; ?>px;
}

.footer.divider-active .inner-wrap,
.footer.divider-active .footer-top,
.footer.divider-active .footer-main,
.footer.divider-active .footer-bottom {
	border-color: <?php echo esc_attr( $divider_color ); ?>;
}
