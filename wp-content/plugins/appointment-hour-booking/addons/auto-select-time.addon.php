<?php
/*
    Shared Availability Addon
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_AutoSelectTime' ) )
{
    class CPAPPB_AutoSelectTime extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-AutoSelectTime-20210605";
		protected $name = "Auto-select time clicking a date";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/01/24/auto-select-time/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {            
			$this->description = __("Select automatically the time after clicking the day. Useful for cases with only one time-slot per day.", 'appointment-hour-booking' );
         
        } // End __construct



    } // End Class

    // Main add-on code
    $CPAPPB_AutoSelectTime_obj = new CPAPPB_AutoSelectTime();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_AutoSelectTime_obj->get_addon_id() ] = $CPAPPB_AutoSelectTime_obj;
}

