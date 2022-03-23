<?php
update_option( 'molla_setup_complete', time() );
?>
<aside class="content-left ready">
</aside>
<div class="content-right">
	<h2><?php esc_html_e( 'Your Website is Ready!', 'molla' ); ?></h2>

	<p class="lead"><?php esc_html_e( 'Congratulations! The theme has been activated and your website is ready. Please go to your WordPress dashboard to make changes and modify the content for your needs.', 'molla' ); ?></p>

	<div class="molla-setup-next-steps">
		<div class="molla-setup-next-steps-first">
			<h4><?php esc_html_e( 'More Resources', 'molla' ); ?></h4>
			<ul style="margin-bottom:40px;">
				<li class="documentation"><a href="https://d-themes.com/wordpress/molla/documentation"><?php esc_html_e( 'Molla Documentation', 'molla' ); ?></a></li>
				<li class="woocommerce documentation"><a href="https://docs.woocommerce.com/document/woocommerce-101-video-series/"><?php esc_html_e( 'Learn how to use WooCommerce', 'molla' ); ?></a></li>
				<li class="howto" style="font-style: normal;"><a href="https://wordpress.org/support/"><?php esc_html_e( 'Learn how to use WordPress', 'molla' ); ?></a></li>
				<li class="rating"><a href="http://themeforest.net/downloads"><?php esc_html_e( 'Leave an Item Rating', 'molla' ); ?></a></li>
			</ul>
			<p class="info-qt light-info">
			<?php
				printf(
					wp_kses(
						__( 'Please come back and leave a <a href="%s" target="_blank">5-star rating</a> if you are happy with this theme. Thanks!', 'molla' ),
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
		<div class="molla-setup-actions">
			<a class="button button-large button-primary button-next" href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'View your new website!', 'molla' ); ?></a>
		</div>
	</div>
</div>