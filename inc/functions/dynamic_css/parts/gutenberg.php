<?php

if ( function_exists( 'molla_option' ) ) :

	include MOLLA_LIB . '/functions/dynamic_css/parts/color.php';
	include MOLLA_LIB . '/functions/dynamic_css/parts/layout.php';

	$gutter           = molla_option( 'grid_gutter_width' );
	$width            = molla_option( 'container_width' );
	$base_font        = molla_option( 'font_base' );
	$base_font_mobile = molla_option( 'font_base_mobile' );
	$primary          = molla_option( 'primary_color' );
	$second           = molla_option( 'primary_color' );
	?>

	.edit-post-visual-editor.editor-styles-wrapper a {
		color: <?php echo esc_attr( $primary ); ?>;
	}

	.wp-block[data-align="wide"] {
		width: <?php echo esc_attr( $width ? $width : '1188px' ); ?>;
		max-width: 100%;
		margin-left: auto;
		margin-right: auto;
	}
	.wp-block-columns.block-editor-block-list__layout {
		margin-left: -<?php echo intval( $gutter ) / 2; ?>px;
		margin-right: -<?php echo intval( $gutter ) / 2; ?>px;
		max-width: calc(100% + <?php echo intval( $gutter ); ?>px);
	}

	html {
		font-size: <?php echo esc_attr( $base_font['font-size'] ? $base_font['font-size'] : '10px' ); ?>;
		line-height: <?php echo esc_attr( '' !== $base_font['line-height'] ? $base_font['line-height'] : '1.86' ); ?>;
		letter-spacing: <?php echo esc_attr( '' !== $base_font['letter-spacing'] ? $base_font['letter-spacing'] : '0' ); ?>;
	}
	.editor-styles-wrapper .mce-content-body {
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

	.wp-block-columns>.block-editor-inner-blocks>.block-editor-block-list__layout,
	.owl-carousel .block-editor-block-list__layout,
	.product-list,
	figure.wp-block-gallery {
		margin-left: -<?php echo intval( $gutter ) / 2; ?>px;
		margin-right: -<?php echo intval( $gutter ) / 2; ?>px;
		width: auto;
		max-width: calc(100% + <?php echo intval( $gutter ); ?>px);
	}
	.wp-block-columns>.block-editor-inner-blocks>.block-editor-block-list__layout>[data-type="core/column"],
	.product-list > * {
		padding-left: <?php echo intval( $gutter ) / 2; ?>px;
		padding-right: <?php echo intval( $gutter ) / 2; ?>px;
	}
	.wp-block-gallery .blocks-gallery-item {
		padding: <?php echo intval( $gutter ) / 2; ?>px;
	}
	.icon-box.icon-box-bordered:before {
		right: -<?php echo intval( $gutter ) / 2; ?>px;
	}
	.btn {
		background-color: <?php echo esc_attr( $primary ); ?>;
		border: 1px solid <?php echo esc_attr( $primary ); ?>;
	}
	.btn-outline,
	.btn-outline:hover,
	.btn-outline:focus,
	.btn-link {
		color: <?php echo esc_attr( $primary ); ?>;
	}
	.btn-link:hover,
	.btn-link:focus {
		color: <?php echo esc_attr( $primary ); ?>;
		border-color: <?php echo esc_attr( $primary ); ?>;
	}
	.editor-styles-wrapper blockquote,
	.wp-block-freeform.block-library-rich-text__tinymce blockquote {
		border-width: 0 0 0 4px;
		border-color: <?php echo esc_attr( $primary ); ?>;
	}

	<?php
	$heading_space = molla_option( 'font_heading_spacing' );
	$para_font     = molla_option( 'font_paragraph' );
	$para_space    = molla_option( 'font_paragraph_spacing' );
	$nav_space     = molla_option( 'font_nav_spacing' );
	$placeholder   = molla_option( 'font_placeholder' );

	if ( is_rtl() ) {
		$left  = 'right';
		$right = 'left';
	} else {
		$left  = 'left';
		$right = 'right';
	}

	$directions = array(
		'top',
		$right,
		'bottom',
		$left,
	);

	$margin     = '';
	$dimensions = array(
		'heading'   => '',
		'paragraph' => '',
		'nav'       => '',
	);
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


	$heading_font = [];
	for ( $i = 0; $i < 6; $i ++ ) {
		$heading_font[ $i ] = molla_option( 'font_heading_h' . ( $i + 1 ) );
	}
	?>
	<?php for ( $i = 0; $i < 6; $i ++ ) : ?>
	.editor-styles-wrapper .block-editor-block-list__layout h<?php echo intval( $i + 1 ); ?> {
		<?php echo molla_dynamic_typography( $heading_font[ $i ], true ); ?>
		margin: <?php echo esc_attr( $dimensions['heading'] ); ?>;
	}
	<?php endfor; ?>
	.editor-styles-wrapper .block-editor-block-list__layout p {
		<?php echo molla_dynamic_typography( $para_font, true ); ?>
		margin: <?php echo esc_attr( $dimensions['paragraph'] ); ?>;
	}
	.editor-styles-wrapper a {
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

	.editor-styles-wrapper [data-block] {
		margin-bottom: <?php echo (int) $gutter / 2; ?>px;
	}
	<?php
endif;
?>
