<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_SchedudeCalContents' ) )
{
    class CPAPPB_SchedudeCalContents extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-SchedudeCalContents-20200121";
		protected $name = "Schedule Calendar Contents Customization";
		protected $description;
        public $category = 'Improvements';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/11/01/schedule-calendar-contents-customization/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on allow to customize the content and colors displayed on the schedule calendar for each form.", 'appointment-hour-booking' );

        } // End __construct



    } // End Class

    // Main add-on code
    $cpappb_SchedudeCalContents_obj = new CPAPPB_SchedudeCalContents();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_SchedudeCalContents_obj->get_addon_id() ] = $cpappb_SchedudeCalContents_obj;
}

