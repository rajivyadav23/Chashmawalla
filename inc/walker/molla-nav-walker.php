<?php

/*
 * Molla custom wp_nav_menu walker
 */

add_filter( 'wp_nav_menu_args', 'molla_nav_menu_args' );

function molla_nav_menu_args( $args ) {
	$args['container']   = '';
	$args['before']      = '';
	$args['after']       = '';
	$args['link_before'] = '';
	$args['link_after']  = '';
	$args['fallback_cb'] = false;
	$args['walker']      = new Molla_Custom_Nav_Walker();
	if ( molla_option( 'lazy_load_menu' ) && ! is_customize_preview() ) {
		if ( ! isset( $args['lazy'] ) ) {
			$args['lazy']  = true;
			$args['depth'] = 1;
		}
	}
	return apply_filters( 'molla_nav_menu_args', $args );
}

if ( ! class_exists( 'Molla_Custom_Nav_Walker' ) ) {
	class Molla_Custom_Nav_Walker extends Walker_Nav_Menu {

		/**
		 * Starts the list before the elements are added.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {

			if ( isset( $args->lazy ) && $args->lazy && ! is_customize_preview() ) {
				return;
			}

			$indent = str_repeat( "\t", $depth );

			$classes = array();
			$style   = '';

			// Default class.
			$classes[] = 'sub-menu';

			// Additional class
			if ( ( ! $this->megamenu || ( $this->megamenu && $this->megamenu_width ) ) && 0 == $depth ) {
				$classes[] = $this->megamenu_pos ? $this->megamenu_pos : 'pos-left';
			}

			// Additional css
			if ( $this->megamenu_width && 0 == $depth ) {
				$style .= ' style="width: ' . esc_attr( $this->megamenu_width ) . 'px"';

			}

			/**
			 * Filters the CSS class(es) applied to a menu list element.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$output .= "\n{$indent}<ul$class_names" . $style . ">\n";
		}

		/**
		 * Ends the list of after the elements are added.
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {

			if ( isset( $args->lazy ) && $args->lazy && ! is_customize_preview() ) {
				return;
			}

			$indent  = str_repeat( "\t", $depth );
			$output .= "$indent</ul>\n";
		}

		/**
		 * Starts the menu element output
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			// Set ul class
			if ( $depth === 0 ) {
				$this->megamenu       = get_post_meta( $item->ID, '_menu_item_megamenu', true );
				$this->megamenu_col   = get_post_meta( $item->ID, '_menu_item_megamenu_col', true );
				$this->megamenu_width = get_post_meta( $item->ID, '_menu_item_megamenu_width', true );
				$this->megamenu_pos   = get_post_meta( $item->ID, '_menu_item_popup_pos', true );
			}

			$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			$style     = '';

			$use_block = get_post_meta( $item->ID, '_menu_item_block_slug', true ) && ! $item->grid_col && ! $item->subtitle;

			// Add custom classes
			if ( ! $depth && $item->megamenu ) {
				$classes[] = 'megamenu';

				if ( ! $item->megamenu_width ) {
					$classes[] = 'megamenu-container';
				}
			}

			if ( $this->has_children && ! $this->megamenu ) {
				$classes[] = 'sf-with-ul';
			}

			if ( $item->nolink ) {
				$classes[] = 'no-link';
			}

			if ( $item->grid_col ) {
				$classes[] = 'menu-grid-col';
			}

			if ( $item->subtitle ) {
				$classes[] = 'menu-subtitle';
			}

			if ( $item->megamenu_col ) {
				$classes[] = 'menu-col-' . $item->megamenu_col;
			}

			if ( $item->menuback ) {
				$style .= ' style="' . 'background: no-repeat center/cover url(' . esc_attr( $item->menuback ) . ');"';
			}

			/**
			 * Filters the arguments for a single nav menu item.
			 */
			$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

			/**
			 * Filters the CSS classes applied to a menu item's list item element.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			 * Filters the ID applied to a menu item's list item element.
			 */
			$id      = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id      = $id ? ' id="' . esc_attr( $id ) . '"' : '';
			$output .= $indent . '<li' . $id . $class_names . $style . '>';
			if ( $use_block ) {
				$name = get_post_meta( $item->ID, '_menu_item_block_slug', true );

				ob_start();

				if ( function_exists( 'molla_print_custom_post' ) ) {
					molla_print_custom_post( 'block', $name );
				} else {
					echo '<strong>Plugin not installed.</strong>';
				}

				$output .= ob_get_clean();
				return;
			}

			$atts           = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			if ( '_blank' === $item->target && empty( $item->xfn ) ) {
				$atts['rel'] = 'noopener noreferrer';
			} else {
				$atts['rel'] = $item->xfn;
			}
			$atts['href']         = ! empty( $item->url ) ? $item->url : '';
			$atts['aria-current'] = $item->current ? 'page' : '';

			/**
			 * Filters the HTML attributes applied to a menu item's anchor element.
			 */
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			/** This filter is documented in wp-includes/post-template.php */
			$title = apply_filters( 'the_title', $item->title, $item->ID );

			/**
			 * Filters a menu item's title.
			 */
			$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

			$item_output  = $args->before;
			$item_output .= '<a' . $attributes . '>';

			$icon_class = get_post_meta( $item->ID, '_menu_item_icon_class', true );
			if ( ! empty( $icon_class ) ) {
				$item_output .= '<i class="' . esc_attr( $icon_class ) . '"></i>';
			}

			$item_output .= $args->link_before . $title . $args->link_after;

			if ( $item->badge_status ) {
				$badge_texts = array(
					'hot'     => esc_html__( 'Hot', 'molla' ),
					'new'     => esc_html__( 'New', 'molla' ),
					'popular' => esc_html__( 'Popular', 'molla' ),
				);
				$item_output .= '<span><span class="tip tip-' . $item->badge_status . '">' . esc_html( isset( $badge_texts[ $item->badge_status ] ) ? $badge_texts[ $item->badge_status ] : $item->badge_status ) . '</span></span>';
			}
			$item_output .= '</a>';
			$item_output .= $args->after;

			/**
			 * Filters a menu item's starting output.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

			if ( isset( $args->lazy ) && $args->lazy && ! is_customize_preview() ) {
				if ( 0 == $depth && $this->has_children ) {
					if ( $this->megamenu ) {
						$megamenu_classes = 'sub-menu megamenu';
						if ( ! $this->megamenu_width ) {
							$megamenu_classes .= ' megamenu-container';
						} else {
							$megamenu_classes .= ' pos-' . ( $this->megamenu_pos ? $this->megamenu_pos : 'left' );
						}
						$megamenu_classes .= ' molla-loading';
						$content           = '<ul class="' . esc_attr( $megamenu_classes ) . '" style="width: ' . $this->megamenu_width . 'px;">';
					} else {
						$content = '<ul class="sub-menu molla-loading">';
					}
					$content .= '<li><i></i></li></ul>';
					$output  .= apply_filters( 'molla_menu_lazyload_content', $content, $this->megamenu, $this->megamenu_width, $this->megamenu_pos );
				}
			}
		}

		/**
		 * Ends the menu element output
		 */
		public function end_el( &$output, $item, $depth = 0, $args = array() ) {
			$output .= "</li>\n";
		}
	}
}
