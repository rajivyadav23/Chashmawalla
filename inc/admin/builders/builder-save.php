<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Save builder elements after customize save, page save etc...
 */

if ( ! class_exists( 'Molla_Builder_Save' ) ) :

	class Molla_Builder_Save {

		public function __construct() {
			// Save Used Global Blocks
			add_action( 'customize_save_after', array( $this, 'update_header_blocks' ) );                   // save blocks in header & menu
			add_action( 'sidebar_admin_setup', array( $this, 'update_sidebar_blocks' ) );                   // save blocks in sidebar
			add_action( 'wp_update_nav_menu_item', array( $this, 'save_menu_item_block' ), 10, 3 );         // save block of menu-item
			add_action( 'wp_update_nav_menu', array( $this, 'update_menu_blocks' ) );                       // save blocks of menus
			add_action( 'wp_delete_nav_menu', array( $this, 'update_deleted_menu_blocks' ), 10, 1 );        // remove blocks of deleted menus
			add_action( 'wp_delete_nav_menu', array( $this, 'update_header_blocks' ), 20, 1 );              // update header blocks except deleted menus
		}

		/**
		 * Save blocks in header when customize panel is published
		 */
		public function update_header_blocks() {

			$h_option = molla_option( 'hb_options' );
			$rows     = array( 'top', 'main', 'bottom' );
			$cols     = array( 'left', 'center', 'right' );

			foreach ( $rows as $r ) {
				$h_elements = array();
				foreach ( $cols as $c ) {
					if ( ! empty( $h_option[ $r . '_' . $c ] ) ) {
						$h_elements = array_merge( $h_elements, json_decode( $h_option[ $r . '_' . $c ] ) );
					}
				}

				$h_blocks = $this->get_header_blocks( $h_elements );

				if ( ! empty( $h_blocks ) ) {
					set_theme_mod( '_molla_blocks_header_' . $r, $h_blocks );
				} else {
					remove_theme_mod( '_molla_blocks_header_' . $r );
				}
			}
		}

		private function get_header_blocks( $elements ) {
			if ( ! $elements || empty( $elements ) ) {
				return array();
			}
			$result = array();
			foreach ( $elements as $element ) {
				if ( is_array( $element ) ) {
					$result = array_merge( $result, $this->get_header_blocks( $element ) );
				} else {
					foreach ( $element as $key => $value ) {
						if ( 'molla_block' == $key && $value ) {
							$str = '';
							if ( is_string( $value ) ) {
								$str = $value;
							} elseif ( is_object( $value ) && isset( $value->html ) ) {
								$str = $value->html;
							}
							if ( $str ) {
								$result[] = $str;
							}
						} elseif ( 'main_menu' == $key || 'custom_menu' == $key ) {
							if ( 'main_menu' == $key ) {
								$menu_id = get_nav_menu_locations()['main_menu'];
							} elseif ( $value ) {
								if ( is_object( $value ) && isset( $value->html ) ) {
									$menu_id = wp_get_nav_menu_object( $value->html )->term_id;
								}
							}
							if ( ! empty( $menu_id ) ) {
								$blocks_in_menu = get_theme_mod( '_molla_blocks_menu', array() );
								if ( isset( $blocks_in_menu[ $menu_id ] ) && is_array( $blocks_in_menu[ $menu_id ] ) ) {
									$result = array_merge( $result, $blocks_in_menu[ $menu_id ] );
								}
							}
						}
					}
				}
			}
			return array_unique( $result );
		}

		/**
		 * Save blocks in sidebar
		 */
		public function update_sidebar_blocks() {
			if ( ! wp_doing_ajax() || ! isset( $_POST['id_base'] ) || ! isset( $_POST['widget-id'] ) ) {
				return;
			}
			$id_base    = wp_unslash( $_POST['id_base'] );
			$widget_id  = wp_unslash( $_POST['widget-id'] );
			$settings   = isset( $_POST[ 'widget-' . $id_base ] ) && is_array( $_POST[ 'widget-' . $id_base ] ) ? $_POST[ 'widget-' . $id_base ] : false;
			$sidebar_id = $_POST['sidebar'];
			$sidebars   = get_option( 'sidebars_widgets' );
			$sidebar    = isset( $sidebars[ $sidebar_id ] ) ? $sidebars[ $sidebar_id ] : array();

			if ( ( 'block-widget' != $id_base && 'nav_menu' != $id_base && 'nav-menu-widget' != $id_base ) || ! $settings ) {
				return;
			}

			$block_widgets      = get_option( 'widget_block-widget', array() );
			$menu_widgets       = get_option( 'widget_nav_menu', array() );
			$molla_menu_widgets = get_option( 'widget_nav-menu-widget', array() );
			$sidebars           = get_theme_mod( '_molla_blocks_sidebar', array() );
			$menus              = get_theme_mod( '_molla_blocks_menu', array() );
			$block_ids          = array();
			$menu_id            = '';

			global $wp_registered_widgets;
			if ( isset( $_POST['delete_widget'] ) && $_POST['delete_widget'] && isset( $wp_registered_widgets[ $widget_id ] ) && isset( $sidebars[ $sidebar_id ] ) && is_array( $sidebars[ $sidebar_id ] ) ) {
				unset( $sidebar[ array_search( $widget_id, $sidebar ) ] );
			} else {
				foreach ( $settings as $widget_number => $widget_settings ) {
					if ( is_array( $widget_settings ) ) {
						foreach ( $widget_settings as $key => $val ) {
							if ( 'name' == $key ) {
								$block_ids[ $widget_id ] = molla_get_post_id_by_name( 'block', $val );
								break;
							}
							if ( 'nav_menu' == $key ) {
								$menu_id = $val;
								break;
							}
						}
					}
				}
			}

			$sidebars[ $sidebar_id ] = array();
			foreach ( $sidebar as $widget ) {
				$widget_type = trim( substr( $widget, 0, strrpos( $widget, '-' ) ) );
				if ( 'block-widget' == $widget_type ) {
					$widget_id = str_replace( 'block-widget-', '', $widget );
					if ( ! empty( $block_widgets[ $widget_id ] ) && ! empty( $block_widgets[ $widget_id ]['id'] ) && empty( $block_ids[ $widget ] ) ) {
						$block_ids[ $widget ] = $block_widgets[ $widget_id ]['id'];
					}
				} elseif ( 'nav_menu' == $widget_type ) {
					$widget_id = str_replace( 'nav_menu-', '', $widget );
					if ( ! empty( $menu_widgets[ $widget_id ] ) && isset( $menus[ $menu_widgets[ $widget_id ]['nav_menu'] ] ) ) {
						$block_ids[ $widget ] = $menus[ $menu_widgets[ $widget_id ]['nav_menu'] ];
					}
				} else {
					$widget_id = str_replace( 'nav-menu-widget-', '', $widget );
					if ( ! empty( $molla_menu_widgets[ $widget_id ] ) && isset( $menus[ $molla_menu_widgets[ $widget_id ]['nav_menu'] ] ) ) {
						$block_ids[ $widget ] = $menus[ $molla_menu_widgets[ $widget_id ]['nav_menu'] ];
					}
				}
			}

			$result = array();

			foreach ( $block_ids as $widget => $block ) {
				if ( is_array( $block ) ) {
					$result = array_merge( $result, $block );
				} else {
					$result[] = $block;
				}
			}

			if ( $menu_id && isset( $menus[ $menu_id ] ) ) {
				$result = array_merge( $result, $menus[ $menu_id ] );
			}

			$result = array_unique( $result );

			if ( ! empty( $result ) ) {
				$sidebars[ sanitize_text_field( $sidebar_id ) ] = $result;
			}

			if ( empty( $sidebars[ $sidebar_id ] ) ) {
				unset( $sidebars[ $sidebar_id ] );
			}

			set_theme_mod( '_molla_blocks_sidebar', $sidebars );
		}

		/**
		 * Save block when editing menu item
		 */
		public function save_menu_item_block( $menu_id, $menu_item_db_id, $args ) {
			$key = 'block_slug';

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
				$block_id = array( $value );
				if ( is_array( $block_id ) && isset( $block_id[0] ) ) {
					$blocks = get_transient( '_molla_menu_blocks' );
					if ( ! $blocks || ! is_array( $blocks ) ) {
						$blocks = array();
					}

					$id       = molla_get_post_id_by_name( 'block', $block_id[0] );
					$blocks[] = $id;
					set_transient( '_molla_menu_blocks', $blocks, 3 ); // 3 seconds
				}
			}
		}

		/**
		 * Save blocks when saving menu
		 */
		public function update_menu_blocks( $menu_id ) {
			$blocks      = get_transient( '_molla_menu_blocks' );
			$menu_blocks = get_theme_mod( '_molla_blocks_menu', array() );
			if ( isset( $menu_blocks[ $menu_id ] ) ) {
				unset( $menu_blocks[ $menu_id ] );
			}
			if ( is_array( $blocks ) && ! empty( $blocks ) ) {
				$menu_blocks[ $menu_id ] = $blocks;
				delete_transient( '_molla_menu_blocks' );
			}
			if ( ! empty( $menu_blocks ) ) {
				set_theme_mod( '_molla_blocks_menu', $menu_blocks );
			} else {
				remove_theme_mod( '_molla_blocks_menu' );
			}
		}

		/**
		 * Save blocks when deleting menu
		 */
		public function update_deleted_menu_blocks( $menu_id ) {
			$menu_blocks = get_theme_mod( '_molla_blocks_menu', array() );
			if ( isset( $menu_blocks[ $menu_id ] ) ) {
				unset( $menu_blocks[ $menu_id ] );
			}
			if ( ! empty( $menu_blocks ) ) {
				set_theme_mod( '_molla_blocks_menu', $menu_blocks );
			} else {
				remove_theme_mod( '_molla_blocks_menu' );
			}
		}
	}
	new Molla_Builder_Save;
endif;
