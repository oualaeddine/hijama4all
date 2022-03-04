<?php
/*
    Shared Availability Addon
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_RemoveIgnoreOld' ) )
{
    class CPAPPB_RemoveIgnoreOld extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-RemoveIgnoreOld-20180607";
		protected $name = "Remove or Ignore Old Bookings";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/09/15/remove-or-ignore-old-bookings/';


        /************************ CONSTRUCT *****************************/

        function __construct()
        {            
			$this->description = __("The add-on allows to automatically remove or ignore old bookings to increase the booking form speed", 'appointment-hour-booking' );
            
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_RemoveIgnoreOld_obj = new CPAPPB_RemoveIgnoreOld();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_RemoveIgnoreOld_obj->get_addon_id() ] = $CPAPPB_RemoveIgnoreOld_obj;
}

