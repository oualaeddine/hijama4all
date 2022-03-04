<?php
/*
    Appointment Limits Addon
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_appLimit' ) )
{
    class CPAPPB_appLimit extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-appLimit-20180607";
		protected $name = "Limit the number of appointments per user";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#appointmentlimits-addon';
        
 
        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for limiting the number of appointments per user", 'appointment-hour-booking' );

        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_appLimit_obj = new CPAPPB_appLimit();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_appLimit_obj->get_addon_id() ] = $CPAPPB_appLimit_obj;
}


?>