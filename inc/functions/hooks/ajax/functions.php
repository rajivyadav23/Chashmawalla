<?php
/**
 * Ajax Request Functions
 */

if ( ! function_exists( 'molla_mobile_menu' ) ) :
	// Build mobile menu
	function molla_mobile_menu() {

		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		$mobile_menus = molla_option( 'mobile_menus' );

		if ( ! count( $mobile_menus ) ) {
			return;
		}

		$mobile_menus_tmp = array();
		$mobile_menus_arr = array();
		$has_been_changed = false;
		foreach ( $mobile_menus as $menu ) {
			$menu_obj = wp_get_nav_menu_object( $menu );
			if ( $menu_obj ) {
				$mobile_menus_arr[] = array(
					'id'   => $menu_obj->term_id,
					'slug' => $menu_obj->slug,
					'name' => $menu_obj->name,
				);
				$mobile_menus_tmp[] = (string) $menu_obj->term_id;
			} else {
				$has_been_changed = true;
			}
		}
		if ( $has_been_changed ) {
			set_theme_mod( 'mobile_menus', $mobile_menus_tmp );
		}

		if ( count( $mobile_menus_arr ) > 1 ) {
			$mobile_tab = true;
		}

		$active = true;

		ob_start();

		if ( $mobile_tab ) :
			?>
			<ul class="nav nav-pills-mobile nav-border-anim" role="tablist">
			<?php
			foreach ( $mobile_menus_arr as $menu ) :
				?>
				<li class="nav-item<?php echo ! $active ? '' : ' active'; ?>">
				<?php $active = false; ?>
					<a class="nav-link" id="<?php echo esc_attr( $menu['slug'] ); ?>-link" data-toggle="tab" href="#<?php echo esc_attr( $menu['slug'] ); ?>-tab" role="tab" aria-controls="<?php echo esc_attr( $menu['slug'] ); ?>-tab" aria-selected="true"><?php echo esc_html( $menu['name'] ); ?></a>
				</li>
				<?php
			endforeach;

			$active = true;
			?>
			</ul>
			<div class="tab-content">

			<?php
			foreach ( $mobile_menus_arr as $menu ) :
				?>
			<div class="tab-pane fade<?php echo ! $active ? '' : ' show active'; ?>" id="<?php echo esc_attr( $menu['slug'] ); ?>-tab" role="tabpanel" aria-labelledby="<?php echo esc_attr( $menu['slug'] ); ?>-link">
				<?php
				wp_nav_menu(
					array(
						'menu'           => $menu['id'],
						'menu_class'     => 'mobile-menu mobile-' . $menu['slug'] . '-menu',
						'menu_id'        => 'mobile-' . $menu['slug'] . '-menu',
						'lazy'           => false,
						'theme_location' => '',
					)
				);
				?>
			</div>
				<?php
				$active = false;
			endforeach;
		else :
			wp_nav_menu(
				array(
					'menu'           => $mobile_menus_arr[0]['id'],
					'menu_class'     => 'mobile-menu mobile-' . $mobile_menus_arr[0]['slug'] . '-menu',
					'menu_id'        => 'mobile-' . $mobile_menus_arr[0]['slug'] . '-menu',
					'lazy'           => false,
					'theme_location' => '',
				)
			);
		endif;

		if ( $mobile_tab ) :
			?>
			</div>
			<?php
		endif;
		echo ob_get_clean();
		exit();

		// phpcs:enable
	}
endif;

if ( ! function_exists( 'molla_cart_item_remove' ) ) :
	/**
	 * Mini-cart item is removed.
	 */
	function molla_cart_item_remove() {

		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		$cart         = WC()->instance()->cart;
		$cart_id      = sanitize_text_field( $_POST['cart_id'] );
		$cart_item_id = $cart->find_product_in_cart( $cart_id );
		if ( $cart_item_id ) {
			$cart->set_quantity( $cart_item_id, 0 );
		}
		$cart_ajax = new WC_AJAX();
		$cart_ajax->get_refreshed_fragments();
		exit();

		// phpcs:enable
	}
endif;

