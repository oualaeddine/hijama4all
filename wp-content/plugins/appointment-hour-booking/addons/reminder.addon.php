<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_Reminder' ) )
{
    class CPAPPB_Reminder extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-Reminder-20170903";
		protected $name = "Reminder notifications for bookings";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#reminder-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for reminder notifications", 'appointment-hour-booking' );
 
        } // End __construct


    } // End Class

    // Main add-on code
    $cpappb_Reminder_obj = new CPAPPB_Reminder();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_Reminder_obj->get_addon_id() ] = $cpappb_Reminder_obj;
}

