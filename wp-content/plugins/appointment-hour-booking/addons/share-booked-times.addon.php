<?php
/*
    Shared Availability Addon
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_SharedAvailability' ) )
{
    class CPAPPB_SharedAvailability extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-SharedAvailability-20180607";
		protected $name = "Shared Availability between Calendars";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/01/20/sharing-booked-times-between-calendars/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on allow to share the booked times between calendars (for blocking booked times)", 'appointment-hour-booking' );
  
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_SharedAvailability_obj = new CPAPPB_SharedAvailability();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_SharedAvailability_obj->get_addon_id() ] = $CPAPPB_SharedAvailability_obj;
}

