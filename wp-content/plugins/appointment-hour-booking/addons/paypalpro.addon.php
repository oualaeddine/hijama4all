<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_PayPalPro' ) )
{
    class CPAPPB_PayPalPro extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-paypalpro-20151212";
		protected $name = "PayPal Pro";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/contact-us';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for PayPal Payment Pro payments to accept credit cars directly into the website", 'appointment-hour-booking' );
           
        } // End __construct



    } // End Class

    // Main add-on code
    $apphbpaypalpro_obj = new CPAPPB_PayPalPro();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $apphbpaypalpro_obj->get_addon_id() ] = $apphbpaypalpro_obj;    
}
?>