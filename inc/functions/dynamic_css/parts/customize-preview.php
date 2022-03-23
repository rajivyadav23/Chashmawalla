<?php

require_once MOLLA_LIB . '/lib/molla-color-lib.php';
$molla_color_lib = MollaColorLib::getInstance();

$primary_color      = molla_option( 'primary_color' );
$primary_dark_color = $molla_color_lib->darken( $primary_color, 10 );

?>
.molla-tooltip {
	background-color: <?php echo esc_attr( $primary_color ); ?>;
}
.molla-tooltip:hover,
.molla-tooltip:focus {
	background-color: <?php echo esc_attr( $primary_dark_color ); ?>;
}

.kirki-customizer-loading-wrapper {
	background-image: url( <?php echo MOLLA_URI; ?>/assets/images/logo-icon.png ) !important;
}
.kirki-customizer-loading-wrapper .kirki-customizer-loading {
	background-color: rgba(0,0,0,.4) !important;
}
