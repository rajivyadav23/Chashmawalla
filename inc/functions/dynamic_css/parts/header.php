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

$directions = array(
	'top',
	$right,
	'bottom',
	$left,
);

$logo_width              = molla_option( 'logo_width' );
$logo_width_sticky       = molla_option( 'logo_width_sticky' );
$logo_max_width          = molla_option( 'logo_max_width' );
$logo_max_width_sticky   = molla_option( 'logo_max_width_sticky' );
$header_color            = molla_option( 'header_color' );
$header_top_color        = molla_option( 'header_top_color' );
$header_main_color       = molla_option( 'header_main_color' );
$header_bottom_color     = molla_option( 'header_bottom_color' );
$header_top_h            = molla_option( 'header_top_height' );
$header_main_h           = molla_option( 'header_main_height' );
$header_bottom_h         = molla_option( 'header_bottom_height' );
$header_top_sticky_h     = molla_option( 'header_top_sticky_height' );
$header_main_sticky_h    = molla_option( 'header_main_sticky_height' );
$header_bottom_sticky_h  = molla_option( 'header_bottom_sticky_height' );
$shop_icon_spacing       = molla_option( 'shop_icons_spacing' );
$divider_color           = array();
$divider_color['global'] = molla_option( 'header_divider_color' );
$logo_spacing            = molla_option( 'logo_spacing' );
$logo_spacing_sticky     = molla_option( 'logo_spacing_sticky' );

if ( $shop_icon_spacing && ! trim( preg_replace( '/(|-)[0-9.]/', '', $shop_icon_spacing ) ) ) {
	$shop_icon_spacing .= 'px';
}

$dimensions = array(
	'logo_spacing'        => '',
	'logo_spacing_sticky' => '',
);

foreach ( $directions as $key ) {
	$logo        = $logo_spacing[ $key ];
	$logo_sticky = $logo_spacing_sticky[ $key ];
	if ( '' == $logo ) {
		$logo = 0;
	}
	if ( '' == $logo_sticky ) {
		$logo_sticky = 0;
	}
	if ( $logo ) {
		$unit = trim( preg_replace( '/(|-)[0-9.]/', '', $logo ) );
		if ( ! $unit ) {
			$logo .= 'px';
		}
	}
	if ( $logo_sticky ) {
		$unit = trim( preg_replace( '/(|-)[0-9.]/', '', $logo_sticky ) );
		if ( ! $unit ) {
			$logo_sticky .= 'px';
		}
	}
	$dimensions['logo_spacing']        .= $logo . ' ';
	$dimensions['logo_spacing_sticky'] .= $logo_sticky . ' ';
}

