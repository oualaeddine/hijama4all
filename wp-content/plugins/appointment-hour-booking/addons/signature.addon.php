<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'Apphb_Signature' ) )
{
    class Apphb_Signature extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
        protected $addonID = "addon-signature-20171025";
        protected $name = "Signature Fields";
        protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#signature-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
            $this->description = __("The add-on allows to replace form fields with \"Signature\" fields", 'appointment-hour-booking');

        } // End __construct

    } // End Class

    // Main add-on code
    $Apphb_Signature_obj = new Apphb_Signature();

    // Add addon object to the objects list
    $cpappb_addons_objs_list[ $Apphb_Signature_obj->get_addon_id() ] = $Apphb_Signature_obj;
}
