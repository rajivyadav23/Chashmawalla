jQuery(document).ready(function($) {
    'use strict';

    var infinite_scroll = false;

    $(window).scroll(function() {
        setTimeout(function() {
            var ws = window.pageYOffset, // Window Scroll Position
                wh = window.innerHeight, // Screen Height
                th = $('html').outerHeight(); // Total Site Height

            $('.infinite-scroll').each(function() {
                var et = $(this).offset().top, // Element Scroll Position
                    eh = $(this).outerHeight(); //  Element Height

                if ( ! infinite_scroll && ( ws + wh >= et + eh + 100 || (ws + wh >= th && et < ws + wh) ) ) {
                    var $this = $(this);
                    infinite_scroll = true;

                    if ( $this.hasClass('products') ) {
                        var $loadmore = $this.closest('.yit-wcan-container').siblings('.more-container').children('.btn-more');
                        if (!$loadmore.length) {
                            $loadmore = $this.closest('.woocommerce').find('.more-container').children('.btn-more');
                        }

                        $(document).on('molla_before_ajax_load_products', function(e, $products, $loadmore) {
                            if ( $products.hasClass('infinite-scroll') ) {
                                $loadmore.parent().addClass('molla-loading');
                                $products.removeClass('molla-loading');
                            }
                        })
                        $loadmore.trigger('click');
                        $(document).on('molla_ajax_load_products_success', function(e, $products, $loadmore) {
                            if ( $products.hasClass('infinite-scroll') ) {
                                infinite_scroll = false;
                                $loadmore.parent('.more-container').removeClass('molla-loading');
                                var $result = $products.closest('.woocommerce').find('.woocommerce-result-count');

                                var props = JSON.parse($products.attr('data-props'));
                                if ( props.extra_atts.page == props.total_pages ) {
                                    $this.removeClass('infinite-scroll');
                                    $loadmore.parent('.more-container').hide();
                                    $result.children(':not(.to):not(.total)').remove();
                                    $result.children('.to').text('all ');
                                } else {
                                    $result.children('.to').text($products.children().length);
                                }
                            }
                        })
                    } else if ( $this.hasClass('posts') ) {
                        var $loadmore = $this.siblings('.more-container').children('.btn-more');

                        $loadmore.parent().addClass('molla-loading');

                        $(document).on('molla_before_ajax_load_posts', function(e, $posts, $loadmore) {
                            if ( $posts.hasClass('infinite-scroll') ) {
                                $posts.removeClass('molla-loading');
                            }
                        })
                        $loadmore.trigger('click');
                        $(document).on('molla_ajax_load_posts_success', function(e, $posts, $loadmore) {
                            if ( $posts.hasClass('infinite-scroll') ) {
                                infinite_scroll = false;
                                $loadmore.parent('.more-container').removeClass('molla-loading');

                                var props = JSON.parse($posts.attr('data-props')),
                                    data = JSON.parse($posts.attr('data-page-props'));
                                if ( Number(data.paged) >= Number(props.max_num_pages) ) {
                                    $this.removeClass('infinite-scroll');
                                    $loadmore.parent('.more-container').hide();
                                }
                            }
                        })
                    }
                }
            })
        }, 100);
    })
})
