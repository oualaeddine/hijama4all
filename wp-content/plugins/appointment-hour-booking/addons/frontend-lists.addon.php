<?php
/*
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_FrontendLists' ) )
{
    class CPAPPB_FrontendLists extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-FrontendLists-20181221";
		protected $name = "Frontend List: Grouped by Date Add-on";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/12/26/grouped-frontend-lists/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on allows to displays list (schedule) of bookings grouped by date in the frontend", 'appointment-hour-booking' );

        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_FrontendLists_obj = new CPAPPB_FrontendLists();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_FrontendLists_obj->get_addon_id() ] = $CPAPPB_FrontendLists_obj;
}
