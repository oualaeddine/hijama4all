<?php


$this->item = intval($_GET["cal"]);

$current_user = wp_get_current_user();
$current_user_access = current_user_can('edit_pages');

if ( !is_admin() || (!$current_user_access && !@in_array($current_user->ID, unserialize($this->get_option("cp_user_access","")))))
{
    echo 'Direct access not allowed.';
    exit;
}

$firstday = 0;
if ($this->item != 0)
{
    $myform = $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.$this->table_items .' WHERE id=%d' ,$this->item) );
    $raw_form_str = cpappb_cleanJSON( $myform[0]->form_structure );
    $form_data = json_decode( $raw_form_str );
    foreach($form_data[0] as $item)
    {
        if ($item->ftype == 'fapp')
        {
            $firstday = $item->firstDay;
            break;
        }
    }
}

$default_from = date("Y-m-d",strtotime("today -10 days"));
$default_to = date("Y-m-d",strtotime("today +30 days"));

$rawfrom = (isset($_GET["dfrom"]) ? sanitize_text_field($_GET["dfrom"]) : '');
$rawto = (isset($_GET["dto"]) ? sanitize_text_field(@$_GET["dto"]) : '');

if ($this->get_option('date_format', 'mm/dd/yy') == 'dd/mm/yy' || $this->get_option('date_format', 'mm/dd/yy') == 'd M, y')
{
    $rawfrom = str_replace('/','.',$rawfrom);
    $rawto = str_replace('/','.',$rawto);
}

$dfrom = ($rawfrom ? date("Y-m-d", strtotime($rawfrom)) : $default_from);
$dto = ($rawto ? date("Y-m-d", strtotime($rawto)) : $default_to);

if ($this->get_option('date_format', 'mm/dd/yy') == 'd M, y')
{
	$dfrom_formatted = date("d/m/Y", strtotime($dfrom));
    $dto_formatted = date("d/m/Y", strtotime($dto));
}
else
{
    $dfrom_formatted = $this->format_date($dfrom);
    $dto_formatted = $this->format_date($dto);
}

?>


<h1><?php _e('Schedule','appointment-hour-booking'); ?> - <?php if ($this->item != 0) echo esc_html($myform[0]->form_name); else echo 'All forms'; ?></h1>

<div class="ahb-buttons-container">
    
    <?php if (!isset($_GET["calendarview"])) { ?>
     <input type="button" value="<?php _e('Change to Schedule Calendar View','appointment-hour-booking'); ?>" class="button button-primary" onclick="document.location='?page=<?php echo $this->menu_parameter; ?>&cal=<?php echo $this->item; ?>&schedule=1&calendarview=1';" />  
    <?php } else { ?>
     <input type="button" value="<?php _e('Change to Schedule List View','appointment-hour-booking'); ?>" class="button button-primary" onclick="document.location='?page=<?php echo $this->menu_parameter; ?>&cal=<?php echo $this->item; ?>&schedule=1';" />
    <?php } ?>    
	<a href="<?php print esc_attr(admin_url('admin.php?page='.$this->menu_parameter));?>" class="ahb-return-link">&larr;<?php _e('Return to the calendars list','appointment-hour-booking'); ?></a>
	<div class="clear"></div>
</div>

<?php if (!isset($_GET["calendarview"])) { ?>

<div class="ahb-section-container">
	<div class="ahb-section">
      <form action="admin.php" method="get">
        <input type="hidden" name="page" value="<?php echo $this->menu_parameter; ?>" />
        <input type="hidden" name="cal" value="<?php echo intval($this->item); ?>" />
        <input type="hidden" name="schedule" value="1" />
		<nobr><label><?php _e('From','appointment-hour-booking'); ?>:</label> <input autocomplete="off" type="text" id="dfrom" name="dfrom" value="<?php echo esc_attr($dfrom_formatted); ?>" >&nbsp;&nbsp;</nobr>
		<nobr><label><?php _e('To','appointment-hour-booking'); ?>:</label> <input autocomplete="off" type="text" id="dto" name="dto" value="<?php echo esc_attr($dto_formatted); ?>" >&nbsp;&nbsp;</nobr>
        <nobr><?php _e('Paid Status','appointment-hour-booking'); ?>: <select id="paid" name="paid">
         <option value=""><?php _e('All','appointment-hour-booking'); ?></option>
         <option value="1" <?php if (!empty($_GET["paid"])) echo ' selected'; ?>><?php _e('Paid only','appointment-hour-booking'); ?></option>
      </select></nobr>
        <nobr><?php _e('Booking Status','appointment-hour-booking'); ?>: <?php $this->render_status_box('status', (!isset($_GET["status"])?'all':sanitize_text_field($_GET["status"])), true); ?></nobr>
		<nobr><label><?php _e('Item','appointment-hour-booking'); ?>:</label> <select id="cal" name="cal">
          <?php if ($current_user_access) { ?> <option value="0">[<?php _e('All Items','appointment-hour-booking'); ?>]</option><?php } ?>
   <?php
    $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.$this->table_items );                                                                     
    $saved_id = $this->item;
    foreach ($myrows as $item)
    {
        $this->setId($item->id);
        if ($current_user_access || @in_array($current_user->ID, unserialize($this->get_option("cp_user_access",""))))        
           echo '<option value="'.intval($item->id).'"'.(intval($item->id)==intval($saved_id)?" selected":"").'>'.esc_html($item->form_name).'</option>'; 
    }
    $this->setId($saved_id);
   ?>
    </select></nobr>
       <div style="float:right">
		<nobr>
            <input type="submit" name="ds" value="<?php _e('Filter','appointment-hour-booking'); ?>" class="button-primary button" style="">
			<input type="submit" name="<?php echo $this->prefix; ?>_csv2" value="<?php _e('Export to CSV','appointment-hour-booking'); ?>" class="button" style="margin-left:10px;">			
		</nobr>
       </div> 
      </form>
	</div>