if ( ! function_exists( 'molla_ajax_account_form' ) ) :
	/**
	 * Opens login popup.
	 */
	function molla_ajax_account_form() {
		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		$is_facebook_login = molla_nextend_social_login( 'facebook' );
		$is_google_login   = molla_nextend_social_login( 'google' );
		$is_twitter_login  = molla_nextend_social_login( 'twitter' );

		echo '<div class="form-box" id="customer_login">';

		echo wc_get_template_part( 'myaccount/form-login' );

		if ( ( $is_facebook_login || $is_google_login || $is_twitter_login ) && molla_option( 'social_login' ) ) {
			echo molla_get_template_part(
				'woocommerce/myaccount/login-social',
				'',
				apply_filters(
					'molla_nextend_social_login_params',
					array(
						'socials' => array(
							'is_facebook_login' => $is_facebook_login,
							'is_google_login'   => $is_google_login,
							'is_twitter_login'  => $is_twitter_login,
						),
					)
				)
			);
		}

		echo '</div>';

		exit();

		// phpcs:enable
	}
endif;

if ( ! function_exists( 'molla_account_login_popup_login' ) ) :
	/**
	 * Validator of sign in.
	 */
	function molla_account_login_popup_login() {
		$nonce_value = wc_get_var( $_REQUEST['woocommerce-login-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.
		$result      = false;
		if ( wp_verify_nonce( $nonce_value, 'woocommerce-login' ) ) {
			try {
				$creds = array(
					'user_login'    => trim( $_POST['username'] ),
					'user_password' => $_POST['password'],
					'remember'      => isset( $_POST['rememberme'] ),
				);

				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'woocommerce_process_login_errors', $validation_error, $_POST['username'], $_POST['password'] );

				if ( $validation_error->get_error_code() ) {
					echo json_encode(
						array(
							'loggedin' => false,
							'message'  => '<strong>' . esc_html__(
								'Error:',
								'woocommerce'
							) . '</strong> ' . $validation_error->get_error_message(),
						)
					);
					die();
				}

				if ( empty( $creds['user_login'] ) ) {
					echo json_encode(
						array(
							'loggedin' => false,
							'message'  => '<strong>' . esc_html__(
								'Error:',
								'woocommerce'
							) . '</strong> ' . esc_html__(
								'Username is required.',
								'woocommerce'
							),
						)
					);
					die();
				}

				// On multisite, ensure user exists on current site, if not add them before allowing login.
				if ( is_multisite() ) {
					$user_data = get_user_by( is_email( $creds['user_login'] ) ? 'email' : 'login', $creds['user_login'] );

					if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
						add_user_to_blog( get_current_blog_id(), $user_data->ID, 'customer' );
					}
				}

				// Perform the login
				$user = wp_signon( apply_filters( 'woocommerce_login_credentials', $creds ), is_ssl() );
				if ( ! is_wp_error( $user ) ) {
					$result = true;
				}
			} catch ( Exception $e ) {
			}
		}
		if ( $result ) {
			echo json_encode(
				array(
					'loggedin' => true,
					'message'  => esc_html__(
						'Login successful, redirecting...',
						'molla'
					),
				)
			);
		} else {
			echo json_encode(
				array(
					'loggedin' => false,
					'message'  => esc_html__(
						'Wrong username or password.',
						'molla'
					),
				)
			);
		}
		die();
	}
endif;

