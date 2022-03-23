<?php

$tag = esc_html__( 'Tags:', 'molla' );
$tag = apply_filters( 'molla_post_tag_label', $tag );

do_action( 'molla_before_blog_tag' );

if ( molla_option( 'blog_single_tag' ) && get_the_tag_list() ) { ?>
	<div class="col-md">
		<div class="entry-tags">
			<span><?php echo esc_html( $tag ); ?></span> <?php echo get_the_tag_list(); ?>
		</div><!-- End .entry-tags -->
	</div><!-- End .col -->
	<?php
}

do_action( 'molla_after_blog_tag' );
