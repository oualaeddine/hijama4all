<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_BookingEdition' ) )
{
    class CPAPPB_BookingEdition extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-Edition-20170903";
		protected $name = "Edition / Booking modification for customers";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/01/12/edition-booking-modification-by-customers-addon/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on allows customers to modify/edit the bookings", 'appointment-hour-booking' );
 
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_BookingEdition_obj = new CPAPPB_BookingEdition();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_BookingEdition_obj->get_addon_id() ] = $CPAPPB_BookingEdition_obj;
}

