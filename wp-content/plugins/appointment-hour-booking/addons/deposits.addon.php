<?php
/*
Documentation: https://apphourbooking.dwbooster.com/
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_Deposits' ) )
{
    class CPAPPB_Deposits extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-deposits-20151212";
		protected $name = "Deposit Payments";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/contact-us';
        

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on enables the option to accept payment deposit as fixed amount or percent of the booking cost", 'appointment-hour-booking' );
         
        } // End __construct



    } // End Class

    // Main add-on code
    $CPAPPB_Deposits_obj = new CPAPPB_Deposits();

    global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_Deposits_obj->get_addon_id() ] = $CPAPPB_Deposits_obj;
}
?>