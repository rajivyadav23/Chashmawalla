<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Molla_Studio' ) ) :

	class Molla_Studio {

		/**
		 * total blocks per page
		 */
		private $limit = 30;

		/**
		 * default category id
		 */
		private $default_category_id = 3; // full page category

		/**
		 * block update period
		 */
		private $update_period = HOUR_IN_SECONDS * 24 * 30; // a month

		/**
		 * Page Builder Type
		 *
		 * This should be 'e' if using Elementor Page Builder, 'v' if using Visual Composer, and 'g' if using Gutenberg Editor.
		 */
		private $page_type = 'e';

		/**
		 * constructor
		 */
		public function __construct() {

			if ( wp_doing_ajax() && isset( $_POST['type'] ) ) {
				$this->page_type = sanitize_text_field( $_POST['type'] );
			}
			add_action( 'wp_ajax_molla_studio_import', array( $this, 'import' ) );
			add_action( 'wp_ajax_molla_studio_filter_category', array( $this, 'filter_category' ) );

			if ( 'post.php' == $GLOBALS['pagenow'] || 'post-new.php' == $GLOBALS['pagenow'] ) {
				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					add_action( 'elementor/editor/footer', array( $this, 'elementor_get_page_content' ) );
					add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue' ), 30 );
				}
			}
		}

		public function add_studio_control( $list ) {
			$list[] = array( 'molla_studio', '<li><a href="javascript:;" class="molla-studio-editor-button" id="molla-studio-editor-button" title="Molla Studio">Molla Studio</a></li>' );
			return $list;
		}

		public function enqueue() {
			if ( is_rtl() ) {
				wp_enqueue_style( 'molla-plugins', MOLLA_PLUGINS_CSS . '/plugins-rtl.css' );
			} else {
				wp_enqueue_style( 'molla-plugins', MOLLA_PLUGINS_CSS . '/plugins.css' );
			}
			wp_enqueue_style( 'molla-studio-fonts', '//fonts.googleapis.com/css?family=Open+Sans%3A400%2C600%2C700&ver=5.2.1' );
			wp_enqueue_script( 'jquery-magnific-popup', MOLLA_JS . '/plugins/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
			wp_enqueue_script( 'jquery-waitforimages', MOLLA_JS . '/plugins/jquery.waitforimages.min.js', array( 'jquery' ), '2.0.2', true );
			wp_enqueue_script( 'isotope-pkgd', MOLLA_JS . '/plugins/isotope.pkgd.min.js', array( 'jquery' ), '3.0.6', true );

			if ( is_rtl() ) {
				wp_enqueue_style( 'molla-studio', MOLLA_PRO_LIB_URI . '/studio/studio-rtl.css' );
			} else {
				wp_enqueue_style( 'molla-studio', MOLLA_PRO_LIB_URI . '/studio/studio-ltr.css' );
			}

			wp_enqueue_script( 'molla-admin', MOLLA_JS . '/admin/admin.min.js', array( 'jquery-magnific-popup' ), MOLLA_VERSION, true );
			wp_enqueue_script( 'molla-studio', MOLLA_PRO_LIB_URI . '/studio/studio.js', array( 'molla-admin' ), MOLLA_VERSION, true );

			wp_localize_script(
				'molla-studio',
				'molla_studio',
				array(
					'wpnonce' => wp_create_nonce( 'molla_studio_nonce' ),
				)
			);
		}

		/**
		 * Import molla blocks in Elementor, Visual Composer backend editor
		 */
		public function import( $pure_return = false ) {
			check_ajax_referer( 'molla_studio_nonce', 'wpnonce' );

			if ( isset( $_POST['block_id'] ) ) {
				require_once MOLLA_PLUGINS . '/importer/importer-api.php';
				$importer_api = new Molla_Importer_API();

				$args = $importer_api->generate_args( false );
				$url  = add_query_arg( $args, $importer_api->get_url( 'studio_block_content' ) );
				$url  = add_query_arg( array( 'block_id' => ( (int) $_POST['block_id'] ) ), $url );

				$block = $importer_api->get_response( $url );

				if ( is_wp_error( $block ) || ! $block || ! isset( $block['content'] ) ) {
					if ( $pure_return ) {
						return false;
					}
					echo json_encode( array( 'error' => esc_js( esc_html__( 'Security issue found! Please try again later.', 'molla' ) ) ) );
					die();
				}
				$block_content = $block['content'];

				// process attachments
				if ( isset( $block['images'] ) ) {
					$block_content = $this->process_posts( $block_content, $block['images'] );
				}
				// process contact forms
				if ( isset( $block['posts'] ) ) {
					$block_content = $this->process_posts( $block_content, $block['posts'], false );
				}
				// replace urls
				$block_content = str_replace( $block['url'], get_home_url(), $block_content );

				if ( 'e' == $this->page_type ) {
					$block_content = json_decode( $block_content, true );
				}

				$result = array( 'content' => $block_content );
				if ( isset( $block['meta'] ) && $block['meta'] ) {
					$result['meta'] = json_decode( $block['meta'], true );
				}

				if ( $pure_return ) {
					return $result;
				}
				return wp_send_json( $result );
			}
		}

		public function filter_category() {
			check_ajax_referer( 'molla_studio_nonce', 'wpnonce' );
			$count_per_page = $this->limit;
			$page           = isset( $_POST['page'] ) && $_POST['page'] ? (int) $_POST['page'] : 1;

			if ( 'e' == $this->page_type ) {
				$transient_key = 'molla_blocks_e';
			} else {
				$transient_key = 'molla_blocks_v';
			}
			$blocks = get_site_transient( $transient_key );
			if ( ! $blocks ) {
				require_once MOLLA_PLUGINS . '/importer/importer-api.php';
				$importer_api = new Molla_Importer_API();
				$args         = $importer_api->generate_args( false );
				$args['type'] = $this->page_type;
				$blocks       = $importer_api->get_response( add_query_arg( $args, $importer_api->get_url( 'studio_blocks' ) ) );
				if ( is_wp_error( $blocks ) || ! $blocks ) {
					die( 'error' );
				}
				set_site_transient( $transient_key, $blocks, $this->update_period );
			}
			$category_blocks = array();
			if ( isset( $_POST['category_id'] ) && $_POST['category_id'] ) {
				foreach ( $blocks as $block ) {
					$categories = explode( ',', $block['c'] );
					if ( in_array( $_POST['category_id'], $categories ) ) {
						$category_blocks[] = $block;
					}
				}
				$category_blocks = array_slice( $category_blocks, ( $page - 1 ) * $count_per_page, $count_per_page );
			} elseif ( isset( $_POST['demo_filter'] ) && is_array( $_POST['demo_filter'] ) ) {
				foreach ( $blocks as $block ) {
					if ( in_array( $block['d'], $_POST['demo_filter'] ) ) {
						$category_blocks[] = $block;
					}
				}
				$total_pages     = ceil( count( $category_blocks ) / $count_per_page );
				$category_blocks = array_slice( $category_blocks, ( $page - 1 ) * $count_per_page, $count_per_page );
			}
			if ( ! empty( $category_blocks ) ) {
				$args = array(
					'block_categories'    => array(),
					'blocks'              => $category_blocks,
					'default_category_id' => $this->default_category_id,
					'page_type'           => $this->page_type,
				);
				if ( isset( $total_pages ) ) {
					$args['total_pages'] = $total_pages;
				}

				molla_get_template_part(
					'inc/lib/pro/studio/blocks',
					null,
					$args
				);
			}
			die();
		}

		/**
		 * Import related posts such as attachments and contact forms
		 */
		private function process_posts( $block_content, $posts, $is_attachment = true ) {
			if ( ! trim( $posts ) ) {
				return $block_content;
			}
			$posts = json_decode( trim( $posts ), true );

			if ( empty( $posts ) ) {
				return $block_content;
			}

			// Check if image is already imported by its ID.
			$id_arr = array();
			foreach ( array_keys( $posts ) as $old_id ) {
				$id_arr[] = ( (int) $_POST['block_id'] ) . '-' . ( (int) $old_id );
			}
			$args = array(
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => '_molla_studio_id',
						'value'   => $id_arr,
						'compare' => 'IN',
					),
				),
			);
			if ( $is_attachment ) {
				$args['post_type']   = 'attachment';
				$args['post_status'] = 'inherit';
			} else {
				$args['post_type']   = 'wpcf7_contact_form';
				$args['post_status'] = 'publish';
			}
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				foreach ( $query->posts as $post ) {
					$old_id    = str_replace( ( (int) $_POST['block_id'] ) . '-', '', get_post_meta( $post->ID, '_molla_studio_id', true ) );
					$image_url = wp_get_attachment_url( $post->ID );

					if ( 'wpcf7_contact_form' == $post->post_type ) {
						$block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', '[contact-form-7 id=\\"' . $post->ID . '\\"]', $block_content );
					} else { // attachment
						$block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', '"id":' . $post->ID . ',"url":"' . $image_url . '"', $block_content );
					}
					unset( $posts[ $old_id ] );
				}
			}

			if ( ! empty( $posts ) ) {

				if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
					define( 'WP_LOAD_IMPORTERS', true ); // we are loading importers
				}

				if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
					require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
				}

				if ( ! class_exists( 'WP_Import' ) ) { // if WP importer doesn't exist
					require_once MOLLA_PLUGINS . '/importer/wordpress-importer.php';
				}

				if ( current_user_can( 'edit_posts' ) && class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) {

					$importer                    = new WP_Import();
					$importer->fetch_attachments = true;

					if ( $is_attachment ) {
						foreach ( $posts as $old_id => $image_url ) {
							$post_data = array(
								'post_title'   => substr( $image_url, strrpos( $image_url, '/' ) + 1, -4 ),
								'post_content' => '',
								'upload_date'  => date( 'Y-m-d H:i:s' ),
								'post_status'  => 'inherit',
							);
							$import_id = $importer->process_attachment( $post_data, $image_url );

							if ( is_wp_error( $import_id ) ) {
								// if image does not exist
								$block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', '"id":"","url":""', $block_content );
							} else {
								update_post_meta( $import_id, '_molla_studio_id', ( (int) $_POST['block_id'] ) . '-' . ( (int) $old_id ) );
								$import_url    = wp_get_attachment_url( $import_id );
								$block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', '"id":' . $import_id . ',"url":"' . $import_url . '"', $block_content );
							}
						}
					} else {
						foreach ( $posts as $old_id => $old_post_data ) {
							$post_data = array(
								'post_title'   => sanitize_text_field( $old_post_data['title'] ),
								'post_type'    => sanitize_text_field( $old_post_data['post_type'] ),
								'post_content' => '', //$old_post_data['content'],//'',//$old_post_data['content'],
								'upload_date'  => date( 'Y-m-d H:i:s' ),
								'post_status'  => 'publish',
							);
							$post_data = wp_slash( $post_data );
							$import_id = wp_insert_post( $post_data, true );
							if ( is_wp_error( $import_id ) ) {
								// if post does not exist
								$block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', '""', $block_content );
							} else {
								update_post_meta( $import_id, '_molla_studio_id', ( (int) $_POST['block_id'] ) . '-' . ( (int) $old_id ) );
								if ( isset( $old_post_data['meta'] ) ) {
									foreach ( $old_post_data['meta'] as $meta_key => $meta_value ) {
										update_post_meta( $import_id, $meta_key, $meta_value );
									}
								}
								$block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', '[contact-form-7 id=\\"' . $import_id . '\\"]', $block_content );
							}
						}
					}
				}
			}

			return $block_content;
		}

		public function get_page_content() {

			// get block categories
			if ( 'e' == $this->page_type ) {
				$transient_key = 'molla_block_categories_e';
			} else {
				$transient_key = 'molla_block_categories_v';
			}
			$block_categories = get_site_transient( $transient_key );
			if ( ! $block_categories ) {
				require_once MOLLA_PLUGINS . '/importer/importer-api.php';
				$importer_api = new Molla_Importer_API();

				$args             = $importer_api->generate_args( false );
				$args['limit']    = $this->limit;
				$args['type']     = $this->page_type;
				$block_categories = $importer_api->get_response( add_query_arg( $args, $importer_api->get_url( 'studio_block_categories' ) ) );

				if ( is_wp_error( $block_categories ) || ! $block_categories ) {
					return esc_html__( 'Could not connect to the API Server! Please try again later.', 'molla' );
				}
				set_site_transient( $transient_key, $block_categories, $this->update_period );
			}

			// get blocks
			if ( 'e' == $this->page_type ) {
				$transient_key = 'molla_blocks_e';
			} else {
				$transient_key = 'molla_blocks_v';
			}
			$blocks = get_site_transient( $transient_key );
			if ( ! $blocks ) {
				if ( ! isset( $importer_api ) ) {
					require_once MOLLA_PLUGINS . '/importer/importer-api.php';
					$importer_api = new Molla_Importer_API();
					$args         = $importer_api->generate_args( false );
					$args['type'] = $this->page_type;
				}
				$blocks = $importer_api->get_response( add_query_arg( $args, $importer_api->get_url( 'studio_blocks' ) ) );
				if ( is_wp_error( $blocks ) || ! $blocks ) {
					return esc_html__( 'Could not connect to the API Server! Please try again later.', 'molla' );
				}
				set_site_transient( $transient_key, $blocks, $this->update_period );
			}
			$latest_blocks = array();
			foreach ( $blocks as $block ) {
				$categories = explode( ',', $block['c'] );
				if ( in_array( $this->default_category_id, $categories ) ) {
					$latest_blocks[] = $block;
				}
			}
			if ( is_array( $block_categories ) && ! empty( $latest_blocks ) ) {
				molla_get_template_part(
					'inc/lib/pro/studio/blocks',
					null,
					array(
						'block_categories'    => $block_categories,
						'blocks'              => array_slice( $latest_blocks, 0, $this->limit ),
						'default_category_id' => $this->default_category_id,
						'page_type'           => $this->page_type,
					)
				);
			}
		}

		public function elementor_get_page_content() {
			$this->page_type = 'e';
			$this->get_page_content();
		}
	}

	new Molla_Studio;

endif;
