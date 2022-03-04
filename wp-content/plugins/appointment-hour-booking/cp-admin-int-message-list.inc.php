<?php


$this->item = intval($_GET["cal"]);

$current_user = wp_get_current_user();
$current_user_access = current_user_can('edit_pages');

$current_page = intval( (empty($_GET["p"])?0:$_GET["p"]) );
if (!$current_page) $current_page = 1;
$records_per_page = 50;

if ( !is_admin() || (!$current_user_access && !@in_array($current_user->ID, unserialize($this->get_option("cp_user_access","")))))
{
    echo 'Direct access not allowed.';
    exit;
}


$message = "";


if (isset($_GET['delmark']) && $_GET['delmark'] != '')
{
    $this->verify_nonce ($_GET["anonce"], 'cpappb_actions_booking');
    for ($i=0; $i<=$records_per_page; $i++)
    if (isset($_GET['c'.$i]) && $_GET['c'.$i] != '')   
        $wpdb->query('DELETE FROM `'.$wpdb->prefix.$this->table_messages.'` WHERE id='.intval($_GET['c'.$i]));       
    $message = "Marked items deleted";
}
else if (isset($_GET['del']) && $_GET['del'] == 'all')
{    
    $this->verify_nonce ($_GET["anonce"], 'cpappb_actions_booking');
    if ($this->item == '' || $this->item == '0')
        $wpdb->query('DELETE FROM `'.$wpdb->prefix.$this->table_messages.'`');           
    else
        $wpdb->query('DELETE FROM `'.$wpdb->prefix.$this->table_messages.'` WHERE formid='.$this->item);           
    $message = "All items deleted";
} 
else if (isset($_GET['lu']) && $_GET['lu'] != '')
{
    $this->verify_nonce ($_GET["anonce"], 'cpappb_actions_booking');
    $myrows = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix.$this->table_messages." WHERE id=%d", $_GET['lu']) );
    $params = unserialize($myrows[0]->posted_data);
    $params["paid"] = $_GET["status"];
    $params["payment_type"] = __('Manually updated','appointment-hour-booking');
    $wpdb->query( $wpdb->prepare('UPDATE `'.$wpdb->prefix.$this->table_messages.'` SET posted_data=%s WHERE id=%d', serialize($params), $_GET['lu']) );
    $message = "Item updated";
}
else if (isset($_GET['ld']) && $_GET['ld'] != '')
{
    $this->verify_nonce ($_GET["anonce"], 'cpappb_actions_booking');
    $wpdb->query( $wpdb->prepare('DELETE FROM `'.$wpdb->prefix.$this->table_messages.'` WHERE id=%d', $_GET['ld']) );
    $message = "Item deleted";
}
else if (isset($_GET['ud']) && $_GET['ud'] != '')
{
    $this->verify_nonce ($_GET["anonce"], 'cpappb_actions_booking');
    $this->update_status($_GET['ud'], sanitize_text_field($_GET['status']));
    $message = "Status updated";
}

if ($this->item != 0)
    $myform = $wpdb->get_results( $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.$this->table_items .' WHERE id=%d' ,$this->item) );

$rawfrom = (isset($_GET["dfrom"]) ? sanitize_text_field($_GET["dfrom"]) : '');
$rawto = (isset($_GET["dto"]) ? sanitize_text_field(@$_GET["dto"]) : '');
if ($this->get_option('date_format', 'mm/dd/yy') == 'dd/mm/yy')
{
    $rawfrom = str_replace('/','.',$rawfrom);
    $rawto = str_replace('/','.',$rawto);
}

$cond = '';
if (!empty($_GET["search"])) $cond .= " AND (data like '%".esc_sql($_GET["search"])."%' OR posted_data LIKE '%".esc_sql($_GET["search"])."%')";
if ($rawfrom != '') $cond .= " AND (`time` >= '".esc_sql( date("Y-m-d",strtotime($rawfrom)))."')";
if ($rawto != '') $cond .= " AND (`time` <= '".esc_sql(date("Y-m-d",strtotime($rawto)))." 23:59:59')";
if ($this->item != 0) $cond .= " AND formid=".intval($this->item);

