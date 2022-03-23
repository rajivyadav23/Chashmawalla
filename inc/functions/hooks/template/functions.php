<?php
/**
 * Molla Hooked Template Functions
 */

function molla_set_layout() {

	$is_shop              = molla_is_shop();
	$is_in_category       = molla_is_in_category();
	$is_product           = molla_is_product();
	$is_customize_preview = is_customize_preview();

	global $molla_settings, $post;

	$molla_settings = array();
	$header_options = array();
	$footer_options = array();
	$page_options   = array();
	$ph_options     = array();

	// Check if use builder conditionals
	$builder_options = json_decode( get_theme_mod( 'builders' ), true );
	$using_builders  = array();

	if ( defined( 'MOLLA_CORE_VERSION' ) ) {
		if ( is_archive() ) { // Archive Page
			global $wp_query;
			$query = $wp_query->query;

			if ( get_queried_object() && property_exists( get_queried_object(), 'term_id' ) ) {
				$term_id        = get_queried_object()->term_id;
				$using_builders = json_decode( get_term_meta( $term_id, 'molla_builders', true ), true );
				if ( isset( $using_builders['archive'] ) ) {
					$using_builders = $using_builders['archive'];
				}
				$using_builders = molla_get_super_conditional_builders( true, $using_builders, array( get_term( $term_id )->taxonomy => $term_id ), $builder_options );
			} elseif ( isset( $query['post_type'] ) ) { // Post Type Archive
				if ( 'product' == $query['post_type'] ) {
					if ( isset( $builder_options['archive']['shop'] ) ) {
						$using_builders = $builder_options['archive']['shop'];
					}
				} else {
					if ( isset( $builder_options['archive'][ $query['post_type'] ] ) ) {
						$using_builders = $builder_options['archive'][ $query['post_type'] ];
					}
				}
				$using_builders = molla_get_super_conditional_builders( true, $using_builders, $query, $builder_options );
			}
		}

		if ( is_singular() ) { // Singular Page
			$super = array();
			if ( is_front_page() ) {
				if ( isset( $builder_options['single'] ) && isset( $builder_options['single']['front'] ) ) {
					$using_builders = $builder_options['single']['front'];
				}
			} else {
				$using_builders = json_decode( get_post_meta( $post->ID, 'molla_builders', true ), true );
				if ( isset( $using_builders['single'] ) ) {
					$using_builders = $using_builders['single'];
				}

				// Super Conditionals of Singular
				$super      = array();
				$taxonomies = get_post_taxonomies();

				foreach ( $taxonomies as $tax ) {
					$super = array_merge( $super, wp_get_post_terms( get_the_ID(), $tax, array( 'fields' => 'ids' ) ) );
				}
			}

			$using_builders = molla_get_super_conditional_builders( false, $using_builders, $super, $builder_options );
		}

		if ( is_home() ) { // Blog Page
			$using_builders = molla_get_super_conditional_builders( true, $using_builders, array( 'post_type' => 'post' ), $builder_options );
		}

		if ( is_404() ) { // 404 Page
			if ( isset( $builder_options['single'] ) && isset( $builder_options['single']['error'] ) ) {
				$using_builders = $builder_options['single']['error'];
			}
			$using_builders = molla_get_super_conditional_builders( false, $using_builders, array(), $builder_options );
		}
	}

	// Page layout is selected
	$post_id = molla_get_page_layout( $post );
	// Header Layout
	if ( isset( $using_builders['header'] ) && molla_get_post_id_by_name( 'header', $using_builders['header'] ) ) { // In case using of header builder
		$header_options['builder'] = $using_builders['header'];
	} else { // Not using header builder
		if ( isset( $using_builders['header'] ) ) {
			unset( $using_builders['header'] );
		}
		$header_options['width']    = molla_option( 'header_width' );
		$header_options['fixed']    = molla_option( 'header_position_fixed' );
		$header_options['sticky']   = array(
			'top'    => molla_option( 'header_top_in_sticky' ),
			'main'   => molla_option( 'header_main_in_sticky' ),
			'bottom' => molla_option( 'header_bottom_in_sticky' ),
		);
		$header_options['elements'] = molla_option( 'hb_options' );

		if ( ! $is_shop && $is_in_category ) {
			$meta_width = get_term_meta( get_queried_object()->term_id, 'cat_header_width', true );
			if ( $meta_width && 'default' != $meta_width ) {
				$header_options['width'] = $meta_width;
			}
		}

		if ( $is_product ) { // Single product type is selected
			$product_layout_type = get_post_meta( $post->ID, 'single_product_layout' );
			if ( count( $product_layout_type ) ) {
				if ( 'boxed' == $product_layout_type[0] ) {
					$header_options['width'] = 'container';
				} elseif ( 'full' == $product_layout_type[0] ) {
					$header_options['width'] = 'container-fluid';
				}
			}
		}

		if ( $post_id ) {
			if ( ! $is_customize_preview ) {
				$header_layout_type = get_post_meta( $post_id, 'header_layout_type' );
				if ( count( $header_layout_type ) ) {
					$header_layout_type = $header_layout_type[0];
				}

				if ( $header_layout_type ) {
					$header_layouts = get_theme_mod( 'molla_header_builder_layouts', array() );
					if ( ! isset( $header_layouts[ $header_layout_type ] ) ) {
						$header_layout_type = 'default';
					}
					$header_options['id']         = $header_layout_type;
					$header_options['elements']   = isset( $header_layouts[ $header_layout_type ]['elements'] ) ? $header_layouts[ $header_layout_type ]['elements'] : array();
					$header_options['custom_css'] = isset( $header_layouts[ $header_layout_type ]['custom_css'] ) ? $header_layouts[ $header_layout_type ]['custom_css'] : '';
				}

				if ( count( get_post_meta( $post_id, 'header' ) ) ) {
					if ( count( get_post_meta( $post_id, 'header_width' ) ) ) {
						$header_options['width'] = get_post_meta( $post_id, 'header_width' )[0];
					}
					if ( count( get_post_meta( $post_id, 'header_fixed' ) ) ) {
						$header_options['fixed'] = false;
					} else {
						$header_options['fixed'] = true;
					}
					$elems                = array(
						'',
						'top',
						'main',
						'bottom',
					);
					$header_options['bg'] = array();
					foreach ( $elems as $elem ) {
						$bg  = get_post_meta( $post_id, 'header_' . ( $elem ? ( $elem . '_' ) : $elem ) . 'bg' )[0];
						$idx = 'header' . ( $elem ? ( '-' . $elem ) : $elem );
						if ( ! empty( $bg['color'] ) ) {
							$header_options['bg'][ $idx ]['background-color'] = esc_attr( $bg['color'] );
						} elseif ( ! $elem ) {
							$header_options['bg'][ $idx ]['background-color'] = 'transparent';
						} else {
							$header_options['bg'][ $idx ]['background-color'] = 'inherit';
						}
						if ( ! empty( $bg['image'] ) ) {
							$bg_url = $bg['image'];
							$header_options['bg'][ $idx ]['background-image'] = 'url("' . esc_url( $bg['image'] ) . '")';
						}
						if ( ! empty( $bg['repeat'] ) ) {
							$header_options['bg'][ $idx ]['background-repeat'] = esc_attr( $bg['repeat'] );
						}
						if ( ! empty( $bg['position'] ) ) {
							$header_options['bg'][ $idx ]['background-position'] = esc_attr( $bg['position'] );
						}
						if ( ! empty( $bg['attachment'] ) ) {
							$header_options['bg'][ $idx ]['background-attachment'] = esc_attr( $bg['attachment'] );
						}
						if ( ! empty( $bg['size'] ) ) {
							$header_options['bg'][ $idx ]['background-size'] = esc_attr( $bg['size'] );
						}
					}
				}
				if ( count( get_post_meta( $post_id, 'sticky_header' ) ) ) {
					$header_options['sticky'] = array(
						'top'    => get_post_meta( $post_id, 'header_top_in_sticky' )[0],
						'main'   => get_post_meta( $post_id, 'header_main_in_sticky' )[0],
						'bottom' => get_post_meta( $post_id, 'header_bottom_in_sticky' )[0],
					);
				}
			}
		}
	}

	// Footer Layout
	if ( isset( $using_builders['footer'] ) && molla_get_post_id_by_name( 'footer', $using_builders['footer'] ) ) { // In case using of footer builder
		$footer_options['builder'] = $using_builders['footer'];
	} else { // Not using header builder
		if ( isset( $using_builders['footer'] ) ) {
			unset( $using_builders['footer'] );
		}
		$footer_options['width'] = molla_option( 'footer_width' );

		if ( ! $is_shop && $is_in_category ) {
			$meta_width = get_term_meta( get_queried_object()->term_id, 'cat_footer_width', true );
			if ( $meta_width && 'default' != $meta_width ) {
				$footer_options['width'] = $meta_width;
			}
		}

		if ( $is_product ) { // Single product type is selected
			if ( ! isset( $product_layout_type ) ) {
				$product_layout_type = get_post_meta( $post->ID, 'single_product_layout' );
			}
			if ( count( $product_layout_type ) ) {
				if ( 'boxed' == $product_layout_type[0] ) {
					$footer_options['width'] = 'container';
				} elseif ( 'full' == $product_layout_type[0] ) {
					$footer_options['width'] = 'container-fluid';
				}
			}
		}

		if ( $post_id ) {
			if ( count( get_post_meta( $post_id, 'footer_width' ) ) ) {
				$footer_options['width'] = get_post_meta( $post_id, 'footer_width' )[0];
			}
		}
	}

	// Sidebar Option
	if ( isset( $using_builders['sidebar'] ) && molla_get_post_id_by_name( 'sidebar', $using_builders['sidebar']['sidebar_id'] ) ) { // In case using of sidebar builder
		$sidebar_options['builder'] = $using_builders['sidebar']['sidebar_id'];
		$sidebar_options['active']  = true;
		$sidebar_options['pos']     = $using_builders['sidebar']['sidebar_pos'];
		$sidebar_options['width']   = $using_builders['sidebar']['sidebar_width'];
	} else { // Not using sidebar builder
		if ( isset( $using_builders['sidebar'] ) ) {
			unset( $using_builders['sidebar'] );
		}
		$sidebar_options = array( 'name' => '' );
		$pos             = molla_option( 'sidebar_option' );
		if ( $is_shop || $is_in_category ) { // Shop Page
			if ( $is_shop ) {
				if ( molla_option( 'shop_page_layout' ) ) {
					$pos = molla_option( 'shop_sidebar_pos' );
					if ( $post_id ) {
						$meta_pos = get_post_meta( $post_id, 'sidebar_pos' );
						if ( count( $meta_pos ) ) {
							$pos = $meta_pos[0];
						}
					}
				}
				if ( 'no' != $pos ) {
					if ( 'top' == $pos ) {
						$sidebar_options['name'] = 'shop-top-sidebar';
					} else {
						$sidebar_options['name'] = 'shop-sidebar';
					}
				}
			} else {
				$term = get_queried_object();
				$meta = get_term_meta( $term->term_id, 'cat_sidebar_pos', true );
				if ( 'default' == $meta ) {
					if ( molla_option( 'shop_page_layout' ) ) {
						$pos = molla_option( 'shop_sidebar_pos' );
					}
				} else {
					$pos = $meta;
				}
				if ( 'no' != $pos ) {
					if ( 'top' == $pos ) {
						$sidebar_options['name'] = 'shop-top-sidebar';
					} else {
						$sidebar_options['name'] = 'shop-sidebar';
					}
				}
			}
		} elseif ( $is_product ) { // Single Product Page
			if ( molla_option( 'single_product_page_layout' ) ) {
				$pos = molla_option( 'single_product_sidebar' );
			}
			// Single product type is selected
			if ( ! isset( $product_layout_type ) ) {
				$product_layout_type = get_post_meta( $post->ID, 'single_product_layout' );
			}
			if ( count( $product_layout_type ) ) {
				if ( 'boxed' == $product_layout_type[0] || 'full' == $product_layout_type[0] ) {
					$pos = 'right';
				}
			}
			// Page layout is selected
			if ( $post_id ) {
				$meta_pos = get_post_meta( $post_id, 'sidebar_pos' );
				if ( count( $meta_pos ) ) {
					$pos = $meta_pos[0];
				}
			}
			if ( 'no' != $pos ) {
				$sidebar_options['name'] = 'product-sidebar';
			}
		} elseif ( is_home() || is_archive() || is_search() ) { // Archive page
			if ( molla_option( 'blog_entry_page_layout' ) ) {
				$pos = molla_option( 'blog_entry_sidebar' );
			}
			if ( 'no' != $pos ) {
				$sidebar_options['name'] = 'main-sidebar';
			}
		} elseif ( is_page() ) { // Page
			$page_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
			if ( $page_template ) {
				if ( false === strpos( $page_template, 'sidebar' ) ) {
					$pos = 'no';
				} else {
					$page_template = str_replace( '.php', '', $page_template );
					$page_template = explode( '-', $page_template );
					$pos           = $page_template[ array_search( 'sidebar', $page_template ) - 1 ];
				}
			} else {
				if ( $post_id ) {
					$meta_pos = get_post_meta( $post_id, 'sidebar_pos' );
					if ( count( $meta_pos ) ) {
						$pos = $meta_pos[0];
					}
				}
			}
			if ( 'no' != $pos ) {
				$sidebar_options['name'] = 'main-sidebar';
			}
		} elseif ( is_single() ) { // Single Post
			if ( molla_option( 'blog_single_page_layout' ) ) {
				$pos = molla_option( 'blog_single_sidebar' );
			}
			// Page layout is selected
			if ( $post_id ) {
				$meta_pos = get_post_meta( $post_id, 'sidebar_pos' );
				if ( count( $meta_pos ) ) {
					$pos = $meta_pos[0];
				}
			}
			if ( 'no' != $pos ) {
				$sidebar_options['name'] = 'main-sidebar';
			}
		}
		$sidebar_options['pos']    = $pos ? $pos : 'left';
		$sidebar_options['active'] = 'sidebar' == get_post_type() || ( is_active_sidebar( $sidebar_options['name'] ) && 'no' != $sidebar_options['pos'] );
	}

	// Page content option
	$page_options['width'] = molla_option( 'page_width' );
	if ( $is_shop || $is_in_category ) {
		if ( molla_option( 'shop_page_layout' ) ) {
			$page_options['width'] = molla_option( 'shop_page_width' );
		}
		// Page layout is selected
		if ( $post_id ) {
			$page_width_meta = get_post_meta( $post_id, 'page_width' );
			if ( count( $page_width_meta ) ) {
				$page_options['width'] = $page_width_meta[0];
			}
		}
		if ( ! $is_shop ) {
			$meta_width = get_term_meta( get_queried_object()->term_id, 'cat_page_width', true );
			if ( $meta_width && 'default' != $meta_width ) {
				$page_options['width'] = $meta_width;
			}
		}
	} elseif ( is_single() ) {
		if ( $is_product ) {
			if ( molla_option( 'single_product_page_layout' ) ) {
				$page_options['width'] = molla_option( 'single_product_width' );
			}

			// Single product type is selected
			if ( ! isset( $product_layout_type ) ) {
				$product_layout_type = get_post_meta( $post->ID, 'single_product_layout' );
			}
			if ( count( $product_layout_type ) ) {
				if ( 'boxed' == $product_layout_type[0] ) {
					$page_options['width'] = 'container';
				} elseif ( 'full' == $product_layout_type[0] ) {
					$page_options['width'] = 'container-fluid';
				}
			}
		}
		if ( 'post' == get_post_type() ) {
			if ( molla_option( 'blog_single_page_layout' ) ) {
				$page_options['width'] = molla_option( 'blog_single_page_width' );
			}
		}
		// Page layout is selected
		if ( $post_id ) {
			$page_width_meta = get_post_meta( $post_id, 'page_width' );
			if ( count( $page_width_meta ) ) {
				$page_options['width'] = $page_width_meta[0];
			}
		}
	} else {
		if ( is_page_template( array( 'page-container-fluid-left-sidebar.php', 'page-container-fluid-right-sidebar.php', 'page-container-fluid.php' ) ) ) {
			$page_options['width'] = 'container-fluid';
		} elseif ( is_page_template( array( 'page-container-left-sidebar.php', 'page-container-right-sidebar.php', 'page-container.php' ) ) ) {
			$page_options['width'] = 'container';
		} elseif ( is_page_template( array( 'page-full-left-sidebar.php', 'page-full-right-sidebar.php', 'page-fullwidth.php' ) ) ) {
			$page_options['width'] = '';
		} elseif ( is_page_template( array( 'default' ) ) ) {
			if ( $post_id ) {
				$page_width_meta = get_post_meta( $post_id, 'page_width' );
				if ( count( $page_width_meta ) ) {
					$page_options['width'] = $page_width_meta[0];
				}
			}
		}
	}

	// Page header option
	$parent_id = wp_get_post_parent_id( $post );
	$title     = '';
	$subtitle  = '';
	$content   = molla_option( 'page_header_content' );

	$is_parallax = molla_option( 'page_header_parallax' );
	$post_layout = molla_option( 'post_layout' );

	$page_width = $page_options['width'];

	$style    = [];
	$internal = '';
	$bg_url   = '';

	if ( $post_id ) {
		// page header params
		$custom_type = count( get_post_meta( $post_id, 'page_header_type' ) ) ? get_post_meta( $post_id, 'page_header_type' )[0] : '';
		if ( $custom_type ) {
			$custom_content = count( get_post_meta( $post_id, 'page_header_content' ) ) ? get_post_meta( $post_id, 'page_header_content' )[0] : '';
			if ( $custom_content ) {
				$content = $custom_content;
			}

			$bg = get_post_meta( $post_id, 'page_header_background' )[0];
			if ( ! empty( $bg['color'] ) ) {
				$style['background-color'] = esc_attr( $bg['color'] );
			}
			if ( ! empty( $bg['image'] ) ) {
				$bg_url                    = $bg['image'];
				$style['background-image'] = 'url("' . esc_url( $bg['image'] ) . '")';
			}
			if ( ! empty( $bg['repeat'] ) ) {
				$style['background-repeat'] = esc_attr( $bg['repeat'] );
			}
			if ( ! empty( $bg['position'] ) ) {
				$style['background-position'] = esc_attr( $bg['position'] );
			}
			if ( ! empty( $bg['attachment'] ) ) {
				$style['background-attachment'] = esc_attr( $bg['attachment'] );
			}
			if ( ! empty( $bg['size'] ) ) {
				$style['background-size'] = esc_attr( $bg['size'] );
			}
		}
	}

	if ( ! $post_id || ! $custom_type ) {
		$bg = molla_option( 'page_header_bg' );
		if ( is_array( $bg ) ) {
			foreach ( $bg as $key => $value ) {
				if ( $value ) {
					if ( 'background-image' == $key ) {
						$bg_url        = $value;
						$style[ $key ] = 'url("' . esc_url( $value ) . '")';
					} else {
						$style[ $key ] = esc_attr( $value );
					}
				}
			}
		}
	}

	if ( $is_shop ) {
		$shop_page_id = wc_get_page_id( 'shop' );

		$title      = 'Shop';
		$meta_title = get_post_meta( $shop_page_id, 'title' );
		if ( $meta_title ) {
			$title = $meta_title[0];
		}
		$title = apply_filters( 'molla_woo_shop_page_title', $title );

		$meta_subtitle = get_post_meta( $shop_page_id, 'subtitle' );
		if ( $meta_subtitle ) {
			$subtitle = $meta_subtitle[0];
		} else {
			$shop_args = molla_get_loop_columns();
			if ( 'list' == $shop_args['style'] ) {
				$subtitle = 'List';
			} else {
				$subtitle = ucfirst( $post_layout ) . ' ' . $shop_args['columns'] . ' ' . esc_html__( 'Columns', 'molla' );
			}
		}
		$subtitle = apply_filters( 'molla_woo_shop_page_subtitle', $subtitle );
	} elseif ( class_exists( 'WooCommerce' ) && ( is_product_category() || is_category() ) ) {
		$current_term = get_queried_object();
		$title        = $current_term->name;
	} elseif ( is_archive() || is_search() || is_home() ) {
		if ( class_exists( 'WooCommerce' ) ) {
			$breadcrumbs = new WC_Breadcrumb();
			$crumbs      = $breadcrumbs->generate();
			$first       = true;
			foreach ( $crumbs as $crumb ) {
				$title .= ( $first ? '' : ' / ' ) . $crumb[0];

				if ( $first && $crumb[0] ) {
					$first = false;
				}
			}
		}
	} else {
		if ( $post && get_post_meta( $post->ID, 'title' ) ) {
			$title = get_post_meta( $post->ID, 'title' )[0];
		} else {
			$title = get_the_title();
		}

		if ( $post && get_post_meta( $post->ID, 'subtitle' ) ) {
			$subtitle = get_post_meta( $post->ID, 'subtitle' )[0];
		}
		if ( ! $subtitle && $parent_id ) {
			$subtitle = get_the_title( $parent_id );
		}
	}

	$classes          = 'page-header text-center';
	$data_page_header = ' ';

	if ( ! is_admin() && ! $is_customize_preview && ! molla_ajax() && molla_option( 'lazy_load_img' ) && isset( $style['background-image'] ) ) {
		if ( $is_parallax ) {
			$data_page_header .= 'data-plx-lazyload="true"';
		} else {
			$classes          .= ' molla-lazyload-back';
			$data_page_header .= 'data-src="' . $bg_url . '"';
		}
	}

	if ( $is_parallax ) {
		$classes .= ' parallax-container';

		if ( isset( $style['background-image'] ) ) {

			$data_page_header .= ' data-plx-img="' . $bg_url . '"';
			if ( isset( $style['background-repeat'] ) ) {
				$data_page_header .= ' data-plx-img-repeat="' . $style['background-repeat'] . '"';
			}
			if ( isset( $style['background-position'] ) ) {
				$data_page_header .= ' data-plx-img-pos="' . $style['background-position'] . '"';
			}
			if ( isset( $style['background-attachment'] ) ) {
				$data_page_header .= ' data-plx-img-att="' . $style['background-attachment'] . '"';
			}
			if ( isset( $style['background-size'] ) ) {
				$data_page_header .= ' data-plx-size="' . $style['background-size'] . '"';
			}
			$data_page_header .= ' data-plx-speed="' . molla_option( 'page_header_plx_speed' ) . '"';
		}
	} else {
		foreach ( $style as $key => $value ) {
			$internal .= $key . ':' . $value . ';';
		}
	}

	$ph_options = array(
		'page_width'       => $page_width,
		'classes'          => $classes,
		'internal'         => $internal,
		'data_page_header' => $data_page_header,
		'title'            => $title,
		'subtitle'         => $subtitle,
		'content'          => $content,
	);

	// page top, inner-top, bottom, inner-bottom blocks
	$locations    = array(
		'top',
		'inner_top',
		'inner_bottom',
		'bottom',
	);
	$meta_options = array();

	foreach ( $locations as $location ) {
		if ( class_exists( 'WooCommerce' ) && is_product_category() ) {
			$term       = get_queried_object();
			$block_name = get_term_meta( $term->term_id, 'content_' . $location . '_block' );
		} else {
			$block_name = get_post_meta( $post_id, 'content_' . $location . '_block' );
		}
		if ( $block_name ) {
			$meta_options[ $location ] = molla_get_post_id_by_name( 'block', $block_name[0] );
		}
	}

	$molla_settings = array(
		'header'      => $header_options,
		'footer'      => $footer_options,
		'page'        => $page_options,
		'sidebar'     => $sidebar_options,
		'page_header' => $ph_options,
		'meta_box'    => $meta_options,
	);
	if ( isset( $using_builders['popup'] ) && molla_get_post_id_by_name( 'popup', $using_builders['popup']['popup_id'] ) ) {
		$molla_settings['popup'] = $using_builders['popup'];
	}
	if ( $is_product && isset( $using_builders['product_layout'] ) ) {
		$molla_settings['product_layout'] = $using_builders['product_layout'];
	}

	$molla_settings = apply_filters( 'molla_global_settings', $molla_settings, $post );
}

