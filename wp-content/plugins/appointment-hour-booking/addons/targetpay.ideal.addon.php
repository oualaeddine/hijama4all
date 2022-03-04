<?php
/*
Documentation: https://www.targetpay.com/docs/TargetPay_iDEAL_V3.0_en.pdf
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_iDealTargetPay' ) )
{
    class CPAPPB_iDealTargetPay extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-idealtargetpay-20151212";
		protected $name = "iDeal TargetPay";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#targetpay-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for iDeal via TargetPay payments", 'appointment-hour-booking' );
          
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_iDealTargetPay_obj = new CPAPPB_iDealTargetPay();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_iDealTargetPay_obj->get_addon_id() ] = $CPAPPB_iDealTargetPay_obj;
}


?>