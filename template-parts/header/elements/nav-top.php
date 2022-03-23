<ul class="nav top-menu sf-arrows">
	<li class="top-link">
		<a href="#"><?php echo esc_html( apply_filters( 'molla_top_menu_label', esc_html__( 'Links', 'molla' ) ) ); ?></a>
		<ul class="nav nav-dropdown">
			<?php

			$elems = molla_option( 'top_nav_items' );

			foreach ( $elems as $elem ) {
				if ( 'top_nav' == $elem ) {
					if ( has_nav_menu( 'top_nav' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'top_nav',
								'target'         => 'top_nav',
								'depth'          => 2,
								'walker'         => new Molla_Custom_Nav_Walker(),
							)
						);
					}
				} else {
					molla_get_template_part( 'template-parts/header/elements/' . $elem, '', array( 'tag' => 'li' ) );
				}
			}
			?>
		</ul>
	</li>
</ul>
