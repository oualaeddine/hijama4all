<?php

class CP_AppBookingPlugin extends CP_APPBOOK_BaseClass {

    private $menu_parameter = 'cp_apphourbooking';
    private $prefix = 'cp_appbooking';
    private $plugin_name = 'Appointment Hour Booking';
    private $plugin_URL = 'https://apphourbooking.dwbooster.com/';
    private $plugin_download_URL = 'https://apphourbooking.dwbooster.com/download';
    public $table_items = "cpappbk_forms";
    public $table_messages = "cpappbk_messages";
    public $print_counter = 1;
    private $include_user_data_csv = false;
    private $booking_form_nonce = false;
    public $CP_CFPP_global_templates;
    protected $postURL;

    protected $paid_statuses = array('Pending','Cancelled','Cancelled by customer','Rejected','Attended');
    public $shorttag = 'CP_APP_HOUR_BOOKING';
    public $blocked_by_admin_indicator = 'BLOCKED@BY.ADMIN';

    protected $tags_allowed = array(
                                  'a' => array(
                                      'href' => array(),
                                      'title' => array(),
                                      'style' => array(),
                                      'class' => array(),
                                  ),
                                  'br' => array(),
                                  'em' => array(),
                                  'b' => array(),
                                  'strong' => array(),
                                  'img' => array(
                                            'src' => array(),
                                            'width' => array(),
                                            'height' => array(),
                                            'border' => array(),
                                            'style' => array(),
                                            'class' => array(),
                                            ),
                              );

    function _install() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $charset_collate = $wpdb->get_charset_collate();