function molla_get_super_conditional_builders( $is_archive = false, $self = false, $super = false, $global = false ) {
	$builders = array( 'header', 'footer', 'sidebar', 'popup', 'product_layout' );
	$missing  = array(); // builders that are not set itself
	foreach ( $builders as $builder ) {
		if ( ! is_array( $self ) || ! isset( $self[ $builder ] ) ) {
			$missing[] = $builder;
		}
	}

	if ( is_array( $super ) && ! isset( $super['post_type'] ) ) {
		$super = array_fill_keys( $super, '' );
	}

	$idx = $is_archive ? 'archive' : 'single';

	foreach ( $missing as $miss ) {
		foreach ( $super as $key => $value ) {
			if ( 'post_type' == $key ) {
				continue;
			}

			$val = json_decode( get_term_meta( $key, 'molla_builders', true ), true );
			$val = ( is_array( $val ) && isset( $val[ $idx ][ $miss ] ) ) ? $val[ $idx ][ $miss ] : '';

			if ( ! $val ) {
				continue;
			}

			$self[ $miss ] = $val;
		}

		if ( isset( $self[ $miss ] ) ) {
			continue;
		}

		$val = '';
		if ( ! $is_archive ) {
			$taxonomies = get_post_taxonomies();
			foreach ( $taxonomies as $tax ) {
				if ( isset( $global[ $idx ][ $tax ] ) && isset( $global[ $idx ][ $tax ][ $miss ] ) ) { // Post Taxonomies 'All' selected
					$val = $global[ $idx ][ $tax ][ $miss ];
					break;
				}
			}

			if ( ! $val ) {
				$post_type = get_post_type();
				if ( isset( $global[ $idx ][ $post_type ] ) && isset( $global[ $idx ][ $post_type ][ $miss ] ) ) { // For Single Post Types 'All' selected
					$val = $global[ $idx ][ $post_type ][ $miss ];
				}
			}
		} else {
			if ( isset( get_term( $key )->taxonomy ) ) { // For Taxonomy 'All' selected
				$tax = get_term( $key )->taxonomy;
				if ( isset( $global[ $idx ][ $tax ] ) && isset( $global[ $idx ][ $tax ][ $miss ] ) ) {
					$val = $global[ $idx ][ $tax ][ $miss ];
				}
			}

			if ( ! $val ) {
				$post_type = isset( $super['post_type'] ) ? $super['post_type'] : get_post_type();
				if ( isset( $global[ $idx ][ $post_type . '_archive' ] ) && isset( $global[ $idx ][ $post_type . '_archive' ][ $miss ] ) ) { // For Single Post Types 'All' selected
					$val = $global[ $idx ][ $post_type . '_archive' ][ $miss ];
				}
			}
		}

		if ( ! $val ) {
			if ( isset( $global[ 'all_' . $idx ] ) && isset( $global[ 'all_' . $idx ][ $miss ] ) ) { // For All Archive / Singulars
				$val = $global[ 'all_' . $idx ][ $miss ];
			}
		}

		if ( ! $val ) {
			if ( isset( $global['default'] ) && isset( $global['default'][ $miss ] ) ) { // For Entire Site
				$val = $global['default'][ $miss ];
			}
		}

		if ( $val ) {
			$self[ $miss ] = $val;
		}
	}
	return apply_filters( 'molla_super_conditionals', $self, $is_archive );
}

