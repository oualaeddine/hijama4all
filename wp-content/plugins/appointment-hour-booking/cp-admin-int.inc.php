<?php

$current_user = wp_get_current_user();
$current_user_access = current_user_can('edit_pages');

if ( !is_admin() || (!$current_user_access && !@in_array($current_user->ID, unserialize($this->get_option("cp_user_access","")))))
{
    echo 'Direct access not allowed.';
    exit;
}

$this->item = intval($_GET["cal"]);

$this->option_buffered_item = false;
$this->option_buffered_id = -1;

define('CP_APPBOOK_DEFAULT_fp_from_email', get_the_author_meta('user_email', get_current_user_id()) );
define('CP_APPBOOK_DEFAULT_fp_destination_emails', CP_APPBOOK_DEFAULT_fp_from_email);

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST[$this->prefix.'_post_options'] ) )
    echo "<div id='setting-error-settings_updated' class='updated'> <h2>Settings saved.</h2></div>";

$nonce = wp_create_nonce( 'cpappb_actions_admin' );

?>
<style>
.width50 { width:48% ! important;}
</style>
<script type="text/javascript">
    function update_cpappb_option()
    {
        var seloption = document.cpformconf.fp_emailtomethod.selectedIndex;
        if (seloption == 0)
        {
            document.getElementById("cpappb_destemails").style.display = '';
            document.getElementById("cpappb_dropemails").style.display = 'none';
        }
        else
        {
            document.getElementById("cpappb_destemails").style.display = 'none';
            document.getElementById("cpappb_dropemails").style.display = '';
        }
    }

	jQuery(function(){
		var $ = jQuery;
		$(document).on('click', '.ahb-step', function(){
			var s = $(this).data('step');
			ahbGoToStep(s);
		});

		window['ahbGoToStep'] = function(s){
			$('.ahb-step.ahb-step-active').removeClass('ahb-step-active');
			$('.ahb-step[data-step="'+s+'"]').addClass('ahb-step-active');
			$('.ahb-adintsection.ahb-adintsection-active').removeClass('ahb-adintsection-active');
			$('.ahb-adintsection[data-step="'+s+'"]').addClass('ahb-adintsection-active');
            $(window).scrollTop( $("#topadminsection").offset().top );
		};
	});

</script>
<div class="wrap">
<h1><?php _e('Edit','appointment-hour-booking'); ?> - <?php echo esc_html($this->get_option('form_name','Calendar')); ?></h1>


<form method="post" action="" name="cpformconf">
<input name="anonce" type="hidden" value="<?php echo $nonce; ?>" />
<input name="<?php echo $this->prefix; ?>_post_options" type="hidden" value="1" />
<input name="<?php echo $this->prefix; ?>_id" type="hidden" value="<?php echo $this->item; ?>" />
<input type="hidden" name="templates" id="templates" value="<?php echo esc_attr( json_encode( $this->available_templates() ) ); ?>" />

<div id="topadminsection"  class="ahb-buttons-container">
	<input type="submit" class="button button-primary ahb-save-btn" name="savereturn" value="<?php _e('Save Changes and Return','appointment-hour-booking'); ?>"  />
	<a href="<?php print esc_attr(admin_url('admin.php?page='.$this->menu_parameter));?>" class="ahb-return-link">&larr;<?php _e('Return to the calendars list','appointment-hour-booking'); ?></a>
	<div class="clear"></div>
</div>

<div class="ahb-adintsection-container">
	<div class="ahb-breadcrumb">
		<div class="ahb-step ahb-step-active" data-step="1">
			<i>1</i>
			<label><?php _e('Editor','appointment-hour-booking'); ?></label>
		</div>
		<div class="ahb-step" data-step="2">
			<i>2</i>
			<label><?php _e('General Settings','appointment-hour-booking'); ?></label>
		</div>
		<div class="ahb-step" data-step="3">
			<i>3</i>
			<label><?php _e('Notification Emails','appointment-hour-booking'); ?></label>
		</div>
		<div class="ahb-step" data-step="4">
			<i>4</i>
			<label><?php _e('Antispam','appointment-hour-booking'); ?></label>
		</div>
		<div class="ahb-step" data-step="5">
			<i>5</i>
			<label><?php _e('Reports','appointment-hour-booking'); ?></label>
		</div>
        <div class="ahb-step" data-step="6">
			<i>6</i>
			<label><?php _e('Add Ons','appointment-hour-booking'); ?></label>
		</div>
	</div>



    <div class="ahb-adintsection ahb-adintsection-active" data-step="1">
       <div class="inside">

         <input type="hidden" name="form_structure" id="form_structure" size="180" value="<?php echo str_replace('"','&quot;',str_replace("\r","",str_replace("\n","",esc_attr($this->cleanJSON($this->admin_process_json($this->get_option('form_structure', CP_APPBOOK_DEFAULT_form_structure))))))); ?>" />

         <link href="<?php echo plugins_url('css/style.css', __FILE__); ?>" type="text/css" rel="stylesheet" />
         <link href="<?php echo plugins_url('css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__); ?>" type="text/css" rel="stylesheet" />


         <script type="text/javascript">
           if (typeof jQuery === "undefined") {
              document.write ("<"+"script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></"+"script>");
              document.write ("<"+"script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.20/jquery-ui.min.js'></"+"script>");
           }
           $easyFormQuery = jQuery.noConflict();
           if (typeof $easyFormQuery == 'undefined' || typeof $fbuilderloadedflag == 'undefined')
           {
              // This code won't be used in most cases. This code is for preventing problems in wrong WP themes and conflicts with third party plugins.
              document.write ("<"+"script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.20/jquery-ui.min.js'></"+"script>");
              document.write ("<"+"script type='text/javascript' src='<?php echo $this->get_site_url( true ).'/?cp_cpappb_resources=admin'; ?>'></"+"script>");
           }
         </script>

         <script>

             $easyFormQuery(document).ready(function() {
                var f = $easyFormQuery("#fbuilder").fbuilder();
    		    window['cff_form'] = f;
                f.fBuild.loadData("form_structure", "templates");

                $easyFormQuery("#saveForm").click(function() {
                    f.fBuild.saveData("form_structure");
                });

                $easyFormQuery(".itemForm").click(function() {
         	       f.fBuild.addItem($easyFormQuery(this).attr("id"));
         	   });

               $easyFormQuery( ".itemForm" ).draggable({revert1: "invalid",helper: "clone",cursor: "move"});
         	   $easyFormQuery( "#fbuilder" ).droppable({
         	       accept: ".button",
         	       drop: function( event, ui ) {
         	           f.fBuild.addItem(ui.draggable.attr("id"));
         	       }
         	   });

             });
            var randcaptcha = 1;
            function generateCaptcha()
            {
               var d=new Date();
               var f = document.cpformconf;
               var qs = "&width="+f.cv_width.value;
               qs += "&height="+f.cv_height.value;
               qs += "&letter_count="+f.cv_chars.value;
               qs += "&min_size="+f.cv_min_font_size.value;
               qs += "&max_size="+f.cv_max_font_size.value;
               qs += "&noise="+f.cv_noise.value;
               qs += "&noiselength="+f.cv_noise_length.value;
               qs += "&bcolor="+f.cv_background.value.replace('#','');
               qs += "&border="+f.cv_border.value.replace('#','');
               qs += "&font="+f.cv_font.options[f.cv_font.selectedIndex].value;
               qs += "&r="+(randcaptcha++);

               document.getElementById("captchaimg").src= "<?php echo $this->get_site_url(true).'?'.$this->prefix.'_captcha=captcha&inAdmin=1'; ?>"+qs;
            }

         </script>

         <div style="background:#fafafa;" class="form-builder">

             <div class="column width50 ctrlsColumn">
                 <div id="tabs">
         			<ul>
         				<li><a href="#cptabs-1"><?php _e('Add a Field','appointment-hour-booking'); ?></a></li>
         				<li><a href="#cptabs-2"><?php _e('Field Settings','appointment-hour-booking'); ?></a></li>
         				<li><a href="#cptabs-3"><?php _e('Form Settings','appointment-hour-booking'); ?></a></li>
         			</ul>
         			<div id="cptabs-1"></div>
         			<div id="cptabs-2"></div>
         			<div id="cptabs-3"></div>
         		</div>
             </div>
             <div class="columnr width50 padding10" id="fbuilder">
                 <div id="formheader"></div>
                 <div id="fieldlist"></div>
                 <!--<div class="button" id="saveForm">Save Form</div>-->
             </div>
             <div class="clearer"></div>

         </div>
      </div>
      <br  />
      <input type="submit" value="<?php _e('Save Changes and Continue Editing','appointment-hour-booking'); ?>" class="button-primary" /> 
