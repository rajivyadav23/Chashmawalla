<?php
if ( ( isset( $is_rtl ) && $is_rtl ) || ( ! isset( $is_rtl ) && is_rtl() ) ) {
	$left  = 'right';
	$right = 'left';
	$rtl   = true;
} else {
	$left  = 'left';
	$right = 'right';
	$rtl   = false;
}

$fonts = array(
	'font_entry_meta'      => '.posts .entry-meta',
	'font_entry_title'     => '.posts .entry-title',
	'font_entry_cat'       => '.posts .entry-cats',
	'font_entry_excerpt'   => '.posts .entry-content p',
	'font_entry_view_more' => '.read-more',
	'font_single_meta'     => '.post-single > .post .entry-meta',
	'font_single_title'    => '.post-single > .post .entry-title',
	'font_single_cat'      => '.post-single > .post .entry-cats',
);

$directions = array(
	'top',
	$right,
	'bottom',
	$left,
);
$dimensions = array(
	'entry_body_padding' => '',
	'entry_meta'         => '',
	'entry_title'        => '',
	'entry_cat'          => '',
	'entry_excerpt'      => '',
	'entry_view_more'    => '',
	'single_meta'        => '',
	'single_title'       => '',
	'single_cat'         => '',
);
$spacings   = $dimensions;

foreach ( $directions as $key ) :
	foreach ( $dimensions as $option => $value ) :
		if ( false === strpos( $option, 'entry_body' ) ) {
			$val = molla_option( $option . '_margin' )[ $key ];
			if ( '' == $val ) {
				$val = 0;
			}
		} else {
			$val = molla_option( $option )[ $key ];
			if ( '' == $val ) {
				if ( 'bottom' == $key ) {
					$val = '2.5rem';
				} else {
					$val = '2rem';
				}
			}
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
		margin: <?php echo esc_attr( $spacings[ str_replace( 'font_', '', $setting ) ] ); ?>;
	}
	<?php
endforeach;

?>

.posts .entry-body {
	padding: <?php echo esc_attr( $spacings['entry_body_padding'] ); ?>;
}

<?php

if ( ! molla_option( 'blog_shadow_hover' ) ) :
	echo '.posts article.post { box-shadow: none }';
endif;

?>
