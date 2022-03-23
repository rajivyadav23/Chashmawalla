<?php
/*
Template name: Page - Container-Fluid Left sidebar
*/

get_header(); ?>

<div class="container-fluid">
<?php do_action( 'molla_page_container_after_start' ); ?>
	<?php get_template_part( 'template-parts/partials/page', 'left-sidebar' ); ?>
<?php do_action( 'molla_page_container_before_end' ); ?>
</div>

<?php

get_footer();
