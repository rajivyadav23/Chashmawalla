( function ( $ ) {
	'use strict';

	var swatches = $( '.swatch_type' );
	swatches.each( function () {
		$( this ).on( 'change', function () {
			var $this = $( this ),
				value = $this.val(),
				$swatch_panel = $this.closest( '.swatch_type_select' ).siblings( '.product_custom_swatches' );

			if ( 'default' == value ) {
				$swatch_panel.hide();
			} else {
				$swatch_panel.show();
			}
		} ).change();
	} );

	$( 'body' ).on( 'change', '#swatch_product_options .swatch_type_select .swatch_type', function ( e ) {
		if ( 'image' == $( this ).val() ) {
			$( this ).siblings( 'p' ).slideUp( 'fast' );
		} else {
			$( this ).siblings( 'p' ).slideDown( 'fast' );
		}
	} )

	$( document ).on( 'molla_after_upload_image', function ( e, attachment, clickedId ) {
		$( '#swatch_product_options table td#' + clickedId + ' img' ).attr( 'src', attachment.url );
		$( '#swatch_product_options table td#' + clickedId + ' .upload_image_url' ).val( attachment.id );
	} )

	$( document ).on( 'molla_after_remove_image', function ( e, clickedId ) {
		$( '#swatch_product_options table td#' + clickedId + ' img' ).attr( 'src', lib_swatch_admin.placeholder );
		$( '#swatch_product_options table td#' + clickedId + ' .upload_image_url' ).val( '' );
	} )
} )( jQuery );
