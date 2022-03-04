<?php
/*
    Reference: https://www.twilio.com/docs/quickstart/php/sms/sending-via-rest#send-sms-via-rest
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_TwilioSMS' ) )
{
    class CPAPPB_TwilioSMS extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-TwilioSMS-20170903";
		protected $name = "Twilio SMS notifications for bookings";
		protected $description;
        public $category = 'SMS Delivery / Text Messaging';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#twilio-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for Twilio SMS notifications", 'appointment-hour-booking' );
          
        } // End __construct



    } // End Class

    // Main add-on code
    $cpappb_TwilioSMS_obj = new CPAPPB_TwilioSMS();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_TwilioSMS_obj->get_addon_id() ] = $cpappb_TwilioSMS_obj;
}

