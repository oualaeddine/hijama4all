<?php
/*
    iCal Import Addon
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_TimezoneConversion' ) )
{
    class CPAPPB_TimezoneConversion extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-TimezoneConversion-20180607";
		protected $name = "Timezone Conversion";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2019/01/11/timezone-conversion/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on applies the timezone conversion to display the time-slots in the customer timezone", 'appointment-hour-booking' );
           
        } // End __construct




    } // End Class

    // Main add-on code
    $CPAPPB_TimezoneConversion_obj = new CPAPPB_TimezoneConversion();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_TimezoneConversion_obj->get_addon_id() ] = $CPAPPB_TimezoneConversion_obj;
}