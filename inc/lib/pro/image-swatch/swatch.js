( function ( $ ) {
	'use strict';

	// products archive
	$( 'body' ).on( 'click', '.products .product .nav-thumbs .nav-thumb', function ( e ) {
		var src = $( this ).attr( 'data-src' ),
			$media = $( this ).closest( '.product' ).find( '.product-media > a' );

		$( this ).siblings().removeClass( 'active' ).closest( '.nav-thumbs' ).siblings( '.nav-thumbs' ).find( '.nav-thumb' ).removeClass( 'active' );

		if ( $( this ).hasClass( 'active' ) ) {
			$( this ).removeClass( 'active' );
			$media.find( 'img.product-attr-image' ).remove();
		} else {
			$( this ).addClass( 'active' );

			if ( $media.find( 'img.product-attr-image' ).length ) {
				$media.find( 'img.product-attr-image' ).attr( 'src', src ? src : '' );
			} else if ( src ) {
				$media.find( 'img:first-of-type' ).after( '<img src="' + ( src ? src : '' ) + '" class="product-attr-image" width="300" height="300" style="position: absolute; left: 0; right: 0; top: 0; bottom: 0;">' );
			}
		}
	} )

	// single product page and quickview
	$( 'body' ).on( 'click', '.product .product-intro form.cart .nav-thumbs .nav-thumb', function ( e ) {
		$( '.product .product-intro  img.product-attr-image' ).remove();
		var $this = $( this ),
			src = $this.attr( 'data-full-src' ),
			$productIntro = $this.closest( '.product-intro' ),
			$productGalleryCarousel = $productIntro.find( '.product-gallery-carousel' ),
			$media = $productGalleryCarousel.find( ' .woocommerce-product-gallery__image > a' );

		e.stopPropagation();
		e.preventDefault();

		if ( !$media.length ) { // in quickview, not in single product page
			$media = $productGalleryCarousel.find( '.owl-item.active .woocommerce-product-gallery__image > a' );
		}

		$media.find( 'img.product-attr-image' ).remove();
		//		$media.siblings('.zoomContainer').remove();

		if ( !$this.hasClass( 'active' ) && src ) {
			var html = '<img src="' + src + '" class="product-attr-image" width="800" height="800" style="position: absolute; left: 0; right: 0; top: 0; bottom: 0;">';
			if ( $productGalleryCarousel.length ) {
				var activeItems = $productGalleryCarousel.find( ".owl-item.active" );
				activeItems.first().find( 'img:first-of-type' ).after( html );
				// if(activeItems.length > 1) {
				// 	activeItems.first().find('img:first-of-type').after(html);
				// } else {
				// 	activeItems.first().find('img:first-of-type').after(html);
				// }
			} else {
				$productIntro.find( 'product-main-image img:first-child' ).after( html );
			}
			//$media.find('img:first-of-type').after('<img src="' + src + '" class="product-attr-image" width="800" height="800" style="position: absolute; left: 0; right: 0; top: 0; bottom: 0;">');

			// var zoomOptions = {};
			// zoomOptions.zoomContainer = $media.parent();
			// Molla.elevateZoomInit($media.find('.product-attr-image'), zoomOptions);
		}
	} )

	$( 'body' ).on( 'click', '.product .product-intro .variations', function ( e ) {
		$( this ).closest( '.product-intro' ).find( ' .woocommerce-product-gallery__image > .zoomContainer' ).remove();
	} )

	$( document ).on( 'found_variation', '.variations_form', function ( e, args ) {
		$( '.product .product-intro  img.product-attr-image' ).remove();
	} );

	$( 'body' ).on( 'click', '.variations .reset_variations', function () {
		$( this ).closest( '.product-intro' ).find( 'img.product-attr-image' ).remove();
		$( this ).closest( '.product-intro' ).find( '.variations_form' ).trigger( 'reset_image' );
	} )

	$( 'body' ).on( 'click', function ( e ) {
		var $target = $( e.target );
		if ( ( $target.hasClass( 'product' ) && $target.closest( '.products' ).length ) || $target.closest( '.products .product' ).length ) {
			return;
		}

		$( '.products .product' ).each( function ( e ) {
			$( this ).find( '.nav-thumb.active' ).trigger( 'click' );
		} )
	} )
} )( jQuery );
