<?php
/**
 * The template for displaying Comments.
 */
if ( post_password_required() ) {
	return;
}

if ( have_comments() ) :
	?>
<div id="comments" class="comments">
	<?php
	$comments_count = number_format_i18n( get_comments_number() );
	$title          = ( $comments_count ? $comments_count : 'No' ) . ' ' . ( 1 == $comments_count ? esc_html__( 'Comment', 'molla' ) : esc_html__( 'Comments', 'molla' ) );
	$title          = apply_filters( 'molla_comments_title', $title );
	?>
	<h3 class="title"><?php echo esc_html( $title ); ?></h3>
		<ul>
			<?php
			// List comments
			wp_list_comments(
				array(
					'callback' => 'molla_comment',
					'style'    => 'ul',
					'format'   => 'html5',
					'short_ping'  => true,
				)
			);
			?>
		</ul>
</div>
	<?php
endif;

if ( comments_open() ) {
	$commenter       = wp_get_current_commenter();
	$consent_escaped = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
	comment_form(
		array(
			'fields'        => array(
				'author'  => '<div class="col-6"><input name="author" type="text" class="form-control" value="" placeholder="' . esc_attr__( 'Name', 'molla' ) . '*"> </div>',
				'email'   => '<div class="col-6"><input name="email" type="text" class="form-control" value="" placeholder="' . esc_attr__( 'Email', 'molla' ) . '*"> </div>',
				'cookies' => '<p class="comment-form-cookies-consent custom-control custom-checkbox col-sm-12"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" class="custom-control-input"' . $consent_escaped . ' />' . '<label class="custom-control-label" for="wp-comment-cookies-consent">' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'molla' ) . '</label></p>',
			),
			'comment_field' => '<textarea name="comment" id="comment" class="form-control" cols="45" maxlength="65525" required="required" placeholder="' . esc_attr__( 'Comment', 'molla' ) . '*"></textarea>',
			'submit_button' => '<button type="submit" class="btn"> <span>' . esc_attr__( 'POST COMMENT', 'molla' ) . '</span> <i class="icon-long-arrow-' . ( is_rtl() ? 'left' : 'right' ) . '"></i> </button>',
		)
	);
}
