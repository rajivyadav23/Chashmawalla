<?php
$cols        = molla_option( 'footer_main_cols' );
$cols        = str_replace( ' ', '', $cols );
$footer_cols = explode( '+', $cols );
$active      = false;
$cols_count  = count( $footer_cols );

for ( $i = 1; $i <= $cols_count; $i ++ ) {
	if ( is_active_sidebar( 'footer-main-widget-' . $i ) ) {
		$active = true;
		break;
	}
}
if ( $active ) :
	?>
<div class="footer-main<?php echo esc_attr( molla_option( 'footer_main_divider_active' ) ? ( molla_option( 'footer_main_divider_width' ) ? ' full-divider' : ' content-divider' ) : '' ); ?>">
	<div class="<?php echo esc_attr( isset( $width ) && $width ? ( ' ' . $width ) : '' ); ?>">
		<div class="inner-wrap">
			<div class="row">
				<?php
				if ( $cols_count <= 4 ) {
					for ( $i = 1; $i <= $cols_count; ++ $i ) {
						if ( is_active_sidebar( 'footer-main-widget-' . $i ) ) {
							$col_escaped = intval( $footer_cols[ $i - 1 ] );

							echo '<div class="col-lg-' . $col_escaped;
							if ( $col_escaped < 3 ) {
								echo ' col-sm-4';
							} elseif ( $col_escaped < 5 ) {
								echo ' col-sm-6';
							}
							echo '">';

							dynamic_sidebar( 'footer-main-widget-' . $i );

							echo '</div>';
						}
					}
				} else {
					for ( $i = 1; $i <= $cols_count; ++ $i ) {
						if ( is_active_sidebar( 'footer-main-widget-' . $i ) ) {
							$col_escaped = intval( $footer_cols[ $i - 1 ] );

							echo '<div class="col-xl-' . $col_escaped;
							if ( $col_escaped < 3 ) {
								echo ' col-lg-4 col-sm-6';
							} elseif ( $col_escaped < 4 ) {
								echo ' col-lg-6 col-sm-6';
							} elseif ( $col_escaped < 5 ) {
								echo ' col-lg-8 col-md-12';
							} else {
								echo ' col-lg-12';
							}
							echo '">';

							dynamic_sidebar( 'footer-main-widget-' . $i );

							echo '</div>';
						}
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
	<?php
endif;
