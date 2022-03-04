<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_MailChimp' ) )
{
    class CPAPPB_MailChimp extends CPAPPB_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-mailchimp-20170504";
		protected $name = "MailChimp";
		protected $description;
        public $category = 'Integration with third party services';
        public $help = 'https://apphourbooking.dwbooster.com/documentation#mailchimp-addon';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on creates MailChimp List members with the submitted information", 'appointment-hour-booking' );
         
        } // End __construct


    } // End Class

    // Main add-on code
    $apphbmailchimp_obj = new CPAPPB_MailChimp();

    global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $apphbmailchimp_obj->get_addon_id() ] = $apphbmailchimp_obj;
}
?>