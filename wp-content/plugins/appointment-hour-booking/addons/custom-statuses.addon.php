<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_CustomStatuses' ) )
{
    class CPAPPB_CustomStatuses extends CPAPPB_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-customstatuses-20201229";
		protected $name = "Additional Booking Statuses";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/01/05/custom-statuses/';
		
        /************************ CONSTRUCT *****************************/
		
        function __construct()
        {
			$this->description = __("The add-on allows to add new statuses to the bookings.", 'appointment-hour-booking');

        } // End __construct
	
    } // End Class
    
    // Main add-on code
    $cpappb_CustomStatuses_obj = new CPAPPB_CustomStatuses();
    
	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_CustomStatuses_obj->get_addon_id() ] = $cpappb_CustomStatuses_obj;
}
?>