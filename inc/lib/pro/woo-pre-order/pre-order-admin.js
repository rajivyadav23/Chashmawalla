jQuery(document).ready(function ($) {
	// Pre-Order
	var molla_pre_order = {
		init: function () {
			$(document.body).on('change', 'input.variable_is_preorder', function (e) {
				if ($(this).is(':checked')) {
					$(this).closest('.woocommerce_variation').find('.show_if_pre_order').show();
				} else {
					$(this).closest('.woocommerce_variation').find('.show_if_pre_order').hide();
				}
			});

			$(document.body).on('change', '#_molla_pre_order', function (e) {
				if ($(this).is(':hidden')) {
					return;
				}
				if ($(this).is(':checked')) {
					$(this).closest('#woocommerce-product-data').find('.show_if_pre_order').show();
					$(this).closest('#woocommerce-product-data').find('.general_options > a').click();
				} else {
					$(this).closest('#woocommerce-product-data').find('.show_if_pre_order').hide();
				}
			});
			this.variations_loaded(null);

			$(document.body).on('woocommerce_variations_added', this.variation_added);
			$('#woocommerce-product-data').on('woocommerce_variations_loaded', this.variations_loaded);
		},
		variations_loaded: function (event, updated) {
			updated = updated || false;
			if (!updated) {
				$('#woocommerce-product-data').find('input.variable_is_preorder, #_molla_pre_order').change();
			}

			$('#woocommerce-product-data .pre_order_available_date').datepicker({
				defaultDate: '',
				dateFormat: 'yy-mm-dd',
				numberOfMonths: 1,
				showButtonPanel: true,
				minDate: new Date(),
			});
		},
		variation_added: function (e, qty) {
			if (1 === qty) {
				molla_pre_order.variations_loaded(null);
			}
		}
	};
	if ($('#woocommerce-product-data').length) {
		molla_pre_order.init();
	}
});