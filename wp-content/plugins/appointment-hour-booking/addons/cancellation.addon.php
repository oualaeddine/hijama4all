<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_Cancellation' ) )
{
    class CPAPPB_Cancellation extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-Cancellation-20170903";
		protected $name = "Cancellation link for bookings";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#cancellation-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for cancellation links for bookings", 'appointment-hour-booking' );
 
        } // End __construct

    } // End Class

    // Main add-on code
    $cpappb_Cancellation_obj = new CPAPPB_Cancellation();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_Cancellation_obj->get_addon_id() ] = $cpappb_Cancellation_obj;
}

