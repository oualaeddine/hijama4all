<?php
/*
Documentation: https://Coupons.com/docs/quickstart
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_Coupons' ) )
{
    class CPAPPB_Coupons extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-Coupons-20151212";
		protected $name = "Coupons";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/11/28/coupon-codes-addon/';
   

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			
			$this->description = __("The add-on adds support for coupons / discounts codes", 'appointment-hour-booking' );
          
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_Coupons_obj = new CPAPPB_Coupons();

    global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_Coupons_obj->get_addon_id() ] = $CPAPPB_Coupons_obj;
}
?>