if ( ! function_exists( 'molla_account_login_popup_register' ) ) :
	/**
	 * Validator of sign up.
	 */
	function molla_account_login_popup_register() {

		$nonce_value = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';
		$nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? $_POST['woocommerce-register-nonce'] : $nonce_value;
		$result      = true;

		if ( wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
			$username = 'no' === get_option( 'woocommerce_registration_generate_username' ) ? $_POST['username'] : '';
			$password = 'no' === get_option( 'woocommerce_registration_generate_password' ) ? $_POST['password'] : '';
			$email    = $_POST['email'];

			try {
				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'woocommerce_process_registration_errors', $validation_error, $username, $password, $email );

				if ( $validation_error->get_error_code() ) {
					echo json_encode(
						array(
							'loggedin' => false,
							'message'  => $validation_error->get_error_message(),
						)
					);
					die();
				}

				$new_customer = wc_create_new_customer( sanitize_email( $email ), wc_clean( $username ), $password );

				if ( is_wp_error( $new_customer ) ) {
					echo json_encode(
						array(
							'loggedin' => false,
							'message'  => $new_customer->get_error_message(),
						)
					);
					die();
				}

				if ( apply_filters( 'woocommerce_registration_auth_new_customer', true, $new_customer ) ) {
					wc_set_customer_auth_cookie( $new_customer );
				}
			} catch ( Exception $e ) {
				$result = false;
			}
		}
		if ( $result ) {
			echo json_encode(
				array(
					'loggedin' => true,
					'message'  => esc_html__(
						'Register successful, redirecting...',
						'molla'
					),
				)
			);
		} else {
			echo json_encode(
				array(
					'loggedin' => false,
					'message'  => esc_html__(
						'Register failed.',
						'molla'
					),
				)
			);
		}
		die();
	}
endif;


if ( ! function_exists( 'molla_woocommerce_review_action' ) ) :
	/**
	 * Single product review recommendations.
	 */
	function molla_woocommerce_review_action() {

		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		if ( isset( $_POST['review_id'] ) ) {
			$review_id       = intval( $_POST['review_id'] );
			$status          = $_POST['status'];
			$like_count      = get_post_meta( $review_id, 'like_count', true );
			$dislike_count   = get_post_meta( $review_id, 'dislike_count', true );
			$err_msg_like    = '';
			$err_msg_dislike = '';

			if ( 'true' == $status ) {
				if ( ! isset( $_COOKIE[ 'molla_review_like_' . $review_id ] ) || 0 == intval( $like_count ) ) {
					setcookie( 'molla_review_like_' . $review_id, $review_id, time() + 360 * 24 * 60 * 60, '/' );
					if ( isset( $_COOKIE[ 'molla_review_dislike_' . $review_id ] ) && $dislike_count ) {
						setcookie( 'molla_review_dislike_' . $review_id, $review_id, time() - 360 * 24 * 60 * 60, '/' );
						$dislike_count --;
					}
					$like_count ++;
				}
				if ( isset( $_COOKIE[ 'molla_review_like_' . $review_id ] ) ) {
					$err_msg_like = 'error';
				}
			} else {
				if ( ! isset( $_COOKIE[ 'molla_review_dislike_' . $review_id ] ) || 0 == intval( $dislike_count ) ) {
					setcookie( 'molla_review_dislike_' . $review_id, $review_id, time() + 360 * 24 * 60 * 60, '/' );
					if ( isset( $_COOKIE[ 'molla_review_like_' . $review_id ] ) && $like_count ) {
						setcookie( 'molla_review_like_' . $review_id, $review_id, time() - 360 * 24 * 60 * 60, '/' );
						$like_count --;
					}
					$dislike_count ++;
				}
				if ( isset( $_COOKIE[ 'molla_review_dislike_' . $review_id ] ) ) {
					$err_msg_dislike = 'error';
				}
			}
			update_post_meta( $review_id, 'like_count', $like_count );
			update_post_meta( $review_id, 'dislike_count', $dislike_count );
			echo json_encode( array( $like_count, $dislike_count, $err_msg_like, $err_msg_dislike ) );
		}
		exit;

		// phpcs:enable
	}
endif;

