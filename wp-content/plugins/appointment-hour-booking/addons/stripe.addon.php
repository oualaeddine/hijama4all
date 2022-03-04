<?php
/*
Documentation: https://stripe.com/docs/quickstart
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_Stripe' ) )
{
    class CPAPPB_Stripe extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-stripe-20151212";
		protected $name = "Stripe";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#stripe-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for Stripe payments", 'appointment-hour-booking' );
         
        } // End __construct



    } // End Class

    // Main add-on code
    $CPAPPB_Stripe_obj = new CPAPPB_Stripe();

    global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_Stripe_obj->get_addon_id() ] = $CPAPPB_Stripe_obj;
}
?>