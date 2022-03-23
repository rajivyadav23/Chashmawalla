<div class="wrap molla-wrap molla-wizard molla-setup">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Setup Wizard', 'molla' ); ?></h2>
	<div class="molla-admin-panel">
		<div class="molla-admin-panel-header">
			<div class="panel-header-left">
				<h1><?php esc_html_e( 'Setup Wizard', 'molla' ) ?></h1>
				<p><?php esc_html_e( 'Molla setup wizard will help you quickly build new website.', 'molla' ) ?></p>
			</div>
			<div class="panel-header-right">
				<img class="logo" src="<?php echo MOLLA_URI; ?>/assets/images/logo_white.png" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" width="81" height="19" />
				<p class="theme-version"><?php echo esc_html__( 'version', 'molla' ) . ' ' . MOLLA_VERSION; ?></p>
			</div>
		</div>
		<div class="molla-admin-panel-menu">