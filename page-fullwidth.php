<?php
/*
Template name: Page - Fullwidth No Sidebar
*/

get_header();
?>

<div class="full-inner">

<?php do_action( 'molla_page_container_after_start' ); ?>
<?php get_template_part( 'template-parts/partials/page', 'content' ); ?>
<?php do_action( 'molla_page_container_after_start' ); ?>

</div>
<?php
get_footer();
