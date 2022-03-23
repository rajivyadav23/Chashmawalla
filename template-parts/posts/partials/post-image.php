<?php

global $post;

do_action( 'molla_before_blog_image' );

$is_single = isset( $p_src );

if ( has_post_thumbnail() || get_post_meta( $post->ID, 'featured_images' ) ) :
	$featured_id = get_post_thumbnail_id();
	$ids         = get_post_meta( $post->ID, 'featured_images' );
	if ( $featured_id ) {
		$ids = array_merge( array( $featured_id ), $ids );
	}
	?>
	<figure class="entry-media<?php echo count( $ids ) > 1 ? ' entry-gallery' : ''; ?>">
		<?php
		if ( $is_single && count( $ids ) > 1 ) :
			wp_enqueue_script( 'owl-carousel' );
			?>
		<div class="owl-carousel owl-simple owl-nav-inside c-xs-1 sp-0" data-toggle="owl" data-owl-options='{
			"autoHeight": true,
			"dots": false
		}'>
			<?php
		endif;

		if ( ! $is_single ) {
			set_post_thumbnail( $post, $ids[0] );
			get_template_part( 'template-parts/posts/partials/posts', 'thumbnail' );
		} else {
			foreach ( $ids as $id ) {
				set_post_thumbnail( $post, $id );
				get_template_part( 'template-parts/posts/partials/posts', 'thumbnail' );
			}
		}
		delete_post_meta( $post->ID, '_thumbnail_id' );
		set_post_thumbnail( $post, $featured_id );
		?>
		<?php if ( $is_single && count( $ids ) > 1 ) : ?>
		</div>
			<?php
		endif;
		?>
	</figure>
	<?php

endif;

do_action( 'molla_after_blog_image' );
