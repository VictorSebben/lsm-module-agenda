var DateTime = {
    dateKeyDown: function dateKeyDown( evt, obj ) {
        var c = evt.keyCode;
        var val = obj.value;

        if ( val.length >= 10 && c !== 8 && c !== 9 && c !== 13 && c !== 39 && c !== 37 ) {
            evt.preventDefault();
        }
    },
    dateKeyUp: function dateKeyUp( evt, obj ) {
        var val = obj.value;
        var tmp = '';

        // If it is not the backspace
        if ( evt.keyCode !== 8 ) {
            val = val.replace( /[^0-9]/g, '' );

            for ( var i = 0; i < val.length; ++i ) {
                tmp += ( i === 1 || i === 3 ) ? val[ i ] + '/' : val[ i ];

                if ( tmp.length === 10 ) {
                    // Let's make sure the date the user typed in is valid.
                    // When she finishes typing it, we'll create a Date object,
                    // which will turn it into a valid date if it is not.
                    var date = tmp.split( '/' );
                    var validDate = new Date( date[ 2 ], date[ 1 ] - 1, date[ 0 ] );

                    if ( ! isNaN( validDate.getDate() ) || ! isNaN( validDate.getMonth() )
                        || ! isNaN( validDate.getFullYear() ) )
                    {
                        var date = '' + validDate.getDate();
                        if ( date.length === 1 ) {
                            date = '0' + date;
                        }

                        var month = '' + ( validDate.getMonth() + 1 );
                        if ( month.length === 1 ) {
                            month = '0' + month;
                        }

                        tmp = date + '/'
                            + month + '/'
                            + validDate.getFullYear();
                    }
                    else {
                        tmp = '';
                    }
                }
            }

            if ( evt.keyCode === 0 ) {
                tmp = val;
            }

            obj.value = tmp;
        }
    },
    timeKeyDown: function timeKeyDown( evt, obj ) {
        var c = evt.keyCode;
        var val = obj.value;

        if ( val.length >= 5 && c !== 8 && c !== 9 && c !== 13 && c !== 39 && c !== 37 ) {
            evt.preventDefault();
        }
    },
    timeKeyUp: function timeKeyUp( evt, obj ) {
        var val = '' + obj.value;

        // If it is not the backspace
        if ( evt.keyCode !== 8 ) {
            val = val.replace( /[^0-9]/g, '' );

            var h = val.substr( 0, 2 );
            var i = val.substr( 2 );

            if ( h > 23 || i > 59 ) {
                val = '';
            }
            else if ( val.length >= 2 ) {
                val = h + ':' + i;
            }
        }

        obj.value = val;
    }
};

var lsmAgenda = ( function () {
    var date = $( '#date' );

    date.datepicker();

    date.keydown( function( evt ) {
        DateTime.dateKeyDown( evt, $( this )[ 0 ] );
    } );
    date.keyup( function( evt ) {
        DateTime.dateKeyUp( evt, $( this )[ 0 ] );
    } );

    var time = $( '#time' );

    time.keydown( function ( evt ) {
        DateTime.timeKeyDown( evt, $( this )[ 0 ] );
    } );
    time.keyup( function ( evt ) {
        DateTime.timeKeyUp( evt, $( this )[ 0 ] );
    } );
}() );
