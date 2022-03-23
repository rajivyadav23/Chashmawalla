<?php
/**
 * The default page template file.
 *
 * @package molla
 */

if ( is_singular( 'product_layout' ) ) {
	get_template_part( 'single', 'product_layout' );
	return;
}

get_header();

if ( ! molla_is_elementor_preview() || 'footer' != get_post_type() ) {
	global $molla_settings;
	$page_width = $molla_settings['page']['width'];
	$sidebar    = $molla_settings['sidebar'];
	?>
	<?php if ( $page_width ) : ?>
<div class="<?php echo esc_attr( $page_width ); ?>">
	<?php endif; ?>
	<?php do_action( 'molla_page_container_after_start' ); ?>
	<?php if ( $sidebar['active'] ) : ?>
<div class="<?php echo molla_sidebar_wrap_classes(); ?>">
		<?php if ( 'left' == $sidebar['pos'] ) : ?>
	<aside class="col-lg-3">
			<?php get_sidebar(); ?>
	</aside>
		<?php endif; ?>
	<div class="col-lg-9">

	<?php endif; ?>

	<?php if ( ! molla_is_elementor_preview() || 'sidebar' != get_post_type() ) : ?>
		<?php get_template_part( 'template-parts/partials/page', 'content' ); ?>
	<?php endif; ?>
		
	<?php if ( $sidebar['active'] ) : ?>
	</div>
		<?php if ( 'right' == $sidebar['pos'] ) : ?>
	<aside class="col-lg-3">
			<?php get_sidebar(); ?>
	</aside>
		<?php endif; ?>
</div>
<a href="#" class="sidebar-toggler"><i class="fa fa-chevron-right"></i></a>
<div class="sidebar-overlay"></div>
	<?php endif; ?>
	<?php do_action( 'molla_page_container_before_end' ); ?>
	<?php if ( $page_width ) : ?>
</div>
	<?php endif; ?>

	<?php
}
get_footer();