if ( ! function_exists( 'molla_woocommerce_more_product_action' ) ) :
	/**
	 * Load more products.
	 */
	function molla_woocommerce_more_product_action() {

		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		if ( isset( $_POST['options'] ) ) {
			$options = $_POST['options'];
			if ( is_array( $options ) ) {
				if ( 'false' != $_POST['filter_cat'] && $_POST['filter_cat'] ) {
					$_POST['filter_cat']               = explode( ',', $_POST['filter_cat'] );
					$options['extra_atts']['category'] = '';
					for ( $i = 0; $i < count( $_POST['filter_cat'] ); $i++ ) {
						if ( $i > 0 ) {
							$options['extra_atts']['category'] .= ',';
						}
						$options['extra_atts']['category'] .= get_term_by( 'slug', $_POST['filter_cat'][ $i ], 'product_cat' )->term_id;
					}
				}

				if ( 'true' == $_POST['btn_more'] ) {
					if ( ! $options['extra_atts']['page'] ) {
						$options['extra_atts']['page'] = 2;
					} else {
						++ $options['extra_atts']['page'];
					}
					$options['extra_atts']['paginate'] = 0;
				} else {
					$options['extra_atts']['paginate'] = 1;
					$options['extra_atts']['page']     = 1;
				}

				$atts = '';

				foreach ( $options as $key => $value ) {
					wc_set_loop_prop( $key, $value );
				}

				wc_set_loop_prop( 'page', $options['extra_atts']['page'] );

				foreach ( $options['extra_atts'] as $key => $value ) {
					$atts .= $key . '=' . $value . ' ';
				}

				echo do_shortcode( '[products ' . $atts . ']' );
			}
		}
		exit;

		// phpcs:enable
	}
endif;

if ( ! function_exists( 'molla_woocommerce_more_articles_action' ) ) :
	/**
	 * Load more posts.
	 */
	function molla_woocommerce_more_articles_action() {

		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		if ( isset( $_POST['page'] ) && isset( $_POST['atts'] ) ) {

			$atts = $_POST['atts'];
			$page = $_POST['page'];

			$page['paged'] ++;

			$posts = new WP_Query( $page );

			if ( $posts ) {

				ob_start();
				while ( $posts->have_posts() ) {
					$posts->the_post();
					if ( function_exists( 'molla_get_template_part' ) ) {
						molla_get_template_part(
							'template-parts/posts/loop/loop',
							'',
							$atts
						);
					}
				}
				wp_reset_postdata();
				echo ob_get_clean();
			}
		}
		exit;

		// phpcs:enable
	}
endif;

// add_action(
// 	'wp_enqueue_scripts',
// 	function() {
// 		wp_enqueue_script( 'jquery-magnific-popup' );
// 		wp_enqueue_script( 'jquery-countdown' );
// 		wp_enqueue_script( 'wc-single-product' );
// 		wp_enqueue_script( 'wc-add-to-cart-variation' );
// 		wp_enqueue_script( 'zoom' );
// 	}
// );

