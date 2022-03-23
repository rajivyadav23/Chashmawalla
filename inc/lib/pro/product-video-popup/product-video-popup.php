<?php
/**
 * Molla Product Video Popup
 *
 * @since 1.6
 * @package Molla WordPress Theme
 */

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'Molla_Product_Video_Popup' ) ) {

	class Molla_Product_Video_Popup {

		public static $instance = null;
		public $video_code      = '';

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

			$video_url       = get_post_meta( get_the_ID(), 'molla_product_video_popup_url', true );
			$video_thumbnail = get_post_meta( get_the_ID(), 'molla_product_video_thumbnail', true );

			if ( $video_url && filter_var( $video_url, FILTER_VALIDATE_URL ) ) {

				$this->video_code = '[video src="' . $video_url . '"]';
				if ( $video_thumbnail ) {
					$video_thumbnail = wp_get_attachment_image_src( $video_thumbnail, 'full' );

					$this->video_code = str_replace( '[video src="', '[video poster="' . esc_url( $video_thumbnail[0] ) . '" src="', $this->video_code );
				}

				$this->video_code = '<figure class="post-media fit-video">' . do_shortcode( $this->video_code ) . '</figure>';

				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_filter( 'molla_single_product_actions', array( $this, 'add_video_viewer_btn' ), 30 );
				add_filter( 'molla_add_var_main_js', array( $this, 'add_video_var' ) );
			}
		}


		/**
		 * Load product video viewer script.
		 *
		 * @since 1.3.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'molla-product-video-popup', MOLLA_PRO_LIB_URI . '/product-video-popup/product-video-popup.css', null, MOLLA_VERSION, 'all' );
			wp_enqueue_script( 'jquery-fitvids' );
			wp_enqueue_script( 'molla-product-video-popup', MOLLA_PRO_LIB_URI . '/product-video-popup/product-video-popup.min.js', array( 'molla-main' ), MOLLA_VERSION, true );
		}


		/**
		 * Pass degree viewer images to js.
		 *
		 * @since 1.3.0
		 */
		public function add_video_var( $vars ) {
			$vars['video_data'] = $this->video_code;
			return $vars;
		}


		/**
		 * Print Video view button in product image.
		 *
		 * @since 1.3.0
		 */
		public function add_video_viewer_btn( $html ) {
			return '<a class="sp-action open-product-video-viewer" href="#"><i class="icon-play-outline"></i></a>' . $html;
		}
	}

}

Molla_Product_Video_Popup::get_instance();
