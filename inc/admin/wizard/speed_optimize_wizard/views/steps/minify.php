<aside class="content-left welcome">
</aside>
<div class="content-right">
	<h2><?php esc_html_e( 'Other Minify', 'molla' ); ?></h2>
	<form method="post" class="molla_submit_form">
		<p class="lead"><?php esc_html_e( 'This will help you to set up general optimization settings.', 'molla' ); ?></p>

		<label class="checkbox checkbox-inline">
			<input type="checkbox" name="css_js" <?php checked( molla_option( 'minify_css_js' ) ); ?>> Minify CSS/JS
		</label>
		<p style="margin-top:5px;margin-bottom:15px;"><?php esc_html_e( 'This will minify all css files which Molla theme generates such as page css, theme option css, global css and etc. Also if you check this option, it uses minified javascript files.', 'molla' ); ?></p>

		<label class="checkbox checkbox-inline">
			<input type="checkbox" name="font_icons" <?php checked( molla_option( 'minify_font_icons' ) ); ?>> Minify Font Icons
		</label>
		<p style="margin-top:5px;margin-bottom:15px;"><?php esc_html_e( 'Unused font icons will be removed from font icon library as well as its CSS.', 'molla' ); ?></p>

		<p class="molla-setup-actions step">
			<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-outline"><?php esc_html_e( 'Skip this step', 'molla' ); ?></a>
			<button type="submit" class="button-primary button button-large button-next" name="save_step"><?php esc_html_e( 'Save & Continue', 'molla' ); ?></button>
			<input type="hidden" name="css_js" id="css_js" value="<?php echo checked( molla_option( 'minify_css_js' ), true, false ) ? 'true' : 'false'; ?>">
			<input type="hidden" name="font_icons" id="font_icons" value="<?php echo checked( molla_option( 'minify_font_icons' ), true, false ) ? 'true' : 'false'; ?>">
			<?php wp_nonce_field( 'molla-setup-wizard' ); ?>
		</p>
	</form>
</div>
