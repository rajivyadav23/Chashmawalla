<?php
wp_enqueue_script( 'jquery-fitvids' );

$video_code = get_post_meta( get_the_ID(), 'media_embed_code', true );

if ( $video_code ) :
	if ( false !== strpos( $video_code, '[video src="' ) && has_post_thumbnail() ) {
		$video_code = str_replace( '[video src="', '[video poster="' . esc_url( get_the_post_thumbnail_url( null, 'full' ) ) . '" src="', $video_code );
	}
	?>
	<figure class="entry-media entry-video fit-video">
		<?php echo do_shortcode( $video_code ); ?>
	</figure>
	<?php
endif;
