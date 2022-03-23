<?php

$class         = ' post-' . $blog_type;
$wrapper_class = 'post-wrapper';
$cats          = '';

if ( 'creative' == $layout_mode || $filter ) {
	$wrapper_class .= ' grid-item';
	$elem_cats      = get_the_category( get_the_ID() );
	foreach ( $elem_cats as $cat ) {
		if ( $cats ) {
			$cats .= ' ' . $cat->slug;
		} else {
			$cats = $cat->slug;
		}
	}
	$wrapper_class .= ' ' . $cats;
}

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

$has_media = in_array( 'f_image', $show_op ) && $has_media;

if ( ! $has_media ) {
	$class .= ' post-empty-media';
}

?>

<div class="<?php echo esc_attr( $wrapper_class ); ?>"<?php echo ! $cats ? '' : ( ' data-cats="' . $cats . '"' ); ?>>
	<article <?php echo 'class="' . join( ' ', get_post_class( 'post' ) ) . $class . '"'; ?>>
		<?php
		do_action( 'molla_before_loop_post_item', $blog_type );

		if ( 'list' == $blog_type ) :
			?>
		<div class="row">
			<?php if ( $has_media ) : ?>
			<div class="col-md-<?php echo (int) $img_width; ?>">
				<?php
			endif;
		endif;
		if ( $has_media ) :
			if ( 'video' == get_post_format() && get_post_meta( get_the_ID(), 'media_embed_code', true ) ) {
				get_template_part( 'template-parts/posts/partials/post', 'video' );
			} else {
				get_template_part( 'template-parts/posts/partials/post', 'image' );
			}
		endif;
		if ( 'list' == $blog_type ) :
			if ( $has_media ) :
				?>
			</div>
		<?php endif; ?>
			<div class="<?php echo ! $has_media ? 'col-12' : ( 'col-md-' . ( 12 - (int) $img_width ) ); ?>">
			<?php
		endif;
		?>

			<div class="entry-body <?php echo esc_attr( isset( $align ) ? 'text-' . $align : '' ); ?> ">
				<?php
				if ( in_array( 'author', $show_op ) || in_array( 'date', $show_op ) || in_array( 'comment', $show_op ) ) :
					molla_get_template_part(
						'template-parts/posts/partials/post',
						'meta',
						array(
							'showing'   => $show_op,
							'is_widget' => isset( $is_widget ),
						)
					);
				endif;
				?>
				<?php get_template_part( 'template-parts/posts/partials/post', 'title' ); ?>
				<?php
				if ( in_array( 'category', $show_op ) ) {
					get_template_part( 'template-parts/posts/partials/post', 'cat' );
				}
				?>

				<div class="entry-content">
					<?php
					$unit   = $excerpt_by ? $excerpt_by : '';
					$length = $excerpt_length ? $excerpt_length : '';
					if ( in_array( 'content', $show_op ) ) :
						$excerpt = molla_excerpt( $length, $unit );
						if ( $excerpt ) :
							?>
							<p><?php echo molla_strip_script_tags( $excerpt ); ?></p>
							<?php
						endif;
					endif;

					if ( in_array( 'read_more', $show_op ) ) :
						molla_get_template_part(
							'template-parts/posts/partials/post',
							'readmore',
							array(
								'blog_more_label' => $blog_more_label,
								'blog_more_icon'  => $blog_more_icon,
							)
						);
					endif;
					?>
				</div>
			</div>
			<?php
			if ( 'list' == $blog_type ) :
				?>
			</div>
		</div>
				<?php
		endif;

			do_action( 'molla_after_loop_post_item', $blog_type );
			?>
	</article>
</div>
