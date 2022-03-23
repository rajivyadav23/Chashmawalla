<?php
if ( ! molla_option( 'header_initial' ) ) {
	molla_init_header_options();
}
global $molla_settings;

if ( 'header' == get_post_type() ) {
	echo '<header class="custom-header full-inner header-' . get_the_ID() . '" id="header">';
	the_content();
	echo '</header>';
} elseif ( isset( $molla_settings['header']['builder'] ) && function_exists( 'molla_print_custom_post' ) ) {
	echo '<header class="custom-header full-inner header-' . $molla_settings['header']['builder'] . '" id="header">';
	molla_print_custom_post( 'header', $molla_settings['header']['builder'] );
	echo '</header>';
} else {
	$header_op       = $molla_settings['header'];
	$header_side     = molla_option( 'header_side' );
	$header_elements = $header_op['elements'];

	$header_class  = 'header';
	$header_class .= ( $header_op['fixed'] ? ' fixed-header' : '' ) . ( molla_option( 'header_top_divider_active' ) || molla_option( 'header_main_divider_active' ) || molla_option( 'header_bottom_divider_active' ) ? ' divider-active' : '' );

	$header_class = apply_filters( 'molla_header_classes', $header_class );
	?>

<header class="<?php echo esc_attr( $header_class ); ?>">
	<?php

	$row_active = array(
		'desktop' => array(
			'top'    => 0,
			'main'   => 0,
			'bottom' => 0,
		),
		'mobile'  => array(
			'top'    => 0,
			'main'   => 0,
			'bottom' => 0,
		),
	);

	$header_rows    = array( 'top', 'main', 'bottom' );
	$header_columns = array( 'left', 'center', 'right' );
	$mobile_same    = false;

	//search rows which is visible
	foreach ( $header_rows as $row ) {
		foreach ( $header_columns as $col ) {
			if ( isset( $header_elements[ $row . '_' . $col ] ) &&
				! empty( json_decode( $header_elements[ $row . '_' . $col ] ) ) ) {
				$row_active['desktop'][ $row ] = 1;
			}
			if ( isset( $header_elements[ 'mobile_' . $row . '_' . $col ] ) &&
				$header_elements[ 'mobile_' . $row . '_' . $col ] &&
				! empty( json_decode( $header_elements[ 'mobile_' . $row . '_' . $col ] ) ) ) {
				$row_active['mobile'][ $row ] = 1;
			}
		}
	}

	if ( ! in_array( 1, $row_active['mobile'] ) ) {
		foreach ( $header_rows as $row ) {
			$row_active['mobile'][ $row ] = $row_active['desktop'][ $row ];
		}
		$mobile_same = true;
	}

	//display header rows
	foreach ( $header_rows as $row ) {
		$header_has_center        = isset( $header_elements[ $row . '_center' ] ) &&
											$header_elements[ $row . '_center' ] &&
											! empty( json_decode( $header_elements[ $row . '_center' ] ) );
		$mobile_header_has_center = isset( $header_elements[ 'mobile' . $row . '_center' ] ) &&
											$header_elements[ 'mobile' . $row . '_center' ] &&
											! empty( json_decode( $header_elements[ 'mobile' . $row . '_center' ] ) );


		if ( $row_active['desktop'][ $row ] || $row_active['mobile'][ $row ] ) {
			$sticky = $header_op['sticky'][ $row ] && molla_option( 'header_sticky_show' );
			if ( ! molla_option( 'header_initial' ) && 'main' == $row ) {
				$sticky = true;
			}
			echo ( ( '<div class="header-row' . ( $sticky ? ' sticky-wrapper' : '' ) . '">' ) .
				"<div class='header-" . $row . ( $header_has_center ? ' header-has-center' : '' ) .
				( $mobile_header_has_center ? ' header-has-center-mob' : '' ) .
				( $sticky ? ' sticky-header' : '' ) .
				( molla_option( 'header_' . $row . '_divider_active' ) ? ( molla_option( 'header_' . $row . '_divider_width' ) ? ' full-divider' : ' content-divider' ) : '' ) .
				( ( 'top' == $row && 'top' == $header_side ) || ( 'main' == $row && 'main' == $header_side ) || ( 'bottom' == $row && 'bottom' == $header_side ) ? ' header-side' . (
						'expand' == molla_option( 'header_side_menu_type' ) ? ' header-side-menu-expand' : ''
					) : '' ) .
				"'><div class='" . esc_attr( $header_op['width'] ) . "'><div class='inner-wrap'>" );
			foreach ( $header_columns as $col ) {
				$elements        = isset( $header_elements[ $row . '_' . $col ] ) ?
										json_decode( $header_elements[ $row . '_' . $col ] ) : array();
				$mobile_elements = isset( $header_elements[ 'mobile_' . $row . '_' . $col ] ) ?
										json_decode( $header_elements[ 'mobile_' . $row . '_' . $col ] ) : array();
				if ( ! class_exists( 'WooCommerce' ) && is_array( $elements ) && count( $elements ) == 1 && isset( $elements[0]->shop ) ) {
					continue;
				}

				if ( ! empty( $elements ) ) {
					echo "<div class='header-col header-" . $col . ( ! $mobile_same ? ' hidden-mob' : '' ) . "'>";
						molla_header_elements( $elements );
					echo '</div>';
				}
				if ( ! empty( $mobile_elements ) ) {
					echo "<div class='header-col header-" . $col . ' hidden-desktop' . "'>";
						molla_header_elements( $mobile_elements );
					echo '</div>';
				}
			}
			echo '</div></div></div></div>';
		}
	}

	?>
</header>
	<?php

}

if ( ! molla_option( 'header_initial' ) ) {
	set_theme_mod( 'header_initial', true );
}
