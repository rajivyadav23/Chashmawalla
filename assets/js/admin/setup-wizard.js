
var MollaWizardAdmin = [];

(function ($) {
	'use strict';

	var dummy_index = 0, dummy_count = 0, dummy_process = 'import_start';
	var self = MollaWizardAdmin = {
		initialised: false,
		mobile: false,
		init: function () {
			if (!this.initialised) {
				this.initialised = true;
			} else {
				return;
			}

			// Molla Admin functions
			self.demoFilterIsotope();
			self.selectedDemo();
			self.importDetailOptions();
			self.molla_demo_import_setting();
			self.molla_load_more_plugins();
		},
		demoFilterIsotope: function () {
			// Filter demos
			if ($('#theme-install-demos').length) {
				var $install_demos = $('#theme-install-demos').isotope(),
					$demos_filter = $('.demo-sort-filters');

				$demos_filter.find('.sort-source li').click(function (e) {
					e.preventDefault();
					var $this = $(this),
						filter = $this.data('filter-by');
					$install_demos.isotope({
						filter: (filter == '*' ? filter : ('.' + filter))
					});
					$demos_filter.find('.sort-source li').removeClass('active');
					$this.addClass('active');
					return false;
				});
				$demos_filter.find('.sort-source li[data-active="true"]').click();
			}
		},
		selectedDemo: function () {
			// import demos
			$(document).on('click', '.molla-install-demos .theme .theme-wrapper', function (e) {
				e.preventDefault();
				if ($(this).closest('.theme').hasClass('open-classic')) {
					$(this).closest('.molla-install-demos').find('.demo-sort-filters [data-filter-by="classic"] a').click();
				} else if ($(this).closest('.theme').hasClass('open-shop')) {
					$(this).closest('.molla-install-demos').find('.demo-sort-filters [data-filter-by="shop"] a').click();
				} else if ($(this).closest('.theme').hasClass('open-blog')) {
					$(this).closest('.molla-install-demos').find('.demo-sort-filters [data-filter-by="blog"] a').click();
				} else {
					$('#molla-install-options').show();
					$(this).closest('.molla-install-demos').find('.molla-install-demo .theme-screenshot').css('background-image', $(this).find('.theme-screenshot').css('background-image'));
					$(this).closest('.molla-install-demos').find('.molla-install-demo .theme-name').html($(this).find('.theme-name').text());
					$(this).closest('.molla-install-demos').find('.molla-install-demo .live-site').attr('href', $(this).find('.theme-name').data('live-url'));
					$(this).closest('.molla-install-demos').find('.molla-install-demo .more-options').removeClass('opened');
					$(this).closest('.molla-install-demos').find('.molla-install-demo .molla-install-options-section').hide();
					$(this).closest('.molla-install-demos').find('.molla-install-demo .plugins-used').remove();
					$('#molla-install-demo-type').val($(this).find('.theme-name').attr('id'));
					if ($(this).find('.plugins-used').length) {
						$(this).find('.plugins-used').clone().insertAfter($(this).closest('.molla-install-demos').find('.molla-install-section'));
						$(this).closest('.molla-install-demos').find('.molla-install-demo .molla-install-section').hide();
						$(this).closest('.molla-install-demos').find('.molla-install-demo .more-options').hide();
					} else {
						$(this).closest('.molla-install-demos').find('.molla-install-demo .molla-install-section').show();
						$(this).closest('.molla-install-demos').find('.molla-install-demo .more-options').show();
					}
					if ($('.molla-import-yes:not(:disabled)').length) {
						$('.molla-install-demo #import-status').html('');
					}
					$.magnificPopup.open({
						items: {
							src: '.molla-install-demo'
						},
						type: 'inline',
						mainClass: 'mfp-with-zoom',
						zoom: {
							enabled: true,
							duration: 300
						}
					});
				}
			});
		},
		alertLeavePage: function (e) {
			var dialogText = "Are you sure you want to leave?";
			e.returnValue = dialogText;
			return dialogText;
		},
		addAlertLeavePage: function () {
			$('.molla-import-yes.btn-primary').attr('disabled', 'disabled');
			$('.mfp-bg, .mfp-wrap').unbind('click');
			$(window).bind('beforeunload', self.alertLeavePage);
		},
		removeAlertLeavePage: function () {
			$('.molla-import-yes.btn-primary').removeAttr('disabled');
			$('.mfp-bg, .mfp-wrap').bind('click', function (e) {
				if ($(e.target).is('.mfp-wrap .mfp-content *')) {
					return;
				}
				e.preventDefault();
				$.magnificPopup.close();
			});
			$(window).unbind('beforeunload', self.alertLeavePage);
		},
		importDetailOptions: function () {
			$('.molla-install-demo .more-options').click(function (e) {
				e.preventDefault();
				$(this).toggleClass('opened');
				$(this).closest('.molla-install-demo').find('.molla-install-options-section').stop().toggle('slide');
			});
		},
		showImportMessage: function (selected_demo, message, count, index) {
			var html = '';
			if (selected_demo) {
				html += '<h3 class="molla-demo-install"><i class="molla-ajax-loader"></i> Installing ' + $('#' + selected_demo).html() + '</h3>';
			}
			if (message) {
				html += '<strong>' + message + '</strong>';
			}
			if (count && index) {
				var percent = index / count * 100;
				if (percent > 100)
					percent = 100;
				html += '<div class="import-progress-bar"><div style="width:' + percent + '%;"></div></div>';
			}
			$('.molla-install-demo #import-status').stop().show().html(html);
		},
		molla_demo_import_setting: function () {
			$('.molla-import-yes').click(function () {
				if (!confirm("Are you sure to import demo contents and override old one?")) {
					return;
				}

				self.addAlertLeavePage();

				var demo = $('#molla-install-demo-type').val(),
					options = {
						demo: demo,
						reset_menus: $('#molla-reset-menus').is(':checked'),
						reset_widgets: $('#molla-reset-widgets').is(':checked'),
						import_dummy: $('#molla-import-dummy').is(':checked'),
						import_widgets: $('#molla-import-widgets').is(':checked'),
						import_options: $('#molla-import-options').is(':checked'),
						override_contents: $('#molla-override-contents').is(':checked')
					};
				if ($(this).hasClass('alternative')) {
					options.dummy_action = 'molla_import_dummy_step_by_step';
				} else {
					options.dummy_action = 'molla_import_dummy';
				}

				if (options.demo) {
					self.showImportMessage(demo, '');
					var data = { 'action': 'molla_download_demo_file', 'demo': demo, 'wpnonce': molla_setup_wizard_params.wpnonce };
					$.post(ajaxurl, data, function (response) {
						try {
							response = $.parseJSON(response);
						} catch (e) { }
						if (response && response.process && response.process == 'success') {
							self.molla_import_options(options);
						} else if (response && response.process && response.process == 'error') {
							self.molla_import_failed(demo, response.message);
						} else {
							self.molla_import_failed(demo);
						}
					}).fail(function (response) {
						self.molla_import_failed(demo);
					});
				}
				$('#molla-install-options').slideUp();
			});
		},
		// import options
		molla_import_options: function (options) {
			if (!options.demo) {
				self.removeAlertLeavePage();
				return;
			}
			if (options.import_options) {
				var demo = options.demo,
					data = { 'action': 'molla_import_options', 'demo': demo, 'wpnonce': molla_setup_wizard_params.wpnonce };

				self.showImportMessage(demo, 'Importing theme options');

				$.post(ajaxurl, data, function (response) {
					if (response) self.showImportMessage(demo, response);
					self.molla_reset_menus(options);
				}).fail(function (response) {
					self.molla_reset_menus(options);
				});
			} else {
				self.molla_reset_menus(options);
			}
		},
		// reset_menus
		molla_reset_menus: function (options) {
			if (!options.demo) {
				self.removeAlertLeavePage();
				return;
			}
			if (options.reset_menus) {
				var demo = options.demo,
					data = { 'action': 'molla_reset_menus', 'import_shortcodes': options.import_shortcodes, 'wpnonce': molla_setup_wizard_params.wpnonce };

				$.post(ajaxurl, data, function (response) {
					if (response) self.showImportMessage(demo, response);
					self.molla_reset_widgets(options);
				}).fail(function (response) {
					self.molla_reset_widgets(options);
				});
			} else {
				self.molla_reset_widgets(options);
			}
		},
		// reset widgets
		molla_reset_widgets: function (options) {
			if (!options.demo) {
				self.removeAlertLeavePage();
				return;
			}
			if (options.reset_widgets) {
				var demo = options.demo,
					data = { 'action': 'molla_reset_widgets', 'wpnonce': molla_setup_wizard_params.wpnonce };

				$.post(ajaxurl, data, function (response) {
					if (response) self.showImportMessage(demo, response);
					self.molla_import_dummy(options);
				}).fail(function (response) {
					self.molla_import_dummy(options);
				});
			} else {
				self.molla_import_dummy(options);
			}
		},
		// import dummy content
		molla_import_dummy: function (options) {
			if (!options.demo) {
				self.removeAlertLeavePage();
				return;
			}
			if (options.import_dummy) {
				var demo = options.demo,
					data = { 'action': options.dummy_action, 'process': 'import_start', 'demo': demo, 'override_contents': options.override_contents, 'wpnonce': molla_setup_wizard_params.wpnonce };
				dummy_index = 0;
				dummy_count = 0;
				dummy_process = 'import_start';
				self.molla_import_dummy_process(options, data);
				self.showImportMessage(demo, 'Importing posts');
			} else {
				self.molla_import_widgets(options);
			}
		},
		// import dummy content process
		molla_import_dummy_process: function (options, args) {
			var demo = options.demo;
			$.post(ajaxurl, args, function (response) {
				if (response && /^[\],:{}\s]*$/.test(response.replace(/\\["\\\/bfnrtu]/g, '@').
					replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
					replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
					response = $.parseJSON(response);
					if (response.process != 'complete') {
						var requests = { 'action': args.action, 'wpnonce': molla_setup_wizard_params.wpnonce };
						if (response.process) requests.process = response.process;
						if (response.index) requests.index = response.index;

						requests.demo = demo;
						requests.override_contents = options.override_contents;
						self.molla_import_dummy_process(options, requests);

						dummy_index = response.index;
						dummy_count = response.count;
						dummy_process = response.process;

						self.showImportMessage(demo, response.message, dummy_count, dummy_index);
					} else {
						self.showImportMessage(demo, response.message);
						self.molla_import_widgets(options);
					}
				} else {
					self.molla_import_failed(demo);
				}
			}).fail(function (response) {
				if (args.action == 'molla_import_dummy') {
					self.molla_import_failed(demo);
				} else {
					var requests;
					if (dummy_index < dummy_count) {
						requests = { 'action': args.action, 'wpnonce': molla_setup_wizard_params.wpnonce };
						requests.process = dummy_process;
						requests.index = ++dummy_index;
						requests.demo = demo;

						self.molla_import_dummy_process(options, requests);
					} else {
						requests = { 'action': args.action, 'wpnonce': molla_setup_wizard_params.wpnonce };
						requests.process = dummy_process;
						requests.demo = demo;

						self.molla_import_dummy_process(options, requests);
					}
				}
			});
		},
		// import widgets
		molla_import_widgets: function (options) {
			if (!options.demo) {
				self.removeAlertLeavePage();
				return;
			}
			if (options.import_widgets) {
				var demo = options.demo,
					data = { 'action': 'molla_import_widgets', 'demo': demo, 'wpnonce': molla_setup_wizard_params.wpnonce };

				self.showImportMessage(demo, 'Importing widgets');

				$.post(ajaxurl, data, function (response) {
					if (response) {
						self.molla_import_finished(options);
					}
				});
			} else {
				self.molla_import_finished(options);
			}
		},
		molla_delete_tmp_dir: function (demo) {
			var data = { 'action': 'molla_delete_tmp_dir', 'demo': demo, 'wpnonce': molla_setup_wizard_params.wpnonce };
			$.post(ajaxurl, data, function (response) {
			});
		},
		// import failed
		molla_import_failed: function (demo, message) {
			self.molla_delete_tmp_dir(demo);
			if (typeof message == 'undefined') {
				self.showImportMessage(demo, 'Failed importing! Please check the <a href="' + window.location.href.replace('&step=demo_content', '&step=status') + '" target="_blank">"System Status"</a> tab to ensure your server meets all requirements for a successful import. Settings that need attention will be listed in red. If your server provider does not allow to update settings, please try using alternative import mode.');
			} else {
				self.showImportMessage(demo, message);
			}
			self.removeAlertLeavePage();
			$('.molla-install-demo .molla-demo-install .molla-ajax-loader').remove();
			$('.molla-install-demo .molla-demo-install').html($("#molla-install-options .theme-name").text() + ' installation is failed!');
			$('.molla-install-demo .molla-demo-install').css('padding-left', 0);
			$('#molla-install-options').show();
		},
		// import finished
		molla_import_finished: function (options) {
			if (!options.demo) {
				removeAlertLeavePage();
				return;
			}
			var demo = options.demo;
			self.molla_delete_tmp_dir(demo);
			setTimeout(function () {
				if ($('#wp-admin-bar-view-site').length) {
					self.showImportMessage(demo, '<a href="' + $('#wp-admin-bar-view-site a').attr('href') + '" target="_blank">Visit your site.</a>');
				} else if ($('#current_site_url').length) {
					self.showImportMessage(demo, '<a href="' + $('#current_site_url').val() + '" target="_blank">Visit your site.</a>');
				} else {
					self.showImportMessage(demo, '');
				}
				$('.molla-install-demo .molla-demo-install .molla-ajax-loader').remove();
				$('.molla-install-demo .molla-demo-install').html($('#' + demo).html() + ' installation is finished!');
				$('.molla-install-demo .molla-demo-install').css('padding-left', 0);
				self.removeAlertLeavePage();
			}, 3000);
		},
		molla_load_more_plugins: function () {
			$('body').on('click', '.button-load-plugins', function (e) {
				e.preventDefault();
				$(this).closest('.molla-setup-wizard-plugins').children('.hidden').hide();
				$(this).closest('.molla-setup-wizard-plugins').children('.hidden').fadeIn();
				$(this).closest('.molla-setup-wizard-plugins').children('.hidden').removeClass('hidden');
				$(this).hide();
			});
		}
	};

	jQuery(document).ready(function ($) {
		'use strict';
		MollaWizardAdmin.init();
	});

})(jQuery);


var MollaWizard = (function ($) {

	var t;

	var callbacks = {
		install_plugins: function (btn) {
			var plugins = new PluginManager();
			plugins.init(btn);
		},
		remove_widgets: function (btn) {
			var widgets = new UnusedWidgetManager();
			widgets.init(btn);
		}
	};

	function window_loaded() {
		// init button clicks:
		$('.button-next').on('click', function (e) {
			var loading_button = wizard_step_loading_button(this);
			if (!loading_button) {
				return false;
			}
			if ($(this).data('callback') && typeof callbacks[$(this).data('callback')] != 'undefined') {
				// we have to process a callback before continue with form submission
				callbacks[$(this).data('callback')](this);
				return false;
			} else {
				loading_content();
				return true;
			}
		});
		$('.button-upload').on('click', function (e) {
			e.preventDefault();
			renderMediaUploader();
		});
		$('.theme-presets a').on('click', function (e) {
			e.preventDefault();
			var $ul = $(this).parents('ul').first();
			$ul.find('.current').removeClass('current');
			var $li = $(this).parents('li').first();
			$li.addClass('current');
			var newcolor = $(this).data('style');
			$('#new_style').val(newcolor);
			return false;
		});
		$('.molla-unused-widgets .molla-page').on('click', function (e) {
			var $parent = $(this).parent();
			if ($parent.hasClass('active')) {
				$parent.removeClass('active');
				$parent.children('ul').slideUp(300);
			} else {
				$parent.addClass('active');
				$parent.children('ul').slideDown(300);
			}
		})
		$('.molla-unused-widgets .molla-page .checkbox-toggle').on('click', function (e) {
			if ($(this).find('.toggle').hasClass('none')) {
				$(this).find('.toggle').removeClass('none').addClass('all');
				$(this).closest('.molla-unused-widgets').find('ul input.widget').prop('checked', true);
			} else {
				$(this).find('.toggle').removeClass('all').addClass('none');
				$(this).closest('.molla-unused-widgets').find('ul input.widget').prop('checked', false);
			}
			e.stopImmediatePropagation();
		})
		$('.molla-unused-widgets ul label.checkbox').on('click', function (e) {
			var checked_count = $(this).closest('.molla-unused-widgets').find('ul input.widget:checked').length,
				count = $(this).closest('.molla-unused-widgets').find('ul input.widget').length;

			if (count == checked_count) {
				$(this).closest('.molla-unused-widgets').find('.toggle').removeClass('none').addClass('all');
			} else if (0 == checked_count) {
				$(this).closest('.molla-unused-widgets').find('.toggle').removeClass('all').addClass('none');
			} else {
				$(this).closest('.molla-unused-widgets').find('.toggle').removeClass('all none');
			}
		})
		$('.molla_submit_form label input').on('click', function (e) {
			$('.molla_submit_form .step').find('[name="' + $(this).attr('name') + '"]').attr('value', $(this).prop('checked'));
		})
	}

	function loading_content() {
		$('.envato-setup-content').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
	}

	function PluginManager() {

		var complete;
		var items_completed = 0;
		var current_item = '';
		var $current_node;
		var current_item_hash = '';

		function ajax_callback(response) {
			if (typeof response == 'object' && typeof response.message != 'undefined') {
				$current_node.find('span').text(response.message);
				if (typeof response.url != 'undefined') {
					// we have an ajax url action to perform.

					if (response.hash == current_item_hash) {
						$current_node.find('span').text("failed");
						find_next();
					} else {
						current_item_hash = response.hash;
						$.post(response.url, response, function (response2) {
							process_current();
							$current_node.find('span').text(response.message);
						}).fail(ajax_callback);
					}

				} else if (typeof response.done != 'undefined') {
					find_next();
				} else {
					find_next();
				}
			} else {
				$current_node.find('span').text("ajax error");
				find_next();
			}
		}
		function process_current() {
			if (current_item) {
				$.post(ajaxurl, {
					action: typeof molla_setup_wizard_params == 'undefined' ? 'molla_speed_optimize_wizard_plugins' : 'molla_setup_wizard_plugins',
					wpnonce: typeof molla_setup_wizard_params == 'undefined' ? molla_speed_optimize_wizard_params.wpnonce : molla_setup_wizard_params.wpnonce,
					slug: current_item
				}, ajax_callback).fail(ajax_callback);
			}
		}
		function find_next() {
			var do_next = false;
			if ($current_node) {
				if (!$current_node.data('done_item')) {
					items_completed++;
					$current_node.data('done_item', 1);
				}
				$current_node.find('.spinner').css('visibility', 'hidden');
			}
			var $li = $('.molla-setup-wizard-plugins li');
			$li.each(function () {
				if ($(this).hasClass('installing')) {
					if (current_item == '' || do_next) {
						current_item = $(this).data('slug');
						$current_node = $(this);
						process_current();
						do_next = false;
					} else if ($(this).data('slug') == current_item) {
						do_next = true;
					}
				}
			});
			if (items_completed >= $('.molla-setup-wizard-plugins li.installing').length) {
				complete();
			}
		}

		return {
			init: function (btn) {
				$('.molla-setup-wizard-plugins > li').each(function () {
					if ($(this).find('input[type="checkbox"]').is(':checked')) {
						$(this).addClass('installing');
					}
				});
				complete = function () {
					loading_content();
					if ($(btn).attr('href') && '#' != $(btn).attr('href')) {
						window.location.href = btn.href;
					} else {
						window.location.reload();
					}
				};
				find_next();
			}
		}
	}

	function UnusedWidgetManager() {
		var success = true;

		function ajax_callback(response) {
			$('.molla-unused-widgets#' + response.id + ' .molla-page').removeClass('installing');
			$('.molla-unused-widgets#' + response.id + ' .spinner').css('visibility', '');

			if (typeof response == 'object' && typeof response.message != 'undefined' && response.done) {
				$('.molla-unused-widgets#' + response.id + ' .result').text('Successfully optimized widgets').css('color', '#6b8fd2');
			} else {
				$('.molla-unused-widgets#' + response.id + ' .result').text('Error occurs while optimizing css file').css('color', '#ef5858');
				success = false;
			}

			find_next();
		}

		function save_page_style($page) {
			var unused = [];

			$page.find('ul input.widget').each(function () {
				if ($(this).prop("checked")) {
					unused.push($(this).attr('name'));
				}
			})

			$.ajax({
				url: ajaxurl,
				data: {
					action: 'molla_speed_wizard_remove_widgets',
					wpnonce: molla_setup_wizard_params.wpnonce,
					id: $page.attr('id'),
					unused: unused,
				},
				type: 'post',
				success: ajax_callback,
			}).fail(ajax_callback);
		}

		function find_next() {
			var $page = $('.molla-unused-widgets > .molla-page.installing').eq(0);

			if ($page.length) {
				save_page_style($page.closest('.molla-unused-widgets'));
			} else if (success) {
				$('body').trigger('unused_widgets_removed');
				if (location.href.indexOf('molla_demo_id') == -1)
					complete();
			}
		}

		return {
			init: function (btn) {
				$('.molla-unused-widgets > ul').slideUp(300);
				$('.molla-unused-widgets > .molla-page').addClass('installing');
				$('.molla-unused-widgets .spinner').css('visibility', 'visible');
				complete = function () {
					loading_content();
					if ($(btn).attr('href') && '#' != $(btn).attr('href')) {
						window.location.href = btn.href;
					} else {
						window.location.reload();
					}
				};
				find_next();
			}
		}
	}

	function renderMediaUploader() {
		'use strict';

		var file_frame, attachment;

		if (undefined !== file_frame) {
			file_frame.open();
			return;
		}
		file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Upload Logo',
			button: {
				text: 'Select Logo'
			},
			multiple: false
		});

		file_frame.on('select', function () {
			attachment = file_frame.state().get('selection').first().toJSON();
			$('.site-logo').attr('src', attachment.url);
			$('#new_logo_id').val(attachment.id);
		});
		file_frame.open();
	}

	function wizard_step_loading_button(btn) {
		var $button = $(btn);
		if ($button.data('done-loading') == 'yes') return false;
		var existing_text = $button.text();
		var existing_width = $button.outerWidth();
		var loading_text = '⡀⡀⡀⡀⡀⡀⡀⡀⡀⡀⠄⠂⠁⠁⠂⠄';
		var completed = false;

		$button.css('width', existing_width);
		$button.addClass('wizard_step_loading_button_current');
		var _modifier = $button.is('input') || $button.is('button') ? 'val' : 'text';
		$button[_modifier](loading_text);
		$button.data('done-loading', 'yes');

		var anim_index = [0, 1, 2];

		// animate the text indent
		function moo() {
			if (completed) return;
			var current_text = '';
			// increase each index up to the loading length
			for (var i = 0; i < anim_index.length; i++) {
				anim_index[i] = anim_index[i] + 1;
				if (anim_index[i] >= loading_text.length) anim_index[i] = 0;
				current_text += loading_text.charAt(anim_index[i]);
			}
			$button[_modifier](current_text);
			setTimeout(function () { moo(); }, 60);
		}

		moo();

		return {
			done: function () {
				completed = true;
				$button[_modifier](existing_text);
				$button.removeClass('wizard_step_loading_button_current');
				$button.attr('disabled', false);
			}
		}

	}

	return {
		init: function () {
			$(window_loaded);
		}
	}

})(jQuery);

MollaWizard.init();