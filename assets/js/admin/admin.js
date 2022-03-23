jQuery( document ).ready( function ( $ ) {
    'use strict';

    var save_menu_offset = 0;

    $( '.walker-megamenu input' ).on( 'change', function () {

        var $this = $( this );
        var $megamenu_width = $this.closest( '.walker-megamenu' ).siblings( '.walker-megamenu-width, .walker-megamenu-col' );

        if ( !$this.prop( 'checked' ) )
            $megamenu_width.hide();
        else
            $megamenu_width.show();
    } )

    $( '.walker-grid-col input, .walker-subtitle input' ).on( 'change', function () {
        var $this = $( this );
        if ( $this.closest( '.description' ).hasClass( 'walker-grid-col' ) ) {
            var $grid = $this,
                $subtitle = $this.closest( '.description' ).siblings( '.walker-subtitle' ).find( 'input' );
        }
        else if ( $this.closest( '.description' ).hasClass( 'walker-subtitle' ) ) {
            var $grid = $this.closest( '.description' ).siblings( '.walker-grid-col' ).find( 'input' ),
                $subtitle = $this;
        }

        var $block = $this.closest( '.description' ).siblings( '.walker-block-slug' );

        if ( !$grid.prop( 'checked' ) && !$subtitle.prop( 'checked' ) )
            $block.show();
        else
            $block.hide();
    } )

    $( '.menu-item-depth-0 .walker-megamenu input' ).each( function () {
        var $this = $( this );
        var $megamenu_width = $this.closest( '.walker-megamenu' ).siblings( '.walker-megamenu-width, .walker-megamenu-col' );

        if ( !$this.prop( 'checked' ) )
            $megamenu_width.hide();
        else
            $megamenu_width.show();
    } );

    $( '.walker-grid-col input, .walker-subtitle input' ).each( function () {
        var $this = $( this );

        if ( $this.closest( '.description' ).hasClass( 'walker-grid-col' ) ) {
            var $grid = $this,
                $subtitle = $this.closest( '.description' ).siblings( '.walker-subtitle' ).find( 'input' );
        }
        else if ( $this.closest( '.description' ).hasClass( 'walker-subtitle' ) ) {
            var $grid = $this.closest( '.description' ).siblings( '.walker-grid-col' ).find( 'input' ),
                $subtitle = $this;
        }

        var $block = $this.closest( '.description' ).siblings( '.walker-block-slug' );

        if ( !$grid.prop( 'checked' ) && !$subtitle.prop( 'checked' ) )
            $block.show();
        else
            $block.hide();
    } );


    if ( $( '#save_menu_header' ).length ) {
        save_menu_offset = $( '#save_menu_header' ).offset().top;
    }

    $( window ).on( 'scroll resize', function () {
        if ( !$( '#save_menu_header' ).length ) {
            return;
        }

        if ( $( window ).scrollTop() > save_menu_offset ) {
            $( '.menu-save, #menu-settings-column' ).addClass( 'fixed' );
        }
        else
            $( '.menu-save, #menu-settings-column' ).removeClass( 'fixed' );
    } )

    var file_frame;
    var clickedID;

    $( '.button_upload_image' ).on( 'click', function ( event ) {

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( !file_frame ) {
            // Create the media frame.
            file_frame = wp.media.frames.downloadable_file = wp.media( {
                title: 'Choose an image',
                button: {
                    text: 'Use image'
                },
                multiple: false
            } );
        }

        file_frame.open();

        clickedID = $( this ).attr( 'id' );

        // When an image is selected, run a callback.
        file_frame.on( 'select', function () {
            var attachment = file_frame.state().get( 'selection' ).first().toJSON();
            $( '#' + clickedID ).val( attachment.url );
            if ( $( '#' + clickedID ).attr( 'data-name' ) )
                $( '#' + clickedID ).attr( 'name', $( '#' + clickedID ).attr( 'data-name' ) );

            $( document ).trigger( 'molla_after_upload_image', [ attachment, clickedID ] );

            file_frame.close();
        } );
    } );

    $( '.button_attach_image' ).on( 'click', function ( event ) {

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( !file_frame ) {
            // Create the media frame.
            file_frame = wp.media.frames.downloadable_file = wp.media( {
                title: 'Choose an image',
                button: {
                    text: 'Use image'
                },
                multiple: false
            } );
        }

        file_frame.open();

        clickedID = $( this ).attr( 'id' );

        // When an image is selected, run a callback.
        file_frame.on( 'select', function () {
            var attachment = file_frame.state().get( 'selection' ).first().toJSON();

            $( '#' + clickedID ).val( attachment.id );
            $( '#' + clickedID + '_thumb' ).html( '<img src="' + attachment.url + '"/>' );
            if ( $( '#' + clickedID ).attr( 'data-name' ) )
                $( '#' + clickedID ).attr( 'name', $( '#' + clickedID ).attr( 'data-name' ) );

            file_frame.close();
        } );
    } );

    $( '.button_remove_image' ).on( 'click', function ( event ) {

        var clickedID = $( this ).attr( 'id' );
        $( '#' + clickedID ).val( '' );
        $( '#' + clickedID + '_thumb' ).html( '' );

        $( document ).trigger( 'molla_after_remove_image', clickedID );

        return false;
    } );
    if ( $.fn.wpColorPicker ) {
        $( 'input.color-picker' ).wpColorPicker();
    }
} );

