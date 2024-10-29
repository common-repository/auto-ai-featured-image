(function( $ ) {
	//'use strict';

$( window ).load(function() {
	//$(document).ready(function($) {

    // We'll pass this variable to the PHP function example_ajax_request
    $('.kh_generate_content_now').click(function(){
		
	//var fruit = 'Banana';
	console.log('button clicked');
    // This does the ajax request
    //return false;
    $('#status_update').html('<span id="infobox">We are creating content, do not refresh.</span>');

    $.ajax({
        url: ajaxurl,
        data: {
            'action':'kh_generate_content_when_click'
        },
        success:function(data) {
            // This outputs the result of the ajax request
            console.log(data);
			$('#status_update').html('<br><span id="infobox">Completed! Refresh this page.</span>');
        },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    });
		
	})
       

//});
});
	
	
})( jQuery );

