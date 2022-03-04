<?php
/*
Plugin Name: Appointment Hour Booking
Plugin URI: https://apphourbooking.dwbooster.com
Description: Appointment Hour Booking is a plugin for creating booking forms for appointments with a start time and a defined duration.
Version: 1.3.44
Author: CodePeople
Author URI: https://apphourbooking.dwbooster.com
License: GPL
Text Domain: appointment-hour-booking
*/

define('CP_APPBOOK_DEFER_SCRIPTS_LOADING', (get_option('CP_APPB_LOAD_SCRIPTS',"1") == "1"?true:false));

define('CP_APPBOOK_DEFAULT_form_structure', '[[{"form_identifier":"","name":"fieldname1","shortlabel":"","index":0,"ftype":"fapp","userhelp":"","userhelpTooltip":false,"csslayout":"","title":"Appointment","services":[{"name":"Service 1","price":1,"duration":60}],"openhours":[{"name":"Default","openhours":[{"type":"all","d":"","h1":8,"m1":0,"h2":17,"m2":0}]}],"allOH":[{"name":"Default","openhours":[{"type":"all","d":"","h1":8,"m1":0,"h2":17,"m2":0}]}],"dateFormat":"mm/dd/yy","showDropdown":false,"dropdownRange":"-10:+10","working_dates":[true,true,true,true,true,true,true],"numberOfMonths":1,"firstDay":0,"minDate":"0","maxDate":"","defaultDate":"","invalidDates":"","required":true,"fBuild":{}},{"form_identifier":"","name":"email","shortlabel":"","index":1,"ftype":"femail","userhelp":"","userhelpTooltip":false,"csslayout":"","title":"Email","predefined":"","predefinedClick":false,"required":true,"size":"medium","equalTo":"","fBuild":{}}],[{"title":"","description":"","formlayout":"top_aligned","formtemplate":"","evalequations":1,"autocomplete":1}]]');


define('CP_APPBOOK_DEFAULT_track_IP', true);

define('CP_APPBOOK_DEFAULT_fp_subject', 'Notification to administrator: Booking request received...');
define('CP_APPBOOK_DEFAULT_fp_inc_additional_info', 'true');
define('CP_APPBOOK_DEFAULT_fp_return_page', get_site_url());
define('CP_APPBOOK_DEFAULT_fp_message', "The following booking request has been received:\n\n<%INFO%>\n\n");

define('CP_APPBOOK_DEFAULT_cu_enable_copy_to_user', 'true');
define('CP_APPBOOK_DEFAULT_cu_user_email_field', 'email');
define('CP_APPBOOK_DEFAULT_cu_subject', 'Confirmation: Your booking has been received...');
define('CP_APPBOOK_DEFAULT_cu_message', "Your appointment is received. We look forward to seeing you then.\n\nThis is a copy of the data sent:\n\n<%INFO%>\n\nBest Regards.");
define('CP_APPBOOK_DEFAULT_email_format','text');

define('CP_APPBOOK_DEFAULT_vs_use_validation', 'true');

define('CP_APPBOOK_DEFAULT_vs_text_is_required', 'This field is required.');
define('CP_APPBOOK_DEFAULT_vs_text_is_email', 'Please enter a valid email address.');

define('CP_APPBOOK_DEFAULT_vs_text_datemmddyyyy', 'Please enter a valid date with this format(mm/dd/yyyy)');
define('CP_APPBOOK_DEFAULT_vs_text_dateddmmyyyy', 'Please enter a valid date with this format(dd/mm/yyyy)');
define('CP_APPBOOK_DEFAULT_vs_text_number', 'Please enter a valid number.');
define('CP_APPBOOK_DEFAULT_vs_text_digits', 'Please enter only digits.');
define('CP_APPBOOK_DEFAULT_vs_text_max', 'Please enter a value less than or equal to {0}.');
define('CP_APPBOOK_DEFAULT_vs_text_min', 'Please enter a value greater than or equal to {0}.');
define('CP_APPBOOK_DEFAULT_vs_text_maxapp', 'Please select a max of  {0} appointments per customer.');

