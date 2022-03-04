<?php
/*
    Reference: https://api.whatsapp.com/send/?phone=1111111111&text=urlencodedtext&app_absent=1
               https://faq.whatsapp.com/iphone/how-to-link-to-whatsapp-from-a-different-app/
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_WhatsappButton' ) )
{
    class CPAPPB_WhatsappButton extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-WhatsappButton-20220129";
		protected $name = "WhatsApp open chat button";
		protected $description;
        public $category = 'SMS Delivery / Text Messaging';
        public $help = 'https://apphourbooking.dwbooster.com/customdownloads/whatsapp-button-addon.png';

	
        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("Adds a button in the booking orders list to start a WhatsApp chat", 'appointment-hour-booking' );
            // Check if the plugin is active


        } // End __construct
                

    } // End Class

    // Main add-on code
    $cpappb_WhatsappButton_obj = new CPAPPB_WhatsappButton();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_WhatsappButton_obj->get_addon_id() ] = $cpappb_WhatsappButton_obj;
}

