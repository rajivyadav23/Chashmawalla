<?php

$search_content_type  = molla_option( 'search_content_type' );
$live_search          = molla_option( 'live_search' );
$search_by_categories = molla_option( 'search_by_categories' );
$search_placeholder   = molla_option( 'search_placeholder' );
$is_header            = '';
if ( isset( $args['aria_label'] ) ) {
	$is_header = $args['aria_label'];
	if ( is_array( $args['aria_label'] ) ) {
		$is_header            = 'header';
		$search_content_type  = $args['aria_label']['search_content_type'];
		$search_by_categories = $args['aria_label']['search_by_categories'];
		$search_placeholder   = $args['aria_label']['search_placeholder'];
	}
}

?>
<?php if ( $is_header ) : ?>
	<a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
<?php endif; ?>
	<form action="<?php echo esc_url( home_url() ); ?>/" method="get" class="searchform<?php echo esc_attr( $search_content_type && ( 'post' === $search_content_type || 'product' === $search_content_type ) ) . ( 'mobile' == $is_header ? ' mobile-search' : '' ); ?>">
		<div class="search-wrapper search-wrapper-wide">
			<input type="hidden" name="post_type" value="<?php echo esc_attr( $search_content_type ); ?>"/>
			<?php
			if ( isset( $is_header ) && 'header' == $is_header && $search_by_categories ) :
				$args = array(
					'show_option_all' => esc_html__( 'All Categories', 'molla' ),
					'hierarchical'    => 1,
					'class'           => 'cat',
					'echo'            => 1,
					'value_field'     => 'slug',
					'selected'        => 1,
				);
				if ( 'product' === $search_content_type && class_exists( 'WooCommerce' ) ) {
					$args['taxonomy'] = 'product_cat';
					$args['name']     = 'product_cat';
				} elseif ( 'all' === $search_content_type && class_exists( 'WooCommerce' ) ) {
					$args['taxonomy'] = array( 'category', 'product_cat' );
				}
				$args['depth'] = 1;
				?>
					<div class="select-custom">
					<?php wp_dropdown_categories( $args ); ?>
					</div>
				<?php
			endif;
			if ( $live_search ) :
				?>
			<div class="live-search">
				<?php
			endif;
			?>
			<input type="search" class="form-control" name="s" value="<?php echo get_search_query(); ?>"  placeholder="<?php echo esc_attr( $search_placeholder ); ?>" required="" autocomplete="off" >
			<?php if ( $live_search ) : ?>
			<div class="live-search-list"></div>
			</div>
			<?php endif; ?>
			<button class="btn<?php echo esc_attr( 'header' == $is_header && molla_option( 'header_search_btn_type' ) ? ' btn-icon' : ' btn-primary' ); ?>" type="submit"><i class="icon-search"></i></button>
		</div><!-- End .search-wrapper -->
	</form>
