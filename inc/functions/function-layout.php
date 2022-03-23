<?php

if ( ! function_exists( 'molla_header_elements' ) ) {
	/**
	 * show header elements
	 */
	function molla_header_elements( $elements ) {

		if ( ! $elements || empty( $elements ) ) {
			return;
		}

		foreach ( $elements as $element ) {
			if ( is_array( $element ) ) {
				echo '<div class="header-row-wrap">';
					molla_header_elements( $element );
				echo '</div>';
			} else {

				foreach ( $element as $key => $value ) {
					molla_get_template_part( 'template-parts/header/elements/' . $key, null, $value );
				}
			}
		}
	}
}

if ( ! function_exists( 'molla_comment' ) ) {
	/**
	 * Template comments
	 */

	function molla_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<div class="comment">
				<?php if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>
					<p><?php esc_html_e( 'Pingback:', 'molla' ); ?> <?php echo get_comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'molla' ), '<span class="edit-link">', '</span>' ); ?></p>
				<?php else : ?>
					<div class="comment-media">
						<?php echo get_avatar( $comment, 80 ); ?>
					</div>

					<div class="comment-body">
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'reply_text' => esc_html__( 'Reply', 'molla' ) . '<i class="icon-mail-reply"></i> ',
									'add_below'  => 'comment',
									'depth'      => $depth,
									'max_depth'  => $args['max_depth'],
								)
							)
						);
						?>
						<div class="comment-user">
							<h4><?php echo get_comment_author_link(); ?></h4>
							<span class="comment-date"><?php printf( esc_html__( '%1$s at %2$s', 'molla' ), get_comment_date(), get_comment_time() ); ?></span>
						</div>

						<div class="comment-content">
							<?php if ( '0' == $comment->comment_approved ) : ?>
								<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'molla' ); ?></em>
								<br />
							<?php endif; ?>
							<?php comment_text(); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php
	}
}

add_action( 'comment_form_before_fields', 'molla_comment_form_before_fields' );
add_action( 'comment_form_after_fields', 'molla_comment_form_after_fields' );
if ( ! function_exists( 'molla_comment_form_before_fields' ) ) :
	function molla_comment_form_before_fields( $fields ) {
		echo '<div class="row">';
	}
endif;
if ( ! function_exists( 'molla_comment_form_after_fields' ) ) :
	function molla_comment_form_after_fields( $fields ) {
		echo '</div>';
	}
endif;

function molla_quickview_html( $type = '' ) {
	global $product;

	if ( $type ) {
		$type = '-' . $type;
	}
	$label = '<span>' . esc_html__( 'Quick view', 'molla' ) . '</span>';
	if ( 'card' == wc_get_loop_prop( 'product_style' ) ) {
		$label = '';
	}
	echo apply_filters( 'molla_woocommerce_quickview_html', '<a href="#" class="btn-product' . esc_attr( $type ) . ' btn-quickview" data-product-id="' . (int) $product->get_id() . '" title="Quick view">' . $label . '</a>', $type );
}

function molla_wishlist_html() {
	if ( class_exists( 'YITH_WCWL' ) ) :
		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
	endif;
}

// Remove 'ul' tag in top menu
add_filter( 'wp_nav_menu_args', 'molla_wp_nav_menu_args' );
if ( ! function_exists( 'molla_wp_nav_menu_args' ) ) :
	function molla_wp_nav_menu_args( $args ) {
		if ( isset( $args['target'] ) && 'top_nav' == $args['target'] ) {
			$args['items_wrap'] = '%3$s';
		}
		return $args;
	}
endif;

if ( ! function_exists( 'molla_404_page' ) ) :
	function molla_404_page() {
		ob_start();
		?>
		<div class="error-page-content text-center">
			<div class="heading justify-content-center">
				<div class="heading-content text-center">
					<h2 class="heading-title"><?php esc_html_e( 'Error 404', 'molla' ); ?></h2>
					<p class="heading-subtitle"><?php esc_html_e( "We are sorry, the page you've requested is not available.", 'molla' ); ?></p>
				</div>
			</div>
			<a href="<?php echo esc_url( home_url() ); ?>" class="btn btn-rect btn-outline btn-primary btn-md "><span><?php esc_html_e( 'BACK TO HOMPEAGE', 'molla' ); ?></span><i class="icon-long-arrow-<?php echo ( is_rtl() ? 'left' : 'right' ); ?>"></i></a>
		</div>
		<?php
		echo ob_get_clean();
	}
endif;

