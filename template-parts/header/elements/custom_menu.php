<?php
if ( isset( $args ) && $args ) {
	$menu         = $args->html;
	$type         = $args->menu_type;
	$title        = $args->menu_title;
	$link         = isset( $args->menu_link ) && $args->menu_link ? $args->menu_link : '';
	$open         = $args->menu_active_event;
	$show_icon    = $args->menu_show_icon;
	$icon         = $args->menu_icon;
	$active_icon  = $args->menu_active_icon;
	$icon_pos     = $args->menu_icon_pos;
	$skin         = $args->menu_skin;
	$show_arrow   = isset( $args->menu_show_arrow ) ? $args->menu_show_arrow : '';
	$hover_effect = isset( $args->menu_hover_effect ) ? $args->menu_hover_effect : '';
	$vertical     = isset( $args->menu_vertical ) ? $args->menu_vertical : false;

	$menu_obj = wp_get_nav_menu_object( $menu );
	if ( $menu_obj ) {

		if ( isset( $args->menu_show_arrow ) || isset( $args->menu_hover_effect ) || isset( $args->menu_vertical ) ) {
			// in case of menu widget
			$menu_class = molla_get_menu_class(
				array(
					'skin'     => $skin,
					'arrow'    => $show_arrow,
					'effect'   => $hover_effect,
					'vertical' => $vertical,
				),
				$menu_obj->slug
			);
		} else {
			// customize header builder
			$menu_class = molla_get_menu_class( $skin, $menu_obj->slug );
		}

		if ( $type ) {

			$wrapper_class  = 'dropdown dropdown-menu-wrapper';
			$wrapper_class .= ' icon-' . ( 'left' == $icon_pos ? 'left' : 'right' );

			if ( 'hover' != $open ) {
				$wrapper_class .= ' open-toggle';
			}
			if ( 'toggle2' == $open && is_front_page() ) {
				$wrapper_class .= ' show';
			}

			echo '<div class="' . apply_filters( 'molla_custom_menu_wrapper_class', $wrapper_class ) . '">';

			$toggle_link = '<span>' . $title ? $title : esc_html__( 'Toggle Title', 'molla' ) . '</span>';
			if ( $show_icon ) {
				if ( $icon ) {
					$toggle_link .= '<i class="' . esc_attr( $icon ) . ' normal-state"></i>';
				}
				if ( $active_icon ) {
					$toggle_link .= '<i class="' . esc_attr( $active_icon ) . '"></i>';
				}
			}

			echo '<a href="' . ( $link ? esc_url( $link ) : '#' ) . '" class="dropdown-toggle">' . $toggle_link . '</a>';

			$menu_class .= ' dropdown-menu menu-vertical';

		}
		wp_nav_menu(
			apply_filters(
				'molla_custom_menu_args',
				array(
					'menu'           => $menu,
					'menu_class'     => $menu_class,
					'theme_location' => '',
				)
			)
		);
		if ( $type ) {
			echo '</div>';
		}
	}
}
