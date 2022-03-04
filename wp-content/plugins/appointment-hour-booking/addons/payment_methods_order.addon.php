<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_paymentoptionorder' ) )
{
    class CPAPPB_paymentoptionorder extends CPAPPB_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-paymentoptionorder-20210706";
		protected $name = "Set order of payment options";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/customdownloads/order-payment-options.png';
        
        public $defaultoptions = "paypal\nstripe\npaylater";
		


        /************************ CONSTRUCT *****************************/
		
        function __construct()
        {
			$this->description = __("Tool for setting the order in which the payment options appear in the booking form (if multiple payment options are enabled).", 'appointment-hour-booking');
			        
        } // End __construct

		
    } // End Class
    
    // Main add-on code
    $cpappb_paymentoptionorder_obj = new CPAPPB_paymentoptionorder();
    
	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_paymentoptionorder_obj->get_addon_id() ] = $cpappb_paymentoptionorder_obj;
}
?>