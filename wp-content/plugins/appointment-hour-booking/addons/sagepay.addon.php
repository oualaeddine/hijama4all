<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_SagePay' ) )
{
    class CPAPPB_SagePay extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-SagePay-20160706";
		protected $name = "SagePay Payment Gateway";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#sagepay-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {	
			$this->description = __("The add-on adds support for SagePay payments", 'appointment-hour-booking' );

        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_SagePay_obj = new CPAPPB_SagePay();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_SagePay_obj->get_addon_id() ] = $CPAPPB_SagePay_obj;
}
