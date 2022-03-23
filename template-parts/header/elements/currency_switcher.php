<?php
$custom_class = ( isset( $custom_class ) && $custom_class ) ? ( ' ' . $custom_class ) : '';
if ( isset( $tag ) && 'li' == $tag ) {
	echo '<li>';
}
if ( has_nav_menu( 'currency_switcher' ) ) {
	wp_nav_menu(
		array(
			'theme_location' => 'currency_switcher',
			'container'      => '',
			'menu_class'     => 'nav header-dropdown sf-arrows' . $custom_class,
			'before'         => '',
			'after'          => '',
			'depth'          => 2,
			'link_before'    => '',
			'link_after'     => '',
			'fallback_cb'    => false,
			'lazy'           => false,
			'walker'         => new Molla_Custom_Nav_Walker(),
		)
	);
} elseif ( class_exists( 'WOOCS' ) ) {
	global $WOOCS;
	$currencies       = $WOOCS->get_currencies();
	$current_currency = $WOOCS->current_currency;

	$active_c_escaped = '';
	$other_c_escaped  = '';

	foreach ( $currencies as $currency ) {
		$label_escaped = ( $currency['flag'] ? '<span class="flag"><img src="' . esc_url( $currency['flag'] ) . '" height="12" alt="' . esc_attr( $currency['name'] ) . '" width="18" /></span>' : '' ) . esc_html( $currency['name'] . ' ' . $currency['symbol'] );
		if ( $currency['name'] == $current_currency ) {
			$active_c_escaped .= $label_escaped;
		} else {
			$other_c_escaped .= '<li rel="' . esc_attr( $currency['name'] ) . '" class="menu-item"><a class="nolink" href="#">' . molla_filter_output( $label_escaped ) . '</a></li>';
		}
	}
	?>
	<ul id="menu-currency-switcher" class="nav header-dropdown sf-arrows<?php echo esc_attr( $custom_class ); ?>">
		<li class="menu-item menu-item-has-children<?php echo ! $other_c_escaped ? '' : ' has-sub'; ?>">
			<a class="nolink" href="#"><?php echo molla_filter_output( $active_c_escaped ); ?></a>
			<?php if ( $other_c_escaped ) : ?>
				<ul class="sub-menu woocs-switcher pos-left">
					<?php echo molla_filter_output( $other_c_escaped ); ?>
				</ul>
			<?php endif; ?>
		</li>
	</ul>
	<?php
} elseif ( class_exists( 'WCML_Multi_Currency' ) ) {
	global $sitepress, $woocommerce_wpml;

	$settings      = $woocommerce_wpml->get_settings();
	$format        = apply_filters( 'molla_WCML_Multi_Currency_format', '%code%' );
	$wc_currencies = get_woocommerce_currencies();
	if ( ! isset( $settings['currencies_order'] ) ) {
		$currencies = $woocommerce_wpml->multi_currency->get_currency_codes();
	} else {
		$currencies = $settings['currencies_order'];
	}
	$active_c_escaped = '';
	$other_c_escaped  = '';

	foreach ( $currencies as $currency ) {
		if ( 1 == $woocommerce_wpml->settings['currency_options'][ $currency ]['languages'][ $sitepress->get_current_language() ] ) {
			$selected        = $currency == $woocommerce_wpml->multi_currency->get_client_currency() ? ' selected="selected"' : '';
			$currency_format = preg_replace(
				array( '#%name%#', '#%symbol%#', '#%code%#' ),
				array( $wc_currencies[ $currency ], get_woocommerce_currency_symbol( $currency ), $currency ),
				$format
			);

			if ( $selected ) {
				$active_c_escaped .= $currency_format;
			} else {
				$other_c_escaped .= '<li class="menu-item"><a class="nolink" href="" rel="' . esc_attr( $currency ) . '">' . $currency_format . '</a></li>';
			}
		}
	}
	?>
	<ul id="menu-currency-switcher" class="nav header-dropdown wcml_currency_switcher sf-arrows<?php echo esc_attr( $custom_class ); ?>">
		<li class="menu-item menu-item-has-children<?php echo ! $other_c_escaped ? '' : ' has-sub'; ?>">
			<a class="nolink" href="#"><?php echo molla_filter_output( $active_c_escaped ); ?></a>
			<?php if ( $other_c_escaped ) : ?>
				<ul class="sub-menu wcml-cs-submenu pos-left">
					<?php echo molla_filter_output( $other_c_escaped ); ?>
				</ul>
			<?php endif; ?>
		</li>
	</ul>
	<?php
} else {
	?>
	<ul id="menu-currency-switcher" class="nav header-dropdown sf-arrows<?php echo esc_attr( $custom_class ); ?>">
		<li class="menu-item menu-item-has-children">
			<a href="#"><?php esc_html_e( 'USD', 'molla' ); ?></a>
			<ul class="sub-menu pos-left">
				<li class="menu-item"><a href="#"><?php esc_html_e( 'Eur', 'molla' ); ?></a></li>
				<li class="menu-item"><a href="#"><?php esc_html_e( 'Usd', 'molla' ); ?></a></li>
			</ul>
		</li>
	</ul>
	<?php
}

if ( isset( $tag ) && 'li' == $tag ) {
	echo '</li>';
}
