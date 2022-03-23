<?php
/**
 * Custom walker nav edit.
 */

class Molla_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

	/**
	 * Start the element output.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$item_output = '';

		parent::start_el( $item_output, $item, $depth, $args, $id );

		$pos = '<fieldset class="field-move';

		ob_start();

		$item_id = intval( $item->ID );

		do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args );

		$extra_field = ob_get_clean();

		$output .= str_replace( $pos, $extra_field . $pos, $item_output );
	}

}
