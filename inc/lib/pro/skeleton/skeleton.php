<?php

if ( ! class_exists( 'Molla_Skeleton' ) ) {
	class Molla_Skeleton {
		private $is_doing        = '';
		private static $instance = null;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {

			if ( is_customize_preview() ||
			molla_is_elementor_preview() ) {
				return;
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );

			//Sidebar Skeleton
			// add_action( 'before_sidebar', array( $this, 'get_skeleton_sidebar' ), 20 );

			add_action( 'before_dynamic_sidebar', array( $this, 'before_dynamic_sidebar' ) );
			add_action( 'after_dynamic_sidebar', array( $this, 'after_dynamic_sidebar' ) );
			add_filter( 'sidebar_content_classes', array( $this, 'sidebar_classes' ) );

			//Product Skeleton
			add_filter( 'molla_wc_shop_loop_classes', array( $this, 'shop_loop_classes' ) );
			add_action( 'molla_wc_before_shop_product', array( $this, 'before_shop_product' ), 20 );
			add_action( 'molla_wc_after_shop_product', array( $this, 'after_shop_product' ), 20 );

			//Category Skeleton
			add_filter( 'molla_wc_shop_cat_classes', array( $this, 'shop_cat_classes' ), 10, 2 );
			add_action( 'molla_wc_before_shop_cat', array( $this, 'before_shop_cat' ), 20 );
			add_action( 'molla_wc_after_shop_cat', array( $this, 'after_shop_cat' ), 20 );

			//Single Product Skeleton
			add_filter( 'molla_wc_single_product_classes', array( $this, 'single_product_classes' ) );
			add_action( 'molla_woo_before_single_product_content', array( $this, 'before_single_product_content' ), 20 );
			add_action( 'molla_woo_after_single_product_content', array( $this, 'after_single_product_content' ), 20 );

			//Post Skeleton
			add_filter( 'molla_loop_post_classes', array( $this, 'loop_post_classes' ) );
			add_action( 'molla_before_loop_post_item', array( $this, 'before_loop_post_item' ), 20 );
			add_action( 'molla_after_loop_post_item', array( $this, 'after_loop_post_item' ), 20 );

			// Menu lazyload skeleton
			add_filter( 'molla_menu_lazyload_content', array( $this, 'menu_skeleton' ), 10, 4 );

			add_action( 'init', array( $this, 'init' ) );
		}

		public function enqueue_scripts() {
			wp_enqueue_style( 'molla-skeleton-css', MOLLA_PRO_LIB_URI . '/skeleton/skeleton' . ( is_rtl() ? '-rtl' : '' ) . '.css' );
			wp_enqueue_script( 'molla-skeleton-js', MOLLA_PRO_LIB_URI . '/skeleton/skeleton.js', array( 'molla-main' ), MOLLA_VERSION, true );

			wp_localize_script(
				'molla-skeleton-js',
				'lib_skeleton',
				array(
					'lazyload' => molla_option( 'lazy_load_img' ),
				)
			);
		}

		public function init() {

		}

		// public function get_skeleton_sidebar( $sidebar ) {

		// }

		public function before_dynamic_sidebar( $sidebar ) {

			if ( ! $this->is_doing ) {
				// if ( ! molla_ajax() && ( 'shop-top-sidebar' == $sidebar || 'shop-sidebar' == $sidebar || 'product-sidebar' == $sidebar || is_archive() || is_search() || ( is_single() && ! is_page() ) ) ) {
				// 	$this->is_doing = 'sidebar';
				// }

				if ( is_archive() || is_search() || ( is_single() && ! is_page() ) ) {
					ob_start();
					$this->is_doing = 'sidebar';
				}
				// if ( is_active_sidebar( $sidebar ) ) {
				// }
			}
		}

		public function after_dynamic_sidebar( $sidebar ) {
			if ( is_active_sidebar( $sidebar ) && 'sidebar' == $this->is_doing ) {
				echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
				echo '<div class="widget"></div><div class="widget"></div>';
				$this->is_doing = '';
			}
		}

		public function sidebar_classes( $classes ) {
			if ( is_archive() || is_search() || ( is_single() && ! is_page() ) ) {
				return $classes .= ' skeleton-body';
			}

			return $classes;
		}

		public function shop_loop_classes( $classes ) {
			if ( molla_is_shop() ||
				molla_is_in_category() ||
				( class_exists( 'WooCommerce' ) && molla_is_product() ) ||
				! wc_get_loop_prop( 'is_shortcode' ) ) {

				return $classes . ' skeleton-body';
			}

			return $classes;
		}

		public function before_shop_product() {
			if ( ! $this->is_doing ) {
				if ( ! wc_get_loop_prop( 'widget' ) && class_exists( 'WooCommerce' ) ) {
					if ( molla_is_product() ) {
						$this->is_doing = 'product';
					} elseif ( ! molla_ajax() ) {
						$this->is_doing = 'product';
					}
				} elseif ( class_exists( 'WooCommerce' ) && molla_is_product() ) { // product layout builder
					$this->is_doing = 'product';
				}
				if ( 'product' == $this->is_doing ) {
					ob_start();
				}
			}
		}

		public function after_shop_product( $style ) {
			if ( 'product' == $this->is_doing ) {
				echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
				echo '<div class="skel-pro' . ( 'list' == $style ? ' skel-pro-list' : '' ) . '"></div>';
				$this->is_doing = '';
			}
		}

		public function shop_cat_classes( $classes, $layout_mode ) {
			if ( ! $this->is_doing ) {
				if ( ! $layout_mode && class_exists( 'WooCommerce' ) && molla_is_shop() || molla_is_in_category() ) {
					$classes .= ' skeleton-body';
				}
			}

			return $classes;
		}

		public function before_shop_cat() {
			if ( ! $this->is_doing && class_exists( 'WooCommerce' ) && molla_is_shop() || molla_is_in_category() ) {
				ob_start();
				$this->is_doing = 'category';
			}
		}

		public function after_shop_cat() {
			if ( 'category' == $this->is_doing ) {
				echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
				echo '<div class="skel-cat"></div>';
			}

			$this->is_doing = '';
		}

		public function single_product_classes( $classes ) {
			if ( ! $this->is_doing ) {
				$classes .= ' skeleton-body';
			}
			return $classes;
		}

		public function before_single_product_content() {
			if ( ! $this->is_doing ) {
				ob_start();
				$this->is_doing = 'single_product';
			}
		}

		public function after_single_product_content( $layout ) {
			if ( 'single_product' == $this->is_doing ) {
				echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
				?>
				<div class="skel-pro-single <?php echo esc_attr( $layout ); ?>">
					<div class="row">
						<div class="<?php echo esc_attr( 'gallery' == $layout ? 'col-12' : 'col-md-6' ); ?>">
							<div class="product-gallery">
							</div>
						</div>
						<div class="<?php echo esc_attr( 'gallery' == $layout ? 'col-12' : 'col-md-6' ); ?>">
							<div class="entry-summary row">
								<div class="<?php echo esc_attr( 'gallery' == $layout ? 'col-6' : 'col-md-12' ); ?>">
									<div class="entry-summary1"></div>
								</div>
								<div class="<?php echo esc_attr( 'gallery' == $layout ? 'col-6' : 'col-md-12' ); ?>">
									<div class="entry-summary2"></div>
								<?php
								if ( 'sticky' == $layout && class_exists( 'WooCommerce' ) ) {
									wc_get_template( 'single-product/tabs/tabs.php' );
								}
								?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				$this->is_doing = '';
			}
		}

		public function loop_post_classes( $classes ) {
			if ( ! $this->is_doing ) {
				if ( is_archive() && ! molla_ajax() ) {
					$classes .= ' skeleton-body';
				}
			}
			return $classes;
		}

		public function before_loop_post_item() {
			if ( ! $this->is_doing && is_archive() && ! molla_ajax() ) {
				ob_start();
				$this->is_doing = 'post';
			}
		}

		public function after_loop_post_item( $type ) {
			if ( 'post' == $this->is_doing ) {
				echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
				echo '<div class="skel-post' . ( 'default' != $type ? ' skel-post-' . $type : '' ) . '"></div>';
				$this->is_doing = '';
			}
		}

		public function menu_skeleton( $content, $megamenu, $megamenu_width, $megamenu_pos ) {
			if ( $megamenu ) {
				$megamenu_classes = 'sub-menu megamenu';
				if ( ! $megamenu_width ) {
					$megamenu_classes .= ' megamenu-container';
				} else {
					$megamenu_classes .= ' pos-' . ( $megamenu_pos ? $megamenu_pos : 'left' );
				}
				$megamenu_classes .= ' skel-megamenu';
				return '<ul class="' . esc_attr( $megamenu_classes ) . '" style="width: ' . $megamenu_width . 'px;"></ul>';
			} else {
				return '<ul class="sub-menu skel-menu"></ul>';
			}
			return $content;
		}

		static public function prevent_skeleton() {
			Molla_Skeleton::$instance->is_doing = 'stop';
		}

		static public function stop_prevent_skeleton() {
			Molla_Skeleton::$instance->is_doing = '';
		}
	}

	Molla_Skeleton::get_instance();
}