jQuery( function ( $ ) {
    'use strict';

    function MollaMenuDepth( $classes ) {
        for ( var i = 0; i < $classes.length; i++ ) {
            if ( $classes[ i ].indexOf( 'menu-item-depth-' ) >= 0 ) {
                var depth = $classes[ i ].split( 'menu-item-depth-' );
                return parseInt( depth[ 1 ] );
            }
        }
    }
    function updateMollaMenuOptions( elem ) {
        var $this = elem,
            $next = $this.next( '.menu-item' );

        var classes = $this.attr( 'class' ).split( ' ' );
        var curdepth = MollaMenuDepth( classes );

        if ( $next.attr( 'class' ) ) {
            classes = $next.attr( 'class' ).split( ' ' );
        }
        var depth = MollaMenuDepth( classes );

        if ( !curdepth || curdepth < depth ) {
            $this.find( '.walker-block-slug' ).addClass( 'd-none' );
        } else {
            $this.find( '.walker-block-slug' ).removeClass( 'd-none' );
        }
    }

    $( "#menu-to-edit" ).on( "sortstop", function ( event, ui ) {
        var menu_item = ui.item;
        setTimeout( function () {
            updateMollaMenuOptions( menu_item );
        }, 200 );
    } )

    $( '.menu-item' ).each( function () {
        updateMollaMenuOptions( $( this ) );
    } )

    jQuery( document ).ready( function ( $ ) {
        if ( $( '#cat_sidebar_pos' ).val() == 'top' ) {
            $( '#cat_sidebar_pos' ).closest( '.form-field' ).siblings( '.form-sidebar-op' ).hide();
        }

        $( '.parent-option' ).each( function () {
            var classes = $( this )[ 0 ].classList,
                target = classes[ classes.length - 1 ].replace( 'option-', '' ),
                val = $( '#' + target ).val();
            if ( !val || 'hide' == val ) {
                $( '.sub-option-' + target ).each( function () {
                    $( this ).hide();
                } )
            }
        } )
        var $layoutbox = $( '#page-layout-mode.postbox' );
        if ( $layoutbox.length ) {
            if ( $layoutbox.find( 'select' ).val() ) {
                $layoutbox.siblings( '#page-content' ).find( '.rwmb-meta-box .rwmb-field:not(:nth-of-type(1)):not(:nth-of-type(2))' ).hide();
                $layoutbox.siblings( '#page-layout' ).hide();
            }
        }

        if ( $( '#single_product_layout' ).length ) {
            var single_layout = $( '#single_product_layout' ).val();
            if ( 'gallery' == single_layout ) {
                $( '#single_product_center' ).closest( '.rwmb-field' ).hide();
            } else {
                $( '#single_product_center' ).closest( '.rwmb-field' ).show();
            }
            if ( 'gallery' == single_layout || 'sticky' == single_layout || 'masonry_sticky' == single_layout ) {
                $( '#single_product_image' ).closest( '.rwmb-field' ).hide();
            } else {
                $( '#single_product_image' ).closest( '.rwmb-field' ).show();
            }
        }

        if ( $( 'body' ).hasClass( 'post-type-page' ) && $( '.editor-page-attributes__template select' ).length ) {
            var template = $( '.editor-page-attributes__template select' ).val();
            if ( -1 != template.indexOf( 'container-fluid' ) ) {
                $( '.editor-styles-wrapper' ).removeClass( 'container' ).addClass( 'container-fluid' );
            } else if ( -1 != template.indexOf( 'container' ) ) {
                $( '.editor-styles-wrapper' ).removeClass( 'container-fluid' ).addClass( 'container' );
            } else if ( !template ) {
                $( '.editor-styles-wrapper' ).removeClass( 'container-fluid container' ).addClass( theme.page_width );
            } else {
                $( '.editor-styles-wrapper' ).removeClass( 'container-fluid container' );
            }
        }
        if ( $( 'body' ).hasClass( 'post-type-post' ) ) {
            $( '.editor-styles-wrapper' ).addClass( theme.post_width );
            if ( $( '.rwmb-field #page_width' ).length ) {
                var template = $( '.rwmb-field #page_width' ).val();
                if ( 'container' == template ) {
                    $( '.editor-styles-wrapper' ).removeClass( 'container container-fluid' ).addClass( 'container' );
                } else if ( 'container-fluid' == template ) {
                    $( '.editor-styles-wrapper' ).removeClass( 'container container-fluid' ).addClass( 'container-fluid' );
                }
            }
        }
    } )

    $( '#page-layout-mode.postbox select' ).on( 'change', function () {
        var val = $( this ).val();
        if ( val ) {
            $( this ).closest( '.postbox' ).siblings( '#page-content' ).find( '.rwmb-meta-box .rwmb-field:not(:nth-of-type(1)):not(:nth-of-type(2))' ).hide();
            $( this ).closest( '.postbox' ).siblings( '#page-layout' ).hide();
        } else {
            $( this ).closest( '.postbox' ).siblings( '#page-content' ).find( '.rwmb-meta-box .rwmb-field' ).show();
            $( this ).closest( '.postbox' ).siblings( '#page-layout' ).show();
        }
    } )

    $( '.parent-option select, .parent-option input' ).on( 'change', function () {
        var val = $( this ).val(),
            classes = $( this ).closest( '.parent-option' ).attr( 'class' ),
            target = classes.slice( classes.indexOf( 'option-' ) );

        $( '.sub-' + target ).each( function () {
            if ( !val || 'hide' == val ) {
                $( this ).hide();
            } else {
                $( this ).show();
            }
        } )
    } )

    $( '#single_product_layout' ).on( 'change', function () {
        var single_layout = $( this ).val();
        if ( 'gallery' == single_layout ) {
            $( '#single_product_center' ).closest( '.rwmb-field' ).hide();
        } else {
            $( '#single_product_center' ).closest( '.rwmb-field' ).show();
        }
        if ( 'gallery' == single_layout || 'sticky' == single_layout || 'masonry_sticky' == single_layout ) {
            $( '#single_product_image' ).closest( '.rwmb-field' ).hide();
        } else {
            $( '#single_product_image' ).closest( '.rwmb-field' ).show();
        }
    } )

    $( '#cat_sidebar_pos' ).on( 'change', function () {
        if ( $( this ).val() == 'top' ) {
            $( this ).closest( '.form-field' ).siblings( '.form-sidebar-op' ).hide();
        } else {
            $( this ).closest( '.form-field' ).siblings( '.form-sidebar-op' ).show();
        }
    } )

    $( '.post-type-page' ).on( 'change', '.editor-page-attributes__template select', function () {
        var template = $( this ).val();
        if ( -1 != template.indexOf( 'container-fluid' ) ) {
            $( '.editor-styles-wrapper' ).removeClass( 'container' ).addClass( 'container-fluid' );
        } else if ( -1 != template.indexOf( 'container' ) ) {
            $( '.editor-styles-wrapper' ).removeClass( 'container-fluid' ).addClass( 'container' );
        } else if ( !template ) {
            $( '.editor-styles-wrapper' ).removeClass( 'container-fluid container' ).addClass( theme.page_width );
        } else {
            $( '.editor-styles-wrapper' ).removeClass( 'container-fluid container' );
        }
    } )

    $( '.post-type-post' ).on( 'change', '.rwmb-field #page_width', function () {
        var template = $( this ).val();
        if ( 'container' == template ) {
            $( '.editor-styles-wrapper' ).removeClass( 'container-fluid' ).addClass( 'container' );
        } else if ( 'container-fluid' == template ) {
            $( '.editor-styles-wrapper' ).removeClass( 'container' ).addClass( 'container-fluid' );
        } else {
            $( '.editor-styles-wrapper' ).addClass( theme.post_width );
        }
    } )
} );