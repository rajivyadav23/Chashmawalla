<?php
update_option( 'molla_setup_complete', time() );
?>
<aside class="content-left welcome">
</aside>
<div class="content-right">
	<h2><?php esc_html_e( 'Your Website is now optimized much better than before!', 'molla' ); ?></h2>

	<p class="lead success"><?php esc_html_e( 'Congratulations! The Site is now much faster, better and full optimized. Please visit your new site to notice how its performance changed.', 'molla' ); ?></p>

	<div class="molla-setup-next-steps">
		<div class="molla-setup-next-steps-first">
			<h4><?php esc_html_e( 'More Resources', 'molla' ); ?></h4>
			<ul>
				<li class="documentation"><a href="https://d-themes.com/wordpress/molla/documentation"><?php esc_html_e( 'Molla Documentation', 'molla' ); ?></a></li>
				<li class="rating"><a href="http://themeforest.net/downloads"><?php esc_html_e( 'Leave a Rating', 'molla' ); ?></a></li>
			</ul>
			<p class="info-qt light-info" style="margin-top: 50px;">
			<?php
				printf(
					wp_kses(
						__( 'Please leave a <a href="%s" target="_blank">5-star rating</a> if you are satisfied with this theme. Thanks!', 'molla' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						)
					),
					'http://themeforest.net/downloads'
				);
			?>
			</p>
		</div>
		<div class="molla-setup-actions molla-setup-next-steps-last">
			<ul>
				<li class="setup-product"><a class="button button-large button-primary button-next" href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'View your new website!', 'molla' ); ?></a></li>
			</ul>
		</div>
	</div>
</div>
