<aside class="content-left welcome">
</aside>
<div class="content-right">
	<h2><?php esc_html_e( 'Optimize Widgets', 'molla' ); ?></h2>
	<form method="post">
		<p class="lead"><?php esc_html_e( "This will help you to optimize elementor widget css files by removing unused widgets' style", 'molla' ); ?></p>
		<p class="descripion"><?php esc_html_e( 'Each page shows its unused widgets. You can remove all resources related to checked widgets. This will reduce 30% ~ 50% of CSS.', 'molla' ); ?></p>
		<p style="margin-bottom: 30px;"><?php esc_html_e( 'Please check unused widgets to remove.', 'molla' ); ?></p>
		<?php
			$pages = get_posts(
				array(
					'numberposts' => -1,
					'post_type'   => 'page',
					'post_status' => 'publish',
					'order'       => 'ASC',
					'exclude'     => class_exists( 'WooCommerce' ) ? array( wc_get_page_id( 'shop' ) ) : array(),
				)
			);
			$cnt   = 0;
			foreach ( $pages as $page ) :
				?>
		<div class="molla-unused-widgets<?php echo esc_attr( $cnt ? '' : ' active' ); ?>" id="<?php echo esc_attr( $page->ID ); ?>">
				<?php
				$unused       = $this->_get_widgets(); /* total widgets */
				$used         = get_post_meta( $page->ID, 'used_widgets' );
				$prev_widgets = get_post_meta( $page->ID, 'removed_widgets', true );
				$toggle_cls   = '';

				if ( ! empty( $used ) ) {
					$used = json_decode( $used[0], true );
				}

				if ( '' !== $prev_widgets ) {
					$prev_widgets = json_decode( $prev_widgets, true );

					if ( empty( $prev_widgets ) ) {
						$toggle_cls = 'none';
					} elseif ( count( array_diff( $prev_widgets, array( 'spacing', 'grid' ) ) ) == count( array_diff( array_keys( $unused ), $used ) ) ) {
						$toggle_cls = 'all';
					}
				} else {
					$toggle_cls = 'all';
				}
				?>
			<div class="molla-page">
				<h3 class="text-capitalize"><?php echo ucwords( $page->post_title ); ?></h3>
				<span class="spinner"></span>
				<span class="result" style="margin-left: 10px;"></span>
				<label class="checkbox checkbox-inline checkbox-toggle">
					<?php echo esc_html( 'Toggle All' ); ?>
					<span type="checkbox" name="<?php echo esc_attr( $page->post_title ); ?>" class="toggle <?php echo esc_attr( $toggle_cls ); ?>"></span>
				</label>
			</div>
			<ul style="<?php echo esc_attr( $cnt ? 'display: none;' : '' ); ?>">
				<?php
				$cnt ++;

				// get unused widgets
				$widgets = array_keys( $unused );

				foreach ( $used as $widget ) {
					$idx = array_search( $widget, $widgets );
					if ( false !== $idx ) {
						$unused[ $widgets[ $idx ] ] = '';
					}
				}

				foreach ( $unused as $key => $value ) :
					if ( $value ) :
						?>
						<li>
							<label class="checkbox checkbox-inline">
								<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" <?php checked( '' === $prev_widgets || in_array( $key, $prev_widgets ) ); ?> class="widget">
								<?php echo esc_html( 'Molla ' . ucwords( $value ) ); ?>
							</label>
						</li>
						<?php
					endif;
				endforeach;
				?>

				<li>
					<label class="checkbox checkbox-inline">
						<input type="checkbox" name="spacing" <?php checked( '' === $prev_widgets || in_array( 'spacing', $prev_widgets ) ); ?> class="widget"> Unused Spacing Classes
					</label>
				</li>

				<li>
					<label class="checkbox checkbox-inline">
						<input type="checkbox" name="grid" <?php checked( '' === $prev_widgets || in_array( 'grid', $prev_widgets ) ); ?> class="widget"> Unused Grid Classes
					</label>
				</li>
			</ul>
		</div>
		<?php endforeach; ?>

		<p class="molla-setup-actions step">
			<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-outline"><?php esc_html_e( 'Skip this step', 'molla' ); ?></a>
			<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next" data-callback="remove_widgets"><?php esc_html_e( 'Compile & Continue', 'molla' ); ?></a>
			<?php wp_nonce_field( 'molla-setup-wizard' ); ?>
		</p>
	</form>
</div>
