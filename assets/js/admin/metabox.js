jQuery(document).ready(function ($) {

	//media embed code area
	$(window).on('load', function () {
		var $select = $('.editor-post-format select');
		if ($select.val() != 'video') {
			$('#media_embed_code').closest('.rwmb-field').addClass('hidden');
		}
		else {
			$('[name="featured_images"]').closest('.rwmb-field').addClass('hidden');
		}
	});

	$('body').on('change', '.editor-post-format select', function () {
		var val = $(this).val();
		if (val == 'video') {
			$('#media_embed_code').closest('.rwmb-field').removeClass('hidden');
			$('[name="featured_images"]').closest('.rwmb-field').addClass('hidden');
		} else {
			$('#media_embed_code').closest('.rwmb-field').addClass('hidden');
			$('[name="featured_images"]').closest('.rwmb-field').removeClass('hidden');
		}
	})
});