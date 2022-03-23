<?php
$str = '';
if ( isset( $args ) ) {
	if ( is_string( $args ) ) {
		$str = $args;
	} elseif ( is_object( $args ) && isset( $args->html ) ) {
		$str = $args->html;
	}
	echo '<div class="custom-html' . ( is_object( $args ) && isset( $args->el_class ) && $args->el_class ? ' ' . esc_attr( $args->el_class ) : '' ) . '">';
		echo do_shortcode( $str );
	echo '</div>';
}