</div>


<br />                            

<div id="dex_printable_contents">

 <div class="cpapp_no_wrap">
  <div class="cpappb_field_0 cpappb_field_header"><?php _e('Date','appointment-hour-booking'); ?></div>
  <div class="cpappb_field_1 cpappb_field_header"><?php _e('Slot','appointment-hour-booking'); ?></div>
  <div class="cpappb_field_2 cpappb_field_header"><?php _e('Service','appointment-hour-booking'); ?></div>
  <div class="cpappb_field_3 cpappb_field_header"><?php _e('Qty','appointment-hour-booking'); ?></div>
  <div class="cpappb_field_4 cpappb_field_header"><?php _e('Paid','appointment-hour-booking'); ?></div>
  <div class="cpappb_field_5 cpappb_field_header"><?php _e('Email','appointment-hour-booking'); ?></div>
  <div class="cpappb_field_6 cpappb_field_header"><?php _e('Data','appointment-hour-booking'); ?></div>
  <div class="cpappb_field_7 cpappb_field_header"><?php _e('Status','appointment-hour-booking'); ?></div>
  <div class="cpapp_break"></div>
 </div> 
 <div class="cpapp_break"></div>
<?php

echo $this->filter_list( array(
                               'calendar' => ($this->item != 0 ? $this->item : ''),
                               'fields' => 'DATE,TIME,SERVICE,quantity,paid,email,data,cancelled',
	    	                   'from' => $dfrom,
	    	                   'to' => $dto,
                               'paidonly' => sanitize_text_field( (!empty($_GET["paid"])?$_GET["paid"]:'')),
                               'status' => (!isset($_GET["status"])?'all':sanitize_text_field($_GET["status"]))
                               ) );

?>
</div>


<div class="ahb-buttons-container">
    <input type="button" value="<?php _e('Print','appointment-hour-booking'); ?>" class="button button-primary" onclick="do_dexapp_print();" />
	<a href="<?php print esc_attr(admin_url('admin.php?page='.$this->menu_parameter));?>" class="ahb-return-link">&larr;<?php _e('Return to the calendars list','appointment-hour-booking'); ?></a>
	<div class="clear"></div>
</div>


