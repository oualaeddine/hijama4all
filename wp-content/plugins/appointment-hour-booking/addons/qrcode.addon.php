<?php
/*
    Appointment Limits Addon
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_QRCode' ) )
{
    class CPAPPB_QRCode extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-QRCode-20180607";
		protected $name = "QRCode Image - Barcode";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/01/15/qrcode-image-barcode-add-on/';
        
        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("Generates a QRCode image for each booking.", 'appointment-hour-booking' );
          
        } // End __construct


    } // End Class

    // Main add-on code
    $CPAPPB_QRCode_obj = new CPAPPB_QRCode();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_QRCode_obj->get_addon_id() ] = $CPAPPB_QRCode_obj;
}
