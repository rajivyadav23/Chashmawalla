<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/result-count.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<p class="woocommerce-result-count">
	<?php
	if ( 1 === $total ) {
		_e( 'Showing the single result', 'woocommerce' );
	} elseif ( $total <= $per_page || -1 === $per_page ) {
		/* translators: %d: total results */
		echo wp_kses(
			sprintf( _n( 'Showing <span>all of %d</span> result', 'Showing <span>all of %d</span> Products', $total, 'molla' ), $total ),
			array(
				'span' => array(
					'class' => array(),
				),
			)
		);
	} else {
		$first = ( $per_page * $current ) - $per_page + 1;
		$last  = min( $total, $per_page * $current );
		/* translators: 1: first result 2: last result 3: total results */
		echo wp_kses(
			sprintf( _nx( 'Showing <span>%1$d& of %3$d</span> result', 'Showing <span class="from">%1$d</span><span>&ndash;</span><span class="to">%2$d</span><span> of </span><span class="total">%3$d</span> Products', $total, 'with first and last result', 'molla' ), $first, $last, $total ),
			array(
				'span' => array(
					'class' => array(),
				),
			)
		);
	}
	?>
</p>
