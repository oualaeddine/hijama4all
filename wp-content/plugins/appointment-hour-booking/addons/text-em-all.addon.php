<?php
/*
    Reference: https://www.TextEmAll.com/docs/quickstart/php/sms/sending-via-rest#send-sms-via-rest
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_TextEmAllSMS' ) )
{
    class CPAPPB_TextEmAllSMS extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-TextEmAllSMS-20170903";
		protected $name = "Text-Em-All SMS Msg & Automated Calling";
		protected $description;
        public $category = 'SMS Delivery / Text Messaging';
        public $help = 'https://apphourbooking.dwbooster.com/customdownloads/text-em-all-add-on.png';

	
        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for Text-Em-All SMS notifications", 'appointment-hour-booking' );
          
        } // End __construct



    } // End Class

    // Main add-on code
    $cpappb_TextEmAllSMS_obj = new CPAPPB_TextEmAllSMS();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_TextEmAllSMS_obj->get_addon_id() ] = $cpappb_TextEmAllSMS_obj;
}

