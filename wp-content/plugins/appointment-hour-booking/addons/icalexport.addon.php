<?php
/*

*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPAPPB_iCalExport' ) )
{
    class CPAPPB_iCalExport extends CPAPPB_BaseAddon
    {

        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-iCalExport-20170903";
		protected $name = "iCal Export Addon";
		protected $description;
        public $category = ' Add-ons included in this plugin version';
        public $help = 'https://apphourbooking.dwbooster.com/blog/2018/12/19/adding-google-iphone-outlook/';  
        
        private $timezones = array('Africa/Abidjan','Africa/Accra','Africa/Addis_Ababa','Africa/Algiers','Africa/Asmara','Africa/Bamako','Africa/Bangui','Africa/Banjul','Africa/Bissau','Africa/Blantyre','Africa/Brazzaville','Africa/Bujumbura','Africa/Cairo','Africa/Casablanca','Africa/Ceuta','Africa/Conakry','Africa/Dakar','Africa/Dar_es_Salaam','Africa/Djibouti','Africa/Douala','Africa/El_Aaiun','Africa/Freetown','Africa/Gaborone','Africa/Harare','Africa/Johannesburg','Africa/Juba','Africa/Kampala','Africa/Khartoum','Africa/Kigali','Africa/Kinshasa','Africa/Lagos','Africa/Libreville','Africa/Lome','Africa/Luanda','Africa/Lubumbashi','Africa/Lusaka','Africa/Malabo','Africa/Maputo','Africa/Maseru','Africa/Mbabane','Africa/Mogadishu','Africa/Monrovia','Africa/Nairobi','Africa/Ndjamena','Africa/Niamey','Africa/Nouakchott','Africa/Ouagadougou','Africa/Porto-Novo','Africa/Sao_Tome','Africa/Tripoli','Africa/Tunis','Africa/Windhoek','America/Adak','America/Anchorage','America/Anguilla','America/Antigua','America/Araguaina','America/Argentina/Buenos_Aires','America/Argentina/Catamarca','America/Argentina/Cordoba','America/Argentina/Jujuy','America/Argentina/La_Rioja','America/Argentina/Mendoza','America/Argentina/Rio_Gallegos','America/Argentina/Salta','America/Argentina/San_Juan','America/Argentina/San_Luis','America/Argentina/Tucuman','America/Argentina/Ushuaia','America/Aruba','America/Asuncion','America/Atikokan','America/Bahia','America/Bahia_Banderas','America/Barbados','America/Belem','America/Belize','America/Blanc-Sablon','America/Boa_Vista','America/Bogota','America/Boise','America/Cambridge_Bay','America/Campo_Grande','America/Cancun','America/Caracas','America/Cayenne','America/Cayman','America/Chicago','America/Chihuahua','America/Costa_Rica','America/Creston','America/Cuiaba','America/Curacao','America/Danmarkshavn','America/Dawson','America/Dawson_Creek','America/Denver','America/Detroit','America/Dominica','America/Edmonton','America/Eirunepe','America/El_Salvador','America/Fort_Nelson','America/Fortaleza','America/Glace_Bay','America/Goose_Bay','America/Grand_Turk','America/Grenada','America/Guadeloupe','America/Guatemala','America/Guayaquil','America/Guyana','America/Halifax','America/Havana','America/Hermosillo','America/Indiana/Indianapolis','America/Indiana/Knox','America/Indiana/Marengo','America/Indiana/Petersburg','America/Indiana/Tell_City','America/Indiana/Vevay','America/Indiana/Vincennes','America/Indiana/Winamac','America/Inuvik','America/Iqaluit','America/Jamaica','America/Juneau','America/Kentucky/Louisville','America/Kentucky/Monticello','America/Kralendijk','America/La_Paz','America/Lima','America/Los_Angeles','America/Lower_Princes','America/Maceio','America/Managua','America/Manaus','America/Marigot','America/Martinique','America/Matamoros','America/Mazatlan','America/Menominee','America/Merida','America/Metlakatla','America/Mexico_City','America/Miquelon','America/Moncton','America/Monterrey','America/Montevideo','America/Montserrat','America/Nassau','America/New_York','America/Nipigon','America/Nome','America/Noronha','America/North_Dakota/Beulah','America/North_Dakota/Center','America/North_Dakota/New_Salem','America/Nuuk','America/Ojinaga','America/Panama','America/Pangnirtung','America/Paramaribo','America/Phoenix','America/Port_of_Spain','America/Port-au-Prince','America/Porto_Velho','America/Puerto_Rico','America/Punta_Arenas','America/Rainy_River','America/Rankin_Inlet','America/Recife','America/Regina','America/Resolute','America/Rio_Branco','America/Santarem','America/Santiago','America/Santo_Domingo','America/Sao_Paulo','America/Scoresbysund','America/Sitka','America/St_Barthelemy','America/St_Johns','America/St_Kitts','America/St_Lucia','America/St_Thomas','America/St_Vincent','America/Swift_Current','America/Tegucigalpa','America/Thule','America/Thunder_Bay','America/Tijuana','America/Toronto','America/Tortola','America/Vancouver','America/Whitehorse','America/Winnipeg','America/Yakutat','America/Yellowknife','Antarctica/Casey','Antarctica/Davis','Antarctica/DumontDUrville','Antarctica/Macquarie','Antarctica/Mawson','Antarctica/McMurdo','Antarctica/Palmer','Antarctica/Rothera','Antarctica/Syowa','Antarctica/Troll','Antarctica/Vostok','Arctic/Longyearbyen','Asia/Aden','Asia/Almaty','Asia/Amman','Asia/Anadyr','Asia/Aqtau','Asia/Aqtobe','Asia/Ashgabat','Asia/Atyrau','Asia/Baghdad','Asia/Bahrain','Asia/Baku','Asia/Bangkok','Asia/Barnaul','Asia/Beirut','Asia/Bishkek','Asia/Brunei','Asia/Chita','Asia/Choibalsan','Asia/Colombo','Asia/Damascus','Asia/Dhaka','Asia/Dili','Asia/Dubai','Asia/Dushanbe','Asia/Famagusta','Asia/Gaza','Asia/Hebron','Asia/Ho_Chi_Minh','Asia/Hong_Kong','Asia/Hovd','Asia/Irkutsk','Asia/Jakarta','Asia/Jayapura','Asia/Jerusalem','Asia/Kabul','Asia/Kamchatka','Asia/Karachi','Asia/Kathmandu','Asia/Khandyga','Asia/Kolkata','Asia/Krasnoyarsk','Asia/Kuala_Lumpur','Asia/Kuching','Asia/Kuwait','Asia/Macau','Asia/Magadan','Asia/Makassar','Asia/Manila','Asia/Muscat','Asia/Nicosia','Asia/Novokuznetsk','Asia/Novosibirsk','Asia/Omsk','Asia/Oral','Asia/Phnom_Penh','Asia/Pontianak','Asia/Pyongyang','Asia/Qatar','Asia/Qostanay','Asia/Qyzylorda','Asia/Riyadh','Asia/Sakhalin','Asia/Samarkand','Asia/Seoul','Asia/Shanghai','Asia/Singapore','Asia/Srednekolymsk','Asia/Taipei','Asia/Tashkent','Asia/Tbilisi','Asia/Tehran','Asia/Thimphu','Asia/Tokyo','Asia/Tomsk','Asia/Ulaanbaatar','Asia/Urumqi','Asia/Ust-Nera','Asia/Vientiane','Asia/Vladivostok','Asia/Yakutsk','Asia/Yangon','Asia/Yekaterinburg','Asia/Yerevan','Atlantic/Azores','Atlantic/Bermuda','Atlantic/Canary','Atlantic/Cape_Verde','Atlantic/Faroe','Atlantic/Madeira','Atlantic/Reykjavik','Atlantic/South_Georgia','Atlantic/St_Helena','Atlantic/Stanley','Australia/Adelaide','Australia/Brisbane','Australia/Broken_Hill','Australia/Darwin','Australia/Eucla','Australia/Hobart','Australia/Lindeman','Australia/Lord_Howe','Australia/Melbourne','Australia/Perth','Australia/Sydney','Europe/Amsterdam','Europe/Andorra','Europe/Astrakhan','Europe/Athens','Europe/Belgrade','Europe/Berlin','Europe/Bratislava','Europe/Brussels','Europe/Bucharest','Europe/Budapest','Europe/Busingen','Europe/Chisinau','Europe/Copenhagen','Europe/Dublin','Europe/Gibraltar','Europe/Guernsey','Europe/Helsinki','Europe/Isle_of_Man','Europe/Istanbul','Europe/Jersey','Europe/Kaliningrad','Europe/Kiev','Europe/Kirov','Europe/Lisbon','Europe/Ljubljana','Europe/London','Europe/Luxembourg','Europe/Madrid','Europe/Malta','Europe/Mariehamn','Europe/Minsk','Europe/Monaco','Europe/Moscow','Europe/Oslo','Europe/Paris','Europe/Podgorica','Europe/Prague','Europe/Riga','Europe/Rome','Europe/Samara','Europe/San_Marino','Europe/Sarajevo','Europe/Saratov','Europe/Simferopol','Europe/Skopje','Europe/Sofia','Europe/Stockholm','Europe/Tallinn','Europe/Tirane','Europe/Ulyanovsk','Europe/Uzhgorod','Europe/Vaduz','Europe/Vatican','Europe/Vienna','Europe/Vilnius','Europe/Volgograd','Europe/Warsaw','Europe/Zagreb','Europe/Zaporozhye','Europe/Zurich','Indian/Antananarivo','Indian/Chagos','Indian/Christmas','Indian/Cocos','Indian/Comoro','Indian/Kerguelen','Indian/Mahe','Indian/Maldives','Indian/Mauritius','Indian/Mayotte','Indian/Reunion','Pacific/Apia','Pacific/Auckland','Pacific/Bougainville','Pacific/Chatham','Pacific/Chuuk','Pacific/Easter','Pacific/Efate','Pacific/Fakaofo','Pacific/Fiji','Pacific/Funafuti','Pacific/Galapagos','Pacific/Gambier','Pacific/Guadalcanal','Pacific/Guam','Pacific/Honolulu','Pacific/Kanton','Pacific/Kiritimati','Pacific/Kosrae','Pacific/Kwajalein','Pacific/Majuro','Pacific/Marquesas','Pacific/Midway','Pacific/Nauru','Pacific/Niue','Pacific/Norfolk','Pacific/Noumea','Pacific/Pago_Pago','Pacific/Palau','Pacific/Pitcairn','Pacific/Pohnpei','Pacific/Port_Moresby','Pacific/Rarotonga','Pacific/Saipan','Pacific/Tahiti','Pacific/Tarawa','Pacific/Tongatapu','Pacific/Wake','Pacific/Wallis');

		public function get_addon_form_settings( $form_id )
		{
			global $wpdb, $cp_appb_plugin;

            $cp_appb_plugin->add_field_verify ($wpdb->prefix.$this->form_table, 'attachical');
            $cp_appb_plugin->add_field_verify ($wpdb->prefix.$this->form_table, 'base_summary');
            $cp_appb_plugin->add_field_verify ($wpdb->prefix.$this->form_table, 'base_description');
            $cp_appb_plugin->add_field_verify ($wpdb->prefix.$this->form_table, 'cal_tzid');

			// Insertion in database
			if(
				isset( $_REQUEST[ 'CPAPPB_icalexport_id' ] )
			)
			{
			    $wpdb->delete( $wpdb->prefix.$this->form_table, array( 'formid' => $form_id ), array( '%d' ) );
				$wpdb->insert(
								$wpdb->prefix.$this->form_table,
								array(
									'formid' => $form_id,

									'observe_day_light'	 => $_REQUEST["observe_day_light"],
									'ical_daylight_zone'	 => $_REQUEST["ical_daylight_zone"],
									'cal_time_zone_modify'	 => $_REQUEST["cal_time_zone_modify"],
                                    'attachical'	 => $_REQUEST["attachical"],
                                    'base_summary'	 => $_REQUEST["base_summary"],
                                    'base_description'	 => $_REQUEST["base_description"],
                                    'cal_tzid'	 => $_REQUEST["cal_tzid"]

								),
								array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
							);
			}

			$rows = $wpdb->get_results(
						$wpdb->prepare( "SELECT * FROM ".$wpdb->prefix.$this->form_table." WHERE formid=%d", $form_id )
					);
			if (!count($rows))
			{
			    $row["observe_day_light"] = "true";
			    $row["ical_daylight_zone"] = "EUROPE";
                $row["cal_time_zone_modify"] = '';
                $row["attachical"] = '';
                $row["cal_tzid"] = '';
                $row["base_summary"] = 'Booking for %email%';
                $row["base_description"] = 'Booking for %email%';
			} else {
			    $row["observe_day_light"] = $rows[0]->observe_day_light;
			    $row["ical_daylight_zone"] = $rows[0]->ical_daylight_zone;
                $row["cal_time_zone_modify"] = $rows[0]->cal_time_zone_modify;
                $row["attachical"] = $rows[0]->attachical;
                $row["cal_tzid"] = $rows[0]->cal_tzid;
                $row["base_summary"] = $rows[0]->base_summary;
                $row["base_description"] = $rows[0]->base_description;
			}
			?>
			<div id="metabox_basic_settings" class="postbox" >
				<h3 class='hndle' style="padding:5px;"><span><?php print $this->name; ?></span></h3>
				<div class="inside">
				   <input type="hidden" name="CPAPPB_icalexport_id" value="1" />
                   iCal link:
                   <div style="border:1px dotted black;padding:5px;">
                    <a href="<?php echo $this->getiCalLink($form_id); ?>"><?php echo $this->getiCalLink($form_id); ?></a>
                   </div>
                   <p>To export the iCal link with Google Calendar on a regular basis, please read the instructions on this Google page:</p>
                   <p><a href="https://support.google.com/calendar/answer/37100?hl=en">https://support.google.com/calendar/answer/37100?hl=en</a></p>
                   <p>This will automatically export the bookings stored in the plugin to the Google Calendar (one way sync).</p>
                   <hr />
                   <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><?php _e('iCal timezone difference', 'appointment-hour-booking'); ?></th>
                    <td><select name="cal_time_zone_modify">
                          <option value="">- none -</option>
                          <?php 
                            for ($i=-23;$i<24; $i++) 
                            { 
                              $text = " ".($i<=0?"":"+").$i." hours"; $text2 = " ".($i<=0?"+".(-1*$i):"-".$i).":00 hours";
                              $cc = '<option value="'.$text.'" '.($row["cal_time_zone_modify"] == $text?' selected':'').'>'.$text2.'</option>';
                              $text = " ".($i<=0?"":"+").$i.":30 hours"; $text2 = " ".($i<=0?"+".(-1*$i):"-".$i).":30 hours";
                              $ch = '<option value="'.$text.'" '.($row["cal_time_zone_modify"] == $text?' selected':'').'>'.$text2.'</option>';
                              if ($i>=0) echo $cc.$ch; else echo $ch.$cc;
                            }
                          ?>
                         </select>
                         <br /><em>Note: Based in your computer timezone the recommended setting for this field is <strong><span id="ahbrectime"></span> hours</strong></em>
                    </td>
                    </tr>
                    
                    
                    <tr valign="top">
                    <th scope="row"><?php _e('Include timezone ID in iCal file?', 'appointment-hour-booking'); ?></th>
                    <td><select name="cal_tzid">
                        <?php $tzone =  wp_timezone_string(); if (substr($tzone,0,1) == "+" || substr($tzone,0,1) == "-") $tzone = "GMT".$tzone; ?>
                          <option value="" <?php if ($row["cal_tzid"] == '') echo ' selected'; ?>>- No, don't include it - (select this if you are not sure)</option>
                          <?php foreach ($this->timezones as $value) { ?>
                          <option value="<?php echo $value; ?>"  <?php if ($row["cal_tzid"] == $value) echo ' selected'; ?>><?php echo $value; ?></option>
                          <?php } ?>
                         </select>
                         <br /><em>Note: Use this adjusting also the above field for the iCal timezone difference.</em>
                    </td>
                    </tr>

                    
                    <tr valign="top">
                    <th scope="row"><?php _e('Observe daylight saving time?', 'appointment-hour-booking'); ?></th>
                    <td><select name="observe_day_light">
                          <option value="true" <?php if ($row["observe_day_light"] == '' || $row["observe_day_light"] == 'true') echo ' selected'; ?>>Yes</option>
                          <option value="false" <?php if ($row["observe_day_light"] == 'false') echo ' selected'; ?>>No</option>
                         </select>
                    </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><?php _e('Daylight saving time zone', 'appointment-hour-booking'); ?></th>
                    <td><select name="ical_daylight_zone">
                          <option value="EUROPE" <?php if ($row["ical_daylight_zone"] == '' || $row["ical_daylight_zone"] == 'EUROPE') echo ' selected'; ?>>Europe</option>
                          <option value="USA" <?php if ($row["ical_daylight_zone"] == 'USA') echo ' selected'; ?>>USA</option>
                          <option value="AU" <?php if ($row["ical_daylight_zone"] == 'AU') echo ' selected'; ?>>AUSTRALIA</option>
                          <option value="NZ" <?php if ($row["ical_daylight_zone"] == 'NZ') echo ' selected'; ?>>NEW ZEALAND</option>
                         </select>
                    </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><?php _e('Attach iCal file to notification emails?', 'appointment-hour-booking'); ?></th>
                    <td><select name="attachical">
                          <option value="0" <?php if ($row["attachical"] == '' || $row["attachical"] == '0') echo ' selected'; ?>>No</option>
                          <option value="1" <?php if ($row["attachical"] == '1') echo ' selected'; ?>>Yes - for all emails (excluding cancelled and rejected items)</option>
                          <option value="2" <?php if ($row["attachical"] == '2') echo ' selected'; ?>>Yes - for approved items only</option>
                         </select>
                         <br />
                         <em>* Important note: The folder "/wp-content/uploads/" must exist and have enough permissions to generate the iCal file to be attached.</em>
                    </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><?php _e('iCal entry summary', 'appointment-hour-booking'); ?></th>
                    <td><textarea name="base_summary"><?php echo esc_textarea($row["base_summary"]); ?></textarea>
                         <br />
                         <em>* Note: You can get the field IDs/tags from the form builder.</em>
                    </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><?php _e('iCal entry description', 'appointment-hour-booking'); ?></th>
                    <td><textarea name="base_description"><?php echo esc_textarea($row["base_description"]); ?></textarea>
                         <br />
                         <em>* Note: You can get the field IDs/tags from the form builder.</em>
                    </td>
                    </tr>
                  </table>
				</div>
			</div>
            <script>
function ahbstartTime() {
  var today = new Date();
  var diff = -1*parseInt(today.getTimezoneOffset()/60);
  if (diff >0) diff = "+"+diff;
  document.getElementById("ahbrectime").innerHTML = diff;
}
ahbstartTime();
            </script>
			<?php
		} // end get_addon_form_settings



		/************************ ADDON CODE *****************************/

        /************************ ATTRIBUTES *****************************/

        private $form_table = 'cpappbk_icalexport';
        private $_inserted = false;

        /************************ CONSTRUCT *****************************/

        function __construct()
        {
			$this->description = __("The add-on adds support for exporting iCal files.", 'appointment-hour-booking' );
            // Check if the plugin is active
			if( !$this->addon_is_active() ) return;

			add_action( 'init', array( &$this, 'pp_iCalExport_update_status' ), 10, 1 );

            add_filter( 'cpappb_email_attachments', array( &$this, 'attach_ical_file' ), 10, 3 );

            $this->update_database();

            $passcode = get_option('CPAHB_PASSCODE',"");
            if ($passcode == '')
            {
                $passcode = wp_generate_uuid4();
                update_option( 'CPAHB_PASSCODE', $passcode);
            }

        } // End __construct


        		/**
         * Create the database tables
         */
        protected function update_database()
		{
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix.$this->form_table." (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					formid INT NOT NULL,
					cal_time_zone_modify varchar(255) DEFAULT '' NOT NULL ,
                    observe_day_light varchar(255) DEFAULT '' NOT NULL ,
                    ical_daylight_zone varchar(255) DEFAULT '' NOT NULL ,
                    attachical varchar(10) DEFAULT '' NOT NULL ,
                    base_summary TEXT DEFAULT '' NOT NULL ,
                    base_description TEXT DEFAULT '' NOT NULL ,
                    cal_tzid TEXT DEFAULT '' NOT NULL ,
					UNIQUE KEY id (id)
				) $charset_collate;";

			$wpdb->query($sql);
		} // end update_database

        /************************ PRIVATE METHODS *****************************/

        private function getiCalLink($form_id)
        {
            global $cp_appb_plugin;
            return $cp_appb_plugin->get_site_url()."?cpappb_app=calfeed&id=".$form_id."&verify=".substr(md5($form_id.get_option('CPAHB_PASSCODE',"")),0,10);
        }

		/**
		 * mark the item as paid
		 */
		private function _log($adarray = array())
		{
			$h = fopen( __DIR__.'/logs.txt', 'a' );
			$log = "";
			foreach( $_REQUEST as $KEY => $VAL )
			{
				$log .= $KEY.": ".$VAL."\n";
			}
			foreach( $adarray as $KEY => $VAL )
			{
				$log .= $KEY.": ".$VAL."\n";
			}
			$log .= "================================================\n";
			fwrite( $h, $log );
			fclose( $h );
		}

		public function pp_iCalExport_update_status( )
		{
            global $wpdb, $cp_appb_plugin;

            if (!isset($_GET["cpappb_app"]) || $_GET["cpappb_app"] != 'calfeed')
                return;

            if (isset($_GET["id"]) && isset($_GET["verify"]) && substr(md5($_GET["id"].get_option('CPAHB_PASSCODE',"")),0,10) == $_GET["verify"])
                $this->export_iCal(intval($_GET["id"]));
            else
                echo 'Access denied - verify value is not correct.';
            exit ();
		}

       	private function export_iCal( $form_id, $date_from = 'today -1 month', $date_to = 'today +10 years')
		{
            global $wpdb, $cp_appb_plugin;

            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=events".date("Y-M-D_H.i.s").".ics");

            $updatefeaturetime = strtotime('2019-03-05');

            echo "BEGIN:VCALENDAR\n";
            echo "PRODID:-//CodePeople//Appointment Hour Booking for WordPress//EN\n";
            echo "VERSION:2.0\n";

            $icalSettings = $wpdb->get_results(
						$wpdb->prepare( "SELECT * FROM ".$wpdb->prefix.$this->form_table." WHERE formid=%d", $form_id )
					);

            $from = date("Y-m-d",strtotime($date_from));
            $to = date("Y-m-d",strtotime($date_to));

            $rows = $wpdb->get_results( $wpdb->prepare("SELECT id,time,notifyto,posted_data,data,ipaddr FROM ".$wpdb->prefix.$cp_appb_plugin->table_messages." WHERE notifyto<>'".esc_sql($cp_appb_plugin->blocked_by_admin_indicator)."' AND ".($form_id?'formid='.intval($form_id).' AND ':'')."time<=%s ORDER BY time DESC LIMIT 0,1000", $to) );

            $blockedstatuses = explode(",", get_option('cp_cpappb_statuses_block',''));
            $blockedstatuses[] = '';

            foreach($rows as $item)
                if ($item->ipaddr != 'remote-ical-file')
                {
                    $data = unserialize($item->posted_data);
                    $ct = 0;
                    foreach($data["apps"] as $app)
                        if (
                            $app["date"] >= $from && $app["date"] <= $to
                            && (!isset($app["cancelled"]) || in_array($app["cancelled"],$blockedstatuses))
                           )
                        {
                            $ct++;
                            $time = explode("/", $app["slot"]);
                            $datetime = $app["date"]." ".trim($time[0]);
                            $duration = "+".$app["duration"]." minutes";
                            $submissiontime = strtotime($item->time);
                            if ($icalSettings[0]->observe_day_light)
                            {
                                $full_date = gmdate("Ymd",strtotime($datetime.$icalSettings[0]->cal_time_zone_modify));
                                $year = substr($full_date,0,4);
                                
                                if (strtoupper($icalSettings[0]->ical_daylight_zone) == 'AU')
                                {
                                    $dst_start = strtotime('first Sunday GMT', strtotime("1 April $year GMT")); // First Sunday April 
                                    $dst_stop = strtotime('first Sunday GMT', strtotime("1 October $year GMT")); // First Sunday September
                                } else if (strtoupper($icalSettings[0]->ical_daylight_zone) == 'NZ')
                                {
                                    $dst_start = strtotime('first Sunday GMT', strtotime("1 April $year GMT")); // First Sunday April 
                                    $dst_stop = strtotime('last Sunday GMT', strtotime("1 October $year GMT")); // Last Sunday September
                                } if (strtoupper($icalSettings[0]->ical_daylight_zone) == 'EUROPE')
                                {
                                    $dst_start = strtotime('last Sunday GMT', strtotime("1 April $year GMT")); // Last Sunday March 
                                    $dst_stop = strtotime('last Sunday GMT', strtotime("1 November $year GMT")); // Last Sunday October
                                } else { // USA
                                    $dst_start = strtotime('second Sunday GMT', strtotime("1 March $year GMT")); // Second Sunday March 
                                    $dst_stop = strtotime('first Sunday GMT', strtotime("1 November $year GMT")); // First Sunday November
                                }
                                if ($full_date >= gmdate("Ymd",$dst_start) && $full_date < gmdate("Ymd",$dst_stop))
                                    $datetime = date("Y-m-d H:i",strtotime($datetime." -1 hour"));
                            }

                            $base_summary = $icalSettings[0]->base_summary;
                            $base_description = $icalSettings[0]->base_description;

                            $base_summary = str_replace ('<%', '%', $base_summary);
                            $base_description = str_replace ('<%', '%', $base_description);
                            $base_summary = str_replace ('%>', '%', $base_summary);
                            $base_description = str_replace ('%>', '%', $base_description);

                            $base_summary = str_replace('%apps%', $cp_appb_plugin->get_appointments_text($data["apps"]), $base_summary);
                            $base_description = str_replace('%apps%', $cp_appb_plugin->get_appointments_text($data["apps"]), $base_description);
                            foreach ($data as $itemgt => $value)
                            {
                                $base_summary = str_replace('%'.$itemgt.'%',(is_array($value)?(implode(", ",$value)):($value)),$base_summary);
                                $base_description = str_replace('%'.$itemgt.'%',(is_array($value)?(implode(", ",$value)):($value)),$base_description);
                            }

                            $base_summary = $cp_appb_plugin->replace_tags($base_summary, $data, false, $ct-1);
                            $base_description = $cp_appb_plugin->replace_tags($base_description, $data, false, $ct-1);

                            $base_summary = str_replace("<br>",'\n',str_replace("<br />",'\n',str_replace("\r",'',str_replace("\n",'\n',$base_summary)) ));
                            $base_description = str_replace("<br>",'\n',str_replace("<br />",'\n',str_replace("\r",'',str_replace("\n",'\n',$base_description)) ));

                            echo "BEGIN:VEVENT\n";
                            echo "DTSTART".($icalSettings[0]->cal_tzid != '' ? ';TZID='.$icalSettings[0]->cal_tzid : '').":".gmdate("Ymd",strtotime($datetime.$icalSettings[0]->cal_time_zone_modify))."T".gmdate("His",strtotime($datetime.$icalSettings[0]->cal_time_zone_modify))."Z\n";
                            echo "DTEND".($icalSettings[0]->cal_tzid != '' ? ';TZID='.$icalSettings[0]->cal_tzid : '').":".gmdate("Ymd",strtotime($datetime.$icalSettings[0]->cal_time_zone_modify.$duration))."T".gmdate("His",strtotime($datetime.$icalSettings[0]->cal_time_zone_modify.$duration))."Z\n";
                            echo "DTSTAMP".($icalSettings[0]->cal_tzid != '' ? ';TZID='.$icalSettings[0]->cal_tzid : '').":".gmdate("Ymd",$submissiontime)."T".gmdate("His",$submissiontime)."Z\n";
                            echo "UID:uid".$item->id.'_'.$ct."@".$_SERVER["SERVER_NAME"].($submissiontime > $updatefeaturetime?$form_id:'')."\n";
                            echo "DESCRIPTION:".sanitize_textarea_field($base_description)."\n";
                            echo "LAST-MODIFIED".($icalSettings[0]->cal_tzid != '' ? ';TZID='.$icalSettings[0]->cal_tzid : '').":".gmdate("Ymd",$submissiontime)."T".gmdate("His",$submissiontime)."Z\n";
                            echo "LOCATION:\n";
                            echo "SEQUENCE:0\n";
                            echo "STATUS:CONFIRMED\n";
                            echo "SUMMARY:".$base_summary."\n";
                            echo "TRANSP:OPAQUE\n";
                            echo "END:VEVENT\n";
                        }
                }

            echo 'END:VCALENDAR';
            exit ();
		}


        function attach_ical_file( $attachments, $params, $form_id)
        {
            global $wpdb, $cp_appb_plugin;

            $updatefeaturetime = strtotime('2019-03-05');

            $icalSettings = $wpdb->get_results(
						$wpdb->prepare( "SELECT * FROM ".$wpdb->prefix.$this->form_table." WHERE formid=%d", $form_id )
					);

            if ($icalSettings[0]->attachical != '1' && $icalSettings[0]->attachical != '2')
                return $attachments;

            $data = $params;
            $ct = 0;
            foreach($data["apps"] as $app)
                if
                  (
                   (strtolower($app["cancelled"]) != 'cancelled') &&
                   (strtolower($app["cancelled"]) != 'Cancelled by customer') &&
                   (strtolower($app["cancelled"]) != 'Rejected') &&
                   ($icalSettings[0]->attachical != '2' || $app["cancelled"] == '' || strtolower($app["cancelled"]) == 'approved')
                  )
                {
                    $ct++;
                    $time = explode("/", $app["slot"]);
                    $datetime = $app["date"]." ".trim($time[0]);
                    $duration = "+".$app["duration"]." minutes";
                    $submissiontime = time();
                    if ($icalSettings[0]->observe_day_light)
                    {
                        $full_date = gmdate("Ymd",strtotime($datetime.$icalSettings[0]->cal_time_zone_modify));
                        $year = substr($full_date,0,4);
                        if (strtoupper($icalSettings[0]->ical_daylight_zone) == 'EUROPE')
                        {
                            $dst_start = strtotime('last Sunday GMT', strtotime("1 April $year GMT"));
                            $dst_stop = strtotime('last Sunday GMT', strtotime("1 November $year GMT"));
                        } else { // USA
                            $dst_start = strtotime('first Sunday GMT', strtotime("1 April $year GMT"));
                            $dst_stop = strtotime('last Sunday GMT', strtotime("1 November $year GMT"));
                        }
                        if ($full_date >= gmdate("Ymd",$dst_start) && $full_date < gmdate("Ymd",$dst_stop))
                            $datetime = date("Y-m-d H:i",strtotime($datetime." -1 hour"));
                    }

                    $base_summary = $icalSettings[0]->base_summary;
                    $base_description = $icalSettings[0]->base_description;

                    $base_summary = str_replace ('<%', '%', $base_summary);
                    $base_description = str_replace ('<%', '%', $base_description);
                    $base_summary = str_replace ('%>', '%', $base_summary);
                    $base_description = str_replace ('%>', '%', $base_description);

                    $base_summary = str_replace('%apps%', $cp_appb_plugin->get_appointments_text($data["apps"]), $base_summary);
                    $base_description = str_replace('%apps%', $cp_appb_plugin->get_appointments_text($data["apps"]), $base_description);
                    foreach ($data as $itemgt => $value)
                    {
                        $base_summary = str_replace('%'.$itemgt.'%',@(is_array($value)?(implode(", ",$value)):($value)),$base_summary);
                        $base_description = str_replace('%'.$itemgt.'%',@(is_array($value)?(implode(", ",$value)):($value)),$base_description);
                    }

                    $base_summary = $cp_appb_plugin->replace_tags($base_summary, $data, false, $ct-1);
                    $base_description = $cp_appb_plugin->replace_tags($base_description, $data, false, $ct-1);

                    $base_summary = str_replace("<br>",'\n',str_replace("<br />",'\n',str_replace("\r",'',str_replace("\n",'\n',$base_summary)) ));
                    $base_description = str_replace("<br>",'\n',str_replace("<br />",'\n',str_replace("\r",'',str_replace("\n",'\n',$base_description)) ));

                    $buffer  = "BEGIN:VCALENDAR\n";
                    $buffer .= "PRODID:-//CodePeople//Appointment Hour Booking for WordPress//EN\n";
                    $buffer .= "VERSION:2.0\n";
                    $buffer .= "X-MS-OLK-FORCEINSPECTOROPEN:TRUE\n";
            
                    $buffer .= "BEGIN:VEVENT\n";
                    $buffer .= "DTSTART:".gmdate("Ymd",strtotime($datetime.$icalSettings[0]->cal_time_zone_modify))."T".gmdate("His",strtotime($datetime.$icalSettings[0]->cal_time_zone_modify))."Z\n";
                    $buffer .= "DTEND:".gmdate("Ymd",strtotime($datetime.$icalSettings[0]->cal_time_zone_modify.$duration))."T".gmdate("His",strtotime($datetime.$icalSettings[0]->cal_time_zone_modify.$duration))."Z\n";
                    $buffer .= "DTSTAMP:".gmdate("Ymd",$submissiontime)."T".gmdate("His",$submissiontime)."Z\n";
                    $buffer .= "UID:uid".$params["itemnumber"].'_'.$ct."@".$_SERVER["SERVER_NAME"].($submissiontime > $updatefeaturetime?$form_id:'')."\n";
                    $buffer .= "DESCRIPTION:".$base_description."\n";
                    $buffer .= "LAST-MODIFIED:".gmdate("Ymd",$submissiontime)."T".gmdate("His",$submissiontime)."Z\n";
                    $buffer .= "LOCATION:\n";
                    $buffer .= "SEQUENCE:0\n";
                    $buffer .= "STATUS:CONFIRMED\n";
                    $buffer .= "ORGANIZER;CN=\"".$_SERVER["HTTP_HOST"]."\":MAILTO:".$cp_appb_plugin->get_option('fp_from_email')."\r\n";
                    $buffer .= "SUMMARY:".$base_summary."\n";
                    $buffer .= "TRANSP:OPAQUE\n";
                    $buffer .= "END:VEVENT\n";
                    
                    $buffer .= 'END:VCALENDAR';
                    
                    $filename1 = sanitize_file_name('Appointment'.'_'.$params["itemnumber"].'_'.$ct.'.ics');
                    $filename1 = WP_CONTENT_DIR . '/uploads/'.$filename1;
                    $handle = fopen($filename1, 'w');
                    fwrite($handle,$buffer);
                    fclose($handle);
                    $attachments[] = $filename1;                   
                    
                }                     

            return $attachments;
        }



    } // End Class

    // Main add-on code
    $cpappb_iCalExport_obj = new CPAPPB_iCalExport();

	// Add addon object to the objects list
	global $cpappb_addons_objs_list;
	$cpappb_addons_objs_list[ $cpappb_iCalExport_obj->get_addon_id() ] = $cpappb_iCalExport_obj;
}

