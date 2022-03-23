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

$base_font        = molla_option( 'font_base' );
$base_font_mobile = molla_option( 'font_base_mobile' );
$heading_space    = molla_option( 'font_heading_spacing' );
$para_font        = molla_option( 'font_paragraph' );
$para_space       = molla_option( 'font_paragraph_spacing' );
$nav_font_color   = molla_option( 'font_nav_color' );
$nav_space        = molla_option( 'font_nav_spacing' );
$placeholder      = molla_option( 'font_placeholder' );

$directions = array(
	'top',
	$right,
	'bottom',
	$left,
);

$dimensions = array(
	'heading'   => '',
	'paragraph' => '',
	'nav'       => '',
);

$heading_font = [];
for ( $i = 0; $i < 6; $i ++ ) {
	$heading_font[ $i ] = molla_option( 'font_heading_h' . ( $i + 1 ) );
}

$margin = '';
foreach ( $directions as $key ) {
	$heading = $heading_space[ 'margin-' . $key ];
	$para    = $para_space[ 'margin-' . $key ];
	$nav     = $nav_space[ 'padding-' . $key ];
	if ( '' == $heading ) {
		$heading = 0;
		if ( 'bottom' == $key ) {
			$heading = '1.4rem';
		}
	}
	if ( '' == $para ) {
		$para = 0;
		if ( 'bottom' == $key ) {
			$para = '1.5rem';
		}
	}
	if ( '' == $nav ) {
		$nav = 0;
	}
	if ( $heading ) {
		if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $heading ) ) ) {
			$heading .= 'px';
		}
	}
	if ( $para ) {
		if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $para ) ) ) {
			$para .= 'px';
		}
	}
	if ( $nav ) {
		if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $nav ) ) ) {
			$nav .= 'px';
		}
	}
	$dimensions['heading']   .= $heading . ' ';
	$dimensions['paragraph'] .= $para . ' ';
	$dimensions['nav']       .= $nav . ' ';
}


?>
html {
	font-size: <?php echo esc_attr( $base_font['font-size'] ? $base_font['font-size'] : '10px' ); ?>;
	line-height: <?php echo esc_attr( '' !== $base_font['line-height'] ? $base_font['line-height'] : '1.86' ); ?>;
	letter-spacing: <?php echo esc_attr( '' !== $base_font['letter-spacing'] ? $base_font['letter-spacing'] : '0' ); ?>;
}
@media (max-width: 575px) {
	html {
		font-size: <?php echo esc_attr( $base_font_mobile['font-size'] ? $base_font_mobile['font-size'] : '9px' ); ?>;
		line-height: <?php echo esc_attr( '' !== $base_font_mobile['line-height'] ? $base_font_mobile['line-height'] : '1.3' ); ?>;
		letter-spacing: <?php echo esc_attr( '' !== $base_font_mobile['letter-spacing'] ? $base_font_mobile['letter-spacing'] : '0' ); ?>;
	}
}
body {
	font-family: "<?php echo esc_attr( $base_font['font-family'] ); ?>";
	font-weight: <?php echo esc_attr( 'regular' == $base_font['font-weight'] ? 400 : $base_font['font-weight'] ); ?>;
	color: <?php echo esc_attr( $base_font['color'] ); ?>;
<?php if ( $base_font['text-transform'] ) : ?>
	text-transform: <?php echo esc_attr( $base_font['text-transform'] ); ?>;
<?php endif; ?>
}
<?php for ( $i = 0; $i < 6; $i ++ ) : ?>
h<?php echo intval( $i + 1 ); ?> {
	<?php echo molla_dynamic_typography( $heading_font[ $i ], true ); ?>
	margin: <?php echo esc_attr( $dimensions['heading'] ); ?>;
}
.elementor-widget-heading h<?php echo intval( $i + 1 ); ?>.elementor-heading-title {
	line-height: <?php echo esc_attr( $heading_font[ $i ]['line-height'] ); ?>;
}
<?php endfor; ?>
p {
	<?php echo molla_dynamic_typography( $para_font, true ); ?>
	margin: <?php echo esc_attr( $dimensions['paragraph'] ); ?>;
}
.elementor-widget-heading p.elementor-heading-title {
	line-height: <?php echo esc_attr( $para_font['line-height'] ); ?>;
}
a {
	color: <?php echo esc_attr( $nav_font_color ? $nav_font_color : 'inherit' ); ?>;
	padding: <?php echo esc_attr( $dimensions['nav'] ); ?>;
}
input::placeholder,
textarea::placeholder {
	<?php echo molla_dynamic_typography( $placeholder, true ); ?>
}
input::-ms-input-placeholder,
textarea::-ms-input-placeholder {
	<?php echo molla_dynamic_typography( $placeholder, true ); ?>
}
