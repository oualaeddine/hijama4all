<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_StatusUpdateEmails' ) )
{
    class CPAPPB_StatusUpdateEmails extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-StatusUpdateEmails-20170903";
		protected $name = "Status Modification Emails";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/12/27/status-update-emails/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on allows to define emails to be sent when the booking status is changed from the bookings list", 'appointment-hour-booking' );
 
        } // End __construct


    } // End Class

    // Main add-on code
    $cpappb_StatusUpdateEmails_obj = new CPAPPB_StatusUpdateEmails();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_StatusUpdateEmails_obj->get_addon_id() ] = $cpappb_StatusUpdateEmails_obj;
}

