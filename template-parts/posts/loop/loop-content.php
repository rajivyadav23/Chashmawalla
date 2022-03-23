<?php
global $wp_query;

if ( ! isset( $posts_query ) ) {
	$posts_query = $wp_query;

	$layout_mode          = molla_option( 'blog_entry_layout' );
	$cols_upper_desktop   = '';
	$columns              = molla_option( 'blog_entry_cols' );
	$columns_tablet       = '';
	$columns_mobile       = '';
	$cols_under_mobile    = '';
	$slider_nav           = 'yes';
	$slider_dot           = 'no';
	$slider_nav_show      = 'yes';
	$filter               = molla_option( 'blog_entry_filter' );
	$blog_filter_pos      = molla_option( 'blog_filter_pos' );
	$blog_slider_nav_pos  = 'owl-nav-inside';
	$blog_slider_nav_type = 'owl-full';
	$spacing              = molla_option( 'grid_gutter_width' );
	$view_more            = molla_option( 'blog_view_more_type' );
	$blog_type            = molla_option( 'blog_entry_type' );
	$img_width            = molla_option( 'blog_img_width' );
	$show_op              = molla_option( 'blog_entry_visible_op' );
	$align                = molla_option( 'blog_entry_align' );
	$excerpt_by           = molla_option( 'blog_excerpt_unit' );
	$excerpt_length       = molla_option( 'blog_excerpt_length' );
	$blog_more_label      = molla_option( 'blog_more_label' );
	$blog_more_icon       = molla_option( 'blog_more_icon' );
	$args                 = $posts_query->query;
	$args['paged']        = 1;
} else {
	if ( 'custom' != $excerpt_type ) {
		$excerpt_by     = molla_option( 'blog_excerpt_unit' );
		$excerpt_length = molla_option( 'blog_excerpt_length' );
	}
	$filter = false;
}
/* Free Features */
if ( ! isset( $layout_mode ) ) {
	$layout_mode       = 'grid';
	$columns           = 1;
	$columns_tablet    = '';
	$columns_mobile    = '';
	$cols_under_mobile = '';
	$filter            = false;
}
/* End */

