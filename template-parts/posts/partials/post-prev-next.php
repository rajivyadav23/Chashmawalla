<nav class="pager-nav blog-pager" aria-label="Page navigation">
	<div class="pager-link pager-link-prev">
	<?php previous_post_link( '%link', esc_html__( 'Previous Post', 'molla' ) . '<span class="pager-link-title">%title</span>' ); ?>
	</div>
	<div class="pager-link pager-link-next">
	<?php next_post_link( '%link', esc_html__( 'Next Post', 'molla' ) . '<span class="pager-link-title">%title</span>' ); ?>
	</div>
</nav>
