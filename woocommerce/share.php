<?php
/**
 * Share template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 3.0.0
 */

/**
 * Template variables:
 *
 * @var $share_title string Title for share section
 * @var $share_facebook_enabled bool Whether to enable FB sharing button
 * @var $share_twitter_enabled bool Whether to enable Twitter sharing button
 * @var $share_pinterest_enabled bool Whether to enable Pintereset sharing button
 * @var $share_email_enabled bool Whether to enable Email sharing button
 * @var $share_whatsapp_enabled bool Whether to enable WhatsApp sharing button (mobile online)
 * @var $share_url_enabled bool Whether to enable share via url
 * @var $share_link_title string Title to use for post (where applicable)
 * @var $share_link_url string Url to share
 * @var $share_summary string Summary to use for sharing on social media
 * @var $share_image_url string Image to use for sharing on social media
 * @var $share_twitter_summary string Summary to use for sharing on Twitter
 * @var $share_facebook_icon string Icon for facebook sharing button
 * @var $share_twitter_icon string Icon for twitter sharing button
 * @var $share_pinterest_icon string Icon for pinterest sharing button
 * @var $share_email_icon string Icon for email sharing button
 * @var $share_whatsapp_icon string Icon for whatsapp sharing button
 * @var $share_whatsapp_url string Sharing url on whatsapp
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly

$share_icon_type = molla_option( 'share_icon_type' );
$share_icon_size = molla_option( 'share_icon_size' );

?>

<?php do_action( 'yith_wcwl_before_wishlist_share', $wishlist ); ?>

<div class="yith-wcwl-share social-icons">
	<h4 class="yith-wcwl-share-title social-label"><?php echo esc_html( $share_title ); ?></h4>
	<ul class="social-icons<?php echo ( 'colored-simple' == $share_icon_type || ( 'colored-circle' ) == $share_icon_type ? ' social-icons-colored' : '' ) . ( 'circle' == $share_icon_type || ( 'colored-circle' ) == $share_icon_type ? ' circle-type' : '' ) . ( $share_icon_size ? ' social-icons-sm' : '' ); ?>">
		<?php if ( $share_facebook_enabled ) : ?>
			<li class="share-button">
				<a target="_blank" class="social-icon social-facebook" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode( $share_link_url ); ?>&p[title]=<?php echo esc_attr( $share_link_title ); ?>" title="<?php _e( 'Facebook', 'molla' ); ?>">
					<?php echo ! $share_facebook_icon ? esc_html__( 'Facebook', 'molla' ) : $share_facebook_icon; ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( $share_twitter_enabled ) : ?>
			<li class="share-button">
				<a target="_blank" class="social-icon social-twitter" href="https://twitter.com/share?url=<?php echo urlencode( $share_link_url ); ?>&amp;text=<?php echo esc_attr( $share_twitter_summary ); ?>" title="<?php _e( 'Twitter', 'molla' ); ?>">
					<?php echo ! $share_twitter_icon ? esc_html__( 'Twitter', 'molla' ) : $share_twitter_icon; ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( $share_pinterest_enabled ) : ?>
			<li class="share-button">
				<a target="_blank" class="social-icon social-pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( $share_link_url ); ?>&amp;description=<?php echo esc_attr( $share_summary ); ?>&amp;media=<?php echo esc_attr( $share_image_url ); ?>" title="<?php _e( 'Pinterest', 'molla' ); ?>" onclick="window.open(this.href); return false;">
					<?php echo ! $share_pinterest_icon ? esc_html__( 'Pinterest', 'molla' ) : $share_pinterest_icon; ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( $share_email_enabled ) : ?>
			<li class="share-button">
				<a class="social-icon social-email" href="mailto:?subject=<?php echo urlencode( apply_filters( 'yith_wcwl_email_share_subject', $share_link_title ) ); ?>&amp;body=<?php echo apply_filters( 'yith_wcwl_email_share_body', urlencode( $share_link_url ) ); ?>&amp;title=<?php echo esc_attr( $share_link_title ); ?>" title="<?php _e( 'Email', 'molla' ); ?>">
					<?php echo ! $share_email_icon ? esc_html__( 'Email', 'molla' ) : $share_email_icon; ?>
				</a>
			</li>
		<?php endif; ?>

		<?php
		if ( $share_whatsapp_enabled ) :
			?>
			<li class="share-button">
				<a class="social-icon social-whatsapp" href="<?php echo esc_attr( $share_whatsapp_url ); ?>" data-action="share/whatsapp/share" target="_blank" title="<?php _e( 'WhatsApp', 'molla' ); ?>">
					<?php echo ! $share_whatsapp_icon ? esc_html__( 'Whatsapp', 'molla' ) : $share_whatsapp_icon; ?>
				</a>
			</li>
			<?php
		endif;
		?>
	</ul>

	<?php if ( $share_url_enabled ) : ?>
		<div class="yith-wcwl-after-share-section">
			<input class="copy-target" readonly="readonly" type="url" name="yith_wcwl_share_url" id="yith_wcwl_share_url" value="<?php echo esc_attr( $share_link_url ); ?>"/>
			<?php echo ( ! empty( $share_link_url ) ) ? sprintf( '<small>%s <span class="copy-trigger">%s</span> %s</small>', esc_html__( '(Now', 'molla' ), esc_html__( 'copy', 'molla' ), esc_html__( 'this wishlist link and share it anywhere)', 'molla' ) ) : ''; ?>
		</div>
	<?php endif; ?>

	<?php do_action( 'yith_wcwl_after_share_buttons', $share_link_url, $share_title, $share_link_title ); ?>
</div>

<?php do_action( 'yith_wcwl_after_wishlist_share', $wishlist ); ?>
