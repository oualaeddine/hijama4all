<?php
/*
Documentation: https://apphourbooking.dwbooster.com/documentation
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_Passwordaddon' ) )
{
    class CPAPPB_Passwordaddon extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-Passwordaddon-20151212";
		protected $name = "Password for making bookings";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/03/27/password-addon/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on is for requiring a password to make a booking.", 'appointment-hour-booking' );
         
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_Passwordaddon_obj = new CPAPPB_Passwordaddon();

    global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_Passwordaddon_obj->get_addon_id() ] = $CPAPPB_Passwordaddon_obj;
}
?>