<?php
get_header();

wp_reset_postdata();

if ( have_posts() ) :
	the_post();

	the_content();

	wp_reset_postdata();

endif;

get_footer();
