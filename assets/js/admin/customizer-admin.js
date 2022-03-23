(function (api, wp, $) {
    'use strict';
    $(document).ready(function () {

        $('.customize-pane-child .accordion-section-title .panel-title, .customize-pane-child .customize-section-title h3').append('<a href="#" class="section-nav-status" title="Customize Navigator"><i class="far fa-star"></i></a>');

        $('.customizer-nav-item').each(function () {
            if ('section' == $(this).data('type')) {
                $('#sub-accordion-section-' + $(this).data('target') + ' .section-nav-status').addClass('active');
            } else {
                $('#sub-accordion-panel-' + $(this).data('target') + ' .section-nav-status').addClass('active');
            }
        })
        // Navigator
        $(document.body).on('click', '.navigator-toggle', function (e) {
            e.preventDefault();
            $(this).closest('.customizer-nav').toggleClass('active');
        })

        $(document.body).on('click', '.customizer-nav-item', function (e) {
            e.preventDefault();
            window.parent.wp.customize[$(this).data('type')]($(this).data('target')).focus();
        })

        $(document.body).on('click', '.section-nav-status', function (e) {
            e.preventDefault();
        })

        $(document.body).on('click', '.accordion-section-title', function (e) {
        })

        $(document.body).on('click', '.section-nav-status:not(.disabled)', function (e) {
            var title = '',
                target = '',
                type = '';

            var pane = $(this).closest('.customize-pane-child');
            if (pane.hasClass('control-panel')) {
                target = pane[0].id.replace('sub-accordion-panel-', '');
                type = 'panel';
            } else {
                target = pane[0].id.replace('sub-accordion-section-', '');
                type = 'section';
            }
            $(this).addClass('disabled');
            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                if ($(this).closest('.customize-section-title').length) {
                    var section = $(this).closest('.customize-section-title'),
                        parent = section.find('.customize-action').text(),
                        current = section.find('h3').text().replace(parent, '');
                    var split_pos = parent.indexOf('▸');
                    if (-1 != split_pos) {
                        parent = parent.slice(split_pos + 1);
                    } else {
                        parent = '';
                    }
                } else {
                    parent = '';
                    var current = $(this).closest('.panel-title').text();
                }
                if (parent) {
                    parent = parent + ' / ';
                }
                title = parent + current;

                $('.customizer-nav-items').append('<li><a href="#" data-target="' + target + '" data-type="' + type + '" class="customizer-nav-item">' + title + '</a><a href="#" class="customizer-nav-remove"><i class="fas fa-trash"></i></a></li>');
            } else {
                $('.customizer-nav-items .customizer-nav-item[data-target="' + target + '"]').parent().fadeOut(200).addClass('hidden');
            }
            $(this).removeClass('disabled');

            var saved = wp.customize.state('saved').get();
            if (saved) {
                wp.customize.state('saved').set(false);
            }
        })

        $(document.body).on('click', '.customizer-nav-remove', function (e) {
            e.preventDefault();
            var li = $(this).closest('li'),
                item = li.children('.customizer-nav-item');
            li.fadeOut(200).addClass('hidden');

            if ('section' == item.data('type')) {
                $('#sub-accordion-section-' + item.data('target') + ' .section-nav-status').removeClass('active');
            } else {
                $('#sub-accordion-panel-' + item.data('target') + ' .section-nav-status').removeClass('active');
            }

            var saved = wp.customize.state('saved').get();
            if (saved) {
                wp.customize.state('saved').set(false);
            }
        })

        $('#customize-save-button-wrapper #save').on('click', function () {
            var navs = {};
            $('.customizer-nav-items li:not(.hidden) .customizer-nav-item').each(function () {
                navs[$(this).data('target')] = [$(this).text(), $(this).data('type')];
            })
            $.ajax({
                url: customizer_admin_vars.ajax_url,
                data: { wp_customize: 'on', action: 'molla_save_customize_nav', nonce: customizer_admin_vars.nonce, navs: navs },
                type: 'post',
                dataType: 'json',
                success: function (response) {
                }
            });
        })

        var hb_controls = '#customize-control-molla_header_custom_menu_id,' +
            '#customize-control-molla_header_custom_menu_type,' +
            '#customize-control-molla_header_toggle_menu_title,' +
            '#customize-control-molla_header_toggle_menu_link,' +
            '#customize-control-molla_header_custom_menu_toggle_event,' +
            '#customize-control-molla_header_toggle_menu_show_icon,' +
            '#customize-control-molla_header_toggle_menu_icon,' +
            '#customize-control-molla_header_toggle_menu_active_icon,' +
            '#customize-control-molla_header_toggle_menu_icon_pos,' +
            '#customize-control-molla_header_layouts_menu_skin,' +
            '#customize-control-molla_header_layouts_block_element,' +
            '#customize-control-molla_header_layouts_html_element,' +
            '#customize-control-molla_header_layouts_el_class,' +
            '#customize-control-molla_header_layouts_save_html_button';
        var preset = false,
            custom_css = '',
            elements = '';

        function generateElementsArray($parent) {
            var result = [];
            $parent.children('span').each(function () {
                var $this = $(this);
                if ($this.hasClass('element-cont')) {
                    var subResult = generateElementsArray($(this));
                    if (Array.isArray(subResult) && subResult.length > 0) {
                        result.push(subResult);
                    }
                } else {
                    var obj = {}, meta = '';
                    if ($this.data('html') || $this.data('el_class')) {
                        meta = {};
                    }
                    if ($this.data('html')) {
                        meta.html = $this.data('html');
                    }
                    if (typeof $this.data('el_class') != 'undefined') {
                        meta.el_class = $this.data('el_class');
                    }
                    if (typeof $this.data('menu_type') != 'undefined') {
                        meta.menu_type = $this.data('menu_type');
                    }
                    if (typeof $this.data('menu_title') != 'undefined') {
                        meta.menu_title = $this.data('menu_title');
                    }
                    if (typeof $this.data('menu_link') != 'undefined') {
                        meta.menu_link = $this.data('menu_link');
                    }
                    if (typeof $this.data('menu_active_event') != 'undefined') {
                        meta.menu_active_event = $this.data('menu_active_event');
                    }
                    if (typeof $this.data('menu_show_icon') != 'undefined') {
                        meta.menu_show_icon = $this.data('menu_show_icon');
                    }
                    if (typeof $this.data('menu_icon') != 'undefined') {
                        meta.menu_icon = $this.data('menu_icon');
                    }
                    if (typeof $this.data('menu_active_icon') != 'undefined') {
                        meta.menu_active_icon = $this.data('menu_active_icon');
                    }
                    if (typeof $this.data('menu_icon_pos') != 'undefined') {
                        meta.menu_icon_pos = $this.data('menu_icon_pos');
                    }
                    if (typeof $this.data('menu_skin') != 'undefined') {
                        meta.menu_skin = $this.data('menu_skin');
                    }
                    obj[$this.data('id')] = meta;
                    result.push(obj);
                }
            });
            return result;
        }
        function generateElementsFromArray(itemArr, itemId, $parent, screen) {
            if (typeof $parent == 'string') {
                $parent = '.header-wrapper-' + $parent + ' [data-id="' + itemId + '"]';
            }
            $.each(itemArr, function (index, value) {
                if (Array.isArray(value)) { // row element
                    var parentObj = $('.molla-' + screen + '-visible span[data-id="row"]').clone().addClass('molla-drop-item' + ('sm' == screen ? '-mobile' : ''));
                    parentObj.children('b').remove();
                    parentObj.appendTo($parent);
                    generateElementsFromArray(value, itemId, parentObj, screen);
                } else {
                    $.each(value, function (key, html) {
                        var $obj;
                        if ($('.molla-' + screen + '-visible span[data-id="' + key + '"]').hasClass('element-infinite')) {
                            $obj = $('.molla-' + screen + '-visible span[data-id="' + key + '"]').clone().appendTo($parent);
                        } else {
                            $obj = $('.molla-' + screen + '-visible span[data-id="' + key + '"]').appendTo($parent);
                        }
                        if (html) {
                            if (typeof html == 'string') {
                                $obj.data('html', html);
                            } else {
                                if (html.html) {
                                    $obj.data('html', html.html);
                                }
                                if (typeof html.el_class != 'undefined') {
                                    $obj.data('el_class', html.el_class);
                                }
                                if (typeof html.menu_type != 'undefined') {
                                    $obj.data('menu_type', html.menu_type);
                                }
                                if (typeof html.menu_title != 'undefined') {
                                    $obj.data('menu_title', html.menu_title);
                                }
                                if (typeof html.menu_link != 'undefined') {
                                    $obj.data('menu_link', html.menu_link);
                                }
                                if (typeof html.menu_active_event != 'undefined') {
                                    $obj.data('menu_active_event', html.menu_active_event);
                                }
                                if (typeof html.menu_show_icon != 'undefined') {
                                    $obj.data('menu_show_icon', html.menu_show_icon);
                                }
                                if (typeof html.menu_icon != 'undefined') {
                                    $obj.data('menu_icon', html.menu_icon);
                                }
                                if (typeof html.menu_active_icon != 'undefined') {
                                    $obj.data('menu_active_icon', html.menu_active_icon);
                                }
                                if (typeof html.menu_icon_pos != 'undefined') {
                                    $obj.data('menu_icon_pos', html.menu_icon_pos);
                                }
                                if (typeof html.menu_skin != 'undefined') {
                                    $obj.data('menu_skin', html.menu_skin);
                                }
                            }
                        }
                    });
                }
            });
        }
        function setItem(item, connectWith) {
            if (item) {
                var arr = generateElementsArray($('.' + connectWith + '[data-id="' + item + '"]'));
                wp.customize.instance(item).set(JSON.stringify(arr));
            }
        }
        function itemSortable($element, connectWithClass) {
            $element.sortable({
                connectWith: '.' + connectWithClass,
                update: function (event, ui) {
                    if ($(ui.item).hasClass('element-cont')) {
                        $(ui.item).contents().filter(function () {
                            return this.nodeType === 3;
                        }).remove();
                        $(ui.item).addClass(connectWithClass).children('b').remove();
                        itemSortable($(ui.item), connectWithClass);
                    }
                    setItem($(ui.item).closest('div').data('id'), connectWithClass);
                    if (!$(ui.sender).hasClass('element-cont')) {
                        setItem($(ui.sender).data('id'), connectWithClass);
                    }
                },
                start: function (event, ui) {
                    if ($(ui.item).hasClass('element-cont')) {
                        var obj = $(ui.item).closest('.' + connectWithClass),
                            objData = obj.data('ui-sortable'),
                            objContainers = objData.containers,
                            flag = false;

                        $.each(objContainers, function (index, cont) {
                            if (typeof cont != 'undefined' && $(cont.element).is($(ui.item))) {
                                objContainers.splice(index, 1);
                                flag = true;
                            }
                        });
                        if (flag) {
                            objData.containers = objContainers;
                            obj.data('ui-sortable', objData);
                        }
                    }
                    $(ui.item).hasClass('element-infinite') && $(ui.item).closest('.molla-header-builder-list').length && $(ui.item).clone().removeAttr('style').insertAfter($(ui.item));
                },
                stop: function (event, ui) {
                    $(ui.item).hasClass('element-infinite') && $(ui.item).closest('.molla-header-builder-list').length && $(ui.item).remove();
                }
            }).disableSelection();
        }
        function initHeaderLayout() {
            $('.molla-drop-item').each(function () {
                var itemId = $(this).data('id');
                if (itemId && wp.customize.instance(itemId) && wp.customize.instance(itemId).get()) {
                    var itemArr = $.parseJSON(wp.customize.instance(itemId).get());
                    generateElementsFromArray(itemArr, itemId, 'desktop', 'lg');
                }
            });
            $('.molla-drop-item-mobile').each(function () {
                var itemId = $(this).data('id');
                if (itemId && wp.customize.instance(itemId) && wp.customize.instance(itemId).get()) {
                    var itemArr = $.parseJSON(wp.customize.instance(itemId).get());
                    generateElementsFromArray(itemArr, itemId, 'mobile', 'sm');
                }
            });
            itemSortable($('.molla-drop-item'), 'molla-drop-item');
            itemSortable($('.molla-drop-item-mobile'), 'molla-drop-item-mobile');
        }

        function resetHeaderBuilderElements(response) {
            $.each(wp.customize.section('molla_header_builder').controls(), function (key, control) {
                wp.customize.instance(control.settings.default.id).set('');
            });
            $('.molla-header-builder .header-builder-wrapper .molla-drop-item span:not(.element-infinite)').insertBefore($('.header-wrapper-desktop .molla-header-builder-list span.element-infinite').first());
            $('.molla-header-builder .header-builder-wrapper .molla-drop-item-mobile span:not(.element-infinite)').insertBefore($('.header-wrapper-mobile .molla-header-builder-list span.element-infinite').first());
            $('.molla-header-builder .header-builder-wrapper .molla-drop-item, .molla-header-builder .header-builder-wrapper .molla-drop-item-mobile').html('');
            if (response.elements) {
                $.each(response.elements, function (key, value) {
                    value && 'custom_css' != key && 'undefined' != typeof wp.customize.instance('hb_options[' + key + ']') && wp.customize.instance('hb_options[' + key + ']').set(value);
                });
            }
            initHeaderLayout();
            if (response.custom_css) {
                wp.customize.instance('molla_header_builder[custom_css]').set(response.custom_css);
            } else {
                wp.customize.instance('molla_header_builder[custom_css]').set('');
            }
            if (response.preset) {
                $('.molla_header_presets [data-preset="' + response.preset + '"]').addClass('active');
            }
        }

        initHeaderLayout();
        wp.customize.panel('header') && wp.customize.panel('header').expanded.bind(function (t) {
            if (!t) {
                $('.molla-header-builder').removeClass('active in-header-panel');
            } else {
                $('.header-builder-header .button-close').html('Close');
                $('.molla-header-builder').addClass('active in-header-panel');
            }
            var selected = wp.customize.instance('molla_header_builder[selected_layout]').get();
            if (!selected) {
                $('.molla-header-builder').removeClass('active');
                $('.header-builder-header .button-close').hide();
            } else {
                $.ajax({
                    url: customizer_admin_vars.ajax_url,
                    data: { wp_customize: 'on', action: 'molla_load_header_elements', nonce: customizer_admin_vars.nonce, header_layout: selected },
                    type: 'post',
                    dataType: 'json',
                    success: function (response) {
                        if (!response) {
                            response = { preset: '' };
                        }
                        if (response.preset) {
                            preset = response.preset;
                        }
                        elements = response.elements;
                        custom_css = response.custom_css;
                    }
                });
            }

        });

        $('body').on('change', '#customize-control-molla_header_builder-selected_layout select', function () {
            activeSettingObject = null;
            if ($(this).val()) {
                if ('default' == $(this).val()) {
                    $('#customize-control-molla_delete_header_type_link').hide();
                } else {
                    $('#customize-control-molla_delete_header_type_link').show();
                }
                var $this = $(this);
                $('.molla-header-builder').addClass('active');
                $('.header-builder-header .button-close').show();
                $('.molla_delete_header_type_link, #customize-control-molla_header_builder-custom_css').show();
                $this.attr('disabled', 'disabled');
                $.ajax({
                    url: customizer_admin_vars.ajax_url,
                    data: { wp_customize: 'on', action: 'molla_load_header_elements', nonce: customizer_admin_vars.nonce, header_layout: $this.val() },
                    type: 'post',
                    dataType: 'json',
                    success: function (response) {
                        if (!response) {
                            response = { preset: '' };
                        }
                        $('.molla_header_presets .img-select.active').removeClass('active');
                        wp.customize.instance('molla_header_builder[preset]').set(response.preset);
                        if (response.preset) {
                            preset = response.preset;
                        } else {
                            preset = false;
                        }
                        elements = response.elements;
                        custom_css = response.custom_css;
                        resetHeaderBuilderElements(response);
                        $this.removeAttr('disabled');
                    }
                });
            } else {
                var header_elements = [
                    'top_left',
                    'top_center',
                    'top_right',
                    'main_left',
                    'main_center',
                    'main_right',
                    'bottom_left',
                    'bottom_center',
                    'bottom_right',
                    'mobile_top_left',
                    'mobile_top_center',
                    'mobile_top_right',
                    'mobile_main_left',
                    'mobile_main_center',
                    'mobile_main_right',
                    'mobile_bottom_left',
                    'mobile_bottom_center',
                    'mobile_bottom_right',
                ];
                for (var i = 0; i < header_elements.length; i++) {
                    wp.customize.instance('hb_options[' + header_elements[i] + ']').set('');
                }
                $('.molla-header-builder').removeClass('active');
                $('.header-builder-header .button-close').hide();
                $('.molla_delete_header_type_link, #customize-control-molla_header_builder-custom_css').hide();
            }
        });

        $('#customize-control-molla_header_custom_menu_type input').on('change', function () {
            if ($(this).is(':checked')) {
                $('#customize-control-molla_header_toggle_menu_title').show();
                $('#customize-control-molla_header_toggle_menu_link').show();
                $('#customize-control-molla_header_custom_menu_toggle_event').show();
                $('#customize-control-molla_header_toggle_menu_show_icon').show();
            } else {
                $('#customize-control-molla_header_toggle_menu_title').hide();
                $('#customize-control-molla_header_toggle_menu_link').hide();
                $('#customize-control-molla_header_custom_menu_toggle_event').hide();
                $('#customize-control-molla_header_toggle_menu_show_icon').hide();
            }
            if (!$(this).is(':checked') || !$('#customize-control-molla_header_toggle_menu_show_icon input').is(':checked')) {
                $('#customize-control-molla_header_toggle_menu_icon').hide();
                $('#customize-control-molla_header_toggle_menu_active_icon').hide();
                $('#customize-control-molla_header_toggle_menu_icon_pos').hide();
            } else {
                $('#customize-control-molla_header_toggle_menu_icon').show();
                $('#customize-control-molla_header_toggle_menu_active_icon').show();
                $('#customize-control-molla_header_toggle_menu_icon_pos').show();
            }
        })
        $('#customize-control-molla_header_toggle_menu_show_icon input').on('change', function () {
            if ($(this).is(':checked')) {
                $('#customize-control-molla_header_toggle_menu_icon').show();
                $('#customize-control-molla_header_toggle_menu_active_icon').show();
                $('#customize-control-molla_header_toggle_menu_icon_pos').show();
            } else {
                $('#customize-control-molla_header_toggle_menu_icon').hide();
                $('#customize-control-molla_header_toggle_menu_active_icon').hide();
                $('#customize-control-molla_header_toggle_menu_icon_pos').hide();
            }
        })

        var activeSettingObject = null;
        $(document.body).on('click', '.molla-header-builder .header-builder-wrapper [data-id="html"], .molla-header-builder .header-builder-wrapper [data-id="molla_block"], .molla-header-builder .header-builder-wrapper [data-id="custom_menu"]', function (e) {
            var sameObject = activeSettingObject == null || !activeSettingObject.is($(this)) ? false : true,
                $this = $(this);
            if ($this.data('id') == 'html') {
                if (!sameObject || $('#customize-control-molla_header_layouts_html_element').is(':hidden')) {
                    $('#customize-control-molla_header_layouts_block_element').hide();
                    $('#customize-control-molla_header_layouts_html_element, #customize-control-molla_header_layouts_el_class, #customize-control-molla_header_layouts_save_html_button').show();
                    $('#customize-control-molla_header_layouts_html_element textarea').focus();
                    if ($this.data('html')) {
                        $('#customize-control-molla_header_layouts_html_element textarea').val($this.data('html'));
                    } else {
                        $('#customize-control-molla_header_layouts_html_element textarea').val('');
                    }
                } else if ($('#customize-control-molla_header_layouts_html_element').is(':visible') && $('#sub-accordion-section-header_builder').hasClass('open')) {
                    $(hb_controls).hide();
                }
            } else if ($this.data('id') == 'molla_block') {
                if (!sameObject || $('#customize-control-molla_header_layouts_block_element').is(':hidden')) {
                    $('#customize-control-molla_header_layouts_block_element, #customize-control-molla_header_layouts_save_html_button').show();
                    if ($this.data('html')) {
                        $('#customize-control-molla_header_layouts_block_element input').val(escape($this.data('html')));
                    }
                } else if ($('#customize-control-molla_header_layouts_block_element').is(':visible')) {
                    $(hb_controls).hide();
                }
            } else if ($this.data('id') == 'custom_menu') {
                if (!sameObject || $('#customize-control-molla_header_custom_menu_id').is(':hidden')) {
                    var hb_custom_menu_controls = '#customize-control-molla_header_custom_menu_id,' +
                        '#customize-control-molla_header_custom_menu_type,' +
                        '#customize-control-molla_header_toggle_menu_title,' +
                        '#customize-control-molla_header_toggle_menu_link,' +
                        '#customize-control-molla_header_custom_menu_toggle_event,' +
                        '#customize-control-molla_header_toggle_menu_show_icon,' +
                        '#customize-control-molla_header_toggle_menu_icon,' +
                        '#customize-control-molla_header_toggle_menu_active_icon,' +
                        '#customize-control-molla_header_toggle_menu_icon_pos,' +
                        '#customize-control-molla_header_layouts_menu_skin,' +
                        '#customize-control-molla_header_layouts_save_html_button';
                    $(hb_custom_menu_controls).show();
                    if ($this.data('html')) {
                        $('#customize-control-molla_header_custom_menu_id select').val(escape($this.data('html')));
                    } else {
                        $('#customize-control-molla_header_custom_menu_id select').val(0);
                    }
                    if ($this.data('menu_type')) {
                        $('#customize-control-molla_header_custom_menu_type input').prop('checked', $this.data('menu_type'));
                    } else {
                        $('#customize-control-molla_header_custom_menu_type input').prop('checked', false);
                    }
                    if ($this.data('menu_title')) {
                        $('#customize-control-molla_header_toggle_menu_title input').val($this.data('menu_title'));
                    } else {
                        $('#customize-control-molla_header_toggle_menu_title input').val('');
                    }
                    if ($this.data('menu_link')) {
                        $('#customize-control-molla_header_toggle_menu_link input').val($this.data('menu_link'));
                    } else {
                        $('#customize-control-molla_header_toggle_menu_link input').val('');
                    }
                    if ($this.data('menu_active_event')) {
                        $('#customize-control-molla_header_custom_menu_toggle_event select').val($this.data('menu_active_event'));
                    } else {
                        $('#customize-control-molla_header_custom_menu_toggle_event select').val('hover');
                    }
                    if ($this.data('menu_show_icon')) {
                        $('#customize-control-molla_header_toggle_menu_show_icon input').prop('checked', $this.data('menu_show_icon'));
                    } else {
                        $('#customize-control-molla_header_toggle_menu_show_icon input').prop('checked', false);
                    }
                    if ($this.data('menu_icon')) {
                        $('#customize-control-molla_header_toggle_menu_icon input').val($this.data('menu_icon'));
                    } else {
                        $('#customize-control-molla_header_toggle_menu_icon input').val('icon-bars');
                    }
                    if ($this.data('menu_active_icon')) {
                        $('#customize-control-molla_header_toggle_menu_active_icon input').val($this.data('menu_active_icon'));
                    } else {
                        $('#customize-control-molla_header_toggle_menu_active_icon input').val('icon-close');
                    }
                    if ($this.data('menu_icon_pos')) {
                        $('#customize-control-molla_header_toggle_menu_icon_pos select').val($this.data('menu_icon_pos'));
                    } else {
                        $('#customize-control-molla_header_toggle_menu_icon_pos select').val('left');
                    }
                    if ($this.data('menu_skin')) {
                        $('#customize-control-molla_header_layouts_menu_skin select').val($this.data('menu_skin'));
                    } else {
                        $('#customize-control-molla_header_layouts_menu_skin select').val('skin-1');
                    }
                } else if ($('#customize-control-molla_header_custom_menu_id').is(':visible')) {
                    $(hb_controls).hide();
                }
                if (!$('#customize-control-molla_header_custom_menu_type input').is(':checked')) {
                    $('#customize-control-molla_header_toggle_menu_title').hide();
                    $('#customize-control-molla_header_toggle_menu_link').hide();
                    $('#customize-control-molla_header_custom_menu_toggle_event').hide();
                    $('#customize-control-molla_header_toggle_menu_show_icon').hide();
                    $('#customize-control-molla_header_toggle_menu_icon').hide();
                    $('#customize-control-molla_header_toggle_menu_active_icon').hide();
                    $('#customize-control-molla_header_toggle_menu_icon_pos').hide();
                }
                if (!$('#customize-control-molla_header_toggle_menu_show_icon input').is(':checked')) {
                    $('#customize-control-molla_header_toggle_menu_icon').hide();
                    $('#customize-control-molla_header_toggle_menu_active_icon').hide();
                    $('#customize-control-molla_header_toggle_menu_icon_pos').hide();
                }
            }
            if ($this.data('el_class')) {
                $('#customize-control-molla_header_layouts_el_class input').val($this.data('el_class'));
            } else {
                $('#customize-control-molla_header_layouts_el_class input').val('');
            }
            activeSettingObject = $(this);
        });
        $(document.body).on('click', '.goto-header-builder', function (e) {
            e.preventDefault();
            wp.customize.section('molla_header_layouts').focus();
        });
        $(document.body).on('click', '.header-builder span[data-target]', function (e) {
            var id = $(this).data('target');
            var type = $(this).data('type');
            if (id) {
                if (type)
                    wp.customize[type](id).focus();
                else
                    wp.customize.control(id).focus();
            }
        });
        $(document.body).on('click', '.molla_header_builder_save_html', function () {
            if (activeSettingObject == null) {
                return false;
            }
            if (activeSettingObject.data('id') == 'html') {
                activeSettingObject.data('html', $('#customize-control-molla_header_layouts_html_element textarea').val());
                activeSettingObject.data('el_class', $('#customize-control-molla_header_layouts_el_class input').val());
                $('#customize-control-molla_header_layouts_html_element textarea').val('');
            } else if (activeSettingObject.data('id') == 'molla_block') {
                activeSettingObject.data('html', $('#customize-control-molla_header_layouts_block_element input').val());
            } else if (activeSettingObject.data('id') == 'custom_menu') {
                activeSettingObject.data('html', $('#customize-control-molla_header_custom_menu_id select').val());
                activeSettingObject.data('menu_type', $('#customize-control-molla_header_custom_menu_type input').is(':checked'));
                activeSettingObject.data('menu_title', $('#customize-control-molla_header_toggle_menu_title input').val());
                activeSettingObject.data('menu_link', $('#customize-control-molla_header_toggle_menu_link input').val());
                activeSettingObject.data('menu_active_event', $('#customize-control-molla_header_custom_menu_toggle_event select').val());
                activeSettingObject.data('menu_show_icon', $('#customize-control-molla_header_toggle_menu_show_icon input').is(':checked'));
                activeSettingObject.data('menu_icon', $('#customize-control-molla_header_toggle_menu_icon input').val());
                activeSettingObject.data('menu_active_icon', $('#customize-control-molla_header_toggle_menu_active_icon input').val());
                activeSettingObject.data('menu_icon_pos', $('#customize-control-molla_header_toggle_menu_icon_pos select').val());
                activeSettingObject.data('menu_skin', $('#customize-control-molla_header_layouts_menu_skin select').val());
            }
            setItem(activeSettingObject.closest('div').data('id'), 'molla-drop-item' + (activeSettingObject.closest('.molla-drop-item-mobile').length ? '-mobile' : ''));
            $(hb_controls).fadeOut();
        });

        // lazy load images
        function mollaAdminLazyLoadImages(element) {
            var $element = $(element);
            if ($element.hasClass('lazy-load-active')) return;
            var src = $element.data('original');
            if (src) $element.attr('src', src);
            $element.addClass('lazy-load-active');
        }

        $('.header-builder-header .preview-desktop').click(function (e) {
            e.preventDefault();
            $('.devices .preview-desktop').click();
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });
        $('.header-builder-header .preview-mobile').click(function (e) {
            e.preventDefault();
            $('.devices .preview-tablet').click();
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });
        $('.header-builder-header .button-close').click(function (e) {
            e.preventDefault();
            if ($(this).closest('.molla-header-builder').hasClass('active')) {
                $(this).html('Open');
                $('.molla-header-builder').removeClass('active');
            } else {
                $(this).html('Close');
                $('.molla-header-builder').addClass('active');
            }
        });
        $('.header-builder-header .button-clear').click(function (e) {
            e.preventDefault();
            $('.header-builder-wrapper .header-builder').each(function () {
                var $this = $(this);
                $this.find('span:not(.element-infinite):not(.molla-header-builder-tooltip)').appendTo($this.parent().prev().find('.molla-header-builder-list'));
                $this.find('.element-infinite').remove();
            });
            $.each(wp.customize.section('molla_header_builder').controls(), function (key, control) {
                wp.customize.instance(control.settings.default.id).set('');
            });
        });

        // set default options for header presets
        js_molla_hb_vars.header_builder_presets = $.parseJSON(js_molla_hb_vars.header_builder_presets);
        $('body').on('click', '.molla_header_presets .img-select', function () {

            var $this = $(this),
                selected = $(this).data('preset');

            $this.toggleClass('active');
            if ($this.hasClass('active')) {
                $this.siblings('.active').removeClass('active');
                wp.customize.instance('molla_header_builder[preset]').set(selected);
            } else {
                wp.customize.instance('molla_header_builder[preset]').set('');
            }
            if (js_molla_hb_vars.header_builder_presets[selected]) {
                if ($this.hasClass('active')) {
                    if (preset != selected) {
                        var preset_settings = js_molla_hb_vars.header_builder_presets[selected];
                        resetHeaderBuilderElements(preset_settings);
                    } else {
                        resetHeaderBuilderElements({
                            elements: elements,
                            custom_css: custom_css,
                            preset: '',
                        });
                    }
                } else {
                    if (preset) {
                        resetHeaderBuilderElements({});
                    } else {
                        resetHeaderBuilderElements({
                            elements: elements,
                            custom_css: custom_css,
                            preset: '',
                        });
                    }
                }
            }
        });

        // Import & Export options
        if (!wp.customize.instance('import_src').get()) {
            $('.molla_import_options').prop('disabled', true);
        }
        wp.customize('import_src', function (e) {
            e.bind(function (src) {
                if (src) {
                    $('.molla_import_options').prop('disabled', false);
                } else {
                    $('.molla_import_options').prop('disabled', true);
                }
            })
        });
        $('.molla_import_options').on('click', function () {

            if (!confirm("Are you sure to import theme options?")) {
                return;
            }

            var $this = $(this),
                $control = $this.closest('li');

            $this.prop('disabled', true);

            $.ajax({
                url: customizer_admin_vars.ajax_url,
                data: {
                    wp_customize: 'on',
                    action: 'molla_customizer_import_options',
                    src: wp.customize.instance('import_src').get(),
                    nonce: customizer_admin_vars.nonce
                },
                type: 'post',
                success: function (response) {
                    if (response.success) {
                        $control.find('.notice-error').remove();
                        if (!$control.find('.notice-success').length) {
                            $control.prepend('<span class="notice notice-success" style="display: block;">Imported successfully!</span>');
                        }
                        window.location.reload();
                    } else {
                        $this.prop('disabled', false);
                        $control.find('.notice-success').remove();
                        if ('not_exists' == response.data) {
                            var validate = 'File does not exists!';
                        } else {
                            var validate = 'File type invalid!';
                        }
                        if (!$control.find('.notice-error').length) {
                            $control.prepend('<span class="notice notice-error" style="display: block;">' + validate + '</span>');
                        }
                        $control.find('.notice-error').html(validate);
                    }
                }
            });
        })

        if (!wp.customize.instance('export_src').get()) {
            $('.molla_export_options').prop('disabled', true);
        }
        wp.customize('export_src', function (e) {
            e.bind(function (src) {
                if (src) {
                    $('.molla_export_options').prop('disabled', false);
                } else {
                    $('.molla_export_options').prop('disabled', true);
                }
            })
        });
        $('.molla_export_options').on('click', function () {
            var $this = $(this),
                $control = $this.closest('li');
            $this.prop('disabled', true);
            $.ajax({
                url: customizer_admin_vars.ajax_url,
                data: {
                    wp_customize: 'on',
                    action: 'molla_customizer_export_options',
                    target: wp.customize.instance('export_src').get(),
                    nonce: customizer_admin_vars.nonce
                },
                type: 'post',
                success: function (response) {
                    if (response.success) {
                        $control.find('.notice-error').remove();
                        if (!$control.find('.notice-success').length) {
                            $control.prepend('<span class="notice notice-success" style="display: block;">Exported successfully!</span>');
                        }
                    } else {
                        $this.prop('disabled', false);
                        $control.find('.notice-success').remove();
                        if (!$control.find('.notice-error').length) {
                            $control.prepend('<span class="notice notice-error" style="display: block;">Export Failed!</span>');
                        }
                    }
                }
            });
        })

        // Customizer Reset options
        var $reset = $('#molla-reset-options');

        $reset.on('click', function (e) {
            e.preventDefault();

            if (!confirm("Are you sure to reset all theme options?")) {
                return;
            }

            $reset.attr('disabled', 'disabled');

            $.ajax({
                url: customizer_admin_vars.ajax_url,
                data: { wp_customize: 'on', action: 'molla_customizer_reset_options', nonce: customizer_admin_vars.nonce },
                type: 'post',
                success: function (response) {
                    wp.customize.state('saved').set(false);
                    $('#customize-save-button-wrapper #save').trigger('click');
                    window.location.reload();
                }
            });
        });

        // if woo_pre_order has changed, reload to save permalink.
        if (typeof wp.customize.instance('woo_pre_order') != 'undefined') {
            var woo_pre_order_origin = wp.customize.instance('woo_pre_order').get();
            wp.customize.bind('saved', function (e) {
                woo_pre_order_origin !== wp.customize.instance('woo_pre_order').get() &&
                    window.location.reload();
            })
        }
    });
})(wp.customize, wp, jQuery);