if ( ! function_exists( 'molla_add_to_wishlist_classes' ) ) :
	function molla_add_to_wishlist_classes( $classes ) {
		if ( wc_get_loop_prop( 'wishlist_style' ) && 'no' != wc_get_loop_prop( 'wishlist_style' ) ) {
			$classes .= ' btn-expandable';
		}
		if ( 'after-add-to-cart' == wc_get_loop_prop( 'wishlist_pos' ) && 'classic' != wc_get_loop_prop( 'product_style' ) ) {
			$classes .= ' btn-product';
		}
		return $classes;
	}
endif;

if ( ! function_exists( 'molla_out_stock_html' ) ) :
	function molla_out_stock_html( $classes ) {
		global $product;
		if ( 'outofstock' == $product->get_stock_status() ) {
			echo ( '<div class="product-label-text">' . esc_html__( 'Out Of Stock', 'molla' ) . '</div>' );
		}
	}
endif;

if ( ! function_exists( 'molla_print_sidebar_overlay' ) ) :
	function molla_print_sidebar_overlay() {
		echo '<a href="#" class="sidebar-toggler"><i class="fa fa-chevron-' . ( is_rtl() ? 'left' : 'right' ) . '"></i></a><div class="sidebar-overlay"></div>';
	}
endif;

if ( ! function_exists( 'molla_set_cookie' ) ) :
	function molla_set_cookie() {

		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		if ( ! empty( $_GET['layout_type'] ) && ! empty( $_GET['device_width'] ) && ! empty( $_GET['life_time'] ) ) {
			setcookie( 'layout_type', sanitize_title( $_GET['layout_type'] ), time() + ( 86400 ), '/' );
			$_COOKIE['layout_type']  = esc_html( $_GET['layout_type'] );
			$_COOKIE['device_width'] = esc_html( $_GET['device_width'] );
		}

		// phpcs:enable
	}
