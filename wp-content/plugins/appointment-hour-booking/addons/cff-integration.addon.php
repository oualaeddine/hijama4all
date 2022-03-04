<?php
/*


*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_CFFIntegration' ) )
{
    class CPAPPB_CFFIntegration extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-CFFIntegration-20180607";
		protected $name = "Integration with Calculated Fields Form plugin";
		protected $description;
        public $category = 'Integration with other plugins';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/05/15/integration-with-calculated-fields-form/';

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds offers integration with the Calculated Fields Form plugin", 'appointment-hour-booking' );

        } // End __construct      


    } // End Class

    // Main add-on code
    $CPAPPB_CFFIntegration_obj = new CPAPPB_CFFIntegration();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $CPAPPB_CFFIntegration_obj->get_addon_id() ] = $CPAPPB_CFFIntegration_obj;
}


?>