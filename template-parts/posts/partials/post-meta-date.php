<?php
do_action( 'molla_before_blog_meta_date' );
?>

<a href="<?php echo esc_url( molla_get_post_meta_date_url() ); ?>"> <?php echo esc_attr( get_the_date() ); ?> </a>

<?php
do_action( 'molla_after_blog_meta_date' );
