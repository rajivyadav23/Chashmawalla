/**
 * Molla Dependent Plugin - Product Featured Video
 *
 * @requires fitVids
 */
(function ($) {
	'use strict';
	$('body').on('click', '.open-product-video-viewer', function (e) {
		e.preventDefault();
		var $this = $(this);

		if (typeof theme.video_data == 'undefined'
			|| $this.length < 1 || theme.video_data == '') {
			return;
		}

		if (!$.fn.fitVids) {
			return;
		}

		var mpInstance = $.magnificPopup.instance;
		if (mpInstance.isOpen) {
			mpInstance.close();
		}
		setTimeout(function () {
			$.magnificPopup.open({

				type: 'inline',
				mainClass: "product-video-wrapper",
				preloader: false,
				items: {
					src: '<div class="video-popup-wrapper ml-auto mr-auto" style="max-width: 83rem; position: relative">' + theme.video_data + '</div>'
				},
				callbacks: {
					open: function () {
						Molla.fitVid($('.video-popup-wrapper .fit-video'));
					},
					beforeClose: function () {
						this.container.empty();
					}
				}
			}, 0);
		}, 500)
	})
})(jQuery);
