<?php
// get all spacing and grid classes used in this page

$result         = array(
	'margin'  => array(
		''   => array(),
		'xl' => array(),
		'lg' => array(),
		'md' => array(),
		'sm' => array(),
	),
	'spacing' => array( 20 ), // 20 is default spacing value of products, categories, blog widgets.
	'cols'    => array( // for bootstrap grid classes like col-md-*, col-*, ...
		''    => array( 6, 12, 'auto' ),
		'xs'  => array( '' ),
		'sm'  => array( '', 4, 6 ),
		'md'  => array( '', 4, 6, 8, intval( molla_option( 'single_product_image_wrap_col' ) ), 12 - intval( molla_option( 'single_product_image_wrap_col' ) ), 12, 'auto' ),
		'lg'  => array( '', 1, 3, 4, 6, 7, 8, 11, intval( molla_option( 'single_product_image_wrap_col' ) ), 12 - intval( molla_option( 'single_product_image_wrap_col' ) ) ),
		'xl'  => array( '' ),
		'xxl' => array( '' ),
	),
	'grid'    => array( // for grid classes like c-xs-*, c-*, ...
		'xs'  => array(),
		'sm'  => array(),
		'md'  => array(),
		'lg'  => array(),
		'xl'  => array(),
		'xxl' => array(),
	),
	'x_pos'   => array(), // for only gutenberg banner content position
	'y_pos'   => array(), // for only gutenberg banner content position
);
$breakpoints    = array(
	''    => '',
	'xs'  => '@media (min-width: 320px) { ',
	'sm'  => '@media (min-width: 576px) { ',
	'md'  => '@media (min-width: 768px) { ',
	'lg'  => '@media (min-width: 992px) { ',
	'xl'  => '@media (min-width: 1200px) { ',
	'xxl' => '@media (min-width: 1600px) { ',
);
$block_ids      = $this->_used_blocks( $id );
$block_ids[]    = $id;
$elements_data  = '';
$gutenberg_data = '';
foreach ( $block_ids as $block_id ) {
	if ( defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $block_id, '_elementor_edit_mode', true ) ) {
		$elements_data = json_decode( get_post_meta( $block_id, '_elementor_data', true ), true );
		$result        = $this->_get_used_space_grid( $elements_data, $result, 'elementor' );
		$result        = $this->_get_used_space_grid( $elements_data, $result, 'elementor' );
	} else {
		$gutenberg_data = get_post( $block_id )->post_content;
		preg_match_all( '/<!-- wp:.*.-->/', $gutenberg_data, $matches, PREG_SET_ORDER );
		$widgets = array();
		foreach ( $matches as $match ) {
			$widgets[] = str_replace( array( '<!-- ', ' /-->', ' -->' ), '', $match );
		}
		$result = $this->_get_used_space_grid( $widgets, $result, 'gutenberg' );
	}
}
?>
@charset "UTF-8";

/*-------------------- Directions --------------------*/

@import 'config/directional';

/*-------------------- Main CSS File --------------------*/
@import 'mixins/clearfix';
@import 'mixins/breakpoints';
@import 'mixins/lazy';
@import 'mixins/buttons';

@import 'config/variables';

/*-------------------- Grid Classes --------------------*/
<?php if ( in_array( 'grid', $unused ) ) : ?>
[class^='col-'], [class*=' col-'] {
	position: relative;
	width: 100%;
}
	<?php
	foreach ( array( 'footer_top_cols', 'footer_main_cols' ) as $key ) {
		$cols = explode( '+', trim( str_replace( ' ', '', molla_option( $key ) ) ) );
		if ( 4 >= count( $cols ) ) {
			foreach ( $cols as $width ) {
				if ( ! in_array( $width, $result['cols']['lg'] ) ) {
					$result['cols']['lg'][] = $width;
				}
			}
		} else {
			foreach ( $cols as $width ) {
				if ( ! in_array( $width, $result['cols']['xl'] ) ) {
					$result['cols']['xl'][] = $width;
				}
			}
		}
	}
	foreach ( $result['cols'] as $break => $col ) {
		if ( ! empty( $col ) ) {
			echo esc_html( $breakpoints[ $break ] );
			echo PHP_EOL;

			sort( $col );
			$prev = 'start';

			foreach ( $col as $value ) {
				if ( $prev == $value ) {
					continue;
				}

				$prev = $value;

				if ( $break ) {
					echo '	';
				}
				echo '.col' . ( $break ? '-' . $break : '' ) . ( $value ? '-' . $value : '' ) . ' { ';
				if ( '' == $value ) {
					echo 'flex-basis: 0; flex-grow: 1; max-width: 100%;';
				} elseif ( 'auto' == $value ) {
					echo 'flex: 0 0 auto; width: auto; max-width: 100%;';
				} else {
					echo 'flex-basis: ' . ( round( 100 / ( 12 / $value ) * 10000 ) / 10000 ) . '%; max-width: ' . ( round( 100 / ( 12 / $value ) * 10000 ) / 10000 ) . '%;';
				}
				echo ' }';
				echo PHP_EOL;
			}

			echo esc_html( $breakpoints[ $break ] ? '}' : '' );
			if ( $break ) {
				echo PHP_EOL;
			}
		}
	}
	?>

