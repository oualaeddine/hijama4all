<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_SalesForce' ) )
{
    class CPAPPB_SalesForce extends CPAPPB_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-salesforce-20150311";
		protected $name = "SalesForce";
		protected $description;
        public $category = 'Integration with third party services';
        public $help = 'https://apphourbooking.dwbooster.com/add-ons/salesforce';
        
        /************************ CONSTRUCT *****************************/
		
        function __construct()
        {
			$this->description = __("The add-on allows create SalesForce leads with the submitted information", 'appointment-hour-booking' );

        } // End __construct
 	
    } // End Class
    
    // Main add-on code
    $cpappb_salesforce_obj = new CPAPPB_SalesForce();
    
	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_salesforce_obj->get_addon_id() ] = $cpappb_salesforce_obj;
}
?>