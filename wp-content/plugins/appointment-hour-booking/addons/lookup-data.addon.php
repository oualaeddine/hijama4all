<?php
/*
    iCal Import Addon
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_DataLookup' ) )
{
    function CPAPPB_DataLookup_load_cron() { $addon = new CPAPPB_DataLookup(); $addon->DataLookup(); }
    
    class CPAPPB_DataLookup extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-DataLookup-20200102";
		protected $name = "Data Lookup";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/01/10/data-lookup-and-autofilling-fields/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on enables data lookup in previous bookings to auto-fill fields", 'appointment-hour-booking' );

        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_DataLookup_obj = new CPAPPB_DataLookup();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_DataLookup_obj->get_addon_id() ] = $CPAPPB_DataLookup_obj;
}