if ( $posts_query->have_posts() ) {

	$category_html_escaped  = '';
	$plugin_options_escaped = '';
	$filter_active          = 'creative' == $layout_mode || $filter;

	if ( $filter ) {

		$cats = array();

		foreach ( get_categories() as $cat ) {
			$cats[ $cat->term_id ] = 0;
		}

		foreach ( $posts_query->posts as $elem ) {
			$elem_cats = get_the_category( $elem->ID );
			foreach ( $elem_cats as $elem_cat ) {
				$cats[ $elem_cat->term_id ] ++;
			}
		}
		foreach ( $cats  as $id => $count ) {
			$term                   = get_term( $id );
			$category_html_escaped .= '<li class="nav-item' . esc_attr( ! $count ? ' d-none' : '' ) . '"><a href="' . esc_url( get_term_link( $id, 'category' ) ) . '" class="' . esc_attr( $term->slug ) . '" data-filter=".' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '<span class="count">' . $count . '</span></a></li>';
		}

		$category_html_escaped  = '<ul class="nav nav-filter justify-content-' . esc_attr( $blog_filter_pos ) . '"><li class="nav-item active"><a href="#" data-filter="*">' . esc_html__( 'All Blog Posts', 'molla' ) . '<span class="count">' . count( $posts_query->posts ) . '</span></a></li>' . $category_html_escaped;
		$category_html_escaped .= '</ul>';
	}

	$add_class = '';

	if ( $filter_active ) {
		wp_enqueue_script( 'isotope-pkgd' );

		$add_class             .= ' grid';
		$plugin_options_escaped = ' data-toggle="isotope"';

		if ( 'grid' == $layout_mode ) {
			$options                 = array();
			$options['layoutMode']   = 'fitRows';
			$options['fitRows']      = array(
				'gutter' => 0,
			);
			$plugin_options_escaped .= ' data-isotope-options="' . esc_attr( json_encode( $options ) ) . '"';
		}
	} elseif ( 'slider' == $layout_mode ) {
		wp_enqueue_script( 'owl-carousel' );

		$add_class .= ' owl-carousel owl-simple carousel-with-shadow' . ( $blog_slider_nav_pos ? ' ' . $blog_slider_nav_pos : '' ) . ( $blog_slider_nav_type ? ' ' . $blog_slider_nav_type : '' ) . ( 'yes' != $slider_nav_show ? ' owl-nav-show' : '' );

		$options           = array();
		$options['margin'] = (int) $spacing;
		$options['loop']   = isset( $slider_loop ) ? $slider_loop : false;
		if ( isset( $slider_auto_play ) && $slider_auto_play ) {
			$options['autoplay']        = $slider_auto_play;
			$options['autoplayTimeout'] = $slider_auto_play_time;
		}
		$options['center'] = isset( $slider_center ) ? $slider_center : false;


		$options['responsive'] = array(
			0    => array(
				'items' => $cols_under_mobile,
				'dots'  => isset( $slider_dot_mobile ) && 'yes' == $slider_dot_mobile ? true : false,
				'nav'   => isset( $slider_nav_mobile ) && 'yes' == $slider_nav_mobile ? true : false,
			),
			576  => array(
				'items' => $columns_mobile,
				'dots'  => isset( $slider_dot_mobile ) && 'yes' == $slider_dot_mobile ? true : false,
				'nav'   => isset( $slider_nav_mobile ) && 'yes' == $slider_nav_mobile ? true : false,
			),
			768  => array(
				'items' => $columns_tablet,
				'dots'  => isset( $slider_dot_tablet ) && 'yes' == $slider_dot_tablet ? true : false,
				'nav'   => isset( $slider_nav_tablet ) && 'yes' == $slider_nav_tablet ? true : false,
			),
			992  => array(
				'items' => $columns,
				'dots'  => 'yes' == $slider_dot ? true : false,
				'nav'   => 'yes' == $slider_nav ? true : false,
			),
			1200 => array(
				'items' => $cols_upper_desktop,
				'dots'  => 'yes' == $slider_dot ? true : false,
				'nav'   => 'yes' == $slider_nav ? true : false,
			),
		);
		if ( function_exists( 'molla_carousel_options' ) && function_exists( 'molla_carousel_responsive_classes' ) ) {
			$options['responsive'] = molla_carousel_options( $options['responsive'] );
			$add_class            .= molla_carousel_responsive_classes( $options['responsive'] );
		}
		$plugin_options_escaped = ' data-toggle="owl" data-owl-options="' . esc_attr( json_encode( $options ) ) . '"';
	}

	if ( $spacing ) {
		$add_class .= ' sp-' . $spacing;
	}
	if ( 'slider' != $layout_mode ) {
		$add_class .= ' row';

		if ( function_exists( 'molla_get_column_class' ) ) {

			$col_args['xxl'] = $columns;
			$col_args['xl']  = $cols_upper_desktop;
			$col_args['md']  = $columns_tablet;
			$col_args['sm']  = $columns_mobile;
			$col_args['xs']  = $cols_under_mobile;

			$add_class .= molla_get_column_class( $col_args );
		}
	}

	if ( 'slider' != $layout_mode && 'scroll' == $view_more ) {
		wp_enqueue_script( 'molla-infinite-scroll' );
		$add_class .= ' infinite-scroll';
	}

	$atts_args                    = [];
	$atts_args['name']            = 'posts';
	$atts_args['layout_mode']     = $layout_mode;
	$atts_args['filter']          = $filter;
	$atts_args['show_op']         = $show_op;
	$atts_args['align']           = $align;
	$atts_args['excerpt_by']      = $excerpt_by;
	$atts_args['excerpt_length']  = $excerpt_length;
	$atts_args['blog_type']       = $blog_type;
	$atts_args['blog_more_label'] = $blog_more_label;
	$atts_args['blog_more_icon']  = $blog_more_icon;
	$atts_args['max_num_pages']   = $posts_query->max_num_pages;

	$page_args = [];

	if ( isset( $args ) ) {
		foreach ( $args as $key => $value ) {
			$page_args[ $key ] = $value;
		}
	}

	echo molla_filter_output( $category_html_escaped );

	echo '<div class="posts ' . esc_attr( 'columns-' . $columns . $add_class ) . apply_filters( 'molla_loop_post_classes', '' ) . '"' . $plugin_options_escaped . ' data-props="' . esc_attr( json_encode( $atts_args ) ) . '" data-page-props="' . esc_attr( json_encode( $page_args ) ) . '">';

	while ( $posts_query->have_posts() ) {
		$posts_query->the_post();
		include MOLLA_DIR . '/template-parts/posts/loop/loop.php';
	}
	wp_reset_postdata();
	?>

	</div>

	<?php
	if ( 'pagination' == $view_more ) {
		molla_pagination( $posts_query, molla_option( 'pagination_pos' ) );
	} elseif ( '' != $view_more ) {
		if ( $posts_query->max_num_pages > 1 ) {
			$label = isset( $view_more_label ) && $view_more_label ? $view_more_label : esc_html__( 'View more articles', 'molla' );
			$icon  = isset( $view_more_icon ) && $view_more_icon ? ( '<i class="' . $view_more_icon . '"></i>' ) : '';
			?>
			<div class="more-container text-center">
				<a href="#" class="btn btn-more more-articles" style="<?php echo esc_attr( 'scroll' == $view_more ? ' display: none;' : '' ); ?>"><span><?php echo molla_strip_script_tags( $label ) . '</span>' . $icon; ?></a>
			</div>
			<?php
		}
	}
} else {
	if ( ! isset( $is_widget ) ) {
		do_action( 'molla_empty_message' );
	}
}
