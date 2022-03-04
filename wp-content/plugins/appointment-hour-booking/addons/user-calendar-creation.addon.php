<?php
/*
    iCal Import Addon
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_UserCalendarCreation' ) )
{
    class CPAPPB_UserCalendarCreation extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-UserCalendarCreation-20180607";
		protected $name = "User Calendar Creation";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#usercreation-addon';
	
        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on creates and assign a calendar for each new registered user", 'appointment-hour-booking' );
       
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_UserCalendarCreation_obj = new CPAPPB_UserCalendarCreation();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_UserCalendarCreation_obj->get_addon_id() ] = $CPAPPB_UserCalendarCreation_obj;
}