$backgrounds = array(
	'.header'              => 'header_bg',
	'.header-top'          => 'header_top_bg',
	'.header-main'         => 'header_main_bg',
	'.header-bottom'       => 'header_bottom_bg',
	'.header-top.fixed'    => 'header_top_bg_sticky',
	'.header-main.fixed'   => 'header_main_bg_sticky',
	'.header-bottom.fixed' => 'header_bottom_bg_sticky',
);
if ( ! isset( molla_option( 'header_bg' )['background-image'] ) || ! molla_option( 'header_bg' )['background-image'] ) {
	echo '.sticky-wrapper { background-color: inherit; }';
}
$header_elems = array(
	'top',
	'main',
	'bottom',
);
foreach ( $header_elems as $elem ) {
	$divider_color[ $elem ] = molla_option( 'header_' . $elem . '_divider_color' );
}
foreach ( $backgrounds as $selector => $setting ) :
	$bg = molla_option( $setting );
	if ( false !== strpos( $selector, 'fixed' ) ) :
		?>
	@media (min-width: 992px) {
		<?php
	endif;
		echo esc_html( $selector );
	?>
		{
			<?php if ( ! empty( $bg['background-color'] ) ) : ?>
			background-color: <?php echo esc_attr( $bg['background-color'] ); ?>;
			<?php elseif ( false === strpos( $selector, 'fixed' ) ) : ?>
				<?php if ( 0 == strpos( $selector, '.header-' ) ) : ?>
			background-color: inherit;
				<?php else : ?>
			background-color: transparent;
				<?php endif; ?>
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

		if ( false !== strpos( $selector, 'fixed' ) ) :
			?>
	}
			<?php
	endif;
endforeach;
?>

.header .header-col .logo {
	margin: <?php echo esc_attr( $dimensions['logo_spacing'] ); ?>;
}
@media (min-width: 992px) {
	.header .fixed .logo {
		margin: <?php echo esc_attr( $dimensions['logo_spacing_sticky'] ); ?>;
	}
	<?php if ( $logo_width_sticky ) : ?>
	.sticky-header.fixed .logo img {
		width: <?php echo esc_attr( $logo_width_sticky ); ?>px;
	}
	<?php endif; ?>
	<?php if ( $logo_max_width_sticky ) : ?>
	.sticky-header.fixed .logo img {
		width: <?php echo esc_attr( $logo_max_width_sticky ); ?>px;
	}
	<?php endif; ?>
}


.logo img {
	<?php if ( $logo_width ) : ?>
		width: <?php echo esc_attr( $logo_width ); ?>px;
	<?php endif; ?>

	<?php if ( $logo_max_width ) : ?>
		max-width: <?php echo esc_attr( $logo_max_width ); ?>px;
	<?php endif; ?>
}

<?php if ( $header_color ) : ?>
	.header {
		color: <?php echo esc_attr( $header_color ); ?>;
	}
<?php endif; ?>

.header-top {
	<?php if ( $header_top_color ) : ?>
		color: <?php echo esc_attr( $header_top_color ); ?>;
	<?php endif; ?>
}

.header-top .inner-wrap {
	padding-top: <?php echo intval( $header_top_h ); ?>px;
	padding-bottom: <?php echo intval( $header_top_h ); ?>px;
}

@media (min-width: 992px) {
	.header-top.fixed .inner-wrap {
		padding-top: <?php echo intval( $header_top_sticky_h ); ?>px;
		padding-bottom: <?php echo intval( $header_top_sticky_h ); ?>px;
	}

	.header-main.fixed .inner-wrap {
		padding-top: <?php echo intval( $header_main_sticky_h ); ?>px;
		padding-bottom: <?php echo intval( $header_main_sticky_h ); ?>px;
	}

	.header-bottom.fixed .inner-wrap {
		padding-top: <?php echo intval( $header_bottom_sticky_h ); ?>px;
		padding-bottom: <?php echo intval( $header_bottom_sticky_h ); ?>px;
	}

}

.header-main {
	<?php if ( $header_main_color ) : ?>
		color: <?php echo esc_attr( $header_main_color ); ?>;
	<?php endif; ?>
}

.header-main .inner-wrap {
	padding-top: <?php echo intval( $header_main_h ); ?>px;
	padding-bottom: <?php echo intval( $header_main_h ); ?>px;
}

.header-bottom {
	<?php if ( $header_bottom_color ) : ?>
		color: <?php echo esc_attr( $header_bottom_color ); ?>;
	<?php endif; ?>
}

.header-bottom .inner-wrap {
	padding-top: <?php echo intval( $header_bottom_h ); ?>px;
	padding-bottom: <?php echo intval( $header_bottom_h ); ?>px;
}

.header.divider-active .inner-wrap,
.header.divider-active .header-top,
.header.divider-active .header-main,
.header.divider-active .header-bottom {
	border-color: <?php echo esc_attr( $divider_color['global'] ); ?>;
}
<?php foreach ( $header_elems as $elem ) : ?>
	<?php if ( $divider_color[ $elem ] ) : ?>
	.header.divider-active .header-<?php echo esc_html( $elem ); ?> .inner-wrap,
	.header.divider-active .header-<?php echo esc_html( $elem ); ?> {
		border-color: <?php echo esc_attr( $divider_color[ $elem ] ); ?>;
	}
	<?php endif; ?>
<?php endforeach; ?>

.header-search .search-wrapper {
<?php if ( ! molla_option( 'header_search_border' ) ) : ?>
	border: none;
<?php endif; ?>
<?php
$dimensions = array(
	'top',
	$right,
	'bottom',
	$left,
);
foreach ( $dimensions as $dim ) :
	$w = molla_option( 'header_search_border_width' )[ $dim ];
	if ( '' !== $w ) :
		if ( $w ) {
			if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $w ) ) ) {
				$w .= 'px';
			}
		}
		?>
	border-<?php echo esc_attr( $dim ); ?>-width: <?php echo esc_attr( $w ); ?>;
		<?php
	endif;
endforeach;

$border_color = molla_option( 'header_search_border_color' );
?>
	border-color: <?php echo esc_attr( $border_color ? $border_color : 'transparent' ); ?>;
}

<?php
$border_radius = molla_option( 'header_search_border_radius' );

if ( $border_radius ) {
	if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $border_radius ) ) ) {
		$border_radius .= 'px';
	}
}
if ( $border_radius ) :
	?>