$events_query = "SELECT * FROM ".$wpdb->prefix.$this->table_messages." WHERE 1=1 ".$cond." ORDER BY `time` DESC";
/**
 * Allows modify the query of messages, passing the query as parameter
 * returns the new query
 */
$events_query = apply_filters( 'cpappb_messages_query', $events_query );
$events = $wpdb->get_results( $events_query );
$total_pages = ceil(count($events) / $records_per_page);


if ($message) echo "<div id='setting-error-settings_updated' class='updated'><h2>".$message."</h2></div>";

$nonce = wp_create_nonce( 'cpappb_actions_booking' );

?>
<script type="text/javascript">
 function cp_UpsItem(id)
 {
     var status = document.getElementById("statusbox"+id).options[document.getElementById("statusbox"+id).selectedIndex].value;
     document.location = 'admin.php?page=<?php echo $this->menu_parameter; ?>&anonce=<?php echo $nonce; ?>&cal=<?php echo intval($_GET["cal"]); ?>&list=1&ud='+id+'&status='+status+'&r='+Math.random();
 }
 function cp_updateMessageItem(id,status)
 {
    document.location = 'admin.php?page=<?php echo $this->menu_parameter; ?>&anonce=<?php echo $nonce; ?>&cal=<?php echo intval($_GET["cal"]); ?>&list=1&status='+status+'&lu='+id+'&r='+Math.random( );
 }
 function cp_deleteMessageItem(id)
 {
    if (confirm('Are you sure that you want to delete this item?'))
    {
        document.location = 'admin.php?page=<?php echo $this->menu_parameter; ?>&anonce=<?php echo $nonce; ?>&cal=<?php echo intval($_GET["cal"]); ?>&list=1&ld='+id+'&r='+Math.random();
    }
 }
 function cp_deletemarked()
 {
    if (confirm('Are you sure that you want to delete the marked items?')) 
        document.dex_table_form.submit();
 }  
 function cp_deleteall()
 {
    if (confirm('Are you sure that you want to delete ALL bookings for this form?'))
    {        
        document.location = 'admin.php?page=<?php echo $this->menu_parameter; ?>&cal=<?php echo intval($_GET["cal"]); ?>&list=1&del=all&anonce=<?php echo $nonce; ?>&r='+Math.random();
    }    
 }
 function cp_markall()
 {
     var ischecked = document.getElementById("cpcontrolck").checked;
     <?php for ($i=($current_page-1)*$records_per_page; $i<$current_page*$records_per_page; $i++) if (isset($events[$i])) { ?>
     document.forms.dex_table_form.c<?php echo $i-($current_page-1)*$records_per_page; ?>.checked = ischecked;
     <?php } ?>
 }  
</script>

<h1><?php _e('Booking Orders','appointment-hour-booking'); ?> - <?php if ($this->item != 0) echo $myform[0]->form_name; else echo 'All forms'; ?></h1>

<div class="ahb-buttons-container">
	<a href="<?php print esc_attr(admin_url('admin.php?page='.$this->menu_parameter));?>" class="ahb-return-link">&larr;<?php _e('Return to the calendars list','appointment-hour-booking'); ?></a>
	<div class="clear"></div>
</div>