define('CP_APPBOOK_DEFAULT_cv_enable_captcha', 'true');
define('CP_APPBOOK_DEFAULT_cv_width', '180');
define('CP_APPBOOK_DEFAULT_cv_height', '60');
define('CP_APPBOOK_DEFAULT_cv_chars', '5');
define('CP_APPBOOK_DEFAULT_cv_font', 'font1');
define('CP_APPBOOK_DEFAULT_cv_min_font_size', '25');
define('CP_APPBOOK_DEFAULT_cv_max_font_size', '35');
define('CP_APPBOOK_DEFAULT_cv_noise', '200');
define('CP_APPBOOK_DEFAULT_cv_noise_length', '4');
define('CP_APPBOOK_DEFAULT_cv_background', 'ffffff');
define('CP_APPBOOK_DEFAULT_cv_border', '000000');
define('CP_APPBOOK_DEFAULT_cv_text_enter_valid_captcha', 'Please enter a valid captcha code.');

define('CP_APPBOOK_REP_ARR', '[+arr1237]');

define ('CP_CPAPPB_SCHCALTITLE_ADMIN', '%email%');
define ('CP_CPAPPB_SCHCALCONTENT_ADMIN', '%INFO%');
define ('CP_CPAPPB_SCHCALTITLE_PUBLIC', 'Booked');
define ('CP_CPAPPB_SCHCALCONTENT_PUBLIC', 'This time-slot is booked');

// loading add-ons
// -----------------------------------------
global $cpappb_addons_active_list, // List of addon IDs
	   $cpappb_addons_objs_list; // List of addon objects
	   
$cpappb_addons_active_list = array();
if (!is_array($cpappb_addons_objs_list))
    $cpappb_addons_objs_list	 = array();
	
function cpappb_loading_add_ons()
{
	global $cpappb_addons_active_list, // List of addon IDs
		   $cpappb_addons_objs_list; // List of addon objects
	
    // Get the list of active addons
	$cpappb_addons_active_list = get_option( 'cpappb_addons_active_list', array() );
	if( !empty( $cpappb_addons_active_list ) 
        || ( isset( $_GET["page"] ) && $_GET["page"] == "cp_apphourbooking" )  
        || ( isset( $_GET["page"] ) && $_GET["page"] == "cp_apphourbooking_addons" )
      )
	{	
		$path = dirname( __FILE__ ).'/addons';
		if( file_exists( $path ) )
		{
			$addons = dir( $path );
			while( false !== ( $entry = $addons->read() ) ) 
			{    
				if( strlen( $entry ) > 3 && strtolower( pathinfo( $entry, PATHINFO_EXTENSION) ) == 'php' )
				{
					require_once $addons->path.'/'.$entry;
				}			
			}
		} 
	}	
}
cpappb_loading_add_ons();



/* initialization / install */

include_once dirname( __FILE__ ) . '/classes/cp-base-class.inc.php';
include_once dirname( __FILE__ ) . '/cp-main-class.inc.php';


$cp_appb_plugin = new CP_AppBookingPlugin;

register_activation_hook(__FILE__, array($cp_appb_plugin,'install') ); 
add_action( 'media_buttons', array($cp_appb_plugin, 'insert_button'), 11);
add_action( 'init', array($cp_appb_plugin, 'data_management'));
add_action( 'wp_loaded', array($cp_appb_plugin, 'data_management_loaded'));
add_action( 'plugins_loaded', array($cp_appb_plugin, 'data_management_pluginsloaded'), -1);

//START: activation redirection 
function cpappb_activation_redirect( $plugin ) {
    if(
        $plugin == plugin_basename( __FILE__ ) &&
        (!isset($_POST["action"]) || $_POST["action"] != 'activate-selected') &&
        (!isset($_POST["action2"]) || $_POST["action2"] != 'activate-selected') 
      )
    {
        exit( wp_redirect( admin_url( 'admin.php?page=cp_apphourbooking' ) ) );
    }
}
add_action( 'activated_plugin', 'cpappb_activation_redirect' );
//END: activation redirection 

if ( is_admin() ) {  
    
    add_action('admin_enqueue_scripts', array($cp_appb_plugin,'insert_adminScripts'), 1);    
    add_filter("plugin_action_links_".plugin_basename(__FILE__), array($cp_appb_plugin,'plugin_page_links'));   
    add_action('admin_menu', array($cp_appb_plugin,'admin_menu') );
    add_action('enqueue_block_editor_assets', array($cp_appb_plugin,'gutenberg_block'));
    
    
} else {    
    add_shortcode( $cp_appb_plugin->shorttag, array($cp_appb_plugin, 'filter_content') );   
    add_shortcode( 'CP_APP_HOUR_BOOKING_LIST', array($cp_appb_plugin, 'filter_list') );    
}  