if ( ! function_exists( 'molla_woocommerce_quickview_action' ) ) :
	/**
	 * Quickview popup.
	 */
	function molla_woocommerce_quickview_action() {

		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		if ( isset( $_POST['product_id'] ) && class_exists( 'WooCommerce' ) ) {
			global $product, $post;
			$old_product = $product;
			$old_post    = $post;
			$post        = get_post( intval( $_POST['product_id'] ) );
			$product     = wc_get_product( intval( $_POST['product_id'] ) );

			if ( post_password_required() ) {
				echo get_the_password_form();
				die();
				return;
			}

			$_POST['quickview'] = true;
			add_filter( 'woocommerce_gallery_thumbnail_size', 'molla_single_product_thumbnail_size' );
			add_filter( 'woocommerce_gallery_image_size', 'molla_single_product_thumbnail_size' );

			ob_start();
			wc_get_template_part( 'content', 'single-product' );

			echo '<script>';
			echo  'var wc_single_product_params = ' .
				json_encode(
					apply_filters(
						'wc_single_product_params',
						array(
							'i18n_required_rating_text' => esc_html__( 'Please select a rating', 'molla' ),
							'review_rating_required'    => wc_review_ratings_required() ? 'yes' : 'no',
							'flexslider'                => apply_filters(
								'woocommerce_single_product_carousel_options',
								array(
									'rtl'            => is_rtl(),
									'animation'      => 'slide',
									'smoothHeight'   => true,
									'directionNav'   => false,
									'controlNav'     => 'thumbnails',
									'slideshow'      => false,
									'animationSpeed' => 500,
									'animationLoop'  => false, // Breaks photoswipe pagination if true.
									'allowOneSlide'  => false,
								)
							),
							'zoom_enabled'              => apply_filters( 'woocommerce_single_product_zoom_enabled', get_theme_support( 'wc-product-gallery-zoom' ) ),
							'zoom_options'              => apply_filters( 'woocommerce_single_product_zoom_options', array() ),
							'photoswipe_enabled'        => apply_filters( 'woocommerce_single_product_photoswipe_enabled', get_theme_support( 'wc-product-gallery-lightbox' ) ),
							'photoswipe_options'        => apply_filters(
								'woocommerce_single_product_photoswipe_options',
								array(
									'shareEl'       => false,
									'closeOnScroll' => false,
									'history'       => false,
									'hideAnimationDuration' => 0,
									'showAnimationDuration' => 0,
								)
							),
							'flexslider_enabled'        => apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) ),
						)
					)
				) . ';';
			echo '</script>';

			if ( 'variable' == $product->get_type() ) {
				wc_get_template( 'single-product/add-to-cart/variation.php' );

				echo '<script>';
				echo 'var wc_add_to_cart_variation_params = ' .
					json_encode(
						apply_filters(
							'wc_add_to_cart_variation_params',
							array(
								'wc_ajax_url'           => WC_AJAX::get_endpoint( '%%endpoint%%' ),
								'i18n_no_matching_variations_text' => esc_js( esc_html__( 'Sorry, no products matched your selection. Please choose a different combination.', 'molla' ) ),
								'i18n_make_a_selection_text' => esc_js( esc_html__( 'Please select some product options before adding this product to your cart.', 'molla' ) ),
								'i18n_unavailable_text' => esc_js( esc_html__( 'Sorry, this product is unavailable. Please choose a different combination.', 'molla' ) ),
							)
						)
					) . ';';
				echo '</script>';
			}

			echo ob_get_clean();

			$product = $old_product;
			$post    = $old_post;
			unset( $_POST['quickview'] );
		}
		exit;

		// phpcs:enable
	}
endif;

if ( ! function_exists( 'molla_lazy_load_menus' ) ) :
	function molla_lazy_load_menus() {

		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		if ( isset( $_POST['menus'] ) && is_array( $_POST['menus'] ) ) {
			$menus = $_POST['menus'];
			if ( ! empty( $menus ) ) {
				$result = array();
				foreach ( $menus as $menu ) {
					$result[ $menu ] = wp_nav_menu(
						array(
							'menu'       => $menu,
							'container'  => '',
							'items_wrap' => '%3$s',
							'echo'       => false,
							'lazy'       => false,
						)
					);
				}
				echo json_encode( $result );
			}
		}

		exit;

		// phpcs:enable
	}
endif;

if ( ! function_exists( 'molla_update_minicart_qty' ) ) :
	function molla_update_minicart_qty() {
		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification\
		if ( isset( $_POST['cart_item_key'] ) && isset( $_POST['qty'] ) ) {
			$cart_item_key     = $_POST['cart_item_key'];
			$qty               = $_POST['qty'];
			$passed_validation = apply_filters( 'woocommerce_update_cart_validation', true, WC()->cart->get_cart()[ $cart_item_key ], $qty );
			if ( $passed_validation ) {
				WC()->cart->set_quantity( $cart_item_key, $qty, true );
			}
		}
		exit;
		// phpcs:enable
	}
endif;

if ( ! function_exists( 'molla_print_popup' ) ) :
	function molla_print_popup() {

		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		if ( isset( $_POST['popup_id'] ) && ( ! isset( $_COOKIE['molla_modal_disable_onload'] ) || ! $_COOKIE['molla_modal_disable_onload'] ) ) {
			$settings = get_post_meta( $_POST['popup_id'], '_elementor_page_settings', true );
			echo do_shortcode( '[molla_lightbox lightbox_block_name="' . $_POST['popup_id'] . '" content_type="popup" lightbox_style="' . ( isset( $settings['popup_animation'] ) ? $settings['popup_animation'] : '' ) . '"]' );
		}
		exit;

		// phpcs:enable
	}
endif;
