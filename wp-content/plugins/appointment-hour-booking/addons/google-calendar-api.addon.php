<?php
/*
Documentation: https://googlecalapi.com/docs/quickstart
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_GoogleCalendarAPI' ) )
{
    class CPAPPB_GoogleCalendarAPI extends CPAPPB_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-googlecalapi-20180214";
		protected $name = "Google Calendar API";
		protected $description;
        public $category = 'Integration with External Calendars';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2020/04/18/google-calendar-api-connection/';


        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for Google Calendar API integration", 'appointment-hour-booking' );
          
        } // End __construct



    } // End Class

    // Main add-on code
    $CPAPPB_GoogleCalendarAPI_obj = new CPAPPB_GoogleCalendarAPI();

    global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_GoogleCalendarAPI_obj->get_addon_id() ] = $CPAPPB_GoogleCalendarAPI_obj;
}
?>