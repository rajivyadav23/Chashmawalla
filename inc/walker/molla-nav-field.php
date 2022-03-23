<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to add menu fields.
 */
class Molla_Nav_Field {

	private $block_ids = [];

	public function __construct() {

		// Add menu custom fields
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_fields_meta' ) );
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'add_custom_fields' ), 10, 4 );

		// Save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields' ), 10, 3 );

		// Edit walker
		// add_filter( 'wp_edit_nav_menu_walker', array( $this, 'custom_nav_edit' ), 10, 2 );
	}

	/**
	 * Add custom menu style fields data to the menu.
	 */
	public function add_custom_fields_meta( $menu_item ) {
		$menu_item->icon_class     = get_post_meta( $menu_item->ID, '_menu_item_icon_class', true );
		$menu_item->nolink         = get_post_meta( $menu_item->ID, '_menu_item_nolink', true );
		$menu_item->megamenu       = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
		$menu_item->megamenu_col   = get_post_meta( $menu_item->ID, '_menu_item_megamenu_col', true );
		$menu_item->megamenu_width = get_post_meta( $menu_item->ID, '_menu_item_megamenu_width', true );
		$menu_item->grid_col       = get_post_meta( $menu_item->ID, '_menu_item_grid_col', true );
		$menu_item->subtitle       = get_post_meta( $menu_item->ID, '_menu_item_subtitle', true );
		$menu_item->menuback       = get_post_meta( $menu_item->ID, '_menu_item_menuback', true );
		$menu_item->popup_pos      = get_post_meta( $menu_item->ID, '_menu_item_popup_pos', true );
		$menu_item->block_slug     = get_post_meta( $menu_item->ID, '_menu_item_block_slug', true );
		$menu_item->badge_status   = get_post_meta( $menu_item->ID, '_menu_item_badge_status', true );
		return $menu_item;
	}

	/**
	 * Add custom menu fields.
	 */
	public function add_custom_fields( $id, $item, $depth, $args ) { ?>
		<p class="walker-icon-class description description-wide">
			<label for="edit-menu-item-icon_class-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Icon Class (Optional)', 'molla' ); ?><br />
				<input type="text" id="edit-menu-item-icon_class-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-custom" name="menu-item-icon_class[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->icon_class ); ?>" min="1" />
			</label>
		</p>
		<p class="walker-nolink description description-wide">
			<label for="edit-menu-item-nolink-<?php echo esc_attr( $item->ID ); ?>">
			<input type="checkbox" id="edit-menu-item-nolink-<?php echo esc_attr( $item->ID ); ?>" class="code edit-menu-item-nolink" value="nolink" name="menu-item-nolink[<?php echo esc_attr( $item->ID ); ?>]"<?php checked( $item->nolink, 'nolink' ); ?> />
				<?php esc_html_e( "Don't link", 'molla' ); ?>
			</label>
		</p>
		<p class="walker-megamenu description description-wide">
			<label for="edit-menu-item-megamenu-<?php echo esc_attr( $item->ID ); ?>">
				<input type="checkbox" id="edit-menu-item-megamenu-<?php echo esc_attr( $item->ID ); ?>" class="code edit-menu-item-megamenu" value="megamenu" name="menu-item-megamenu[<?php echo esc_attr( $item->ID ); ?>]"<?php checked( $item->megamenu, 'megamenu' ); ?> />
				<?php esc_html_e( 'Enable megamenu', 'molla' ); ?>
			</label>
		</p>
		<p class="walker-megamenu-width description description-wide">
			<label for="edit-menu-item-megamenu_width-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Megamenu width (px)', 'molla' ); ?><br />
				<input type="number" id="edit-menu-item-megamenu_width-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-custom" name="menu-item-megamenu_width[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->megamenu_width ); ?>" min="1" />
				<?php esc_html_e( 'Leave it blank to make full width.', 'molla' ); ?><br />
			</label>
		</p>
		<p class="walker-megamenu-col description description-wide">
			<label for="edit-menu-item-megamenu_col-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Megamenu columns (from 1 to 6)', 'molla' ); ?><br />
				<input type="number" id="edit-menu-item-megamenu_col-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-custom" name="menu-item-megamenu_col[<?php echo esc_attr( $item->ID ); ?>]" min="1" max="6" value="<?php echo esc_attr( $item->megamenu_col ); ?>" />
			</label>
		</p>
		<p class="walker-popup-pos description description-wide">
			<label for="edit-menu-item-popup_pos-<?php echo esc_attr( $item->ID ); ?>">
			<?php esc_html_e( 'Popup position', 'molla' ); ?><br />
			<select id="edit-menu-item-popup_pos-<?php echo esc_attr( $item->ID ); ?>" name="menu-item-popup_pos[<?php echo esc_attr( $item->ID ); ?>]" 
				value="<?php echo esc_attr( $item->popup_pos ); ?>">
				<option value=""
				<?php
				if ( '' == $item->popup_pos ) {
					echo 'selected="selected"';}
				?>
				><?php echo 'Left'; ?></option>
				<option value="pos-center" 
				<?php
				if ( 'pos-center' == $item->popup_pos ) {
					echo 'selected="selected"';}
				?>
				><?php echo 'Center'; ?></option>
				<option value="pos-right" 
				<?php
				if ( 'pos-right' == $item->popup_pos ) {
					echo 'selected="selected"';}
				?>
				><?php echo 'Right'; ?></option>
			</select>
		</p>
		<p class="walker-grid-col description description-wide">
			<label for="edit-menu-item-grid_col-<?php echo esc_attr( $item->ID ); ?>">
			<input type="checkbox" id="edit-menu-item-grid_col-<?php echo esc_attr( $item->ID ); ?>" class="code edit-menu-item-grid_col" value="grid_col" name="menu-item-grid_col[<?php echo esc_attr( $item->ID ); ?>]"<?php checked( $item->grid_col, 'grid_col' ); ?> />
				<?php esc_html_e( 'Use as grid column', 'molla' ); ?>
			</label>
		</p>
		<p class="walker-subtitle description description-wide">
			<label for="edit-menu-item-subtitle-<?php echo esc_attr( $item->ID ); ?>">
			<input type="checkbox" id="edit-menu-item-subtitle-<?php echo esc_attr( $item->ID ); ?>" class="code edit-menu-item-subtitle" value="subtitle" name="menu-item-subtitle[<?php echo esc_attr( $item->ID ); ?>]"<?php checked( $item->subtitle, 'subtitle' ); ?> />
				<?php esc_html_e( 'Set as subtitle', 'molla' ); ?>
			</label>
		</p>
		<p class="walker-menuback description description-wide">
			<label for="edit-menu-item-menuback-<?php echo esc_attr( $item->ID ); ?>">
				<?php echo 'Background Image'; ?><br />
				<input type="text" id="edit-menu-item-menuback-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-menuback"
					<?php if ( $item->menuback ) : ?>
						name="menu-item-menuback[<?php echo esc_attr( $item->ID ); ?>]"
					<?php endif; ?>
						data-name="menu-item-menuback[<?php echo esc_attr( $item->ID ); ?>]"
						value="<?php echo esc_attr( $item->menuback ); ?>" />
				<br/>
				<input class="button_upload_image button" id="edit-menu-item-menuback-<?php echo esc_attr( $item->ID ); ?>" type="button" value="Upload Image" />&nbsp;
				<input class="button_remove_image button" id="edit-menu-item-menuback-<?php echo esc_attr( $item->ID ); ?>" type="button" value="Remove Image" />
			</label>
		</p>
		<p class="walker-block-slug description description-wide">
			<label for="edit-menu-item-block_slug-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Block Id or Slug', 'molla' ); ?><br />
				<input type="text" id="edit-menu-item-block_slug-<?php echo esc_attr( $item->ID ); ?>" class="code edit-menu-item-block_slug" value="<?php echo esc_attr( $item->block_slug ); ?>" name="menu-item-block_slug[<?php echo esc_attr( $item->ID ); ?>]"/>
			</label>
		</p>
		<p class="walker-badge-status description description-wide">
			<label for="edit-menu-item-badge_status-<?php echo esc_attr( $item->ID ); ?>">
				<?php esc_html_e( 'Status', 'molla' ); ?><br />
				<select id="edit-menu-item-badge_status-<?php echo esc_attr( $item->ID ); ?>" name="menu-item-badge_status[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->badge_status ); ?>" >
				<?php
				$style = array(
					''        => '',
					'hot'     => esc_html__( 'Hot', 'molla' ),
					'new'     => esc_html__( 'New', 'molla' ),
					'popular' => esc_html__( 'Popular', 'molla' ),
				);
				$style = apply_filters( 'molla_menu_item_status', $style );
				foreach ( $style as $key => $elem ) {
					echo '<option value="' . esc_attr( $key ) . '"' . esc_attr( $key == $item->badge_status ? 'selected="selected"' : '' ) . '>' . esc_html( $elem ) . '</option>';
				}
				?>
				</select>
			</label>
		</p>
		<?php
	}

	/**
	 * Save fields of menu
	 */
	public function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

		$db_fields = array( 'icon_class', 'nolink', 'megamenu', 'megamenu_width', 'megamenu_col', 'grid_col', 'subtitle', 'menuback', 'popup_pos', 'block_slug', 'badge_status' );
		foreach ( $db_fields as $key ) {

			if ( ! isset( $_POST[ 'menu-item-' . $key ][ $menu_item_db_id ] ) ) {
				if ( ! isset( $args[ 'menu-item-' . $key ] ) ) {
					$value = '';
				} else {
					$value = $args[ 'menu-item-' . $key ];
				}
			} else {
				$value = sanitize_text_field( $_POST[ 'menu-item-' . $key ][ $menu_item_db_id ] );
			}

			if ( $value ) {
				update_post_meta( $menu_item_db_id, '_menu_item_' . $key, $value );
			} else {
				delete_post_meta( $menu_item_db_id, '_menu_item_' . $key );
			}
		}
	}

	/**
	 * Edit menu walker
	 */
	public function custom_nav_edit() {
		require_once MOLLA_LIB . '/walker/walker-edit-custom.php';
		return 'Molla_Walker_Nav_Menu_Edit';
	}

}

new Molla_Nav_Field();