endif;

if ( ! function_exists( 'molla_add_page_header_action' ) ) :
	function molla_add_page_header_action() {
		global $post;

		$page_header_active = molla_option( 'page_header_show' );
		if ( is_single() ) {
			if ( molla_option( 'single_blog_page_header' ) ) {
				$page_header_active = true;
			} else {
				$page_header_active = false;
			}
		}
		if ( molla_is_product() ) {
			if ( molla_option( 'single_product_page_header' ) ) {
				$page_header_active = true;
			} else {
				$page_header_active = false;
			}
		}
		if ( $post ) {
			$post_id = molla_get_page_layout( $post );

			$active = get_post_meta( $post_id, 'page_header' );
			if ( $active ) {
				if ( 'show' == $active[0] ) {
					$page_header_active = true;
				} else {
					$page_header_active = false;
				}
			}
		}
		if ( $page_header_active ) {
			add_action( 'page_content_before', 'molla_page_header' );
		}
	}
endif;

if ( ! function_exists( 'molla_page_header' ) ) :
	function molla_page_header() {
		get_template_part( 'template-parts/partials/page', 'header' );
	}
endif;

if ( ! function_exists( 'molla_page_block_actions' ) ) :
	function molla_page_block_actions() {
		add_action( 'page_container_before', 'molla_page_blocks' );
		add_action( 'page_content_inner_top', 'molla_page_blocks' );
		add_action( 'page_content_inner_bottom', 'molla_page_blocks' );
		add_action( 'page_container_after', 'molla_page_blocks' );
	}
