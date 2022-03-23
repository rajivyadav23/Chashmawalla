<?php
/**
 * The blog template file.
 *
 * @package molla
 */

get_header();

get_template_part( 'template-parts/posts/loop/loop', 'layout' );

get_footer();
