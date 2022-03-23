<?php

if ( ! class_exists( 'Molla_Image_Swatch' ) ) {
	class Molla_Image_Swatch {
		public $swatch_options = '';
		public $type           = '';

		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );

			add_filter( 'molla_check_product_variation_type', array( $this, 'check_variation_type' ), 10, 3 );
			add_filter( 'molla_before_product_variation', array( $this, 'before_product_variation' ), 10, 3 );
			add_action( 'molla_after_product_variation', array( $this, 'after_product_variation' ), 10, 3 );

			add_action( 'init', array( $this, 'init' ) );
		}

		public function check_variation_type( $res, $attr_name, $options ) {
			global $product;

			$this->type           = '';
			$this->swatch_options = $product->get_meta( 'swatch_options', true ); // Image Swatch

			if ( 'variable' == $product->get_type() && $this->swatch_options ) {
				if ( ! is_array( $this->swatch_options ) ) {
					$this->swatch_options = json_decode( $this->swatch_options, true );
				}

				if ( isset( $this->swatch_options[ $attr_name ] ) && 'image' == $this->swatch_options[ $attr_name ]['type'] ) {
					$this->type = 'image';
				}
			}

			return ( 'image' == $this->type ) || $res;
		}

		public function before_product_variation( $options, $attribute_name, $terms ) {
			if ( ! empty( $options ) && 'image' == $this->type ) { // image attribute type
				return false;
			}
			return $options;
		}

		public function after_product_variation( $options, $attribute_name, $terms ) {
			if ( ! empty( $options ) && 'image' == $this->type ) { // image attribute type
				if ( ! empty( $terms ) ) {
					foreach ( $terms as $term ) {
						if ( in_array( $term->slug, $options, true ) ) {
							$img_id = $this->swatch_options[ $attribute_name ][ $term->term_id ];
							$args   = '';

							if ( ! $img_id ) {
								$img_full = $img_thumb = $img_swatch = wc_placeholder_img_src();
							} else {
								$img_swatch = wp_get_attachment_image_src( $img_id, array( 32, 32 ) )[0];
								$img_thumb  = wp_get_attachment_image_src( $img_id, 'medium' )[0];
								$img_full   = wp_get_attachment_image_src( $img_id, 'full' )[0];
							}

							$style = ' style="background-image: url(' . esc_url( $img_swatch ) . ')"';

							if ( $img_id ) {
								$args = 'data-src=' . esc_url( $img_thumb ) . ' data-full-src=' . esc_url( $img_full );
							}

							echo '<button class="nav-thumb thumb-image" name="' . $term->slug . '"' . $style . ' ' . $args . '></button>';
						}
					}
				} else { // custom added attribute
					foreach ( $options as $option ) {
						$idx    = preg_replace( '/\s+/', '_', $option );
						$img_id = $this->swatch_options[ $attribute_name ][ $idx ];
						$args   = '';

						if ( ! $img_id ) {
							$img_full = $img_thumb = $img_swatch = wc_placeholder_img_src();
						} else {
							$img_swatch = wp_get_attachment_image_src( $img_id, array( 32, 32 ) )[0];
							$img_thumb  = wp_get_attachment_image_src( $img_id, 'medium' )[0];
							$img_full   = wp_get_attachment_image_src( $img_id, 'full' )[0];
						}

						$style = ' style="background-image: url(' . esc_url( $img_swatch ) . ')"';

						if ( $img_id ) {
							$args = 'data-src=' . esc_url( $img_thumb ) . ' data-full-src=' . esc_url( $img_full );
						}

						echo '<button class="nav-thumb thumb-image" name="' . $option . '"' . $style . ' ' . $args . '></button>';
					}
				}
			}
		}

		public function init() {

		}

		public function enqueue_scripts() {
			wp_enqueue_script( 'molla-image-swatch-js', MOLLA_PRO_LIB_URI . '/image-swatch/swatch.js', array( 'molla-main' ), MOLLA_VERSION, true );
		}
	}
}

new Molla_Image_Swatch;
