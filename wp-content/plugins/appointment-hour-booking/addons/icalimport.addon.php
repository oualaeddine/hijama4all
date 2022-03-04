<?php
/*
    iCal Import Addon
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_iCalImport' ) )
{
    class CPAPPB_iCalImport extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-iCalImport-20180607";
		protected $name = "iCal Automatic Import";
		protected $description;
        public $category = 'Integration with External Calendars';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/12/20/ical-import/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for importing iCal files from external websites/services", 'appointment-hour-booking' );
           
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_iCalImport_obj = new CPAPPB_iCalImport();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_iCalImport_obj->get_addon_id() ] = $CPAPPB_iCalImport_obj;
}