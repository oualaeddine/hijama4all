<?php

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

global $wpdb, $cpappb_addons_active_list, $cpappb_addons_objs_list;

$message = "";

if( isset( $_GET[ 'b' ] ) && $_GET[ 'b' ] == 1 )
{
    $this->verify_nonce ($_GET["anonce"], 'cpappb_actions_list');
	// Save the option for active addons
	delete_option( 'cpappb_addons_active_list' );
	if( !empty( $_GET[ 'cpappb_addons_active_list' ] ) && is_array( $_GET[ 'cpappb_addons_active_list' ] ) ) 
	{
		update_option( 'cpappb_addons_active_list', $_GET[ 'cpappb_addons_active_list' ] );
	}	
	
	// Get the list of active addons
	$cpappb_addons_active_list = get_option( 'cpappb_addons_active_list', array() );
    $message = "Add Ons settings updated";
}

$nonce = wp_create_nonce( 'cpappb_actions_list' );

?>
<style>
	.clear{clear:both;}
	.ahb-addons-container {
		border: 1px solid #e6e6e6;
		padding: 20px;
		border-radius: 3px;
		-webkit-box-flex: 1;
		flex: 1;
		margin: 1em 1em 1em 0;
		min-width: 200px;
		background: white;
		position:relative;
	}
	.ahb-addons-container h2{margin:0 0 20px 0;padding:0;}
	.ahb-addon{border-bottom: 1px solid #efefef;padding: 10px 0;}
	.ahb-addon:first-child{border-top: 1px solid #efefef;}
	.ahb-addon:last-child{border-bottom: 0;}
	.ahb-addon label{font-weight:600;}
	.ahb-addon p{font-style:italic;margin:5px 0 0 0;}
	.ahb-first-button{margin-right:10px !important;}
    
    .ahb-buttons-container{margin:1em 1em 1em 0;}
    .ahb-return-link{float:right;}

	.ahb-disabled-addons {
		background: #f9f9f9;
	}
	.ahb-addons-container h2{margin-left:30px;}
	.ahb-disabled-addons *{
		color:#888888;
	}
	.ahb-disabled-addons input{
		pointer-events: none !important;
	}

	/** For Ribbon **/
	.ribbon {
		position: absolute;
		left: -5px; top: -5px;
		z-index: 1;
		overflow: hidden;
		width: 75px; height: 75px;
		text-align: right;
	}
	.ribbon span {
		font-size: 10px;
		font-weight: bold;
		color: #FFF;
		text-transform: uppercase;
		text-align: center;
		line-height: 20px;
		transform: rotate(-45deg);
		-webkit-transform: rotate(-45deg);
		width: 100px;
		display: block;
		background: #79A70A;
		background: linear-gradient(#2989d8 0%, #1e5799 100%);
		box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
		position: absolute;
		top: 19px; left: -21px;
	}
	.ribbon span::before {
		content: "";
		position: absolute; left: 0px; top: 100%;
		z-index: -1;
		border-left: 3px solid #1e5799;
		border-right: 3px solid transparent;
		border-bottom: 3px solid transparent;
		border-top: 3px solid #1e5799;
	}
	.ribbon span::after {
		content: "";
		position: absolute; right: 0px; top: 100%;
		z-index: -1;
		border-left: 3px solid transparent;
		border-right: 3px solid #1e5799;
		border-bottom: 3px solid transparent;
		border-top: 3px solid #1e5799;
	}
h2.category {
    background-color: #C3E0E5;
    border-bottom: 1px solid #336699;
    color: #224477;
    margin:0px;
    padding: 5px;
}    
h2.categorycommercial {
    background-color: #dddddd;
    border-bottom: 1px solid #666666;
    color: #555555;
    margin:0px;
    padding: 5px;
}    
a.addonshelp { text-decoration: none; color: #aaaaaa; }
a.addonshelp:hover { text-decoration: none; color: #0000ff; }    
</style>

<script type="text/javascript">
    
 function cp_activateAddons()
 {
    var cpappb_addons = document.getElementsByName("cpappb_addons"),
		cpappb_addons_active_list = [];
	for( var i = 0, h = cpappb_addons.length; i < h; i++ )
	{
		if( cpappb_addons[ i ].checked ) cpappb_addons_active_list.push( 'cpappb_addons_active_list[]='+encodeURIComponent( cpappb_addons[ i ].value ) );
	}	
	document.location = 'admin.php?page=<?php echo $this->menu_parameter; ?>_addons&anonce=<?php echo $nonce; ?>&b=1&r='+Math.random()+( ( cpappb_addons_active_list.length ) ? '&'+cpappb_addons_active_list.join( '&' ) : '' )+'#addons-section';
 }    
 
</script>

<a id="top"></a>

<h1><?php _e('Appointment Hour Booking - Add Ons','appointment-hour-booking'); ?></h1>

<?php if ($message) echo "<div id='setting-error-settings_updated' class='updated' style='margin:0px;'><h2>".$message."</h2></div> <br />";
 ?>

<div class="ahb-buttons-container">
	<a href="<?php print esc_attr(admin_url('admin.php?page='.$this->menu_parameter));?>" class="ahb-return-link">&larr;<?php _e('Return to the calendars list','appointment-hour-booking'); ?></a>
	<div class="clear"></div>
</div>


<input type="button" value="Activate/Deactivate Marked Add Ons" onclick="cp_activateAddons();" class="button button-primary ahb-first-button" />
<input type="button" value="Get The Full List of Add Ons" onclick="document.location='?page=cp_apphourbooking_upgrade';"class="button" />
<div class="clear"></div>

<!-- Add Ons -->
<h2><?php _e('Add Ons','appointment-hour-booking'); ?></h2>

<?php
$printedmsg = false;
$newlist = array();
foreach( $cpappb_addons_objs_list as $key => $obj )
{
    if (!isset($newlist[$obj->category]))
        $newlist[$obj->category] = array();
    $newlist[$obj->category][] = array ($key, $obj); 
}

ksort($newlist);

foreach ($newlist as $category => $cpappb_addons_objs_list_sublist)
{ 

  for ($i=0; $i<count($cpappb_addons_objs_list_sublist)-1; $i++)
      for ($j=$i+1; $j<count($cpappb_addons_objs_list_sublist); $j++)
          if ($cpappb_addons_objs_list_sublist[$i][1]->get_addon_name() > $cpappb_addons_objs_list_sublist[$j][1]->get_addon_name())
          {
              $tmp = $cpappb_addons_objs_list_sublist[$i];
              $cpappb_addons_objs_list_sublist[$i] = $cpappb_addons_objs_list_sublist[$j];
              $cpappb_addons_objs_list_sublist[$j] = $tmp;
          }

 $is_commercial = ($category[0] != ' ');
?>


<?php if (!$printedmsg && $is_commercial) { $printedmsg = true; ?><h2><?php _e('The following Add Ons are included in the commercial versions of the plugin:','appointment-hour-booking'); ?></h2><?php } ?>

<div class="ahb-addons-container">
   <?php if ($is_commercial) { ?><div class="ribbon"><span>Upgrade</span></div><?php } ?>
	<h2 class="category<?php if ($is_commercial) echo 'commercial'; ?>"><?php echo $category; ?></h2>
	<div class="ahb-addons-group">

	<?php
   // $i=0;
	for ($i=0; $i<count($cpappb_addons_objs_list_sublist); $i++)
	{
        $obj = $cpappb_addons_objs_list_sublist[$i];
		print '<div class="ahb-addon" style="padding-right:20px;border:0;width:320px;min-height:90px;float:left;">';
        if ($is_commercial)
            if (!empty($obj[1]->help))
                echo '<a style="text-decoration:none;color:#888888" target="_blank" href="'.$obj[1]->help.'">';
            else
                echo '<a style="text-decoration:none;color:#888888" target="_blank" href="https://apphourbooking.dwbooster.com/download">';
        if (!$is_commercial) print '<label>'; else print '<strong>';
        print '<input '.($is_commercial?'disabled':'').' type="checkbox" id="'.$obj[0].'" name="cpappb_addons" value="'.$obj[0].'" '.( ( $obj[1]->addon_is_active() ) ? 'CHECKED' : '' ).'>'.$obj[1]->get_addon_name();
        if (!$is_commercial) print '</label>'; else print '</strong>';
        if ($is_commercial)
            echo '</a>';
        if (!empty($obj[1]->help))
            echo ' &nbsp; <a class="addonshelp" target="_blank" href="'.$obj[1]->help.'">[?]</a>';
        print '<div style="margin-left:20px;color:#336699">'.$obj[1]->get_addon_description().'</div>';
        print '</div>';
        //$i++;
        //if (!(($i+1)%3)) echo '<div class="clear"></div>';
	}
	?>    
    <div class="clear"></div>
	</div>
</div>    

<?php } ?>    


<div class="ahb-to-top" style="margin-bottom:10px;"><a href="#top">&uarr; Top</a></div>

<input type="button" value="Activate/Deactivate Marked Add Ons" onclick="cp_activateAddons();" class="button button-primary ahb-first-button" />
<input type="button" value="Get The Full List of Add Ons" onclick="document.location='?page=cp_apphourbooking_upgrade';"class="button" />
<div class="clear"></div>