(function ($) {
	'use strict';

	// Skeleton Screens
	Molla.skeletonScreen = function () {
		var $skel_bodies = $('.skeleton-body'),
			skel_cnt = $skel_bodies.length;
		$skel_bodies.each(
			function (e) {
				var $this = $(this);

				var dclPromise = (function () {
					var deferred = $.Deferred();
					if (lib_skeleton.lazyload) {
						deferred.resolve();
					} else {
						if (typeof imagesLoaded === 'function') {
							var $content = $this.find('script[type="text/template"]');
							var cnt = $content.length;
							$content.each(
								function () {
									$(JSON.parse($(this).html())).imagesLoaded(
										function () {
											if (0 == --cnt) {
												deferred.resolve();
											}
										}
									);
								}
							)
						}
					}
					return deferred.promise();
				})();

				$.when(dclPromise).done(
					function (e) {
						var $content = $this.find('script[type="text/template"]');
						$content.map(
							function () {
								var $parent = $(this).parent();
								$parent.children().remove();
								$parent.append($(JSON.parse($(this).html())));
							}
						);

						$this.removeClass('skeleton-body');

						if (Molla.liveSearch) {
							Molla.liveSearch();
						}

						--skel_cnt || $(document).trigger('molla_skeleton_loaded', $this); // trigger molla_skeleton_loaded when all skeletons loaded.

						$(document.body).trigger('wc_fragment_refresh');

						if ($.fn.mediaelementplayer) {
							$(window.wp.mediaelement.initialize);
						}
					}
				);

			}
		);
	}

	$(window).on('load', function () {
		Molla.skeletonScreen();
	});

	$(document).on('molla_ajax_load_more_pagination_success', function (e, ret, $content, $pagination) {
		Molla.skeletonScreen();
	});

	$(document).on('molla_before_ajax_load_more_pagination', function (e, $content, $pagination, url) {
		var classes = $content.children().eq(0).attr('class');
		var type;

		$content.removeClass('molla-loading');

		if ($content.hasClass('products')) {
			type = 'skel-pro';

			if ($content.find('.product-list').length) {
				type += ' skel-pro-list';
			}
		} else if ($content.hasClass('posts')) {
			type = 'skel-post';

			if ($content.find('.post-list').length) {
				type += ' skel-post-list';
			}
		}
		$content.html('');

		for (var i = 0; i < 12; ++i) {
			$content.append('<div class="' + classes + ' skeleton-body"><div class="' + type + '"></div></div>');
		}
		var offset = $('[class*="' + $content.attr('class') + '"]').offset().top;
		if ($('.sticky-header.fixed')) {
			offset -= $('.sticky-header.fixed').outerHeight();
		}
		if ($('#wpadminbar').length) {
			offset -= $('#wpadminbar').outerHeight();
		}
		$('html, body').animate({
			'scrollTop': offset
		}, 400);
	});

	$(document).on('yith-wcan-ajax-loading', function (e) {
		$(yith_wcan.container).not('.ywcps-products').removeClass('yith-wcan-loading');
		for (var i = 0; i < 12; ++i) {
			$(yith_wcan.container).append('<div class="skeleton-body"><div class="' +
				($(yith_wcan.container).hasClass('products-list-loop') ? 'skel-pro skel-pro-list' : 'skel-pro') +
				'"></div></div>');
		}
		if ($('.yit-wcan-container').length) {
			var offset = $('.yit-wcan-container').offset().top;
			if ($('.sticky-header.fixed')) {
				offset -= $('.sticky-header.fixed').outerHeight();
			}
			if ($('#wpadminbar').length) {
				offset -= $('#wpadminbar').outerHeight();
			}
			$('html, body').animate({
				'scrollTop': offset
			}, 400);
		}
	})

	$(document).on('yith-wcan-ajax-filtered', function (e, res) {
		Molla.skeletonScreen();
	})

	$(document).on('quickview_content_before_ajax', function (e, content) {
		return '<div class="skeleton-body">' +
			'<div class="skel-pro-single">' +
			'<div class="row">' +
			'<div class="col-lg-6">' +
			'<div class="product-gallery"></div>' +
			'</div>' +
			'<div class="col-lg-6">' +
			'<div class="entry-summary row">' +
			'<div class="col-md-12">' +
			'<div class="entry-summary1"></div>' +
			'</div>' +
			'<div class="col-md-12">' +
			'<div class="entry-summary2"></div>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>';
	});
})(jQuery);
