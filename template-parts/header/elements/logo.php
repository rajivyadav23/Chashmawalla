<?php
$site_logo   = molla_option( 'site_logo' );
$site_retina = molla_option( 'site_retina_logo' );

$site_logo   = $site_logo ? $site_logo : MOLLA_URI . '/assets/images/logo.png';
$site_retina = $site_retina ? $site_retina : MOLLA_URI . '/assets/images/retina_logo.png';

$logo_max_width = molla_option( 'logo_width' ) ? molla_option( 'logo_width' ) : 105;
?>
<h1 class="logo">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<img src="<?php echo esc_attr( $site_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" srcset="<?php echo esc_attr( $site_retina ); ?> 2x" width="<?php echo esc_attr( $logo_max_width ); ?>" >
	</a>
</h1>
