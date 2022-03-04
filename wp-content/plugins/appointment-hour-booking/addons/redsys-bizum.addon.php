<?php
/*
Documentation: https://goo.gl/w3kKoH
https://pagosonline.redsys.es/entornosPruebas.html
https://pagosonline.redsys.es/codigosRespuesta.html
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_sabtpvBizum' ) )
{
    class CPAPPB_sabtpvBizum extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-sabtpvBizum-20200911";
		protected $name = "RedSys-Bizum TPV";
		protected $description;
        public $category = 'Payment Gateways Integration';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#bizum-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {	
			$this->description = __("The add-on adds support for RedSys-Bizum TPV payments", 'appointment-hour-booking' );
 
        } // End __construct

    } // End Class

    // Main add-on code
    $apphbsabtpvBizum_obj = new CPAPPB_sabtpvBizum();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $apphbsabtpvBizum_obj->get_addon_id() ] = $apphbsabtpvBizum_obj;
}
