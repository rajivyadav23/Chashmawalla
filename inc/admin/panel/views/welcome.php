<?php

$theme = wp_get_theme();
if ( $theme->parent_theme ) {
	$template_dir = basename( get_template_directory() );
	$theme        = wp_get_theme( $template_dir );
}
?>
<div class="wrap molla-wrap">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Welcome to Molla', 'molla' ); ?></h2>
	<div class="molla-admin-panel">
		<div class="molla-admin-panel-header">
			<div class="panel-header-left">
				<h1><?php esc_html_e( 'Welcome to Molla', 'molla' ); ?></h1>
				<p><?php esc_html_e( 'Thank you for choosing Molla theme from ThemeForest. Please register your purchase and make sure that you have fulfilled all of the requirements.', 'molla' ); ?></p>
			</div>
			<div class="panel-header-right">
				<img class="logo" src="<?php echo MOLLA_URI; ?>/assets/images/logo_white.png" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" width="81" height="19" />
				<p class="theme-version"><?php echo esc_html__( 'version', 'molla' ) . ' ' . MOLLA_VERSION; ?></p>
			</div>
		</div>
		<div class="molla-admin-panel-menu">
			<ul class="molla-admin-panel-menu_list">
			<?php
			printf( '<li class="active"><a href="#" class="nav-tab">%s</a></li>', esc_html__( 'Theme License', 'molla' ) );
			printf( '<li><a href="%s" class="nav-tab">%s</a></li>', esc_url( admin_url( 'admin.php?page=molla-changelog' ) ), esc_html__( 'Change Log', 'molla' ) );
			printf( '<li><a href="%s" class="nav-tab">%s</a></li>', esc_url( admin_url( 'customize.php' ) ), esc_html__( 'Theme Options', 'molla' ) );
			printf( '<li><a href="%s" class="nav-tab">%s</a></li>', esc_url( admin_url( 'admin.php?page=molla-setup-wizard' ) ), esc_html__( 'Setup Wizard', 'molla' ) );
			printf( '<li><a href="%s" class="nav-tab">%s</a></li>', esc_url( admin_url( 'admin.php?page=molla-speed-optimize-wizard' ) ), esc_html__( 'Speed Optimize Wizard', 'molla' ) );

			if ( defined( 'MOLLA_DEMO_EXPORT_VERSION' ) ) :
				printf( '<li><a href="%s" class="nav-tab">%s</a></li>', esc_url( admin_url( 'admin.php?page=molla-demos' ) ), esc_html__( 'Export Demos', 'molla' ) );
			endif;
			?>
			</ul>
		</div>
		<div class="molla-admin-panel-content theme-license-content">
			<div class="row">
				<div class="welcome col-left">
					<div class="molla-section">
						<div class="molla-important-notice registration-form-container">
							<?php if ( Molla()->is_registered() ) : ?>
								<p class="about-description"><?php esc_html_e( 'Congratulations! Your product is registered now.', 'molla' ); ?></p>
							<?php else : ?>
								<p class="about-description"><?php echo esc_html__( 'Molla is ready to be used. Please register your product to import Demo Contents, to install Premium Plugins and get automatic updates.', 'molla' ); ?></p>
							<?php endif; ?>
							<div class="molla-registration-form">
								<?php if ( ! Molla()->is_registered() ) : ?>
									<p class="about-description"><?php esc_html_e( 'Please enter your Purchase Code to complete registration.', 'molla' ); ?></p>
								<?php endif; ?>
								<form id="molla_registration" method="post">
									<?php
									$disable_field = '';
									$error_message = get_option( 'molla_register_error_msg' );
									update_option( 'molla_register_error_msg', '' );
									$purchase_code = Molla()->get_purchase_code_asterisk();
									?>
									<?php if ( $purchase_code && ! empty( $purchase_code ) ) : ?>
										<?php
										if ( Molla()->is_registered() ) :
											$disable_field = ' disabled=true';
											?>
											<span class="dashicons dashicons-yes molla-code-icon"></span>
										<?php else : ?>
											<span class="dashicons dashicons-no molla-code-icon"></span>
										<?php endif; ?>
									<?php else : ?>
										<span class="dashicons dashicons-admin-network molla-code-icon"></span>
									<?php endif; ?>
									<input type="hidden" name="molla_registration" />
									<?php if ( Molla()->is_envato_hosted() ) : ?>
									<p class="confirm unregister">
										You are using Envato Hosted, this subscription code can not be deregistered.
									</p>
									<?php else : ?>
										<input type="text" id="molla_purchase_code" name="code" class="regular-text" value="<?php echo esc_attr( $purchase_code ); ?>" placeholder="<?php esc_attr_e( 'Purchase Code', 'molla' ); ?>" <?php echo molla_filter_output( $disable_field ); ?> />
										<?php if ( Molla()->is_registered() ) : ?>
											<input type="hidden" name="action" value="unregister" />
											<?php submit_button( esc_html__( 'Deactivate', 'molla' ), array( 'button-danger', 'large', 'molla-large-button' ), '', true ); ?>
										<?php else : ?>
											<input type="hidden" name="action" value="register" />
											<?php submit_button( esc_html__( 'Active', 'molla' ), array( 'primary', 'large', 'molla-large-button' ), '', true ); ?>
										<?php endif; ?>
									<?php endif; ?>
									<?php wp_nonce_field( 'molla-setup-wizard' ); ?>
								</form>
								<?php if ( $error_message ) : ?>
									<p class="molla-ui-notice notice-error"><?php echo molla_strip_script_tags( $error_message ); ?></p>
								<?php endif; ?>

								<p class="info-qt"><?php esc_html_e( 'Where can I find my purchase code?', 'molla' ); ?></p>
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
							</div>
						</div>
						<p class="about-description">
							<?php /* translators: $1: opening A tag which has link to the Molla documentation $2: closing A tag */ ?>
							<?php printf( esc_html__( 'Before you get started, please be sure to always check out %1$sthis documentation%2$s. We outline all kinds of good information, and provide you with all the details you need to use Molla.', 'molla' ), '<a href="https://d-themes.com/wordpress/molla/documentation" target="_blank">', '</a>' ); ?>
						</p>
						<p class="about-description">
							<?php /* translators: $1: opening A tag which has link to the Molla support $2: closing A tag */ ?>
							<?php printf( esc_html__( 'If you are unable to find your answer in our documentation, we encourage you to contact us through %1$ssupport page%2$s with your site CPanel (or FTP) and WordPress admin details. We are very happy to help you and you will get reply from us more faster than you expected.', 'molla' ), '<a href="https://d-themes.com/wordpress/molla/support" target="_blank">', '</a>' ); ?>
						</p>
					</div>
				</div>
				<div class="system-status col-right">
					<h2 class="about-heading"><?php esc_html_e( 'System Status', 'molla' ); ?></h2>
					<?php require_once MOLLA_ADMIN . '/panel/views/mini-status.php'; ?>
				</div>
			</div>
		</div>
	</div>
</div>
