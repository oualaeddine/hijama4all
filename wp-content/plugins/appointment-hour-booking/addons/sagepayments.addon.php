<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_SagePayments' ) )
{
    class CPAPPB_SagePayments extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-SagePayments-20160706";
		protected $name = "SagePayments Payment Gateway";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#sagepayment-addon';
        
        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for SagePayments payments", 'appointment-hour-booking' );
  
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_SagePayments_obj = new CPAPPB_SagePayments();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_SagePayments_obj->get_addon_id() ] = $CPAPPB_SagePayments_obj;
}

