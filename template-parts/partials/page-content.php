<?php
// Load posts loop.
if ( have_posts() ) {

	do_action( 'page_content_inner_top', 'inner_top' );

	while ( have_posts() ) {
		the_post();
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'molla' ),
				'after'  => '</div>',
			)
		);
	}

	do_action( 'page_content_inner_bottom', 'inner_bottom' );

} else {
	do_action( 'molla_empty_message' );
}

wp_reset_postdata();

if ( comments_open() ) {
	comments_template();
}