endif;


if ( ! function_exists( 'molla_page_blocks' ) ) {
	function molla_page_blocks( $location ) {
		global $post;

		$block_name = '';

		if ( class_exists( 'WooCommerce' ) && is_product_category() ) {
			$term = get_queried_object();
			if ( 'top' == $location ) {
				$block_name = get_term_meta( $term->term_id, 'content_top_block' );
			} elseif ( 'inner_top' == $location ) {
				$block_name = get_term_meta( $term->term_id, 'content_inner_top_block' );
			} elseif ( 'inner_bottom' == $location ) {
				$block_name = get_term_meta( $term->term_id, 'content_inner_bottom_block' );
			} elseif ( 'bottom' == $location ) {
				$block_name = get_term_meta( $term->term_id, 'content_bottom_block' );
			}
		} else {
			$post_id = molla_get_page_layout( $post );

			if ( 'top' == $location ) {
				$block_name = get_post_meta( $post_id, 'content_top_block' );
			} elseif ( 'inner_top' == $location ) {
				$block_name = get_post_meta( $post_id, 'content_inner_top_block' );
			} elseif ( 'inner_bottom' == $location ) {
				$block_name = get_post_meta( $post_id, 'content_inner_bottom_block' );
			} elseif ( 'bottom' == $location ) {
				$block_name = get_post_meta( $post_id, 'content_bottom_block' );
			}
		}

		$name = $block_name ? $block_name[0] : '';
		if ( $name && function_exists( 'molla_print_custom_post' ) ) {
			molla_print_custom_post( 'block', $name );
		}
	}
}


