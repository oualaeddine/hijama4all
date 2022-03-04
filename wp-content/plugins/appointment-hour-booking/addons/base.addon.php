<?php
/*
....
*/

if( !class_exists( 'CPAPPB_BaseAddon' ) )
{
    class CPAPPB_BaseAddon 
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID;
		protected $name;
		protected $description;
        public $category = ' Other add-ons';
        public $help = '';
		
		public function get_addon_id()
		{
			return $this->addonID;
		}
		
		public function get_addon_name()
		{
			return $this->name;
		}
		
		public function get_addon_description()
		{
			return $this->description;
		}
		
		public function get_addon_form_settings( $form_id )
		{
			return '';
		}
		
		public function get_addon_settings()
		{
			return '';
		}
		
		public function addon_is_active()
		{
			global $cpappb_addons_active_list;
            if (is_array($cpappb_addons_active_list))
			    return in_array( $this->get_addon_id(), $cpappb_addons_active_list );
            else
                return false;
		}
	} // End Class
}
?>