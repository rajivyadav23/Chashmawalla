<?php
if ( ! function_exists( 'molla_get_related_posts' ) ) :
	function molla_get_related_posts( $post_id ) {

		$args = '';
		$args = wp_parse_args(
			$args,
			array(
				'showposts'           => molla_option( 'post_related_count' ),
				'post__not_in'        => array( $post_id ),
				'ignore_sticky_posts' => 0,
				'category__in'        => wp_get_post_categories( $post_id ),
				'orderby'             => molla_option( 'related_posts_sort_by' ),
				'order'               => molla_option( 'related_posts_sort_order' ),
			)
		);

		$query = new WP_Query( $args );

		return $query;
	}
endif;

function molla_get_post_meta_date_url( $atts = array() ) {
	$id   = get_the_ID();
	echo get_day_link(
		get_post_time( 'Y', false, $id, true ),
		get_post_time( 'm', false, $id, true ),
		get_post_time( 'j', false, $id, true )
	);;
}

add_action( 'wp_head', 'molla_post_add_actions' );
function molla_post_add_actions() {
	if ( is_single() && 'post' == get_post_type() ) {
		if ( 'fullwidth' == molla_option( 'blog_single_featured_image_type' ) ) {
			add_action( 'page_container_before', 'molla_post_add_thumbnail_before_content' );
		} elseif ( 'outer-content' == molla_option( 'blog_single_featured_image_type' ) ) {
			add_action( 'molla_before_blog', 'molla_post_add_thumbnail_before_content' );
		}
	}
}

if ( ! function_exists( 'molla_post_add_thumbnail_before_content' ) ) :

	/**
	 * Add featured image before page content in single post page
	 */
	function molla_post_add_thumbnail_before_content() {
		if ( 'video' == get_post_format() && get_post_meta( get_the_ID(), 'media_embed_code', true ) ) {
			get_template_part( 'template-parts/posts/partials/post', 'video' );
		} else {
			molla_get_template_part(
				'template-parts/posts/partials/post',
				'image',
				array(
					'p_src'      => 'single',
					'image_size' => 'full',
				)
			);
		}
	}
endif;


if ( ! function_exists( 'molla_excerpt' ) ) {

	/**
	 * Returns trimed post content according to your theme options
	 */
	function molla_excerpt( $length = 0, $unit = 0 ) {

		global $post;

		if ( ! $length ) {
			$length = (int) molla_option( 'blog_excerpt_length' );
		}

		if ( ! $unit ) {
			$unit = molla_option( 'blog_excerpt_unit' );
		}

		// Check if has excerpt
		if ( has_excerpt( $post ) ) {
			$output = get_the_excerpt( $post );
		}

		// Has no custom excerpt
		else {
			// Check if has more tag
			if ( strpos( $post->post_content, '<!--more-->' ) ) {
				$output = apply_filters( 'the_content', molla_strip_script_tags( get_the_content() ) );
			}
			// Has not more tag
			else {
				// Check if the unit is character
				if ( 'character' == $unit ) {
					$output = molla_strip_script_tags( get_the_content() );
					if ( mb_strlen( $output ) > $length ) {
						$output = mb_substr( $output, 1, $length ) . '...';
					}
				}

				// Check if the unit is word
				else {
					$output = wp_trim_words( molla_strip_script_tags( get_the_content() ), $length );
				}
			}
		}

		return $output;
	}
}
