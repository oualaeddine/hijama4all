<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_AuthNetSIM' ) )
{
    class CPAPPB_AuthNetSIM extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-AuthNetSIM-20160910";
		protected $name = "Authorize.net Server Integration Method";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#authorize-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {	
			$this->description = __("The add-on adds support for Authorize.net Server Integration Method payments", 'appointment-hour-booking' );
 
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_AuthNetSIM_obj = new CPAPPB_AuthNetSIM();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_AuthNetSIM_obj->get_addon_id() ] = $CPAPPB_AuthNetSIM_obj;
}

