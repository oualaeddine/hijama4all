<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_AdminFrontend' ) )
{
    class CPAPPB_AdminFrontend extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-admincalendarf-20200212";
		protected $name = "Calendar Admin in Frontend";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/customdownloads/calendar-admin-in-frontend.png';


        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("Shortcode to admin the calendar assigned to the logged in user in the frontend.", 'appointment-hour-booking' );
     
        } // End __construct



    } // End Class

    // Main add-on code
    $CPAPPB_AdminFrontend_obj = new CPAPPB_AdminFrontend();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_AdminFrontend_obj->get_addon_id() ] = $CPAPPB_AdminFrontend_obj;
}

