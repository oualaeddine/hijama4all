<?php
/*
Documentation: https://goo.gl/w3kKoH
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_iDealMollie' ) )
{
    class CPAPPB_iDealMollie extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-idealmollie-20151212";
		protected $name = "iDeal Mollie";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#mollie-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {			
			$this->description = __("The add-on adds support for iDeal via Mollie payments", 'appointment-hour-booking' );

        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_iDealMollie_obj = new CPAPPB_iDealMollie();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_iDealMollie_obj->get_addon_id() ] = $CPAPPB_iDealMollie_obj;
}


?>