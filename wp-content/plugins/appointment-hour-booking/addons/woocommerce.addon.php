<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_WooCommerce' ) )
{
    class CPAPPB_WooCommerce extends CPAPPB_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-woocommerce-20150309";
		protected $name = "WooCommerce";
		protected $description;
        public $category = 'Integration with other plugins';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/10/30/appointment-form-with-woocommerce/';
		
        /************************ CONSTRUCT *****************************/
        
        function __construct()
        {
			$this->description = __("The add-on allows integrate the forms with WooCommerce products", 'appointment-hour-booking');

            
        } // End __construct
  
    } // End Class
    
    // Main add-on code
    $cpappb_woocommerce_obj = new CPAPPB_WooCommerce();
    
	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_woocommerce_obj->get_addon_id() ] = $cpappb_woocommerce_obj;
}
?>