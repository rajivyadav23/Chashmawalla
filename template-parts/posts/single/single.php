<?php
$blog_single_share_pos = molla_option( 'blog_single_share_pos' ) && has_action( 'molla_print_share' );

$class = '';
if ( 'video' == get_post_format() && ! get_post_meta( get_the_ID(), 'media_embed_code', true ) ) {
	$class .= ' post-empty-video';
}

$has_media = false;
if ( has_post_thumbnail() || get_post_meta( $post->ID, 'featured_images' ) || ( 'video' == get_post_format() && get_post_meta( get_the_ID(), 'media_embed_code', true ) ) ) {
	$has_media = true;
}

$has_media = $has_media && molla_option( 'blog_single_featured_image' );

if ( ! $has_media ) {
	$class .= ' post-empty-media';
}

?>

<article id="post-<?php the_ID(); ?>" <?php echo 'class="' . join( ' ', get_post_class( 'post' ) ) . $class . '"'; ?>>
	<?php
	if ( $has_media && 'inner-content' == molla_option( 'blog_single_featured_image_type' ) ) {
		if ( 'video' == get_post_format() && get_post_meta( get_the_ID(), 'media_embed_code', true ) ) {
			get_template_part( 'template-parts/posts/partials/post', 'video' );
		} else {
			molla_get_template_part( 'template-parts/posts/partials/post', 'image', array( 'p_src' => 'single' ) );
		}
	}
	?>
	<?php if ( $blog_single_share_pos ) : ?>
		<div class="row<?php echo esc_attr( $blog_single_share_pos ? ' sticky-sidebar-wrapper' : '' ); ?>">
			<div class="col-lg-11">
	<?php endif; ?>
				<div class="entry-body">
					<?php
					if ( molla_option( 'blog_single_meta' ) ) {
						molla_get_template_part(
							'template-parts/posts/partials/post',
							'meta',
							array(
								'showing' => array( 'author', 'date', 'comment' ),
							)
						);
					}

					molla_get_template_part(
						'template-parts/posts/partials/post',
						'title',
						array(
							'is_single' => true,
						)
					);

					if ( molla_option( 'blog_single_category' ) ) {
						get_template_part( 'template-parts/posts/partials/post', 'cat' );
					}
					?>

					<div class="entry-content editor-content">
						<?php the_content(); ?>
					</div>

					<?php
					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'molla' ),
							'after'  => '</div>',
						)
					);
					?>

					<?php get_template_part( 'template-parts/posts/partials/post', 'footer' ); ?>

				</div>
	<?php if ( $blog_single_share_pos ) : ?>
			</div>
			<div class="col-lg-1 social-icons-wrapper<?php echo 'sticky-left' == $blog_single_share_pos ? ' order-lg-first' : ''; ?>">
				<?php get_template_part( 'template-parts/posts/partials/post', 'share' ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	if ( molla_option( 'blog_single_author_box' ) && get_the_content() ) {
		get_template_part( 'template-parts/posts/partials/post', 'author' );
	}
	?>
</article>
<?php
if ( molla_option( 'blog_single_prev_next_nav' ) ) {
	get_template_part( 'template-parts/posts/partials/post', 'prev-next' );
}
?>

<?php
if ( molla_option( 'blog_single_related' ) ) {
	molla_get_template_part( 'template-parts/posts/partials/post', 'related', array( 'align' => molla_option( 'blog_entry_align' ) ) );
}

comments_template();