<div class="ahb-section-container">
	<div class="ahb-section">
      <form action="admin.php" method="get">
        <input type="hidden" name="page" value="<?php echo $this->menu_parameter; ?>" />
        <input type="hidden" name="cal" value="<?php echo $this->item; ?>" />
        <input type="hidden" name="list" value="1" />
        <input type="hidden" name="anonce" value="<?php echo $nonce; ?>" />
		<nobr><label><?php _e('Search for','appointment-hour-booking'); ?>:</label> <input type="text" name="search" value="<?php echo esc_attr((!empty($_GET["search"])?$_GET["search"]:'')); ?>">&nbsp;&nbsp;</nobr>
		<nobr><label><?php _e('From','appointment-hour-booking'); ?>:</label> <input autocomplete="off" type="text" id="dfrom" name="dfrom" value="<?php echo esc_attr((!empty($_GET["dfrom"])?$_GET["dfrom"]:'')); ?>" >&nbsp;&nbsp;</nobr>
		<nobr><label><?php _e('To','appointment-hour-booking'); ?>:</label> <input autocomplete="off" type="text" id="dto" name="dto" value="<?php echo esc_attr((!empty($_GET["dto"])?$_GET["dto"]:'')); ?>" >&nbsp;&nbsp;</nobr>
		<nobr><label><?php _e('Item','appointment-hour-booking'); ?>:</label> <select id="cal" name="cal">
          <?php if ($current_user_access) { ?> <option value="0">[<?php _e('All Items','appointment-hour-booking'); ?>]</option><?php } ?>
   <?php
    $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.$this->table_items );
    $saved_id = $this->item;
    foreach ($myrows as $item)
    {
        $this->setId($item->id);
        if ($current_user_access || @in_array($current_user->ID, unserialize($this->get_option("cp_user_access",""))))
            echo '<option value="'.$item->id.'"'.(intval($item->id)==intval($saved_id)?" selected":"").'>'.esc_html($item->form_name).'</option>';
    }
    $this->setId($saved_id);
   ?>
    </select></nobr>
       <div style="float:right">
		<nobr>
            <input type="submit" name="ds" value="<?php _e('Filter','appointment-hour-booking'); ?>" class="button-primary button" style="">
			<input type="submit" name="<?php echo $this->prefix; ?>_csv1" value="<?php _e('Export to CSV','appointment-hour-booking'); ?>" class="button" style="margin-left:10px;">
		</nobr>
       </div> 
      </form>
	</div>
</div>


<?php


echo paginate_links(  array(
    'base'         => 'admin.php?page='.$this->menu_parameter.'&cal='.$this->item.'&list=1%_%&dfrom='.urlencode(sanitize_text_field((!empty($_GET["dfrom"])?$_GET["dfrom"]:''))).'&dto='.urlencode(sanitize_text_field((!empty($_GET["dto"])?$_GET["dto"]:''))).'&search='.urlencode($this->clean_sanitize((!empty($_GET["search"])?$_GET["search"]:''))),
    'format'       => '&p=%#%',
    'total'        => $total_pages,
    'current'      => $current_page,
    'show_all'     => False,
    'end_size'     => 1,
    'mid_size'     => 2,
    'prev_next'    => True,
    'prev_text'    => __('&laquo; Previous'),
    'next_text'    => __('Next &raquo;'),
    'type'         => 'plain',
    'add_args'     => False
    ) );

?>

<div id="dex_printable_contents">
<form name="dex_table_form" id="dex_table_form" action="admin.php" method="get">
 <input type="hidden" name="page" value="<?php echo $this->menu_parameter; ?>" />
 <input type="hidden" name="cal" value="<?php echo intval($_GET["cal"]); ?>" />
 <input type="hidden" name="list" value="1" />
 <input type="hidden" name="delmark" value="1" />
 <input type="hidden" name="anonce" value="<?php echo $nonce; ?>" />
