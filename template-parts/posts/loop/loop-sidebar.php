<?php
if ( isset( $sidebar ) && $sidebar ) :
	?>
		<aside class="col-lg-3<?php echo esc_attr( 'right' == $sidebar ? ' order-last' : '' ); ?>">
		<?php get_sidebar(); ?>
		</aside>
	<?php
endif;
