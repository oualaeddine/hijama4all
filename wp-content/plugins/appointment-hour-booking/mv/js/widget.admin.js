var $j = jQuery.noConflict();
function cpmv_verifyList(prefix) {   
    if ($j(prefix+"viewList").is(':checked'))
            $j(prefix+"listconfig").css("display","block");
    else
            $j(prefix+"listconfig").css("display","none");        
}
function cpmv_vcheck(prefix) {   
    var options = "";
    if ($j(prefix+"viewDay").is(':checked'))
        options += '<option value="day">Day</option>';
    if ($j(prefix+"viewWeek").is(':checked'))
        options += '<option value="week">Week</option>';
    if ($j(prefix+"viewMonth").is(':checked'))
        options += '<option value="month">Month</option>';
    if ($j(prefix+"viewNMonth").is(':checked'))
        options += '<option value="nMonth">nMonth</option>';
    if ($j(prefix+"viewList").is(':checked'))
        options += '<option value="list">List</option>';    
    $j(prefix+"viewdefault").html(options);       
}