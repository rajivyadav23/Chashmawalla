<?php
/*
Template name: Page - Container-Fluid No sidebar
*/

get_header(); ?>

<div class="container-fluid">
<?php do_action( 'molla_page_container_after_start' ); ?>
<?php get_template_part( 'template-parts/partials/page', 'content' ); ?>
<?php do_action( 'molla_page_container_before_end' ); ?>
</div>

<?php

get_footer();
