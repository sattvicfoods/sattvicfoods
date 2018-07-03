/**
 * Functionality specific to Circumference.
 *
 * Provides helper functions to enhance the theme experience.
 */

( function( $ ) {
	var body    = $( 'body' ),
	    _window = $( window );


	/**
	 * Enables menu toggle for small screens.
	 */
	( function() {
		var nav = $( '#site-navigation' ), button, menu;
		if ( ! nav )
			return;

		button = nav.find( '.menu-toggle' );
		if ( ! button )
			return;

		// Hide button if menu is missing or empty.
		menu = nav.find( '.nav-menu' );
		if ( ! menu || ! menu.children().length ) {
			button.hide();
			return;
		}

		$( '.menu-toggle' ).on( 'click.circumference', function() {
			nav.toggleClass( 'toggled-on' );
		} );
	} )();

	/*
	 * Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	_window.on( 'hashchange.circumference', function() {
		var element = document.getElementById( location.hash.substring( 1 ) );

		if ( element ) {
			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
				element.tabIndex = -1;
			}

			element.focus();

			// Repositions the window on jump-to-anchor to account for header height.
			window.scrollBy( 0, -80 );
		}
	} );


( function() {
	var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
	    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
	    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

	if ( ( is_webkit || is_opera || is_ie ) && 'undefined' !== typeof( document.getElementById ) ) {
		var eventMethod = ( window.addEventListener ) ? 'addEventListener' : 'attachEvent';
		window[ eventMethod ]( 'hashchange', function() {
			var element = document.getElementById( location.hash.substring( 1 ) );

			if ( element ) {
				if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) )
					element.tabIndex = -1;

				element.focus();
			}
		}, false );
	}
})();


// lets add some bootstrap styling to WordPress elements starting with the tables

jQuery(function($){
	$( 'table' ).addClass( 'table' );
	$( '#submit' ).addClass( 'btn btn-sm' );
	$( '#bbpress-forums button' ).addClass( 'btn btn-sm' );
	$( '#bbp_search_submit').addClass( 'btn' );
	$( '#bbp_search' ).addClass( 'form-control' );
	$( '#bbp-search-form' ).addClass( 'input-group' );
	$( '#bbp_topic_title' ).addClass( 'form-control' );
	$( '#bbp_topic_content' ).addClass( 'form-control' );
	$( '#bbp_topic_tags' ).addClass( 'form-control' );
	$( '#bbpress-forums select' ).addClass( 'form-control' );
	$( '#bbp_topic_submit', '.subscription-toggle' ).addClass( 'btn' );
	$( '.subscription-toggle' ).addClass( 'btn btn-sm' );
	$( '#bbp_anonymous_author' ).addClass( 'form-control col-md-6' );
	$( '#bbp_anonymous_email' ).addClass( 'form-control col-md-6' );
	$( '#bbp_anonymous_website' ).addClass( 'form-control col-md-6' );
	
});

//adding move to top featured

    //Scroll To Top
    var window_height = $(window).height();
    var window_height = (window_height) + (50);
    
    

    $(window).scroll(function() {
        var scroll_top = $(window).scrollTop();
        if (scroll_top > window_height) {
            $('.circumference_move_to_top').show();
        }
        else {
            $('.circumference_move_to_top').hide();   
        }
    });
    $('.circumference_move_to_top').click(function(){
        $('html, body').animate({scrollTop:0}, 'slow');
        return false;
        
    });

    //making of search bottom
	$( '.cir-search-bottom' ).click(function( event) {
		$('.cir-overlay-form' ).show();
	});
	$( '#cancel-search-form' ).click(function() {
		//event.preventDefault();
		$('.cir-overlay-form' ).hide();
		
	});

	
} )( jQuery );