// register gutemberg block
if (function_exists('register_block_type'))
{
    register_block_type('cpapphourbk/form-rendering', array(
                        'attributes'      => array(
                                'formId'    => array(
                                    'type'      => 'string'
                                ),
                                'instanceId'    => array(
                                    'type'      => 'string'
                                ),
                            ),
                        'render_callback' => array($cp_appb_plugin, 'render_form_admin')
                    )); 
}

// banner             
$codepeople_promote_banner_plugins[ 'appointment-hour-booking' ] = array( 
                      'plugin_name' => 'Appointment Hour Booking', 
                      'plugin_url'  => 'https://wordpress.org/support/plugin/appointment-hour-booking/reviews/?filter=5#new-post'
);
require_once 'banner.php';

// optional opt-in deactivation feedback
require_once 'cp-feedback.php';


add_filter('autoptimize_filter_js_exclude', 'apphourbk_autoptimize_filter_js_exclude' );
function apphourbk_autoptimize_filter_js_exclude($excluded)
{
    return $excluded.",jquery.ui.datepicker-fr-CA.js,".
                     "jquery.ui.datepicker-es_ES.js,".
                     "jquery.ui.datepicker-no.js,".
                     "jquery.ui.datepicker-he_IL.js,".
                     "jquery.ui.datepicker-pt_BR.js,".
                     "jquery.ui.datepicker-ro_RO.js,".
                     "jquery.ui.datepicker-ru_RU.js,".
                     "jquery.ui.datepicker-sk_SK.js,".
                     "jquery.ui.datepicker-sl_SI.js,".
                     "jquery.ui.datepicker-sr_SR.js,".
                     "jquery.ui.datepicker-sv_SE.js,".
                     "jquery.ui.datepicker-tr_TR.js,".
                     "jquery.ui.datepicker-zh_CN.js,".
                     "jquery.ui.datepicker-zh_TW.js,".
                     "jquery.ui.datepicker-nl_NL.js,".
                     "jquery.ui.datepicker-pl_PL.js,".
                     "jquery.ui.datepicker-pt_PT.js,".
                     "jquery.ui.datepicker-bg_BG.js,".
                     "jquery.ui.datepicker-bs_BA.js,".
                     "jquery.ui.datepicker-cs_CZ.js,".
                     "jquery.ui.datepicker-da_DK.js,".
                     "jquery.ui.datepicker-de_DE.js,".
                     "jquery.ui.datepicker-eo_EO.js,".
                     "jquery.ui.datepicker-fa_IR.js,".
                     "jquery.ui.datepicker-fr_FR.js,".
                     "jquery.ui.datepicker-gl_ES.js,".
                     "jquery.ui.datepicker-hi_IN.js,".
                     "jquery.ui.datepicker-hu_HU.js,".
                     "jquery.ui.datepicker-hy_AM.js,".
                     "jquery.ui.datepicker-id_ID.js,".
                     "jquery.ui.datepicker-it_IT.js,".
                     "jquery.ui.datepicker-ka_GE.js,".
                     "jquery.ui.datepicker-ko_KR.js,".
                     "jquery.ui.datepicker-lt_LT.js,".
                     "jquery.ui.datepicker-ms_MY.js,".
                     "jquery.ui.datepicker-nb_NO.js,".
                     "jquery.ui.datepicker-be_BY.js,".
                     "jquery.ui.datepicker-mk_MK.js,".
                     "jquery.ui.datepicker-ml_IN.js,".
                     "jquery.ui.datepicker-az.js,".
                     "jquery.ui.datepicker-af.js,".
                     "jquery.ui.datepicker-ar.js,".
                     "jquery.ui.datepicker-ca.js,".
                     "jquery.ui.datepicker-el.js,".
                     "jquery.ui.datepicker-et.js,".
                     "jquery.ui.datepicker-eu.js,".
                     "jquery.ui.datepicker-fi.js,".
                     "jquery.ui.datepicker-hr.js,".
                     "jquery.ui.datepicker-ja.js,".
                     "jquery.ui.datepicker-lv.js,".
                     "jquery.ui.datepicker-sq.js,".
                     "jquery.ui.datepicker-ta.js,".
                     "jquery.ui.datepicker-th.js,".
                     "jquery.ui.datepicker-uk.js,".
                     "jquery.ui.datepicker-vi.js,".
                     "jquery.validate.min.js,jQuery.stringify.js,jquery.validate.js";
}


