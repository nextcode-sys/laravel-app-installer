var installer_api = {


	ajax : function ( url, method, params, callback )
	{
		let xhr = new XMLHttpRequest();
		xhr.open( method, url, true );
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                callback( JSON.parse( this.response ) );
            }
        }

		xhr.send( EncodeHTMLForm( params ) );
	},


	step1 : function ( params, callback )
	{
		this.ajax(
			'/installer/api.php?mode=step1',
			'POST',
            params,
            callback
		);
	},

    step2 : function ( params, callback )
    {
        this.ajax(
            '/installer/api.php?mode=step2',
            'POST',
            params,
            callback
        );
    },

    step3 : function ( params, callback )
    {
        this.ajax(
            '/installer/api.php?mode=step3',
            'POST',
            params,
            callback
        );
    },

}



/**
 * HTMLフォームの形式にデータを変換する
 *
 * @param data
 * @returns {string}
 * @constructor
 */
function EncodeHTMLForm( data )
{
    var params = [];

    for( let name in data )
    {
        var value = data[ name ];
        var param = encodeURIComponent( name ) + '=' + encodeURIComponent( value );

        params.push( param );
    }

    return params.join( '&' ).replace( /%20/g, '+' );
}