        $results = $wpdb->get_results("SHOW TABLES LIKE '".$wpdb->prefix.$this->table_messages."'");
        if (!count($results))
        {
            $sql = "CREATE TABLE ".$wpdb->prefix.$this->table_messages." (
                id int(10) NOT NULL AUTO_INCREMENT,
                formid INT NOT NULL,
                time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                ipaddr VARCHAR(250) DEFAULT '' NOT NULL,
                notifyto VARCHAR(250) DEFAULT '' NOT NULL,
                data mediumtext,
                posted_data mediumtext,
                whoadded VARCHAR(250) DEFAULT '' NOT NULL,
                UNIQUE KEY id (id)
            )".$charset_collate.";";
            $wpdb->query($sql);
        }

        $results = $wpdb->get_results("SHOW TABLES LIKE '".$wpdb->prefix.$this->table_items."'");
        if (!count($results))
        {
            $sql = "CREATE TABLE ".$wpdb->prefix.$this->table_items." (
                 id mediumint(9) NOT NULL AUTO_INCREMENT,

                 form_name VARCHAR(250) DEFAULT '' NOT NULL,

                 form_structure mediumtext,

                 calendar_language VARCHAR(250) DEFAULT '' NOT NULL,
                 date_format VARCHAR(250) DEFAULT '' NOT NULL,
                 product_name VARCHAR(250) DEFAULT '' NOT NULL,
                 pay_later_label VARCHAR(250) DEFAULT '' NOT NULL,

                 defaultstatus VARCHAR(250) DEFAULT '' NOT NULL,
                 defaultpaidstatus VARCHAR(250) DEFAULT '' NOT NULL,

                 fp_from_email VARCHAR(250) DEFAULT '' NOT NULL,
                 fp_destination_emails text,
                 fp_subject VARCHAR(250) DEFAULT '' NOT NULL,
                 fp_inc_additional_info VARCHAR(10) DEFAULT '' NOT NULL,
                 fp_return_page VARCHAR(250) DEFAULT '' NOT NULL,
                 fp_message text,
                 fp_emailformat VARCHAR(10) DEFAULT '' NOT NULL,

                 fp_emailtomethod VARCHAR(10) DEFAULT '' NOT NULL,
                 fp_destination_emails_field VARCHAR(200) DEFAULT '' NOT NULL,
                 cu_enable_copy_to_user VARCHAR(10) DEFAULT '' NOT NULL,
                 cu_user_email_field VARCHAR(250) DEFAULT '' NOT NULL,
                 cu_subject VARCHAR(250) DEFAULT '' NOT NULL,
                 cu_message text,
                 cu_emailformat VARCHAR(10) DEFAULT '' NOT NULL,
                 fp_emailfrommethod VARCHAR(10) DEFAULT '' NOT NULL,

                 vs_text_maxapp TEXT DEFAULT '' NOT NULL,
                 vs_text_is_required TEXT DEFAULT '' NOT NULL,
                 vs_text_is_email TEXT DEFAULT '' NOT NULL,
                 vs_text_datemmddyyyy TEXT DEFAULT '' NOT NULL,
                 vs_text_dateddmmyyyy TEXT DEFAULT '' NOT NULL,
                 vs_text_number TEXT DEFAULT '' NOT NULL,
                 vs_text_digits TEXT DEFAULT '' NOT NULL,
                 vs_text_max TEXT DEFAULT '' NOT NULL,
                 vs_text_min TEXT DEFAULT '' NOT NULL,
                 vs_text_pageof TEXT DEFAULT '' NOT NULL,
                 vs_text_submitbtn TEXT DEFAULT '' NOT NULL,
                 vs_text_previousbtn TEXT DEFAULT '' NOT NULL,
                 vs_text_nextbtn TEXT DEFAULT '' NOT NULL,

                 vs_text_quantity TEXT DEFAULT '' NOT NULL,
                 vs_text_cancel TEXT DEFAULT '' NOT NULL,
                 vs_text_cost TEXT DEFAULT '' NOT NULL,
                 vs_text_nmore TEXT DEFAULT '' NOT NULL,

                 cp_user_access text,
                 cp_user_access_settings VARCHAR(10) DEFAULT '' NOT NULL,
                 display_emails_endtime VARCHAR(10) DEFAULT '' NOT NULL,
                 rep_enable VARCHAR(10) DEFAULT '' NOT NULL,
                 rep_days VARCHAR(10) DEFAULT '' NOT NULL,
                 rep_hour VARCHAR(10) DEFAULT '' NOT NULL,
                 rep_emails text,
                 rep_subject text,
                 rep_emailformat VARCHAR(10) DEFAULT '' NOT NULL,
                 rep_message text,

                 cv_enable_captcha VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_width VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_height VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_chars VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_font VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_min_font_size VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_max_font_size VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_noise VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_noise_length VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_background VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_border VARCHAR(20) DEFAULT '' NOT NULL,
                 cv_text_enter_valid_captcha VARCHAR(200) DEFAULT '' NOT NULL,

                 UNIQUE KEY id (id)
            )".$charset_collate.";";
            $wpdb->query($sql);
        }

        // insert initial data
        $count = $wpdb->get_var(  "SELECT COUNT(id) FROM ".$wpdb->prefix.$this->table_items  );
        if (!$count)
        {
            define('CP_APPBOOK_DEFAULT_fp_from_email', get_the_author_meta('user_email', get_current_user_id()) );
            define('CP_APPBOOK_DEFAULT_fp_destination_emails', CP_APPBOOK_DEFAULT_fp_from_email);
            $wpdb->insert( $wpdb->prefix.$this->table_items, array( 'id' => 1,
                                      'form_name' => 'Form 1',

                                      'form_structure' => $this->get_option('form_structure', CP_APPBOOK_DEFAULT_form_structure),

                                      'calendar_language' => $this->get_option('calendar_language', ''),
                                      'date_format' => $this->get_option('date_format', 'mm/dd/yy'),
                                      'product_name' => $this->get_option('fp_from_email', 'Booking'),
                                      'pay_later_label' => $this->get_option('fp_from_email', 'Pay later'),

                                      'fp_from_email' => $this->get_option('fp_from_email', CP_APPBOOK_DEFAULT_fp_from_email),
                                      'fp_destination_emails' => $this->get_option('fp_destination_emails', CP_APPBOOK_DEFAULT_fp_destination_emails),
                                      'fp_subject' => $this->get_option('fp_subject', CP_APPBOOK_DEFAULT_fp_subject),
                                      'fp_inc_additional_info' => $this->get_option('fp_inc_additional_info', CP_APPBOOK_DEFAULT_fp_inc_additional_info),
                                      'fp_return_page' => $this->get_option('fp_return_page', CP_APPBOOK_DEFAULT_fp_return_page),
                                      'fp_message' => $this->get_option('fp_message', CP_APPBOOK_DEFAULT_fp_message),
                                      'fp_emailformat' => $this->get_option('fp_emailformat', CP_APPBOOK_DEFAULT_email_format),

                                      'cu_enable_copy_to_user' => $this->get_option('cu_enable_copy_to_user', CP_APPBOOK_DEFAULT_cu_enable_copy_to_user),
                                      'cu_user_email_field' => $this->get_option('cu_user_email_field', CP_APPBOOK_DEFAULT_cu_user_email_field),
                                      'cu_subject' => $this->get_option('cu_subject', CP_APPBOOK_DEFAULT_cu_subject),
                                      'cu_message' => $this->get_option('cu_message', CP_APPBOOK_DEFAULT_cu_message),
                                      'cu_emailformat' => $this->get_option('cu_emailformat', CP_APPBOOK_DEFAULT_email_format),

                                      'vs_text_maxapp' => $this->get_option('vs_text_maxapp', CP_APPBOOK_DEFAULT_vs_text_maxapp),
                                      'vs_text_is_required' => $this->get_option('vs_text_is_required', CP_APPBOOK_DEFAULT_vs_text_is_required),
                                      'vs_text_is_email' => $this->get_option('vs_text_is_email', CP_APPBOOK_DEFAULT_vs_text_is_email),
                                      'vs_text_datemmddyyyy' => $this->get_option('vs_text_datemmddyyyy', CP_APPBOOK_DEFAULT_vs_text_datemmddyyyy),
                                      'vs_text_dateddmmyyyy' => $this->get_option('vs_text_dateddmmyyyy', CP_APPBOOK_DEFAULT_vs_text_dateddmmyyyy),
                                      'vs_text_number' => $this->get_option('vs_text_number', CP_APPBOOK_DEFAULT_vs_text_number),
                                      'vs_text_digits' => $this->get_option('vs_text_digits', CP_APPBOOK_DEFAULT_vs_text_digits),
                                      'vs_text_max' => $this->get_option('vs_text_max', CP_APPBOOK_DEFAULT_vs_text_max),
                                      'vs_text_min' => $this->get_option('vs_text_min', CP_APPBOOK_DEFAULT_vs_text_min),
                                      'vs_text_pageof' => $this->get_option('vs_text_pageof', 'Page {0} of {0}'),
                                      'vs_text_submitbtn' => $this->get_option('vs_text_submitbtn', 'Submit'),
                                      'vs_text_previousbtn' => $this->get_option('vs_text_previousbtn', 'Previous'),
                                      'vs_text_nextbtn' => $this->get_option('vs_text_nextbtn', 'Next'),

                                      'vs_text_quantity' => $this->get_option_not_empty('vs_text_quantity', 'Quantity'),
                                      'vs_text_cancel' => $this->get_option_not_empty('vs_text_cancel', 'Cancel'),
                                      'vs_text_cost' => $this->get_option_not_empty('vs_text_cost', 'Cost'),
                                      'vs_text_nmore' => $this->get_option_not_empty('vs_text_nmore', 'Selected time is no longer available. Please select a different time.'),

                                      'rep_enable' => $this->get_option('rep_enable', 'no'),
                                      'rep_days' => $this->get_option('rep_days', '1'),
                                      'rep_hour' => $this->get_option('rep_hour', '0'),
                                      'rep_emails' => $this->get_option('rep_emails', ''),
                                      'rep_subject' => $this->get_option('rep_subject', 'Submissions report...'),
                                      'rep_emailformat' => $this->get_option('rep_emailformat', 'text'),
                                      'rep_message' => $this->get_option('rep_message', 'Attached you will find the data from the form submissions.'),

                                      'cv_enable_captcha' => $this->get_option('cv_enable_captcha', CP_APPBOOK_DEFAULT_cv_enable_captcha),
                                      'cv_width' => $this->get_option('cv_width', CP_APPBOOK_DEFAULT_cv_width),
                                      'cv_height' => $this->get_option('cv_height', CP_APPBOOK_DEFAULT_cv_height),
                                      'cv_chars' => $this->get_option('cv_chars', CP_APPBOOK_DEFAULT_cv_chars),
                                      'cv_font' => $this->get_option('cv_font', CP_APPBOOK_DEFAULT_cv_font),
                                      'cv_min_font_size' => $this->get_option('cv_min_font_size', CP_APPBOOK_DEFAULT_cv_min_font_size),
                                      'cv_max_font_size' => $this->get_option('cv_max_font_size', CP_APPBOOK_DEFAULT_cv_max_font_size),
                                      'cv_noise' => $this->get_option('cv_noise', CP_APPBOOK_DEFAULT_cv_noise),
                                      'cv_noise_length' => $this->get_option('cv_noise_length', CP_APPBOOK_DEFAULT_cv_noise_length),
                                      'cv_background' => $this->get_option('cv_background', CP_APPBOOK_DEFAULT_cv_background),
                                      'cv_border' => $this->get_option('cv_border', CP_APPBOOK_DEFAULT_cv_border),
                                      'cv_text_enter_valid_captcha' => $this->get_option('cv_text_enter_valid_captcha', CP_APPBOOK_DEFAULT_cv_text_enter_valid_captcha)
                                     )
                      );
        }
    }


    public function get_status_list()
    {
        $statuses = array('Approved');
        foreach ($this->paid_statuses as $item)
            $statuses[] = $item;
        return $statuses;
    }


    function render_status_box($name, $selected, $displayall = false)
    {
        echo '<select name="'.$name.'" id="'.$name.'">';
        if ($displayall)
            echo '<option value="all"'.($selected == 'all'?' selected':'').'>'.__('[All]','appointment-hour-booking').'</option>';
        echo '<option value=""'.($selected == ''?' selected':'').'>'.__('Approved','appointment-hour-booking').'</option>';
        foreach ($this->paid_statuses as $item)
            echo '<option value="'.$item.'"'.($selected == $item?' selected':'').'>'.__($item,'appointment-hour-booking').'</option>';
        echo '</select>';
    }


    public function update_status($id, $status)
    {
        global $wpdb;
        $events = $wpdb->get_results( $wpdb->prepare('SELECT * FROM `'.$wpdb->prefix.$this->table_messages.'` WHERE id=%d', $id) );
        $posted_data = unserialize($events[0]->posted_data);
        $countapps = count($posted_data["apps"]);
        for($k=0; $k<$countapps; $k++)
        {
             $posted_data["apps"][$k]["cancelled"] = $status;
             $posted_data["app_status_".($k+1)] = $status;
        }
        $posted_data = serialize($posted_data);
        $wpdb->update ( $wpdb->prefix.$this->table_messages, array( 'posted_data' => $posted_data ), array( 'id' => $id ));
        do_action( 'cpappb_update_status', $id, $status );
    }


    /* Using timezone from WordPress settings */
    public function localtimezone_strtotime($datestring)
    {
        $timezone_string = get_option('timezone_string');
        $gmt_offset = get_option('gmt_offset', 0);
        if (empty($timezone_string))
        {
            if ($gmt_offset == 0)
                $timezone_string = 'UTC';
            else
            {
                $timezone_string = $gmt_offset;
                if(substr($gmt_offset, 0, 1) != "-" && substr($gmt_offset, 0, 1) != "+" && substr($gmt_offset, 0, 1) != "U")
                    $timezone_string = "+" . $gmt_offset;
            }
        }
        try
        {
            $result = new DateTime($datestring, new DateTimeZone($timezone_string));
            return strtotime($result->format('Y-m-d H:i:s'));
        }
        catch (Exception $e)
        {
            return strtotime($datestring);
        }
    }


    /* Filter for placing the item into the contents */
    public function filter_list($atts) {
        global $wpdb;
        extract( shortcode_atts( array(
	    	'calendar' => '',  // accepts comma separated IDs
	    	'fields' => 'DATE,TIME,email',
	    	'from' => "today",
	    	'to' => "today +30 days",
            'paidonly' => "",
            'status' => "notcancelled",   // "all" means all statuses
            'service' => "",
            'email' => "",
            'onlycurrentuser' => "",
            'maxitems' => "",
            'limit' => "10000"
	    ), $atts ) );

        if (!is_admin())
        {
            wp_enqueue_style('cpapp-publicstyle', plugins_url('css/stylepublic.css', __FILE__));
            wp_enqueue_style('cpapp-custompublicstyle', $this->fixurl($this->get_site_url( false ),'cp_cpappb_resources=css'));
        }

        if (strtolower($status) == 'approved')
            $status = '';

        ob_start();

        if ($this->get_option('date_format', 'mm/dd/yy') == 'dd/mm/yy')
        {
            $from = str_replace('/','.',$from);
            $to = str_replace('/','.',$to);
        }

        // calculate dates
        $from = date("Y-m-d",$this->localtimezone_strtotime($from));
        $to = date("Y-m-d",$this->localtimezone_strtotime($to));
        $to_query = date("Y-m-d",$this->localtimezone_strtotime($to." +1 day"));




        $calquery = '';
        $calendar = explode (",",$calendar);
        foreach ($calendar as $cal)
            if (trim($cal))
            {
                $calquery .= ($calquery!=''?' OR ':'').'formid='.intval(trim($cal));
                $this->setId(intval(trim($cal)));
            }
        if ($calquery != '')
            $calquery = '('.$calquery.') AND ';

        if (strtolower($onlycurrentuser) == 'yes' || strtolower($onlycurrentuser) == 'true')
        {
            $current_user = wp_get_current_user();
            if ($current_user->ID)
                $user_query = " whoadded='".esc_sql($current_user->ID)."' AND ";
            else
                $user_query = "(1=0) AND "; // if no logged in user then no current user bookings
        }
        else
            $user_query = '';

        if ($email != '')
           $user_query .= " (notifyto='".esc_sql($email)."') AND ";

        // pre-select time-slots
        $selection = array();
        $rows = $wpdb->get_results( $wpdb->prepare("SELECT notifyto,posted_data,data,formid FROM ".$wpdb->prefix.$this->table_messages." WHERE notifyto<>'".esc_sql($this->blocked_by_admin_indicator)."' AND ".$user_query.$calquery."time<=%s ORDER BY time DESC LIMIT 0,".intval($limit), $to_query) );

        foreach($rows as $item)
        {
            $data = unserialize($item->posted_data);
            if (!$paidonly || (!empty($data['paid']) && $data['paid']))
            {
                $appindex = 0;
                if (is_array($data["apps"]))
                    foreach($data["apps"] as $app)
                    {
                        $appindex++;
                        if ($app["date"] >= $from && $app["date"] <= $to &&
                           ($status == 'notcancelled' || $status == 'all' || $status == $app["cancelled"]) &&
                           ($status != 'notcancelled' || ('Cancelled by customer' != $app["cancelled"] && 'Cancelled' != $app["cancelled"]) )
                           )
                        {
                            if ($service == '' || $service == $app["service"])
                                $selection[] = array(
                                                     $app["date"]." ".$app["slot"],
                                                     $app["date"],
                                                     $app["slot"],
                                                     $data,
                                                     sanitize_email($item->notifyto),
                                                     $item->data,
                                                     $app["cancelled"],
                                                     $app["service"],
                                                     $appindex,                       // 8
                                                     $item->formid,                   // 9
                                                     $app["quant"]              // 10
                                                    );
                        }
                    }
            }
        }

        // order time-slots
        if (!function_exists('appbkfastsortfn'))
        {
            function appbkfastsortfn($a, $b) { return ($a[0] > $b[0]?1:-1); }
        }
        usort($selection, "appbkfastsortfn" );

        // clean fields IDs
        $fields = explode(",",trim($fields));
        for($j=0; $j<count($fields); $j++)
            $fields[$j] = strtolower(trim($fields[$j]));

        // print table
        if (!count($selection))
            echo '<div class="cpappb_noapps">'.__('No appointments found with the selected filters','appointment-hour-booking').'</div>';
        else for($i=0; $i<count($selection) && ($maxitems == '' || $i < $maxitems); $i++)
        {
            echo '<div class="cpapp_no_wrap">';
            for($j=0; $j<count($fields); $j++)
            {
                echo '<div class="cpappb_field_'.$j.($selection[$i][6]!=''?' cpappb_cancelled':'').'">';
                switch ($fields[$j]) {
                    case 'rownumber':
                        echo $i+1;
                        break;
                    case 'weekday':
                        echo ucfirst(__(date('l',strtotime($selection[$i][1]))));
                        break;
                    case 'date':
                        echo $this->format_date($selection[$i][1]);
                        break;
                    case 'time':
                        echo esc_html($selection[$i][2]);
                        break;
                    case 'email':
                        echo esc_html($selection[$i][4])."&nbsp;";
                        break;
                    case 'service':
                        echo esc_html($selection[$i][7])."&nbsp;";
                        break;
                    case 'quantity':
                        echo $selection[$i][10]."&nbsp;";
                        break;
                    case 'cancelled':
                        if ($selection[$i][6] == '')
                            echo __('Approved','appointment-hour-booking');
                        else
                            echo esc_html(__($selection[$i][6],'appointment-hour-booking'));
                        echo '&nbsp;';
                        break;
                    case 'data':
                        echo esc_html(substr($selection[$i][5],strpos($selection[$i][5],"\n\n")+2));
                        break;
                    case 'paid':
                        echo esc_html(( (!empty($selection[$i][3]['paid']) && $selection[$i][3]['paid'])?__('Yes','appointment-hour-booking'):'&nbsp;'));
                        break;
                    default:
                        if (is_array($selection[$i][3][$fields[$j]]))
                            echo esc_html(implode(",",$selection[$i][3][$fields[$j]]));
                        else
                            echo esc_html(($selection[$i][3][$fields[$j]]==''?'&nbsp;':$selection[$i][3][$fields[$j]])."&nbsp;");
                }
                echo '</div>';
            }
            echo '</div>';
            echo '<div class="cpapp_break"></div>';
        }

        $buffered_contents = ob_get_contents();
        ob_end_clean();
        return $buffered_contents;
    }


    /* Filter for placing the item into the contents */
    public function filter_content($atts) {
        global $wpdb;
        extract( shortcode_atts( array(
    		                           'id' => '',
    	                        ), $atts ) );
        if ($id != '')
            $this->item = intval($id);

    	/**
    	 * Filters applied before generate the form,
    	 * is passed as parameter an array with the forms attributes, and return the list of attributes
    	 */
        $atts = apply_filters( 'cpappb_pre_form',  $atts );

        ob_start();
        $this->insert_public_item();
        $buffered_contents = ob_get_contents();
        ob_end_clean();

	    /**
	     * Filters applied after generate the form,
	     * is passed as parameter the HTML code of the form with the corresponding <LINK> and <SCRIPT> tags,
	     * and returns the HTML code to includes in the webpage
	     */
	    $buffered_contents = apply_filters( 'cpappb_the_form', $buffered_contents,  $this->item );

        return $buffered_contents;
    }


    function insert_public_item() {
        global $wpdb;

        // Initializing the $form_counter
        if(!isset($GLOBALS['codepeople_form_sequence_number'])) $GLOBALS['codepeople_form_sequence_number'] = 0;
        $GLOBALS['codepeople_form_sequence_number']++;
        $this->print_counter = $GLOBALS['codepeople_form_sequence_number']; // Current form
        
        $pageof_label = $this->get_option('vs_text_pageof', 'Page {0} of {0}');
        $pageof_label = ($pageof_label==''?'Page {0} of {0}':$pageof_label);
        $previous_label = $this->get_option('vs_text_previousbtn', 'Previous');
        $previous_label = ($previous_label==''?'Previous':$previous_label);
        $next_label = $this->get_option('vs_text_nextbtn', 'Next');
        $next_label = ($next_label==''?'Next':$next_label);

        $calendar_language = $this->get_option('calendar_language','');
        if ($calendar_language == '') $calendar_language = $this->autodetect_language();

        if (CP_APPBOOK_DEFER_SCRIPTS_LOADING)
        {
            wp_enqueue_style('cpapp-calendarstyle', plugins_url('css/cupertino/calendar.css', __FILE__));
            wp_enqueue_style('cpapp-publicstyle', plugins_url('css/stylepublic.css', __FILE__));
            wp_enqueue_style('cpapp-custompublicstyle', $this->fixurl($this->get_site_url( false ),'cp_cpappb_resources=css'));
            if ( !defined('ELEMENTOR_PRO_VERSION'))
                wp_enqueue_style('wp-jquery-ui-dialog');

            if ($calendar_language != '' && file_exists(dirname( __FILE__ ).'/js/languages/jquery.ui.datepicker-'.$calendar_language.'.js'))
                wp_enqueue_script($this->prefix.'_language_file', plugins_url('js/languages/jquery.ui.datepicker-'.$calendar_language.'.js', __FILE__),array("jquery","jquery-ui-core","jquery-ui-sortable","jquery-ui-tabs","jquery-ui-droppable","jquery-ui-button","jquery-ui-datepicker"));

            wp_enqueue_script( $this->prefix.'_builder_script',
               $this->fixurl($this->get_site_url( false ), 'cp_cpappb_resources=public'),array("jquery","jquery-ui-core","jquery-ui-datepicker","jquery-ui-widget","jquery-ui-position","jquery-ui-tooltip","jquery-ui-dialog"), false, true );

            wp_enqueue_script( $this->prefix.'_customjs', $this->fixurl($this->get_site_url( false ),'cp_cpappb_resources=customjs&cal='.$this->item.'&nc=1'),array($this->prefix.'_builder_script'));

            wp_localize_script($this->prefix.'_builder_script', $this->prefix.'_fbuilder_config'.('_'.$this->print_counter), array('obj' =>
            '{"pub":true,"identifier":"'.('_'.$this->print_counter).'","messages": {
            	                	"required": "'.str_replace(array('"'),array('\\"'),__($this->get_option('vs_text_is_required', CP_APPBOOK_DEFAULT_vs_text_is_required),'appointment-hour-booking')).'",
                                    "maxapp": "'.str_replace(array('"'),array('\\"'),__($this->get_option('vs_text_maxapp', CP_APPBOOK_DEFAULT_vs_text_maxapp),'appointment-hour-booking')).'",
                                    "language": "'.str_replace(array('"'),array('\\"'),$calendar_language).'",
                                    "date_format": "'.str_replace(array('"'),array('\\"'),$this->get_option('date_format', 'mm/dd/yy')).'",
            	                	"email": "'.str_replace(array('"'),array('\\"'),__($this->get_option('vs_text_is_email', CP_APPBOOK_DEFAULT_vs_text_is_email),'appointment-hour-booking')).'",
            	                	"datemmddyyyy": "'.str_replace(array('"'),array('\\"'),__($this->get_option('vs_text_datemmddyyyy', CP_APPBOOK_DEFAULT_vs_text_datemmddyyyy),'appointment-hour-booking')).'",
            	                	"dateddmmyyyy": "'.str_replace(array('"'),array('\\"'),__($this->get_option('vs_text_dateddmmyyyy', CP_APPBOOK_DEFAULT_vs_text_dateddmmyyyy),'appointment-hour-booking')).'",
            	                	"number": "'.str_replace(array('"'),array('\\"'),__($this->get_option('vs_text_number', CP_APPBOOK_DEFAULT_vs_text_number),'appointment-hour-booking')).'",
            	                	"digits": "'.str_replace(array('"'),array('\\"'),__($this->get_option('vs_text_digits', CP_APPBOOK_DEFAULT_vs_text_digits),'appointment-hour-booking')).'",
            	                	"max": "'.str_replace(array('"'),array('\\"'),__($this->get_option('vs_text_max', CP_APPBOOK_DEFAULT_vs_text_max),'appointment-hour-booking')).'",
            	                	"min": "'.str_replace(array('"'),array('\\"'),__($this->get_option('vs_text_min', CP_APPBOOK_DEFAULT_vs_text_min),'appointment-hour-booking')).'",
                                    "maxlength": "'.str_replace(array('"'),array('\\"'),__('Please enter no more than {0} characters.','appointment-hour-booking')).'",
                                    "minlength": "'.str_replace(array('"'),array('\\"'),__('Please enter at least {0} characters.','appointment-hour-booking')).'",
    	                    	    "previous": "'.str_replace(array('"'),array('\\"'),$previous_label).'",
    	                    	    "next": "'.str_replace(array('"'),array('\\"'),$next_label).'",
    	                    	    "pageof": "'.str_replace(array('"'),array('\\"'),$pageof_label).'"
            	                }}'
            ));
        }
        else
        {
            wp_enqueue_script( "jquery" );
            wp_enqueue_script( "jquery-ui-core" );
            wp_enqueue_script( "jquery-ui-datepicker" );
        }

        ?><!--noptimize-->
        <script type="text/javascript">
         var cp_hourbk_cancel_label = '<?php echo str_replace("'", "\'", __( $this->get_option_not_empty('vs_text_cancel', 'Cancel') ,'appointment-hour-booking')); ?>';
         var cp_hourbk_quantity_label = '<?php echo str_replace("'", "\'", __( $this->get_option_not_empty('vs_text_quantity', 'Quantity') ,'appointment-hour-booking')); ?>';
         var cp_hourbk_cost_label = '<?php echo str_replace("'", "\'", __( $this->get_option_not_empty('vs_text_cost', 'Cost') ,'appointment-hour-booking')); ?>';
         var cp_hourbk_overlapping_label = '<?php echo str_replace("'", "\'", __( $this->get_option_not_empty('vs_text_nmore', 'Selected time is no longer available. Please select a different time.') ,'appointment-hour-booking')); ?>';
         var cp_hourbk_nomore_label = '<?php echo str_replace("'", "\'", __("No more slots available.",'appointment-hour-booking')); ?>';
         var cp_hourbk_avoid_overlapping = 0;
         var apphboverbooking_handler<?php echo $this->print_counter-1; ?> = false;
         function <?php echo $this->prefix; ?>_pform_doValidate<?php echo '_'.$this->print_counter; ?>(form)
         {
            try { if (document.<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>.<?php echo $this->prefix; ?>_pform_status.value == '1') return false; } catch (e) {}
            $dexQuery = jQuery.noConflict();
            try { document.<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>.cp_ref_page.value = document.location; } catch (e) {}
            $dexQuery = jQuery.noConflict();<?php if (!is_admin() && $this->get_option('cv_enable_captcha', CP_APPBOOK_DEFAULT_cv_enable_captcha) != 'false') { ?>
            if (!apphboverbooking_handler<?php echo $this->print_counter-1; ?>) {
            if (document.<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>.hdcaptcha_<?php echo $this->prefix; ?>_post.value == '') { setTimeout( "<?php echo $this->prefix; ?>_cerror<?php echo '_'.$this->print_counter; ?>()", 100); return false; }
            var result = $dexQuery.ajax({ type: "GET", url: "<?php echo $this->get_site_url(); ?>?ps=<?php echo '_'.$this->print_counter; ?>&<?php echo $this->prefix; ?>_pform_process=2&<?php echo $this->prefix; ?>_id=<?php echo intval($this->item); ?>&inAdmin=1&ps=<?php echo '_'.$this->print_counter; ?>&hdcaptcha_<?php echo $this->prefix; ?>_post="+document.<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>.hdcaptcha_<?php echo $this->prefix; ?>_post.value, async: false }).responseText;
            }
            if (!apphboverbooking_handler<?php echo $this->print_counter-1; ?> && result.indexOf("captchafailed") != -1) {
                $dexQuery("#captchaimg<?php echo '_'.$this->print_counter; ?>").attr('src', $dexQuery("#captchaimg<?php echo '_'.$this->print_counter; ?>").attr('src')+'&'+Math.floor((Math.random() * 99999) + 1));
                setTimeout( "<?php echo $this->prefix; ?>_cerror<?php echo '_'.$this->print_counter; ?>()", 100);
                return false;
            } else <?php } ?>
            {
                var cpefb_error = 0;
                $dexQuery("#<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>").find(".cpefb_error").each(function(index){
                    if ($dexQuery(this).css("display")!="none")
                        cpefb_error++;
                    })
                if (cpefb_error==0)
                {
                    if (!apphboverbooking_handler<?php echo $this->print_counter-1; ?>)
                    {
                        try { document.<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>.<?php echo $this->prefix; ?>_pform_status.value = '1';	 } catch (e) {}
                        apphbblink<?php echo '_'.$this->print_counter; ?>(".pbSubmit:visible");
                        $dexQuery("#<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>").find(".avoid_overlapping_before").not(".ignore,.ignorepb").removeClass("avoid_overlapping_before").removeClass("valid").addClass("avoid_overlapping");
                        cp_hourbk_avoid_overlapping = 1;
                        try {
                            $dexQuery("#<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>").find(".avoid_overlapping").valid();
                        } catch (e) { cp_hourbk_avoid_overlapping = 0; }
                        function check_cp_hourbk_avoid_overlapping(){
		                    if (cp_hourbk_avoid_overlapping>0)
		                        setTimeout(check_cp_hourbk_avoid_overlapping,100);
		                    else
		                    {
                                var cpefb_error = 0;
                                $dexQuery("#<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>").find(".cpefb_error").each(function(index){
                                    if ($dexQuery(this).css("display")!="none")
                                        cpefb_error++;
                                    });
                                try { document.<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>.<?php echo $this->prefix; ?>_pform_status.value = '0';	 } catch (e) {}
                                if (cpefb_error==0)
                                {
                                    apphboverbooking_handler<?php echo $this->print_counter-1; ?> = true;
                                    if (<?php echo $this->prefix; ?>_pform_doValidate<?php echo '_'.$this->print_counter; ?>(form))
                                    {
                                        $dexQuery( ".pbSubmit" ).unbind();
                                        if ($dexQuery( ".pbSubmit" ).hasClass("nofirst"))
                                            return false;
                                        $dexQuery( ".pbSubmit" ).addClass("nofirst");
                                        document.getElementById("<?php echo $this->prefix.'_pform_'.($this->print_counter); ?>").submit();
                                    }
                                }
		                    }
		                }
		                check_cp_hourbk_avoid_overlapping();
                        return false;
                    }
                    <?php
                    /**
				     * Action called before insert the data into database.
				     * To the function are passed two parameters: the array with submitted data, and the number of form in the page.
				     */

				    do_action( 'cpappb_script_after_validation', $this->print_counter, $this->item );

	                ?>
	        		$dexQuery("#<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>").find( '.ignore' ).closest( '.fields' ).remove();
	        	}
	        	document.getElementById("form_structure<?php echo '_'.$this->print_counter; ?>").value = '';
	        	document.getElementById("refpage<?php echo '_'.$this->print_counter; ?>").value = document.location;
                try {
                    if (cpefb_error==0)
                    {
                        document.<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>.<?php echo $this->prefix; ?>_pform_status.value = '1';
                        apphbblink<?php echo '_'.$this->print_counter; ?>(".pbSubmit");
                    }
                } catch (e) {}
                return true;
            }
         }
         function apphbblink<?php echo '_'.$this->print_counter; ?>(selector){
             try {
                 $dexQuery = jQuery.noConflict();
                 $dexQuery(selector).fadeOut(1000, function(){
                     $dexQuery(this).fadeIn(1000, function(){
                             try {
                                 if (document.<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>.<?php echo $this->prefix; ?>_pform_status.value != '0')
                                     apphbblink<?php echo '_'.$this->print_counter; ?>(this);
                             } catch (e) { console.log(e)}
                     });
                 });
             } catch (e) {}
         }
         function <?php echo $this->prefix; ?>_cerror<?php echo '_'.$this->print_counter; ?>(){$dexQuery = jQuery.noConflict();$dexQuery("#hdcaptcha_error<?php echo '_'.$this->print_counter; ?>").css('top',$dexQuery("#hdcaptcha_<?php echo $this->prefix; ?>_post<?php echo '_'.$this->print_counter; ?>").outerHeight());$dexQuery("#hdcaptcha_error<?php echo '_'.$this->print_counter; ?>").css("display","inline");}
        </script><!--/noptimize-->
        <?php

        $button_label = $this->get_option('vs_text_submitbtn', 'Submit');
        $button_label = ($button_label==''?'Submit':$button_label);


        // START:: code to load form settings
        $raw_form_str = str_replace("\r"," ",str_replace("\n"," ",$this->cleanJSON($this->translate_json($this->get_option('form_structure', CP_APPBOOK_DEFAULT_form_structure)))));

        $form_data = json_decode( $raw_form_str );
        if( is_null( $form_data ) ){
        	$json = new JSON;
        	$form_data = $json->unserialize( $raw_form_str );
        }

        if( !is_null( $form_data ) )
        {
        	if( !empty( $form_data[ 0 ] ) )
        	{
        		foreach( $form_data[ 0 ] as $key => $object )
        		{
        			if ($object->ftype == 'fcheck' || $object->ftype == 'fradio' || $object->ftype == 'fdropdown')
        			{
        			    for($ki=0; $ki<count($object->choicesVal); $ki++)
        			        $object->choicesVal[$ki] = str_replace('@', CP_APPBOOK_REP_ARR, $object->choicesVal[$ki]);
        			    $form_data[ 0 ][ $key ] = $object;
        				$raw_form_str = json_encode( $form_data );
        			}
                    if (defined('CPAPPHOURBK_BLOCK_TIMES'))
                    {
                        if ($object->ftype != 'fapp')
                        {
                            $object->csslayout = 'donotdisplayfield';
                            if (@$object->required)
                                $object->required = false;
                            if (!empty($object->minlength))
                                $object->minlength = '';
                            if (!empty($object->maxlength))
                                $object->maxlength = '';
                            if (@$object->equalTo)
                                $object->equalTo = '';
                            $object->ftype = 'ftext';
                        }
                        else
                        {
                            $object->maxNumberOfApp = 9999;
                            $object->showQuantity = 1;
                        }
                        $form_data[ 0 ][ $key ] = $object;
                        $raw_form_str = json_encode( $form_data );
                    }
        		}
        	}

        	if( isset( $form_data[ 1 ] ) && isset( $form_data[ 1 ][ 0 ] ) && isset( $form_data[ 1 ][ 0 ]->formtemplate ) )
        	{
        		$templatelist = $this->available_templates();
        		if(  isset( $templatelist[ $form_data[ 1 ][ 0 ]->formtemplate ] ) )
        		print '<link href="'.esc_attr( esc_url( $templatelist[ $form_data[ 1 ][ 0 ]->formtemplate ][ 'file' ] ) ).'" type="text/css" rel="stylesheet" />';
        	}
        }

        $raw_form_str = str_replace('"','&quot;',esc_attr($raw_form_str));
        // END:: code to load form settings

        if (!defined('CP_AUTH_INCLUDE')) define('CP_AUTH_INCLUDE',true);
        @include dirname( __FILE__ ) . '/cp-public-int.inc.php';
        if (!CP_APPBOOK_DEFER_SCRIPTS_LOADING)
        {
            $prefix_ui = '';
            if (@file_exists(dirname( __FILE__ ).'/../../../wp-includes/js/jquery/ui/jquery.ui.core.min.js'))
                $prefix_ui = 'jquery.ui.';
            // This code won't be used in most cases. This code is for preventing problems in wrong WP themes and conflicts with third party plugins.
            ?>
                 <?php $plugin_url = plugins_url('', __FILE__); ?>
                 <!--noptimize-->
                 <link href="<?php echo plugins_url('css/stylepublic.css', __FILE__); ?>" type="text/css" rel="stylesheet" />
                 <link href="<?php echo $this->fixurl($this->get_site_url( false ),'cp_cpappb_resources=css'); ?>" type="text/css" rel="stylesheet" />
                 <link href="<?php echo plugins_url('css/cupertino/calendar.css', __FILE__); ?>" type="text/css" rel="stylesheet" />
                 <script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/jquery.js'; ?>'></script>
                 <script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'core.min.js'; ?>'></script>
                 <script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'datepicker.min.js'; ?>'></script>
<?php if (@file_exists(dirname( __FILE__ ).'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'widget.min.js')) { ?><script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'widget.min.js'; ?>'></script><?php } ?>
<?php if (@file_exists(dirname( __FILE__ ).'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'position.min.js')) { ?><script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'position.min.js'; ?>'></script><?php } ?>
                 <script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'tooltip.min.js'; ?>'></script>
                 <?php if ($calendar_language != '' && file_exists(dirname( __FILE__ ).'/js/languages/jquery.ui.datepicker-'.$calendar_language.'.js')) { ?><script type='text/javascript' src='<?php echo plugins_url('js/languages/jquery.ui.datepicker-'.$calendar_language.'.js', __FILE__); ?>'></script><?php } ?>
                 <script type='text/javascript'>
                 /* <![CDATA[ */
                 var <?php echo $this->prefix; ?>_fbuilder_config<?php echo '_'.$this->print_counter; ?> = {"obj":"{\"pub\":true,\"identifier\":\"<?php echo '_'.$this->print_counter; ?>\",\"messages\": {\n    \t                \t\"required\": \"<?php echo str_replace(array('"'),array('\\"'),$this->get_option('vs_text_is_required', CP_APPBOOK_DEFAULT_vs_text_is_required));?>\",\"maxapp\": \"<?php echo str_replace(array('"'),array('\\"'),$this->get_option('vs_text_maxapp', CP_APPBOOK_DEFAULT_vs_text_maxapp));?>\",\"language\": \"<?php echo str_replace(array('"'),array('\\"'),$calendar_language);?>\",\"date_format\": \"<?php echo str_replace(array('"'),array('\\"'),$this->get_option('date_format', 'mm/dd/yy'));?>\",\n    \t                \t\"email\": \"<?php echo str_replace(array('"'),array('\\"'),$this->get_option('vs_text_is_email', CP_APPBOOK_DEFAULT_vs_text_is_email));?>\",\n    \t                \t\"datemmddyyyy\": \"<?php echo str_replace(array('"'),array('\\"'),$this->get_option('vs_text_datemmddyyyy', CP_APPBOOK_DEFAULT_vs_text_datemmddyyyy));?>\",\n    \t                \t\"dateddmmyyyy\": \"<?php echo str_replace(array('"'),array('\\"'),$this->get_option('vs_text_dateddmmyyyy', CP_APPBOOK_DEFAULT_vs_text_dateddmmyyyy));?>\",\n    \t                \t\"number\": \"<?php echo str_replace(array('"'),array('\\"'),$this->get_option('vs_text_number', CP_APPBOOK_DEFAULT_vs_text_number));?>\",\n    \t                \t\"digits\": \"<?php echo str_replace(array('"'),array('\\"'),$this->get_option('vs_text_digits', CP_APPBOOK_DEFAULT_vs_text_digits));?>\",\n    \t                \t\"max\": \"<?php echo str_replace(array('"'),array('\\"'),$this->get_option('vs_text_max', CP_APPBOOK_DEFAULT_vs_text_max));?>\",\n    \t                \t\"min\": \"<?php echo str_replace(array('"'),array('\\"'),$this->get_option('vs_text_min', CP_APPBOOK_DEFAULT_vs_text_min));?>\",\"previous\": \"<?php echo str_replace(array('"'),array('\\"'),$previous_label); ?>\",\"next\": \"<?php echo str_replace(array('"'),array('\\"'),$next_label); ?>\"\n    \t                }}"};
                 /* ]]> */
                 </script>
                 <script type='text/javascript' src='<?php echo $this->fixurl($this->get_site_url( false ) ,'cp_cpappb_resources=public'); ?>'></script>
                 <script type='text/javascript' src='<?php echo $this->fixurl($this->get_site_url( false ) ,'cp_cpappb_resources=customjs'); ?>'></script>
                 <!--/noptimize-->
            <?php
        }
        // $this->print_counter++;
    }


    /* Code for the admin area */

    public function plugin_page_links($links) {
        $customAdjustments_link = '<a href="https://apphourbooking.dwbooster.com/customization">'.__('Request custom changes').'</a>';
    	array_unshift($links, $customAdjustments_link);
        $settings_link = '<a href="admin.php?page='.$this->menu_parameter.'">'.__('Settings').'</a>';
    	array_unshift($links, $settings_link);
    	$help_link = '<a href="'.$this->plugin_URL.'">'.__('Help').'</a>';
    	array_unshift($links, $help_link);
    	return $links;
    }


    public function admin_menu() {
        add_options_page($this->plugin_name.' '.__( 'Options' ,'appointment-hour-booking'), $this->plugin_name, 'manage_options', $this->menu_parameter, array($this, 'settings_page') );
        add_menu_page( $this->plugin_name.' '.__( 'Options' ,'appointment-hour-booking'), $this->plugin_name, 'read', $this->menu_parameter, array($this, 'settings_page') );
        add_submenu_page( $this->menu_parameter, __('General Settings' ,'appointment-hour-booking'), __('General Settings' ,'appointment-hour-booking'), 'edit_pages', $this->menu_parameter."_settings", array($this, 'settings_page') );
        add_submenu_page( $this->menu_parameter, __('Add Ons' ,'appointment-hour-booking'), __('Add Ons' ,'appointment-hour-booking'), 'edit_pages', $this->menu_parameter."_addons", array($this, 'settings_page') );
        add_submenu_page( $this->menu_parameter, __( 'Online Demo','appointment-hour-booking'), __('Online Demo' ,'appointment-hour-booking'), 'edit_pages', $this->menu_parameter."_odemo", array($this, 'settings_page') );
        add_submenu_page( $this->menu_parameter, __( 'I Need Help','appointment-hour-booking'), __('I Need Help' ,'appointment-hour-booking'), 'edit_pages', $this->menu_parameter."_support", array($this, 'settings_page') );
        add_submenu_page( $this->menu_parameter, __('Upgrade Plugin' ,'appointment-hour-booking'), __('Upgrade Plugin' ,'appointment-hour-booking'), 'edit_pages', $this->menu_parameter."_upgrade", array($this, 'settings_page') );
    }


    function insert_button() {
        global $wpdb;
        $options = '';
        $calendars = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.$this->table_items);
        foreach($calendars as $item)
            $options .= '<option value="'.$item->id.'">'.$item->form_name.'</option>';

        if ( (!defined('ELEMENTOR_MENUS_VERSION') && !defined('ELEMENTOR_PRO_VERSION')) || @$_GET["action"] != 'elementor')
            wp_enqueue_style('wp-jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script( 'cpapphourbk_classic_editor', plugins_url('/js/insertpanel.js', __FILE__));

        $forms = $this->getThisUserForms();

        wp_localize_script( 'cpapphourbk_classic_editor', 'apphourbk_formsclassic', array(
                            'forms' => $forms,
                            'siteUrl' => get_site_url()
                          ) );

        print '<a href="javascript:cpappbk_appointments_fpanel.open()" title="'.__('Insert Appointment Hour Booking','appointment-hour-booking').'"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert  Appointment Hour Booking','appointment-hour-booking').'" /></a>';
    }


    function getThisUserForms()
    {
        global $wpdb;
        $current_user = wp_get_current_user();
        $current_user_access = current_user_can('manage_options');
        $rows = $wpdb->get_results("SELECT id,form_name,cp_user_access FROM ".$wpdb->prefix.$this->table_items." ORDER BY form_name");
        $forms = array();
        if (!$current_user_access)  // if isn't admin and has some form assigned then return only assigned forms
        {
            foreach ($rows as $item)
            {
               if (@in_array($current_user->ID, unserialize($item->cp_user_access)))
                   $forms[] = array (
                                    'value' => $item->id,
                                    'label' => $item->form_name,
                                    );
            }
        }

        if (!count($forms))
            foreach ($rows as $item)
               $forms[] = array (
                                'value' => $item->id,
                                'label' => $item->form_name,
                                );

        return $forms;
    }


    public function settings_page() {
        global $wpdb;
        if ($this->get_param("cal") || $this->get_param("cal") == '0' || $this->get_param("pwizard") == '1')
        {
            $this->item = intval($this->get_param("cal"));
            if (isset($_GET["edit"]) && $_GET["edit"] == '1')
                @include_once dirname( __FILE__ ) . '/cp_admin_int_edition.inc.php';
            else if ($this->get_param("schedule") == '1')
                @include_once dirname( __FILE__ ) . '/cp-admin-int-schedule.inc.php';
            else if ($this->get_param("list") == '1')
                @include_once dirname( __FILE__ ) . '/cp-admin-int-message-list.inc.php';
            else if ($this->get_param("report") == '1')
                @include_once dirname( __FILE__ ) . '/cp-admin-int-report.inc.php';
            else if ($this->get_param("addbk") == '1')
                @include_once dirname( __FILE__ ) . '/cp-admin-int-add-booking.inc.php';
            else if ($this->get_param("blocktimes") == '1')
                @include_once dirname( __FILE__ ) . '/cp-admin-int-block-times.inc.php';
            else if ($this->get_param("pwizard") == '1')
            {
                if ($this->get_param("cal"))
                    $this->item = intval($this->get_param("cal"));
                @include_once dirname( __FILE__ ) . '/cp-publish-wizzard.inc.php';
            }
            else
                @include_once dirname( __FILE__ ) . '/cp-admin-int.inc.php';
        }
        else if ($this->get_param("page") == $this->menu_parameter.'_upgrade')
        {
            echo("Redirecting to upgrade page...<script type='text/javascript'>document.location='".$this->plugin_download_URL."';</script>");
            exit;
        }
        else if ($this->get_param("page") == $this->menu_parameter.'_odemo')
        {
            echo("Redirecting to demo page...<script type='text/javascript'>document.location='https://apphourbooking.dwbooster.com/home#demos';</script>");
            exit;
        }
        else if ($this->get_param("page") == $this->menu_parameter.'_support')
        {
            echo("Redirecting to support page...<script type='text/javascript'>document.location='https://wordpress.org/support/plugin/appointment-hour-booking#new-post';</script>");
            exit;
        }
        else if ($this->get_param("page") == $this->menu_parameter.'_settings')
        {
            @include_once dirname( __FILE__ ) . '/cp-settings.inc.php';
        }
        else if ($this->get_param("page") == $this->menu_parameter.'_addons')
        {
            @include_once dirname( __FILE__ ) . '/cp-addons.inc.php';
        }
        else
            @include_once dirname( __FILE__ ) . '/cp-admin-int-list.inc.php';
    }


    function gutenberg_block() {
        global $wpdb;

        wp_enqueue_script( 'cpapphourbk_gutenberg_editor', plugins_url('/js/block.js', __FILE__));

        wp_enqueue_style('cpapp-calendarstyle', plugins_url('css/cupertino/calendar.css', __FILE__));
        wp_enqueue_style('cpapp-publicstyle', plugins_url('css/stylepublic.css', __FILE__));
        wp_enqueue_style('cpapp-custompublicstyle', $this->fixurl($this->get_site_url( false ),'cp_cpappb_resources=css'));

        wp_enqueue_script( $this->prefix.'_builder_script',
               $this->fixurl($this->get_site_url( false ),'cp_cpappb_resources=public'),array("jquery","jquery-ui-core","jquery-ui-datepicker","jquery-ui-widget","jquery-ui-position","jquery-ui-tooltip"), false, true );

        $forms = $this->getThisUserForms();

        wp_localize_script( 'cpapphourbk_gutenberg_editor', 'apphourbk_forms', array(
                            'forms' => $forms,
                            'siteUrl' => get_site_url()
                          ) );
    }


    public function render_form_admin ($atts) {
        $is_gutemberg_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];
        if (!$is_gutemberg_editor)
            return $this->filter_content (array('id' => $atts["formId"]));
        else if ($atts["formId"])
        {
            $this->setId($atts["formId"]);
            return '<input type="hidden" name="form_structure'.$atts["instanceId"].'" id="form_structure'.$atts["instanceId"].'" value="'.str_replace("\r","",str_replace("\n","",esc_attr($this->get_option('form_structure')))).'" /><fieldset class="ahbgutenberg_editor" disabled><div id="fbuilder"><div id="fbuilder_'.$atts["instanceId"].'"><div id="formheader_'.$atts["instanceId"].'"></div><div id="fieldlist_'.$atts["instanceId"].'"></div></div></div></fieldset>';
        }
        else
            return '';
    }


    function insert_adminScripts($hook) {
        if ($this->get_param("page") == $this->menu_parameter && $this->get_param("blocktimes") != '1' && $this->get_param("addbk") != '1')
        {
            wp_deregister_script( 'bootstrap-datepicker-js' );
            wp_register_script('bootstrap-datepicker-js', plugins_url('/js/nope.js', __FILE__));
            wp_deregister_script( 'wpsp_wp_admin_jquery7' );
            wp_register_script('wpsp_wp_admin_jquery7', plugins_url('/js/nope.js', __FILE__));

            wp_enqueue_script( $this->prefix.'_builder_script', $this->get_site_url( true ).'?cp_cpappb_resources=admin',array("jquery","jquery-ui-core","jquery-ui-sortable","jquery-ui-tabs","jquery-ui-droppable","jquery-ui-button","jquery-ui-datepicker") );

            if (isset($_GET["calendarview"]) && $_GET["calendarview"] == '1')
                wp_enqueue_script( 'jquery-ui-dialog' );

            wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
            wp_enqueue_style('cpapp-style', plugins_url('/css/style.css', __FILE__));
            wp_enqueue_style('cpapp-newadminstyle', plugins_url('/css/newadminlayout.css', __FILE__));
            $calendar_language = $this->get_option('calendar_language','');
            if ($calendar_language == '') $calendar_language = $this->autodetect_language();
            if ($calendar_language != '')
                wp_enqueue_script($this->prefix.'_language_file', plugins_url('js/languages/jquery.ui.datepicker-'.$calendar_language.'.js', __FILE__), array("jquery","jquery-ui-core","jquery-ui-sortable","jquery-ui-tabs","jquery-ui-droppable","jquery-ui-button","jquery-ui-datepicker"));
        }
        if( 'post.php' != $hook  && 'post-new.php' != $hook )
            return;
        // space to include some script in the post or page areas if needed
    }


    function autodetect_language() {
        $basename = '/js/languages/jquery.ui.datepicker-';

        $options = array (get_bloginfo('language'),
                          strtolower(get_bloginfo('language')),
                          substr(strtolower(get_bloginfo('language')),0,2)."-".substr(strtoupper(get_bloginfo('language')),strlen(strtoupper(get_bloginfo('language')))-2,2),
                          substr(strtolower(get_bloginfo('language')),0,2),
                          substr(strtolower(get_bloginfo('language')),strlen(strtolower(get_bloginfo('language')))-2,2)
                          );
        foreach ($options as $option)
        {
            if (file_exists(dirname( __FILE__ ).$basename.$option.'.js'))
                return $option;
            $option = str_replace ("-","_", $option);
            if (file_exists(dirname( __FILE__ ).$basename.$option.'.js'))
                return $option;
        }
        return '';
    }


   public function data_management_pluginsloaded ()
   {
       if (isset($_GET["cp_cpappb_resources"]))
       {
           add_filter( 'trp_allow_tp_to_run', 'apphourbk_tp_disable_filter' );
       }
   }


   function data_management_loaded() {
        global $wpdb;

        $action = $this->get_param('cp_apphourbooking_do_action_loaded');
    	if (!$action) return; // go out if the call isn't for this one

        if ($this->get_param('cpapphourbk_id')) $this->item = intval($this->get_param('cpapphourbk_id'));

        if ($action == "wizard" && current_user_can('manage_options'))
        {
            $this->verify_nonce ($_POST["anonce"], 'cpappb_actions_pwizard');
            $shortcode = '[CP_APP_HOUR_BOOKING  id="'.$this->item .'"]';
            $this->postURL = $this->publish_on($this->sanitize(@$_POST["whereto"]), $this->sanitize(@$_POST["publishpage"]), $this->sanitize(@$_POST["publishpost"]), $shortcode, $this->sanitize(@$_POST["posttitle"]));
            return;
        }

        // ...
        echo 'Some unexpected error happened. If you see this error contact the support service at https://apphourbooking.dwbooster.com/contact-us';

        exit();
    }


    private function publish_on($whereto, $publishpage = '', $publishpost = '', $content = '', $posttitle = 'Booking Form')
    {
        global $wpdb;
        $id = '';
        if ($whereto == '0' || $whereto =='1') // new page
        {
            $my_post = array(
              'post_title'    => $posttitle,
              'post_type' => ($whereto == '0'?'page':'post'),
              'post_content'  => 'This is a <b>preview</b> page, remember to publish it if needed. You can edit the full calendar and form settings into the admin settings page.<br /><br /> '.$content,
              'post_status'   => 'draft'
            );

            // Insert the post into the database
            $id = wp_insert_post( $my_post );
        }
        else
        {
            $id = ($whereto == '2'?$publishpage:$publishpost);
            $post = get_post( $id );
            $pos = strpos($post->post_content,$content);
            if ($pos === false)
            {
                $my_post = array(
                      'ID'           => $id,
                      'post_content' => $content.$post->post_content,
                  );
                // Update the post into the database
                wp_update_post( $my_post );
            }
        }
        return get_permalink($id);
    }


    function print_multiview_format($data)
    {
        // $data[$k]["d"] - date
        // $data[$k]["h1"] - hour
        // $data[$k]["m1"] - minute
        // $data[$k]["h2"] - hour end
        // $data[$k]["m2"] - minute end
        // $data[$k]["info"] - description
        // echo '<pre>';print_r($data);

        function _js2PhpTime($jsdate){
          if(preg_match('@(\d+)/(\d+)/(\d+)\s+(\d+):(\d+)((am|pm)*)@', $jsdate, $matches)==1){
            if ($matches[6]=="pm")
                if ($matches[4]<12)
                    $matches[4] += 12;
            $ret = mktime($matches[4], $matches[5], 0, $matches[1], $matches[2], $matches[3]);
          }else if(preg_match('@(\d+)/(\d+)/(\d+)@', $jsdate, $matches)==1){
            $ret = mktime(0, 0, 0, $matches[1], $matches[2], $matches[3]);
          }
          return $ret;
        }


        function _php2MySqlTime($phpDate){
            return date("Y-m-d H:i:s", $phpDate);
        }


        function _php2JsTime($phpDate){
            return @date("m/d/Y H:i", $phpDate);
        }


        function _mySql2PhpTime($sqlDate){
            $a1 = explode (" ",$sqlDate);
            $a2 = explode ("-",$a1[0]);
            $a3 = explode (":",$a1[1]);
            $t = mktime(@$a3[0],@$a3[1],@$a3[2],@$a2[1],@$a2[2],@$a2[0]);
            return $t;
        }

        usort($data, array($this, 'wptsbk_custom_sort') );

        $ret = array();
        $ret['events'] = array();
        $ret["issort"] = true;
        $ret['error'] = null;
        $d1 = _js2PhpTime($_POST["startdate"]);
        $d2 = _js2PhpTime($_POST["enddate"]);
        $d1 = mktime(0, 0, 0,  date("m", $d1), date("d", $d1), date("Y", $d1));
        $d2 = mktime(0, 0, 0, date("m", $d2), date("d", $d2), date("Y", $d2))+24*60*60-1;
        $ret["start"] = _php2JsTime($d1);
        $ret["end"] = _php2JsTime($d2);

        $aid = 1;
        foreach ($data as $item)
        {
            $datetime = $item["d"]." ".$item["h1"].":".($item["m1"]<10?"0":"").$item["m1"];
            $datetime2 = $item["d"]." ".$item["h2"].":".($item["m2"]<10?"0":"").$item["m2"];
            $ev = array(
                $aid++,//mt_rand(1,9999999), //$row["id"],
                $item["e"],
                _php2JsTime(_mySql2PhpTime($datetime)),
                _php2JsTime(_mySql2PhpTime($datetime2)),
                0, // is  all day event?
                0, // more than one day event
                '',//Recurring event rule,
                '#3CF',
                0,//editable
                '',
                '',//$attends
                $item["info"],
                '',
                1
            );
            $ret['events'][] = $ev;
        }
        echo json_encode($ret);
        exit;
    }


    public function wptsbk_custom_sort($a,$b) {
          return ($a['d']>$b['d']) ||
                 ($a['d']==$b['d'] && $a['h1']>$b['h1']) ||
                 ($a['d']==$b['d'] && $a['h1']==$b['h1'] && $a['m1']>$b['m1']);
    }


    function check_current_user_access($calid = '')
    {
        $current_user = wp_get_current_user();
        $current_user_access = current_user_can('manage_options');
        $saved_id = $this->item;
        $this->setID($calid);
        $result = ($current_user_access || @in_array($current_user->ID, unserialize($this->get_option("cp_user_access",""))));
        $this->setID($saved_id);
        return $result;
    }


    function data_management() {
        global $wpdb;


        load_plugin_textdomain( 'appointment-hour-booking', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        if(!empty($_REQUEST['cp_app_action']))
        {
            $formid = intval($_REQUEST['formid']);
            $field = @$_REQUEST['formfield'];

            if ($_REQUEST['cp_app_action'] == 'mv')
            {
                $t_content_admin = get_option('cp_cpappb_schcalcontent_admin',CP_CPAPPB_SCHCALCONTENT_ADMIN);
                $t_title_admin = get_option('cp_cpappb_schcaltitle_admin',CP_CPAPPB_SCHCALTITLE_ADMIN);
                $t_content_public = get_option('cp_cpappb_schcalcontent_public',CP_CPAPPB_SCHCALCONTENT_PUBLIC);
                $t_title_public = get_option('cp_cpappb_schcaltitle_public',CP_CPAPPB_SCHCALTITLE_PUBLIC);
                $t_content_admin = apply_filters( 'cpappb_mv_content_admin_filter', $t_content_admin,  intval($formid) );
                $t_title_admin = apply_filters( 'cpappb_mv_title_admin_filter', $t_title_admin,  intval($formid) );
                $t_content_public = apply_filters( 'cpappb_mv_content_public_filter', $t_content_public,  intval($formid) );
                $t_title_public = apply_filters( 'cpappb_mv_title_public_filter', $t_title_public,  intval($formid) );
            }

            $myrows = $wpdb->get_results( $wpdb->prepare("SELECT data,notifyto,posted_data FROM ".$wpdb->prefix.$this->table_messages." where formid=%d". ($_REQUEST['cp_app_action'] != 'mv' || get_option('cp_cpappb_sch_admin_blockedt','') == 'Yes'?"":" AND notifyto<>'".esc_sql($this->blocked_by_admin_indicator)."'"), $formid) );
            $tmp2 = array();

            $excluded_multiview = ',,'.get_option('cp_cpappb_schcalcontent_exclude','').','; // fields excluded form multi view calendar

            for ($i=0; $i < count($myrows); $i++)
            {
                $data = unserialize($myrows[$i]->posted_data);
                for($k=0; $k<count($data["apps"]); $k++)
                    if ( (!isset($data["apps"][$k]["cancelled"]) || $data["apps"][$k]["cancelled"] == '' || $data["apps"][$k]["cancelled"] == 'Attended') &&
                        ( !isset($data["apps"][$k]["field"]) || @$data["apps"][$k]["field"] == $field || $field == '') &&
                        (($_REQUEST['cp_app_action'] != 'mv') || !strpos($excluded_multiview, ','.$data["apps"][$k]["field"].','))
                       )
                    {
                        $slot = $data["apps"][$k]["slot"];
                        $quantity = intval(@$data["apps"][$k]["quant"]);
                        if (!$quantity) $quantity = 1;

                        $availitem = array("d"=>$data["apps"][$k]["date"] ,"h1"=>intval(substr($slot,0,2)),"m1"=>intval(substr($slot,3,2)),"h2"=>intval(substr($slot,6,2)),"m2"=>intval(substr($slot,9,2)),"serviceindex"=>intval(@$data["apps"][$k]["serviceindex"]),"quantity"=>$quantity);

                        if (isset($data["apps"][$k]["sid"]) && $data["apps"][$k]["sid"] != '')
                            $availitem["sid"] = intval(@$data["apps"][$k]["sid"]);

                        $tmp2[] = $availitem;

                        if ($_REQUEST['cp_app_action'] == 'mv' && $this->check_current_user_access($formid))
                        {
                            $data["INFO"] = $myrows[$i]->data;
                            $tmp2[count($tmp2)-1]["info"] = $this->replace_tags($t_content_admin, $data, false, $k);  //   $myrows[$i]->data;
                            $tmp2[count($tmp2)-1]["e"] = $this->replace_tags($t_title_admin, $data, false, $k);  // sanitize_email($myrows[$i]->notifyto);
                        }
                    }
            }
            if ($_REQUEST['cp_app_action'] == 'mv' && is_admin())
            {
                $this->print_multiview_format($tmp2);
            }
            else
                echo json_encode($tmp2); //{type:"all",d:"",h1:8,m1:0,h2:17,m2:0}
		    exit;
        }

    	if( isset( $_REQUEST[ 'cp_cpappb_resources' ] ) )
    	{
    		if( $_REQUEST[ 'cp_cpappb_resources' ] == 'admin' )
    		{
    			require_once dirname( __FILE__ ).'/js/fbuilder-loader-admin.php';
    		}
            else if( $_REQUEST[ 'cp_cpappb_resources' ] == 'css' )
    		{
                header("Content-type: text/css");
    			$custom_styles = base64_decode(get_option('CP_AHB_CSS', ''));
                echo $custom_styles;
    		}
            else if( $_REQUEST[ 'cp_cpappb_resources' ] == 'customjs' )
    		{
                header("Content-type: application/javascript");
    			$custom_scripts = base64_decode(get_option('CP_AHB_JS', ''));
                echo $custom_scripts;
    		}
    		else
    		{
    			require_once dirname( __FILE__ ).'/js/fbuilder-loader-public.php';
    		}
    		exit;
    	}

        $this->check_reports();

        if ($this->get_param($this->prefix.'_captcha') == 'captcha' )
        {
            @include_once dirname( __FILE__ ) . '/captcha/captcha.php';
            exit;
        }

        if ($this->get_param($this->prefix.'_csv1') && is_admin() && $this->check_current_user_access(intval($this->get_param("cal"))))
        {
            $this->export_csv();
            return;
        }

        if ($this->get_param($this->prefix.'_csv2') && is_admin() && $this->check_current_user_access(intval($this->get_param("cal"))))
        {
            $this->export_csv_schedule(array());
            return;
        }

        if ( $this->get_param($this->prefix.'_post_options') && is_admin() && $this->check_current_user_access(intval($this->get_param($this->prefix.'_id'))))
        {
            $this->save_options();
            return;
        }

        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['CP_AHB_post_edition'] ) && current_user_can('edit_pages') && is_admin() )
        {
            $this->save_edition();
            return;
        }

    	if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST[$this->prefix.'_pform_process'] ) )
    	    if ( 'GET' != $_SERVER['REQUEST_METHOD'] || !isset( $_GET['hdcaptcha_'.$this->prefix.'_post'] ) )
    		    return;

        if ($this->get_param($this->prefix.'_id')) $this->item = intval($this->get_param($this->prefix.'_id'));

        @session_start();
        if (isset($_GET["ps"])) $sequence = $_GET["ps"]; else if (isset($_POST["cp_pform_psequence"])) $sequence = $_POST["cp_pform_psequence"];
        if (
               !apply_filters( 'cpappb_valid_submission', true) ||
               (
                   (!is_admin() && $this->get_option('cv_enable_captcha', CP_APPBOOK_DEFAULT_cv_enable_captcha) != 'false') &&
                   ( (strtolower($this->get_param('hdcaptcha_'.$this->prefix.'_post')) != strtolower($_SESSION['rand_code'.$sequence])) ||
                     ($_SESSION['rand_code'.$sequence] == '')
                   )
                   &&
                   ( (md5(strtolower($this->get_param('hdcaptcha_'.$this->prefix.'_post'))) != ($_COOKIE['rand_code'.$sequence])) ||
                     ($_COOKIE['rand_code'.$sequence] == '')
                   )
               )
           )
        {
            echo 'captchafailed';
            exit;
        }

    	// if this isn't the real post (it was the captcha verification) then echo ok and exit
        if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST[$this->prefix.'_pform_process'] ) )
    	{
    	    echo 'ok';
            exit;
    	}

        $posted_items = array();
        foreach ($_POST as $item => $value)
            $posted_items[sanitize_key($item)] = $this->sanitize( is_array($value) ? $value : stripcslashes($value) );

        if (isset($_GET["blocktimes"]) && is_admin() && current_user_can('edit_posts'))
            define('CPAPPHOURBK_BLOCK_TIMES_PROCESS', true);

        // get form info
        //---------------------------
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        $form_data = json_decode($this->cleanJSON($this->get_option('form_structure', CP_APPBOOK_DEFAULT_form_structure)));
        $fields = array();

        $apps = $this->extract_appointments($form_data[0], $posted_items, $sequence);

        $honeypotcheck = sanitize_key(get_option('cp_cpappb_honeypot',''));
        if ($honeypotcheck != '' && (isset($_POST[$honeypotcheck]) && $_POST[$honeypotcheck] != ''))
    	{
    	    echo 'Blocked by anti-spam rule. Please contact our support service if you this this is an error.';
            exit;
    	}

        $price = $this->extract_total_price ($apps);
        $apptext = $this->get_appointments_text ($apps);
        $excluded_items = array();

        foreach ($form_data[0] as $item)
            if ($item->ftype != 'fapp' && !defined('CPAPPHOURBK_BLOCK_TIMES_PROCESS'))
            {
                $fields[$item->name] = $item->title;
                if ($item->ftype == 'fPhone') // join fields for phone fields
                {
                    $posted_items[$item->name.$sequence] = '';
                    for($i=0; $i<=substr_count($item->dformat," "); $i++)
                    {
                        $posted_items[$item->name.$sequence] .= ($posted_items[$item->name.$sequence."_".$i]!=''?($i==0?'':'-').$posted_items[$item->name.$sequence."_".$i]:'');
                        unset($posted_items[$item->name.$sequence."_".$i]);
                    }
                }
            }
            else
            {
                $fields[$item->name] = $item->title;
                $posted_items[$item->name.$sequence] = $apptext;
                $excluded_items[] = $item->name;
            }



        // grab posted data
        //---------------------------
        $buffer = __('Appointments','appointment-hour-booking').":\n".$apptext."\n";

        $founddata = false;
        $params = array();
        $params["final_price"] = $price;
        $params["final_price_short"] = number_format($price,0);
        $params["request_timestamp"] = $this->format_date(date("Y-m-d", current_time('timestamp'))). " ". (date("H:i:s", current_time('timestamp')));
        $params["apps"] = $apps;
        foreach ($apps as $appitem)
        {
           $params["app_service_".$appitem["id"]] = $appitem["service"];
           $params["app_status_".$appitem["id"]] = $appitem["cancelled"];
           $params["app_duration_".$appitem["id"]] = $appitem["duration"];
           $params["app_price_".$appitem["id"]] = $appitem["price"];
           $params["app_date_".$appitem["id"]] = $this->format_date($appitem["date"]);
           $params["app_slot_".$appitem["id"]] = $appitem["slot"];
           $slotpieces = explode("/",$appitem["slot"]);
           $params["app_starttime_".$appitem["id"]] = $this->format12hours(trim(@$slotpieces[0]), @$appitem["military"] == 0);
           $params["app_endtime_".$appitem["id"]] = $this->format12hours(trim(@$slotpieces[1]), @$appitem["military"] == 0);
           $params["app_quantity_".$appitem["id"]] = $appitem["quant"];
           $founddata = true;
        }

        $params["formid"] = $this->item;
        $params["formname"] = $this->get_option('form_name');
        $params["referrer"] = $posted_items["refpage".$sequence];
        foreach ($posted_items as $item => $value)
            if (isset($fields[str_replace($sequence,'',$item)]))
            {
                if (is_array($value))
                {
                    for ($iv=0; $iv<count($value); $iv++)
                        $value[$iv] = str_replace(CP_APPBOOK_REP_ARR, "@", $value[$iv]);
                }
                else
                    $value = str_replace(CP_APPBOOK_REP_ARR, "@", $value);
                if (!in_array(str_replace($sequence,'',$item), $excluded_items))
                    $buffer .= ($fields[str_replace($sequence,'',$item)]?$fields[str_replace($sequence,'',$item)] . ": ":""). (is_array($value)?($this->recursive_implode(", ",$value)):($value)) . "\n\n";
                $params[str_replace($sequence,'',$item)] = $value;
                $founddata = true;
            }

        foreach ($_FILES as $item => $value)
        {
            $item = str_replace( $sequence,'',$item );
	    	if ( isset( $fields[ $item ] ) )
            {
	    		$files_names_arr = array();
	    		$files_links_arr = array();
	    		$files_urls_arr  = array();
	    		for( $f = 0; $f < count( $value[ 'name' ] ); $f++ )
	    		{
	    			if( !empty( $value[ 'name' ][ $f ] ) )
	    			{
	    				$uploaded_file = array(
	    					'name' 		=> $value[ 'name' ][ $f ],
	    					'type' 		=> $value[ 'type' ][ $f ],
	    					'tmp_name' 	=> $value[ 'tmp_name' ][ $f ],
	    					'error' 	=> $value[ 'error' ][ $f ],
	    					'size' 		=> $value[ 'size' ][ $f ],
	    				);
	    				$movefile = wp_handle_upload( $uploaded_file, array( 'test_form' => false ) );
	    				if ( empty( $movefile[ 'error' ] ) )
	    				{
	    					$files_links_arr[] = $params[ $item."_link" ][ $f ] = $movefile["file"];
	    					$files_urls_arr[]  = $params[ $item."_url" ][ $f ] = $movefile["url"];
	    					$files_names_arr[] = $uploaded_file[ 'name' ];

						    /**
						     * Action called when the file is uploaded, the file's data is passed as parameter
						     */
						    do_action( 'cpappb_file_uploaded', $movefile );
	    				} //else echo $movefile[ 'error' ];
	    			}
	    		}
	    		$joinned_files_names = implode( ", ", $files_names_arr );
	    		$buffer .= $fields[ $item ] . ": ". $joinned_files_names . "\n\n";
	    		$params[ $item ] = $joinned_files_names;
                $founddata = true;
	    		//$params[ $item."_links"] = implode( ",",  $files_links_arr );
	    		//$params[ $item."_urls"] = implode( ",",  $files_urls_arr );
	    	}
	    }

        $buffer_A = (defined('CPAPPHOURBK_BLOCK_TIMES_PROCESS')?__('BLOCKED BY ADMIN','appointment-hour-booking')."\n\n":'') . $buffer;

        if ($this->booking_form_nonce || is_admin())
            $this->verify_nonce ($posted_items["anonce"], 'cpappb_actions_bookingform');

	    /**
	     * Action called before insert the data into database.
	     * To the function is passed an array with submitted data.
	     */
	    do_action( 'cpappb_process_data_before_insert', $params );

        if (!$founddata)
        {
            echo 'Empty post! No data received.';
            exit;
        }

        // insert into database
        //---------------------------
        $current_user = wp_get_current_user();
        $this->add_field_verify($wpdb->prefix.$this->table_messages, "whoadded");

        $to = $this->get_option('cu_user_email_field', CP_APPBOOK_DEFAULT_cu_user_email_field);
        $sanitized_email = sanitize_email( ( !empty($posted_items[$to.$sequence]) ? $posted_items[$to.$sequence] : '' ) );
        if (defined('CPAPPHOURBK_BLOCK_TIMES_PROCESS'))
            $sanitized_email = $this->blocked_by_admin_indicator;
        $rows_affected = $wpdb->insert( $wpdb->prefix.$this->table_messages, array( 'formid' => $this->item,
                                                                                    'time' => current_time('mysql'),
                                                                                    'ipaddr' => (get_option('cp_cpappb_storeip', CP_APPBOOK_DEFAULT_track_IP)?$_SERVER['REMOTE_ADDR']:''),
                                                                                    'notifyto' => $sanitized_email,
                                                                                    'posted_data' => serialize($params),
                                                                                    'data' =>$buffer_A,
                                                                                    'whoadded' => "".$current_user->ID
                                                                                   ) );
        if (!$rows_affected)
        {
            echo 'Error saving data! Please try again.';
            exit;
        }

        // $myrows = $wpdb->get_results( "SELECT MAX(id) as max_id FROM ".$wpdb->prefix.$this->table_messages );
        $item_number = $wpdb->insert_id; // $myrows[0]->max_id;

	    // Call action for data processing
	    //---------------------------------
	    $params[ 'itemnumber' ] = $item_number;

	    /**
	     * Action called after inserted the data into database.
	     * To the function is passed an array with submitted data.
	     */
	    do_action_ref_array( 'cpappb_process_data', array(&$params) );

        $wpdb->update( $wpdb->prefix.$this->table_messages,
                       array( 'posted_data' => serialize($params) ),
                       array ( 'id' => $item_number),
                       array( '%s' ),
	                   array( '%d' )
                       );

        $this->ready_to_go_reservation($item_number, "");
        $_SESSION[ 'cp_cff_form_data' ] = $item_number;

        if (is_admin())
            return;

        /**
		 * Filters applied to decide if the website should be redirected to the thank you page after submit the form,
		 * pass a boolean as parameter and returns a boolean
		 */
		$redirect = true;
        $redirect = apply_filters( 'cpappb_redirect', $redirect );

        if( $redirect )
        {
            header("Location: ". $this->replace_tags($this->translate_permalink($this->get_option('fp_return_page', CP_APPBOOK_DEFAULT_fp_return_page)), $params, true));
            exit();
        }
    }


    public function replace_tags ($message, $params, $urlencode = false, $slotindex = '')
    {
        if ($slotindex !== '' && isset($params["apps"][$slotindex]))
        {
            $slotindex++;
            $params["app_service"] = $params["app_service_".$slotindex];
            $params["app_status"] = $params["app_status_".$slotindex];
            $params["app_duration"] = $params["app_duration_".$slotindex];
            $params["app_date"] = $params["app_date_".$slotindex];
            $params["app_slot"] = $params["app_slot_".$slotindex];
            $params["app_starttime"] = $params["app_starttime_".$slotindex];
            $params["app_endtime"] = $params["app_endtime_".$slotindex];
            $params["app_quantity"] = $params["app_quantity_".$slotindex];
        }

        foreach ($params as $item => $value)
        {
            if ($urlencode)
                $value = urlencode( (is_array($value)?'':$value) );
            $message = @str_replace('<'.'%'.$item.'%'.'>',(is_array($value)?($this->recursive_implode(", ",$value)):($value)),$message);
            $message = @str_replace('%'.$item.'%',(is_array($value)?($this->recursive_implode(", ",$value)):($value)),$message);
        }

        for ($i=0;$i<500;$i++)
        {
            $message = str_replace('<'.'%fieldname'.$i.'%'.'>',"",$message);
            $message = str_replace('%fieldname'.$i.'%',"",$message);
            $message = str_replace('<'.'%fieldname'.$i.'_block%'.'>',"",$message);
            $message = str_replace('%fieldname'.$i.'_endblock%',"",$message);
            $message = str_replace('%app_date_'.$i.'%',"",$message);
            $message = str_replace('%app_slot_'.$i.'%',"",$message);
            $message = str_replace('%app_starttime_'.$i.'%',"",$message);
            $message = str_replace('%app_endtime_'.$i.'%',"",$message);
            $message = str_replace('%app_service_'.$i.'%',"",$message);
            $message = str_replace('%app_status_'.$i.'%',"",$message);
            $message = str_replace('%app_duration_'.$i.'%',"",$message);
            $message = str_replace('%app_price_'.$i.'%',"",$message);
            $message = str_replace('%app_quantity_'.$i.'%',"",$message);
        }

        // Remove empty blocks
	    while( preg_match( "/%\s*(.+)_block\s*%/", $message, $matches ) )
	    {
	    	if( empty( $params[ ''.$matches[ 1 ] ] ) )
	    	{
	    		$from = strpos( $message, $matches[ 0 ] );
	    		if( preg_match( "/%\s*(".$matches[ 1 ].")_endblock\s*%/", $message, $matches_end ) )
	    		{
	    			$lenght = strpos( $message, $matches_end[ 0 ] ) + strlen( $matches_end[ 0 ] ) - $from;
	    		}
	    		else
	    		{
	    			$lenght = strlen( $matches[ 0 ] );
	    		}
	    		$message = substr_replace( $message, '', $from, $lenght );
	    	}
	    	else
	    	{
	    		$message = preg_replace( array( "/%\s*".$matches[ 1 ]."_block\s*%/", "/%\s*".$matches[ 1 ]."_endblock\s*%/"), "", $message );
	    	}
	    }

        $message = str_replace('<'.'%email'.'%'.'>',"",$message);
        $message = str_replace('%email'.'%',"",$message);
        return $message;
    }


    public function extract_appointments($form,$data,$sequence)
    {
        $apps = array();
        $subid = 0;
        if (is_admin() || (isset($_POST["bccf_payment_option_paypal"]) && $_POST["bccf_payment_option_paypal"] == '0') || defined('CPAPPHOURBK_BLOCK_TIMES_PROCESS'))
            $status = $this->get_option('defaultpaidstatus', '');
        else
            $status = $this->get_option('defaultstatus', '');
        if (defined('CPAPPHOURBK_BLOCK_TIMES_PROCESS')) $status = '';
        foreach($form as $field)
            if ($field->ftype == 'fapp' && @$data[$field->name.$sequence] != '')
            {
                $apps_text = explode(';',$data[$field->name.$sequence]);
                $fieldtotalcost = 0;
                $fieldpostedcost = floatval(@$data["tcost".$field->name.$sequence]);
                foreach($apps_text as $app_item_text)
                {
                    $item_split = explode(' ',$app_item_text);
                    $subid++;
                    $fieldtotalcost += $field->services[ $item_split[2] ]->price;
                    $apps[] = array (
                                     'id' => $subid,
                                     'cancelled' => $status,
                                     'serviceindex' => $item_split[2],
                                     'service' => $field->services[ $item_split[2] ]->name,
                                     'duration' => $field->services[ $item_split[2] ]->duration,
                                     'price' => 0, //$field->services[ $item_split[2] ]->price,
                                     'date' => $item_split[0],
                                     'slot' => $item_split[1],
                                     'military' => @$field->militaryTime,
                                     'field' => $field->name,
                                     'quant' => $item_split[3],
                                     'sid' => (isset($field->services[ $item_split[2] ]->idx)?$field->services[ $item_split[2] ]->idx:'')  // service ID
                                     );
                }
                //if ($fieldtotalcost < $fieldpostedcost)      // this is to support javascript price calculations
                    $apps[count($apps)-1]["price"] = $fieldpostedcost;
            }
        return $apps;
    }


    function extract_total_price($apps)
    {
        $price = 0;
        foreach($apps as $app)
            $price += floatval($app["price"]);
        return number_format($price,2,'.','');
    }

    function get_appointments_text($apps)
    {
        $option = $this->get_option('display_emails_endtime', '');
        $text = '';
        foreach($apps as $app)
        {
            $slot = $app["slot"];
            if ($option != '')
                $slot = substr ($slot, 0, strpos($slot,"/"));
            $slot = str_replace("/","-",$app["slot"]);
            if (!isset($app["military"]) ||@$app["military"] == 0)
            {
                $times = explode("-",$slot);
                $times[0] = explode(":",$times[0]);
                $times[1] = explode(":",$times[1]);
                if ($option != '')
                    $slot = ($times[0][0]>12?$times[0][0]-12:$times[0][0]).":".$times[0][1].' '.($times[0][0]>=12?'PM':'AM');
                else
                    $slot = ($times[0][0]>12?$times[0][0]-12:$times[0][0]).":".$times[0][1].' '.($times[0][0]>=12?'PM':'AM') ." - ".
                            ($times[1][0]>12?$times[1][0]-12:$times[1][0]).":".$times[1][1].' '.($times[1][0]>=12?'PM':'AM');

            }
            $text .= " - ".$this->format_date($app["date"])." ".$slot.(isset($app["quant"]) && $app["quant"]>1?' ('.$app["quant"].')':'')." (".$app["service"].")\n";
        }
        return $text;
    }


    function format12hours($time, $is_non_military)
    {
        if ($is_non_military)
        {
            $times = explode(":",$time);
            $time = ($times[0]>12?$times[0]-12:$times[0]).":".$times[1].' '.($times[0]>=12?'PM':'AM');
        }
        return $time;
    }


    function format_date($date)
    {
        $format = $this->get_option('date_format', 'mm/dd/yy');
        if (!$format) $format = 'mm/dd/yy';
        $format = str_replace('mm', 'm', $format);
        $format = str_replace('dd', 'd', $format);
        $format = str_replace('yy', 'Y', $format);
        $format = str_replace('DD', 'K', $format);
        $format = str_replace('MM', 'Q', $format);

        $dconv = date($format, strtotime($date));

        $dconv = str_replace('K', ucfirst (__(date('l', strtotime($date)))), $dconv);
        $dconv = str_replace('Q', ucfirst (__(date('F', strtotime($date)))), $dconv);

        return $dconv;
    }

    function ready_to_go_reservation($itemnumber, $payer_email = "")
    {

        global $wpdb;

        $myrows = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix.$this->table_messages." WHERE id=%d", $itemnumber) );
        $params = unserialize($myrows[0]->posted_data);
        $mycalendarrows = $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.$this->table_items.' WHERE `id`=%d', $myrows[0]->formid) );

        $this->item = intval($myrows[0]->formid);

        $buffer_A = $myrows[0]->data;
        $buffer = $buffer_A;

        if ('true' == $this->get_option('fp_inc_additional_info', CP_APPBOOK_DEFAULT_fp_inc_additional_info))
        {
            $buffer .="ADDITIONAL INFORMATION\n"
                  ."*********************************\n";

            $basic_data = "IP: ".$myrows[0]->ipaddr."\n"
              ."Server Time:  ".date("Y-m-d H:i:s")."\n";

		    /**
		     *	Includes additional information to the email's message,
		     *  are passed two parameters: the basic information, and the IP address
		     */
		    $basic_data = apply_filters( 'cpappb_additional_information',  $basic_data, $myrows[0]->ipaddr );
		    $params["additional"] = $basic_data;
		    $buffer .= $basic_data;
        }

        // 1- Send email
        //---------------------------
        $attachments = array();

        $message = str_replace('<'.'%', '%', __($this->get_option('fp_message', CP_APPBOOK_DEFAULT_fp_message) , 'appointment-hour-booking'));
        $message = str_replace('%'.'>', '%', $message);
        $subject = str_replace('<'.'%', '%', __($this->get_option('fp_subject', CP_APPBOOK_DEFAULT_fp_subject) , 'appointment-hour-booking'));
        $subject = str_replace('%'.'>', '%', $subject);

        if ('html' == $this->get_option('fp_emailformat', CP_APPBOOK_DEFAULT_email_format))
            $message = str_replace('%INFO%',str_replace("\n","<br />",str_replace('<','&lt;',$buffer)),$message);
        else
            $message = str_replace('%INFO%',$buffer,$message);
        $subject = $this->get_option('fp_subject', CP_APPBOOK_DEFAULT_fp_subject);

        /**
		 *	Attach or modify attached files,
		 *  Example for adding ical or PDF attachments
		 */
		$attachments = apply_filters( 'cpappb_email_attachments',  $attachments, $params, $this->item);

        $params["apps"] = $this->get_appointments_text($params["apps"]);
        foreach ($params as $item => $value)
        {
            $message = str_replace('%'.$item.'%',(is_array($value)?($this->recursive_implode(", ",$value)):($value)),$message);
            $subject = str_replace('%'.$item.'%',(is_array($value)?($this->recursive_implode(", ",$value)):($value)),$subject);
            if (strpos($item,"_link"))
            {
                foreach ($value as $filevalue)
                    $attachments[] = $filevalue;
            }
        }

        $message = str_replace('%itemnumber%',$itemnumber,$message);
        $subject = str_replace('%itemnumber%',$itemnumber,$subject);

        $from = $this->get_option('fp_from_email', (defined('CP_APPBOOK_DEFAULT_fp_from_email')?CP_APPBOOK_DEFAULT_fp_from_email:''));
        $to = explode(",",$this->get_option('fp_destination_emails', (defined('CP_APPBOOK_DEFAULT_fp_destination_emails')?CP_APPBOOK_DEFAULT_fp_destination_emails:'')));
        if ('html' == $this->get_option('fp_emailformat', (defined('CP_APPBOOK_DEFAULT_email_format')?CP_APPBOOK_DEFAULT_email_format:'text'))) $content_type = "Content-Type: text/html; charset=utf-8\n"; else $content_type = "Content-Type: text/plain; charset=utf-8\n";

        $replyto = sanitize_email($myrows[0]->notifyto);
        if ($this->get_option('fp_emailfrommethod', "fixed") == "customer")
            $from_1 = $replyto;
        else
            $from_1 = $from;

        if ($this->get_option('fp_emailtomethod', "fixed") == 'customer')
        {
            $text_addr = $params[$this->get_option('fp_destination_emails_field', "fixed")];
            if (is_array($text_addr))
                $text_addr = implode(", ",$text_addr);
            $pattern = '/[a-zA-Z0-9_\.\+-]+@[A-Za-z0-9_-]+\.([A-Za-z0-9_-][A-Za-z0-9_]+)/'; //regex for pattern of e-mail address
            preg_match_all($pattern, $text_addr, $matches);
            if (count($matches[0]) > 0)
                $to = $matches[0];
        }
        $to = array_unique ($to);

        $message = $this->replace_tags($message, $params);
        $subject = $this->replace_tags($subject, $params);


        // if is_admin and not required emails end function here
        if (is_admin() && !isset($_POST["sendemails_admin"]))
            return;

        foreach ($to as $item)
            if (trim($item) != '')
            {
                if (!strpos($from_1,">"))
                    $from_1 = '"'.$from_1.'" <'.$from_1.'>';
                wp_mail(trim($item), $subject, $message,
                    "From: ".$from_1."\r\n".
                    ($replyto!=''?"Reply-To: ".$replyto."\r\n":'').
                    $content_type.
                    "X-Mailer: PHP/" . phpversion(), $attachments);
            }

        if ($mycalendarrows[0]->rep_days == 0 && $mycalendarrows[0]->rep_enable == 'yes')
        {
            $this->check_reports(true);
        }

        // 2- Send copy to user
        //---------------------------
        $to = $this->get_option('cu_user_email_field', CP_APPBOOK_DEFAULT_cu_user_email_field);
        $destination_to = sanitize_email($myrows[0]->notifyto);
        if (($destination_to != '' || $payer_email != '') && 'true' == $this->get_option('cu_enable_copy_to_user', CP_APPBOOK_DEFAULT_cu_enable_copy_to_user))
        {
            $message = str_replace('<'.'%', '%', __($this->get_option('cu_message', CP_APPBOOK_DEFAULT_cu_message) , 'appointment-hour-booking'));
            $message = str_replace('%'.'>', '%', $message);

            $subject = str_replace('<'.'%', '%', __($this->get_option('cu_subject', CP_APPBOOK_DEFAULT_cu_subject) , 'appointment-hour-booking'));
            $subject = str_replace('%'.'>', '%', $subject);
            if ('html' == $this->get_option('cu_emailformat', CP_APPBOOK_DEFAULT_email_format))
                $message = str_replace('%INFO%',str_replace("\n","<br />",str_replace('<','&lt;',$buffer_A)).'</pre>',$message);
            else
                $message = str_replace('%INFO%',$buffer_A,$message);

            $message = str_replace('%itemnumber%',$itemnumber,$message);
            $subject = str_replace('%itemnumber%',$itemnumber,$subject);

            $message = $this->replace_tags($message, $params);
            $subject = $this->replace_tags($subject, $params);

            if (!strpos($from,">"))
                $from = '"'.$from.'" <'.$from.'>';
            if ('html' == $this->get_option('cu_emailformat', CP_APPBOOK_DEFAULT_email_format)) $content_type = "Content-Type: text/html; charset=utf-8\n"; else $content_type = "Content-Type: text/plain; charset=utf-8\n";
            if ($destination_to != '')
                wp_mail($destination_to, $subject, $message,
                        "From: ".$from."\r\n".
                        $content_type.
                        "X-Mailer: PHP/" . phpversion(), $attachments);
            if ($destination_to != $payer_email && $payer_email != '')
                wp_mail(trim($payer_email), $subject, $message,
                        "From: ".$from."\r\n".
                        $content_type.
                        "X-Mailer: PHP/" . phpversion(), $attachments);
        }

    }


    function recursive_implode($glue, array $array, $include_keys = false, $trim_all = true)
    {
    	$glued_string = '';

    	// Recursively iterates array and adds key/value to glued string
    	array_walk_recursive($array, function($value, $key) use ($glue, $include_keys, &$glued_string)
    	{
    		$include_keys and $glued_string .= $key.$glue;
    		$glued_string .= $value.$glue;
    	});

    	// Removes last $glue from string
    	strlen($glue) > 0 and $glued_string = substr($glued_string, 0, -strlen($glue));

    	// Trim ALL whitespace
    	// $trim_all and $glued_string = preg_replace("/(\s)/ixsm", '', $glued_string);

    	return (string) $glued_string;
    }


    function available_templates(){

    	if( empty( $this->CP_CFPP_global_templates ) )
    	{
    		// Get available designs
    		$tpls_dir = dir( plugin_dir_path( __FILE__ ).'templates' );
    		$this->CP_CFPP_global_templates = array();
    		while( false !== ( $entry = $tpls_dir->read() ) )
    		{
    			if ( $entry != '.' && $entry != '..' && is_dir( $tpls_dir->path.'/'.$entry ) && file_exists( $tpls_dir->path.'/'.$entry.'/config.ini' ) )
    			{
    				if( ( $ini_array = parse_ini_file( $tpls_dir->path.'/'.$entry.'/config.ini' ) ) !== false )
    				{
    					if( !empty( $ini_array[ 'file' ] ) ) $ini_array[ 'file' ] = plugins_url( 'templates/'.$entry.'/'.$ini_array[ 'file' ], __FILE__ );
    					if( !empty( $ini_array[ 'thumbnail' ] ) ) $ini_array[ 'thumbnail' ] = plugins_url( 'templates/'.$entry.'/'.$ini_array[ 'thumbnail' ], __FILE__ );
    					$this->CP_CFPP_global_templates[ $ini_array[ 'prefix' ] ] = $ini_array;
    				}
    			}
    		}
    	}

    	return $this->CP_CFPP_global_templates;
    }


    function save_edition()
    {
        global $wpdb;

        $this->verify_nonce ($_POST["nonce"], 'cpappb_actions_admin');

        foreach ($_POST as $item => $value)
            if (!is_array($value))
                $_POST[$item] = stripcslashes($value);
        if (isset($_POST["gotab"]) && @$_POST["gotab"] == '')
        {
            update_option( 'cp_cpappb_rep_enable', sanitize_text_field($_POST["cp_cpappb_rep_enable"]));
            update_option( 'cp_cpappb_rep_days', sanitize_text_field($_POST["cp_cpappb_rep_days"]));
            update_option( 'cp_cpappb_rep_hour', sanitize_text_field($_POST["cp_cpappb_rep_hour"]));
            update_option( 'cp_cpappb_rep_emails', sanitize_text_field($_POST["cp_cpappb_rep_emails"]));
            update_option( 'cp_cpappb_fp_from_email', sanitize_text_field($_POST["cp_cpappb_fp_from_email"]));
            update_option( 'cp_cpappb_rep_subject', $this->clean_sanitize($_POST["cp_cpappb_rep_subject"]));
            update_option( 'cp_cpappb_rep_emailformat', sanitize_text_field($_POST["cp_cpappb_rep_emailformat"]));
            update_option( 'cp_cpappb_rep_message', $this->clean_sanitize($_POST["cp_cpappb_rep_message"]));
        }
        else if (@$_POST["gotab"] == 'csvarea')
        {
            update_option( 'cp_cpappb_bocsvexclude', sanitize_text_field($_POST["bocsvexclude"]));
            update_option( 'cp_cpappb_schcsvexclude', sanitize_text_field($_POST["schcsvexclude"]));
        }
        else if (@$_POST["gotab"] == 'miscsettings')
        {
            $honeypot = sanitize_text_field($_POST["cp_cpappb_honeypot"]);
            if ($honeypot == 'Yes')
                $honeypot = 'cpapphb'.substr(sanitize_key(md5(wp_generate_uuid4())),0,5);
            update_option( 'cp_cpappb_honeypot', $honeypot);
            update_option( 'cp_cpappb_storeip', sanitize_text_field($_POST["cp_cpappb_storeip"]));
        }
        else if (@$_POST["gotab"] == 'schedulecalarea')
        {
            update_option( 'cp_cpappb_schcaltitle_admin', $this->clean_sanitize($_POST["schcaltitle_admin"]));
            update_option( 'cp_cpappb_schcalcontent_admin', $this->clean_sanitize($_POST["schcalcontent_admin"]));
            update_option( 'cp_cpappb_schcaltitle_public', $this->clean_sanitize($_POST["schcaltitle_public"]));
            update_option( 'cp_cpappb_schcalcontent_public', $this->clean_sanitize($_POST["schcalcontent_public"]));
            update_option( 'cp_cpappb_sch_admin_blockedt', sanitize_text_field($_POST["cp_cpappb_sch_admin_blockedt"]));
            update_option( 'cp_cpappb_schcalcontent_exclude', sanitize_text_field(trim(str_replace(' ','',str_replace('>','',str_replace('<','',str_replace('%','',$_POST["cp_cpappb_schcalcontent_exclude"])))))));
            update_option( 'cp_cpappb_schcalcontent_otherparams', $this->clean_sanitize($_POST["cp_cpappb_schcalcontent_otherparams"]));

        }
        else if (@$_POST["gotab"] == 'fixarea')
        {
            update_option( 'CP_APPB_LOAD_SCRIPTS', ($_POST["ccscriptload"]=="1"?"0":"1") );
            update_option( 'CP_APPB_CSV_CHARFIX', ($_POST["csvcharautofix"]==""?"":"1") );
            update_option( 'CP_APPB_CSV_SEPARATOR', ($_POST["csvseparator"]==";"?";":",") );
            if ($_POST["cccharsets"] != '')
            {
                $target_charset = str_replace('`','``',sanitize_text_field($_POST["cccharsets"]));
                $tables = array( $wpdb->prefix.$this->table_messages, $wpdb->prefix.$this->table_items );
                foreach ($tables as $tab)
                {
                    $myrows = $wpdb->get_results( "DESCRIBE {$tab}" );
                    foreach ($myrows as $item)
	                {
	                    $name = $item->Field;
	        	        $type = $item->Type;
	        	        if (preg_match("/^varchar\((\d+)\)$/i", $type, $mat) || !strcasecmp($type, "CHAR") || !strcasecmp($type, "TEXT") || !strcasecmp($type, "MEDIUMTEXT"))
	        	        {
	                        $wpdb->query("ALTER TABLE {$tab} CHANGE {$name} {$name} {$type} COLLATE `{$target_charset}`");
	                    }
	                }
                }
            }
        }
        else
        {
            if (substr_count(@$_POST['editionarea'],"\\\""))
                $_POST["editionarea"] = stripcslashes(@$_POST["editionarea"]);
            if (@$_POST["cfwpp_edit"] == 'js')
                update_option('CP_AHB_JS', base64_encode($this->clean_sanitize(@$_POST["editionarea"])));
            else if (@$_POST["cfwpp_edit"] == 'css')
                update_option('CP_AHB_CSS', base64_encode($this->clean_sanitize(@$_POST["editionarea"])));
        }
    }


    function save_options()
    {
        global $wpdb;
        $this->item = intval($_POST[$this->prefix."_id"]);

        $this->verify_nonce ($_POST["anonce"], 'cpappb_actions_admin');

        $this->add_field_verify($wpdb->prefix.$this->table_items, 'vs_text_nmore');
        $this->add_field_verify($wpdb->prefix.$this->table_items, 'vs_text_cost');
        $this->add_field_verify($wpdb->prefix.$this->table_items, 'vs_text_cancel');
        $this->add_field_verify($wpdb->prefix.$this->table_items, 'vs_text_quantity');
        $this->add_field_verify($wpdb->prefix.$this->table_items, 'calendar_language');
        $this->add_field_verify($wpdb->prefix.$this->table_items, 'date_format');
        $this->add_field_verify($wpdb->prefix.$this->table_items, 'vs_text_maxapp');
        $this->add_field_verify($wpdb->prefix.$this->table_items, 'defaultstatus', "VARCHAR(250) DEFAULT '' NOT NULL");;
        $this->add_field_verify($wpdb->prefix.$this->table_items, 'defaultpaidstatus', "VARCHAR(250) DEFAULT '' NOT NULL");
        $this->add_field_verify($wpdb->prefix.$this->table_items, 'cp_user_access_settings', "VARCHAR(10) DEFAULT '' NOT NULL");
        $this->add_field_verify($wpdb->prefix.$this->table_items, 'display_emails_endtime', "VARCHAR(10) DEFAULT '' NOT NULL");

        while ((substr_count($_POST['form_structure'],"\\") > 30) || substr_count($_POST['form_structure'],"\\\"title\\\":"))
            foreach ($_POST as $item => $value)
                if (!is_array($value))
                    $_POST[$item] = stripcslashes($value);

        $data = array(
                      'form_structure' => $this->clean_sanitize($this->admin_process_json($_POST['form_structure'])),

                      'vs_text_maxapp' => sanitize_text_field($_POST['vs_text_maxapp']),
                      'calendar_language' => sanitize_text_field($_POST['calendar_language']),
                      'date_format' => sanitize_text_field($_POST['date_format']),
                      'product_name' => sanitize_text_field($_POST['product_name']),
                      'pay_later_label' => sanitize_text_field($_POST['pay_later_label']),
                      'fp_from_email' => sanitize_text_field($_POST['fp_from_email']),
                      'fp_destination_emails' => sanitize_text_field(@$_POST['fp_destination_emails']),
                      'fp_subject' => $this->clean_sanitize($_POST['fp_subject']),
                      'fp_inc_additional_info' => sanitize_text_field($_POST['fp_inc_additional_info']),
                      'fp_return_page' => sanitize_text_field($_POST['fp_return_page']),
                      'fp_message' => $this->clean_sanitize($_POST['fp_message']),
                      'fp_emailformat' => sanitize_text_field($_POST['fp_emailformat']),

                      'defaultstatus' => sanitize_text_field($_POST['defaultstatus']),
                      'defaultpaidstatus' => sanitize_text_field($_POST['defaultpaidstatus']),

                      'fp_emailtomethod' => sanitize_text_field($_POST['fp_emailtomethod']),
                      'fp_destination_emails_field' => sanitize_text_field(@$_POST['fp_destination_emails_field']),

                      'cu_enable_copy_to_user' => sanitize_text_field($_POST['cu_enable_copy_to_user']),
                      'cu_user_email_field' => sanitize_text_field(@$_POST['cu_user_email_field']),
                      'cu_subject' => $this->clean_sanitize($_POST['cu_subject']),
                      'cu_message' => $this->clean_sanitize($_POST['cu_message']),
                      'cu_emailformat' => sanitize_text_field($_POST['cu_emailformat']),
                      'fp_emailfrommethod' => sanitize_text_field($_POST['fp_emailfrommethod']),

                      'vs_text_is_required' => sanitize_text_field($_POST['vs_text_is_required']),
                      'vs_text_is_email' => sanitize_text_field($_POST['vs_text_is_email']),
                      'vs_text_datemmddyyyy' => sanitize_text_field($_POST['vs_text_datemmddyyyy']),
                      'vs_text_dateddmmyyyy' => sanitize_text_field($_POST['vs_text_dateddmmyyyy']),
                      'vs_text_number' => sanitize_text_field($_POST['vs_text_number']),
                      'vs_text_digits' => sanitize_text_field($_POST['vs_text_digits']),
                      'vs_text_max' => sanitize_text_field($_POST['vs_text_max']),
                      'vs_text_min' => sanitize_text_field($_POST['vs_text_min']),
                      'vs_text_pageof' => sanitize_text_field($_POST['vs_text_pageof']),
                      'vs_text_submitbtn' => sanitize_text_field($_POST['vs_text_submitbtn']),
                      'vs_text_previousbtn' => sanitize_text_field($_POST['vs_text_previousbtn']),
                      'vs_text_nextbtn' => sanitize_text_field($_POST['vs_text_nextbtn']),

                      'vs_text_quantity' => sanitize_text_field($_POST['vs_text_quantity']),
                      'vs_text_cancel' => sanitize_text_field($_POST['vs_text_cancel']),
                      'vs_text_cost' => sanitize_text_field($_POST['vs_text_cost']),
                      'vs_text_nmore' => sanitize_text_field($_POST['vs_text_nmore']),

                      'cp_user_access' => serialize($this->sanitize( (isset($_POST["cp_user_access"])?@$_POST["cp_user_access"]:array()) )),
                      'cp_user_access_settings' => sanitize_text_field($_POST['cp_user_access_settings']),
                      'display_emails_endtime' => sanitize_text_field($_POST['display_emails_endtime']),
                      'rep_enable' => sanitize_text_field($_POST['rep_enable']),
                      'rep_days' => sanitize_text_field($_POST['rep_days']),
                      'rep_hour' => sanitize_text_field($_POST['rep_hour']),
                      'rep_emails' => sanitize_text_field($_POST['rep_emails']),
                      'rep_subject' => $this->clean_sanitize($_POST['rep_subject']),
                      'rep_emailformat' => sanitize_text_field($_POST['rep_emailformat']),
                      'rep_message' => $this->clean_sanitize($_POST['rep_message']),

                      'cv_enable_captcha' => sanitize_text_field($_POST['cv_enable_captcha']),
                      'cv_width' => sanitize_text_field($_POST['cv_width']),
                      'cv_height' => sanitize_text_field($_POST['cv_height']),
                      'cv_chars' => sanitize_text_field($_POST['cv_chars']),
                      'cv_font' => sanitize_text_field($_POST['cv_font']),
                      'cv_min_font_size' => sanitize_text_field($_POST['cv_min_font_size']),
                      'cv_max_font_size' => sanitize_text_field($_POST['cv_max_font_size']),
                      'cv_noise' => sanitize_text_field($_POST['cv_noise']),
                      'cv_noise_length' => sanitize_text_field($_POST['cv_noise_length']),
                      'cv_background' => sanitize_text_field(str_replace('#','',$_POST['cv_background'])),
                      'cv_border' => sanitize_text_field(str_replace('#','',$_POST['cv_border'])),
                      'cv_text_enter_valid_captcha' => sanitize_text_field($_POST['cv_text_enter_valid_captcha'])
    	);

        $wpdb->update ( $wpdb->prefix.$this->table_items, $data, array( 'id' => $this->item ));

        if (isset($_POST["savepublish"]))
        {
            echo '<script type="text/javascript">document.location="?page=cp_apphourbooking&pwizard=1&cal='.$this->item.'";</script>';
            //exit;
        } else if (isset($_POST["savereturn"]))
        {
            echo '<script type="text/javascript">document.location="?page=cp_apphourbooking&confirm=1";</script>';
            //exit;
        }
    }


    function get_form_field_label ($fieldid, $form)
    {
            foreach($form as $item)
                if ($item->name == $fieldid)
                {
                    if (isset($item->shortlabel) && $item->shortlabel != '')
                        return $item->shortlabel;
                    else
                        return $item->title;
                }
        return $fieldid;
    }


    function generateSafeFileName($filename) {
        $filename = strtolower(strip_tags($filename));
        $filename = str_replace(";","_",$filename);
        $filename = str_replace("#","_",$filename);
        $filename = str_replace(" ","_",$filename);
        $filename = str_replace("'","",$filename);
        $filename = str_replace('"',"",$filename);
        $filename = str_replace("__","_",$filename);
        $filename = str_replace("&","and",$filename);
        $filename = str_replace("/","_",$filename);
        $filename = str_replace("\\","_",$filename);
        $filename = str_replace("?","",$filename);
        return sanitize_file_name($filename);
    }


    function export_csv ()
    {        
        if (!is_admin())
            return;
        global $wpdb;

        $this->item = intval($this->get_param("cal"));

        if ($this->item)
        {
            $form = json_decode($this->cleanJSON($this->get_option('form_structure', CP_APPBOOK_DEFAULT_form_structure)));
            $form = $form[0];
        }
        else
            $form = array();

        $cond = '';
        if ($this->get_param("search")) $cond .= " AND (data like '%".esc_sql($this->get_param("search"))."%' OR posted_data LIKE '%".esc_sql($this->get_param("search"))."%')";

        $rawfrom = (isset($_GET["dfrom"]) ? sanitize_text_field($_GET["dfrom"]) : '');
        $rawto = (isset($_GET["dto"]) ? sanitize_text_field(@$_GET["dto"]) : '');
        if ($this->get_option('date_format', 'mm/dd/yy') == 'dd/mm/yy')
        {
            $rawfrom = str_replace('/','.',$rawfrom);
            $rawto = str_replace('/','.',$rawto);
        }

        if ($this->get_param("dfrom")) $cond .= " AND (`time` >= '".esc_sql(date("Y-m-d",strtotime($rawfrom)))."')";
        if ($this->get_param("dto")) $cond .= " AND (`time` <= '".esc_sql(date("Y-m-d",strtotime($rawto)))." 23:59:59')";
        if ($this->item != 0) $cond .= " AND formid=".intval($this->item);


	    $events_query = "SELECT * FROM ".$wpdb->prefix.$this->table_messages." WHERE 1=1 ".$cond." ORDER BY `time` DESC";
	    /**
	     * Allows modify the query of messages, passing the query as parameter
	     * returns the new query
	     */
	    $events_query = apply_filters( 'cpappb_csv_query', $events_query );
	    $events = $wpdb->get_results( $events_query );

        if ($this->include_user_data_csv)
            $fields = array("ID", "Form", "Time", "IP Address", "email");
        else
            $fields = array("ID", "Form", "Time", "email");

        $fields_exclude = explode(",",trim(get_option('cp_cpappb_bocsvexclude',"")));
        $fields_exclude_org = array();
        for($j=0; $j<count($fields_exclude); $j++)
        {
           $fields_exclude_org[] = (trim($fields_exclude[$j]));
           $fields_exclude[$j] = strtolower(trim($fields_exclude[$j]));
        }

        $fields = array_values(array_diff($fields, $fields_exclude_org));

        $newfields = array();
        for($j=0; $j<count($fields); $j++)
           if (!in_array($fields[$j],$fields_exclude))
               $newfields[] = $fields[$j];
        $fields = $newfields;

        $values = array();
        foreach ($events as $item)
        {
            $value = array();
            if (in_array('ID',$fields)) $value[] = $item->id;
            if (in_array('Form',$fields)) $value[] = $this->get_option('form_name','');
            if (in_array('Time',$fields)) $value[] = $item->time;
            if ($this->include_user_data_csv)
                if (in_array('IP Address',$fields)) $value[] = $item->ipaddr;
            if (in_array('email',$fields)) $value[] = sanitize_email($item->notifyto);

            if ($item->posted_data)
                $data = unserialize($item->posted_data);
            else
                $data = array();

            $end = count($fields);
            for ($i=0; $i<$end; $i++)
                {  //  if (isset($data[$fields[$i]]) )
                    if (isset($data[$fields[$i]]))
                        $d = $data[$fields[$i]];
                    else if (!isset($value[$i]))
                        $d = "";
                    else
                        $d = $value[$i];
                    if (substr($fields[$i],0,strlen('app_status_')) == 'app_status_')
                    {
                       $d = $data["apps"][ intval( substr($fields[$i],strlen('app_status_'))-1) ]["cancelled"];
                       if ($d == '')
                           $d = __('Approved','appointment-hour-booking');
                    }
                    $value[$i] = $d;
                    unset($data[$fields[$i]]);
                }

            if (is_array($data)) foreach ($data as $k => $d)
                if ($k != 'apps' && $k != 'itemnumber' && !in_array(strtolower($k),$fields_exclude))
                {
                   $fields[] = $k;
                   if (substr($k,0,strlen('app_status_')) == 'app_status_')
                   {
                       $d = $data["apps"][ intval( substr($k,strlen('app_status_'))-1) ]["cancelled"];
                       if ($d == '')
                           $d = __('Approved','appointment-hour-booking');
                   }
                   $value[] = $d;
                }
            $values[] = $value;
        }

        $separator = get_option('CP_APPB_CSV_SEPARATOR',",");
        if ($separator == '') $separator = ',';

        $filename = $this->generateSafeFileName(strtolower($this->get_option('form_name','export'))).'_'.date("m_d_y");

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$filename.".csv");
        $end = count($fields);
        for ($i=0; $i<$end; $i++)
        {
            $hlabel = $this->iconv("utf-8", "ISO-8859-1//TRANSLIT//IGNORE", $this->get_form_field_label($fields[$i],$form));
            echo '"'.str_replace('"','""', $hlabel).'"'.$separator;
        }

        echo "\n";
        foreach ($values as $item)
        {
            for ($i=0; $i<$end; $i++)
            {
                if (!isset($item[$i]))
                    $item[$i] = '';
                if (is_array($item[$i]))
                    $item[$i] = implode(',',$item[$i]);
                $item[$i] = $this->iconv("utf-8", "ISO-8859-1//TRANSLIT//IGNORE", $item[$i]);
                echo '"'.str_replace('"','""', trim($item[$i])).'"'.$separator;
            }
            echo "\n";
        }

        exit;
    }


    function export_csv_schedule ($atts)
    {
        if (!is_admin())
            return;
        global $wpdb;
        extract( shortcode_atts( array(
	    	'calendar' => '',
	    	'fields' => 'DATE,TIME,SERVICE,final_price,paid,email,data,cancelled',
	    	'from' => "today",
	    	'to' => "today +30 days",
            'paidonly' => "",
            'status' => "-1"
	    ), $atts ) );

        if (isset($_REQUEST["dfrom"])) $from = $_REQUEST["dfrom"];
        if (isset($_REQUEST["dto"])) $to = $_REQUEST["dto"];
        if (isset($_REQUEST["status"])) $status = $_REQUEST["status"];
        if (isset($_REQUEST["paid"])) $paidonly = $_REQUEST["paid"];
        if ($this->get_param("cal")) $calendar = intval($this->get_param("cal"));

        ob_start();

        // calculate dates
        $this->item = intval($this->get_param("cal"));
        if ($this->get_option('date_format', 'mm/dd/yy') == 'dd/mm/yy')
        {
            $from = str_replace('/','.',$from);
            $to = str_replace('/','.',$to);
        }

        $from = date("Y-m-d",strtotime($from));
        $to = date("Y-m-d",strtotime($to));

        // pre-select time-slots
        $selection = array();
        $rows = $wpdb->get_results( $wpdb->prepare("SELECT notifyto,posted_data,data FROM ".$wpdb->prefix.$this->table_messages." WHERE ".($calendar?'formid='.intval($calendar).' AND ':'')."time<=%s ORDER BY time DESC LIMIT 0,10000", $to) );
        foreach($rows as $item)
        {
            $data = unserialize($item->posted_data);
            if (!$paidonly || $data['paid'])
            {
                foreach($data["apps"] as $app)
                    if ($app["date"] >= $from && $app["date"] <= $to && ($status == 'all' || $status == '-1' || $status == $app["cancelled"]) )
                    {
                        $selection[] = array($app["date"]." ".$app["slot"], $app["date"], $app["slot"], $data, sanitize_email($item->notifyto), $item->data, $app["cancelled"], $app["service"], $app["quant"]);
                    }
            }
        }

        // order time-slots
        if (!function_exists('appbkfastsortfn'))
        {
            function appbkfastsortfn($a, $b) { return ($a[0] > $b[0]); }
        }
        usort($selection, "appbkfastsortfn" );

        // clean fields IDs
        $fields = explode(",",trim($fields));
        for($j=0; $j<count($fields); $j++)
           $fields[$j] = strtolower(trim($fields[$j]));

        $fields_exclude = explode(",",trim(get_option('cp_cpappb_schcsvexclude',"")));
        for($j=0; $j<count($fields_exclude); $j++)
           $fields_exclude[$j] = strtolower(trim($fields_exclude[$j]));

        $fields = array_values(array_diff ($fields, $fields_exclude));

        $separator = get_option('CP_APPB_CSV_SEPARATOR',",");
        if ($separator == '') $separator = ',';

        // print table
        for($i=0; $i<count($selection); $i++)
        {
            for($j=0; $j<count($fields); $j++)
            {
                if ($j>0) echo $separator;
                echo '"';
                switch ($fields[$j]) {
                    case 'date':
                        $value = $selection[$i][1];
                        break;
                    case 'time':
                        $value = $selection[$i][2];
                        break;
                    case 'email':
                        $value = $selection[$i][4];
                        break;
                    case 'service':
                        $value = $selection[$i][7];
                        break;
                    case 'quantity':
                        $value = $selection[$i][8];
                        break;
                    case 'cancelled':
                        if ($selection[$i][6] == '')
                            $value = __('Approved','appointment-hour-booking');
                        else
                            $value = $selection[$i][6];
                        break;
                    case 'data':
                        $value = substr($selection[$i][5],strpos($selection[$i][5],"\n\n")+2);
                        break;
                    case 'paid':
                        $value = (@$selection[$i][3]['paid']?__('Yes','appointment-hour-booking'):'');
                        break;
                    default:
                        $value = ($selection[$i][3][$fields[$j]]==''?'':$selection[$i][3][$fields[$j]])."";
                }
                $value = str_replace('"','""', $value);
                echo trim($value);
                echo '"';
            }
            echo "\n";
        }

        $buffered_contents = ob_get_contents();
        ob_end_clean();

        if ($this->item)
        {
            $form = json_decode($this->cleanJSON($this->get_option('form_structure', CP_APPBOOK_DEFAULT_form_structure)));
            $form = $form[0];
        }
        else
            $form = array();

        $filename = $this->generateSafeFileName(strtolower($this->get_option('form_name','export'))).'_'.date("m_d_y");

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$filename.".csv");

        $end = count($fields);
        for ($i=0; $i<$end; $i++)
        {
            $hlabel = $this->iconv("utf-8", "ISO-8859-1//TRANSLIT//IGNORE", $this->get_form_field_label($fields[$i],$form));
            echo '"'.str_replace('"','""', $hlabel).'"'.$separator;
        }
        echo "\n";
        echo $buffered_contents;

        exit;
    }


    public function fixurl($base, $param)
    {
        if (strpos($base,'?'))
            return $base."&".$param;
        else
		{
			$base = rtrim($base,"/")."/";
            return $base."?".$param;
		}
    }


    public function setId($id)
    {
        $this->item = intval($id);
    }


    public function translate_permalink($url)
    {
        $postid = url_to_postid($url);
        if ($postid)
        {
            $newpostid = apply_filters( 'wpml_object_id', $postid, 'post', TRUE );
            if ($newpostid != $postid)
                $url = get_permalink($newpostid);
        }
        return $url;
    }


    public function filter_allowed_tags($content)
    {
        //$allowed_tags = wp_kses_allowed_html( 'post' );
        //return  wp_kses( $content, $allowed_tags );
        return  wp_kses( $content, $this->tags_allowed );
    }


    public function admin_process_json($str)
    {
        $form_data = json_decode($this->cleanJSON($str));

        $form_data[1][0]->title = $this->filter_allowed_tags(($form_data[1][0]->title));
        $form_data[1][0]->description = $this->filter_allowed_tags(($form_data[1][0]->description));

        for ($i=0; $i < count($form_data[0]); $i++)
        {
            $form_data[0][$i]->title = $this->filter_allowed_tags(($form_data[0][$i]->title));
            $form_data[0][$i]->userhelpTooltip = $this->filter_allowed_tags(($form_data[0][$i]->userhelpTooltip));
            $form_data[0][$i]->userhelp = $this->filter_allowed_tags(($form_data[0][$i]->userhelp));

          //  if (property_exists($form_data[0][$i], 'predefined') && $form_data[0][$i]->predefined != '' && $form_data[0][$i]->ftype != 'fapp')
          //      $form_data[0][$i]->predefined = $this->filter_allowed_tags($form_data[0][$i]->predefined);

            if ($form_data[0][$i]->ftype == 'fCommentArea')
                $form_data[0][$i]->userhelp = $this->filter_allowed_tags(($form_data[0][$i]->userhelp));
            else if ($form_data[0][$i]->ftype == 'fradio' || $form_data[0][$i]->ftype == 'fcheck' || $form_data[0][$i]->ftype == 'fdropdown')
            {
                    for ($j=0; $j < count($form_data[0][$i]->choices); $j++)
                        $form_data[0][$i]->choices[$j] = $this->filter_allowed_tags(($form_data[0][$i]->choices[$j]));
            }
            else if ($form_data[0][$i]->ftype == 'fapp')
            {
                for ($j=0; $j < count($form_data[0][$i]->services); $j++)
                    $form_data[0][$i]->services[$j]->name = $this->filter_allowed_tags(($form_data[0][$i]->services[$j]->name));
                $form_data[0][$i]->emptySelect= $this->filter_allowed_tags(($form_data[0][$i]->emptySelect));
            }
        }
        $str = json_encode($form_data);
        return $str;
    }


    public function translate_json($str)
    {
        $form_data = json_decode($this->cleanJSON($str));

        $form_data[1][0]->title = $this->filter_allowed_tags(__($form_data[1][0]->title,'appointment-hour-booking'));
        $form_data[1][0]->description = $this->filter_allowed_tags(__($form_data[1][0]->description,'appointment-hour-booking'));

        for ($i=0; $i < count($form_data[0]); $i++)
        {
            $form_data[0][$i]->title = $this->filter_allowed_tags(__($form_data[0][$i]->title,'appointment-hour-booking'));
            $form_data[0][$i]->userhelpTooltip = $this->filter_allowed_tags(__($form_data[0][$i]->userhelpTooltip,'appointment-hour-booking'));
            $form_data[0][$i]->userhelp = $this->filter_allowed_tags(__($form_data[0][$i]->userhelp,'appointment-hour-booking'));

            if (property_exists($form_data[0][$i], 'predefined') && $form_data[0][$i]->predefined != '' && $form_data[0][$i]->ftype != 'fapp')
                $form_data[0][$i]->predefined = __($form_data[0][$i]->predefined,'appointment-hour-booking');

            if ($form_data[0][$i]->ftype == 'fCommentArea')
                $form_data[0][$i]->userhelp = $this->filter_allowed_tags(__($form_data[0][$i]->userhelp,'appointment-hour-booking'));
            else if ($form_data[0][$i]->ftype == 'fradio' || $form_data[0][$i]->ftype == 'fcheck' || $form_data[0][$i]->ftype == 'fdropdown')
            {
                    for ($j=0; $j < count($form_data[0][$i]->choices); $j++)
                        $form_data[0][$i]->choices[$j] = $this->filter_allowed_tags(__($form_data[0][$i]->choices[$j],'appointment-hour-booking'));
            }
            else if ($form_data[0][$i]->ftype == 'fapp')
            {
                for ($j=0; $j < count($form_data[0][$i]->services); $j++)
                    $form_data[0][$i]->services[$j]->name = $this->filter_allowed_tags(__($form_data[0][$i]->services[$j]->name,'appointment-hour-booking'));
                $form_data[0][$i]->emptySelect= $this->filter_allowed_tags(__($form_data[0][$i]->emptySelect,'appointment-hour-booking'));
            }
        }
        $str = json_encode($form_data);
        return $str;
    }


    private function get_records_csv($formid, $form_name = "")
    {
        global $wpdb;

        $saved_item = $this->item;
        $this->item = $formid;

        $last_sent_id = get_option('cp_cpappb_last_sent_id_'.$formid, '0');
        $events = $wpdb->get_results(
                             $wpdb->prepare("SELECT * FROM ".$wpdb->prefix.$this->table_messages." WHERE formid=%d AND id>%d ORDER BY id ASC",$formid,$last_sent_id)
                                     );

        if ($wpdb->num_rows <= 0) // if no rows, return empty
        {
            $this->item = $saved_item;
            return '';
        }

        if ($this->item)
        {
            $form = json_decode($this->cleanJSON($this->get_option('form_structure', CP_APPBOOK_DEFAULT_form_structure)));
            $form = $form[0];
        }
        else
            $form = array();

        $buffer = '';
        if ($this->include_user_data_csv)
            $fields = array("Submission ID", "Form", "Time", "IP Address", "email");
        else
            $fields = array("Submission ID", "Form", "Time", "email");

        $fields_exclude = explode(",",trim(get_option('cp_cpappb_bocsvexclude',"")));
        $fields_exclude_org = array();
        for($j=0; $j<count($fields_exclude); $j++)
        {
           $fields_exclude_org[] = (trim($fields_exclude[$j]));
           $fields_exclude[$j] = strtolower(trim($fields_exclude[$j]));
        }

        $fields = array_values(array_diff($fields, $fields_exclude_org));

        $newfields = array();
        for($j=0; $j<count($fields); $j++)
           if (!in_array($fields[$j],$fields_exclude))
               $newfields[] = $fields[$j];
        $fields = $newfields;


        $values = array();
        foreach ($events as $item)
        {
            $value = array();
            if (in_array('Submission ID',$fields)) $value[] = $item->id;
            if (in_array('Form',$fields)) $value[] = $this->get_option('form_name','');
            if (in_array('Time',$fields)) $value[] = $item->time;
            if ($this->include_user_data_csv)
                if (in_array('IP Address',$fields)) $value[] = $item->ipaddr;
            if (in_array('email',$fields)) $value[] = sanitize_email($item->notifyto);

            $last_sent_id = $item->id;
            if ($item->posted_data)
                $data = unserialize($item->posted_data);
            else
                $data = array();

            $end = count($fields);
            for ($i=0; $i<$end; $i++)
            { //if (isset($data[$fields[$i]]) ){
                if (isset($data[$fields[$i]]))
                    $d = $data[$fields[$i]];
                else if (!isset($value[$i]))
                    $d = "";
                else
                    $d = $value[$i];                
                if (substr($fields[$i],0,strlen('app_status_')) == 'app_status_')
                {
                   $d = $data["apps"][ intval( substr($fields[$i],strlen('app_status_'))-1) ]["cancelled"];
                   if ($d == '')
                       $d = __('Approved','appointment-hour-booking');
                }
                $value[$i] = $d;
                unset($data[$fields[$i]]);
            }

            if (is_array($data)) foreach ($data as $k => $d)
                if ($k != 'apps' && $k != 'itemnumber' && !in_array(strtolower($k),$fields_exclude))
                {
                   $fields[] = $k;
                   if (substr($k,0,strlen('app_status_')) == 'app_status_')
                   {
                       $d = $data["apps"][ intval( substr($k,strlen('app_status_'))-1) ]["cancelled"];
                       if ($d == '')
                           $d = __('Approved','appointment-hour-booking');
                   }
                   $value[] = $d;
                }
            $values[] = $value;
        }
        update_option('cp_cpappb_last_sent_id_'.$formid, $last_sent_id);


        $separator = get_option('CP_APPB_CSV_SEPARATOR',",");
        if ($separator == '') $separator = ',';

        $end = count($fields);
        for ($i=0; $i<$end; $i++)
        {
            $hlabel = $this->iconv("utf-8", "ISO-8859-1//TRANSLIT//IGNORE", $this->get_form_field_label($fields[$i],$form));
            $buffer .= '"'.str_replace('"','""', $hlabel).'"'.$separator;
        }
        $buffer .= "\n";
        foreach ($values as $item)
        {
            for ($i=0; $i<$end; $i++)
            {
                if (!isset($item[$i]))
                    $item[$i] = '';
                if (is_array($item[$i]))
                    $item[$i] = implode(',',$item[$i]);
                $item[$i] = $this->iconv("utf-8", "ISO-8859-1//TRANSLIT//IGNORE", $item[$i]);
                $buffer .= '"'.str_replace('"','""', $item[$i]).'"'.$separator;
            }
            $buffer .= "\n";
        }

        $this->item = $saved_item;

        return $buffer;

    }

    private function check_reports($skip_verification = false) {
        global $wpdb;

        $last_verified = get_option('cp_cpappb_last_verified','');
        if ( $skip_verification || $last_verified == '' || $last_verified < date("Y-m-d H:i:s", strtotime("-1 minutes")) )  // verification to don't check too fast to avoid overloading the site
        {
            update_option('cp_cpappb_last_verified',date("Y-m-d H:i:s"));

            // global reports for all forms
            if (get_option('cp_cpappb_rep_enable', 'no') == 'yes' && get_option('cp_cpappb_rep_days', '') != '' && get_option('cp_cpappb_rep_emails', '') != '' )
            {
                $formid = 0;
                $verify_after = date("Y-m-d H:i:s", strtotime("-".get_option('cp_cpappb_rep_days', '')." days"));
                $last_sent = get_option('cp_cpappb_last_sent'.$formid, '');
                if ($last_sent == '' || $last_sent < $verify_after)  // check if this form needs to check for a new report
                {
                    update_option('cp_cpappb_last_sent'.$formid, date("Y-m-d ".(get_option('cp_cpappb_rep_hour', '')<'10'?'0':'').get_option('cp_cpappb_rep_hour', '').":00:00"));
                    $text = '';
                    $forms = $wpdb->get_results("SELECT id,fp_from_email,form_name,rep_days,rep_hour,rep_emails,rep_subject,rep_emailformat,rep_message,rep_enable FROM ".$wpdb->prefix.$this->table_items);  // " WHERE rep_emails<>'' AND rep_enable='yes'"
                    $attachments = array();
                    foreach ($forms as $form)  // for each form with the reports enabled
                    {
                        $csv = $this->get_records_csv($form->id, $form->form_name);
                        if ($csv != '')
                        {
                            $text = "- ".substr_count($csv,",\n\"").' submissions from '.$form->form_name."\n";
                            $filename = $this->generateSafeFileName(strtolower($form->form_name)).'_'.date("m_d_y");
                            $filename = WP_CONTENT_DIR . '/uploads/'.$filename .'.csv';
                            $handle = fopen($filename, 'w');
                            fwrite($handle,$csv);
                            fclose($handle);
                            $attachments[] = $filename;
                        }
                    }
                    if ('html' == get_option('cp_cpappb_rep_emailformat','')) $content_type = "Content-Type: text/html; charset=utf-8\n"; else $content_type = "Content-Type: text/plain; charset=utf-8\n";
                    if (count($attachments))
                        wp_mail( str_replace(" ","",str_replace(";",",",get_option('cp_cpappb_rep_emails',''))), get_option('cp_cpappb_rep_subject',''), get_option('cp_cpappb_rep_message','')."\n".$text,
                                    "From: \"".get_option('cp_cpappb_fp_from_email','')."\" <".get_option('cp_cpappb_fp_from_email','').">\r\n".
                                    $content_type.
                                    "X-Mailer: PHP/" . phpversion(),
                                    @$attachments);
                }
            }

            // reports for specific forms
            $forms = $wpdb->get_results("SELECT id,form_name,fp_from_email,rep_days,rep_hour,rep_emails,rep_subject,rep_emailformat,rep_message,rep_enable FROM ".$wpdb->prefix.$this->table_items." WHERE rep_emails<>'' AND rep_enable='yes'");
            foreach ($forms as $form)  // for each form with the reports enabled
            {
                $formid = $form->id;
                $verify_after = date("Y-m-d H:i:s", strtotime("-".$form->rep_days." days"));
                $last_sent = get_option('cp_cpappb_last_sent'.$formid, '');
                if ($skip_verification || $last_sent == '' || $last_sent < $verify_after)  // check if this form needs to check for a new report
                {
                    update_option('cp_cpappb_last_sent'.$formid, date("Y-m-d ".($form->rep_hour<'10'?'0':'').$form->rep_hour.":00:00"));
                    $csv = $this->get_records_csv($formid, $form->form_name);
                    if ($csv != '')
                    {
                        $filename = $this->generateSafeFileName(strtolower($form->form_name)).'_'.date("m_d_y");
                        $filename = WP_CONTENT_DIR . '/uploads/'.$filename .'.csv';
                        $handle = fopen($filename, 'w');
                        fwrite($handle,$csv);
                        fclose($handle);
                        $attachments = array( $filename );
                        if ('html' == $form->rep_emailformat) $content_type = "Content-Type: text/html; charset=utf-8\n"; else $content_type = "Content-Type: text/plain; charset=utf-8\n";
                        wp_mail( str_replace(" ","",str_replace(";",",",$form->rep_emails)), $form->rep_subject, $form->rep_message,
                                "From: \"".$form->fp_from_email."\" <".$form->fp_from_email.">\r\n".
                                $content_type.
                                "X-Mailer: PHP/" . phpversion(),
                                $attachments);
                    }
                }
            } // end foreach
        } // end if
    }  // end check_reports function


    protected function iconv($from, $to, $text)
    {
        $text = trim($text);
        if ( strlen($text) > 1 && (in_array(substr($text,0,1), array('=','@','+'))) )
        {
                $text = chr(9).$text;
        }
        if (get_option('CP_APPB_CSV_CHARFIX',"") == "" && function_exists('iconv'))
            return iconv($from, $to, $text);
        else
            return $text;
    }


} // end class





