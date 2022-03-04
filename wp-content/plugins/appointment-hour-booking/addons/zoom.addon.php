<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_Zoom' ) )
{
    class CPAPPB_Zoom extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-Zoom-20170903";
		protected $name = "Zoom.us Meetings Integration";
		protected $description;
        public $category = 'Integration with third party services';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#zoom-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("Automatically creates a Zoom.us meeting for the booked time", 'appointment-hour-booking' );
         
        } // End __construct


    } // End Class

    // Main add-on code
    $cpappb_Zoom_obj = new CPAPPB_Zoom();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_Zoom_obj->get_addon_id() ] = $cpappb_Zoom_obj;
}
