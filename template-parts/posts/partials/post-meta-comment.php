<?php
do_action( 'molla_before_blog_meta_comment' );
?>

<span class="meta-separator<?php echo esc_attr( ( isset( $is_widget ) && $is_widget ) ? '' : ' ml-2 mr-2' ); ?>"><?php echo esc_html( apply_filters( 'molla_blog_meta_separator', ( isset( $is_widget ) && $is_widget ) ? ',' : '|' ) ); ?></span>

<?php
comments_popup_link( esc_html__( '0 Comments', 'molla' ), esc_html__( '1 Comment', 'molla' ), esc_html__( '% Comments', 'molla' ), 'comments-link scroll-to local' );

do_action( 'molla_after_blog_meta_comment' );
