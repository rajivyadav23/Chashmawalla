<aside class="content-left welcome">
</aside>
<div class="content-right">
	<h2><?php esc_html_e( 'Lazyload', 'molla' ); ?></h2>
	<form method="post" class="molla_submit_form">
		<p class="lead"><?php esc_html_e( 'This will help you make your site faster by lazyloading images and contents.', 'molla' ); ?></p>

		<label class="checkbox checkbox-inline">
			<input type="checkbox" name="lazyload" <?php checked( molla_option( 'lazy_load_img' ) ); ?>> Lazy Load Images
		</label>
		<p style="margin-top:5px; margin-bottom: 5px;"><?php esc_html_e( "All image resources will be lazyloaded so that page's loading speed gets faster.", 'molla' ); ?></p>
		<p class="info-qt light-info" style="margin-top: 0; margin-bottom: 15px;"><?php esc_html_e( 'Use with caution! Disable this option if you have any compability problems.', 'molla' ); ?></p>

		<label class="checkbox checkbox-inline">
			<input type="checkbox" name="skeleton" <?php checked( molla_option( 'skeleton_screen' ) ); ?>> Skeleton Screen
		</label>
		<p style="margin-top:5px;margin-bottom:15px;"><?php esc_html_e( 'Instead of real content, skeleton is used to enhance speed of page loading and make it more beautiful.', 'molla' ); ?></p>

		<label class="checkbox checkbox-inline">
			<input type="checkbox" name="webfont" <?php checked( molla_option( 'google_webfont' ) ); ?>> Enable Web Font Loader
		</label>
		<p style="margin-top: 5px; margin-bottom: 15px;"><?php printf( esc_html__( 'Using %1$sWeb Font Loader%2$s, you can enhance page loading speed by about 4 percent in %3$sGoogle PageSpeed Insights%4$s for both mobile and desktop.', 'molla' ), '<a href="https://developers.google.com/fonts/docs/webfont_loader" target="_blank">', '</a>', '<a href="https://developers.google.com/speed/pagespeed/insights/" target="_blank">', '</a>' ); ?></p>

		<p class="molla-setup-actions step">
			<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-outline"><?php esc_html_e( 'Skip this step', 'molla' ); ?></a>
			<button type="submit" class="button-primary button button-large button-next" name="save_step" /><?php esc_html_e( 'Save & Continue', 'molla' ); ?></button>
			<input type="hidden" name="lazyload" id="lazyload" value="<?php echo checked( molla_option( 'lazy_load_img' ), true, false ) ? 'true' : 'false'; ?>">
			<input type="hidden" name="skeleton" id="skeleton" value="<?php echo checked( molla_option( 'skeleton_screen' ), true, false ) ? 'true' : 'false'; ?>">
			<input type="hidden" name="webfont" id="webfont" value="<?php echo checked( molla_option( 'google_webfont' ), true, false ) ? 'true' : 'false'; ?>">
			<?php wp_nonce_field( 'molla-setup-wizard' ); ?>
		</p>
	</form>
</div>