[class*=' c-'] > * {
	width: 100%;
	padding-#{$left}: 1rem;
	padding-#{$right}: 1rem;
	flex-grow: 0;
	flex-shrink: 0;
}
	<?php
	// quickview classes
	$result['grid']['xs'][] = 3;
	$result['grid']['sm'][] = 4;
	foreach ( $result['grid'] as $break => $grid ) {
		if ( ! empty( $grid ) ) {
			echo esc_html( $breakpoints[ $break ] );
			echo PHP_EOL;

			foreach ( $grid as $value ) {
				if ( $break ) {
					echo '	';
				}
				echo '.c-' . ( $break ? $break . '-' : '' ) . $value . ' > * { ';
				echo 'flex-basis: ' . ( round( 100 / intval( $value ) * 10000 ) / 10000 ) . '% !important; max-width: ' . ( round( 100 / intval( $value ) * 10000 ) / 10000 ) . '% !important;';
				echo ' }';
				echo PHP_EOL;
			}

			echo esc_html( $breakpoints[ $break ] ? '}' : '' );
			if ( $break ) {
				echo PHP_EOL;
			}
		}
	}
else :
	?>
@import 'libs/grid';
<?php endif; ?>

/*-------------------- Bootstrap Styles --------------------*/
@import 'libs/bootstrap';

/*-------------------- General Styles --------------------*/
@import 'base/base';
@import 'base/type';
@import 'base/layout';

/*-------------------- Base Styles --------------------*/
@import 'base/headers/header';
@import 'base/headers/menu';
@import 'base/headers/vertical-menu';
@import 'base/headers/sticky-header';
<?php if ( molla_option( 'header_side' ) ) : ?>
@import 'base/headers/side-header';
<?php endif; ?>
@import 'base/headers/mobile-menu';

<?php if ( molla_option( 'mobile_menu_skin' ) ) : ?>
@import 'base/headers/mobile-menu-light';
<?php endif; ?>

<?php if ( $sidebar ) : ?>
@import 'base/sidebar';
<?php endif; ?>

@import 'base/widgets';
@import 'base/footers/footer';

/*-------------------- Elements Styles --------------------*/
@import 'elements/titles';
<?php
	$elements = array(
		'page-header',
		'breadcrumb',
		'cards',
		'tabs',
		'buttons',
		'products',
		'banners',
		'product-category',
		'social-icons',
		'testimonials',
		'forms',
		'tooltips',
		'tables',
		'counters',
		'pagination',
		'slider',
		'countdown',
		'member',
		'blog',
		'magnific_popup',
		'icon-boxes',
		'hotspot',
	);

	foreach ( $elements as $element ) :
		if ( ! in_array( $element, $unused ) ) :
			echo "@import 'elements/" . $element . "';\n";
		endif;
	endforeach;
	?>

/*-------------------- Page Styles --------------------*/
@import 'elements/progressbar';
@import 'pages/home';
<?php
// If it is cart, checkout, wishlist page
if ( ( class_exists( 'WooCommerce' ) && ( wc_get_page_id( 'cart' ) == $id || wc_get_page_id( 'checkout' ) == $id ) ) ||
	( function_exists( 'yith_wcwl_object_id' ) && yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) ) == $id ) ) :
	?>
@import 'elements/tables';
@import 'pages/cart';
@import 'pages/checkout';
@import 'pages/wishlist';
<?php endif; ?>
<?php if ( class_exists( 'WooCommerce' ) && wc_get_page_id( 'myaccount' ) == $id ) : ?>
@import 'pages/dashboard';
<?php endif; ?>
<?php
// When Products widget is used and Quickview is enabled
if ( ! in_array( 'products', $unused ) &&
		( in_array( 'quickview', molla_option( 'product_show_op' ) ) || in_array( 'quickview', molla_option( 'public_product_show_op' ) ) )
	) :
	?>
