$.fbuilder.typeList.push(
	{
		id:"fapp",
		name:"Appointment",
		control_category:1
	}
);
$.fbuilder.controls[ 'fapp' ]=function(){  this.init();  };
$.extend(
	$.fbuilder.controls[ 'fapp' ].prototype,
	$.fbuilder.controls[ 'ffields' ].prototype,
	{
		title:"Appointment",
		ftype:"fapp",			
		services:new Array({name:"Sample Service",price:24.99,capacity:1,duration:60,pb:0,pa:0,ohindex:0}),
		/*openhours:new Array({type:"all",d:"",h1:8,m1:0,h2:17,m2:0}),new Array({name:"Default",openhours:new Array({type:"all",d:"",h1:8,m1:0,h2:17,m2:0})})*/
		openhours:new Array(),
		allOH:new Array({name:"Default",openhours:new Array({type:"all",d:"",h1:8,m1:0,h2:17,m2:0})}),
		dateFormat:"mm/dd/yy",
		showDropdown:false,
		showTotalCost:false,
		showTotalCostFormat:"$ {0}",
		showEndTime:false,
		showQuantity:false,
		usedSlotsCheckbox:false,
		avoidOverlaping:true,
		emptySelectCheckbox:false,
		emptySelect:"-- Please select service --",
		dropdownRange:"-10:+10",
		working_dates:[true,true,true,true,true,true,true],
		numberOfMonths:1,
		maxNumberOfApp:0,
		firstDay:0,
		minDate:"0",
		maxDate:"",
		defaultDate:"",
		invalidDates:"",
		tmpinvalidDates:[],
		required:true,
		bSlotsCheckbox: true,
		bSlots:30,
		militaryTime:1,
		autonum:0,
		autonumStaff:0,
		display:function()
		{
			return '<div class="fields '+this.name+' fapp" id="field'+this.form_identifier+'-'+this.index+'"><div class="arrow ui-icon ui-icon-play "></div><div title="Duplicate" class="copy ui-icon ui-icon-copy "></div><div title="Delete" class="remove ui-icon ui-icon-trash "></div><label>'+this.title+''+((this.required)?"*":"")+'</label><div class="dfield"><div class="fieldCalendarService'+this.name+'"></div><div class="fieldCalendar'+this.name+'"></div><div class="slotsCalendar'+this.name+'"></div><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';
		},
		msort:function(arr){
            for(var i =0;i<arr.length;i++){
                for(var j= i+1;j<arr.length;j++){
                    if(arr[i].type>arr[j].type || (arr[i].type==arr[j].type && arr[i].type=="special" && arr[i].d>arr[j].d) || (arr[i].type==arr[j].type && arr[i].type!="special" && arr[i].h1*60+arr[i].m1>arr[j].h1*60+arr[j].m1)){
                        var swap = arr[i];
                        arr[i] = arr[j];
                        arr[j] = swap;
                    }
                }
            }
            return arr;
        },
        normalizeSelectIndex:function(ind)
        {
            if (this.emptySelectCheckbox)
                ind--;
            return ind;
        },
        showopenhourAll:function()
		{
		    var str = "", e   = $( '#field' + this.form_identifier + '-' + this.index + ' .fieldCalendar'+this.name );
		    var me = this;
		    for (var i=0;i<me.allOH.length;i++)
		    {
		        str += '<div class="openhoursdiv" i="'+i+'">';
		        if (i>0)
		            str += '<div><label>Open hours:  <input type="text" class="nameopenhours" i="'+i+'" value="'+me.allOH[i].name+'"></label> <input type="button" i="'+i+'" class="deleteopenhours" value="Delete"></div>';
		        str += me.showOpenHours(me.allOH[i].openhours,me.dateFormat);
		        str += '<hr /></div>';    
		    }
		    $("#openHoursSection").html(str);
			$(".openhours_special").datepicker('destroy').datepicker({dateFormat:me.dateFormat,
				onSelect: function(d,inst) {
				    var i = inst.input.parents(".openhoursdiv").attr("i")*1;
					me.allOH[i].openhours[inst.input.attr("i")].d = $.datepicker.formatDate("yy-mm-dd", $.datepicker.parseDate(me.dateFormat, d));
					$.fbuilder.reloadItems({'field':me});
         	}});
         	$(".service_openhours").each( function(){
         	    var str = "";
				for (var i=0;i<me.allOH.length;i++)
  	  		        str += '<option  value="'+i+'">'+me.allOH[i].name+'</option>';
  	  		    $(this).html(str); 
  	  		    $(this).val(me.services[$(this).attr("i")*1].ohindex);
  	  		    if (me.allOH.length>1)
  	  		        $(this).parent().css("display","block");
  	  		    else
  	  		        $(this).parent().css("display","none");    
		    });
		},
        showOpenHours:function(openhours,df)
		{
		    var str="", dayNames = $.datepicker.regional[""].dayNames;
		    var me = this;
		    str += '<div><div class="labelT">Day(s)</div><div class="labelF">From</div><div class="labelF">To</div><div class="clearer"></div></div>';
		    for (var i=0;i<openhours.length;i++)
		    {
		        str += '<div class="choicesEdit">';
		        str += '<select class="openhours_type" i="'+i+'" "><option value="all" '+((openhours[i].type=='all')?"selected":"")+'>All days</option>';
		        for (var d=0;d<7;d++)
		            str += '<option value="d'+d+'" '+((openhours[i].type=="d"+d)?"selected":"")+'>'+dayNames[d]+'</option>';
		        str += '<option value="special" '+((openhours[i].type=='special')?"selected":"")+'>Only ...</option></select>';
		        str += '<input style="visibility:'+((openhours[i].type=="special")?"visible":"hidden")+'" class="openhours_special" i="'+i+'" type="text"  value="'+((openhours[i].type=="special")?$.fbuilder.htmlEncode($.datepicker.formatDate(df, $.datepicker.parseDate("yy-mm-dd", openhours[i].d))):"")+'"/>';
		        str += '<select class="openhours_from" i="'+i+'">';
		        for (var h=0;h<24;h++)
		          for (var m=0;m<60;m=m+5)
		            str += '<option h="'+h+'" m="'+m+'" value="'+((h<10)?"0":"")+h+":"+((m<10)?"0":"")+m+'" '+((openhours[i].h1==h && openhours[i].m1==m)?"selected":"")+'>'+me.formattime(h*60+m)+'</option>';    
		        str += '</select>';
		        str += '<select class="openhours_to" i="'+i+'">';
		        for (var h=0;h<24;h++)
		          for (var m=0;m<60;m=m+5)
		            str += '<option h="'+h+'" m="'+m+'" value="'+((h<10)?"0":"")+h+":"+((m<10)?"0":"")+m+'" '+((openhours[i].h2==h && openhours[i].m2==m)?"selected":"")+'>'+me.formattime(h*60+m)+'</option>'; 
		        str += '<option h="0" m="0" value="00:00">00:00</option>';        
		        str += '</select>';
		        str += '<a class="choice_add ui-icon ui-icon-circle-plus" i="'+i+'" title="Add another choice."></a><a class="choice_remove ui-icon ui-icon-circle-minus" i="'+i+'" title="Delete this choice."></a></div>';
		    }
		    return str;
		},	
		showSlots:function()
		{		
		    function isOpen(h,m,s)
		    {                
		    	for (var i=0;i<s.length;i++)
		          if ((h*60+m>=s[i].h1*60+s[i].m1) && (h*60+m<=s[i].h2*60+s[i].m2))
		              return "sopen";
		        return "";        
		    }			    
		    var slots = '';
		    for (var i=0;i<24;i++)
		    {
		        slots += '<div><div>'+i+'</div>';
		        for (var j=0;j<60;j=j+5)
		            slots += '<div id="d'+i+'-'+j+'" class="slot '+isOpen(i,j,this.slots)+'" ></div>';
		        slots += '</div>';
		    }
		    return slots+this.slots.length;
		},	
		editItemEvents:function()
		{
            var me = this;
            me.showopenhourAll();				    		
			var evt = [
			    {s:"#sDateFormat",e:"change", l:"dateFormat"},
				{s:"#sNumberOfMonths",e:"change", l:"numberOfMonths"},
				{s:"#sMaxNumberOfApp",e:"change", l:"maxNumberOfApp"},
				
				{s:"#sFirstDay",e:"change", l:"firstDay"},
				{s:"#sMilitaryTime",e:"change", l:"militaryTime"},
				
				{s:"#sMinDate",e:"change", l:"minDate"},
				{s:"#sMaxDate",e:"change", l:"maxDate"},
				{s:"#sInvalidDates",e:"change", l:"invalidDates"},
				{s:"#sDefaultDate",e:"change", l:"defaultDate"},
				{s:"#sDropdownRange",e:"keyup", l:"dropdownRange"},
				{s:"#sShowTotalCostFormat",e:"keyup", l:"showTotalCostFormat"},
				{s:"#sEmptySelectCheckbox",e:"click", l:"emptySelectCheckbox", f:function(el){
					var v = el.is(':checked'); 
					$("#sEmptySelectDiv")[( v ) ? 'show' : 'hide']();
					return v;
					}
				},
				{s:"#sBSlotsCheckbox",e:"click", l:"bSlotsCheckbox", f:function(el){
					var v = el.is(':checked'); 
					$("#sBSlotsDiv")[( v ) ? 'hide' : 'show']();
					return v;
					}
				},
				{s:"#sEmptySelect",e:"keyup", l:"emptySelect"},
				{s:"#sBSlots",e:"change", l:"bSlots"},							
				{s:"#sShowEndTime",e:"click", l:"showEndTime", f:function(el){
					return el.is(':checked');
					}
				},							
				{s:"#sShowQuantity",e:"click", l:"showQuantity", f:function(el){
					return el.is(':checked');
					}
				},
				{s:"#sUsedSlotsCheckbox",e:"click", l:"usedSlotsCheckbox", f:function(el){
					return el.is(':checked');
					}
				},				
				{s:"#sAvoidOverlaping",e:"click", l:"avoidOverlaping", f:function(el){
					return el.is(':checked');
					}
				},
				{s:"#sShowTotalCost",e:"click", l:"showTotalCost", f:function(el){
					var v = el.is(':checked'); 
					$("#divTotalCostFormat")[( v ) ? 'show' : 'hide']();
					return v;
					}
				},
				{s:"#sShowDropdown",e:"click", l:"showDropdown", f:function(el){
					var v = el.is(':checked'); 
					$("#divdropdownRange")[( v ) ? 'show' : 'hide']();
					return v;
					}
				}
			];
			$(".working_dates input").bind("click", {obj: this}, function(e) {
			  e.data.obj.working_dates[$(this).val()] = $(this).is(':checked');
				$.fbuilder.reloadItems({'field':e.data.obj});
		    });
			$(".service_name").bind("keyup", {obj: this}, function(e) 
			{
				e.data.obj.services[$(this).attr("i")].name= $(this).val();
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
		    $(".service_price").bind("keyup", {obj: this}, function(e) 
			{
				e.data.obj.services[$(this).attr("i")].price= Number($(this).val().replace(/[^0-9\.]+/g,""));
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
		    $(".service_capacity").bind("keyup", {obj: this}, function(e) 
			{
				e.data.obj.services[$(this).attr("i")].capacity= $(this).val();
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
			$(".service_duration").bind("change", {obj: this}, function(e) 
			{
				e.data.obj.services[$(this).attr("i")].duration= $(this).val();
				$.fbuilder.reloadItems({'field':e.data.obj});
			});						
			$(".service_paddingbefore").bind("change", {obj: this}, function(e) 
			{
				e.data.obj.services[$(this).attr("i")].pb = parseInt($(this).val());
				$.fbuilder.reloadItems({'field':e.data.obj});
			});						
			$(".service_paddingafter").bind("change", {obj: this}, function(e) 
			{
				e.data.obj.services[$(this).attr("i")].pa = parseInt($(this).val());
				$.fbuilder.reloadItems({'field':e.data.obj});
			});						
			$(document).off("change", ".service_openhours").on("change", ".service_openhours", {obj: this}, function(e)
			{
			    e.data.obj.services[$(this).attr("i")].ohindex = parseInt($(this).val());
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
		    $(".services .choice_up").bind("click", {obj: this}, function(e) 
			{
				var i = $(this).attr("i")*1;
				if (i!=0)
				    e.data.obj.services.splice(i-1, 0, e.data.obj.services.splice(i, 1)[0]);
				$.fbuilder.editItem(e.data.obj.index);
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
		    $(".services .choice_down").bind("click", {obj: this}, function(e) 
			{
				var i = $(this).attr("i")*1;
				var n = $(this).attr("n")*1;
				if (i!=n)
				    e.data.obj.services.splice(i, 0, e.data.obj.services.splice(i+1, 1)[0]);
				$.fbuilder.editItem(e.data.obj.index);
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
		    $(".services .choice_add").bind("click", {obj: this}, function(e) 
			{
			    e.data.obj.autonum++;
				e.data.obj.services.splice($(this).attr("i")*1+1,0,{name:"Sample Service",price:24.99,capacity:1,duration:60,pb:0,pa:0,ohindex:0,idx:e.data.obj.autonum});
				$.fbuilder.editItem(e.data.obj.index);
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
			$(".services .choice_remove").bind("click", {obj: this}, function(e) 
			{
				if (e.data.obj.services.length>1)
					e.data.obj.services.splice( $(this).attr("i")*1, 1 )							
				$.fbuilder.editItem(e.data.obj.index);
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
			$(".openhours").off("change", ".openhours_type").on("change", ".openhours_type", {obj: this}, function(e)
			{
			    var i = $(this).parents(".openhoursdiv").attr("i")*1;
				e.data.obj.allOH[i].openhours[$(this).attr("i")].type= $(this).val();
				if ($(this).val()=="special")
				    $(this).parents(".choicesEdit").find(".openhours_special").css("visibility","visible");
				else
					$(this).parents(".choicesEdit").find(".openhours_special").css("visibility","hidden");    
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
			$(".openhours").off("change", ".openhours_from").on("change", ".openhours_from", {obj: this}, function(e)
			{
			    var i = $(this).parents(".openhoursdiv").attr("i")*1;
			    e.data.obj.allOH[i].openhours[$(this).attr("i")].h1= $(this).find(":selected").attr("h");
				e.data.obj.allOH[i].openhours[$(this).attr("i")].m1= $(this).find(":selected").attr("m");						
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
			$(".openhours").off("change", ".openhours_to").on("change", ".openhours_to", {obj: this}, function(e)
			{
			    var i = $(this).parents(".openhoursdiv").attr("i")*1;
			    e.data.obj.allOH[i].openhours[$(this).attr("i")].h2= $(this).find(":selected").attr("h");
				e.data.obj.allOH[i].openhours[$(this).attr("i")].m2= $(this).find(":selected").attr("m");
				$.fbuilder.reloadItems({'field':e.data.obj});
			}); 
		    $(".openhours").off("click", ".choice_add").on("click", ".choice_add", {obj: this}, function(e)
			{
			    var i = $(this).parents(".openhoursdiv").attr("i")*1;
			    e.data.obj.allOH[i].openhours.splice($(this).attr("i")*1+1,0,{type:"all",d:"",h1:8,m1:0,h2:17,m2:0});    
				e.data.obj.allOH[i].openhours = me.msort(e.data.obj.allOH[i].openhours);
				$.fbuilder.editItem(e.data.obj.index);
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
			$(".openhours").off("click", ".choice_remove").on("click", ".choice_remove", {obj: this}, function(e) 
			{
			    var i = $(this).parents(".openhoursdiv").attr("i")*1;
			    if (e.data.obj.allOH[i].openhours.length>1)
				    e.data.obj.allOH[i].openhours.splice( $(this).attr("i")*1, 1 );								
				$.fbuilder.editItem(e.data.obj.index);
				$.fbuilder.reloadItems({'field':e.data.obj});
			});
			$(".openhours").off("click", "input.addOpenHours").on("click", "input.addOpenHours", {obj: this}, function(e) {
                e.data.obj.allOH[e.data.obj.allOH.length] = {name:"Special",openhours:new Array({type:"all",d:"",h1:8,m1:0,h2:17,m2:0})};
                me.showopenhourAll();
                $.fbuilder.reloadItems({'field':e.data.obj});
            });
            $(".openhours").off("click", "input.deleteopenhours").on("click", "input.deleteopenhours", {obj: this}, function(e) {
                var ind = $(this).attr("i")*1;
                for (var i=0;i<e.data.obj.services.length;i++)
                    if (e.data.obj.services[i].ohindex > ind)
                        e.data.obj.services[i].ohindex--;
                    else if (e.data.obj.services[i].ohindex == ind)
                        e.data.obj.services[i].ohindex = 0;            
  	            e.data.obj.allOH.splice(ind, 1 ); 
  	  	        me.showopenhourAll();
  	  	        $.fbuilder.reloadItems({'field':e.data.obj});
  	        });
  	        $(".openhours").off("keyup", "input.nameopenhours").on("keyup", "input.nameopenhours", {obj: this}, function(e) {		        
  	  	        e.data.obj.allOH[$(this).attr("i")*1].name = $(this).val();
  	  	        $.fbuilder.reloadItems({'field':e.data.obj});
  	        });
			$.fbuilder.controls[ 'ffields' ].prototype.editItemEvents.call(this, evt);
		},		  	    
		formattime:function(t)
		{
		    var me  = this;
		    if (t<0) t+=(24*60);
		    t = t % (24*60);
		    var h = Math.floor(t/60);
		    var m = t%60;
		    var suffix = "";
		    if (me.militaryTime==0)
		    {
		        if (h>12)
		        {
		            h = h-12;
		            suffix = " PM";
		        }
		        else if (h==12)
		            suffix = " PM"; 
		        else
		        {  
		            if (h==0) h=12;
		            suffix = " AM";
		        }    
		    }
			  return (((h<10)?"0":"")+h+":"+(m<10?"0":"")+m)+suffix;									
		},
		after_show:function()
		{ 
		    var me  = this,
				e   = $( '#field' + me.form_identifier + '-' + me.index + ' .fieldCalendar'+me.name ),
				d   = $( '#field' + me.form_identifier + '-' + me.index + ' .fieldCalendarService'+me.name ),
				str = "",
			    op = "";
			if (me.openhours.length>0)/*compatible with old version*/
			{
			    if (!me.openhours[0].name)
			    {
			        var obj = {name:"Default",openhours:me.openhours.slice(0)};
			        me.openhours = new Array();			     
			        me.openhours[0] = obj;			     
			    }
			    me.allOH = new Array();
			    me.allOH = me.openhours.slice(0);
			    me.openhours = new Array();
			}
			for (var i=0; i< me.services.length;i++)
			    me.services[i].ohindex = me.services[i].ohindex || 0;
		  	if (me.autonum==0)
			    for (var i=0; i< me.services.length;i++)
			    {   
			        me.autonum++; 
			        me.services[i].idx = me.autonum; 
			    }
			function onChangeDateOrService(d)
		  	{    
		  	    if (!(!me.emptySelectCheckbox || (me.emptySelectCheckbox && $(".fieldCalendarService"+me.name+" select option:selected").index() > 0 )))
		  	    {
		  	        $( '#field' + me.form_identifier + '-' + me.index + ' .slotsCalendar'+me.name ).html("");
		  	        return;
		  	    }
				function getSlots(arr, duration, bduration, pa, pb)
				{
				    var str = "";
				    var a1 = new Array();
				    var minutesStart = 0;
				    if (me.bSlotsCheckbox)
				        bduration += pa + pb;//
				    for (var i=0;i<arr.length;i++)
				    {
				    	
				    	st = arr[i].h1*60+arr[i].m1*1;
				    	et = arr[i].h2*60+arr[i].m2*1;
				    	st += pb;
				    	if (st >= et)
		  		            et += 24 * 60;
				    	while (st + duration + pa <= et  && st<24 * 60)
				    	{
				    	    if ($.inArray(st ,a1)==-1 )
				    	        if (st>=minutesStart)
				    	            a1[a1.length] = st;
				    	    st += bduration;
				    	}
				    }
				    a1.sort(function (a, b) {return a - b;});
				    for (var i=0;i<a1.length;i++)
				        str+= "<div>"+me.formattime(a1[i])+(me.showEndTime?("-"+me.formattime(a1[i]+duration)):"")+"</div>";
				    return str;	
				}
				var arr = new Array();								
				var day = d;
				var s = $( '#field' + me.form_identifier + '-' + me.index + ' .slotsCalendar'+me.name );
				var ohindex = me.services[me.normalizeSelectIndex($(".fieldCalendarService"+me.name+" select option:selected").index())].ohindex;
				for (var i=0;i<me.allOH[ohindex].openhours.length;i++)
				{
			        if (me.allOH[ohindex].openhours[i].type=="special")
				    {
				    	  arr[me.allOH[ohindex].openhours[i].d] = arr[me.allOH[ohindex].openhours[i].d] || [];
				    	  arr[me.allOH[ohindex].openhours[i].d][arr[me.allOH[ohindex].openhours[i].d].length] = me.allOH[ohindex].openhours[i];
				    }
				    else
				    {
				        arr[me.allOH[ohindex].openhours[i].type] = arr[me.allOH[ohindex].openhours[i].type] || [];
				        arr[me.allOH[ohindex].openhours[i].type][arr[me.allOH[ohindex].openhours[i].type].length] = me.allOH[ohindex].openhours[i];	
				    }        
				}
				var duration = $(".fieldCalendarService"+me.name+" select option:selected").val()*1;
				var bduration = duration;
				if (!me.bSlotsCheckbox)
				    bduration = me.bSlots*1;
				var st, et, str = "", ind = me.normalizeSelectIndex($(".fieldCalendarService"+me.name+" select option:selected").index());
				var pa = me.services[ind].pa * 1 || 0;								
				var pb = me.services[ind].pb * 1 || 0;
				if (arr[d])
				    str = getSlots(arr[d],duration,bduration,pa,pb);
				else if (arr["d"+$.datepicker.parseDate('yy-mm-dd', d).getDay()])
					  str = getSlots(arr["d"+$.datepicker.parseDate('yy-mm-dd', d).getDay()],duration,bduration,pa,pb);
				else if (arr["all"])
					  str = getSlots(arr["all"],duration,bduration,pa,pb);	  
				s.html("<div class=\"slots\">"+$.datepicker.formatDate(me.dateFormat, $.datepicker.parseDate("yy-mm-dd", d))+"<br />"+str+"</div>");
		  	}
		  					
			for (var i=0;i<me.services.length;i++)
			    str += '<option value="'+me.services[i].duration+'">'+me.services[i].name+'</option>';
			if (me.emptySelectCheckbox) 
			    str = '<option value="">'+ me.emptySelect +'</option>'+ str ;
			    
			d.html('<select>'+str+'</select>');
			if (me.emptySelectCheckbox)
		  	    d.find("select").prop('selectedIndex', 1);
			$(".fieldCalendarService"+me.name+" select").bind("change", function() 
			{
				if (e.datepicker("getDate"))
				    onChangeDateOrService($.datepicker.formatDate('yy-mm-dd', e.datepicker("getDate")))
			});
			this.tmpinvalidDates = this.invalidDates;
			this.tmpinvalidDates = this.tmpinvalidDates.replace( /\s+/g, '' );
			if( !/^\s*$/.test( this.tmpinvalidDates ) )
			{
				  var	dateRegExp = new RegExp( /^\d{1,2}\/\d{1,2}\/\d{4}$/ ),
				      counter = 0,
				      dates = this.tmpinvalidDates.split( ',' );
				  this.tmpinvalidDates = [];
				  for( var i = 0, h = dates.length; i < h; i++ )
				  {
				  	  var range = dates[ i ].split( '-' );
            
				  	  if( range.length == 2 && range[0].match( dateRegExp ) != null && range[1].match( dateRegExp ) != null )
				  	  {
				  	  	  var fromD = new Date( range[ 0 ] ),
				  	  	  	  toD = new Date( range[ 1 ] );
				  	  	  while( fromD <= toD )
				  	  	  {
				  	  	  	  this.tmpinvalidDates[ counter ] = fromD;
				  	  	  	  var tmp = new Date( fromD.valueOf() );
				  	  	  	  tmp.setDate( tmp.getDate() + 1 );
				  	  	  	  fromD = tmp;
				  	  	  	  counter++;
				  	  	  }
				  	  }
				  	  else
				  	  {
				  	  	  for( var j = 0, k = range.length; j < k; j++ )
				  	  	  {
				  	  	  	  if( range[ j ].match( dateRegExp ) != null )
				  	  	  	  {
				  	  	  	  	  this.tmpinvalidDates[ counter ] = new Date( range[ j ] );
				  	  	  	  	  counter++;
				  	  	  	  }
				  	  	  }
				  	  }
				  }
			}
		
		    me.special_days = new Array();
		    if (!me.emptySelectCheckbox || (me.emptySelectCheckbox && $(".fieldCalendarService"+me.name+" select option:selected").index() > 0 ))
		    {
		        var ohindex = me.services[me.normalizeSelectIndex($(".fieldCalendarService"+me.name+" select option:selected").index())].ohindex;		    
		        for (var i=0;i<me.allOH[ohindex].openhours.length;i++)
		            if (me.allOH[ohindex].openhours[i].type=="special")
		                me.special_days[me.special_days.length] = me.allOH[ohindex].openhours[i].d;	  
		    }
		    if ($("#date_format").length>0)
		        me.dateFormat = $("#date_format").val();
		     
		  	var hrs = 0;
		  	me.minDateTmp = me.minDate;
		  	if (me.minDate!=="")
		    {
		        if (me.minDate.indexOf("h")!= -1)
		        {		            
		            if (me.minDate.indexOf(" ")!= -1)
		            {
		                var a = me.minDate.split(" ");
		                var find = false;
		                for (var i=0;(i<a.length && !find);i++)
		                {
		                    if (a[i].indexOf("h")!= -1)
		                    {
		                        find = true;
		                        hrs = parseInt(a[i].replace("h",""));
		                        me.minDateTmp = me.minDate.replace(a[i],"");
		                    }
		                }
		            }
		            else
		            {
		                hrs = parseInt(me.minDate.replace("h",""));
		                me.minDateTmp = 0;
		            }
		        }
		    }
		    e.datepicker({numberOfMonths:parseInt(me.numberOfMonths),
		    	firstDay:parseInt(me.firstDay),
		    	minDate:me.minDateTmp,
		    	maxDate:me.maxDate,
		    	dateFormat:me.dateFormat,
		    	defaultDate:me.defaultDate,
		    	changeMonth: me.showDropdown, 
		    	changeYear: me.showDropdown,
		    	yearRange: ((me.showDropdown)?me.dropdownRange:""),
		    	onSelect: function(d,inst) {
		    		onChangeDateOrService($.datepicker.formatDate("yy-mm-dd", $.datepicker.parseDate(me.dateFormat, d)));
             	},
		    	beforeShowDay: function (d) {
		    		var day = $.datepicker.formatDate('yy-mm-dd', d);
                    var c =  new Array(day);
                    if (me.working_dates[d.getDay()]==0  && ($.inArray(day, me.special_days) == -1))
                        c.push("nonworking");
                    for( var i = 0, l = me.tmpinvalidDates.length; i < l; i++ )
                    {
                    	if (d.getTime() === me.tmpinvalidDates[i].getTime())
                    	   c.push("nonworking invalidDate");
                    }    
                    return [true,c.join(" ")];
	              }
	        });
	        if (me.minDateTmp!=="")
		    {	
		        me.getMinDate = e.datepicker("getDate");
		        var t = new Date();
		        var isRelativeDate = 1;
		        try{
		          $.datepicker.parseDate(me.dateFormat,me.minDateTmp);
		          isRelativeDate = 0;
		        } catch (e) {}		            
		        me.getMinDate = new Date((me.getMinDate.getTime() + isRelativeDate * t.getHours() * 60 * 60 * 1000 + isRelativeDate * t.getMinutes() * 60 * 1000 + hrs * 60 * 60 * 1000) );
		        e.datepicker("option", "minDate", me.getMinDate );
		        e.datepicker("setDate", me.getMinDate);
		    }
	        me.tmpinvalidDatestime = new Array();
            for (var i=0;i<me.tmpinvalidDates.length;i++)
                me.tmpinvalidDatestime[i]=me.tmpinvalidDates[i].getTime();
            function DisableSpecificDates(date) {
                var currentdate = date.getTime();
                if ($.inArray(currentdate, me.tmpinvalidDatestime) > -1 ) 
                    return false;
                if (me.working_dates[date.getDay()]==0)
                    return false; 
                return true;
            }
            var sum = 0;
            for (var i=0;i<me.working_dates.length;i++)
                sum += me.working_dates[i];
            if (sum>0)
            {       
	            var nextdateAvailable = e.datepicker("getDate");
                while (!DisableSpecificDates(nextdateAvailable))
                    nextdateAvailable.setDate(nextdateAvailable.getDate() + 1);
	            e.datepicker("setDate", nextdateAvailable);    
	            onChangeDateOrService($.datepicker.formatDate('yy-mm-dd', nextdateAvailable));
	        }
        },
		showSpecialDataInstance: function() 
		{    
			var str = "", e   = $( '#field' + this.form_identifier + '-' + this.index + ' .fieldCalendar'+this.name ),dayNames  = $.datepicker.regional[""].dayNames;
			str += '<div class="choicesSet services"><label>Services [<a class="helpfbuilder" text="Add the services offered:\n\n.Name: The service name\n\n.Price: Price number only, without currency symbol or code\n\n.Capacity: For example the number of persons can book/attend the same service at the same time, default is 1\n\n.Duration: The service duration, example 30 mins\n\n.Padding: The padding time is a period before and/or after the appointment not used for other bookings. It can be used for prep for next appointment, travel, clean-up or a snack/bathroom break.">help?</a>]</label>';
			for (var i=0;i<this.services.length;i++)
			{
			    str += '<div class="choicesEdit" style="background:#ddd">';
			    str += '<div><div class="labelN">Name</div><div class="labelahb">Price</div><div class="labelahb">Capacity</div><div class="clearer"></div></div>';
			    str += '<input class="service_name" i="'+i+'" type="text" name="sService'+this.name+'" id="sService'+this.name+'" value="'+$.fbuilder.htmlEncode(this.services[i].name)+'"/><input class="service_price" i="'+i+'" type="text" name="sService'+this.name+'P'+i+'" id="sService'+this.name+'P'+i+'" value="'+$.fbuilder.htmlEncode(this.services[i].price)+'"/>';
			    str += '<input class="service_capacity" i="'+i+'" type="text" name="sService'+this.name+'C'+i+'" id="sService'+this.name+'C'+i+'" value="'+$.fbuilder.htmlEncode(((parseInt(this.services[i].capacity)>0)?this.services[i].capacity:"1"))+'"/>';
			    str += '<div><div class="labelahb">Duration</div><div >Padding time before and after</div><div class="clearer"></div></div>';
			    str += '<select class="service_duration" i="'+i+'" name="sService'+this.name+'D'+i+'" id="sService'+this.name+'D'+i+'">';
			    for (var j=1;j<15;j++)
			        str += '<option value="'+(j)+'" '+((this.services[i].duration==j)?"selected":"")+'>'+(j)+' min</option>';
			    for (var j=3;j<=24*12;j++)
			        str += '<option value="'+(5*j)+'" '+((this.services[i].duration==5*j)?"selected":"")+'>'+(5*j)+' min</option>';    
			    str += '</select>';
			    str += '<select class="service_paddingbefore" i="'+i+'" name="sService'+this.name+'before'+i+'" id="sService'+this.name+'before'+i+'">';
			    for (var j=0;j<=6*12;j++)
			        str += '<option value="'+(5*j)+'" '+((this.services[i].pb==5*j)?"selected":"")+'>'+(5*j)+' min</option>';    
			    str += '</select>';
			    str += '<select class="service_paddingafter" i="'+i+'" name="sService'+this.name+'after'+i+'" id="sService'+this.name+'after'+i+'">';
			    for (var j=0;j<=6*12;j++)
			        str += '<option value="'+(5*j)+'" '+((this.services[i].pa==5*j)?"selected":"")+'>'+(5*j)+' min</option>';    
			    str += '</select>';
			    str += '<div>Open hours<br /><select class="service_openhours" i="'+i+'" ></select></div>';
			    str += '<a class="choice_down ui-icon ui-icon-arrowthick-1-s" i="'+i+'" n="'+(this.services.length-1)+'" title="Down"></a><a class="choice_up ui-icon ui-icon-arrowthick-1-n" i="'+i+'" title="Up"></a><a class="choice_add ui-icon ui-icon-circle-plus" i="'+i+'" title="Add another choice."></a><a class="choice_remove ui-icon ui-icon-circle-minus" i="'+i+'" title="Delete this choice."></a><hr /></div>';
			}
			str += '<div><input type="checkbox"  name="sBSlotsCheckbox" id="sBSlotsCheckbox" '+((this.bSlotsCheckbox)?"checked":"")+'/> Generate time slots automatically based on service duration';
			str += '<div id="sBSlotsDiv" style="display:'+((this.bSlotsCheckbox)?"none":"block")+'">Generate slots every <select class="BSlots" name="sBSlots" id="sBSlots">';
			for (var j=1;j<=24*12;j++)
			    str += '<option value="'+(5*j)+'" '+((this.bSlots==5*j)?"selected":"")+'>'+(5*j)+' min</option>';    
		    str += '</select></div></div>';
			str += '<div id="sBUsedSlotsDiv"><input type="checkbox"  name="sUsedSlotsCheckbox" id="sUsedSlotsCheckbox" '+((this.usedSlotsCheckbox)?"checked":"")+'/> Show used slots</div>';
			str += '<div><input type="checkbox"  name="sEmptySelectCheckbox" id="sEmptySelectCheckbox" '+((this.emptySelectCheckbox)?"checked":"")+'/> Make user choose service before display times';
			if (typeof apphourbk_cmadmin !== 'undefined')
			    str += '<div><input type="checkbox"  name="sAvoidOverlaping" id="sAvoidOverlaping" '+((this.avoidOverlaping)?"checked":"")+'/> Avoid overlaping between services [<a class="helpfbuilder" text="If checked only one service will used for each time-slot. If un-checked each time-slot availability will be calculated separately for each service.">help?</a>]</div>';
			str += '<div id="sEmptySelectDiv" style="display:'+((this.emptySelectCheckbox)?"block":"none")+'">Text ';			   
		    str += '<input type="text" name="sEmptySelect" id="sEmptySelect" value="'+this.emptySelect+'"></div></div>';
			str += '</div>';
			str += '<div class="choicesSet openhours"><label>Open hours: Default [<a class="helpfbuilder" text="Open hours for all dates, for each weekday or specific date. Examples: \n\n - For all days: All days from 08:00 to 17:00 hours. \n\n - For specific weekdays: Mondays from 08:00 to 17:00 hours. \n\n - For specific dates: 27/12/2018 from 08:00 to 10:00 hours.">help?</a>]</label>';
			str += '<div id="openHoursSection"></div>';
			if (typeof apphourbk_cmadmin !== 'undefined')
			    str += '<input type="button" class="addOpenHours" value="Add Special Open Hours">';
			str += '</div>';
			str += '<div class="working_dates"><label>Working dates </label><br /><input name="sWD0" id="sWD0" value="0" type="checkbox" '+((this.working_dates[0])?"checked":"")+'/>Su<input name="sWD1" id="sWD1" value="1" type="checkbox" '+((this.working_dates[1])?"checked":"")+'/>Mo<input name="sWD2" id="sWD2" value="2" type="checkbox" '+((this.working_dates[2])?"checked":"")+'/>Tu<input name="sWD3" id="sWD3" value="3" type="checkbox" '+((this.working_dates[3])?"checked":"")+'/>We<input name="sWD4" id="sWD4" value="4" type="checkbox" '+((this.working_dates[4])?"checked":"")+'/>Th<input name="sWD5" id="sWD5" value="5" type="checkbox" '+((this.working_dates[5])?"checked":"")+'/>Fr<input name="sWD6" id="sWD6" value="6" type="checkbox" '+((this.working_dates[6])?"checked":"")+'/>Sa</div>';
			var sfirst = "";
			for (var i=0;i<7;i++)
				sfirst += '<option value="'+i+'" '+((i==this.firstDay)?"selected":"")+'>'+dayNames[i]+'</option>';
			str += '<div><label>First Date</label><br /><label><select name="sFirstDay" id="sFirstDay">'+sfirst+'</select></div>';
			var snumberOfM = "";
			for (var i=1;i<=12;i++)
				snumberOfM += '<option value="'+i+'" '+((i==this.numberOfMonths)?"selected":"")+'>'+i+'</option>';
			str += '<div><label>Military Time</label><br /><label><select name="sMilitaryTime" id="sMilitaryTime"><option value="1" '+((1==this.militaryTime)?"selected":"")+'>Yes (24 hours)</option><option value="0" '+((1!=this.militaryTime)?"selected":"")+'>No (12 hours AM/PM)</option></select></div>';
            str += '<div><label>Number of months</label><br /><label><select name="sNumberOfMonths" id="sNumberOfMonths">'+snumberOfM+'</select></div>';
			var snumberOfApp = "";
			for (var i=0;i<=99;i++)
				snumberOfApp += '<option value="'+i+'" '+((i==this.maxNumberOfApp)?"selected":"")+'>'+((i==0)?"Unlimited":i)+'</option>';
			str += '<div><label>Max number of appointments</label><br /><label><select name="sMaxNumberOfApp" id="sMaxNumberOfApp">'+snumberOfApp+'</select></div>';
			str += '<div><label>Default date [<a class="helpfbuilder" text="You can put one of the following type of values into this field:\n\nEmpty: Leave empty for current date.\n\nDate: A Fixed date with the same date format indicated in the &quot;Date Format&quot; drop-down field.\n\nNumber: A number of days from today. For example 2 represents two days from today and -1 represents yesterday.\n\nString: A smart text indicating a relative date. Relative dates must contain value (number) and period pairs; valid periods are &quot;y&quot; for years, &quot;m&quot; for months, &quot;w&quot; for weeks, and &quot;d&quot; for days. For example, &quot;+1m +7d&quot; represents one month and seven days from today.">help?</a>]</label><br /><input class="medium" name="sDefaultDate" id="sDefaultDate" value="'+$.fbuilder.htmlEncode(this.defaultDate)+'" /></div>';
			str += '<div id="sdMindate"><label>Min date [<a class="helpfbuilder" text="You can put one of the following type of values into this field:\n\nEmpty: No min Date.\n\nDate: A Fixed date with the same date format indicated in the &quot;Date Format&quot; drop-down field.\n\nNumber: A number of days from today. For example 2 represents two days from today and -1 represents yesterday.\n\nString: A smart text indicating a relative date. Relative dates must contain value (number) and period pairs; valid periods are &quot;y&quot; for years, &quot;m&quot; for months, &quot;w&quot; for weeks, &quot;d&quot; for days, and &quot;h&quot; for hours. For example, &quot;+1m +7d&quot; represents one month and seven days from today.">help?</a>] [<a class="helpfbuilderfont"  href="https://apphourbooking.dwbooster.com/blog/2021/05/12/minimum-available-time/" target="_blank">Samples</a>]</label> <br /><input class="medium" name="sMinDate" id="sMinDate" value="'+$.fbuilder.htmlEncode(this.minDate)+'" /></div>';
			str += '<div id="sdMaxdate"><label>Max date [<a class="helpfbuilder" text="You can put one of the following type of values into this field:\n\nEmpty: No max Date.\n\nDate: A Fixed date with the same date format indicated in the &quot;Date Format&quot; drop-down field.\n\nNumber: A number of days from today. For example 2 represents two days from today and -1 represents yesterday.\n\nString: A smart text indicating a relative date. Relative dates must contain value (number) and period pairs; valid periods are &quot;y&quot; for years, &quot;m&quot; for months, &quot;w&quot; for weeks, and &quot;d&quot; for days. For example, &quot;+1m +7d&quot; represents one month and seven days from today.">help?</a>]</label><br /><input class="medium" name="sMaxDate" id="sMaxDate" value="'+$.fbuilder.htmlEncode(this.maxDate)+'" /></div>';
            str += '<div><label>Invalid Dates [<a class="helpfbuilder" text="To define some dates as invalid, enter the dates with the format: mm/dd/yyyy separated by comma; for example: 12/31/2014,02/20/2014 or by hyphen for intervals; for example: 12/20/2014-12/28/2014 ">help?</a>]</label><br /><input class="medium" name="sInvalidDates" id="sInvalidDates" value="'+$.fbuilder.htmlEncode(this.invalidDates)+'" /></div>';
            str += '<div><input type="checkbox" name="sShowDropdown" id="sShowDropdown" '+((this.showDropdown)?"checked":"")+'/><label>Show Dropdown Year and Month</label><div id="divdropdownRange" style="display:'+((this.showDropdown)?"":"none")+'">Year Range [<a class="helpfbuilder" text="The range of years displayed in the year drop-down: either relative to today\'s year (&quot;-nn:+nn&quot;), absolute (&quot;nnnn:nnnn&quot;), or combinations of these formats (&quot;nnnn:-nn&quot;)">help?</a>]: <input type="text" name="sDropdownRange" id="sDropdownRange" value="'+$.fbuilder.htmlEncode(this.dropdownRange)+'"/></div></div>';
			str += '<div><input type="checkbox" name="sShowEndTime" id="sShowEndTime" '+((this.showEndTime)?"checked":"")+'/><label>Show end time [<a class="helpfbuilder" text="If enabled it will display the end time for each time-slot based in the duration of the selected service.">help?</a>]</label></div>';
			str += '<div><input type="checkbox" name="sShowTotalCost" id="sShowTotalCost" '+((this.showTotalCost)?"checked":"")+'/><label>Show Total Cost</label><div id="divTotalCostFormat" style="display:'+((this.showTotalCost)?"":"none")+'">Total cost format [<a class="helpfbuilder" text="The string {0} will be replaced with the calculated cost. Keep the {0} reference. You can edit the currency symbol or add additional text.">help?</a>]: <input type="text" name="sShowTotalCostFormat" id="sShowTotalCostFormat" value="'+$.fbuilder.htmlEncode(this.showTotalCostFormat)+'"/></div></div>';
			str += '<div><input type="checkbox" name="sShowQuantity" id="sShowQuantity" '+((this.showQuantity)?"checked":"")+'/><label>Show quantity field [<a class="helpfbuilder" text="If enabled a drop-down field will be displayed above the calendar to select the quantity to book o the selected service (useful for services with capacity greater than 1).">help?</a>]</label></div>';
		    str += '<hr></hr>';
			return str;
		}
	}
);