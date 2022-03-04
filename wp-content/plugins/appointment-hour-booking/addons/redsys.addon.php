<?php
/*
Documentation: https://goo.gl/w3kKoH
https://pagosonline.redsys.es/entornosPruebas.html
https://pagosonline.redsys.es/codigosRespuesta.html
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_SabTPV' ) )
{
    class CPAPPB_SabTPV extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-sabtpv-20151212";
		protected $name = "RedSys TPV";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#redsys-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for RedSys TPV payments", 'appointment-hour-booking' );
         
        } // End __construct



    } // End Class

    // Main add-on code
    $apphbsabtpv_obj = new CPAPPB_SabTPV();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $apphbsabtpv_obj->get_addon_id() ] = $apphbsabtpv_obj;
}