@import 'pages/product';
<?php endif; ?>
<?php
/*
	pages/_login.scss file is imported under only below conditions.
	1. If Login/out Form is used in header.
	2. If Nav Top Group is used and it has login form in header.
	3. If it is myaccount page.
*/
$header = str_replace( '\\', '', sanitize_text_field( json_encode( molla_option( 'hb_options' ) ) ) );
if ( false !== strpos( $header, '{"login-form":""}' ) ||
	false !== strpos( $header, '{"nav-top":""}' ) && in_array( 'login-form', molla_option( 'top_nav_items' ) ) ||
	class_exists( 'WooCommerce' ) && wc_get_page_id( 'myaccount' ) == $id ) :
	?>
	<?php if ( in_array( 'tabs', $unused ) ) : ?>
@import 'elements/tabs';
	<?php endif; ?>
@import 'pages/login';
<?php endif; ?>

/*--------------------------- Helpers --------------------------*/
@import 'config/helpers';

/*-------------------- Used Spacing classes --------------------*/
<?php
if ( in_array( 'spacing', $unused ) ) {
	$space_map = array(
		'm' => 'margin',
		'p' => 'padding',
		't' => 'top',
		'r' => '#{$right}',
		'b' => 'bottom',
		'l' => '#{$left}',
	);
	foreach ( $result['margin'] as $break => $space ) {
		if ( ! empty( $space ) ) {
			echo esc_html( $breakpoints[ $break ] );
			echo PHP_EOL;

			foreach ( $space as $key => $value ) {
				if ( $value > 10 ) {
					continue;
				}
				if ( $break ) {
					echo '	';
				}
				echo '.' . $key . ' { ';
				echo esc_html( $space_map[ $key[0] ] . '-' . $space_map[ $key[1] ] . ':' . ( 0.5 * intval( $value ) ) . 'rem !important;' );
				echo ' }';
				echo PHP_EOL;
			}

			echo esc_html( $breakpoints[ $break ] ? '}' : '' );
			if ( $break ) {
				echo PHP_EOL;
			}
		}
	}
	?>
@media (max-width: 991px) {
	.mt-lg-max-0 {
		margin-top: 0 !important;
	}
	.mb-lg-max-0 {
		margin-bottom: 0 !important;
	}
	.pt-lg-max-0 {
		padding-top: 0 !important;
	}
	.pb-lg-max-0 {
		padding-bottom: 0 !important;
	}
}
	<?php
	// quickview classes
	$result['spacing'][] = 10;
	foreach ( $result['spacing'] as $sp ) {
		echo '.sp-' . $sp . ' { 
	margin-#{$left}: -' . $sp / 2 . 'px;
	margin-#{$right}: -' . $sp / 2 . 'px;
	width: calc(100% + ' . $sp . 'px);
	>* {
		padding: ' . $sp / 2 . 'px !important;
	}
}';
		echo PHP_EOL;
	}
	?>
.t-x-left {
	transform: translateX(0);
	&.t-y-top {
		transform: translate(0, 0);
	}
	&.t-y-center {
		transform: translate(0, -50%);
	}
}

.t-x-center {
	transform: translateX(-50%);
	&.t-y-top {
		transform: translate(-50%, 0);
	}
	&.t-y-center {
		transform: translate(-50%, -50%);
	}
}

.t-y-top {
	transform: translateY(0);
}

.t-y-center {
	transform: translateY(-50%);
}
	<?php
	foreach ( $result['x_pos'] as $x_pos ) {
		echo '.x-' . $x_pos . ' { #{$left}: ' . $x_pos . '%; }';
		echo PHP_EOL;
	}
	foreach ( $result['y_pos'] as $y_pos ) {
		echo '.y-' . $y_pos . ' { top: ' . $y_pos . '%; }';
		echo PHP_EOL;
	}
} else {
	?>
@import 'config/spacing';
<?php } ?>

<?php if ( ! molla_option( 'error-block-name' ) ) : ?>
@import 'pages/404';
<?php endif; ?>

/*-------------------- Compatibility Styles --------------------*/
@import 'libs/elementor';
<?php if ( $gutenberg_data ) : ?>
@import 'libs/gutenberg';
<?php endif; ?>
@import 'libs/woocommerce';