// molla Contact Form Functions
if ( ! function_exists( 'molla_wpcf7_add_form_tag_submit' ) ) {
	function molla_wpcf7_add_form_tag_submit() {
		wpcf7_remove_form_tag( 'submit' );
		wpcf7_add_form_tag( 'submit', 'molla_wpcf7_submit_form_tag_handler' );
	}
}

if ( ! function_exists( 'molla_wpcf7_validate_arg' ) ) :
	function molla_wpcf7_validate_arg() {
		return '';
	}
endif;

if ( ! function_exists( 'molla_wpcf7_submit_form_tag_handler' ) ) {
	function molla_wpcf7_submit_form_tag_handler( $tag ) {
		$class = wpcf7_form_controls_class( $tag->type );

		$atts = array();

		$atts['class']    = $tag->get_class_option( $class ) . ' btn';
		$atts['id']       = $tag->get_id_option();
		$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );

		$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

		if ( empty( $value ) ) {
			$value = esc_html__( 'Send', 'molla' );
		}

		$atts['type']  = 'submit';
		$atts['value'] = $value;

		$atts = wpcf7_format_atts( $atts );

		$html = sprintf( '<button %1$s>%2$s</button>', $atts, esc_html( $value ) );

		return $html;
	}
}

if ( ! function_exists( 'molla_widget_categories_args' ) ) :
	/**
	 * Category Widget List.
	 */
	function molla_widget_categories_args( $args, $instance ) {
		require_once MOLLA_CLASS . '/molla_cat_list_walker.php';
		$args['walker'] = new Molla_Walker_Category;
		return $args;
	}
endif;

if ( ! function_exists( 'molla_empty_message' ) ) :
	function molla_empty_message() {
		echo '<p class="woocommerce-info">' . esc_html__( 'Sorry, but nothing matched your terms. Please try again.', 'molla' ) . '</p>';
	}
endif;
