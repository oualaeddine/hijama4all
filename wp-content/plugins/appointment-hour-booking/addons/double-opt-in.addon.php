<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_DoubleOptIn' ) )
{
    class CPAPPB_DoubleOptIn extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-DoubleOptIn-20200805";
		protected $name = "Double opt-in email verification";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2019/01/10/double-opt-in-addon/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("Double opt-in email verification link to mark the booking as approved", 'appointment-hour-booking' );
 
        } // End __construct


    } // End Class

    // Main add-on code
    $cpappb_DoubleOptIn_obj = new CPAPPB_DoubleOptIn();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_DoubleOptIn_obj->get_addon_id() ] = $cpappb_DoubleOptIn_obj;
}

