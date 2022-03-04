<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_CreditsPurchase' ) )
{
    class CPAPPB_CreditsPurchase extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-CreditsPurchase-20200419";
		protected $name = "Credits Purchase: Purchase hours credits";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/customdownloads/credits-purchase-add-on.png';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on allows to purchase some number of hours (credits) and then use those credits to complete bookings", 'appointment-hour-booking' );
 
        } // End __construct

    } // End Class

    // Main add-on code
    $CPAPPB_CreditsPurchase_obj = new CPAPPB_CreditsPurchase();

    global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_CreditsPurchase_obj->get_addon_id() ] = $CPAPPB_CreditsPurchase_obj;
}
