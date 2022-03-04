<?php
/*
Documentation: https://razorpay.com/docs/payment-gateway/web-integration/standard/
Keys: https://dashboard.razorpay.com/app/keys
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_razorpay' ) )
{
    class CPAPPB_razorpay extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-razorpay-20210312";
		protected $name = "Razorpay Payment Gateway";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#razorpay-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for Razorpay.com payments", 'appointment-hour-booking' );
 
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_razorpay_obj = new CPAPPB_razorpay();

    global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_razorpay_obj->get_addon_id() ] = $CPAPPB_razorpay_obj;
}
?>