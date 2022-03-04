<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_PayPalStandard' ) )
{
    class CPAPPB_PayPalStandard extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-PayPalStandard-20170903";
		protected $name = "PayPal Standard Payments Integration";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/customdownloads/paypal-add-ons-settings.png';
        
        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for PayPal Standard payments", 'appointment-hour-booking' );

        } // End __construct

    } // End Class

    // Main add-on code
    $cpappb_PayPalStandard_obj = new CPAPPB_PayPalStandard();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_PayPalStandard_obj->get_addon_id() ] = $cpappb_PayPalStandard_obj;
}

