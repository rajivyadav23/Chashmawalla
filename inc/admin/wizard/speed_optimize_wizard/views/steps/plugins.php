<aside class="content-left welcome">
</aside>
<div class="content-right">
	<h2><?php esc_html_e( 'Plugins', 'molla' ); ?></h2>
	<form method="post">

		<?php
		$plugins = $this->_get_plugins();
		if ( count( $plugins['all'] ) ) {
			?>
			<p class="lead" style="margin-bottom: 5px;"><?php esc_html_e( 'This will install the plugins which can acclerate your site.', 'molla' ); ?></p>
			<p class="info-qt light-info" style="margin-top: 0;margin-bottom: 20px;"><?php esc_html_e( 'You should disable below plugins while in development. Changes may not be applied because of them.', 'molla' ); ?></p>
			<ul class="molla-speed-wizard-plugins">
				<?php
				foreach ( $plugins['all'] as $slug => $plugin ) {
					?>
					<li data-slug="<?php echo esc_attr( $slug ); ?>"<?php echo isset( $plugin['visibility'] ) && 'hidden' === $plugin['visibility'] ? ' class="hidden"' : ''; ?>>
						<label class="checkbox checkbox-inline">
							<input type="checkbox" name="setup-plugin"<?php echo ! $plugin['required'] ? '' : ' checked="checked"'; ?>>
							<?php
								$key = '';
							if ( isset( $plugins['install'][ $slug ] ) ) {
								$key = esc_html__( 'Install', 'molla' );
							} elseif ( isset( $plugins['update'][ $slug ] ) ) {
								$key = esc_html__( 'Update', 'molla' );
							} elseif ( isset( $plugins['activate'][ $slug ] ) ) {
								$key = esc_html__( 'Activate', 'molla' );
							}
							?>
							<?php /* translators: %s: Plugin url and name */ ?>
							<?php printf( ' <a href="%s" target="_blank">%s</a>', 'https://wordpress.org/plugins/' . esc_attr( $slug ) . '/', $plugin['name'] ); ?>
							<span></span>
						</label>
						<div class="spinner"></div>
						<?php if ( $plugin['desc'] ) : ?>
							<p style="margin-top: 5px;margin-bottom: 15px;"><?php echo esc_html( $plugin['desc'] ); ?></p>
						<?php endif; ?>
					</li>
					<?php if ( 'molla-core' === $plugin['slug'] ) : ?>
						<li class="separator"></li>
					<?php endif; ?>
				<?php } ?>
			</ul>
			<ul style="margin-bottom: 20px;">
				<li class="howto">
					<a href="https://gtmetrix.com/leverage-browser-caching.html" target="_blank" style="font-style: normal;"><?php esc_html_e( 'How to enable leverage browser  caching.', 'molla' ); ?></a>
					<p style="margin-top: 0;font-style: normal;"><?php esc_html_e( 'Page loading duration can be significantly improved by asking visitors to save and reuse the files included in your website.', 'molla' ); ?></p>
				</li>
			</ul>
			<?php
		} else {
			echo '<p class="lead">' . esc_html__( 'Good news! All plugins are already installed and up to date. Please continue.', 'molla' ) . '</p>';
		}
		?>

		<p class="molla-setup-actions step">
			<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next" data-callback="install_plugins"><?php esc_html_e( 'Continue', 'molla' ); ?></a>
			<?php wp_nonce_field( 'molla-setup-wizard' ); ?>
		</p>
	</form>
</div>