.header-search .search-wrapper,
.header-search .form-control {
	border-radius: <?php echo esc_attr( $border_radius ); ?>;
}
.header-search .btn {
	<?php if ( $rtl ) : ?>
	border-radius: <?php echo esc_attr( $border_radius ) . ' 0 0 ' . esc_attr( $border_radius ); ?>;
	<?php else : ?>
	border-radius: 0 <?php echo esc_attr( $border_radius ) . ' ' . esc_attr( $border_radius ); ?> 0;
	<?php endif; ?>
}
.header-search .icon-left .btn {
	<?php if ( $rtl ) : ?>
	border-radius: 0 <?php echo esc_attr( $border_radius ) . ' ' . esc_attr( $border_radius ); ?> 0;
	<?php else : ?>
	border-radius: <?php echo esc_attr( $border_radius ) . ' 0 0 ' . esc_attr( $border_radius ); ?>;
	<?php endif; ?>
}
<?php endif; ?>

.shop-icon + .shop-icon {
	margin-<?php echo molla_filter_output( $left ); ?>: <?php echo esc_attr( $shop_icon_spacing ? $shop_icon_spacing : '3rem' ); ?>;
}

.header .shop-icons .divider {
	margin: 0 <?php echo esc_attr( $shop_icon_spacing ? $shop_icon_spacing : '3rem' ); ?>;
}

<?php
// Side Header
$header_side          = molla_option( 'header_side' );
$gutter_escaped       = (int) molla_option( 'grid_gutter_width' );
$container_width      = molla_option( 'container_width' );
$container_width      = $container_width ? (int) $container_width : 1188;
$header_side_width_sm = (int) apply_filters( 'header_side_width_sm', 240 );
$header_side_width_lg = (int) apply_filters( 'header_side_width_lg', 300 );

if ( ! empty( $header_side ) ) :
	?>
	@media (min-width: 992px) {
		.header-<?php echo 'top' == $header_side ? 'main, .header-bottom' : ( 'main' == $header_side ? 'top, .header-bottom' : 'top, .header-main' ); ?> {
			position: relative;
			z-index: 1;
		}
		.header-side {
			width: <?php echo molla_filter_output( $header_side_width_sm + $gutter_escaped ); ?>px;
		}
		.sticky-bar.fixed,
		.header-side .menu > li > .sub-menu {
			<?php echo molla_filter_output( $left . ':' . ( $header_side_width_sm + $gutter_escaped ) ); ?>px;
		}
		.sticky-bar.fixed {
			width: auto;
		}
		.main {
			margin-<?php echo molla_filter_output( $left . ':' . $header_side_width_sm ); ?>px;
			width: auto;
		}
		.footer {
			width: auto;
			margin-<?php echo molla_filter_output( $left . ':' . ( $header_side_width_sm + $gutter_escaped ) ); ?>px;
		}
	}

	@media (min-width: 1200px) {
		.header-side {
			width: <?php echo molla_filter_output( $header_side_width_lg + $gutter_escaped ); ?>px;
		}
		.sticky-bar.fixed,
		.header-side .menu > li > .sub-menu {
			<?php echo molla_filter_output( $left . ':' . ( $header_side_width_lg + $gutter_escaped ) ); ?>px;
		}
		.main {
			margin-<?php echo molla_filter_output( $left . ':' . $header_side_width_lg ); ?>px;
		}
		.footer {
			margin-<?php echo molla_filter_output( $left . ':' . ( $header_side_width_lg + $gutter_escaped ) ); ?>px;
		}
	}

	@media (min-width: <?php echo ( molla_filter_output( $container_width ) + $gutter_escaped + 1 ); ?>px) {
		.header-side {
			width: calc(50% - <?php echo molla_filter_output( $container_width ); ?>px / 2 + <?php echo molla_filter_output( $header_side_width_lg + $gutter_escaped / 2 ); ?>px);
			padding-<?php echo molla_filter_output( $left ); ?>: calc((100% - <?php echo molla_filter_output( $container_width ); ?>px) / 2);
		}
		.main {
			margin-<?php echo molla_filter_output( $left ); ?>: calc((100% - <?php echo molla_filter_output( $container_width ); ?>px) / 2 + <?php echo molla_filter_output( $header_side_width_lg ); ?>px);
			margin-<?php echo molla_filter_output( $right ); ?>: calc((100% - <?php echo molla_filter_output( $container_width ); ?>px) / 2);
		}
		.footer {
			margin-<?php echo molla_filter_output( $left ); ?>: calc((100% - <?php echo molla_filter_output( $container_width ); ?>px) / 2 + <?php echo molla_filter_output( $header_side_width_lg + $gutter_escaped / 2 ); ?>px);
		}
		.header-side .menu > li > .sub-menu {
			<?php echo molla_filter_output( $left ); ?>: calc((100% - <?php echo molla_filter_output( $container_width ); ?>px) / 2 + <?php echo molla_filter_output( $header_side_width_lg + $gutter_escaped / 2 ); ?>px);
		}
	}
<?php endif; ?>
