<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_Clickatell' ) )
{
    class CPAPPB_Clickatell extends CPAPPB_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-clickatell-20170403";
		protected $name = "Clickatell";
		protected $description;
        public $category = 'SMS Delivery / Text Messaging';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#clickatell-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on allows to send notification messages (SMS) via Clickatell after submitting the form", 'appointment-hour-booking');

        } // End __construct


    } // End Class

    // Main add-on code
    $apphbclickatell_obj = new CPAPPB_Clickatell();

    global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $apphbclickatell_obj->get_addon_id() ] = $apphbclickatell_obj;
}
?>