// Auxiliar functions
// ******************************************************************

// Auxiliar functions
// ***********************************************************************

function cpappb_cleanJSON ($str)
{
    $str = str_replace('&qquot;','"',$str);
    $str = str_replace('	',' ',$str);
    $str = str_replace("\n",'\n',$str);
    $str = str_replace("\r",'',$str);
    return $str;
}

function cpappb_form_result( $atts, $content = "", $id = 0 )
{
    // not needed at this point
}


function _cpappb_extract_tags( $message )
{
	$tags_arr = array();
	if(
		preg_match_all(	"/<%(info|fieldname\d+|fieldname\d+_label|fieldname\d+_shortlabel|fieldname\d+_value|fieldname\d+_url|fieldname\d+_urls|coupon|itemnumber|final_price|payment_option|ipaddress|currentdate_mmddyyyy|currentdate_ddmmyyyy)\b(?:(?!%>).)*%>/i",
			$message,
			$matches
		)
	)
	{
		$tag = array();
		foreach( $matches[ 0 ] as $index => $value )
		{
			$tag[ 'node' ] = $value;
			$tag[ 'tag' ]  = strtolower( $matches[ 1 ][ $index ] );
			$tag[ 'if_not_empty' ] 	= preg_match( "/if_not_empty/i", $value );
			$tag[ 'before' ]    	= ( preg_match( "/before\s*=\s*\{\{((?:(?!\}\}).)*)\}\}/i",  $value, $match ) ) ? $match[ 1 ] : '';
			$tag[ 'after' ]   		= ( preg_match( "/after\s*=\s*\{\{((?:(?!\}\}).)*)\}\}/i", $value, $match ) ) ? $match[ 1 ] : '';
			$tag[ 'separator' ]    	= ( preg_match( "/separator\s*=\s*\{\{((?:(?!\}\}).)*)\}\}/i",  $value, $match ) ) ? $match[ 1 ] : '';

			$baseTag = ( preg_match( "/(fieldname\d+)_(label|value|shortlabel)/i", $tag[ 'tag' ], $match ) ) ? $match[ 1 ] : $tag[ 'tag' ];

			if( empty( $tags_arr[ $baseTag ] ) ) $tags_arr[ $baseTag ] = array();
			$tags_arr[ $baseTag ][] = $tag;
		}
	}
	return $tags_arr;
}

function _cpappb_replace_vars( $fields, $params, $message, $buffer = '', $contentType = 'html', $itemnumber = '' )
{
    // not needed at this point
}



?>