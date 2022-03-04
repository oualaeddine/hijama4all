<?php if ( !defined('CP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.'; exit; } ?>
<form class="cpp_form" name="<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>" id="<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>" action="<?php $this->get_site_url(); ?>" method="post" enctype="multipart/form-data" onsubmit="return <?php echo $this->prefix; ?>_pform_doValidate<?php echo '_'.$this->print_counter; ?>(this);"><input type="hidden" name="cp_pform_psequence" value="<?php echo '_'.$this->print_counter; ?>" /><input type="hidden" name="<?php echo $this->prefix; ?>_pform_process" value="1" /><input type="hidden" name="<?php echo $this->prefix; ?>_id" value="<?php echo $this->item; ?>" /><input type="hidden" name="cp_ref_page" value="<?php esc_attr($this->get_site_url()); ?>" /><input type="hidden" name="form_structure<?php echo '_'.$this->print_counter; ?>" id="form_structure<?php echo '_'.$this->print_counter; ?>" size="180" value="<?php echo $raw_form_str; ?>" /><input type="hidden" name="refpage<?php echo '_'.$this->print_counter; ?>" id="refpage<?php echo '_'.$this->print_counter; ?>" value=""><input type="hidden" name="<?php echo $this->prefix; ?>_pform_status" value="0" /><input name="anonce" type="hidden" value="<?php echo wp_create_nonce( 'cpappb_actions_bookingform' ); ?>"/> 
<?php if (is_admin() && !defined('APHOURBK_ELEMENTOR_EDIT_MODE') && (empty($_GET["action"]) || @$_GET["action"] != 'edit') && !defined('CPAPPHOURBK_BLOCK_TIMES')) {?>
  <fieldset style="border: 1px solid black; -webkit-border-radius: 8px; -moz-border-radius: 8px; border-radius: 8px; padding:15px;">
   <legend>Administrator options</legend>
    <input type="checkbox" name="sendemails_admin" value="1" vt="1" checked /> Send notification emails for this booking<br /><br />
  </fieldset> 
<?php } ?>
<div id="fbuilder">    
    <div id="fbuilder<?php echo '_'.$this->print_counter; ?>">
        <div id="formheader<?php echo '_'.$this->print_counter; ?>"></div>
        <div id="fieldlist<?php echo '_'.$this->print_counter; ?>"></div>
    </div>
</div>    
<div style="display:none">
<div id="cpcaptchalayer<?php echo '_'.$this->print_counter; ?>" class="cpcaptchalayer">
 <div class="fields" id="field-c0" style="display:none">
    <label></label>
    <div class="dfield"><!--addons-payment-options--></div>
    <div class="clearer"></div>
 </div>
<?php if (!is_admin() && $this->get_option('cv_enable_captcha', CP_APPBOOK_DEFAULT_cv_enable_captcha) != 'false') { ?>
  <?php _e("Security Code",'appointment-hour-booking'); ?>:<br />
  <img src="<?php echo $this->fixurl($this->get_site_url(), $this->prefix.'_captcha=captcha&ps=_'.$this->print_counter.'&inAdmin=1&width='.$this->get_option('cv_width', CP_APPBOOK_DEFAULT_cv_width).'&height='.$this->get_option('cv_height', CP_APPBOOK_DEFAULT_cv_height).'&letter_count='.$this->get_option('cv_chars', CP_APPBOOK_DEFAULT_cv_chars).'&min_size='.$this->get_option('cv_min_font_size', CP_APPBOOK_DEFAULT_cv_min_font_size).'&max_size='.$this->get_option('cv_max_font_size', CP_APPBOOK_DEFAULT_cv_max_font_size).'&noise='.$this->get_option('cv_noise', CP_APPBOOK_DEFAULT_cv_noise).'&noiselength='.$this->get_option('cv_noise_length', CP_APPBOOK_DEFAULT_cv_noise_length).'&bcolor='.$this->get_option('cv_background', CP_APPBOOK_DEFAULT_cv_background).'&border='.$this->get_option('cv_border', CP_APPBOOK_DEFAULT_cv_border).'&font='.$this->get_option('cv_font', CP_APPBOOK_DEFAULT_cv_font)); ?>"  id="captchaimg<?php echo '_'.$this->print_counter; ?>" alt="security code" border="0" class="skip-lazy" />
  <br /><?php _e("Please enter the security code",'appointment-hour-booking'); ?>:<br />
  <div class="dfield"><input type="text" size="20" name="hdcaptcha_<?php echo $this->prefix; ?>_post" id="hdcaptcha_<?php echo $this->prefix; ?>_post<?php echo '_'.$this->print_counter; ?>" value="" autocomplete="off" />
  <div class="cpefb_error message" id="hdcaptcha_error<?php echo '_'.$this->print_counter; ?>" generated="true" style="display:none;position: absolute; left: 0px; top: 25px;"><?php echo esc_attr(__($this->get_option('cv_text_enter_valid_captcha', CP_APPBOOK_DEFAULT_cv_text_enter_valid_captcha),'appointment-hour-booking')); ?></div>
  </div><br />
<?php } ?><!-- rcadon -->
</div>
</div>
<div id="cp_subbtn<?php echo '_'.$this->print_counter; ?>" class="cp_subbtn"><?php if (defined('CPAPPHOURBK_BLOCK_TIMES')) _e('Block selected dates','appointment-hour-booking'); else _e($button_label,'appointment-hour-booking'); ?></div>
<div style="clear:both;"></div>
<?php if (get_option('cp_cpappb_honeypot','') != '') echo '<input type="hidden" name="'.sanitize_key(get_option('cp_cpappb_honeypot','')).'" value="">'; ?>
</form>
<?php if (defined('APBCT_NAME')) { ?>
<script>
setInterval('cpapph<?php echo '_'.$this->print_counter; ?>()',2000);
function cpapph<?php echo '_'.$this->print_counter; ?>() {
document.<?php echo $this->prefix; ?>_pform<?php echo '_'.$this->print_counter; ?>.onsubmit = function(){ return <?php echo $this->prefix; ?>_pform_doValidate<?php echo '_'.$this->print_counter; ?>(this);};
}
</script>
<?php } ?>