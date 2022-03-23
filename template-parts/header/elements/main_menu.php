<?php
if ( has_nav_menu( 'main_menu' ) ) :
	$skin            = molla_option( 'main_menu_skin' );
	$main_menu_class = molla_get_menu_class( $skin );
	$main_menu_class = apply_filters( 'molla_main_menu_class', $main_menu_class );

	wp_nav_menu(
		array(
			'menu_class'     => $main_menu_class,
			'theme_location' => 'main_menu',
		)
	);
endif;
