<?php
/**
 * Molla Product 360 Degree Viewer
 *
 * @since 1.3.0
 * @package Molla WordPress Theme
 */

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'Molla_360_Degree_Viewer' ) ) {

	class Molla_360_Degree_Viewer {

		public static $instance = null;

		/**
		 * Get Singleton Instance
		 *
		 * @since 1.3.0
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		/**
		 * Constructor.
		 *
		 * @since 1.3.0
		 */
		public function __construct() {

			$this->images = get_post_meta( get_the_ID(), 'molla_product_360_view', false );

			$images = array();

			if ( $this->images ) {
				foreach ( $this->images as $image ) {
					$image_src = wp_get_attachment_image_src( $image, 'full' );
					if ( ! empty( $image_src[0] ) ) {
						$images[] = $image_src[0];
					}
				}
				$this->images = implode( ',', $images );

				add_filter( 'molla_single_product_actions', array( $this, 'add_degree_viewer_btn' ), 20 );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );
				add_filter( 'molla_add_var_main_js', array( $this, 'add_images_var' ) );
			}
		}

		/**
		 * Load 360 degree viewer style & script
		 *
		 * @since 1.3.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'molla-360-degree-viewer', MOLLA_PRO_LIB_URI . '/360-degree-viewer/360-degree-viewer.css', null, MOLLA_VERSION, 'all' );
			wp_enqueue_script( 'threesixty-slider' );
			wp_enqueue_script( 'molla-360-degree-viewer', MOLLA_PRO_LIB_URI . '/360-degree-viewer/360-degree-viewer.js', array( 'molla-main' ), MOLLA_VERSION, true );
		}

		/**
		 * Pass degree viewer images to js
		 *
		 * @since 1.3.0
		 */
		public function add_images_var( $vars ) {
			$vars['threesixty_data'] = $this->images;
			return $vars;
		}


		/**
		 * Print Degree viewer button in product image.
		 *
		 * @since 1.3.0
		 */
		public function add_degree_viewer_btn( $html ) {
			return '<a class="sp-action open-product-degree-viewer" href="#"><i class="icon-rotate-3d"></i></a>' . $html;
		}
	}

}

Molla_360_Degree_Viewer::get_instance();
