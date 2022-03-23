<?php

if ( ( isset( $is_rtl ) && $is_rtl ) || ( ! isset( $is_rtl ) && is_rtl() ) ) {
	$rtl = true;
} else {
	$rtl = false;
}

$skins = array(
	'skin1',
	'skin2',
	'skin3',
);

$dimensions = array(
	'top',
	'right',
	'bottom',
	'left',
);

foreach ( $skins as $skin ) {
	$bg                 = molla_option( $skin . '_menu_bg' );
	$dropdown_bg        = molla_option( $skin . '_menu_dropdown_bg' );
	$menu_divider_color = molla_option( $skin . '_menu_divider_color' );
	$font_ancestor      = molla_option( $skin . '_menu_ancestor_font' );
	$ancestor_color     = molla_option( $skin . '_menu_ancestor_color' );
	$ancestor_margin    = molla_option( $skin . '_menu_ancestor_margin' );
	$ancestor_padding   = molla_option( $skin . '_menu_ancestor_padding' );
	$font_subtitle      = molla_option( $skin . '_menu_subtitle_font' );
	$subtitle_color     = molla_option( $skin . '_menu_subtitle_color' );
	$subtitle_margin    = molla_option( $skin . '_menu_subtitle_margin' );
	$subtitle_padding   = molla_option( $skin . '_menu_subtitle_padding' );
	$font_menu_item     = molla_option( $skin . '_menu_item_font' );
	$menu_item_color    = molla_option( $skin . '_menu_item_color' );
	$menu_item_margin   = molla_option( $skin . '_menu_item_margin' );
	$menu_item_padding  = molla_option( $skin . '_menu_item_padding' );

	if ( isset( $bg['background-color'] ) || isset( $bg['background-image'] ) ) :
		?>
		<?php echo '.menu-' . $skin; ?> {
			<?php if ( isset( $bg['background-color'] ) && $bg['background-color'] ) : ?>
			background-color: <?php echo esc_attr( $bg['background-color'] ); ?>;
			<?php else : ?>
			background-color: transparent;
			<?php endif; ?>
			<?php if ( isset( $bg['background-image'] ) && $bg['background-image'] ) : ?>
			background-image: url('<?php echo esc_url( $bg['background-image'] ); ?>');
			background-repeat: <?php echo esc_attr( $bg['background-repeat'] ? ( 'repeat-all' == $bg['background-repeat'] ? 'repeat' : $bg['background-repeat'] ) : 'no-repeat' ); ?>;
			background-position: <?php echo esc_attr( $bg['background-position'] ? $bg['background-position'] : 'left top' ); ?>;
				<?php if ( $bg['background-size'] ) : ?>
			background-size: <?php echo esc_attr( $bg['background-size'] ); ?>;
				<?php endif; ?>
				<?php if ( $bg['background-attachment'] ) : ?>
			background-attachment: <?php echo esc_attr( $bg['background-attachment'] ); ?>;
				<?php endif; ?>
			<?php endif; ?>
		}
		<?php
	endif;

	if ( isset( $dropdown_bg['background-color'] ) || isset( $dropdown_bg['background-image'] ) ) :
		?>
		<?php echo '.menu-' . $skin . ' .sub-menu'; ?> {
			<?php if ( isset( $dropdown_bg['background-color'] ) && $dropdown_bg['background-color'] ) : ?>
			background-color: <?php echo esc_attr( $dropdown_bg['background-color'] ); ?>;
			<?php else : ?>
			background-color: transparent;
			<?php endif; ?>
			<?php if ( isset( $dropdown_bg['background-image'] ) && $dropdown_bg['background-image'] ) : ?>
			background-image: url('<?php echo esc_url( $dropdown_bg['background-image'] ); ?>');
			background-repeat: <?php echo esc_attr( $dropdown_bg['background-repeat'] ? ( 'repeat-all' == $dropdown_bg['background-repeat'] ? 'repeat' : $dropdown_bg['background-repeat'] ) : 'no-repeat' ); ?>;
			background-position: <?php echo esc_attr( $dropdown_bg['background-position'] ? $dropdown_bg['background-position'] : 'left top' ); ?>;
				<?php if ( $dropdown_bg['background-size'] ) : ?>
			background-size: <?php echo esc_attr( $dropdown_bg['background-size'] ); ?>;
				<?php endif; ?>
				<?php if ( $dropdown_bg['background-attachment'] ) : ?>
			background-attachment: <?php echo esc_attr( $dropdown_bg['background-attachment'] ); ?>;
				<?php endif; ?>
			<?php endif; ?>
		}
		<?php
	endif;
	?>
	.menu-<?php echo esc_html( $skin ); ?> li > a {
		<?php echo molla_dynamic_typography( $font_menu_item, true ); ?>
		<?php
		foreach ( $dimensions as $d ) :
			if ( '' !== $menu_item_margin[ $d ] ) :
				if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $menu_item_margin[ $d ] ) ) ) :
					$menu_item_margin[ $d ] .= 'px';
				endif;
				?>
			margin-<?php echo esc_attr( molla_rtl_params( $rtl, $d ) ); ?>: <?php echo esc_attr( $menu_item_margin[ $d ] ); ?>;
				<?php
			endif;
		endforeach;
		foreach ( $dimensions as $d ) :
			if ( '' !== $menu_item_padding[ $d ] ) :
				if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $menu_item_padding[ $d ] ) ) ) :
					$menu_item_padding[ $d ] .= 'px';
				endif;
				?>
			padding-<?php echo esc_attr( molla_rtl_params( $rtl, $d ) ); ?>: <?php echo esc_attr( $menu_item_padding[ $d ] ); ?>;
				<?php
			endif;
		endforeach;
		?>
	}

	.menu-<?php echo esc_html( $skin ); ?> > .menu-item > a {
		<?php echo molla_dynamic_typography( $font_ancestor, true ); ?>
		<?php
		foreach ( $dimensions as $d ) :
			if ( '' !== $ancestor_margin[ $d ] ) :
				if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $ancestor_margin[ $d ] ) ) ) :
					$ancestor_margin[ $d ] .= 'px';
				endif;
				?>
			margin-<?php echo esc_attr( molla_rtl_params( $rtl, $d ) ); ?>: <?php echo esc_attr( $ancestor_margin[ $d ] ); ?>;
				<?php
			endif;
		endforeach;
		foreach ( $dimensions as $d ) :
			if ( '' !== $ancestor_padding[ $d ] ) :
				if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $ancestor_padding[ $d ] ) ) ) :
					$ancestor_padding[ $d ] .= 'px';
				endif;
				?>
			padding-<?php echo esc_attr( molla_rtl_params( $rtl, $d ) ); ?>: <?php echo esc_attr( $ancestor_padding[ $d ] ); ?>;
				<?php
			endif;
		endforeach;
		?>
	}
	
	<?php if ( $menu_item_color ) : ?>
	.menu-<?php echo esc_html( $skin ); ?> li > a:hover,
	.menu-<?php echo esc_html( $skin ); ?> .megamenu li:not(.menu-item-has-children):hover > a {
		color: <?php echo esc_attr( $menu_item_color ); ?>;
	}
	<?php endif; ?>

	.menu.menu-<?php echo esc_html( $skin ); ?> .menu-subtitle > a {
		<?php echo molla_dynamic_typography( $font_subtitle, true ); ?>
		<?php
		foreach ( $dimensions as $d ) :
			if ( '' !== $subtitle_margin[ $d ] ) :
				if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $subtitle_margin[ $d ] ) ) ) :
					$subtitle_margin[ $d ] .= 'px';
				endif;
				?>
			margin-<?php echo esc_attr( molla_rtl_params( $rtl, $d ) ); ?>: <?php echo esc_attr( $subtitle_margin[ $d ] ); ?>;
				<?php
			endif;
		endforeach;
		foreach ( $dimensions as $d ) :
			if ( '' !== $subtitle_padding[ $d ] ) :
				if ( ! trim( preg_replace( '/(|-)[0-9.]/', '', $subtitle_padding[ $d ] ) ) ) :
					$subtitle_padding[ $d ] .= 'px';
				endif;
				?>
			padding-<?php echo esc_attr( molla_rtl_params( $rtl, $d ) ); ?>: <?php echo esc_attr( $subtitle_padding[ $d ] ); ?>;
				<?php
			endif;
		endforeach;
		?>
	}
	
	<?php if ( $subtitle_color ) : ?>
	.menu.menu-<?php echo esc_html( $skin ); ?> .menu-subtitle > a:hover {
		color: <?php echo esc_attr( $subtitle_color ); ?>;
	}
	<?php endif; ?>

	.menu-<?php echo esc_html( $skin ); ?>.sf-dividers ul a {
		border-color: <?php echo esc_attr( $menu_divider_color ); ?>;
	}
	<?php if ( $ancestor_color ) : ?>
	.menu-<?php echo esc_html( $skin ); ?> > li.current-menu-item > a,
	.menu-<?php echo esc_html( $skin ); ?> > li.current-menu-ancestor > a,
	.menu-<?php echo esc_html( $skin ); ?> > li > a:hover,
	.menu-<?php echo esc_html( $skin ); ?> > li:hover > a {
		color: <?php echo esc_attr( $ancestor_color ); ?>;
	}
	.menu-<?php echo esc_html( $skin ); ?> > li > a:before {
		background-color: <?php echo esc_attr( $ancestor_color ); ?>;
	}
	<?php endif;
}
?>
