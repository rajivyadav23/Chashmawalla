<aside class="content-left support">
</aside>
<div class="content-right">
	<h2><?php esc_html_e( 'Help and Support', 'molla' ); ?></h2>
	<p class="lead"><?php esc_html_e( 'This theme comes with 6 months item support from purchase date (with the option to extend this period). This license allows you to use this theme on a single website. Please purchase an additional license to use this theme on another website.', 'molla' ); ?></p>
	<br>
	<div class="row">
		<div class="col-1">
			<div class="support-info-box">
				<h4 class="success system-status"><i class="status yes fas fa-check"></i> 
				<?php
					echo wp_kses(
						__( 'Item Support <strong class="success">DOES</strong> Include:', 'molla' ),
						array(
							'strong' => array(
								'class' => array(
								),
							),
						)
					);
				?>
				</h4>

				<ul class="list">
					<li><?php esc_html_e( 'Availability of the author to answer questions', 'molla' ); ?></li>
					<li><?php esc_html_e( 'Answering technical questions about item features', 'molla' ); ?></li>
					<li><?php esc_html_e( 'Assistance with reported bugs and issues', 'molla' ); ?></li>
					<li><?php esc_html_e( 'Help with bundled 3rd party plugins', 'molla' ); ?></li>
				</ul>
			</div>
		</div>
		<div class="col-1">
			<div class="support-info-box">
				<h4 class="error system-status"><i class="status no fas fa-ban"></i> 
				<?php
					echo wp_kses(
						__( 'Item Support <strong class="error">DOES NOT</strong> Include:', 'molla' ),
						array(
							'strong' => array(
								'class' => array(
								),
							),
						)
					);
				?>
				</h4>
				<ul class="list">
					<li>
					<?php
						printf(
							wp_kses(
								__( 'Customization services (this is available through <a href="%s">%s</a>)', 'molla' ),
								array(
									'a' => array(
										'href' => array(
										),
									),
								)
							),
							'mailto:ptheme.customize@gmail.com',
							'ptheme.customize@gmail.com'
						);
					?>
					</li>
					<li>
					<?php
						printf(
							wp_kses(
								__( 'Installation services (this is available through <a href="%s">%s</a>)', 'molla' ),
								array(
									'a' => array(
										'href' => array(
										),
									),
								)
							),
							'mailto:ptheme.customize@gmail.com',
							'ptheme.customize@gmail.com'
						);
					?>
					</li>
					<li><?php esc_html_e( 'Help and Support for non-bundled 3rd party plugins (i.e. plugins you install yourself later on)', 'molla' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
	<br>
	<p class="info-qt light-info">
	<?php
		printf(
			wp_kses(
				__( 'More details about item support can be found in the ThemeForest <a href="%s" target="_blank">Item Support Policy</a>.', 'molla' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			'http://themeforest.net/page/item_support_policy'
		);
	?></p>
	<p class="molla-setup-actions step">
		<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-primary button-next button-large"><?php esc_html_e( 'Continue', 'molla' ); ?></a>
		<?php wp_nonce_field( 'molla-setup-wizard' ); ?>
	</p>
</div>
<?php
