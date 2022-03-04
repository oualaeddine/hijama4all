jQuery( document ).ready( function() {

	jQuery( document ).on( 'click', '.aep-notice-content a', function(event) {
        if( jQuery(event.target).hasClass('never-show')) {
            var data = {
                action: 'never_show_again',
            };
        }
		
		jQuery.post( notice_params.ajaxurl, data, function() {
            if(data){
                jQuery('.aep-notice').fadeOut();
            }
		});
    });
});