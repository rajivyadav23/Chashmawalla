<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package molla
 */

if ( isset( $_POST['ajax_loadmore'] ) && $_POST['ajax_loadmore'] ) {
	return;
}

		global $molla_settings;
		$sidebar = $molla_settings['sidebar'];
if ( molla_is_elementor_preview() && 'sidebar' == get_post_type() ) {
	the_content();
} elseif ( isset( $molla_settings['sidebar']['builder'] ) && function_exists( 'molla_print_custom_post' ) ) {
	echo '<div class="sidebar custom-sidebar sidebar-' . $molla_settings['sidebar']['builder'] . esc_attr( molla_sidebar_classes() ) . '">';
	molla_print_custom_post( 'sidebar', $molla_settings['sidebar']['builder'] );
	echo '</div>';
} else {
	?>
	<div class="sidebar<?php echo esc_attr( molla_sidebar_classes() ) . ' ' . $sidebar['name']; ?>">

		<?php do_action( 'before_sidebar', $sidebar['name'] ); ?>

		<div class="sidebar-content<?php echo apply_filters( 'sidebar_content_classes', '' ); ?>">
		<?php
		do_action( 'before_dynamic_sidebar', $sidebar['name'] );
		if ( $sidebar['active'] ) {
			dynamic_sidebar( $sidebar['name'] );
		}
		do_action( 'after_dynamic_sidebar', $sidebar['name'] );
		?>
		</div>

		<?php do_action( 'after_sidebar', $sidebar['name'] ); ?>
	</div>
		<?php
}
