<?php
do_action( 'molla_before_blog_entry' );

global $molla_settings;
$page_width = $molla_settings['page']['width'];
$sidebar    = $molla_settings['sidebar'];

?>

<div class="<?php echo esc_attr( $page_width ? $page_width : 'full' ) . esc_attr( $sidebar['active'] && 'right' == $sidebar['pos'] ? ' right-sidebar' : '' ); ?>">

<div class="<?php echo esc_attr( $sidebar['active'] ? ( molla_sidebar_wrap_classes() . ' ' ) : '' ); ?>blog-entry-wrapper">
<?php
if ( $sidebar['active'] ) :

	if ( 'left' == $sidebar['pos'] ) :
		molla_get_template_part( 'template-parts/posts/loop/loop', 'sidebar', array( 'sidebar' => $sidebar['pos'] ) );
	endif;
	?>

	<div class="col-lg-9">
	<?php
endif;

	get_template_part( 'template-parts/posts/loop/loop', 'content' );

if ( $sidebar['active'] ) :
	?>
	</div>

	<?php
	if ( 'right' == $sidebar['pos'] ) :
		molla_get_template_part( 'template-parts/posts/loop/loop', 'sidebar', array( 'sidebar' => $sidebar['pos'] ) );
	endif;
endif;
?>
</div>

<?php
if ( $sidebar['active'] ) :
	do_action( 'molla_sidebar_control' );
endif;
?>

</div>

<?php
	do_action( 'molla_after_blog_entry' );
