<?php
$nav_items = molla_option( 'navigator_items' );
?>
<div class="customizer-nav">
	<h3><?php esc_html_e( 'Navigator', 'molla' ); ?><a href="#" class="navigator-toggle"><i class="fas fa-chevron-left"></i></a></h3>
	<div class="customizer-nav-content">
		<ul class="customizer-nav-items">
	<?php foreach ( $nav_items as $section => $label ) : ?>
			<li>
				<a href="#" data-target="<?php echo esc_attr( $section ); ?>" data-type="<?php echo esc_attr( $label[1] ); ?>" class="customizer-nav-item"><?php echo esc_html( $label[0] ); ?></a>
				<a href="#" class="customizer-nav-remove"><i class="fas fa-trash"></i></a>
			</li>
	<?php endforeach; ?>
		</ul>
	</div>
</div>
