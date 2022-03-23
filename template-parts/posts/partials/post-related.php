<?php

$related_posts = molla_get_related_posts( $post->ID );
$show_op       = molla_option( 'blog_entry_visible_op' );

$options               = array();
$options['nav']        = molla_option( 'related_posts_nav' ) ? true : false;
$options['autoHeight'] = true;
$options['dots']       = molla_option( 'related_posts_dot' ) ? true : false;
$options['margin']     = (int) molla_option( 'related_posts_padding' );
$options['loop']       = molla_option( 'related_posts_loop' ) ? true : false;
$options['responsive'] = array();

$args = array(
	0   => array(
		'items' => '',
	),
	576 => array(
		'items' => '',
	),
	768 => array(
		'items' => '',
	),
	992 => array(
		'items' => (int) molla_option( 'related_posts_cols' ),
	),
);

$options['responsive'] = molla_carousel_options( $args );
$add_class             = molla_carousel_responsive_classes( $options['responsive'] );

do_action( 'molla_before_blog_related' );

if ( $related_posts->have_posts() ) :
	wp_enqueue_script( 'owl-carousel' );
	?>

	<div class="posts related-posts">
		<h3 class="title"><?php esc_html_e( 'Related Posts', 'molla' ); ?></h3><!-- End .title -->

		<div class="owl-carousel owl-simple carousel-with-shadow <?php echo esc_attr( $add_class ); ?>" data-toggle="owl" 
			data-owl-options='<?php echo esc_attr( json_encode( $options ) ); ?>'>

			<?php
			while ( $related_posts->have_posts() ) :
				$related_posts->the_post();

				$class = ' post-' . ( 'list' == molla_option( 'blog_entry_type' ) ? 'default' : molla_option( 'blog_entry_type' ) );

				if ( 'video' == get_post_format() ) {
					$class .= ' format-video';
					if ( ! get_post_meta( get_the_ID(), 'media_embed_code', true ) ) {
						$class .= ' post-empty-video';
					}
				}

				$has_media = false;
				if ( has_post_thumbnail() || get_post_meta( $post->ID, 'featured_images' ) || ( 'video' == get_post_format() && get_post_meta( get_the_ID(), 'media_embed_code', true ) ) ) {
					$has_media = true;
				}

				if ( ! $has_media ) {
					$class .= ' post-empty-media';
				}

				?>
				<article class="<?php echo join( ' ', get_post_class( 'post' ) ) . $class; ?>">
					<?php
					if ( $has_media ) {
						if ( 'video' == get_post_format() && get_post_meta( get_the_ID(), 'media_embed_code', true ) ) {
							get_template_part( 'template-parts/posts/partials/post', 'video' );
						} else {
							molla_get_template_part( 'template-parts/posts/partials/post', 'image', array( 'image_size' => 'full' ) );
						}
					}
					?>
					<div class="entry-body<?php echo isset( $align ) ? ( ' text-' . $align ) : ''; ?>">
						<?php
						if ( in_array( 'author', $show_op ) || in_array( 'date', $show_op ) || in_array( 'comment', $show_op ) ) :
							molla_get_template_part(
								'template-parts/posts/partials/post',
								'meta',
								array( 'showing' => $show_op )
							);
						endif;
						?>

						<?php get_template_part( 'template-parts/posts/partials/post', 'title' ); ?>

						<?php if ( in_array( 'category', $show_op ) ) : ?>
						<div class="entry-cats">
							in <?php echo get_the_category_list( esc_html__( ', ', 'molla' ) ); ?>
						</div>
						<?php endif; ?>

						<div class="entry-content">

						<?php
						if ( in_array( 'content', $show_op ) ) :
							$length  = molla_option( 'blog_excerpt_length' );
							$unit    = molla_option( 'blog_excerpt_unit' );
							$excerpt = molla_excerpt( $length, $unit );
							if ( $excerpt ) :
								echo '<p>' . molla_strip_script_tags( $excerpt ) . '</p>';
							endif;
						endif;
						?>

						<?php if ( in_array( 'read_more', $show_op ) ) : ?>
							<?php get_template_part( 'template-parts/posts/partials/post', 'readmore' ); ?>
						<?php endif; ?>
						</div>
					</div>
				</article>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php
endif;

do_action( 'molla_after_blog_related' );
