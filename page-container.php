<?php
/*
Template name: Page - Container No sidebar
*/

get_header();
?>

<div class="container">
<?php do_action( 'molla_page_container_after_start' ); ?>
<?php get_template_part( 'template-parts/partials/page', 'content' ); ?>
<?php do_action( 'molla_page_container_before_end' ); ?>
</div>

<?php

get_footer();
