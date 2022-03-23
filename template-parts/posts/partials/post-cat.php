<?php
$category_in   = esc_html__( 'in', 'molla' );
$category_in   = apply_filters( 'molla_post_category_in_text', $category_in );
$category_list = get_the_category_list( ', ', 'molla' );

do_action( 'molla_before_blog_cats' );

if ( $category_list ) :
?>

<div class="entry-cats">
	<?php echo molla_strip_script_tags( $category_in . ' ' . $category_list ); ?>
</div>
<?php

endif;

do_action( 'molla_after_blog_cats' );
