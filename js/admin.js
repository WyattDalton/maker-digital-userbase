(function($) {

$('.ub_settings_page_controls button').on( 'click', function(e){

    const el = e.target;
    let div = $( el ).data( 'for' );
        div = '#' + div;
    console.log( div);
        
    $( el ).attr( 'aria-expanded', 'true' );
    $( el ).siblings().attr( 'aria-expanded', 'false' );

    if( $( div ).hasClass( 'hidden' ) ) {
        $( div ).removeClass( 'hidden' ).addClass( 'displayed' );
        $( div ).siblings( '.ub_settings_panel' ).removeClass( 'displayed' ).addClass( 'hidden' );
    }
});

})( jQuery );