<?php 

header('Content-Type: application/x-javascript; charset=UTF-8'); 

require 'jQuery.stringify.js';
require 'jquery.validate.js';


?>
fbuilderjQuery = (typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery(function(){
(function($) {
	// Namespace of fbuilder
	$.fbuilder = $.fbuilder || {};
	$.fbuilder[ 'objName' ] = 'fbuilderjQuery';	
	
<?php
	// Load Module files
	try 
	{
        $md = dir( dirname( __FILE__ )."/modules" );
		$modules_files = array();
        while( false !== ( $entry = $md->read() ) ) 
		{    
            if ( strlen( $entry ) > 3 && is_dir( $md->path.'/'.$entry ) )
			{
				if ( file_exists( $md->path.'/'.$entry.'/public' ) )
				{
					$m = dir( $md->path.'/'.$entry.'/public' );
					while( false !== ( $mentry = $m->read() ) )
					{	
						if( strlen( $mentry ) > 3 && strtolower( substr( $mentry, strlen( $mentry ) - 3 ) ) == '.js' )
						{
							$modules_files[] = $m->path.'/'.$mentry;
						}
					}
				}	
						
			}			
        }
		sort( $modules_files );
		foreach( $modules_files as $file )
		{
			require $file;
		}
	} 
	catch (Exception $e) 
	{
        // ignore the error
    }

	// Load Control files
    require 'fbuilder-pro-public.jquery.js';
    try {
        $d = dir( dirname( __FILE__ )."/fields-public" );
		$controls_files = array();
        while (false !== ($entry = $d->read())) {            
            if (strlen($entry) > 3 && strtolower(substr($entry,strlen($entry)-3)) == '.js')
                if ( file_exists( $d->path.'/'.$entry ) )
                    $controls_files[] = $d->path.'/'.$entry;
        }
		sort( $controls_files );
		foreach( $controls_files as $file )
		{
			require $file;
		}
    } catch (Exception $e) {
        // ignore the error
    }
?>
        var fcount = 1;
        var fcount_tags = 1;
        var fnum = "_"+fcount; 
        var cp_avoid_hidden = false;
        while (20>fcount || eval("typeof cp_appbooking_fbuilder_config"+fnum+" != 'undefined'"))
        {
            try {
            var cp_appbooking_fbuilder_config = eval("cp_appbooking_fbuilder_config"+fnum);
            while (20>fcount_tags && !$("#fbuilder_"+fcount_tags).length)
                fcount_tags++;
            cp_appbooking_fbuilder_config = $.parseJSON(cp_appbooking_fbuilder_config.obj);
            cp_appbooking_fbuilder_config.identifier = "_"+fcount_tags;
            
                var opt_identifier = $("#fieldlist_"+fcount_tags);
                opt_identifier.attr("fcount_tags",fcount_tags); 
                opt_identifier.attr("fnum",fnum); 
                opt_identifier.addClass("cp_avoid_hidden")
                var f = $("#fbuilder_"+fcount_tags).fbuilder(cp_appbooking_fbuilder_config);
			    f.fBuild.loadData("form_structure_"+fcount_tags);
			    $.fbuilder.configValidate($("#cp_appbooking_pform_"+fcount_tags));   
			    if((typeof opt_identifier.attr("fnum") !== 'undefined') && !opt_identifier.is(':hidden'))
			        $(opt_identifier).addClass("cp_v_v");
			    else
			        cp_avoid_hidden = true;
			            
     		} catch (e) {}
	    	fcount++;
            fcount_tags++;
	    	fnum = "_"+fcount;
	    }
	    
	    if (cp_avoid_hidden)
	        $( document ).each(
	            function()
	            {
	            	(new MutationObserver(
	            		function(mutationsList, observer)
	            		{   
	            			for(let k in mutationsList)
	            			{
	            			    var mutation = mutationsList[k];
	            				if (mutation.type === 'childList')
	            				{
	            					if(mutation.addedNodes.length)
	            					{
	            						try{ 
	            						    $(".cp_avoid_hidden").each(function(){
	            						        var opt_identifier= $("#"+$(this).attr("id"));
	            						        if((typeof opt_identifier.attr("fnum") !== 'undefined') && !opt_identifier.is(':hidden') && !opt_identifier.hasClass("cp_v_v"))
	            						        {
	            						            $(opt_identifier).addClass("cp_v_v");
	            						            var fnum = opt_identifier.attr("fnum");   
                                                    var fcount_tags = opt_identifier.attr("fcount_tags");
	            						            var cp_appbooking_fbuilder_config = eval("cp_appbooking_fbuilder_config"+fnum);
                                                    cp_appbooking_fbuilder_config = $.parseJSON(cp_appbooking_fbuilder_config.obj);
                                                    cp_appbooking_fbuilder_config.identifier = "_"+fcount_tags;
	            						            var f = $("#fbuilder_"+fcount_tags).fbuilder(cp_appbooking_fbuilder_config);
			                                        f.fBuild.loadData("form_structure_"+fcount_tags);
	            						            $.fbuilder.configValidate(opt_identifier.closest("form"));
	            						        }
	            						    })
	            						}catch(err){}
	            					}
	            				}
	            			}
	            		}
	            	)).observe(this, { childList: true, subtree: true });
	            }
	        );
})(fbuilderjQuery);
});