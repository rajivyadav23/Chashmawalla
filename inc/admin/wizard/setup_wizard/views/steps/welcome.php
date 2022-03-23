<aside class="content-left welcome">
</aside>
<div class="content-right">
<?php
if ( get_option( 'molla_setup_complete', false ) ) {
	?>
	<?php /* translators: %s: Theme name */ ?>
	<h2><?php printf( esc_html__( 'Welcome to the setup wizard for %s.', 'molla' ), wp_get_theme() ); ?></h2>
	<p class="lead success"><?php esc_html_e( 'It looks like you already have setup Molla.', 'molla' ); ?></p>

	<p class="molla-setup-actions step">
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=molla' ) ); ?>" class="button-outline button button-large button-prev"><?php esc_html_e( 'Exit to Molla Panel', 'molla' ); ?></a>
		<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next"><?php esc_html_e( 'Run Setup Wizard Again', 'molla' ); ?></a>
	</p>
	<?php
} else {
	?>
	<?php /* translators: %s: Theme name */ ?>
	<h2><?php printf( esc_html__( 'Welcome to the setup wizard for %s.', 'molla' ), wp_get_theme() ); ?></h2>
	<?php /* translators: %s: Theme name */ ?>
	<p class="lead"><?php printf( esc_html__( 'Thank you for choosing the %s theme. This quick setup wizard will help you configure your new website. This wizard will install the required WordPress plugins, demo content, logo, etc.', 'molla' ), wp_get_theme() ); ?></p>
	<p><?php echo '<span class="info-qt">' . esc_html__( 'No time right now?', 'molla' ) . '</span>' . __( " If you don't want to go through the wizard, you can skip and return to the WordPress dashboard. Come back anytime if you change your mind!", 'molla' ); ?></p>
	<p class="molla-setup-actions step">
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=molla' ) ); ?>" class="button-outline button button-large button-prev"><?php esc_html_e( 'Not right now', 'molla' ); ?></a>
		<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next"><?php esc_html_e( "Let's Go", 'molla' ); ?></a>
	</p>
	<?php
}
?>
</div>
<?php