<div class="ahb-orderssection-container" style="background:#f6f6f6;padding-bottom:20px;">
<table border="0" style="width:100%;" class="ahb-orders-list" cellpadding="10" cellspacing="10">
	<thead>
	<tr>
      <th width="10"><input type="checkbox" name="cpcontrolck" id="cpcontrolck" value="" onclick="cp_markall();"></th>
      <th width="30"><?php _e('ID','appointment-hour-booking'); ?></th>
	  <th style="text-align:left" width="130"><?php _e('Submission Date','appointment-hour-booking'); ?></th>
	  <th style="text-align:left"><?php _e('Email','appointment-hour-booking'); ?></th>
	  <th style="text-align:left"><?php _e('Message','appointment-hour-booking'); ?></th>
      <th width="130"><?php _e('Paid Status','appointment-hour-booking'); ?></th>
	  <th  class="cpnopr"><?php _e('Options','appointment-hour-booking'); ?></th>
	</tr>
	</thead>
	<tbody id="the-list">
	 <?php for ($i=($current_page-1)*$records_per_page; $i<$current_page*$records_per_page; $i++) if (isset($events[$i])) {
              $posted_data = unserialize($events[$i]->posted_data);
              $cancelled = false;
              $status = '';
              for($k=0; $k<count($posted_data["apps"]); $k++)
                  if ($posted_data["apps"][$k]["cancelled"] != '')
                  {
                      $cancelled = true;
                      $status = $posted_data["apps"][$k]["cancelled"];
                  }
     ?>
	  <tr class='<?php if ($cancelled) { ?>cpappb_cancelled <?php } ?><?php if (($i%2)) { ?>alternate <?php } ?>author-self status-draft format-default iedit' valign="top">
        <th><input type="checkbox" name="c<?php echo $i-($current_page-1)*$records_per_page; ?>" value="<?php echo $events[$i]->id; ?>" /></th> 
        <th><?php echo intval($events[$i]->id); ?></th>
		<td><?php echo $this->format_date(substr($events[$i]->time,0,16)).date(" H:i",strtotime($events[$i]->time)); ?></td>
		<td><?php echo sanitize_email($events[$i]->notifyto); ?></td>
		<td><?php
		        $data = str_replace("\n","<br />",str_replace('<','&lt;',$events[$i]->data));
		        foreach ($posted_data as $item => $value)
		            if (strpos($item,"_url") && $value != '')
		            {
		                $data = str_replace ($posted_data[str_replace("_url","",$item)],'<a href="'.$value[0].'" target="_blank">'.$posted_data[str_replace("_url","",$item)].'</a><br />',$data);
		            }
		        $data = str_replace("&lt;img ","<img ", $data);
                
                echo $this->filter_allowed_tags(apply_filters( 'cpappb_booking_orders_item', $data, $posted_data ));
		    ?></td>
        <td align="center"><?php echo '<span style="color:#006799;font-weight:bold;">'.(!empty($posted_data["paid"]) && @$posted_data["paid"]=='1'?__('Paid','appointment-hour-booking').'</span><br /><em class="cpappsoft">'.$posted_data["payment_type"]:'').'</em>'; ?></td>
		<td class="cpnopr" style="text-align:center;">
          <input class="button" type="button" name="caldelete_<?php echo $events[$i]->id; ?>" value="Toggle Payment" onclick="cp_updateMessageItem(<?php echo $events[$i]->id; ?>,<?php echo (!empty($posted_data["paid"]) && $posted_data["paid"]?'0':'1'); ?>);" />
		  <input class="button" type="button" name="caldelete_<?php echo $events[$i]->id; ?>" value="Delete" onclick="cp_deleteMessageItem(<?php echo $events[$i]->id; ?>);" />
          <hr />
          <?php
           // if ($cancelled) echo '<div style="color:#ff0000;font-weight:bold;">* Contains non-approved or cancelled dates</div>';
          ?>
          <nobr><?php $this->render_status_box('statusbox'.$events[$i]->id, $status); ?><input class="button" type="button" name="calups_<?php echo $events[$i]->id; ?>" value="Update Status" onclick="cp_UpsItem(<?php echo $events[$i]->id; ?>);" /></nobr>
		</td>
      </tr>
     <?php } ?>
	</tbody>
</table>
</div>
</form>
</div>

<div class="ahb-buttons-container">
    <input type="button" value="Print" class="button button-primary" onclick="do_dexapp_print();" />
	<a href="<?php print esc_attr(admin_url('admin.php?page='.$this->menu_parameter));?>" class="ahb-return-link">&larr;<?php _e('Return to the calendars list','appointment-hour-booking'); ?></a>
	<div class="clear"></div>
</div>

<div style="clear:both"></div>
<p class="submit" style="float:left;"><input class="button" type="button" name="pbutton" value="Delete marked items" onclick="cp_deletemarked();" /> &nbsp; &nbsp; &nbsp; </p>
<p class="submit" style="float:left;"><input class="button" type="button" name="pbutton" value="Delete All Bookings" onclick="cp_deleteall();" /></p>
<div style="clear:both"></div>


<script type="text/javascript">
 function do_dexapp_print()
 {
      w=window.open();
      w.document.write("<style>.cpnopr{display:none;};table{border:2px solid black;width:100%;}th{border-bottom:2px solid black;text-align:left}td{padding-left:10px;border-bottom:1px solid black;}</style>"+document.getElementById('dex_printable_contents').innerHTML);
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