<br />      
       <br />
        <div style="padding:10px;background-color:#ffffdd;border:1px dotted black;">
            <p><?php _e('<STRONG>In this version</STRONG> the form builder supports <STRONG>calendar, text, email and acceptance checkbox fields</STRONG>.','appointment-hour-booking'); ?></p>
            <p><button type="button" onclick="window.open('<?php echo $this->plugin_download_URL; ?>?src=activatebtn');" style="cursor:pointer;height:35px;color:#20A020;font-weight:bold;"><?php _e('Activate the FULL form builder','appointment-hour-booking'); ?></button>
               <p style="font-weight:bold"><?php _e('The full set of fields also supports:','appointment-hour-booking'); ?>
               <ul>
                <li> - <strong>Conditional Logic</strong>: Hide/show fields based in previous selections.</li>
                <li> - File <strong>uploads</strong>, strong>Multi-page</strong> forms</li>
                <li> - <strong>Payments integration</strong> with PayPal Standard, PayPal Pro, Stripe, Authorize.net, Skrill, Mollie / iDeal, TargetPay / iDeal, SagePay, RedSys TPV and Sage Payments.</li>
                <li> - <strong><a href="?page=cp_apphourbooking_addons">Full set of addons</a></strong> (iCal sync, SMS, reminders, cancellation opts, reCaptcha, MailChimp, ...), <strong>fields</strong> and <strong>validations</strong></li>
               </ul>              
         </div>  
         
      <br />
      
      <!-- TEXT DEFINITIONS -->
	  <h2><?php _e('Labels and Texts','appointment-hour-booking'); ?></h2>
	  <hr />

      <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Button Labels','appointment-hour-booking'); ?></span></h3>
      <div class="inside">
         <table class="form-table ahbsmallpadding1">
            <tr valign="top">
            <td scope="row">
             <strong><?php _e('Submit button label (text)','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_submitbtn" size="40" value="<?php $label = esc_attr($this->get_option('vs_text_submitbtn', 'Submit')); echo ($label==''?'Submit':$label); ?>" />
            </td>
            <td>
              <strong><?php _e('Page {0} of {0} (text','appointment-hour-booking'); ?>):</strong><br />
              <input type="text" name="vs_text_pageof" size="40" value="<?php $label = esc_attr($this->get_option('vs_text_pageof', 'Page {0} of {0}')); echo ($label==''?'Page {0} of {0}':$label); ?>" />
            </td>
            </tr>
            <tr valign="top">
            <td scope="row">
             <strong><?php _e('Previous page button label (text)','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_previousbtn" size="40" value="<?php $label = esc_attr($this->get_option('vs_text_previousbtn', 'Previous')); echo ($label==''?'Previous':$label); ?>" /></td>
            <td scope="row">
             <strong><?php _e('Next page button label (text)','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_nextbtn" size="40" value="<?php $label = esc_attr($this->get_option('vs_text_nextbtn', 'Next')); echo ($label==''?'Next':$label); ?>" /></td>
            </tr>
            <tr valign="top">
            <td colspan="2">The  <em>class="pbSubmit"</em> can be used to modify the button styles. The styles can be applied into the <a href="?page=cp_apphourbooking_settings&gotab=css">CSS customization area</a></em>.  For further modifications the submit button is located at the end of the file <em>"cp-public-int.inc.php"</em>. For general CSS styles modifications to the form and samples <a href="http://apphourbooking.dwbooster.com/faq/" target="_blank">check the FAQ</a>.
            </tr>
         </table>
      </div>
      
      <hr  size="1" />

      <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Error messages for validation rules','appointment-hour-booking'); ?></span></h3>
      <div class="inside">
         <table class="form-table ahbsmallpadding1">
            <tr valign="top">
            <td scope="row">
             <strong><?php _e('"is required" text','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_is_required" size="40" value="<?php echo esc_attr($this->get_option('vs_text_is_required', CP_APPBOOK_DEFAULT_vs_text_is_required)); ?>" />
            </td>
            <td scope="row">
             <strong><?php _e('"is email" text','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_is_email" size="40" value="<?php echo esc_attr($this->get_option('vs_text_is_email', CP_APPBOOK_DEFAULT_vs_text_is_email)); ?>" />
            </td>
            </tr>
            <tr valign="top">
            <td scope="row">
             <strong><?php _e('"is valid captcha" text','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="cv_text_enter_valid_captcha" size="40" value="<?php echo esc_attr($this->get_option('cv_text_enter_valid_captcha', CP_APPBOOK_DEFAULT_cv_text_enter_valid_captcha)); ?>" />
            </td>
            <td scope="row"><strong><?php _e('"is valid date (mm/dd/yyyy)" text','appointment-hour-booking'); ?>:</strong><br /><input type="text" name="vs_text_datemmddyyyy" size="40" value="<?php echo esc_attr($this->get_option('vs_text_datemmddyyyy', CP_APPBOOK_DEFAULT_vs_text_datemmddyyyy)); ?>" /></td>
            </tr>
            <tr valign="top">
            <td scope="row">
             <strong><?php _e('"is valid date (dd/mm/yyyy)" text','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_dateddmmyyyy" size="40" value="<?php echo esc_attr($this->get_option('vs_text_dateddmmyyyy', CP_APPBOOK_DEFAULT_vs_text_dateddmmyyyy)); ?>" />
            </td>
            <td scope="row">
             <strong><?php _e('"is number" text','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_number" size="40" value="<?php echo esc_attr($this->get_option('vs_text_number', CP_APPBOOK_DEFAULT_vs_text_number)); ?>" />
            </td>
            </tr>
            <tr valign="top">
            <td scope="row">
             <strong><?php _e('"only digits" text','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_digits" size="40" value="<?php echo esc_attr($this->get_option('vs_text_digits', CP_APPBOOK_DEFAULT_vs_text_digits)); ?>" />
            </td>
            <td scope="row">
             <strong><?php _e('"under maximum" text','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_max" size="40" value="<?php echo esc_attr($this->get_option('vs_text_max', CP_APPBOOK_DEFAULT_vs_text_max)); ?>" />
            </td>
            </tr>
            <tr valign="top">
            <td scope="row">
             <strong><?php _e('"over minimum" text','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_min" size="40" value="<?php echo esc_attr($this->get_option('vs_text_min', CP_APPBOOK_DEFAULT_vs_text_min)); ?>" />
            </td>
            <td scope="row">
             <strong><?php _e('"Max appointments allowed messsage" text','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_maxapp" size="40" value="<?php echo esc_attr($this->get_option('vs_text_maxapp', CP_APPBOOK_DEFAULT_vs_text_maxapp)); ?>" />
            </td>
            </tr>

         </table>
      </div>
      
      <hr  size="1" />

      <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Other Texts','appointment-hour-booking'); ?></span></h3>
      <div class="inside">
         <table class="form-table ahbsmallpadding1">
            <tr valign="top">
            <td scope="row">
             <strong><?php _e('"Quantity" field label','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_quantity" size="40" value="<?php echo esc_attr($this->get_option_not_empty('vs_text_quantity', 'Quantity')); ?>" />
            </td>
            <td>
              <strong><?php _e('"Cancel" link label','appointment-hour-booking'); ?>):</strong><br />
              <input type="text" name="vs_text_cancel" size="40" value="<?php echo esc_attr($this->get_option_not_empty('vs_text_cancel', 'Cancel')); ?>" />
            </td>
            </tr>
            <tr valign="top">
            <td scope="row">
             <strong><?php _e('"Cost" label','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_cost" size="40" value="<?php echo esc_attr($this->get_option_not_empty('vs_text_cost', 'Cost')); ?>" />
            </td>
            <td scope="row">
             <strong><?php _e('"Selected time is no longer available..." text','appointment-hour-booking'); ?>:</strong><br />
             <input type="text" name="vs_text_nmore" size="40" value="<?php echo esc_attr($this->get_option_not_empty('vs_text_nmore', 'Selected time is no longer available. Please select a different time.')); ?>" /></td>
            </tr>              
         </table>
      </div>     
      
        <hr>
		<div class="ahb-buttons-container">
			<input type="button" value="<?php _e('Next Step - General Settings >','appointment-hour-booking'); ?>" class="button" style="float:right;margin-right:10px" onclick="ahbGoToStep(2);" />
			<?php if ($current_user_access) { ?><input type="submit" name="savepublish" value="<?php _e('Save and Publish','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" /><?php } ?>
			<input type="submit" name="savereturn" value="<?php _e('Save and Return','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" />
			<div class="clear"></div>
		</div>
     </div>
 

     <div class="ahb-adintsection" data-step="2">
      
      <div class="inside">


         <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Confirmation / Thank you page','appointment-hour-booking'); ?></span></h3>
         <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Confirmation / Thank you page','appointment-hour-booking'); ?></th>
            <td><input type="text" name="fp_return_page" size="70" value="<?php echo esc_attr($this->get_option('fp_return_page', CP_APPBOOK_DEFAULT_fp_return_page)); ?>" />
            <br /><em><?php _e('Address / URL of the page where the user will be redirected after submiting the booking form','appointment-hour-booking'); ?></em></td>
            </tr>
          <table> 
         <hr />
         
         <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Booking Status','appointment-hour-booking'); ?></span></h3>
         <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Default status of new bookings','appointment-hour-booking'); ?></th>
            <td><?php $this->render_status_box('defaultstatus',$this->get_option('defaultstatus', ''));  ?>
            <br /><em><?php _e('Only "Approved" items are taken in account for the availability verification.','appointment-hour-booking'); ?></em></td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Default status of paid bookings','appointment-hour-booking'); ?></th>
            <td><?php $this->render_status_box('defaultpaidstatus',$this->get_option('defaultpaidstatus', ''));  ?>
            <br /><em><?php _e('If a payment add-on is enabled the booking will be changed to this status after the payment.','appointment-hour-booking'); ?></em></td>
            </tr>            
          <table> 
         <hr />         
         
         <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Global Calendar Settings','appointment-hour-booking'); ?></span></h3> 
         <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Date Format','appointment-hour-booking'); ?></th>
            <td><?php $v = $this->get_option('date_format','mm/dd/yy'); ?>
         	   <select name="date_format" id="date_format">
    		   <option <?php if ($v == '' || $v == 'mm/dd/yy') echo 'selected'; ?> value="mm/dd/yy">Default - mm/dd/yyyy</option>
               <option <?php if ($v == 'dd/mm/yy') echo 'selected'; ?> value="dd/mm/yy">dd/mm/yyyy</option>
               <option <?php if ($v == 'mm.dd.yy') echo 'selected'; ?> value="mm.dd.yy">mm.dd.yyyy</option>
               <option <?php if ($v == 'dd.mm.yy') echo 'selected'; ?> value="dd.mm.yy">dd.mm.yyyy</option>
    		   <option <?php if ($v == 'yy-mm-dd') echo 'selected'; ?> value="yy-mm-dd">ISO 8601 - yyyy-mm-dd</option>
    		   <option <?php if ($v == 'd M, y') echo 'selected'; ?> value="d M, y">Short (UK) - d M, yy</option>
               <option <?php if ($v == 'M d, yy') echo 'selected'; ?> value="M d, yy">Short - M d, yy</option>
    		   <option <?php if ($v == 'd MM, y') echo 'selected'; ?> value="d MM, y">Medium (UK) - d MM, yy</option>
               <option <?php if ($v == 'MM d, yy') echo 'selected'; ?> value="MM d, yy">Medium - MM d, yy</option>
    		   <option <?php if ($v == 'DD, d MM, yy') echo 'selected'; ?> value="DD, d MM, yy">Full (UK) - DD, d MM, yyyy</option>
               <option <?php if ($v == 'DD, MM d, yy') echo 'selected'; ?> value="DD, MM d, yy">Full - DD, MM d, yyyy</option>
        	   </select>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Calendar Language','appointment-hour-booking'); ?></th>
            <td><?php $v = $this->get_option('calendar_language',''); ?>
                 <select name="calendar_language" id="calendar_language">
    <option <?php if ($v == '') echo 'selected'; ?> value=""> - auto-detect - </option>
    <option <?php if ($v == 'af') echo 'selected'; ?> value="af">Afrikaans</option>
    <option <?php if ($v == 'sq') echo 'selected'; ?> value="sq">Albanian</option>
    <option <?php if ($v == 'ar') echo 'selected'; ?> value="ar">Arabic</option>
    <option <?php if ($v == 'ar_DZ') echo 'selected'; ?> value="ar_DZ">Arabic (Algeria)</option>
    <option <?php if ($v == 'hy_AM') echo 'selected'; ?> value="hy_AM">Armenian</option>
    <option <?php if ($v == 'az') echo 'selected'; ?> value="az">Azerbaijani</option>
    <option <?php if ($v == 'eu') echo 'selected'; ?> value="eu">Basque</option>
    <option <?php if ($v == 'bs_BA') echo 'selected'; ?> value="bs_BA">Bosnian</option>
    <option <?php if ($v == 'bg_BG') echo 'selected'; ?> value="bg_BG">Bulgarian</option>
    <option <?php if ($v == 'be_BY') echo 'selected'; ?> value="be_BY">Byelorussian (Belarusian)</option>
    <option <?php if ($v == 'km') echo 'selected'; ?> value="km">Cambodian</option>
    <option <?php if ($v == 'ca') echo 'selected'; ?> value="ca">Catalan</option>
    <option <?php if ($v == 'zh_HK') echo 'selected'; ?> value="zh_HK">Chinese (Hong Kong SAR)</option>
    <option <?php if ($v == 'zh_CN') echo 'selected'; ?> value="zh_CN">Chinese (PRC)</option>
    <option <?php if ($v == 'zh_TW') echo 'selected'; ?> value="zh_TW">Chinese (Taiwan)</option>
    <option <?php if ($v == 'hr') echo 'selected'; ?> value="hr">Croatian</option>
    <option <?php if ($v == 'cs_CZ') echo 'selected'; ?> value="cs_CZ">Czech</option>
    <option <?php if ($v == 'da_DK') echo 'selected'; ?> value="da_DK">Danish</option>
    <option <?php if ($v == 'nl_NL') echo 'selected'; ?> value="nl_NL">Dutch</option>
    <option <?php if ($v == 'nl_BE') echo 'selected'; ?> value="nl_BE">Dutch - Belgium</option>
    <option <?php if ($v == 'en_AU') echo 'selected'; ?> value="en_AU">English (Australia)</option>
    <option <?php if ($v == 'en_NZ') echo 'selected'; ?> value="en_NZ">English (New Zealand)</option>
    <option <?php if ($v == 'en_GB') echo 'selected'; ?> value="en_GB">English (United Kingdom)</option>
    <option <?php if ($v == 'eo_EO') echo 'selected'; ?> value="eo">Esperanto</option>
    <option <?php if ($v == 'et') echo 'selected'; ?> value="et">Estonian</option>
    <option <?php if ($v == 'fo') echo 'selected'; ?> value="fo">Faeroese</option>
    <option <?php if ($v == 'fa_IR') echo 'selected'; ?> value="fa_IR">Farsi</option>
    <option <?php if ($v == 'fi') echo 'selected'; ?> value="fi">Finnish</option>
    <option <?php if ($v == 'fr_FR') echo 'selected'; ?> value="fr_FR">French</option>
    <option <?php if ($v == 'fr_CA') echo 'selected'; ?> value="fr_CA">French (Canada)</option>
    <option <?php if ($v == 'fr_CH') echo 'selected'; ?> value="fr_CH">French (Switzerland)</option>
    <option <?php if ($v == 'gl_ES') echo 'selected'; ?> value="gl_ES">Galician</option>
    <option <?php if ($v == 'ka_GE') echo 'selected'; ?> value="ka_GE">Georgian</option>
    <option <?php if ($v == 'de_DE') echo 'selected'; ?> value="de_DE">German</option>
    <option <?php if ($v == 'el') echo 'selected'; ?> value="el">Greek</option>
    <option <?php if ($v == 'he_IL') echo 'selected'; ?> value="he_IL">Hebrew</option>
    <option <?php if ($v == 'hi_IN') echo 'selected'; ?> value="hi_IN">Hindi</option>
    <option <?php if ($v == 'hu_HU') echo 'selected'; ?> value="hu_HU">Hungarian</option>
    <option <?php if ($v == 'is') echo 'selected'; ?> value="is">Icelandic</option>
    <option <?php if ($v == 'id_ID') echo 'selected'; ?> value="id_ID">Indonesian</option>
    <option <?php if ($v == 'it_IT') echo 'selected'; ?> value="it_IT">Italian</option>
    <option <?php if ($v == 'it_CH') echo 'selected'; ?> value="it_CH">Italian (Switzerland)</option>
    <option <?php if ($v == 'ja') echo 'selected'; ?> value="ja">Japanese</option>
    <option <?php if ($v == 'kk') echo 'selected'; ?> value="kk">Kazakh</option>
    <option <?php if ($v == 'ky') echo 'selected'; ?> value="ky">Kirghiz</option>
    <option <?php if ($v == 'ko_KR') echo 'selected'; ?> value="ko_KR">Korean</option>
    <option <?php if ($v == 'lv') echo 'selected'; ?> value="lv">Latvian (Lettish)</option>
    <option <?php if ($v == 'lt_LT') echo 'selected'; ?> value="lt_LT">Lithuanian</option>
    <option <?php if ($v == 'lb') echo 'selected'; ?> value="lb">Luxembourgish</option>
    <option <?php if ($v == 'mk_MK') echo 'selected'; ?> value="mk_MK">Macedonian</option>
    <option <?php if ($v == 'ms_MY') echo 'selected'; ?> value="ms_MY">Malay</option>
    <option <?php if ($v == 'ml_IN') echo 'selected'; ?> value="ml_IN">Malayalam</option>
    <option <?php if ($v == 'no') echo 'selected'; ?> value="no">Norwegian</option>
    <option <?php if ($v == 'nb_NO') echo 'selected'; ?> value="nb_NO">Norwegian (Bokm&aring;l)</option>
    <option <?php if ($v == 'nn') echo 'selected'; ?> value="nn">Norwegian Nynorsk</option>
    <option <?php if ($v == 'pl_PL') echo 'selected'; ?> value="pl_PL">Polish</option>
    <option <?php if ($v == 'pt_PT') echo 'selected'; ?> value="pt_PT">Portuguese</option>
    <option <?php if ($v == 'pt_BR') echo 'selected'; ?> value="pt_BR">Portuguese (Brazil)</option>
    <option <?php if ($v == 'rm') echo 'selected'; ?> value="rm">Rhaeto-Romance</option>
    <option <?php if ($v == 'ro_RO') echo 'selected'; ?> value="ro_RO">Romanian</option>
    <option <?php if ($v == 'ru_RU') echo 'selected'; ?> value="ru_RU">Russian</option>
    <option <?php if ($v == 'sr_SR') echo 'selected'; ?> value="sr_SR">Serbian</option>
    <option <?php if ($v == 'sk_SK') echo 'selected'; ?> value="sk_SK">Slovak</option>
    <option <?php if ($v == 'sl_SI') echo 'selected'; ?> value="sl_SI">Slovenian</option>
    <option <?php if ($v == 'es_ES') echo 'selected'; ?> value="es_ES">Spanish</option>
    <option <?php if ($v == 'sv_SE') echo 'selected'; ?> value="sv_SE">Swedish</option>
    <option <?php if ($v == 'tj') echo 'selected'; ?> value="tj">Tajikistan</option>
    <option <?php if ($v == 'ta') echo 'selected'; ?> value="ta">Tamil</option>
    <option <?php if ($v == 'th') echo 'selected'; ?> value="th">Thai</option>
    <option <?php if ($v == 'tr_TR') echo 'selected'; ?> value="tr_TR">Turkish</option>
    <option <?php if ($v == 'uk') echo 'selected'; ?> value="uk">Ukrainian</option>
    <option <?php if ($v == 'vi') echo 'selected'; ?> value="vi">Vietnamese</option>
    <option <?php if ($v == 'cy_GB') echo 'selected'; ?> value="cy_GB">Welsh/UK</option>
    </select>
    </td>
            </tr>
           <tr valign="top">
            <th scope="row"><?php _e('Include appointment end-time in the confirmation emails?','appointment-hour-booking'); ?>:</th>
            <td>
              <?php $option = $this->get_option('display_emails_endtime', ''); ?>
              <select name="display_emails_endtime">
               <option value=""<?php if ($option == '') echo ' selected'; ?>><?php _e('Yes','appointment-hour-booking'); ?></option>
               <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e('No','appointment-hour-booking'); ?></option>
              </select>
            </td>
           </tr>             
         </table>
      </div>
      <hr />
      <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Payment Integration Settings','appointment-hour-booking'); ?></span></h3>
      <div class="inside">
         <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Product name at payment page','appointment-hour-booking'); ?></th>
            <td><input type="text" name="product_name" size="40" value="<?php echo esc_attr($this->get_option('product_name', 'Booking')); ?>" /></td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Label of "Pay Later" option (if enabled)','appointment-hour-booking'); ?></th>
            <td><input type="text" name="pay_later_label" size="40" value="<?php echo esc_attr($this->get_option('pay_later_label', 'Pay later')); ?>" /></td>
            </tr>
         </table>
         <em>* <?php _e('Note: To enable a payment method enable first the related addon.','appointment-hour-booking'); ?></em>
      </div>
      <hr />
      <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Users with access to the messages list','appointment-hour-booking'); ?></span></h3>
      <div class="inside">
         <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Select users with access (CTRL+click for multiple selection)','appointment-hour-booking'); ?>:</th>
            <td>
              <?php
                 $users = $wpdb->get_results( "SELECT user_login,ID FROM ".$wpdb->users." ORDER BY user_login ASC" );
                 $options = unserialize($this->get_option('cp_user_access', array()));
                 if (!is_array($options))
                     $options = array();
              ?>
              <select name="cp_user_access[]" multiple="multiple" size="5">
                <?php foreach ($users as $user) { ?>
                 <option value="<?php echo $user->ID; ?>"<?php if ( in_array ($user->ID, $options) ) echo ' selected'; ?>><?php echo $user->user_login; ?></option>
                <?php  } ?>
              </select>
            </td>
           </tr>
           <tr valign="top">
            <th scope="row"><?php _e('Allow selected users access also to the calendar settings?','appointment-hour-booking'); ?>:</th>
            <td>
              <?php $option = $this->get_option('cp_user_access_settings', ''); ?>
              <select name="cp_user_access_settings">
               <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e('Yes','appointment-hour-booking'); ?></option>
               <option value=""<?php if ($option == '') echo ' selected'; ?>><?php _e('No','appointment-hour-booking'); ?></option>
              </select>
            </td>
           </tr>            
         </table>
      </div>
      <hr>
		<div class="ahb-buttons-container">
			<input type="button" value="<?php _e('Next Step - Notification Emails >','appointment-hour-booking'); ?>" class="button" style="float:right;margin-right:10px" onclick="ahbGoToStep(3);" />
			<?php if ($current_user_access) { ?><input type="submit" name="savepublish" value="<?php _e('Save and Publish','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" /><?php } ?>
			<input type="submit" name="savereturn" value="<?php _e('Save and Return','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" />
			<div class="clear"></div>
		</div>
     </div>

     <div class="ahb-adintsection" data-step="3">
      <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('From / To Email Addresses','appointment-hour-booking'); ?>:</span></h3>
      <div class="inside">
         <table class="form-table">

            <tr valign="top">
            <th scope="row"><?php _e('Send email "From"','appointment-hour-booking'); ?> </th>
            <td>
              <?php $option = $this->get_option('fp_emailfrommethod', "fixed"); ?>
               <select name="fp_emailfrommethod">
                 <option value="fixed"<?php if ($option == 'fixed') echo ' selected'; ?>><?php _e('From fixed email address indicated below - Recommended option','appointment-hour-booking'); ?></option>
                 <option value="customer"<?php if ($option == 'customer') echo ' selected'; ?>><?php _e('From the email address indicated by the custome','appointment-hour-booking'); ?>r</option>
                </select><br />
                <span style="font-size:10px;color:#666666">
                * <?php _e('If you select "from fixed..." the customer email address will appear in the "to" address when you hit "reply", this is the recommended setting to avoid mail server restrictions.','appointment-hour-booking'); ?>
                <br />
                * <?php _e('If you select "from customer email" then the customer email will appear also visually when you receive the email, but this isn\'t supported by all hosting services, so this
                option isn\'t recommended in most cases.','appointment-hour-booking'); ?>
                </span>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('"From" email (for fixed "from" addresses)','appointment-hour-booking'); ?></th>
            <td><input type="text" name="fp_from_email" size="40" value="<?php echo esc_attr($this->get_option('fp_from_email', CP_APPBOOK_DEFAULT_fp_from_email)); ?>" /></td>
            </tr>

            <th scope="row"><?php _e('Send email "To"','appointment-hour-booking'); ?> </th>
            <td>
              <?php $option = $this->get_option('fp_emailtomethod', "fixed"); ?>
               <select name="fp_emailtomethod" onchange="update_cpappb_option();">
                 <option value="fixed"<?php if ($option == 'fixed') echo ' selected'; ?>><?php _e('To the fixed email(s) address(es) indicated below - Recommended option','appointment-hour-booking'); ?></option>
                 <option value="customer"<?php if ($option == 'customer') echo ' selected'; ?>><?php _e('To the email address selected in a form field (ex: captcha image enabled is recommended in this case)','appointment-hour-booking'); ?></option>
                </select><br />
                <span style="font-size:10px;color:#666666">
                * <?php _e('If you select "To fixed..." enter the destination emails in the next field. ','appointment-hour-booking'); ?>
                <br />
                * <?php _e('If you select "To email ...in form field" then add a field like a drop-down, radio-button or checkbox that contains the email address in the field value (not needed in the field text but in the internal value).','appointment-hour-booking'); ?>
                </span>
            </td>
            </tr>
            <tr valign="top" id="cpappb_destemails" <?php if ($option == 'customer') echo ' style="display:none;"'; ?>>
            <th scope="row"><?php _e('Destination emails (comma separated)','appointment-hour-booking'); ?></th>
            <td><input type="text" name="fp_destination_emails" size="40" value="<?php echo esc_attr($this->get_option('fp_destination_emails', CP_APPBOOK_DEFAULT_fp_destination_emails)); ?>" /></td>
            </tr>
            <tr valign="top" id="cpappb_dropemails" <?php if ($option != 'customer') echo ' style="display:none;"'; ?>>
            <th scope="row"><?php _e('Field that cotains the destination email(s)','appointment-hour-booking'); ?></th>
            <td>
                <select id="fp_destination_emails_field" name="fp_destination_emails_field" def="<?php echo esc_attr($this->get_option('fp_destination_emails_field', '')); ?>"></select>
            </td>
            </tr>
            </table>
            
            <hr />
            <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Email Notification to Website Administrator','appointment-hour-booking'); ?>:</span></h3>
            
            <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Email subject','appointment-hour-booking'); ?></th>
            <td><input type="text" name="fp_subject" size="70" value="<?php echo esc_attr($this->get_option('fp_subject', CP_APPBOOK_DEFAULT_fp_subject)); ?>" /></td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Include additional information?','appointment-hour-booking'); ?></th>
            <td>
              <?php $option = $this->get_option('fp_inc_additional_info', CP_APPBOOK_DEFAULT_fp_inc_additional_info); ?>
              <select name="fp_inc_additional_info">
               <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e('Yes','appointment-hour-booking'); ?></option>
               <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e('No','appointment-hour-booking'); ?></option>
              </select>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Email format?','appointment-hour-booking'); ?></th>
            <td>
              <?php $option = $this->get_option('fp_emailformat', CP_APPBOOK_DEFAULT_email_format); ?>
              <select name="fp_emailformat">
               <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e('Plain Text (default)','appointment-hour-booking'); ?></option>
               <option value="html"<?php if ($option == 'html') echo ' selected'; ?>><?php _e('HTML (use html in the textarea below)','appointment-hour-booking'); ?></option>
              </select>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Message','appointment-hour-booking'); ?></th>
            <td><textarea type="text" name="fp_message" rows="6" cols="80"><?php echo $this->get_option('fp_message', CP_APPBOOK_DEFAULT_fp_message); ?></textarea></td>
            </tr>
         </table>
      </div>
      <hr />
      <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Email Copy to the User / Customer','appointment-hour-booking'); ?>:</span></h3>
      <div class="inside">
         <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Send confirmation/thank you message to user?','appointment-hour-booking'); ?></th>
            <td>
              <?php $option = $this->get_option('cu_enable_copy_to_user', CP_APPBOOK_DEFAULT_cu_enable_copy_to_user); ?>
              <select name="cu_enable_copy_to_user">
               <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e('Yes','appointment-hour-booking'); ?></option>
               <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e('No','appointment-hour-booking'); ?></option>
              </select>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Email field on the form','appointment-hour-booking'); ?></th>
            <td><select id="cu_user_email_field" name="cu_user_email_field" def="<?php echo esc_attr($this->get_option('cu_user_email_field', CP_APPBOOK_DEFAULT_cu_user_email_field)); ?>"></select></td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Email subject','appointment-hour-booking'); ?></th>
            <td><input type="text" name="cu_subject" size="70" value="<?php echo esc_attr($this->get_option('cu_subject', CP_APPBOOK_DEFAULT_cu_subject)); ?>" /></td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Email format?','appointment-hour-booking'); ?></th>
            <td>
              <?php $option = $this->get_option('cu_emailformat', CP_APPBOOK_DEFAULT_email_format); ?>
              <select name="cu_emailformat">
               <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e('Plain Text (default)','appointment-hour-booking'); ?></option>
               <option value="html"<?php if ($option == 'html') echo ' selected'; ?>><?php _e('HTML (use html in the textarea below)','appointment-hour-booking'); ?></option>
              </select>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Message','appointment-hour-booking'); ?></th>
            <td><textarea type="text" name="cu_message" rows="6" cols="80"><?php echo $this->get_option('cu_message', CP_APPBOOK_DEFAULT_cu_message); ?></textarea></td>
            </tr>
         </table>
      </div>
		<hr>
		<div class="ahb-buttons-container">
			<input type="button" value="<?php _e('Next Step - Antispam >','appointment-hour-booking'); ?>" class="button" style="float:right;margin-right:10px" onclick="ahbGoToStep(4);" />
			<?php if ($current_user_access) { ?><input type="submit" name="savepublish" value="<?php _e('Save and Publish','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" /><?php } ?>
			<input type="submit" name="savereturn" value="<?php _e('Save and Return','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" />
			<div class="clear"></div>
		</div>
     </div>


     <div class="ahb-adintsection" data-step="4">
      <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Captcha Verification','appointment-hour-booking'); ?></span></h3>
      <div class="inside">
         <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Use Captcha Verification?','appointment-hour-booking'); ?></th>
            <td colspan="5">
              <?php $option = $this->get_option('cv_enable_captcha', CP_APPBOOK_DEFAULT_cv_enable_captcha); ?>
              <select name="cv_enable_captcha">
               <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e('Yes','appointment-hour-booking'); ?></option>
               <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e('No','appointment-hour-booking'); ?></option>
              </select>
            </td>
            </tr>

            <tr valign="top">
             <th scope="row"><?php _e('Width','appointment-hour-booking'); ?>:</th>
             <td><input type="text" name="cv_width" size="10" value="<?php echo esc_attr($this->get_option('cv_width', CP_APPBOOK_DEFAULT_cv_width)); ?>"  onblur="generateCaptcha();"  /></td>
             <th scope="row"><?php _e('Height','appointment-hour-booking'); ?>:</th>
             <td><input type="text" name="cv_height" size="10" value="<?php echo esc_attr($this->get_option('cv_height', CP_APPBOOK_DEFAULT_cv_height)); ?>" onblur="generateCaptcha();"  /></td>
             <th scope="row"><?php _e('Chars','appointment-hour-booking'); ?>:</th>
             <td><input type="text" name="cv_chars" size="10" value="<?php echo esc_attr($this->get_option('cv_chars', CP_APPBOOK_DEFAULT_cv_chars)); ?>" onblur="generateCaptcha();"  /></td>
            </tr>

            <tr valign="top">
             <th scope="row" valign="top"><?php _e('Min font size','appointment-hour-booking'); ?>:</th>
             <td valign="top"><input type="text" name="cv_min_font_size" size="10" value="<?php echo esc_attr($this->get_option('cv_min_font_size', CP_APPBOOK_DEFAULT_cv_min_font_size)); ?>" onblur="generateCaptcha();"  /></td>
             <th scope="row" valign="top"><?php _e('Max font size','appointment-hour-booking'); ?>:</th>
             <td valign="top"><input type="text" name="cv_max_font_size" size="10" value="<?php echo esc_attr($this->get_option('cv_max_font_size', CP_APPBOOK_DEFAULT_cv_max_font_size)); ?>" onblur="generateCaptcha();"  /></td>
             <td colspan="2" rowspan="2">
               <?php _e('Preview','appointment-hour-booking'); ?>:<br />
                 <br />
                <img src="<?php echo $this->get_site_url(true).'?'.$this->prefix.'_captcha=captcha&inAdmin=1'; ?>"  id="captchaimg" alt="security code" border="0"  />
             </td>
            </tr>


            <tr valign="top">
             <th scope="row"><?php _e('Noise','appointment-hour-booking'); ?>:</th>
             <td><input type="text" name="cv_noise" size="10" value="<?php echo esc_attr($this->get_option('cv_noise', CP_APPBOOK_DEFAULT_cv_noise)); ?>" onblur="generateCaptcha();" /></td>
             <th scope="row"><?php _e('Noise Length','appointment-hour-booking'); ?>:</th>
             <td><input type="text" name="cv_noise_length" size="10" value="<?php echo esc_attr($this->get_option('cv_noise_length', CP_APPBOOK_DEFAULT_cv_noise_length)); ?>" onblur="generateCaptcha();" /></td>
            </tr>


            <tr valign="top">
             <th scope="row"><?php _e('Background','appointment-hour-booking'); ?>:</th>
             <td><input type="color" name="cv_background" size="10" value="#<?php echo esc_attr($this->get_option('cv_background', CP_APPBOOK_DEFAULT_cv_background)); ?>" onblur="generateCaptcha();" /></td>
             <th scope="row"><?php _e('Border','appointment-hour-booking'); ?>:</th>
             <td><input type="color" name="cv_border" size="10" value="#<?php echo esc_attr($this->get_option('cv_border', CP_APPBOOK_DEFAULT_cv_border)); ?>" onblur="generateCaptcha();" /></td>
             <th scope="row"><?php _e('Font','appointment-hour-booking'); ?>:</th>
             <td>
                <select name="cv_font" onchange="generateCaptcha();" >
                  <option value="font1"<?php if ("font1" == $this->get_option('cv_font', CP_APPBOOK_DEFAULT_cv_font)) echo " selected"; ?>>Font 1</option>
                  <option value="font2"<?php if ("font2" == $this->get_option('cv_font', CP_APPBOOK_DEFAULT_cv_font)) echo " selected"; ?>>Font 2</option>
                  <option value="font3"<?php if ("font3" == $this->get_option('cv_font', CP_APPBOOK_DEFAULT_cv_font)) echo " selected"; ?>>Font 3</option>
                  <option value="font4"<?php if ("font4" == $this->get_option('cv_font', CP_APPBOOK_DEFAULT_cv_font)) echo " selected"; ?>>Font 4</option>
                </select>
             </td>
            </tr>
         </table>
      </div>
		<hr>
		<div class="ahb-buttons-container">
			<input type="button" value="<?php _e('Next Step - Reports >','appointment-hour-booking'); ?>" class="button" style="float:right;margin-right:10px" onclick="ahbGoToStep(5);" />
			<?php if ($current_user_access) { ?><input type="submit" name="savepublish" value="<?php _e('Save and Publish','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" /><?php } ?>
			<input type="submit" name="savereturn" value="<?php _e('Save and Return','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" />
			<div class="clear"></div>
		</div>
     </div>

     <div class="ahb-adintsection" data-step="5">
      <h3 class='hndle' style="padding-top:5px;padding-bottom:5px;"><span><?php _e('Automatic Reports: Send submissions in CSV format via email','appointment-hour-booking'); ?></span></h3>
      <div class="inside">
         <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Enable Reports?','appointment-hour-booking'); ?></th>
            <td>
              <?php $option = $this->get_option('rep_enable', 'no'); ?>
              <select name="rep_enable">
               <option value="no"<?php if ($option == 'no' || $option == '') echo ' selected'; ?>><?php _e('No','appointment-hour-booking'); ?></option>
               <option value="yes"<?php if ($option == 'yes') echo ' selected'; ?>><?php _e('Yes','appointment-hour-booking'); ?></option>
              </select>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Send report every','appointment-hour-booking'); ?></th>
            <td><input type="text" name="rep_days" size="4" value="<?php echo esc_attr($this->get_option('rep_days', '1')); ?>" /> <?php _e('days (Put a 0 to send the report immediately after each submission)','appointment-hour-booking'); ?></td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Send report after this hour (server time)','appointment-hour-booking'); ?></th>
            <td>
              <select name="rep_hour">
               <?php
                 $hour = $this->get_option('rep_hour', '0');
                 for ($k=0;$k<24;$k++)
                     echo '<option value="'.$k.'"'.($hour==$k?' selected':'').'>'.($k<10?'0':'').$k.'</option>';
               ?>
              </select>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Send the report to the following email addresses (comma separated)','appointment-hour-booking'); ?></th>
            <td><input type="text" name="rep_emails" size="70" value="<?php echo esc_attr($this->get_option('rep_emails', '')); ?>" /></td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Email subject','appointment-hour-booking'); ?></th>
            <td><input type="text" name="rep_subject" size="70" value="<?php echo esc_attr($this->get_option('rep_subject', 'Submissions report...')); ?>" /></td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Email format?','appointment-hour-booking'); ?></th>
            <td>
              <?php $option = $this->get_option('rep_emailformat', 'text'); ?>
              <select name="rep_emailformat">
               <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e('Plain Text (default)','appointment-hour-booking'); ?></option>
               <option value="html"<?php if ($option == 'html') echo ' selected'; ?>><?php _e('HTML (use html in the textarea below)','appointment-hour-booking'); ?></option>
              </select>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Email Text (CSV file will be attached with the submissions)','appointment-hour-booking'); ?></th>
            <td><textarea type="text" name="rep_message" rows="3" cols="80"><?php echo $this->get_option('rep_message', 'Attached you will find the data from the form submissions.'); ?></textarea></td>
            </tr>
         </table>
      </div>
        <hr>
		<div class="ahb-buttons-container">
			<input type="button" value="<?php _e('Next Step - Add Ons >','appointment-hour-booking'); ?>" class="button" style="float:right;margin-right:10px" onclick="ahbGoToStep(6);" />
			<?php if ($current_user_access) { ?><input type="submit" name="savepublish" value="<?php _e('Save and Publish','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" /><?php } ?>
			<input type="submit" name="savereturn" value="<?php _e('Save and Return','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" />
			<div class="clear"></div>
		</div>
     </div>


    <div class="ahb-adintsection" data-step="6">
     <?php
    	global $cpappb_addons_objs_list, $cpappb_addons_active_list;
    	if( count( $cpappb_addons_active_list ) )
    	{
    		_e( '<h2>Add-Ons Settings:</h2><hr />', 'appointment-hour-booking' );
    		foreach( $cpappb_addons_active_list as $addon_id ) if( isset( $cpappb_addons_objs_list[ $addon_id ] ) ) print $cpappb_addons_objs_list[ $addon_id ]->get_addon_form_settings( $this->item );
    	}
        else
        {
            ?>
            <p><?php _e('You can optionally','appointment-hour-booking'); ?> <a target="_blank" href="?page=cp_apphourbooking_addons"><?php _e('activate add ons in the add ons section','appointment-hour-booking'); ?></a>.</p>
            <p><?php _e('The add ons can be enabled to add new features','appointment-hour-booking'); ?>.</p>
            <p><?php _e('If you don\'t want to enable add ons now then <strong>continue saving these settings and publishing the booking form</strong>.','appointment-hour-booking'); ?></p>
            <?php
        }
     ?>
		<hr>
		<div class="ahb-buttons-container">
			<?php if ($current_user_access) { ?><input type="submit" name="savepublish" value="<?php _e('Save and Publish','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" /><?php } ?>
			<input type="submit" name="savereturn" value="<?php _e('Save and Return','appointment-hour-booking'); ?>" class="button button-primary" style="float:right;margin-right:10px" />
			<div class="clear"></div>
		</div>
    </div>

 </div>

 <div class="ahb-buttons-container">
	<a href="<?php print esc_attr(admin_url('admin.php?page='.$this->menu_parameter));?>" class="ahb-return-link">&larr;<?php _e('Return to the calendars list','appointment-hour-booking'); ?></a>
 </div>

</div>



[<a href="https://apphourbooking.dwbooster.com/contact-us" target="_blank"><?php _e('Request Custom Modifications','appointment-hour-booking'); ?></a>] | [<a href="https://wordpress.org/support/plugin/appointment-hour-booking#new-post" target="_blank"><?php _e('Free Support','appointment-hour-booking'); ?></a>] | [<a href="<?php echo $this->plugin_URL; ?>" target="_blank"><?php _e('Help','appointment-hour-booking'); ?></a>]
</form>

<script type="text/javascript">generateCaptcha();</script>
 <script>
  jQuery( function() {
    jQuery( "#admin-tabs" ).tabs();
  } );
  </script>