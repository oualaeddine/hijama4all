<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_CryptoCom' ) )
{
    class CPAPPB_CryptoCom extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-CryptoCom-20210517";
		protected $name = "Crypto.com Payments Integration";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/contact-us';
 
        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for Crypto.com payments", 'appointment-hour-booking' );
        
        } // End __construct



    } // End Class

    // Main add-on code
    $cpappb_CryptoCom_obj = new CPAPPB_CryptoCom();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_CryptoCom_obj->get_addon_id() ] = $cpappb_CryptoCom_obj;
}

