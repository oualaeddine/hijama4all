<?php 

  if ( !is_admin() || !current_user_can('manage_options') ) {echo 'Direct access not allowed.';exit;} 

  $nonce = wp_create_nonce( 'cpappb_actions_pwizard' );
  
?>

<h1>Publish Appointment Hour Booking Form</h1>

<style type="text/css">

.ahb-buttons-container{margin:1em 1em 1em 0;}
.ahb-return-link{float:right;}
.ahb-mssg{margin-left:0 !important; }
.ahb-section-container {
	border: 1px solid #e6e6e6;
	padding:0px;
	border-radius: 3px;
	-webkit-box-flex: 1;
	flex: 1;
	margin: 1em 1em 1em 0;
	min-width: 200px;
	background: #ffffff;
	position:relative;
}
.ahb-section{padding:20px;display:none;}
.ahb-section label{font-weight:600;}
.ahb-section-active{display:block;}

.ahb-row{display:none;}
.ahb-section table td,
.ahb-section table th{padding-left:0;padding-right:0;}
.ahb-section select,
.ahb-section input[type="text"]{width:100%;}

.cpmvcontainer { font-size:16px !important; }
</style>

<div class="ahb-buttons-container">
	<a href="<?php print esc_attr(admin_url('admin.php?page='.$this->menu_parameter));?>" class="ahb-return-link">&larr;<?php _e('Return to the calendars list','appointment-hour-booking'); ?></a>
	<div class="clear"></div>
</div>

<form method="post" action="?page=cp_apphourbooking&pwizard=1" name="regForm" id="regForm">          
 <input name="cp_apphourbooking_do_action_loaded" type="hidden" value="wizard" />
 <input name="anonce" type="hidden" value="<?php echo $nonce; ?>" />
 

<?php 

