<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Molla_Walker_Category', false ) ) {
	return;
}

/**
 * Category list walker class.
 */
class Molla_Walker_Category extends Walker_Category {

	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters( 'list_cats', esc_attr( $category->name ), $category );

		// Don't generate an element if the category name is empty.
		if ( '' === $cat_name ) {
			return;
		}

		$atts         = array();
		$atts['href'] = get_term_link( $category );

		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
			$atts['title'] = strip_tags( apply_filters( 'category_description', $category->description, $category ) );
		}

		$atts = apply_filters( 'category_list_link_attributes', $atts, $category, $depth, $args, $id );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$toggle = '';
		if ( 'list' == $args['style'] && $args['has_children'] && empty( $args['show_count'] ) ) {
			$toggle = '<span class="toggle"><i class="icon-angle-up"></i></span>';
		}

		$count = '';

		if ( ! empty( $args['show_count'] ) ) {
			$count .= '<span class="count">' . number_format_i18n( $category->count ) . '</span>';
		}

		$link = sprintf(
			'<a%s>%s%s%s</a>',
			$attributes,
			$cat_name,
			$count,
			$toggle
		);

		if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
			$link .= ' ';

			if ( empty( $args['feed_image'] ) ) {
				$link .= '(';
			}

			$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

			if ( empty( $args['feed'] ) ) {
				/* translators: %s: Category name. */
				$alt = ' alt="' . sprintf( esc_html__( 'Feed for all posts filed under %s', 'molla' ), $cat_name ) . '"';
			} else {
				$alt   = ' alt="' . $args['feed'] . '"';
				$name  = $args['feed'];
				$link .= empty( $args['title'] ) ? '' : $args['title'];
			}

			$link .= '>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= $name;
			} else {
				$link .= "<img src='" . esc_url( $args['feed_image'] ) . "'$alt" . ' />';
			}
			$link .= '</a>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= ')';
			}
		}

		if ( 'list' == $args['style'] ) {
			$output     .= "\t<li";
			$css_classes = array(
				'cat-item',
				'cat-item-' . $category->term_id,
			);

			if ( $args['hierarchical'] ) {
				$css_classes[] = 'hierarchical';
			}

			if ( ! empty( $args['current_category'] ) ) {
				// 'current_category' can be an array, so we use `get_terms()`.
				$_current_terms = get_terms(
					array(
						'taxonomy'   => $category->taxonomy,
						'include'    => $args['current_category'],
						'hide_empty' => false,
					)
				);

				foreach ( $_current_terms as $_current_term ) {
					if ( $category->term_id == $_current_term->term_id ) {
						$css_classes[] = 'current-cat';
						$link          = str_replace( '<a', '<a aria-current="page"', $link );
					} elseif ( $category->term_id == $_current_term->parent ) {
						$css_classes[] = 'current-cat-parent';
					}
					while ( $_current_term->parent ) {
						if ( $category->term_id == $_current_term->parent ) {
							$css_classes[] = 'current-cat-ancestor';
							break;
						}
						$_current_term = get_term( $_current_term->parent, $category->taxonomy );
					}
				}
			}

			if ( $args['has_children'] ) {
				$css_classes[] = 'cat-parent';
				if ( ! in_array( 'current-cat-ancestor', $css_classes ) && empty( $args['show_count'] ) ) {
					$css_classes[] = 'collapsed';
				}
			}

			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );
			$css_classes = $css_classes ? ' class="' . esc_attr( $css_classes ) . '"' : '';

			$output .= $css_classes;
			$output .= ">$link\n";
		} elseif ( isset( $args['separator'] ) ) {
			$output .= "\t$link" . $args['separator'] . "\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}
}
