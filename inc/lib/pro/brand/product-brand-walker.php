<?php
/**
 * Molla_WC_Product_Brand_List_Walker class
 *
 * @version 1.2.5
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Molla_WC_Product_Brand_List_Walker', false ) ) {
	return;
}

include_once WC()->plugin_path() . '/includes/walkers/class-wc-product-cat-list-walker.php';

/**
 * Product brand list walker class.
 */
class Molla_WC_Product_Brand_List_Walker extends WC_Product_Cat_List_Walker {

	public $tree_type = 'product_brand';

	public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$cat_id = intval( $cat->term_id );
		$parent = false;

		$output .= '<li class="cat-item cat-item-' . $cat_id;

		if ( $args['hierarchical'] ) {
			$output .= ' hierarchical';
		}

		if ( $args['current_category'] === $cat_id ) {
			$output .= ' current-cat';
		}

		if ( $args['has_children'] && $args['hierarchical'] && ( empty( $args['max_depth'] ) || $args['max_depth'] > $depth + 1 ) ) {
			$output .= ' cat-parent';
			if ( ! $args['current_category_ancestors'] || ! $args['current_category'] || ! in_array( $cat_id, $args['current_category_ancestors'], true ) ) {
				$output .= ' collapsed';
			}
			$parent = true;
		}

		if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat_id, $args['current_category_ancestors'], true ) ) {
			$output .= ' current-cat-parent';
		}

		$output .= '"><a href="' . esc_url( get_term_link( $cat_id, $this->tree_type ) ) . '">' . apply_filters( 'list_product_cats', $cat->name, $cat );

		if ( $args['show_count'] ) {
			$output .= ' <span class="count">' . $cat->count . '</span>';
		}

		if ( $parent ) {
			$output .= '<span class="toggle"><i class="icon-angle-up"></i></span>';
		}

		$output .= '</a>';
	}
}
