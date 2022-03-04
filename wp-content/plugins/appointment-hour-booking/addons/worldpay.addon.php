<?php
/*
Documentation: http://support.worldpay.com/support/bg/index.php?page=development&sub=integration&subsub=examples
               http://support.worldpay.com/support/kb/bg/paymentresponse/pr0000.html
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_WorldPay' ) )
{
    class CPAPPB_WorldPay extends CPAPPB_BaseAddon
    {
       
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-WorldPay-20201216";
		protected $name = "WorldPay Payment Gateway";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/contact-us';
        
        /************************ CONSTRUCT *****************************/
		
        function __construct()
        {
			$this->description = __("The add-on adds support for WorldPay payments", 'cpabc' );
           
        } // End __construct
        

      			              	          		        		
		
		
    } // End Class
    
    // Main add-on code
    $CPAPPB_WorldPay_obj = new CPAPPB_WorldPay();
    
	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_WorldPay_obj->get_addon_id() ] = $CPAPPB_WorldPay_obj;
}



?>