<?php
/**
 * Displays post entry read more
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$blog_more_label = isset( $blog_more_label ) ? $blog_more_label : molla_option( 'blog_more_label' );
$blog_more_icon  = isset( $blog_more_icon ) ? $blog_more_icon : molla_option( 'blog_more_icon' );

$text  = $blog_more_label ? $blog_more_label : esc_html__( 'Continue Reading', 'molla' );
$class = 'read-more' . ( ( true == $blog_more_icon || 'yes' == $blog_more_icon ) ? '' : ' icon-hidden' );

$class = apply_filters( 'molla_post_readmore_classes', $class ); ?>


<?php do_action( 'molla_before_blog_entry_readmore' ); ?>

<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $text ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $text ); ?></a>

<?php
do_action( 'molla_after_blog_entry_readmore' );