if ($this->get_param('cp_apphourbooking_do_action_loaded') == 'wizard') {
?>
<div class="ahb-section-container">
	<div class="ahb-section ahb-section-active" data-step="1">
        <h1><?php _e('Great! Form successfully published','appointment-hour-booking'); ?></h1>
        <p class="cpmvcontainer"><?php _e('The booking form was placed into the page','appointment-hour-booking'); ?> <a href="<?php echo $this->postURL; ?>"><?php echo $this->postURL; ?></a>.</p>
        <p class="cpmvcontainer"><?php _e('Now you can:','appointment-hour-booking'); ?></p>
        <div style="clear:both"></div>
        <button class="button button-primary cpmvcontainer" type="button" id="nextBtn" onclick="window.open('<?php echo $this->postURL; ?>');"><?php _e('View the Published Booking Form','appointment-hour-booking'); ?></button>
        <div style="clear:both"></div>
        <p class="cpmvcontainer">* <?php _e('Note: If the calendar was published in a new page or post it will be a \'draft\', you have to publish the page/post in the future if needed.','appointment-hour-booking'); ?></p>
        <div style="clear:both"></div>
        <button class="button button-primary cpmvcontainer" type="button" id="nextBtn" onclick="window.open('?page=cp_apphourbooking&cal=<?php echo intval($this->get_param("cpapphourbk_id")); ?>');"><?php _e('Edit the booking form settings and calendar availability','appointment-hour-booking'); ?></button>
        <div style="clear:both"></div>
    </div>
</div>
<div style="clear:both"></div>
<?php
} else {     
?>

<div class="ahb-section-container">
	<div class="ahb-section ahb-section-active" data-step="1">
		<table class="form-table">
            <tbody>
				<tr valign="top">
					<th><label><?php _e('Select calendar','appointment-hour-booking'); ?></label></th>
					<td>
                    <select id="cpapphourbk_id" name="cpapphourbk_id" onchange="reloadappbk(this);">
<?php
  $myrows = $wpdb->get_results( "SELECT * FROM ". $wpdb->prefix.$this->table_items);
  foreach ($myrows as $item)            
      echo '<option value="'.$item->id.'"'.($item->id==$this->item?' selected':'').'>'.esc_html($item->form_name).'</option>';
?>                
            </select>
                    </td>    
                </tr>   
                <tr valign="top">
                    <th><label><?php _e('Where to publish it?','appointment-hour-booking'); ?></label></th>
					<td> 
                        <select name="whereto" onchange="mvpublish_displayoption(this);">
                          <option value="0"><?php _e('Into a new page','appointment-hour-booking'); ?></option>
                          <option value="1"><?php _e('Into a new post','appointment-hour-booking'); ?></option>
                          <option value="2"><?php _e('Into an existent page','appointment-hour-booking'); ?></option>
                          <option value="3"><?php _e('Into an existent post','appointment-hour-booking'); ?></option>
                          <option value="4" style="color:#bbbbbb"><?php _e('Widget in a sidebar, header or footer - upgrade required for this option -','appointment-hour-booking'); ?></option>
                        </select>                    
                    </td>    
                </tr> 
                <tr valign="top" id="posttitle">
                    <th><label><?php _e('Page/Post Title','appointment-hour-booking'); ?></label></th>
					<td> 
                        <input type="text" name="posttitle" value="Booking Form" />
                    </td>    
                </tr>                  
                <tr valign="top"  id="ppage" style="display:none">
                    <th valign="top"></th>
					<td valign="top">
                    
                       <h3 style="background:#cccccc; padding:5px;"><?php _e('Classic way? Just copy and paste the following shortcode into the page/post:','appointment-hour-booking'); ?></h3>
                       
                       <div style="border: 1px dotted black; background-color: #FFFACD ;padding:15px; font-weight: bold; margin:10px;">
                         [<?php echo $this->shorttag; ?> id="<?php echo intval($this->item); ?>"]
                       </div>
                       
                       <?php if (defined('ELEMENTOR_PATH')) { ?>
                       <br /> 
                       <h3 style="background:#cccccc; padding:5px;"><?php _e('Using Elementor?','appointment-hour-booking'); ?></h3>
                       
                       <img src="<?php echo plugins_url('/controllers/help/elementor.png', __FILE__) ?>">
                       <?php } ?>                       
                       
                       <br />                       
                       <h3 style="background:#cccccc; padding:5px;"><?php _e('Using New WordPress Editor (Gutemberg)?','appointment-hour-booking'); ?> </h3>
                       
                       <img src="<?php echo plugins_url('/controllers/help/gutemberg.png', __FILE__) ?>">                      
                       
                       <br /> 
                       <h3 style="background:#cccccc; padding:5px;"><?php _e('Using classic WordPress editor or other editors?','appointment-hour-booking'); ?></h3>
                       
                        <?php _e('You can also publish the form in a post/page, use the dedicated icon','appointment-hour-booking'); ?> <?php echo '<img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert '.$this->plugin_name).'" /></a>';     ?>
                        <?php _e('which has been added to your Upload/Insert Menu, just below the title of your Post/Page', 'appointment-hour-booking'); ?>
   
                         <!-- <select name="publishpage">
                         <?php 
                             $pages = get_pages();
                             foreach ( $pages as $page ) {
                               $option = '<option value="' .  $page->ID  . '">';
                               $option .= $page->post_title;
                               $option .= '</option>';
                               echo $option;
                             }
                         ?>
                        </select>
                        -->
                    </td>    
                </tr> 
                <tr valign="top" id="ppost" style="display:none">
                    <th><label><?php _e('Select post','appointment-hour-booking'); ?></label></th>
					<td> 
                        <select name="publishpost">
                         <?php 
                             $pages = get_posts();
                             foreach ( $pages as $page ) {
                               $option = '<option value="' .  $page->ID  . '">';
                               $option .= $page->post_title;
                               $option .= '</option>';
                               echo $option;
                             }
                         ?>
                        </select>                    
                    </td>    
                </tr>                    
            <tbody>                
       </table>
       <hr size="1" />
       <div class="ahb-buttons-container">
			<input type="submit" id="subbtnnow" value="<?php _e('Publish Calendar','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px"  />
			<div class="clear"></div>
		</div>
</form>
</div>
</div>
<?php } ?>


<script type="text/javascript">

function reloadappbk(item) {
    document.location = '?page=cp_apphourbooking&pwizard=1&cal='+item.options[item.options.selectedIndex].value;
}

function mvpublish_displayviews(sel) {
    if (sel.checked)
        document.getElementById("nmonthsnum").style.display = '';
    else
        document.getElementById("nmonthsnum").style.display = 'none';        
}

function mvpublish_displayoption(sel) {
    document.getElementById("ppost").style.display = 'none';
    document.getElementById("ppage").style.display = 'none';
    document.getElementById("posttitle").style.display = 'none';   
    document.getElementById("subbtnnow").style.display = '';    
    if (sel.selectedIndex == 4)
    {
        alert('Widget option available only in commercial versions. Upgrade required for this option.');
        sel.selectedIndex = 0;      
    }
    else if (sel.selectedIndex == 2 || sel.selectedIndex == 3)
    {        
        document.getElementById("ppage").style.display = '';
        document.getElementById("subbtnnow").style.display = 'none';
    }
    else if (sel.selectedIndex == 1 || sel.selectedIndex == 0)
        document.getElementById("posttitle").style.display = '';
}


</script>   

<div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Note','appointment-hour-booking'); ?></span></h3>
  <div class="inside">
   <?php _e('You can also publish the form in a post/page, use the dedicated icon','appointment-hour-booking'); ?> <?php echo '<img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert '.$this->plugin_name).'" /></a>';     ?>
   <?php _e('which has been added to your Upload/Insert Menu, just below the title of your Post/Page or under the "+" icon if using the Gutemberg editor.','appointment-hour-booking'); ?>
   <br /><br />
  </div>
</div>