// code for compatibility with third party scripts

add_filter('litespeed_cache_optimize_js_excludes', 'apphourbk_litespeed_cache_optimize_js_excludes' );
function apphourbk_litespeed_cache_optimize_js_excludes($options)
{
    return  "jquery.ui.datepicker-fr-CA.js\n".
                     "jquery.ui.datepicker-es_ES.js\n".
                     "jquery.ui.datepicker-no.js\n".
                     "jquery.ui.datepicker-he_IL.js\n".
                     "jquery.ui.datepicker-pt_BR.js\n".
                     "jquery.ui.datepicker-ro_RO.js\n".
                     "jquery.ui.datepicker-ru_RU.js\n".
                     "jquery.ui.datepicker-sk_SK.js\n".
                     "jquery.ui.datepicker-sl_SI.js\n".
                     "jquery.ui.datepicker-sr_SR.js\n".
                     "jquery.ui.datepicker-sv_SE.js\n".
                     "jquery.ui.datepicker-tr_TR.js\n".
                     "jquery.ui.datepicker-zh_CN.js\n".
                     "jquery.ui.datepicker-zh_TW.js\n".
                     "jquery.ui.datepicker-nl_NL.js\n".
                     "jquery.ui.datepicker-pl_PL.js\n".
                     "jquery.ui.datepicker-pt_PT.js\n".
                     "jquery.ui.datepicker-bg_BG.js\n".
                     "jquery.ui.datepicker-bs_BA.js\n".
                     "jquery.ui.datepicker-cs_CZ.js\n".
                     "jquery.ui.datepicker-da_DK.js\n".
                     "jquery.ui.datepicker-de_DE.js\n".
                     "jquery.ui.datepicker-eo_EO.js\n".
                     "jquery.ui.datepicker-fa_IR.js\n".
                     "jquery.ui.datepicker-fr_FR.js\n".
                     "jquery.ui.datepicker-gl_ES.js\n".
                     "jquery.ui.datepicker-hi_IN.js\n".
                     "jquery.ui.datepicker-hu_HU.js\n".
                     "jquery.ui.datepicker-hy_AM.js\n".
                     "jquery.ui.datepicker-id_ID.js\n".
                     "jquery.ui.datepicker-it_IT.js\n".
                     "jquery.ui.datepicker-ka_GE.js\n".
                     "jquery.ui.datepicker-ko_KR.js\n".
                     "jquery.ui.datepicker-lt_LT.js\n".
                     "jquery.ui.datepicker-ms_MY.js\n".
                     "jquery.ui.datepicker-nb_NO.js\n".
                     "jquery.ui.datepicker-be_BY.js\n".
                     "jquery.ui.datepicker-mk_MK.js\n".
                     "jquery.ui.datepicker-ml_IN.js\n".
                     "jquery.ui.datepicker-az.js\n".
                     "jquery.ui.datepicker-af.js\n".
                     "jquery.ui.datepicker-ar.js\n".
                     "jquery.ui.datepicker-ca.js\n".
                     "jquery.ui.datepicker-el.js\n".
                     "jquery.ui.datepicker-et.js\n".
                     "jquery.ui.datepicker-eu.js\n".
                     "jquery.ui.datepicker-fi.js\n".
                     "jquery.ui.datepicker-hr.js\n".
                     "jquery.ui.datepicker-ja.js\n".
                     "jquery.ui.datepicker-lv.js\n".
                     "jquery.ui.datepicker-sq.js\n".
                     "jquery.ui.datepicker-ta.js\n".
                     "jquery.ui.datepicker-th.js\n".
                     "jquery.ui.datepicker-uk.js\n".
                     "jquery.ui.datepicker-vi.js\n".
                     "jquery.validate.min.js\njQuery.stringify.js\njquery.validate.js\njquery.js\n".$options;
}

add_filter('option_sbp_settings', 'apphourbk_sbp_fix_conflict' );
function apphourbk_sbp_fix_conflict($option)
{
    if(!is_admin())
    {
       if(is_array($option) && isset($option['jquery_to_footer'])) 
           unset($option['jquery_to_footer']);
    }
    return $option;
}

function apphourbk_tp_disable_filter()
{
    return false;
}

// elementor integration
include_once dirname( __FILE__ ) . '/controllers/elementor/cp-elementor-widget.inc.php';

?>