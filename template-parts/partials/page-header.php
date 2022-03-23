<?php
global $molla_settings;
$param = $molla_settings['page_header'];

if ( count( $param ) && ( $param['title'] || $param['subtitle'] || 'breadcrumb' == $param['content'] ) ) :
	?>
<div class="<?php echo esc_attr( $param['classes'] ); ?>" <?php echo ( ! $param['internal'] ? '' : ( 'style="' . esc_attr( $param['internal'] ) . '"' ) ) . $param['data_page_header']; ?>>
	<div class="<?php echo esc_attr( $param['page_width'] ); ?>">
		<h2 class="page-title"><?php echo sanitize_text_field( $param['title'] ); ?></h2>
		<?php if ( 'subtitle' == $param['content'] ) : ?>
			<?php if ( $param['subtitle'] ) : ?>
				<h3 class="page-subtitle"><?php echo sanitize_text_field( $param['subtitle'] ); ?></h3>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( 'breadcrumb' == $param['content'] ) : ?>
			<?php molla_woocommerce_breadcrumb(); ?>
		<?php endif; ?>
	</div>
</div>
	<?php
endif;
