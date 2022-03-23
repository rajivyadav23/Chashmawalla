jQuery(document).ready(function ($) {
    'use strict';

    var isRTL = $('body').hasClass('rtl'),
        left = isRTL ? 'right' : 'left',
        right = isRTL ? 'left' : 'right';

    // Tooltip
    $(document.body).on('mouseenter', '.molla-tooltip', function () {
        $(this).stop(true, true).show();
        var obj = $(this).data('triggerObj');
        if (obj) {
            obj.addClass('molla-tooltip-active');
        }
    }).on('mouseleave', '.molla-tooltip', function () {
        $(this).stop().fadeOut(400);
        var obj = $(this).data('triggerObj');
        if (obj) {
            obj.removeClass('molla-tooltip-active');
        }
    });
    $(document.body).on('click', '.molla-tooltip', function () {
        var initCall = $(this).data('initCall');
        if (initCall) {
            initCall.call(this, $(this).data('triggerObj'));
        }
    });

    $.fn.mollaTooltip = function (options) {
        options.target = escape(options.target.replace(/"/g, ''));
        $('.molla-tooltip[data-target="' + options.target + '"]').remove();
        return $(this).each(function () {
            if ($(this).hasClass('molla-tooltip-initialized')) {
                return;
            }

            var $this = $(this),
                $tooltip = $('<div class="molla-tooltip" data-target="' + options.target + '" style="display: none; position: absolute; z-index: 9999;">' + options.text + '</div>').appendTo('body');
            $tooltip.data('triggerObj', $this);
            if (options.init) {
                $tooltip.data('initCall', options.init);
            }
            $this.mouseenter(function () {
                $tooltip.text(options.text);
                if (options.position == 'top') {
                    $tooltip.css('top', $this.offset().top - $tooltip.outerHeight() / 2).css('left', $this.offset().left + $this.outerWidth() / 2 - $tooltip.outerWidth() / 2);
                } else if (options.position == 'bottom') {
                    $tooltip.css('top', $this.offset().top + $this.outerHeight() - $tooltip.outerHeight() / 2).css('left', $this.offset().left + $this.outerWidth() / 2 - $tooltip.outerWidth() / 2);
                } else if (options.position == 'left') {
                    $tooltip.css('top', $this.offset().top + $this.outerHeight() / 2 - $tooltip.outerHeight() / 2).css('left', $this.offset().left - $tooltip.outerWidth() / 2);
                } else if (options.position == 'right') {
                    $tooltip.css('top', $this.offset().top + $this.outerHeight() / 2 - $tooltip.outerHeight() / 2).css('left', $this.offset().left + $this.outerWidth() - $tooltip.outerWidth() / 2);
                }
                $tooltip.stop().fadeIn(100);
                $this.addClass('molla-tooltip-active');
            }).mouseleave(function () {
                $tooltip.stop(true, true).fadeOut(400);
                $this.removeClass('molla-tooltip-active');
            }).addClass('molla-tooltip-initialized');
        });
    };

    function initTooltipSection(e, $obj) {
        if (e.elementID && 'custom' != e.type) {
            window.parent.wp.customize[e.type](e.elementID).focus();
        } else if ('custom' == e.type && e.elementID) {
            window.parent.wp.customize.section('header_presets').focus();
            var index = $(e.target, '.header-wrapper').index($obj),
                isMobile = $obj.closest('.visible-for-sm:visible').length ? true : false;
            $('.molla-header-builder .header-wrapper-' + (isMobile ? 'mobile' : 'desktop') + ' .header-builder-wrapper', window.parent.document).find('[data-id="' + e.elementID + '"]').eq(index).trigger('click');
        }
    }

    function initCustomizerTooltips($parent) {
        tooltips.forEach(function (e) {
            if ($(e.target).is($parent) || $parent.find($(e.target)).length) {
                e.type || (e.type = 'control');
                $(e.target).mollaTooltip({
                    position: e.pos,
                    text: e.text,
                    target: e.target,
                    init: function ($obj) {
                        initTooltipSection(e, $obj);
                    }
                });
            }
        });
    }

    var tooltips = [{
        target: '.header .custom-html',
        text: 'HTML',
        elementID: 'html',
        pos: 'top',
        type: 'custom'
    }, {
        target: '.header .logo',
        text: 'Logo',
        elementID: 'site_logo',
        pos: 'top'
    }, {
        target: '.header #menu-main-menu',
        text: 'Main Menu',
        elementID: 'main_menu_skin',
        pos: 'left'
    }, {
        target: '.menu',
        text: 'Menu',
        elementID: 'menu_skins',
        pos: 'left'
    }, {
        target: '.header .logout-link',
        text: 'Account Form',
        elementID: 'log_in_label',
        pos: 'bottom'
    }, {
        target: '.header .top-menu',
        text: 'Responsive Group',
        elementID: 'top_nav_items',
        pos: 'bottom'
    }, {
        target: '.header .social-icons',
        text: 'Social Icons',
        elementID: 'facebook',
        pos: 'bottom'
    }, {
        target: '.header .header-search',
        text: 'Header Search',
        elementID: 'search_content_type',
        pos: 'bottom'
    }, {
        target: '.header .shop-icons',
        text: 'Shop Icons',
        elementID: 'header_shop_icons',
        type: 'section',
        pos: 'bottom',
    }, {
        target: '.header .header-top',
        text: 'Header Top',
        elementID: 'header_top_divider_width',
        pos: 'bottom',
    }, {
        target: '.header .header-main',
        text: 'Header Main',
        elementID: 'header_main_divider_width',
        pos: 'bottom',
    }, {
        target: '.header .header-bottom',
        text: 'Header Bottom',
        elementID: 'header_bottom_divider_width',
        pos: 'bottom',
    }, {
        target: '#scroll-top',
        text: 'Scroll to Top',
        elementID: 'scroll_top_icon',
        pos: 'top',
    }, {
        target: '.page-header',
        text: 'Page Header',
        elementID: 'page_header_bg',
        pos: 'bottom',
    }, {
        target: '.posts .entry-content p',
        text: 'Content Length',
        elementID: 'blog_excerpt_unit',
        pos: 'left',
    }, {
        target: '.posts .post',
        text: 'Post Type',
        elementID: 'blog_entry_type',
        pos: 'bottom',
    }, {
        target: '.shop-sidebar',
        text: 'Shop Sidebar Option',
        elementID: 'shop_sidebar_pos',
        pos: 'top',
    }, {
        target: '.sticky-bar.fixed',
        text: 'Product Sticky Bar',
        elementID: 'single_sticky_bar_show',
        pos: 'top',
    }, {
        target: '.product-label',
        text: 'Product Label',
        elementID: 'product_label_type',
        pos: 'top',
    }, {
        target: '.myaccount-content .container',
        text: 'Account Form',
        elementID: 'woo_account_background',
        pos: 'top',
    }, {
        target: '.toolbox .woocommerce-result-count',
        text: 'Loop Count',
        elementID: 'woocommerce_catalog_columns',
        pos: 'right',
    }, {
        target: '.related-posts',
        text: 'Related Posts',
        elementID: 'related_posts_sort_by',
        pos: 'top',
    }, {
        target: '.social-icons .social-icons',
        text: 'Share Icons',
        elementID: 'share_icons',
        pos: 'top',
    }, {
        target: '.footer-top',
        text: 'Footer Top',
        elementID: 'footer_top_divider_width',
        pos: 'top',
    }, {
        target: '.footer-main',
        text: 'Footer Main',
        elementID: 'footer_main_divider_width',
        pos: 'top',
    }, {
        target: '.footer-bottom',
        text: 'Footer Bottom',
        elementID: 'footer_custom_html',
        pos: 'top',
    }, {
        target: '.products .product',
        text: 'Product Type',
        elementID: 'post_product_type',
        pos: 'bottom',
    }, {
        target: '.single-product-details',
        text: 'Product Details',
        elementID: 'single_product_tab_title',
        pos: 'top',
    }, {
        target: '.related.products',
        text: 'Related Products',
        elementID: 'single_related_show',
        pos: 'top',
    }, {
        target: '.post-single',
        text: 'Single Post',
        elementID: 'blog_single_featured_image',
        pos: 'top',
    }, {
        target: '.woocommerce-breadcrumb',
        text: 'Breadcrumb',
        elementID: 'breadcrumb_show',
        pos: 'top',
    }, {
        target: '.posts .entry-meta',
        text: 'Blog Archive Meta',
        elementID: 'font_entry_meta',
        pos: 'left',
    }, {
        target: '.posts .entry-title',
        text: 'Blog Archive Title',
        elementID: 'font_entry_title',
        pos: 'left',
    }, {
        target: '.posts .entry-cats',
        text: 'Blog Archive Category',
        elementID: 'font_entry_cat',
        pos: 'left',
    }, {
        target: '.posts .entry-content p',
        text: 'Blog Archive Excerpt',
        elementID: 'font_entry_excerpt',
        pos: 'left',
    }, {
        target: '.read-more',
        text: 'Blog Archive Read More',
        elementID: 'font_entry_view_more',
        pos: 'left',
    }, {
        target: '.post-single > .post .entry-meta',
        text: 'Blog Single Meta',
        elementID: 'font_single_meta',
        pos: 'left',
    }, {
        target: '.post-single > .post .entry-title',
        text: 'Blog Single Title',
        elementID: 'font_single_title',
        pos: 'left',
    }, {
        target: '.post-single > .post .entry-cats',
        text: 'Blog Single Category',
        elementID: 'font_single_cat',
        pos: 'left',
    }];

    initCustomizerTooltips($(document.body));

    wp.customize.selectiveRefresh.bind('partial-content-rendered', function (placement) {
        initCustomizerTooltips($(placement.partial.params.selector));
    })

    wp.customize.selectiveRefresh.Partial.prototype.ready = function () {
        var partial = this;
        _.each(partial.placements(), function (placement) {
            partial.createEditShortcutForPlacement(placement);
        });
        $(document).on('click', partial.params.selector, function (e) {
            if (!e.shiftKey) {
                return;
            }
            e.preventDefault();
            _.each(partial.placements(), function (placement) {
                if ($(placement.container).is(e.currentTarget)) {
                    partial.showControl();
                }
            });
        });
    }

    wp.customize.selectiveRefresh.bind('partial-content-rendered', function (placement) {
        if ('blog_related_posts' == placement.partial.id) {
            Molla.owlCarousels(placement.container);
        } else if ('blog_entries' == placement.partial.id) {
            Molla.owlCarousels(placement.container);
        } else if ('single_post' == placement.partial.id) {
            Molla.owlCarousels(placement.container);
            placement.container.find('.sticky-sidebar').trigger('recalc.pin');
        }
    });

    function appendStyle(itemId, style) {
        itemId = itemId.replace('[', '').replace(']', '');
        // if (typeof Molla.molla_vars.label_type != "undefined") {
        //     console.log(Molla.molla_vars.label_type);
        // }
        $("style#customize-" + itemId).length ? $("style#customize-" + itemId).text(style) : $("head").append('<style id="customize-' + itemId + '" type="text/css">' + style + "</style>");
    }

    var width = {
        'header_width': '.header',
        'footer_width': '.footer',
    };

    $.each(width, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (width) {
                if (width == 'container') {
                    $($selector).find('.inner-wrap').each(function () {
                        $(this).parent().removeClass('container-fluid').addClass(width);
                    })
                } else {
                    $($selector).find('.inner-wrap').each(function () {
                        $(this).parent().removeClass('container').addClass(width);
                    })
                }
            })
        });
    })

    wp.customize('sidebar_width', function (e) {
        e.bind(function (width) {
            var w = parseInt(width),
                css = '';
            css = '.sidebar-wrapper > .col-lg-3 { flex: 0 0 ' + w + '%; max-width: ' + w + '%; }';
            css += '.sidebar-wrapper > .col-lg-9 { flex: 0 0 ' + (100 - w) + '%; max-width: ' + (100 - w) + '%; }';
            appendStyle('sidebar_width', css);
            $(window).trigger('resize');
        })
    });

    wp.customize('header_side', function (e) {
        e.bind(function (header_side) {
            $('.header-top').toggleClass('header-side', 'top' == header_side);
            $('.header-main').toggleClass('header-side', 'main' == header_side);
            $('.header-bottom').toggleClass('header-side', 'bottom' == header_side);

            var css = '';
            if (header_side) {
                // reset expandable side menu if it is active
                $('.header-side').toggleClass('header-side-menu-expand', ('expand' == wp.customize.instance('header_side_menu_type').get()));

                // remove sticky when side header is active
                $('.header-side').removeClass('sticky-header');

                // css
                var gutter = parseInt(wp.customize.instance('grid_gutter_width').get()),
                    container_width = parseInt(wp.customize.instance('container_width').get());
                container_width || (container_width = 1188);

                css += '@media (min-width: 992px) {';
                css += '.header-' + ('top' == header_side ? 'main, .header-bottom' : ('main' == header_side ? 'top, .header-bottom' : 'top, .header-main')) + ' { position: relative; z-index: 1; }';
                css += '.header-side { width: ' + (300 + gutter) + 'px; }';
                css += '.header-side .menu > li > .sub-menu { ' + left + ':' + (300 + gutter) + 'px; }';
                css += '.main { margin-' + left + ': 300px; width: auto; }';
                css += '.footer { width: auto; margin-' + left + ':' + (300 + gutter) + 'px; margin-' + right + ':' + gutter + 'px; }';
                css += '}';

                css += '@media (min-width: ' + (container_width + gutter + 1) + 'px) {';
                css += '.header-side { ' + left + ': calc((100% - ' + container_width + 'px) / 2); width: ' + (300 + gutter / 2) + 'px; }';
                css += '.main { margin-' + left + ': calc((100% - ' + container_width + 'px) / 2 + 300px); margin-' + right + ': calc((100% - ' + container_width + 'px) / 2); }';
                css += '.footer { margin-' + left + ': calc((100% - ' + container_width + 'px) / 2 + ' + (300 + gutter / 2) + 'px); margin-' + right + ': calc((100% - ' + container_width + 'px) / 2 + ' + (gutter / 2) + 'px); }';
                css += '.header-side .menu > li > .sub-menu { ' + left + ': calc((100% - ' + container_width + 'px) / 2 + ' + (300 + gutter / 2) + 'px); }';
                css += '}';
            } else {
                // remove expandable side menu.
                $('.header-side-menu-expand').removeClass('header-side-menu-expand');

                // reset sticky header.
                $('sticky-wrapper > div').addClass('sticky-header');

                // css
                css += '@media (min-width: 992px) {';
                css += '.main { width: 100%; margin-left: 0; }';
                css += '.footer { width: 100%; margin-left: 0; marign-right: 0; }';
                css += '}';
            }
            appendStyle('header-side', css);
        })
    })

    wp.customize('header_side_menu_type', function (e) {
        e.bind(function (header_side_menu_type) {
            $('.header-side').toggleClass('header-side-menu-expand', 'expand' == header_side_menu_type);
        })
    })

    wp.customize('breadcrumb_divider_active', function (e) {
        e.bind(function (active) {
            if (active) {
                $('.woocommerce-breadcrumb').addClass('divider-active');
            } else {
                $('.woocommerce-breadcrumb').removeClass('divider-active');
            }
        })
    })

    var colors = {
        'header_color': '.header',
        'header_top_color': '.header-top',
        'header_main_color': '.header-main',
        'header_bottom_color': '.header-bottom',
        'new_price_color': '.products .price ins',
        'old_price_color': '.products .price del',
    };

    $.each(colors, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (value) {
                appendStyle($setting, $selector + '{color:' + value + ';}');
            })
        })
    })

    var dividers = {
        'header_top_divider_active': '.header',
        'header_main_divider_active': '.header',
        'header_bottom_divider_active': '.header',
    };

    $.each(dividers, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (value) {
                var elems = ['top', 'main', 'bottom'];
                var active = false;
                for (var i = 0; i < 3; i++) {
                    if (wp.customize.instance('header_' + elems[i] + '_divider_active').get()) {
                        active = true;
                        break;
                    }
                }
                if (active) {
                    $($selector).addClass('divider-active');
                } else {
                    $($selector).removeClass('divider-active');
                }
            })
        })
    })

    var divider_colors = {
        'header_divider_color': '.header.divider-active .header-row > div, .header.divider-active .header-row .inner-wrap',
        'footer_divider_color': '.footer.divider-active > div, .footer.divider-active .inner-wrap',
        'breadcrumb_divider_color': '.woocommerce-breadcrumb.divider-active .full-divider, .woocommerce-breadcrumb.divider-active .inner-wrap',
    };
    $.each(divider_colors, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (color) {
                var css = '';
                css = 'border-color: ' + (color ? color : 'transparent') + ';';
                css = $selector + ' {' + css + '}';
                appendStyle($setting, css);

            });
        });
    })

    divider_colors = {
        'header_top_divider_color': '.header.divider-active .header-row .header-top, .header.divider-active .header-row .header-top .inner-wrap',
        'header_main_divider_color': '.header.divider-active .header-row .header-main, .header.divider-active .header-row .header-main .inner-wrap',
        'header_bottom_divider_color': '.header.divider-active .header-row .header-bottom, .header.divider-active .header-row .header-bottom .inner-wrap',
    };
    $.each(divider_colors, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (color) {
                var css = '';
                if (!color) {
                    var global_color = wp.customize.instance('header_divider_color').get();
                    if (global_color) {
                        color = global_color;
                    } else {
                        color = 'transparent';
                    }
                }
                css = 'border-color: ' + color + ';';
                css = $selector + ' {' + css + '}';
                appendStyle($setting, css);

            });
        });
    })

    wp.customize('header_position_fixed', function (e) {
        e.bind(function (active) {
            if (active) {
                $('header.header').addClass('fixed-header');
            } else {
                $('header.header').removeClass('fixed-header');
            }
        })
    });

    var bg_settings = {
        'header_bg': '.header',
        'header_top_bg': '.header .header-top',
        'header_main_bg': '.header .header-main',
        'header_bottom_bg': '.header .header-bottom',
        'skin1_menu_bg': '.menu-skin1',
        'skin2_menu_bg': '.menu-skin2',
        'skin3_menu_bg': '.menu-skin3',
        'skin1_menu_dropdown_bg': '.menu-skin1 .sub-menu',
        'skin2_menu_dropdown_bg': '.menu-skin2 .sub-menu',
        'skin3_menu_dropdown_bg': '.menu-skin3 .sub-menu',
        'footer_bg': '.footer',
        'footer_top_bg': '.footer .footer-top',
        'footer_main_bg': '.footer .footer-main',
        'footer_bottom_bg': '.footer .footer-bottom',
        'page_header_bg': '.page-header',
        'content_bg': '.page-wrapper',
        'frame_bg': 'body',
        'woo_account_background': '.myaccount-content',
    };

    $.each(bg_settings, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (bg) {
                var css = '';
                if (bg['background-color']) {
                    css += 'background-color: ' + bg['background-color'] + ';';
                } else {
                    if (-1 !== $selector.indexOf('.header .header-') || -1 !== $selector.indexOf('.footer .footer-')) {
                        css += 'background-color: inherit;';
                    } else {
                        css += 'background-color: transparent;';
                    }
                }

                if (bg['background-image']) {
                    if (bg['background-image']) {
                        css += 'background-image: url("' + bg['background-image'] + '");';
                        css += 'background-repeat: ' + (!bg['background-repeat'] ? 'no-repeat' : (bg['background-repeat'] == 'repeat-all' ? 'repeat' : bg['background-repeat'])) + ';';
                        css += 'background-size: ' + bg['background-size'] + ';';
                        css += 'background-position: ' + (bg['background-position'] ? bg['background-position'] : 'left top') + ';';
                        css += 'background-attachment: ' + bg['background-attachment'] + ';';
                    } else {
                        css += 'background-image: none;';
                    }
                }
                if (css) {
                    css = $selector + '{' + css + '}';
                    if (-1 !== $selector.indexOf('sticky')) {
                        css = '@media (min-width: 992px) {' + css + '}';
                    }
                    if ($setting == 'content_bg') {
                        css += '.products.split-line:before { background-color: ' + bg['background-color'] + '; }';
                    }
                    appendStyle($setting, css);
                }
            })
        })
    });

    var spacings = {
        'logo_spacing': '.header .header-col .logo',
        'logo_spacing_sticky': '.header .fixed .logo',
        'product_cat_margin': '.products .product-cat',
        'product_title_margin': '.products .product-title',
        'product_price_margin': '.products .product-wrap .product .price',
        'product_rating_margin': '.products .ratings-container',
        'entry_meta_margin': '.posts .entry-meta',
        'entry_title_margin': '.posts .entry-title',
        'entry_cat_margin': '.posts .entry-cats',
        'entry_excerpt_margin': '.posts .entry-content p',
        'entry_view_more_margin': '.read-more',
        'single_meta_margin': '.post-single > .post .entry-meta',
        'single_title_margin': '.post-single > .post .entry-title',
        'single_cat_margin': '.post-single > .post .entry-cats',
    };
    $.each(spacings, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (spacing) {
                var css = '',
                    dimensions = ['top', 'right', 'bottom', 'left'];
                for (var i = 0; i < dimensions.length; i++) {
                    if ('' === spacing[dimensions[i]]) {
                        spacing[dimensions[i]] = 0;
                    }
                    if (spacing[dimensions[i]] && !spacing[dimensions[i]].replace(/(|-)[0-9.]+/, '').trim()) {
                        spacing[dimensions[i]] += 'px';
                    }
                }
                css += 'margin: ' + spacing['top'] + ' ' + spacing['right'] + ' ' + spacing['bottom'] + ' ' + spacing['left'] + ';';
                css = $selector + '{' + css + '}';
                appendStyle($setting, css);
            })
        });
    });

    var width_settings = {
        'logo_width': '.logo img',
        'logo_width_sticky': '.sticky-header.fixed .logo img',
    };
    $.each(width_settings, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (width) {
                if ('' != width) {
                    var css = '';
                    css += 'width: ' + width + 'px;';
                    css = $selector + '{' + css + '}';
                    appendStyle($setting, css);
                }
            })
        });
    });

    var max_width_settings = {
        'logo_max_width': '.logo img',
        'logo_max_width_sticky': '.sticky-header.fixed .logo img',
    };
    $.each(max_width_settings, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (width) {
                if ('' != width) {
                    var css = '';
                    css += 'max-width: ' + width + 'px;';
                    css = $selector + '{' + css + '}';
                    appendStyle($setting, css);
                }
            })
        });
    });

    var divider_width_settings = {
        'header_top_divider_width': '.header-top',
        'header_main_divider_width': '.header-main',
        'header_bottom_divider_width': '.header-bottom',
        'footer_top_divider_width': '.footer-top',
        'footer_main_divider_width': '.footer-main',
        'footer_bottom_divider_width': '.footer-bottom',
        'breadcrumb_divider_width': '.woocommerce-breadcrumb .breadcrumb-wrap',
    };
    $.each(divider_width_settings, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (width) {
                if (wp.customize.instance($setting.replace('width', 'active')).get()) {

                    if (!width) {
                        $($selector).removeClass('full-divider').addClass('content-divider');
                    } else {
                        $($selector).removeClass('content-divider').addClass('full-divider');

                        if ('.woocommerce-breadcrumb' != $selector)
                            $($selector).parent().css('height', 'auto');
                    }
                }
            })
        })
    });
    var divider_width_settings = {
        'header_top_divider_active': '.header-top',
        'header_main_divider_active': '.header-main',
        'header_bottom_divider_active': '.header-bottom',
        'footer_top_divider_active': '.footer-top',
        'footer_main_divider_active': '.footer-main',
        'footer_bottom_divider_active': '.footer-bottom',
    };
    $.each(divider_width_settings, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (active) {
                if (active) {
                    var key = $setting.replace('active', 'width');
                    if (wp.customize.instance(key).get()) {
                        $($selector).removeClass('content-divider').addClass('full-divider');
                    } else {
                        $($selector).removeClass('full-divider').addClass('content-divider');
                    }
                } else {
                    $($selector).removeClass('full-divider').removeClass('content-divider');
                }
            })
        })
    });

    var hp_settings = {
        'header_top_height': '.header-top',
        'header_main_height': '.header-main',
        'header_bottom_height': '.header-bottom',
        'header_top_sticky_height': '.header-top.fixed',
        'header_main_sticky_height': '.header-main.fixed',
        'header_bottom_sticky_height': '.header-bottom.fixed',
    };
    $.each(hp_settings, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (height) {
                var css = '';
                css += 'padding-top: ' + height + 'px;';
                css += 'padding-bottom: ' + height + 'px;';
                css = $selector + ' .inner-wrap {' + css + '}';

                $($selector).parent().css('height', 'auto');

                appendStyle($setting, css);
                if (!$($selector).hasClass('fixed')) {
                    Molla.stickyHeader();
                    $($selector).closest('.sticky-wrapper').css('height', $($selector).outerHeight() + 'px');
                }
            })
        })
    });

    var fpt_settings = {
        'footer_top_pt': '.footer-top .inner-wrap',
        'footer_main_pt': '.footer-main .inner-wrap',
        'footer_bottom_pt': '.footer-bottom .inner-wrap',
    }
    $.each(fpt_settings, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (height) {
                var css = '';
                css += 'padding-top: ' + height + 'px;';
                css = $selector + '{' + css + '}';

                appendStyle($setting, css);
            })
        })
    });

    var fpb_settings = {
        'footer_top_pb': '.footer-top .inner-wrap',
        'footer_main_pb': '.footer-main .inner-wrap',
        'footer_bottom_pb': '.footer-bottom .inner-wrap',
    }
    $.each(fpb_settings, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (height) {
                var css = '';
                css += 'padding-bottom: ' + height + 'px;';
                css = $selector + '{' + css + '}';

                appendStyle($setting, css);
            })
        })
    });

    var header_elems = {
        'header_top_in_sticky': '.header-top',
        'header_main_in_sticky': '.header-main',
        'header_bottom_in_sticky': '.header-bottom',
    }
    wp.customize('header_sticky_show', function (e) {
        e.bind(function (show) {
            if (!show) {
                $('.sticky-wrapper').each(function () {
                    $(this).removeClass('sticky-wrapper');
                })
                $('.sticky-header').each(function () {
                    $(this).removeClass('sticky-header');
                    $(this).removeClass('fixed');
                })
            } else {
                $.each(header_elems, function ($key, $selector) {
                    if (wp.customize.instance($key).get()) {
                        $($selector).addClass('sticky-header');
                        $($selector).parent().addClass('sticky-wrapper');
                    }
                })
            }
        })
    });

    $.each(header_elems, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (active) {
                if (active) {
                    $($selector).parent().addClass('sticky-wrapper');
                    $($selector).addClass('sticky-header');
                } else {
                    $($selector).parent().removeClass('sticky-wrapper');
                    $($selector).removeClass('sticky-header').removeClass('fixed');
                }
            })
        })
    })
    wp.customize('header_search_style', function (e) {
        e.bind(function (style) {
            if (style) {
                $('.header .header-search').addClass(style);
            } else {
                $('.header .header-search').removeClass('header-search-visible');
            }
        })
    });

    wp.customize('search_placeholder', function (e) {
        e.bind(function (text) {
            $('.header .header-search .form-control').attr('placeholder', text);
        })
    });

    wp.customize('header_search_border_color', function (e) {
        e.bind(function (color) {
            var css = '';
            css += 'border-color: ' + (color ? color : 'transparent') + ';';
            css = '.header .header-search .search-wrapper' + '{' + css + '}';
            appendStyle('header_search_border_color', css);
        })
    });

    wp.customize('header_search_border_width', function (e) {
        e.bind(function (width) {
            var css = '';
            var dimensions = ['top', 'right', 'bottom', 'left'];
            for (var i = 0; i < dimensions.length; i++) {
                var w = width[dimensions[i]];
                if (w !== '') {
                    if (!w.replace(/(|-)[0-9.]+/, '').trim()) {
                        w += 'px';
                    }
                    css += 'border-' + dimensions[i] + '-width: ' + w + ';';
                }
            }
            css = '.header .header-search .search-wrapper' + '{' + css + '}';
            appendStyle('header_search_border_width', css);
        })
    });

    wp.customize('header_search_border_radius', function (e) {
        e.bind(function (radius) {
            var css = '';
            if (radius) {
                if (!radius.replace(/(|-)[0-9.]+/, '').trim()) {
                    radius += 'px';
                }
            } else {
                radius = 0;
            }
            css += 'border-radius: ' + radius + ';';
            css = '.header .header-search .search-wrapper' + '{' + css + '}';
            var btn_css = '';
            btn_css = '.header-search .btn {' + 'border-radius: 0 ' + radius + ' ' + radius + ' 0; }';
            btn_css += '.header-search.icon-left .btn {' + 'border-radius: ' + radius + ' 0 0 ' + radius + '; }';
            css += btn_css;
            appendStyle('header_search_border_radius', css);
        })
    });

    wp.customize('header_search_btn_type', function (e) {
        e.bind(function (type) {
            var $search_box = $('.header .header-search .btn');
            if (type) {
                $search_box.removeClass('btn-primary').addClass('btn-icon');
            } else {
                $search_box.removeClass('btn-icon').addClass('btn-primary');
            }
        })
    });

    wp.customize('header_search_icon_left', function (e) {
        e.bind(function (active) {
            if (active) {
                $('.header-search').addClass('icon-left');
            } else {
                $('.header-search').removeClass('icon-left');
            }
        })
    });

    wp.customize('browse_cat_label', function (e) {
        e.bind(function (text) {
            if (text) {
                $('.dropdown-menu-wrapper .dropdown-toggle span').html(text);
            } else {
                $('.dropdown-menu-wrapper .dropdown-toggle span').html('Browse Categories');
            }
        })
    });

    wp.customize('browse_cat_icon', function (e) {
        e.bind(function (icon_class) {
            if (icon_class) {
                $('.dropdown-menu-wrapper .dropdown-toggle i:first-of-type').attr('class', icon_class).addClass('normal-state');
            } else {
                $('.dropdown-menu-wrapper .dropdown-toggle i:first-of-type').attr('class', 'icon-bars').addClass('normal-state');
            }
        })
    });

    wp.customize('browse_cat_icon_hover', function (e) {
        e.bind(function (icon_class) {
            if (icon_class) {
                $('.dropdown-menu-wrapper .dropdown-toggle i:last-of-type').attr('class', icon_class);
            } else {
                $('.dropdown-menu-wrapper .dropdown-toggle i:last-of-type').attr('class', 'icon-close');
            }
        })
    });

    wp.customize('browse_cat_icon_show', function (e) {
        e.bind(function (show) {
            if (show) {
                $('.dropdown-menu-wrapper').removeClass('icon-hidden');
            } else {
                $('.dropdown-menu-wrapper').addClass('icon-hidden');
            }
        })
    });

    wp.customize('browse_cat_icon_pos', function (e) {
        e.bind(function (pos) {
            if ('left' == pos) {
                $('.dropdown-menu-wrapper').addClass('icon-left');
                $('.dropdown-menu-wrapper').removeClass('icon-right');
            } else {
                $('.dropdown-menu-wrapper').addClass('icon-right');
                $('.dropdown-menu-wrapper').removeClass('icon-left');
            }
        })
    });


    var effects = {
        'skin1_menu_effect': '.menu-skin1',
        'skin2_menu_effect': '.menu-skin2',
        'skin3_menu_effect': '.menu-skin3',
    };

    $.each(effects, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (effect) {
                if (effect) {
                    $($selector).each(function () {
                        $(this).addClass(effect);
                        if (effect == 'scale-eff') {
                            $(this).removeClass('bottom-scale-eff');
                        }
                    })
                } else {
                    $($selector).each(function () {
                        $(this).removeClass('scale-eff bottom-scale-eff');
                    })
                }
            })
        });
    })

    var arrows = {
        'skin1_menu_arrow': '.menu-skin1',
        'skin2_menu_arrow': '.menu-skin2',
        'skin3_menu_arrow': '.menu-skin3',
    };

    $.each(arrows, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (active) {
                $($selector).each(function () {
                    if (active) {
                        $(this).addClass('sf-arrows');
                    } else {
                        $(this).removeClass('sf-arrows');
                    }
                })
            })
        });
    })

    var dividers = {
        'skin1_menu_divider': '.menu-skin1',
        'skin2_menu_divider': '.menu-skin2',
        'skin3_menu_divider': '.menu-skin3',
    };

    $.each(dividers, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (active) {
                $($selector).each(function () {
                    if (active) {
                        $(this).addClass('sf-dividers');
                    } else {
                        $(this).removeClass('sf-dividers');
                    }
                })
            })
        });
    })
    var divider_colors = {
        'skin1_menu_divider_color': '.menu-skin1.sf-dividers ul a',
        'skin2_menu_divider_color': '.menu-skin2.sf-dividers ul a',
        'skin3_menu_divider_color': '.menu-skin3.sf-dividers ul a',
    };

    $.each(divider_colors, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (color) {
                var css = '';
                css += 'border-color: ' + color + ';';
                css = $selector + '{' + css + '}';

                appendStyle($setting, css);
            })
        });
    })

    var menu_active_colors = {
        'skin1_menu_ancestor_color': '.menu-skin1 > li.current-menu-item > a,.menu-skin1 > li.current-menu-ancestor > a,.menu-skin1 > li > a:hover,.menu-skin1 > li:hover > a',
        'skin2_menu_ancestor_color': '.menu-skin2 > li.current-menu-item > a,.menu-skin2 > li.current-menu-ancestor > a,.menu-skin2 > li > a:hover,.menu-skin2 > li:hover > a',
        'skin3_menu_ancestor_color': '.menu-skin3 > li.current-menu-item > a,.menu-skin3 > li.current-menu-ancestor > a,.menu-skin3 > li > a:hover,.menu-skin3 > li:hover > a',
    };

    $.each(menu_active_colors, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (color) {
                var css = '';
                if (!color) {
                    color = wp.customize.instance('primary_color').get();
                }
                css += 'color: ' + color + ';';
                css = $selector + '{' + css + '}';
                if (-1 != $setting.indexOf('skin1')) {
                    css += '.menu-skin1 > li > a:before {' + 'background-color: ' + color + '; }';
                } else if (-1 != $setting.indexOf('skin2')) {
                    css += '.menu-skin2 > li > a:before {' + 'background-color: ' + color + '; }';
                } else if (-1 != $setting.indexOf('skin3')) {
                    css += '.menu-skin3 > li > a:before {' + 'background-color: ' + color + '; }';
                }
                appendStyle($setting, css);
            })
        });
    })

    var labels = {
        'log_in_label': '.login-link',
        'log_out_label': '.logout-link',
        'register_label': '.register-link',
    };
    $.each(labels, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (label) {
                $($selector).html(label);
            })
        });
    });

    wp.customize('show_register_label', function (e) {
        e.bind(function (active) {
            if (active) {
                $('.login-link ~ span').removeClass('d-none');
                $('.register-link').removeClass('d-none');
            } else {
                $('.login-link ~ span').addClass('d-none');
                $('.register-link').addClass('d-none');
            }
        })
    });

    wp.customize('shop_icons_spacing', function (e) {
        e.bind(function (spacing) {
            if (spacing && !spacing.replace(/(|-)[0-9.]+/, '').trim()) {
                spacing += 'px';
            }
            var css = '';
            css += 'margin-left: ' + (spacing ? spacing : '3rem') + ';';
            css = '.header .shop-icon + .shop-icon {' + css + '}';

            var divider = '';
            divider = 'margin: 0 ' + (spacing ? spacing : '3rem') + ';';
            divider = '.header .shop-icons .divider {' + divider + '}';

            css += divider;
            appendStyle('shop_icons_spacing', css);
        })
    });

    wp.customize('shop_icons_divider', function (e) {
        e.bind(function (active) {
            var spacing = wp.customize.instance('shop_icons_spacing').get();
            if (active) {
                $('.header .shop-icon').addClass('m-0');
                $('.shop-icon:not(:first-of-type)').before('<span class="divider"></span>');
            } else {
                $('.shop-icons .divider').remove();
                $('.header .shop-icon').removeClass('m-0');
            }
        })
    });

    wp.customize('shop_icon_label_hide', function (e) {
        e.bind(function (active) {
            if (active) {
                $('.shop-icon').addClass('label-hidden');
            } else {
                $('.shop-icon').removeClass('label-hidden');
            }
        })
    });

    var labels = {
        'shop_icon_label_account': '.account .custom-label',
        'shop_icon_label_cart': '.cart .custom-label',
        'shop_icon_label_wishlist': '.wishlist .custom-label',
        'footer_copyright': '.footer .footer-copyright',
    };
    $.each(labels, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (html) {
                $($selector).html(html);
            })
        });
    })

    var icons = {
        'shop_icon_class_account': '.account i',
        'shop_icon_class_cart': '.cart i',
        'shop_icon_class_wishlist': '.wishlist i',
        'log_icon_class': '.account-links i',
    };
    $.each(icons, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (class_name) {
                if (class_name) {
                    $($selector).attr('class', class_name);
                } else {
                    if ($setting == 'shop_icon_class_account') {
                        $($selector).attr('class', 'icon-user');
                    } else if ($setting == 'shop_icon_class_cart') {
                        $($selector).attr('class', 'icon-shopping-cart');
                    } else if ($setting == 'shop_icon_class_wishlist') {
                        $($selector).attr('class', 'icon-heart-o');
                    }
                }
            })
        });
    })

    wp.customize('shop_icon_cart_price_show', function (e) {
        e.bind(function (active) {
            if (!active) {
                $('.shop-icon.cart').addClass('price-hidden');
            } else {
                $('.shop-icon.cart').removeClass('price-hidden');
            }
        })
    });

    wp.customize('shop_icon_label_type', function (e) {
        e.bind(function (active) {
            if (active) {
                $('.shop-icon').addClass('hdir');
            } else {
                $('.shop-icon').removeClass('hdir');
            }
        })
    });

    var fonts = {
        'footer_font': 'footer',
        'footer_font_heading': '.footer h1, .footer h2, .footer h3, .footer h4, .footer h5, .footer h6, .footer .widget-title',
        'footer_font_paragraph': '.footer p, .footer a, .footer .menu li a',
        'font_base': 'body',
        'font_base_mobile': 'body',
        'font_heading_h1': 'h1',
        'font_heading_h2': 'h2',
        'font_heading_h3': 'h3',
        'font_heading_h4': 'h4',
        'font_heading_h5': 'h5',
        'font_heading_h6': 'h6',
        'font_paragraph': 'p',
        'font_placeholder': 'input::placeholder, textarea::placeholder',
        'skin1_menu_ancestor_font': '.menu-skin1 > .menu-item > a',
        'skin1_menu_subtitle_font': '.menu.menu-skin1 .menu-subtitle > a',
        'skin1_menu_item_font': '.menu-skin1 li > a',
        'skin2_menu_ancestor_font': '.menu-skin2 > .menu-item > a',
        'skin2_menu_subtitle_font': '.menu.menu-skin2 .menu-subtitle > a',
        'skin2_menu_item_font': '.menu-skin2 li > a',
        'skin3_menu_ancestor_font': '.menu-skin3 > .menu-item > a',
        'skin3_menu_subtitle_font': '.menu.menu-skin3 .menu-subtitle > a',
        'skin3_menu_item_font': '.menu-skin3 li > a',
        'font_entry_meta': '.posts .entry-meta',
        'font_entry_title': '.posts .entry-title',
        'font_entry_cat': '.posts .entry-cats',
        'font_entry_excerpt': '.posts .entry-content p',
        'font_entry_view_more': '.read-more',
        'font_single_meta': '.post-single > .post .entry-meta',
        'font_single_title': '.post-single > .post .entry-title',
        'font_single_cat': '.post-single > .post .entry-cats',
        'font_product_cat': '.products .product-cat',
        'font_product_title': '.products .product-title',
        'font_product_price': '.products .product-wrap .product .price',
    };
    $.each(fonts, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (font) {
                var css = '';
                var add = '';
                if ('font_base' == $setting) {
                    add += 'font-size: ' + (font['font-size'] ? font['font-size'] : '10px') + ';';
                    add += 'line-height: ' + (font['line-height'] ? font['line-height'] : '1.86') + ';';
                    add += 'letter-spacing: ' + (font['letter-spacing'] ? font['letter-spacing'] : '0') + ';';
                    add = 'html {' + add + '}';
                }
                if ('font_base_mobile' == $setting) {
                    add += 'font-size: ' + (font['font-size'] ? font['font-size'] : '9px') + ';';
                    add += 'line-height: ' + (font['line-height'] ? font['line-height'] : '1.3') + ';';
                    add += 'letter-spacing: ' + (font['letter-spacing'] ? font['letter-spacing'] : '0') + ';';
                    add = '@media ( max-width: 575px ) { html {' + add + '} }';
                }
                css += 'font-family: ' + ('inherit' == font['font-family'] ? 'inherit' : ('"' + font['font-family'] + '"')) + ';';
                if (font['font-weight']) {
                    css += 'font-weight: ' + font['font-weight'] + ';';
                }
                if ('font_base' != $setting) {
                    if (font['font-size']) {
                        css += 'font-size: ' + font['font-size'] + ';';
                    }
                    if (font['line-height']) {
                        css += 'line-height: ' + font['line-height'] + ';';
                    }
                    if (font['letter-spacing']) {
                        css += 'letter-spacing: ' + font['letter-spacing'] + ';';
                    }
                }
                css += 'color: ' + (font['color'] ? font['color'] : 'inherit') + ';';
                if (font['text-transform']) {
                    css += 'text-transform: ' + font['text-transform'] + ';';
                }
                if (font['font-style']) {
                    css += 'font-style: ' + font['font-style'] + ';';
                }
                css = $selector + ' {' + css + '}';
                css += add;
                appendStyle($setting, css);
            })
        });
    })

    var margins = {
        'skin1_menu_ancestor_margin': '.menu-skin1 > .menu-item > a',
        'skin1_menu_subtitle_margin': '.menu.menu-skin1 .menu-subtitle > a',
        'skin1_menu_item_margin': '.menu-skin1 li > a',
        'skin2_menu_ancestor_margin': '.menu-skin2 > .menu-item > a',
        'skin2_menu_subtitle_margin': '.menu.menu-skin2 .menu-subtitle > a',
        'skin2_menu_item_margin': '.menu-skin2 li > a',
        'skin3_menu_ancestor_margin': '.menu-skin3 > .menu-item > a',
        'skin3_menu_subtitle_margin': '.menu.menu-skin3 .menu-subtitle > a',
        'skin3_menu_item_margin': '.menu-skin3 li > a',
    };
    $.each(margins, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (spacing) {
                var css = '';
                if ('' !== spacing['top']) {
                    if (!spacing['top'].replace(/(|-)[0-9.]+/, '').trim()) {
                        spacing['top'] += 'px';
                    }
                    css += 'margin-top: ' + spacing['top'] + ';';
                }
                if ('' !== spacing['right']) {
                    if (!spacing['right'].replace(/(|-)[0-9.]+/, '').trim()) {
                        spacing['right'] += 'px';
                    }
                    css += 'margin-right: ' + spacing['right'] + ';';
                }
                if ('' !== spacing['bottom']) {
                    if (!spacing['bottom'].replace(/(|-)[0-9.]+/, '').trim()) {
                        spacing['bottom'] += 'px';
                    }
                    css += 'margin-bottom: ' + spacing['bottom'] + ';';
                }
                if ('' !== spacing['left']) {
                    if (!spacing['left'].replace(/(|-)[0-9.]+/, '').trim()) {
                        spacing['left'] += 'px';
                    }
                    css += 'margin-left: ' + spacing['left'] + ';';
                }
                css = $selector + ' {' + css + '}';
                appendStyle($setting, css);
            })
        });
    })

    var paddings = {
        'skin1_menu_ancestor_padding': '.menu-skin1 > .menu-item > a',
        'skin1_menu_subtitle_padding': '.menu.menu-skin1 .menu-subtitle > a',
        'skin1_menu_item_padding': '.menu-skin1 li > a',
        'skin2_menu_ancestor_padding': '.menu-skin2 > .menu-item > a',
        'skin2_menu_subtitle_padding': '.menu.menu-skin2 .menu-subtitle > a',
        'skin2_menu_item_padding': '.menu-skin2 li > a',
        'skin3_menu_ancestor_padding': '.menu-skin3 > .menu-item > a',
        'skin3_menu_subtitle_padding': '.menu.menu-skin3 .menu-subtitle > a',
        'skin3_menu_item_padding': '.menu-skin3 li > a',
    };
    $.each(paddings, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (spacing) {
                var css = '';
                if ('' !== spacing['top']) {
                    if (!spacing['top'].replace(/(|-)[0-9.]+/, '').trim()) {
                        spacing['top'] += 'px';
                    }
                    css += 'padding-top: ' + spacing['top'] + ';';
                }
                if ('' !== spacing['right']) {
                    if (!spacing['right'].replace(/(|-)[0-9.]+/, '').trim()) {
                        spacing['right'] += 'px';
                    }
                    css += 'padding-right: ' + spacing['right'] + ';';
                }
                if ('' !== spacing['bottom']) {
                    if (!spacing['bottom'].replace(/(|-)[0-9.]+/, '').trim()) {
                        spacing['bottom'] += 'px';
                    }
                    css += 'padding-bottom: ' + spacing['bottom'] + ';';
                }
                if ('' !== spacing['left']) {
                    if (!spacing['left'].replace(/(|-)[0-9.]+/, '').trim()) {
                        spacing['left'] += 'px';
                    }
                    css += 'padding-left: ' + spacing['left'] + ';';
                }
                css = $selector + ' {' + css + '}';
                appendStyle($setting, css);
            })
        });
    })

    wp.customize('font_nav_color', function (e) {
        e.bind(function (color) {
            var css = 'color: ' + color + ';';
            css = 'a {' + css + '}';
            appendStyle('font_nav_color', css);
        })
    });

    wp.customize('scroll_top_icon', function (e) {
        e.bind(function (icon) {
            if (icon) {
                $('#scroll-top i').attr('class', icon);
            } else {
                $('#scroll-top i').attr('class', 'icon-arrow-up');
            }
        })
    });

    wp.customize('footer_bottom_dir', function (e) {
        e.bind(function (dir) {
            if (dir) {
                $('.footer-bottom').addClass('footer-vertical');
            } else {
                $('.footer-bottom').removeClass('footer-vertical');
            }
        })
    });

    // Layout options
    wp.customize('body_layout', function (e) {
        e.bind(function (layout) {
            if (layout == 'boxed') {
                $('body').addClass('boxed');
                $('body').removeClass('framed');
            } else if (layout == 'framed') {
                $('body').addClass('framed');
                $('body').removeClass('boxed');
            } else {
                $('body').removeClass('framed');
                $('body').removeClass('boxed');
            }
        })
    });

    var width = {
        'content_box_width': '.boxed .page-wrapper, .framed .page-wrapper',
    }
    $.each(width, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (width) {
                var css = '';
                css = 'width: ' + (width ? width : 1500) + 'px;';
                css = $selector + ' {' + css + '}';
                appendStyle($setting, css);
            })
        });
    })

    wp.customize('content_box_padding', function (e) {
        e.bind(function (padding) {
            var css = '';
            if (!padding.replace(/(|-)[0-9.]+/, '').trim()) {
                padding += 'px';
            }
            css = 'margin: ' + padding + ' auto;';
            css = '.framed .page-wrapper {' + css + '}';
            appendStyle('content_box_padding', css);
        })
    });

    wp.customize('product_label_type', function (e) {
        e.bind(function (type) {
            //      Molla.molla_vars.label_type = type;
            $('.product [class*="label-"]').each(function () {
                var $this = $(this);
                if (type == 'circle') {
                    $this.removeClass('label-polygon');
                    $this.addClass('label-circle');
                } else if (type == 'polygon') {
                    $this.removeClass('label-circle');
                    $this.addClass('label-polygon');
                } else {
                    $this.removeClass('label-circle');
                    $this.removeClass('label-polygon');
                }
            })
        })
    })
    var label_colors = {
        'featured_label_color_mode': '.product .product-label.label-hot',
        'new_label_color_mode': '.product .product-label.label-new',
        'sale_label_color_mode': '.product .product-label.label-sale',
        'outstock_label_color_mode': '.product .product-label.label-sale',
        'hurry_label_color_mode': '.product .product-label.label-hurry',
    };
    $.each(label_colors, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (mode) {
                var css = '';
                if (mode) {
                    css += 'background-color: ' + wp.customize.instance(mode).get() + ';';
                    css += 'border-color: ' + wp.customize.instance(mode).get() + ';';
                } else {
                    var st = $setting.replace('_mode', '');
                    css += 'color: ' + wp.customize.instance(st + '_text').get() + ';';
                    css += 'background-color: ' + wp.customize.instance(st).get() + ';';
                    css += 'border-color: ' + wp.customize.instance(st).get() + ';';
                }
                css = $selector + '{' + css + '}';

                appendStyle($setting, css);
            })
        })
    });

    var label_colors = {
        'featured_label_color': '.product .product-label.label-hot',
        'new_label_color': '.product .product-label.label-new',
        'sale_label_color': '.product .product-label.label-sale',
        'outstock_label_color': '.product .product-label.label-out',
        'hurry_label_color': '.product .product-label.label-hurry',
    };
    $.each(label_colors, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (color) {
                var css = '';
                css += 'color: ' + wp.customize.instance($setting + '_text').get() + ';';
                css += 'background-color: ' + color + ';';
                css += 'border-color: ' + color + ';';
                css = $selector + '{' + css + '}';


                appendStyle($setting + '_mode', css);
            })
        })
    });

    var label_text_colors = {
        'featured_label_color_text': '.product .product-label.label-hot',
        'new_label_color_text': '.product .product-label.label-new',
        'sale_label_color_text': '.product .product-label.label-sale',
        'outstock_label_color_text': '.product .product-label.label-out',
        'hurry_label_color_text': '.product .product-label.label-hurry',
    };
    $.each(label_text_colors, function ($setting, $selector) {
        wp.customize($setting, function (e) {
            e.bind(function (color) {
                var css = '';
                var st = $setting.replace('_text', '');
                css += 'color: ' + color + ';';
                css += 'background-color: ' + wp.customize.instance(st).get() + ';';
                css += 'border-color: ' + wp.customize.instance(st).get() + ';';
                css = $selector + '{' + css + '}';

                appendStyle(st + '_mode', css);
            })
        })
    });

})