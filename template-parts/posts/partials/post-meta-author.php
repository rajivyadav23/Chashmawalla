<?php
$text = esc_html__( 'by', 'molla' );
$text = apply_filters( 'molla_post_author_label', $text );

do_action( 'molla_before_blog_meta_author' );
?>

<span class="entry-author">
	<?php echo esc_html( $text ); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"> <?php echo esc_html( get_the_author() ); ?> </a>
</span>
<span class="meta-separator<?php echo esc_attr( isset( $is_widget ) && $is_widget ? '' : ' ml-2 mr-2' ); ?>"><?php echo esc_html( apply_filters( 'molla_blog_meta_separator', ( isset( $is_widget ) && $is_widget ) ? ',' : '|' ) ); ?></span>

<?php
do_action( 'molla_after_blog_meta_author' );
