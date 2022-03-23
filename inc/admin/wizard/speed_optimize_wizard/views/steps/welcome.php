<aside class="content-left welcome">
</aside>
<div class="content-right">
	<?php
	if ( get_option( 'molla_speed_complete', false ) ) {
		?>
		<?php /* translators: %s: Theme name */ ?>
		<h2><?php printf( esc_html__( 'Welcome to the speed optimize wizard for %s.', 'molla' ), wp_get_theme() ); ?></h2>
		<p class="lead success"><?php esc_html_e( 'It looks like you have already optimized your site.', 'molla' ); ?></p>

		<p class="molla-setup-actions step">
			<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-next button-large"><?php esc_html_e( 'Run Speed Optimize Wizard Again', 'molla' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=molla' ) ); ?>" class="button button-large"><?php esc_html_e( 'Exit to Molla Panel', 'molla' ); ?></a>
		</p>
		<?php
	} else {
		?>
		<?php /* translators: %s: Theme name */ ?>
		<h2><?php printf( esc_html__( 'Welcome to the speed optimize wizard for %s.', 'molla' ), wp_get_theme() ); ?></h2>
		<?php /* translators: %s: Theme name */ ?>
		<p class="lead"><?php printf( esc_html__( 'This Speed Optimize Wizard is introduced to optimize all resources that are unnecessary for your site. Every step has enough description about how it works. Some options may produce some conflicts if your site is still in development progress, so we advise you to enable all options once site development is completed.', 'molla' ), wp_get_theme() ); ?></p>
		<p><?php echo '<span class="info-qt">' . esc_html__( 'No time right now? ', 'molla' ) . '</span>' . __( "If you don't want to go through the wizard, you can skip and return to the WordPress dashboard. Come back anytime you want!", 'molla' ); ?></p>
		<p class="molla-setup-actions step">
			<a href="<?php echo esc_url( wp_get_referer() && ! strpos( wp_get_referer(), 'update.php' ) ? wp_get_referer() : admin_url( '' ) ); ?>" class="button button-large button-outline"><?php esc_html_e( 'Not right now', 'molla' ); ?></a>
			<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next"><?php esc_html_e( "Let's Go", 'molla' ); ?></a>
		</p>
		<?php
	}
	?>
</div>
<?php
