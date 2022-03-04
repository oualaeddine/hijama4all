try {
    for (var i=0; i<100;i++)
    {
    	try {
        var tt = eval("cpmvc_configmultiview"+i);        
        if (tt)
        {
            (function($) {    
                mvcconfig = $.parseJSON(tt.obj);                
                if (mvcconfig.params.otherparams)
                {
                    //console.log("var others={"+mvcconfig.params.otherparams+"};");
                    mvcconfig.params.otherparams = mvcconfig.params.otherparams.replace(/#/g,'"');                    
                    eval("var others={"+mvcconfig.params.otherparams+"};");                    
                    mvcconfig.params = $.extend(mvcconfig.params, others);
                }
            })(jQuery); 
            mvcconfig.calendar = mvcconfig.calendar.replace(/,/g,"-");  
            var pathCalendar = mvcconfig.ajax_url;
            if ( document.getElementById("cal"+mvcconfig.calendar+"_"+i) !== null)
                initMultiViewCal("cal"+mvcconfig.calendar+"_"+i, mvcconfig.params.id,(mvcconfig.params));
        }
       }catch (e) {}  
    }
}catch (e) {} 