<?php } else { ?>

<div id="cpabc_printable_contents">

<p><?php _e('The purpose of this page is to <strong>display the bookings/schedule in a calendar view</strong>. You can add bookings from the public booking form or','appointment-hour-booking'); ?> <a href="?page=<?php echo $this->menu_parameter; ?>&cal=<?php echo $this->item; ?>&addbk=1"><?php _e('add bookings from the dashboard','appointment-hour-booking'); ?></a> <?php _e('and the bookings will appear in this calendar.<br />For CSV export, print and filter options switch to the "<strong>List View</strong>" with the button above this text.','appointment-hour-booking'); ?> </p>
<div class="clearer"></div>

            <link rel="stylesheet" href="<?php echo plugins_url('mv/css/cupertino/calendar.css', __FILE__); ?>" type="text/css" />
            <link rel="stylesheet" href="<?php echo plugins_url('mv/css/main.css', __FILE__); ?>" type="text/css" /> 
            
            <script type="text/javascript" src="<?php echo plugins_url('mv/js/underscore.js', __FILE__); ?>"></script>
            <script type="text/javascript" src="<?php echo plugins_url('mv/js/rrule.js', __FILE__); ?>"></script>
            <script type="text/javascript" src="<?php echo plugins_url('mv/js/Common.js', __FILE__); ?>"></script>
            
<?php
function cpwptimeslotsbk_mv_autodetect_language()
{
        $basename = '/mv/language/multiview_lang_';
        
        $binfo = str_replace('-','_',get_bloginfo('language'));
        
        $options = array ($binfo,
                          strtolower($binfo),
                          substr(strtolower($binfo),0,2)."_".substr(strtoupper($binfo),strlen(strtoupper($binfo))-2,2),
                          substr(strtolower($binfo),0,2)."_".substr(strtoupper($binfo),0,2),
                          substr(strtolower($binfo),0,2),
                          substr(strtolower($binfo),strlen(strtolower($binfo))-2,2)                      
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

        if (file_exists(dirname( __FILE__ ).'/mv/language/multiview_lang_'.cpwptimeslotsbk_mv_autodetect_language().'.js'))
            $langscript = plugins_url('/mv/language/multiview_lang_'.cpwptimeslotsbk_mv_autodetect_language().'.js', __FILE__);
        else
            $langscript = plugins_url('/mv/language/multiview_lang_en_GB.js', __FILE__);
?>        
            <script type="text/javascript" src="<?php echo $langscript; ?>"></script>
            <script type="text/javascript" src="<?php echo plugins_url('mv/js/jquery.calendar.js', __FILE__); ?>"></script>
            <script type="text/javascript" src="<?php echo plugins_url('mv/js/jquery.alert.js', __FILE__); ?>"></script>
            <script type="text/javascript" src="<?php echo plugins_url('mv/js/multiview.js', __FILE__); ?>"></script>
          
            <script type="text/javascript">
             var pathCalendar = "<?php echo $this->get_site_url( true ); ?>?cp_app_action=mv&formid=<?php echo intval($this->item); ?>";
             var dc_subjects = "";var dc_locations = "";
             initMultiViewCal("cal<?php echo intval($this->item); ?>", <?php echo intval($this->item); ?>,
          {viewDay:true,
          viewWeek:true,
          viewMonth:true,
          viewNMonth:true,
          viewList:true,
          viewdefault:"week",
          numberOfMonths:12,
          showtooltip:false,
          tooltipon:0,
          shownavigate:false,
          url:"",
          target:0,
          start_weekday: <?php echo intval($firstday); ?>,
          language:"en-GB",
          cssStyle:"cupertino",
          edition:true,
          btoday:true,
          dialogWidth: 330,
          bnavigation:true,
          brefresh:true,
          bnew:false,
          path:pathCalendar,
          userAdd:false,
          userEdit:false,
          userDel:false,
          userEditOwner:false,
          userDelOwner:false,
          showtooltipdwm:true,
          userOwner:0 ,cellheight:62 , palette:0, paletteDefault:"F00", 
          <?php 
                $otherparams = get_option('cp_cpappb_schcalcontent_otherparams',''); 
                if ($otherparams != '')
                    $otherparams = rtrim($otherparams,",").",";
                echo $otherparams;
          ?>          
          paletteFull:["FFF","FCC","FC9","FF9","FFC","9F9","9FF","CFF","CCF","FCF","CCC","F66","F96","FF6","FF3","6F9","3FF","6FF","99F","F9F","BBB","F00","F90","FC6","FF0","3F3","6CC","3CF","66C","C6C","999","C00","F60","FC3","FC0","3C0","0CC","36F","63F","C3C","666","900","C60","C93","990","090","399","33F","60C","939","333","600","930","963","660","060","366","009","339","636","000","300","630","633","330","030","033","006","309","303"]});
            </script>
          
            <div id="multicalendar"><div id="cal<?php echo intval($this->item); ?>" class="multicalendar"></div></div>
            
             <div style="clear:both;height:20px" ></div>    

</div>

<?php } ?>

<script type="text/javascript">
 function do_dexapp_print()
 {
      w=window.open();
      w.document.write("<style>.cpappb_field_header {font-weight: bold;background-color: #dcdcdc;}.cpapp_break { clear: both; }.cpappb_field_0, .cpappb_field_1,.cpappb_field_2, .cpappb_field_3,.cpappb_field_4, .cpappb_field_5,.cpappb_field_6, .cpappb_field_7,.cpappb_field_8, .cpappb_field_9,.cpappb_field_10, .cpappb_field_11{float: left; min-width: 80px;padding-right:11px;border-bottom: 1px dotted #777777;margin-left: 1px;     padding: 5px;margin: 2px;}.cpappb_field_0 {color: #44aa44;font-weight: bold; }.cpappb_field_1 {color: #aaaa44;font-weight: bold; }.cpappb_field_2{width:180px;}.cpappb_field_3{min-width: 20px;width:20px ;max-width:20px;}.cpappb_field_5{width:200px;overflow:hidden;}.cpappb_field_4,.cpappb_field_6{display:none;}.cpnopr{display:none;};table{border:2px solid black;width:100%;}th{border-bottom:2px solid black;text-align:left}td{padding-left:10px;border-bottom:1px solid black;}</style>"+document.getElementById('dex_printable_contents').innerHTML);
      w.print();
      w.close();    
 }
 

<?php
	
	 $dformatc = $this->get_option('date_format', 'mm/dd/yy');
	 if ($dformatc == 'd M, y') $dformatc = 'dd/mm/yy';
		 
?>	
 var $j = jQuery.noConflict();
 $j(function() {
 	$j("#dfrom").datepicker({
                    dateFormat: '<?php echo $dformatc; ?>'
                 });
 	$j("#dto").datepicker({
                    dateFormat: '<?php echo $dformatc; ?>'
                 });
 });
 
         

</script>












