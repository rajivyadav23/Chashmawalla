<?php
/**
 * Molla_wcfm class
 *
 * @since 1.0.0
 * @package Molla WordPress Framework
 */
defined( 'ABSPATH' ) || die;

class Molla_WCFM {

	private static $instances = array();

	static function get_instance() {
		$called_class = get_called_class();
		if ( empty( self::$instances[ $called_class ] ) ) {
			self::$instances[ $called_class ] = new $called_class();
		}
		return self::$instances[ $called_class ];
	}

	public function __construct() {
        global $WCFM, $WCFMmp; //phpcs:ignore

		add_action( 'init', array( $this, 'init_vendor_settings' ), 20 );
		// Enqueue molla-wcfm script
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ), 50 );
	}

	/**
	 * Initialize wcfm hooks
	 *
	 * @since 1.0.0
	 */
	public function init_vendor_settings() {

		global $WCFM, $WCFMmp; //phpcs:ignore

		// phpcs:disable
		if ( ! molla_is_elementor_preview() ) {

			// Remove default product manage button and set newly
			remove_action( 'woocommerce_before_single_product_summary', array( $WCFM->frontend, 'wcfm_product_manage' ), 4 );
			add_action( 'molla_woocommerce_after_single_image', array( $WCFM->frontend, 'wcfm_product_manage' ) );

			remove_action( 'woocommerce_before_shop_loop_item', array( $WCFM->frontend, 'wcfm_product_manage' ), 4 );
			add_action( 'woocommerce_before_shop_loop_item', array( $WCFM->frontend, 'wcfm_product_manage' ), 6 );

			// Remove all defaut sold by template from WCFM dashboard settings
			remove_action( 'woocommerce_after_shop_loop_item_title', array( $WCFMmp->frontend, 'wcfmmp_sold_by_product' ), 9 );
			remove_action( 'woocommerce_after_shop_loop_item', array( $WCFMmp->frontend, 'wcfmmp_sold_by_product' ), 50 );
			remove_action( 'woocommerce_after_shop_loop_item_title', array( $WCFMmp->frontend, 'wcfmmp_sold_by_product' ), 50 );

			// Set sold by position by theme.
			add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'add_sold_by_to_loop' ), 10 );
		}

		// Set sold by template by theme
		$template_type = $WCFMmp->wcfmmp_vendor->get_vendor_sold_by_template();

		if ( 'tab' != $template_type ) {
			$wcfm_marketplace_options                            = get_option( 'wcfm_marketplace_options', array() );
			$wcfm_marketplace_options['vendor_sold_by_template'] = 'tab';

			// update wcfm settings
			update_option( 'wcfm_marketplace_options', $wcfm_marketplace_options );

			remove_action( 'woocommerce_single_product_summary', array( $WCFMmp->frontend, 'wcfmmp_sold_by_single_product' ), 6 );
			remove_action( 'woocommerce_single_product_summary', array( $WCFMmp->frontend, 'wcfmmp_sold_by_single_product' ), 15 );
			remove_action( 'woocommerce_single_product_summary', array( $WCFMmp->frontend, 'wcfmmp_sold_by_single_product' ), 25 );
			remove_action( 'woocommerce_product_meta_start', array( $WCFMmp->frontend, 'wcfmmp_sold_by_single_product' ), 50 );

			add_filter( 'woocommerce_product_tabs', 'wcfm_product_multivendor_tab', 98 );
		}
		
		//phpcs:enable
	}


	/**
	 * Enqueue wcfm Style
	 *
	 * @since 1.0.0
	 */
	public function enqueue_style() {
		wp_enqueue_style( 'molla-wcfm-style', MOLLA_PLUGINS_URI . '/compatibility/wcfm/wcfm' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array( 'molla-style' ), MOLLA_VERSION );
	}

		/**
	 * Add sold by label to product loop
	 *
	 * @since 1.0.0
	 */
	public function add_sold_by_to_loop() {

		if ( ! class_exists( 'WCFM' ) ) {
			return;
		}

		global $WCFM, $post, $WCFMmp;

		$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $post->ID );

		if ( ! $vendor_id ) {
			return;
		}

		$sold_by_text = apply_filters( 'wcfmmp_sold_by_label', esc_html__( 'Sold By:', 'molla' ) );
		$store_name   = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint( $vendor_id ) );

		?>
		<div class="molla-sold-by-container">
			<span class="sold-by-label"><?php echo esc_html( $sold_by_text ); ?></span>
			<?php echo wp_kses_post( $store_name ); ?>
		</div>
		<?php
	}
}

Molla_WCFM::get_instance();
