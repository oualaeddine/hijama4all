<?php
/*
    iCal Import Addon
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_GoogleMapsAddress' ) )
{
    function CPAPPB_GoogleMapsAddress_load_cron() { $addon = new CPAPPB_GoogleMapsAddress(); $addon->GoogleMapsAddress(); }
    
    class CPAPPB_GoogleMapsAddress extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-GoogleMapsAddress-20210615";
		protected $name = "Address auto-complete (Google Maps)";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/02/10/address-auto-complete-google-maps/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("Convert text fields to auto-complete address fields using Google Maps addresses", 'appointment-hour-booking' );

        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_GoogleMapsAddress_obj = new CPAPPB_GoogleMapsAddress();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_GoogleMapsAddress_obj->get_addon_id() ] = $CPAPPB_GoogleMapsAddress_obj;
}
