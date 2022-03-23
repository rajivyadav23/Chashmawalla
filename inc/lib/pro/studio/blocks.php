<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( molla_ajax() ) {
	$is_ajax = true;
} else {
	$is_ajax = false;
}
?>

<div class="blocks-wrapper mfp-hide">
	<div class="category-list">
		<h2><img src="<?php echo MOLLA_URI; ?>/assets/images/logo-studio.png" alt="<?php esc_attr_e( 'Molla Studio', 'molla' ); ?>"></h2>
		<p><?php esc_html_e( 'Quickly get a project started with any of our examples:', 'molla' ); ?></p>
		<ul>
			<li style="display: none;"><a href="#" data-filter-by="0"<?php echo isset( $total_pages ) ? ' data-total-page="' . intval( $total_pages ) . '"' : ''; ?>><?php esc_html_e( 'Latest', 'molla' ); ?></a></li>
		<?php foreach ( $block_categories as $category ) : ?>
			<?php if ( $category['count'] > 0 ) : ?>
				<li><a href="#" data-filter-by="<?php echo (int) $category['id']; ?>" data-total-page="<?php echo (int) ( $category['total'] ); ?>"<?php echo (int) $category['id'] == (int) $default_category_id ? ' class="active"' : ''; ?>><?php echo esc_html( $category['title'] ); ?><span><?php echo (int) $category['count']; ?></span></a></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	</div>
	<div class="blocks-section">
		<div class="demo-filter">
			<?php
			if ( ! class_exists( 'Molla_Setup_Wizard' ) ) {
				require_once MOLLA_ADMIN . '/wizard/setup_wizard/setup_wizard.php';
			}
				$instance = Molla_Setup_Wizard::get_instance();
				$filters1 = $instance->molla_demo_filters();
				$filters2 = $instance->molla_demo_types();
			?>
			<h3><?php esc_html_e( 'Filter by Demos', 'molla' ); ?></h3>
			<select class="filter1">
				<?php foreach ( $filters1 as $name => $value ) : ?>
					<option value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
			<select class="filter2">
				<option value=""><?php esc_html_e( 'Show All', 'molla' ); ?></option>
				<?php foreach ( $filters2 as $name => $value ) : ?>
					<?php
					if ( ( ! empty( $value['plugins'] ) && 'v' == $page_type && in_array( 'js_composer', $value['plugins'] ) ) ||
						( 'e' == $page_type && false !== strpos( $value['filter'], 'elementor' ) ) ||
						( 'g' == $page_type && false !== strpos( $value['filter'], 'gutenberg' ) ) ) :
						?>
						<option value="<?php echo esc_attr( $name ); ?>" data-filter="<?php echo esc_attr( $value['filter'] ); ?>"><?php echo esc_html( $value['alt'] ); ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
			<button class="btn btn-primary" disabled="disabled"><?php esc_html_e( 'Submit', 'molla' ); ?></button>
			<a href="#" class="demo-filter-trigger"><i class="fas fa-filter"></i> <?php esc_html_e( 'Filters', 'molla' ); ?></a>
		</div>
		<div class="blocks-list">
		<?php foreach ( $blocks as $block ) : ?>
			<div class="block" data-template_name="<?php echo function_exists( 'vc_slugify' ) ? esc_attr( vc_slugify( $block['t'] ) ) : sanitize_title( $block['t'] ); ?>">
				<img <?php echo ! $is_ajax ? 'data-original' : 'src'; ?>="<?php echo esc_url( isset( $block['img'] ) ? $block['img'] : 'https://d-themes.com/wordpress/molla/dummy/images/studio/' . ( (int) $block['block_id'] ) . '.jpg' ); ?>" alt="<?php echo esc_attr( $block['t'] ); ?>"<?php echo isset( $block['w'] ) && $block['w'] ? ' width="' . intval( $block['w'] ) . '"' : '', isset( $block['h'] ) && $block['h'] ? ' height="' . intval( $block['h'] ) . '"' : ''; ?>>
				<div class="block-actions">
					<a href="<?php echo esc_url( $block['u'] ); ?>" class="btn btn-dark" target="_blank"><i class="fas fa-search-plus"></i><?php esc_html_e( 'Preview', 'molla' ); ?></a>
					<?php // if ( ( function_exists( 'Molla' ) && Molla()->is_registered() || get_option( 'molla_registered' ) ) ) : ?>
						<button class="btn btn-primary import" data-id="<?php echo esc_attr( $block['block_id'] ); ?>"><i class="fas fa-download"></i><?php esc_html_e( 'Import', 'molla' ); ?></button>
					<?php // endif; ?>
				</div>
				<h4 class="block-title"><?php echo esc_html( $block['t'] ); ?></h4>
			</div>
		<?php endforeach; ?>
		</div>
		<i class="molla-loading"></i>
	</div>
	<i class="molla-loading"></i>
</div>