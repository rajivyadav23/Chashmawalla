<?php
do_action( 'molla_before_blog_footer' );
?>
<?php if ( ( molla_option( 'blog_single_share' ) && ! molla_option( 'blog_single_share_pos' ) && has_action( 'molla_print_share' ) ) || ( molla_option( 'blog_single_tag' ) && get_the_tag_list() ) ) : ?>
<div class="entry-footer row sp-0 flex-column flex-md-row">
	<?php get_template_part( 'template-parts/posts/partials/post', 'tag' ); ?>
	<?php
	if ( ! molla_option( 'blog_single_share_pos' ) ) {
		get_template_part( 'template-parts/posts/partials/post', 'share' );
	}
	?>
</div>
<?php endif; ?>
<?php
do_action( 'molla_after_blog_footer' );
