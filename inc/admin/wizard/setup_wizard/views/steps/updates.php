<aside class="content-left update">
</aside>
<div class="content-right">
	<h2><?php esc_html_e( 'Activate Molla Theme', 'molla' ); ?></h2>
	<?php if ( Molla()->is_envato_hosted() ) : ?>
		<p class="lead" style="margin-bottom:40px">
		<?php esc_html_e( 'You are using Envato Hosted.', 'molla' ); ?>
		</p>
		<p class="molla-setup-actions step">
			<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-primary button-large button-next"><?php esc_html_e( 'Continue', 'molla' ); ?></a>
		</p>
	<?php else : ?>
		<p class="lead"><?php esc_html_e( 'Enter Your Purchase Code to activate Molla theme. If you don’t have the purchase code right now you can skip this step.', 'molla' ); ?></p>
			<?php
			$output = '';

			$errors = get_option( 'molla_register_error_msg' );
			delete_option( 'molla_register_error_msg' );
			$purchase_code = Molla()->get_purchase_code_asterisk();

			if ( ! empty( $errors ) ) {
				echo '<div class="notice-error notice-alt notice-large">' . esc_html( $errors ) . '</div>';
			}

			if ( ! empty( $purchase_code ) ) {
				if ( ! empty( $errors ) ) {
					echo '<div class="notice-warning notice-alt notice-large">' . esc_html__( 'Purchase code not updated. We will keep the existing one.', 'molla' ) . '</div>';
				} else {
					/* translators: $1 and $2 opening and closing strong tags respectively */
					echo '<div class="notice-success notice-alt notice-large">' . sprintf( esc_html__( 'Your %1$spurchase code is valid%2$s. Thank you! Enjoy Molla Theme and automatic updates.', 'molla' ), '<strong>', '</strong>' ) . '</div>';
				}
			}

			if ( ! Molla()->is_registered() ) {
				echo '<form action="" method="post">';

				echo '<input type="hidden" name="molla_registration" /><input type="hidden" name="action" value="register" />' .
						'<input type="text" id="molla_purchase_code" name="code" value="' . esc_attr( $purchase_code ) . '" placeholder="' . esc_html__( 'Purchase code', 'molla' ) . '" style="width:100%;" required/><br/><br/>';
				?>
					<p class="info-qt" style="margin-bottom: 0;"><?php esc_html_e( 'Where can I find my purchase code?', 'molla' ); ?></p>
					<ul>
						<?php /* translators: $1: opening A tag which has link to the Themeforest downloads page $2: closing A tag */ ?>
						<li><span class="step-id">1</span><?php printf( esc_html__( 'Please go to %1$sThemeForest.net/downloads%2$s', 'molla' ), '<a target="_blank" href="https://themeforest.net/downloads">', '</a>' ); ?></li>
						<?php /* translators: $1 and $2 opening and closing strong tags respectively */ ?>
						<li><span class="step-id">2</span><?php printf( esc_html__( 'Click the %1$sDownload%2$s button in Molla row', 'molla' ), '<strong>', '</strong>' ); ?></li>
						<?php /* translators: $1 and $2 opening and closing strong tags respectively */ ?>
						<li><span class="step-id">3</span><?php printf( esc_html__( 'Select %1$sLicense Certificate & Purchase code%2$s', 'molla' ), '<strong>', '</strong>' ); ?></li>
						<?php /* translators: $1 and $2 opening and closing strong tags respectively */ ?>
						<li><span class="step-id">4</span><?php printf( esc_html__( 'Copy %1$sItem Purchase Code%2$s', 'molla' ), '<strong>', '</strong>' ); ?></li>
					</ul>
				<?php
				echo '<p class="molla-setup-actions step">' .
						'<a href="' . esc_url( $this->get_next_step_link() ) . '" class="button button-large button-outline button-next button-icon-hide">' . esc_html__( 'Skip this step', 'molla' ) . '</a>' .
						'<button type="submit" class="button button-large button-next button-primary">' . esc_attr__( 'Activate', 'molla' ) . '</button>' .
						'</p>';
			} else {
				echo '<form action="" method="post"><input type="hidden" name="molla_registration" /><input type="hidden" name="action" value="unregister" />' .
						'<input type="text" id="molla_purchase_code" name="code" value="' . esc_attr( $purchase_code ) . '" placeholder="' . esc_html__( 'Purchase code', 'molla' ) . '" style="width:100%;"/><br/><br/>' .
						'<p class="molla-setup-actions step">' . '<input type="submit" class="button button-large button-next button-outline" value="' . esc_attr__( 'Deactivate', 'molla' ) . '" style="margin-right: 0.5em;" />' . '<a href="' . esc_url( $this->get_next_step_link() ) . '" class="button button-primary button-large button-next" style="margin-right: 0;">' . esc_html__( 'Next Step', 'molla' ) . '</a>' .
						'</p>';
			}
			wp_nonce_field( 'molla-setup-wizard' );
			echo '</form>';
	endif;
	?>
</div>
<?php

