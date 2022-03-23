<?php
/**
 * Molla Social Login
 */

if ( is_user_logged_in() ) : ?>

	<h1 class="text-uppercase mb-0"><?php the_title(); ?></h1>

	<?php
else :
	?>

	<div class="social-login">
		<p class="text-center"><?php esc_html_e( 'or sign in with', 'molla' ); ?></p>
		<div class="socials">
		<?php do_action( 'molla_before_login_social', $socials ); ?>
		<?php if ( $socials['is_facebook_login'] ) { ?>
			<a class="btn btn-icon social-button facebook" href="<?php echo wp_login_url(); ?>?loginFacebook=1&redirect=<?php echo the_permalink(); ?>" onclick="window.location.href = '<?php echo wp_login_url(); ?>?loginFacebook=1&redirect='+window.location.href; return false"><i class="icon-facebook-f"></i>
				<span><?php esc_html_e( 'Login With Facebook', 'molla' ); ?></span></a>
		<?php } ?>
		<?php if ( $socials['is_google_login'] ) { ?>
			<a class="btn btn-icon social-button google" href="<?php echo wp_login_url(); ?>?loginGoogle=1&redirect=<?php echo the_permalink(); ?>" onclick="window.location.href = '<?php echo wp_login_url(); ?>?loginGoogle=1&redirect='+window.location.href; return false">
				<i class="icon-google"></i>
				<span><?php esc_html_e( 'Login With Google', 'molla' ); ?></span></a>
		<?php } ?>
		<?php if ( $socials['is_twitter_login'] ) { ?>
			<a class="btn btn-icon social-button twitter" href="<?php echo wp_login_url(); ?>?loginSocial=twitter&redirect=<?php echo the_permalink(); ?>" onclick="window.location.href = '<?php echo wp_login_url(); ?>?loginSocial=twitter&redirect='+window.location.href; return false">
				<i class="icon-twitter"></i>
				<span><?php esc_html_e( 'Login With Twitter', 'molla' ); ?></span></a>
		<?php } ?>
		<?php do_action( 'molla_after_login_social', $socials ); ?>
		</div>
	</div>
<?php endif; ?>
