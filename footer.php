<?php

if ( isset( $_POST['ajax_loadmore'] ) && $_POST['ajax_loadmore'] ) {
	return;
}

do_action( 'page_container_after', 'bottom' );

$footer_tooltip = molla_option( 'footer_tooltip_label' );
if ( ! $footer_tooltip ) {
	$footer_tooltip = esc_html__( 'Footer', 'molla' );
}

?>
			</div>
			<?php get_template_part( 'template-parts/partials/scroll', 'top' ); ?>
		</div>
		<?php
		$footer_show = array();
		global $post, $molla_settings;
		if ( $post ) {
			$post_id     = molla_get_page_layout( $post );
			$footer_show = get_post_meta( $post_id, 'footer_show' );
		}
		if ( is_array( $footer_show ) && ( ! $footer_show || 'show' == $footer_show[0] ) ) :
			if ( 'footer' == get_post_type() ) {
				echo '<footer class="custom-footer full-inner footer-' . get_the_ID() . '" id="footer">';
				the_content();
				echo '</footer>';
			} elseif ( isset( $molla_settings['footer']['builder'] ) && function_exists( 'molla_print_custom_post' ) ) {
				echo '<footer class="custom-footer full-inner footer-' . $molla_settings['footer']['builder'] . '" id="footer">';
				molla_print_custom_post( 'footer', $molla_settings['footer']['builder'] );
				echo '</footer>';
			} else {
				$footer_class  = 'footer';
				$footer_class .= molla_option( 'footer_top_divider_active' ) || molla_option( 'footer_main_divider_active' ) || molla_option( 'footer_bottom_divider_active' ) ? ' divider-active' : '';
				$footer_class  = apply_filters( 'molla_footer_classes', $footer_class );
				?>
			<footer class="<?php echo esc_attr( $footer_class ); ?>" data-section-tooltip="<?php echo esc_html( $footer_tooltip ); ?>">
				<?php
				$footer_block_name = molla_option( 'footer_block_name' );
				if ( $footer_block_name ) {
					if ( function_exists( 'molla_print_custom_post' ) ) {
						molla_print_custom_post( 'block', $footer_block_name );
					} else {
						echo '<strong>Plugin not installed.</strong>';
					}
				} else {

					$elems = array(
						'top',
						'main',
						'bottom',
					);

					$footer_width = $molla_settings['footer']['width'];

					foreach ( $elems as $elem ) {
						molla_get_template_part( 'template-parts/footer/footer', $elem, array( 'width' => $footer_width ) );
					}
				}
				?>
			</footer>
				<?php
			}
		endif;
		?>

	</div>
	<?php
	get_template_part( 'template-parts/header/header', 'mobile' );

	do_action( 'molla_body_before_end' );

	wp_footer();
	?>
</body>
</html>
