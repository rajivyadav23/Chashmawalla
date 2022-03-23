<aside class="content-left plugins">
</aside>
<div class="content-right">
	<h2><?php esc_html_e( 'Default Plugins', 'molla' ); ?></h2>
	<form method="post">

		<?php
		$plugins = $this->_get_plugins();
		if ( count( $plugins['all'] ) ) {
			?>
			<p class="lead"><?php echo esc_html__( 'This will install the default plugins which is used in Molla', 'molla' ) . '<br>' . esc_html__( 'Please check the plugins to install:', 'molla' ); ?></p>
			<ul class="molla-setup-wizard-plugins">
				<?php
				$idx      = 0;
				$loadmore = false;
				foreach ( $plugins['all'] as $slug => $plugin ) {
					if ( isset( $plugin['visibility'] ) && 'speed_wizard' == $plugin['visibility'] ) {
						continue;
					}
					$idx ++;
					?>
					<?php
					if ( $idx > 6 && ! $loadmore ) :
						?>
						<li class="separator">
							<a href="#" class="button-load-plugins"><b><?php esc_html_e( 'Load more', 'molla' ); ?></b> <i class="fas fa-chevron-down"></i></a>
						</li>
						<?php
						$loadmore = true;
					endif;
					?>
					<li data-slug="<?php echo esc_attr( $slug ); ?>"<?php echo 6 < $idx ? ' class="hidden"' : ''; ?>>
						<label class="checkbox checkbox-inline">
							<input type="checkbox" name="setup-plugin"<?php echo ! $plugin['required'] ? '' : ' checked="checked"'; ?>>
							<?php echo esc_html( $plugin['name'] ); ?>
							<span class="info">
							<?php
								$key = '';
							if ( isset( $plugins['install'][ $slug ] ) ) {
								$key = esc_html__( 'Installation', 'molla' );
							} elseif ( isset( $plugins['update'][ $slug ] ) ) {
								$key = esc_html__( 'Update', 'molla' );
							} elseif ( isset( $plugins['activate'][ $slug ] ) ) {
								$key = esc_html__( 'Activation', 'molla' );
							}
							if ( $key ) {
								if ( $plugin['required'] ) {
									/* translators: %s: Plugin name */
									printf( esc_html__( '%s required', 'molla' ), $key );
								} else {
									/* translators: %s: Plugin name */
									printf( esc_html__( '%s recommended for certain demos', 'molla' ), $key );
								}
							}
							?>
							</span>
						</label>
						<div class="spinner"></div>
					</li>
					<?php if ( 'molla-core' === $plugin['slug'] ) : ?>
						<li class="separator"></li>
					<?php endif; ?>
				<?php } ?>
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
