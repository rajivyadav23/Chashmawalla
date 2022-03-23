<?php

if ( is_customize_preview() ) {
	echo molla_get_dynamic_css( false, 'customize-preview' );
}
$elems = apply_filters(
	'molla_dynamic_elements',
	array(
		'header',
		'footer',
		'color',
		'typography',
		'blog',
		'menu_skin',
		'elements',
		'layout',
		'woocommerce',
	)
);
foreach ( $elems as $elem ) {
	echo molla_get_dynamic_css( false, $elem );
}
echo molla_minify_css( molla_option( 'custom_css' ) );

