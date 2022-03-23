<?php
global $molla_settings;
$sidebar = $molla_settings['sidebar'];

if ( $sidebar['active'] ) :
	?>
<div class="<?php echo molla_sidebar_wrap_classes(); ?>">
	<aside class="col-lg-3">
		<?php get_sidebar(); ?>
	</aside>
	<div class="col-lg-9">
		<?php get_template_part( 'template-parts/partials/page', 'content' ); ?>
	</div>
</div>
	<?php
	do_action( 'molla_sidebar_control' );
else :
	get_template_part( 'template-parts/partials/page', 'content' );
endif;
