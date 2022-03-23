jQuery(document).ready(function ($) {
	'use strict';

	// Add studio block section when add is triggered in Elementor
	var $addStudioSection = false,
		filter1_status = 'all',
		filter2_status = '';

	// Run studio
	window.runStudio = function (addButton) {
		$('#molla-elementor-panel-molla-studio').trigger('click');

		if (addButton) {
			$addStudioSection = $(addButton).closest('.elementor-add-section');
		}
	}

	// add Molla Studio menu
	elementor.on('panel:init', function () {
		$('<div id="molla-elementor-panel-molla-studio" class="elementor-panel-footer-tool tooltip-target" data-tooltip="Molla Studio"><img class="molla-studio-icon" src="https://d-themes.com/wordpress/molla/wp-content/themes/molla/assets/images/logo-studio-mini.png" alt="Molla Studio"><span class="elementor-screen-only">Molla Studio</span></div>')
			.insertAfter('#elementor-panel-footer-saver-preview').tipsy({
				gravity: 's',
				title: function title() {
					return this.getAttribute('data-tooltip');
				}
			});
	});

	$(window).on('load', function () {

		// molla studio
		if ($('.blocks-wrapper .blocks-list .block').length) {
			var molla_blocks_cur_page = 1;
			$('.blocks-wrapper .category-list a').on('click', function (e, cur_page, demo_filter) {
				e.preventDefault();
				if ($('.blocks-wrapper').hasClass('loading')) {
					return false;
				}
				if (typeof cur_page != 'undefined') {
					molla_blocks_cur_page = parseInt(cur_page, 10);
				} else {
					molla_blocks_cur_page = 1;
				}
				var $this = $(this),
					cat = $this.data('filter-by'),
					limit = $this.data('limit'),
					loaddata = { action: 'molla_studio_filter_category', category_id: cat, count_per_page: limit, wpnonce: molla_studio.wpnonce, page: molla_blocks_cur_page };
				if (typeof demo_filter != 'undefined') {
					loaddata.demo_filter = demo_filter;
				}
				if ($(document.body).hasClass('elementor-editor-active')) {
					loaddata.type = 'e'; // Elementor
				} else {
					loaddata.type = 'v'; // Visual Composer
				}
				$('.blocks-wrapper').addClass('loading');

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'html',
					data: loaddata,
					success: function (response) {
						if ('error' == response) {
							$('.blocks-wrapper').removeClass('loading').removeClass('infiniteloading');
							return;
						}
						if (molla_blocks_cur_page === 1) {
							$('.blocks-wrapper .blocks-list').isotope('remove', $('.blocks-wrapper .blocks-list').children());
						}
						var newItems = $(response).find('.blocks-list').children();
						$('.blocks-wrapper .blocks-list').append(newItems);
						$('.blocks-wrapper .blocks-list').isotope('appended', newItems);
						$('.blocks-wrapper .category-list a').removeClass('active');
						$this.addClass('active');
						if (typeof demo_filter != 'undefined') {
							var total_page = $(response).find('.category-list li:hidden a').data('total-page');
							if (total_page) {
								$this.data('total-page', parseInt(total_page, 10));
							} else {
								$this.data('total-page', 1);
							}
						}
						$('.blocks-wrapper .blocks-list').waitForImages(function () {
							$('.blocks-wrapper .blocks-list').isotope('layout');
							$('.blocks-wrapper').removeClass('loading').removeClass('infiniteloading');
							$('.mfp-wrap.blocks-cont').trigger('scroll');
						});
					}
				}).fail(function (jqXHR, textStatus, errorThrown) {
					alert('Loading failed!');
					$('.blocks-wrapper').removeClass('loading').removeClass('infiniteloading');
				});
			});

			$('.blocks-wrapper .blocks-list').on('click', '.import', function (e) {
				e.preventDefault();
				var $this = $(this),
					block_id = $this.data('id');
				$this.attr('disabled', 'disabled');
				$this.closest('.block').addClass('importing');

				var importdata = { action: 'molla_studio_import', block_id: block_id, wpnonce: molla_studio.wpnonce };
				if ($(document.body).hasClass('elementor-editor-active')) {
					importdata.type = 'e'; // Elementor
				} else {
					importdata.type = 'v'; // Visual Composer
				}
				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: importdata,
					success: function (response) {
						if (response && response.content) {
							if (typeof vc != 'undefined' && vc.storage) {
								vc.storage.append(response.content);
								vc.shortcodes.fetch({
									reset: !0
								}), _.delay(function () {
									window.vc.undoRedoApi.unlock();
								}, 50);
							} else if (window.vc_iframe_src) { // frontend editor
								var render_data = { action: 'vc_frontend_load_template', block_id: block_id, content: response.content, wpnonce: molla_studio.wpnonce, template_unique_id: '1', template_type: 'my_templates', vc_inline: true, _vcnonce: window.vcAdminNonce };
								if (response.meta) {
									render_data.meta = response.meta;
								}
								$.ajax({
									url: window.vc_iframe_src.replace(/&amp;/g, '&'),
									type: 'post',
									data: render_data,
									success: function (html) {
										var template, data;
										_.each($(html), function (element) {
											if ('vc_template-data' === element.id) {
												try {
													data = JSON.parse(element.innerHTML);
												} catch (err) { }
											}
											if ('vc_template-html' === element.id) {
												template = element.innerHTML;
											}
										});
										if (template && data) {
											vc.builder.buildFromTemplate(template, data);
											vc.closeActivePanel();
										}
									},
								}).always(function () {
									$this.removeAttr('disabled');
									$this.closest('.block').removeClass('importing');
								});
							} else if (typeof elementor != 'undefined') {
								var addID = function (content) {
									Array.isArray(content) &&
										content.forEach(function (item, i) {
											item.elements && addID(item.elements);
											item.elType && (content[i].id = elementor.helpers.getUniqueID());
										});
								};

								if (Array.isArray(response.content) && 1 == response.content.length) {
									if ('widget' == response.content[0].elType) {
										response.content = [{
											elType: 'section',
											elements: [{
												elType: 'column',
												elements: response.content
											}]
										}];
									} else if ('column' == response.content[0].elType) {
										response.content = [{
											elType: 'section',
											elements: response.content
										}];
									}
								}

								addID(response.content);

								// import studio block to end or add-section
								elementor.getPreviewView().addChildModel(response.content,
									$addStudioSection && $addStudioSection.parent().hasClass('elementor-section-wrap') ?
										(
											$addStudioSection.find('.elementor-add-section-close').trigger('click'),
											{ at: $addStudioSection.index() }
										) : {}
								);

								// active save button or save elementor
								if (elementor.saver && elementor.saver.footerSaver && elementor.saver.footerSaver.activateSaveButtons) {
									elementor.saver.footerSaver.activateSaveButtons(document, 'publish');
								} else {
									$e.run('document/save/publish');
								}
							}
						}
						if (response && response.meta) {
							for (var key in response.meta) {
								var value = response.meta[key].replace('/<script.*?\/script>/s', '');
								if (typeof vc != 'undefined' && vc.storage && $('[name="' + key + '"]').length) {
									switch ($('[name="' + key + '"]')[0].tagName.toLowerCase()) {
										case 'input':
											var input_type = $('[name="' + key + '"]').attr('type').toLowerCase();
											if ('text' == input_type || 'hidden' == input_type) {
												$('[name="' + key + '"]').val(value);
											} else if ('checkbox' == input_type || 'radio' == input_type) {
												$('[name="' + key + '"]').removeProp('checked');
												$('[name="' + key + '"]').each(function () {
													if ($(this).val() == value) {
														$(this).prop('checked', 'checked');
													}
												});
											}
											break;
										case 'select':
											$('[name="' + key + '"] option').removeProp('selected');
											$('[name="' + key + '"] option[value="' + value + '"]').prop('selected', 'selected');
											$('[name="' + key + '"]').val(value);
											break;
										default:
											$('[name="' + key + '"]').each(function () {
												$(this).val($(this).val() + value);
											});
									}
								} else if (window.vc_iframe_src) {
									if (typeof molla_studio['meta_fields'] == 'undefined') {
										molla_studio['meta_fields'] = {};
									}
									if (typeof molla_studio['meta_fields'][key] == 'undefined') {
										molla_studio['meta_fields'][key] = '';
									}
									if (molla_studio['meta_fields'][key].indexOf(value) === -1) {
										molla_studio['meta_fields'][key] += value;
									}
								} else if (typeof elementor != 'undefined') {
									var key_data = elementor.settings.page.model.get(key);
									if (typeof key_data == 'undefined') {
										key_data = '';
									}
									if (!key_data || key_data.indexOf(value) === -1) {
										elementor.settings.page.model.set(key, key_data + value);
									}
									if ('page_css' == key) {
										elementorFrontend.hooks.doAction('refresh_dynamic_css', key_data + value, block_id);
										$('textarea[data-setting="page_css"]').val(key_data + value);
									}
								}
							}
						}
						if (response && response.error) {
							alert(response.error);
						}
					},
					failure: function () {
						alert('There was an error when importing block. Please try again later!');
					}
				}).always(function () {
					//if (vc.storage) {
					$this.removeAttr('disabled');
					$this.closest('.block').removeClass('importing');
					//}
				});
			});
			// molla studio in vc front-end editor
			$('#vc_button-update').on('click', function (e) {
				if (molla_studio['meta_fields'] && vc_post_id) {
					$.ajax({
						url: ajaxurl,
						type: 'post',
						dataType: 'json',
						data: { action: 'molla_studio_save', post_id: vc_post_id, nonce: molla_studio.wpnonce, fields: molla_studio['meta_fields'] }
					});
				}
			});
			// molla studio demo filters
			$('.blocks-wrapper .demo-filter-trigger').on('click', function (e) {
				e.preventDefault();
				$(this).parent().toggleClass('active');
			});
			$('.blocks-wrapper .demo-filter .filter1').on('change', function (e) {
				if ('all' != $(this).val()) {
					$('.blocks-wrapper .demo-filter .filter2 option').removeAttr('selected').hide();
					$('.blocks-wrapper .demo-filter .filter2 option[data-filter*="' + $(this).val() + '"]').show();
					$('.blocks-wrapper .demo-filter .filter2 option:first-child').attr('selected', 'selected').show();
				} else {
					$('.blocks-wrapper .demo-filter .filter2 option').removeAttr('selected').show();
				}
				if (filter1_status != $(this).val() || filter2_status != $('.blocks-wrapper .demo-filter .filter2').val()) {
					$('.demo-filter .btn').removeAttr('disabled');
				} else {
					$('.demo-filter .btn').attr('disabled', 'disabled');
				}
			});
			$('.blocks-wrapper .demo-filter .filter2').on('change', function (e) {
				if (filter1_status != $('.blocks-wrapper .demo-filter .filter1').val() || filter2_status != $(this).val()) {
					$('.demo-filter .btn').removeAttr('disabled');
				} else {
					$('.demo-filter .btn').attr('disabled', 'disabled');
				}
			});
			$('.blocks-wrapper .demo-filter .btn').on('click', function (e, cur_page) {
				e.preventDefault();
				var $this = $(this),
					filter = [];
				if (typeof cur_page == 'undefined') {
					cur_page = 1;
				}
				filter1_status = $this.closest('.demo-filter').find('.filter1').val();
				filter2_status = $this.closest('.demo-filter').find('.filter2').val();

				if ($this.closest('.demo-filter').find('.filter2').val()) {
					filter[0] = $this.closest('.demo-filter').find('.filter2').val();
				} else {
					var filter1 = $this.closest('.demo-filter').find('.filter1').val()
					$this.closest('.demo-filter').find('.filter2 option[data-filter*="' + filter1 + '"]').each(function () {
						filter.push($(this).val());
					});
				}
				$('.blocks-wrapper .category-list li:first-child a').trigger('click', [cur_page, filter]);
				$this.attr('disabled', 'disabled');
			});
		}

		// Molla Studio Popup Button
		// $('#molla-studio-editor-button, #molla-elementor-panel-molla-studio').on('click', function(e) {
		$('#molla-elementor-panel-molla-studio').on('click', function (e) {
			e.preventDefault();
			if ($(this).hasClass('disabled')) {
				return false;
			}
			$(this).addClass('disabled');
			$('.blocks-wrapper img[data-original]').each(function () {
				$(this).attr('src', $(this).data('original'));
				$(this).removeAttr('data-original');
			});
			$('.blocks-wrapper').waitForImages(function () {
				$('#molla-studio-editor-button, #molla-elementor-panel-molla-studio').removeClass('disabled');
				$.magnificPopup.open({
					items: {
						src: '.blocks-wrapper'
					},
					type: 'inline',
					mainClass: 'blocks-cont mfp-fade',
					removalDelay: 160,
					preloader: false,
					//fixedContentPos: false,
					callbacks: {
						change: function () {
							setTimeout(function () {
								if (!$('.blocks-wrapper .blocks-list').hasClass('initialized')) {
									$('.blocks-wrapper .blocks-list').addClass('initialized').isotope({
										itemSelector: '.block',
										layoutMode: 'masonry'
									});

									$('.mfp-wrap.blocks-cont').on('scroll', function () {
										var $this = $(this),
											top = $this.find('.blocks-wrapper').offset().top - $this.offset().top + $this.find('.blocks-wrapper').height() - $this.height();
										if (top <= 10 && !$this.find('.blocks-wrapper').hasClass('loading') && parseInt($this.find('.blocks-wrapper .category-list a.active').data('total-page'), 10) >= molla_blocks_cur_page + 1) {
											if (parseInt($this.find('.blocks-wrapper .category-list a.active').data('filter-by'), 10)) {
												$this.find('.blocks-wrapper .category-list a.active').trigger('click', [molla_blocks_cur_page + 1]);
											} else {
												$this.find('.blocks-wrapper .demo-filter .btn').trigger('click', [molla_blocks_cur_page + 1]);
											}
											$this.find('.blocks-wrapper').addClass('infiniteloading');
										}
									});

									$('.mfp-wrap.blocks-cont').trigger('scroll');
								}
								$('.blocks-wrapper .blocks-list').isotope('layout');
							}, 100);
						}
					}
				});
			});
		});
	});
});