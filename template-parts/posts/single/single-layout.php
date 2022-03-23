<?php
global $molla_settings;
$page_width = $molla_settings['page']['width'];
$sidebar    = $molla_settings['sidebar'];

?>

<div class="<?php echo esc_attr( $page_width ? $page_width : 'full' ) . esc_attr( $sidebar['active'] && 'right' == $sidebar['pos'] ? ' right-sidebar' : '' ); ?>">

<?php do_action( 'molla_before_blog' ); ?>

<?php
if ( $sidebar['active'] ) :
	?>
<div class="<?php echo molla_sidebar_wrap_classes(); ?>">
	<?php

	if ( 'left' == $sidebar['pos'] ) :
		molla_get_template_part( 'template-parts/posts/single/single', 'sidebar', array( 'sidebar' => $sidebar['pos'] ) );
	endif;
	?>

	<div class="col-lg-9">
	<?php
endif;

if ( ! molla_is_elementor_preview() || 'sidebar' != get_post_type() ) :
	get_template_part( 'template-parts/posts/single/single', 'content' );
endif;

if ( $sidebar['active'] ) :
	?>
	</div>

	<?php
	if ( 'right' == $sidebar['pos'] ) :
		molla_get_template_part( 'template-parts/posts/single/single', 'sidebar', array( 'sidebar' => $sidebar['pos'] ) );
	endif;
endif;
?>
</div>

<?php
if ( $sidebar['active'] ) :
	do_action( 'molla_sidebar_control' );
	?>
</div>
	<?php
endif;
?>


<?php
	do_action( 'molla_after_blog' );
