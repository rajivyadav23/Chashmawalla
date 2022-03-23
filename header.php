<?php
if ( isset( $_POST['ajax_loadmore'] ) && $_POST['ajax_loadmore'] ) {
	return;
}
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action( 'molla_body_after_start' ); ?>
<?php if ( molla_option( 'loading_overlay' ) ) : ?>
	<div class="loading-overlay">
		<div class="bounce-loader">
			<div class="bounce1"></div>
			<div class="bounce2"></div>
			<div class="bounce3"></div>
		</div>
	</div>
<?php endif; ?>

	<div class="page-wrapper">
		<?php
		global $post;
		$header_show = '';
		if ( $post ) {
			$post_id     = molla_get_page_layout( $post );
			$header_show = get_post_meta( $post_id, 'header_show' );
		}
		if ( ! is_array( $header_show ) || ( ! $header_show || 'show' == $header_show[0] ) ) {
			get_template_part( 'template-parts/header/header' );
		}
		?>
		<div class="main">
			<?php do_action( 'page_content_before' ); ?>
			<div class="page-content<?php echo esc_attr( ' ' . apply_filters( 'molla_page_content_class', '' ) ); ?>"<?php echo esc_attr( apply_filters( 'molla_page_content_attrs', '' ) ); ?>>
				<?php do_action( 'page_container_before', 'top' ); ?>
