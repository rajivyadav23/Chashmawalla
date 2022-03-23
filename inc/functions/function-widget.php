<?php

// Register sidebars and widgetized areas

add_action( 'widgets_init', 'molla_register_sidebars' );

function molla_register_sidebars() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Main Sidebar', 'molla' ),
			'id'            => 'main-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	$footer_col = explode( '+', molla_option( 'footer_top_cols' ) );
	for ( $i = 1; $i <= count( $footer_col ); $i ++ ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Top Widget', 'molla' ) . ' ' . $i,
				'id'            => 'footer-top-widget-' . $i,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}

	$footer_col = explode( '+', molla_option( 'footer_main_cols' ) );
	for ( $i = 1; $i <= count( $footer_col ); $i ++ ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Main Widget', 'molla' ) . ' ' . $i,
				'id'            => 'footer-main-widget-' . $i,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Bottom Widget', 'molla' ),
			'id'            => 'footer-bottom-widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop Sidebar', 'molla' ),
			'id'            => 'shop-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop Top Sidebar', 'molla' ),
			'id'            => 'shop-top-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Product Sidebar', 'molla' ),
			'id'            => 'product-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

}
