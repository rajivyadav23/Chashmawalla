<?php
$custom_class = ( isset( $custom_class ) && $custom_class ) ? ( ' ' . $custom_class ) : '';
if ( isset( $tag ) && 'li' == $tag ) {
	echo '<li>';
}
if ( has_nav_menu( 'lang_switcher' ) ) {
	wp_nav_menu(
		array(
			'theme_location' => 'lang_switcher',
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
} elseif ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
	$languages = apply_filters( 'wpml_active_languages', array() );
	if ( ! empty( $languages ) ) {
		$active_lang = '';
		$other_langs = '';
		foreach ( $languages as $l ) {
			if ( ! $l['active'] ) {
				$other_langs .= '<li class="menu-item"><a href="' . esc_url( $l['url'] ) . '">';
			}
			if ( $l['country_flag_url'] ) {
				if ( $l['active'] ) {
					$active_lang .= '<span class="flag"><img src="' . esc_url( $l['country_flag_url'] ) . '" height="12" alt="' . esc_attr( $l['language_code'] ) . '" width="18" /></span>';
				} else {
					$other_langs .= '<span class="flag"><img src="' . esc_url( $l['country_flag_url'] ) . '" height="12" alt="' . esc_attr( $l['language_code'] ) . '" width="18" /></span>';
				}
			}
			if ( $l['active'] ) {
				$active_lang .= icl_disp_language( $l['native_name'], $l['translated_name'], apply_filters( 'molla_icl_show_native_name', true, $l ) );
			} else {
				$other_langs .= icl_disp_language( $l['native_name'], $l['translated_name'], apply_filters( 'molla_icl_show_native_name', true, $l ) );
			}
			if ( ! $l['active'] ) {
				$other_langs .= '</a></li>';
			}
		}
		?>
		<ul id="menu-language-switcher" class="nav header-dropdown sf-arrows<?php echo esc_attr( $custom_class ); ?>">
			<li class="menu-item menu-item-has-children<?php echo ! $other_langs ? '' : ' has-sub'; ?>">
				<a class="nolink" href="#"><?php echo molla_strip_script_tags( $active_lang ); ?></a>
				<?php if ( $other_langs ) : ?>
					<ul class="sub-menu">
						<?php echo molla_strip_script_tags( $other_langs ); ?>
					</ul>
				<?php endif; ?>
			</li>
		</ul>
		<?php
	}
} elseif ( function_exists( 'qtranxf_getSortedLanguages' ) ) {
	global $q_config;

	$languages     = qtranxf_getSortedLanguages();
	$flag_location = qtranxf_flag_location();
	if ( is_404() ) {
		$url = esc_url( home_url() );
	} else {
		$url = '';
	}

	if ( ! empty( $languages ) ) {
		$active_lang = '';
		$other_langs = '';
		foreach ( $languages as $language ) {
			if ( $language != $q_config['language'] ) {
				$other_langs .= '<li class="menu-item"><a href="' . qtranxf_convertURL( $url, $language, false, true ) . '">';
				$other_langs .= '<span class="flag"><img src="' . esc_url( $flag_location . $q_config['flag'][ $language ] ) . '" alt="' . esc_attr( $q_config['language_name'][ $language ] ) . '" /></span>';
				$other_langs .= $q_config['language_name'][ $language ];
				$other_langs .= '</a></li>';
			} else {
				$active_lang .= '<span class="flag"><img src="' . esc_url( $flag_location . $q_config['flag'][ $language ] ) . '" alt="' . esc_attr( $q_config['language_name'][ $language ] ) . '" /></span>';
				$active_lang .= $q_config['language_name'][ $language ];
			}
		}
		?>
		<ul id="menu-language-switcher" class="nav header-dropdown sf-arrows<?php echo esc_attr( $custom_class ); ?>">
			<li class="menu-item menu-item-has-children<?php echo ! $other_langs ? '' : ' has-sub'; ?>">
				<a class="nolink" href="#"><?php echo molla_strip_script_tags( $active_lang ); ?></a>
				<?php if ( $other_langs ) : ?>
					<ul class="sub-menu">
						<?php echo molla_strip_script_tags( $other_langs ); ?>
					</ul>
				<?php endif; ?>
			</li>
		</ul>
		<?php
	}
} else {
	?>
	<ul id="menu-language-switcher" class="nav header-dropdown sf-arrows<?php echo esc_attr( $custom_class ); ?>">
		<li class="menu-item menu-item-has-children">
			<a href="#"><?php esc_html_e( 'English', 'molla' ); ?></a>
			<ul class="sub-menu pos-left">
				<li class="menu-item"><a href="#"><?php esc_html_e( 'English', 'molla' ); ?></a></li>
				<li class="menu-item"><a href="#"><?php esc_html_e( 'Chinese', 'molla' ); ?></a></li>
			</ul>
		</li>
	</ul>
	<?php
}

if ( isset( $tag ) && 'li' == $tag ) {
	echo '</li>';
}
