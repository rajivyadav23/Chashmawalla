/**
 * Molla Dependent Plugin - Product360DegreeViewer
 *
 * @requires threesixty-slider
 */
(function ($) {
	'use strict';
	$('body').on('click', '.open-product-degree-viewer', function (e) {
		e.preventDefault();
		var $this = $(this);

		if (typeof theme.threesixty_data == 'undefined'
			|| $this.length < 1 || theme.threesixty_data.length < 1) {
			return;
		}

		if (!$.fn.ThreeSixty) {
			return;
		}

		Molla.popup({
			type: 'inline',
			mainClass: "molla-product-degree-wrapper",
			preloader: false,
			items: {
				src: '<div id="molla-product-degree-viewport" class="molla-product-degree-viewport">' +
					'<div class="molla-product-gallery-degree">' +
					'<div class="molla-degree-progress-bar"><i></i></div>' +
					'<ul class="molla-product-degree-images"></ul>' +
					'</div>' +
					'</div>'
			},
			callbacks: {
				open: function () {

					var $degree_viewport = $('#molla-product-degree-viewport');

					if ($degree_viewport.hasClass('init')) { // Create degree_viewer object only once
						return;
					}
					$degree_viewport.addClass('init');

					var imageArr = theme.threesixty_data.split(','),
						images = [];

					for (var i = 0; i < imageArr.length; i++) {
						images.push(imageArr[i]);
					}

					$degree_viewport.find('.molla-product-gallery-degree').ThreeSixty(
						{
							totalFrames: images.length, // Total no. of image you have for 360 slider
							endFrame: images.length, // end frame for the auto spin animation
							currentFrame: images.length - 1, // This the start frame for auto spin
							imgList: $degree_viewport.find('.molla-product-degree-images'), // selector for image list
							progress: '.molla-degree-progress-bar', // selector to show the loading progress
							imgArray: images, // path of the image assets
							height: 500,
							width: 830,
							speed: 10,
							navigation: true
						}
					);

					$degree_viewport.find('.molla-product-degree-images').imagesLoaded(
						function () {
							$degree_viewport.find('.nav_bar').removeClass('hide');
						}
					);
				},
				beforeClose: function () {
					this.container.empty();
				}
			}
		})
	})
})(jQuery);
