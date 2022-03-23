<?php

$share = esc_html__( 'Share this post:', 'molla' );

$sticky = molla_option( 'blog_single_share_pos' );

if ( $sticky ) {
	$share = esc_html__( 'Share:', 'molla' );
}

$share = apply_filters( 'molla_post_share_label', $share );

do_action( 'molla_before_blog_share' );

if ( molla_option( 'blog_single_share' ) ) {
	?>
	<div class="<?php echo esc_attr( $sticky ? 'sticky-sidebar' : 'col-md-auto mt-2 mt-md-0' ); ?>">
		<?php do_action( 'molla_print_share', $share, $sticky, molla_option( 'share_icon_type' ) ); ?>
	</div><!-- End .col-auto -->
	<?php
}

do_action( 'molla_after_blog_share' );
