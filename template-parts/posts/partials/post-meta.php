<div class="entry-meta">
	<?php
	if ( in_array( 'author', $showing ) ) {
		molla_get_template_part( 'template-parts/posts/partials/post', 'meta-author', array( 'is_widget' => isset( $is_widget ) ) );
	}
	if ( in_array( 'date', $showing ) ) {
		molla_get_template_part( 'template-parts/posts/partials/post', 'meta-date', array( 'is_widget' => isset( $is_widget ) ) );
	}
	if ( in_array( 'comment', $showing ) ) {
		molla_get_template_part( 'template-parts/posts/partials/post', 'meta-comment', array( 'is_widget' => isset( $is_widget ) ) );
	}
	?>
</div>
