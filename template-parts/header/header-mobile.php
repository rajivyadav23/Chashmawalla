<?php
$mmenu_skin = molla_option( 'mobile_menu_skin' );
?>

<div class="mobile-menu-overlay"></div>
<div class="mobile-menu-container<?php echo esc_attr( $mmenu_skin ? ( ' ' . $mmenu_skin ) : '' ); ?>">
	<div class="mobile-menu-wrapper">
		<span class="mobile-menu-close"><i class="icon-close"></i></span>

		<?php

		get_search_form( array( 'aria_label' => 'mobile' ) );

		echo '<div class="mobile-menus"></div>';


		// social links
		if ( count( molla_option( 'header_social_links' ) ) ) {
			get_template_part( 'template-parts/header/elements/social' );
		}

		?>

	</div><!-- End .mobile-menu-wrapper -->
</div><!-- End .mobile-menu-container -->
