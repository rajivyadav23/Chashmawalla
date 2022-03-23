<?php
$view_all = esc_html__( 'View all posts by ', 'molla' );
$view_all = apply_filters( 'molla_post_view_all_link_text', $view_all );

if ( ! get_the_author_meta( 'user_description' ) ) {
	return;
}

do_action( 'molla_before_blog_author' );
?>

<div class="entry-author-details">
	<div class="author-media">
		<?php
		$user = get_the_author_meta( 'ID' );
		echo get_avatar( $user, 90 );
		?>
	</div><!-- End .author-media -->

	<div class="author-body">
		<div class="author-header row sp-0 flex-column flex-md-row">
			<div class="col">
				<h4><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></a></h4>
			</div><!-- End .col -->
			<div class="col-auto">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-link"><?php echo esc_html( $view_all . get_the_author_meta( 'display_name' ) ); ?> <i class="icon-long-arrow-right"></i></a>
			</div><!-- End .col -->
		</div><!-- End .row -->

		<div class="author-content">
			<p><?php echo esc_html( get_the_author_meta( 'user_description' ) ); ?></p>
		</div><!-- End .author-content -->
	</div><!-- End .author-body -->
</div>
<?php
do_action( 'molla_after_blog_author' );
