<?php
do_action( 'molla_before_single_post' );

if ( have_posts() ) {
	?>
	<div class="post-single">
	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/posts/single/single' );
	}
	?>
	</div>
	<?php
	wp_reset_postdata();
} else {
	do_action( 'molla_empty_message' );
}

do_action( 'molla_after_single_post' );
