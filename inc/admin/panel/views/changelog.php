<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
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
			printf( '<li><a href="%s" class="nav-tab">%s</a></li>', esc_url( admin_url( 'admin.php?page=molla' ) ), esc_html__( 'Theme License', 'molla' ) );
			printf( '<li class="active"><a href="#" class="nav-tab">%s</a></li>', esc_html__( 'Change Log', 'molla' ) );
			printf( '<li><a href="%s" class="nav-tab">%s</a></li>', esc_url( admin_url( 'customize.php' ) ), esc_html__( 'Theme Options', 'molla' ) );
			printf( '<li><a href="%s" class="nav-tab">%s</a></li>', esc_url( admin_url( 'admin.php?page=molla-setup-wizard' ) ), esc_html__( 'Setup Wizard', 'molla' ) );
			printf( '<li><a href="%s" class="nav-tab">%s</a></li>', esc_url( admin_url( 'admin.php?page=molla-speed-optimize-wizard' ) ), esc_html__( 'Speed Optimize Wizard', 'molla' ) );

			if ( defined( 'MOLLA_DEMO_EXPORT_VERSION' ) ) :
				printf( '<li><a href="%s" class="nav-tab">%s</a></li>', esc_url( admin_url( 'admin.php?page=molla-demos' ) ), esc_html__( 'Export Demos', 'molla' ) );
			endif;
			?>
			</ul>
		</div>
		<div class="molla-admin-panel-content">
		<?php
		require_once MOLLA_PLUGINS . '/importer/importer-api.php';
		$importer_api = new Molla_Importer_API();
		$result       = $importer_api->get_response( 'changelog', array(), 'text' );
		if ( ! is_wp_error( $result ) ) {
			echo '<pre>' . molla_strip_script_tags( $result ) . '</pre>';
		}
		?>
		</div>
	</div>
</div>
