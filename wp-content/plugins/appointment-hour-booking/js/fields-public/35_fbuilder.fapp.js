$.fbuilder.controls[ 'fapp' ] = function(){};
$.extend( 
	$.fbuilder.controls[ 'fapp' ].prototype, 
	$.fbuilder.controls[ 'ffields' ].prototype,
	{
		title:"Number",
		ftype:"fapp",			
		services:new Array({name:"Service 1",price:1,capacity:1,duration:60,pb:0,pa:0,ohindex:0}),
		/*openhours:new Array({type:"all",d:"",h1:8,m1:0,h2:17,m2:0}),new Array({name:"Default",openhours:new Array({type:"all",d:"",h1:8,m1:0,h2:17,m2:0})})*/
		openhours:new Array(),
		allOH:new Array({name:"Default",openhours:new Array({type:"all",d:"",h1:8,m1:0,h2:17,m2:0})}),
		usedSlots:new Array(),
		dateFormat:"mm/dd/yy",
		showDropdown:false,
		showTotalCost:false,
		showTotalCostFormat:"$ {0}",
		showEndTime:false,
		usedSlotsCheckbox:false,		
		avoidOverlaping:true,
		emptySelectCheckbox:false,
		emptySelect:"-- Please select service --",
		dropdownRange:"-10:+10",
		working_dates:[true,true,true,true,true,true,true],
		numberOfMonths:1,
		maxNumberOfApp:0,
		showAllServices:false,		
		allowDifferentQuantities:false,
		firstDay:0,
		minDate:"0",
		maxDate:"",
		defaultDate:"",
		invalidDates:"",
		required:true,			
		bSlotsCheckbox: true,
		bSlots:30,
		militaryTime:1,
		cacheArr:new Array(),
		getD:new Date(),
		formId:0,
		getMinDate:"",
		getMaxDate:"",
		arr:new Array(),
		allUsedSlots:new Array(),
		service_selected:0,
		quantity_selected:1,
		tz:0,
		loadOK:false,
		ignoreUsedSlots:false,
		initialapp:"",
		initialID:0,
		pctByDay:new Array(),
		htmlUsedSlots:new Array(),
		extras:0,
		sub_cost:0,
		percent:0,
		notShowBookedDate:true,
		showWeek:false,
		autonum:0,		
		slotsDate:[],
		getSplittedSlots:function(d,s)
		{	
		    function splitSlots (a,serviceindex) {
                var dots = new Array();
                for (var i=0;i<a.length;i++)
                {
                    dots[dots.length] = a[i].t1;
                    dots[dots.length] = a[i].t2;
                }
                dots.sort(function(a, b){return a - b});    
                var processed_dots = new Array();
                for (var i=0;i<dots.length;i++)
                    if (i==0 || dots[i] != dots[i-1])
                        processed_dots[processed_dots.length] = dots[i];
                var aoutput = new Array();
                for (var i=0;i<processed_dots.length-1;i++)
                {
                    var s = processed_dots[i];
                    var e = processed_dots[i+1];
                    var m1 = s%60;
                    var m2 = e%60;
                    var segment = {t1:s, t2:e, quantity:0,serviceindex:serviceindex,h1:(s-m1)/60,m1:m1,h2:(e-m2)/60,m2:m2};
                    for (var j=0;j<a.length;j++)
                        if ( 
                             (s>a[j].t1 && s<a[j].t2) || 
                             (e>a[j].t1 && e<a[j].t2) ||
                             (s==a[j].t1 && e==a[j].t2)
                           )
                            segment.quantity += a[j].quantity;
                    if (segment.quantity)    
                        aoutput[aoutput.length] = segment;          
                }
                return aoutput;
            }
		    var data = new Array();
		    d.sort(function(a, b){
		  	    if ((typeof a.serviceindex !== 'undefined') && (typeof b.serviceindex !== 'undefined'))
		  	        return a.serviceindex - b.serviceindex;
		  	    else if (typeof a.serviceindex === 'undefined')
		  	        return -1  - b.serviceindex;
		  	    else 
		  	        return a.serviceindex - (-1);        
		  	});
		  	var sid = -2;
		  	var dtmp = [];
		    for (var i=0;i<d.length;i++)
		    {   
		        if (typeof d[i].serviceindex !== 'undefined')
		        {
		            if (d[i].serviceindex!=sid)
		            {
		                if (i!=0)
		                    data = data.concat(splitSlots(dtmp,sid));
		                dtmp = [];
		                sid = d[i].serviceindex;		                    
		            }
		            dtmp[dtmp.length] = jQuery.extend({}, d[i]);     
		        }    
		        else
		            data[data.length] = jQuery.extend({}, d[i]);		        
		    }
		    if (dtmp.length!=0)
		        data = data.concat(splitSlots(dtmp,sid));
			return data;
		},
		getCompatSlots:function(d)
		{
		    
		    var data = new Array();
		    var find = false;
		    for (var i=0;i<d.length;i++)
			{
			    if (!d[i].quantity)
			        d[i].quantity = 1000;
			    var s = -1;    
			    if (typeof d[i].serviceindex !== 'undefined')
			        s = d[i].serviceindex;
			    d[i].service = new Array();
			    d[i].service[0] = s;    
			                                  
			    find = false; 
			    for (var j=0;j<data.length && !find;j++)
			        if (d[i].t1==data[j].t1 && d[i].t2==data[j].t2 && (d[i].serviceindex == data[j].serviceindex))
			        {
			            data[j].quantity += d[i].quantity;
			            data[j].currentSelection = data[j].currentSelection || d[j].currentSelection || false;
			            if (!$.inArray(d[i].service[0],data[j].service))
			                data[j].service[data[j].service.length] = d[i].service[0]; 
			            find = true;
			        }
			    if (!find)
			        data[data.length] = jQuery.extend({}, d[i]);             
			}
			return data;
		},
        normalizeSelectIndex:function(ind)
        {
            if (this.emptySelectCheckbox && ind > 0)
                ind--;
            return ind;
        },
		show:function()
		{
		    return '<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield fapp"><input class="field avoid_overlapping_before '+((this.required)?" required":"")+'" id="'+this.name+'" name="'+this.name+'" type="hidden" value="" summary="usedSlots"/><input id="'+this.name+'_services" name="'+this.name+'_services" type="hidden" value="0"/><input id="'+this.name+'_capacity" name="'+this.name+'_capacity" type="hidden" value="0"/><input class="" id="tcost'+this.name+'" name="tcost'+this.name+'" type="hidden" value=""/><div class="fieldCalendarService fieldCalendarService'+this.name+'"></div><div class="fieldCalendar fieldCalendar'+this.name+'"></div><div class="slotsCalendar slotsCalendar'+this.name+'"></div><div class="usedSlots usedSlots'+this.name+'"></div><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';
		},
		getSpecialDays:function()
		{
		    var me  = this;
		    var a = new Array();
		  	if (!me.emptySelectCheckbox || (me.emptySelectCheckbox && $(".fieldCalendarService"+me.name+" select option:selected").index() > 0 ))
		  	{
		  	    var ohindex = me.services[me.normalizeSelectIndex($(".fieldCalendarService"+me.name+" select option:selected").index())].ohindex;
			    for (var i=0;i<me.allOH[ohindex].openhours.length;i++)
			        if (me.allOH[ohindex].openhours[i].type=="special")
			            a[a.length] = me.allOH[ohindex].openhours[i].d;
			}
			return a;
	    },
	    getServiceInd:function(sid)
		{
		    var me  = this;
		    if (typeof me.getServiceIndArr === 'undefined')
		    {
		        me.getServiceIndArr = [];
		        for (var i=0; i< me.services.length;i++)
		            me.getServiceIndArr["idx"+me.services[i].idx] = i;
			}
			if (typeof me.getServiceIndArr["idx"+sid] !== 'undefined')
			    return me.getServiceIndArr["idx"+sid];
			else    
			    return -1;
	    },
	    normalizeRanges:function(a)
	    {
	        for (var i=0;i<a.length;i++)
	        {
                    a[i].t1 = a[i].h1 * 60 + a[i].m1*1;
			    	a[i].t2 = a[i].h2 * 60 + a[i].m2*1;
			    	if (a[i].t1 >= a[i].t2)
			    	    a[i].t2 += 24 * 60;
            }
	    },
	    initcacheOpenHours: function()
        {
            var me  = this;
            for (j=0;j<me.allOH.length;j++)
                me.normalizeRanges(me.allOH[j].openhours);
            me.cacheOpenHours = [];
            for (j=0;j<me.services.length;j++)
            {
                var ohindex = me.services[j].ohindex;
                var arr = [];
		  	    for (var i=0;i<me.allOH[ohindex].openhours.length;i++)
		  	    {
		  	        if (me.allOH[ohindex].openhours[i].type=="special")
		  	        {
		  	        	arr[me.allOH[ohindex].openhours[i].d] = arr[me.allOH[ohindex].openhours[i].d] || [];
		  	        	arr[me.allOH[ohindex].openhours[i].d][arr[me.allOH[ohindex].openhours[i].d].length] = jQuery.extend({capacity:me.services[j].capacity}, me.allOH[ohindex].openhours[i]);
		  	        }
		  	        else
		  	        {
		  	            arr[me.allOH[ohindex].openhours[i].type] = arr[me.allOH[ohindex].openhours[i].type] || [];
		  	            arr[me.allOH[ohindex].openhours[i].type][arr[me.allOH[ohindex].openhours[i].type].length] = jQuery.extend({capacity:me.services[j].capacity}, me.allOH[ohindex].openhours[i]);
		  	        }
		  	    }
		  	    me.cacheOpenHours[j] = arr;
		    }
        },
        getAvailablePartialSlots: function(d, part,s) 
        {   
            var me  = this;
            /*verify if not special_days and (not working_dates or not invalidDates )*/
            if (($.inArray(d, me.special_days) == -1))
            {
                var d2 = $.datepicker.parseDate("yy-mm-dd",d);
                if (me.working_dates[d2.getDay()]==0)
                    return new Array(); 
                for( var i = 0, l = me.invalidDates.length; i < l; i++ )
                {
                	if (d2.getTime() === me.invalidDates[i].getTime())
                	    return new Array(); 
                }       
            }
            var capacity_service = me.services[s].capacity;
            var a = [];
            if (me.cacheOpenHours[s][d])
			    a = me.cacheOpenHours[s][d].slice(0);
			else if (me.cacheOpenHours[s]["d"+$.datepicker.parseDate("yy-mm-dd", d).getDay()])
				a = me.cacheOpenHours[s]["d"+$.datepicker.parseDate("yy-mm-dd", d).getDay()].slice(0);
			else if (me.cacheOpenHours[s]["all"])
				a = me.cacheOpenHours[s]["all"].slice(0);
            me.arr[d]	= a;
            if (!me.duration)
            {
                var arr = new Array();
                return arr;
            }   
            var data1 = me.cacheArr[d];            
            if (!data1) data1 = new Array();
            var duration = parseFloat(me.services[s].duration);
            me.duration = duration;
		  	me.bduration = me.duration;
		  	if (!me.bSlotsCheckbox)
		  	    me.bduration = me.bSlots*1;
			var arr = new Array();
			for (var i=0;i<me.arr[d].length;i++)
			    arr[i] = jQuery.extend({}, me.arr[d][i]);			  		 	      
            for (var i=0;i<arr.length;i++)
			{
				arr[i].t1 = arr[i].h1 * 60 + arr[i].m1*1;
				arr[i].t2 = arr[i].h2 * 60 + arr[i].m2*1;
				if (arr[i].t1>=arr[i].t2)
				    arr[i].t2 += 24 * 60;
			}		
			if (me.ignoreUsedSlots)
			    var data2 = $.merge(data1.slice(0),[]);
			else
			{    
			    me.usedSlots[d] = me.usedSlots[d] || [];
			    var data2 = $.merge(data1.slice(0),me.usedSlots[d]);
			    var t = $.datepicker.parseDate("yy-mm-dd", d);
		        t.setDate(t.getDate() - 1);
				var bd = $.datepicker.formatDate("yy-mm-dd", t);
				me.usedSlots[bd] = me.usedSlots[bd] || [];
				for (var i=0;i<me.usedSlots[bd].length;i++)
		  		{
		  		    if ((me.usedSlots[bd][i].h1 > me.usedSlots[bd][i].h2 && me.usedSlots[bd][i].h2!=0) || me.usedSlots[bd][i].h2>24)
		  		    {
		  		        if (me.usedSlots[bd][i].h1>me.usedSlots[bd][i].h2)
		  		            me.usedSlots[bd][i].h2 += 24;    
		  		        var obj = jQuery.extend({}, me.usedSlots[bd][i]);
				        obj.h2 = me.usedSlots[bd][i].h2 - 24;
				        obj.h1 = 0;obj.m1 = 0;
				        obj.d = d;				        
				        data2[data2.length] = obj;
		  		    }				    
		  		}
			}
			for (var i=0;i<data2.length;i++)
			{
			    data2[i].t1 = data2[i].h1 * 60 + data2[i].m1*1;// - me.pb;
			    data2[i].t2 = data2[i].h2 * 60 + data2[i].m2*1;// + me.pa;
			    if (typeof data2[i].serviceindex !== 'undefined' && typeof data2[i].nopadding === 'undefined' )
			    {
			        try{
			        if (data2[i].t1==data2[i].t2)  data2[i].t2 += 24 * 60;    
			        data2[i].t1 -= me.services[data2[i].serviceindex].pb;
			        data2[i].t2 += me.services[data2[i].serviceindex].pa;
			        } catch (e) {}
			    }    			    
			}
			var data = $.merge(data2,part);
			for (var i=0;i<data.length;i++)
			{
			    data[i].t1 = data[i].t1 || (data[i].h1 * 60 + data[i].m1*1);
			    data[i].t2 = data[i].t2 || (data[i].h2 * 60 + data[i].m2*1);
				if (data[i].t1>data[i].t2)
				    data[i].t2 += 24 * 60;
			}	    
			if (typeof cp_hourbk_cmpublic !== 'undefined')
			    data = me.getSplittedSlots(data,s);
			data = me.getCompatSlots(data);
			for (var i=0;i<data.length;i++)
			{		
			    
			    if (me.avoidOverlaping && (data[i].quantity+me.quantity_selected>capacity_service || (data[i].service.length==0 || (data[i].service.length && data[i].service[0]!=s)))
			  //|| (!me.avoidOverlaping && (data[i].quantity+me.quantity_selected>capacity_service && (typeof data[i].serviceindex === 'undefined' || data[i].serviceindex==s)) ))
			    || (!me.avoidOverlaping && ((data[i].quantity+me.quantity_selected>capacity_service && data[i].serviceindex==s) || typeof data[i].serviceindex === 'undefined') )) 
			    {
			        for (var j=0;j<arr.length;j++)
			        {
			            if ((data[i].t1 > arr[j].t1) && (data[i].t1 < arr[j].t2)   &&  (data[i].t2 > arr[j].t1) && (data[i].t2 < arr[j].t2))
			            {
			            	var v1 = {t1:arr[j].t1,  t2:data[i].t1,   h1:arr[j].h1,  h2:data[i].h1,   m1:arr[j].m1,  m2:data[i].m1};
			            	var v2 = {t1:data[i].t2, t2:arr[j].t2,    h1:data[i].h2, h2:arr[j].h2,    m1:data[i].m2, m2:arr[j].m2};
			                arr.splice(j, 1, v1, v2);
			            	j--;
			            }
			            else if ((data[i].t1 > arr[j].t1) && (data[i].t1 < arr[j].t2))
			            {
			            	arr[j].t2 = data[i].t1;
			            	arr[j].h2 = data[i].h1;
			            	arr[j].m2 = data[i].m1;
			            } 
			            else if ((data[i].t2 > arr[j].t1) && (data[i].t2 < arr[j].t2))
			            {
			            	arr[j].t1 = data[i].t2;
			            	arr[j].h1 = data[i].h2;
			            	arr[j].m1 = data[i].m2;
			            }
			            else if ((data[i].t1 <= arr[j].t1) && (data[i].t2 >= arr[j].t2))
			            {
			            	arr.splice(j, 1);
			            	j--;
			            }
			        }
			    }
			}
			for (var i=0;i<arr.length;i++)
                arr[i].day = d;
            if (me.minDate!=="" && me.getMinDate!="")
            {
		        var current = me.getMinDate;
		    	var currenttime = current.getTime()-me.tz*60*60*1000;
			    for (var i=arr.length-1;i>=0;i--)
			    {
			        if ($.datepicker.parseDate("yy-mm-dd",arr[i].day).getTime()+arr[i].t2*60*1000 <= currenttime)
			            arr.splice(i, 1 );
			        else if ($.datepicker.parseDate("yy-mm-dd",arr[i].day).getTime()+arr[i].t1*60*1000 <= currenttime)
			        {
			            var st = arr[i].t1;//var st = arr[i].t1 + duration + me.pb + me.pa;
			            while ($.datepicker.parseDate("yy-mm-dd",arr[i].day).getTime() + st*60*1000 <= currenttime)
			            {
			                if (!me.bSlotsCheckbox)
		  	  	                st += me.bduration;
		  	  	            else    
		  	  	                st += duration + me.pb + me.pa;
		  	  	            //st += duration + me.pb + me.pa;    
			            }
			            var m1 = st % 60;
			            var h1 = (st - m1)/60;
			            arr[i].t1 = st;
			           	arr[i].h1 = h1;
			            arr[i].m1 = m1;
			        }			                
			    }        
            }
            if (me.maxDate!=="" && me.getMaxDate!="")
            {
		        var current = me.getMaxDate;
		    	var currenttime = current.getTime()+me.tz*60*60*1000;
			    for (var i=arr.length-1;i>=0;i--)
			    {
			        if ($.datepicker.parseDate("yy-mm-dd",arr[i].day).getTime()+arr[i].t1*60*1000 >= currenttime)
			            arr.splice(i, 1 );
			        else if ($.datepicker.parseDate("yy-mm-dd",arr[i].day).getTime()+arr[i].t2*60*1000 >= currenttime)
			        {    
			            var et = arr[i].t1;
			            while ($.datepicker.parseDate("yy-mm-dd",arr[i].day).getTime() + (et + duration)*60*1000 <= currenttime)
			                et += duration; 
			            var m2 = et % 60;
			            var h2 = (et - m2)/60;
			            arr[i].t2 = et;
			           	arr[i].h2 = h2;
			            arr[i].m2 = m2;
			        }			                
			    }        
            }  
            for (var i=arr.length-1;i>=0;i--)
			    if (arr[i].t1+duration > arr[i].t2 || arr[i].t1 > 24*60)
                    arr.splice(i, 1 );
			
			return arr;		  
			  		       
        },    	
		formattime: function(t,mt)/*mt=2 for database 09:00*/
		{
		    if (t<0) t+=(24*60);
		    t = t % (24*60);
		    var h = Math.floor(t/60);
			var m = t%60;
			var suffix = "";
			if (mt==0)
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
		            if (h==0 && mt!=2) h=12;
		            suffix = " AM";  
		        }    
			}
			return (((h<10)?((mt==2)?"0":"0"):"")+h+":"+(m<10?"0":"")+m)+suffix;									
		},
        formatString: function(obj,showdate,tz)
		{
            var me = this;
		    tz = tz * 60;
		    if (typeof obj.st === 'undefined')
		        obj.st = obj.h1*60+obj.m1*1;
		    if (typeof obj.et === 'undefined')
		        obj.et = obj.h2*60+obj.m2*1;    
		    var str = "";
		    if (showdate)
		    {
		        var d = $.datepicker.parseDate("yy-mm-dd", obj.d);
		        if (tz!=0)
		        {
		            if (obj.st+tz<0)
		                d.setDate(d.getDate() - 1);
		            else if (obj.st+tz>24*60)    
		                d.setDate(d.getDate() + 1);
		        }   
		        str += "<span class=\"d\">"+$.datepicker.formatDate(me.dateFormat, d)+"</span> ";
		    }
		    str += (showdate?"<span class=\"t\">":"");
		    str += me.formattime(obj.st+tz,me.militaryTime)+(me.showEndTime?("-"+me.formattime(obj.et+tz,me.militaryTime)):"");
		    str += (showdate?"</span>":"");    
		    return str;      
		},
        getCurrentSlots: function(arr,d,s)
        {
            var me = this;
            var duration = parseFloat(me.services[s].duration);
            var html = "";
            var htmlSlots = new Array();
		    var pb = 0;
		    var pa = 0;
		    var v = false;
		    var capacity_service = me.services[s].capacity;
            if (true)
		    		{ 
		    		    var compactUsedSlots = me.getCompatSlots(me.htmlUsedSlots[d])             
		    	        for (var i=0;i<compactUsedSlots.length;i++)
		    	        { 
		    	            //if (compactUsedSlots[i].quantity>=capacity_service && compactUsedSlots[i].serviceindex==s)
		    	            if (compactUsedSlots[i].serviceindex==s)
		    	            {
		    	                compactUsedSlots[i].st = compactUsedSlots[i].h1 * 60 + compactUsedSlots[i].m1;
		    	                compactUsedSlots[i].t = $.datepicker.parseDate("yy-mm-dd",compactUsedSlots[i].d).getTime()+compactUsedSlots[i].st*60*1000;
		    	                compactUsedSlots[i].html = "";		    	                
		                        var v = false;
		    	                if (me.minDate!=="" && me.getMinDate!="") //check with the min date
                                {
		                            var current = me.getMinDate;
		                        	var currenttime = current.getTime()-me.tz*60*60*1000;			                        
			                        if (compactUsedSlots[i].t > currenttime)
			                        {   
		    	                        v = true;
		    	                    }
		    	                }
		    	                else
		    	                    v = true;
		    	                if (v)
		    	                {
		    	                    if (compactUsedSlots[i].quantity>=capacity_service || compactUsedSlots[i].currentSelection)
		    	                        compactUsedSlots[i].html = '<div s="'+s+'" h1="'+compactUsedSlots[i].h1+'" m1="'+compactUsedSlots[i].m1+'" h2="'+compactUsedSlots[i].h2+'" m2="'+compactUsedSlots[i].m2+'" style="'+(!me.usedSlotsCheckbox?"display:none":"")+'" class="htmlUsed  '+((typeof compactUsedSlots[i].s !== 'undefined')?compactUsedSlots[i].s.replace(/ /g,"").toLowerCase()+" old":" choosen")+'"><a '+((typeof compactUsedSlots[i].e !== 'undefined')?"title=\""+compactUsedSlots[i].e+"\"":"")+'>'+me.formatString(compactUsedSlots[i],false,me.tz)+'</a>'+((typeof compactUsedSlots[i].e !== 'undefined')?"<div class=\"ahbmoreinfo\">"+compactUsedSlots[i].e+"</div>":"")+'</div>';
		    	                    compactUsedSlots[i].availableslot = false;
		    	                    htmlSlots[htmlSlots.length] = compactUsedSlots[i];
		    	                }       
		    	            }
		    	        }
		    		}
            
		    if ((typeof specialPadding === 'undefined'))
		    {
		        pb = me.pb;
		        pa = me.pa;
		    }
		  	for (var i=0;i<arr.length;i++)
		  	{
		  	  	st = arr[i].t1 || (arr[i].h1 * 60+arr[i].m1*1);
		  	  	et = arr[i].t2 || (arr[i].h2 * 60+arr[i].m2*1);
		  	  	if (st >= et)
		  	        et += 24 * 60;  
		  	  	st += me.pb;
		  	  	while (st + duration + me.pa <=et  && st < 24 * 60)
		  	  	{ 
		  	  	    html = "<div class=\"availableslot\"><a  s=\""+s+"\"  href=\"\" d=\""+arr[i].day+"\" h1=\""+Math.floor((st)/60)+"\" m1=\""+((st)%60)+"\" h2=\""+Math.floor((st+duration)/60)+"\" m2=\""+((st+duration)%60)+"\">"+me.formatString({st:st,et:st+duration},false,me.tz)+((typeof cp_hourbk_cmpublic !== 'undefined')?"<span class=\"ahb_slot_availability\"><span class=\"p\">ahbslotavailabilityP</span><span class=\"t\">ahbslotavailabilityT</span></span>":"")+"</a></div>";
		  	  	    htmlSlots[htmlSlots.length] = {availableslot:true,st:st,serviceindex:s,h1:Math.floor((st)/60),m1:((st)%60),h2:Math.floor((st+duration)/60),m2:((st+duration)%60),html:html,t:$.datepicker.parseDate("yy-mm-dd",arr[i].day).getTime()+st*60*1000};
		  	  	    if (!me.bSlotsCheckbox)
		  	  	        st += me.bduration;
		  	  	    else    
		  	  	        st += me.bduration + pa + pb;
		  	  	}
		  	}
		  	htmlSlots.sort(function(a, b){
		  	    if ((typeof cp_hourbk_cmpublic !== 'undefined') && (a.t == b.t))
		  	    {
		  	        if ((typeof a.quantity !== 'undefined') && (typeof b.quantity === 'undefined'))
		  	        {
		  	            b.html = b.html.replace("ahbslotavailabilityP",(capacity_service - a.quantity));
		  	            b.quantity = a.quantity;
		  	        }    
		  	        else if ((typeof b.quantity !== 'undefined') && (typeof a.quantity === 'undefined'))
		  	        {
		  	            a.html = a.html.replace("ahbslotavailabilityP",(capacity_service - b.quantity));
		  	            a.quantity = b.quantity;
		  	        }    
		  	    }
		  	    return a.t - b.t
		  	});
		  	//remove duplicates
		  	htmlSlots = htmlSlots.reduce(function(field, e1){  
                	var matches = field.filter(function(e2){return e1.html== e2.html}); 
                	if (matches.length == 0){ 
                		field.push(e1);  
                	}return field;
                }, []);
		  	htmlSlots = htmlSlots.reduce(function(field, e1){  
                	var matches = field.filter(function(e2){return e1.t== e2.t}); 
                	if (matches.length == 0){ 
                		field.push(e1);  
                	}
                	else
                	{
                	    for (var i=0;i<field.length;i++)
                	        if (field[i].t==e1.t && !field[i].availableslot && e1.availableslot)
                	        {
                	             field[i]= e1;
                	             break;        
                	        }
                	}
                	return field;
                }, []);
            me.usedSlots[d] = me.usedSlots[d] || [];	
		  	if (me.usedSlots[d].length>0 && htmlSlots.length>0)
		  	    for (var i=0;i<me.usedSlots[d].length;i++)
		  	        for (var j=0;j<htmlSlots.length;j++)
		  	            if (htmlSlots[j].serviceindex==me.usedSlots[d][i].serviceindex && htmlSlots[j].h1==me.usedSlots[d][i].h1 && htmlSlots[j].m1==me.usedSlots[d][i].m1 && htmlSlots[j].h2==me.usedSlots[d][i].h2 && htmlSlots[j].m2==me.usedSlots[d][i].m2 )
		  	            {
		  	                if (htmlSlots[j].html.indexOf("currentSelection")==-1) htmlSlots[j].html = htmlSlots[j].html.replace("htmlUsed","htmlUsed currentSelection");
		  	                if (htmlSlots[j].html.indexOf("currentSelection")==-1) htmlSlots[j].html = htmlSlots[j].html.replace("availableslot","availableslot currentSelection");
		  	            }
		  	return htmlSlots;    
        },
        getAvailableSlotsByService: function(d,s)
        {
            var me = this;
            var c = "s"+s+"q"+me.quantity_selected+"d"+d;
            if (typeof me.slotsDate[c]!== 'undefined')
                return me.slotsDate[c];		    
		    function setHtmlUsedSlots(d,st,et)
		    {
		        st = st * 60;
		        et = et * 60;
		        var htmlSlots = new Array();
		    	//if (me.bSlotsCheckbox && me.usedSlotsCheckbox)
		    	if (true)//if (me.usedSlotsCheckbox)
		    	{
		    	    me.cacheArr[d] = me.cacheArr[d] || [];
		    	    for (var i=0;i<me.cacheArr[d].length;i++)
		    	    {    
		    	        me.cacheArr[d][i].t1 = me.cacheArr[d][i].t1 || me.cacheArr[d][i].h1*60+me.cacheArr[d][i].m1*1;
		    	        me.cacheArr[d][i].t2 = me.cacheArr[d][i].t2 || me.cacheArr[d][i].h2*60+me.cacheArr[d][i].m2*1;
		    	        if (me.cacheArr[d][i].t1>=me.cacheArr[d][i].t2)
				             me.cacheArr[d][i].t2 += 24 * 60;
		    	        if (st<=me.cacheArr[d][i].t1 && et>=me.cacheArr[d][i].t1) 
		    	            htmlSlots[htmlSlots.length] = jQuery.extend({}, me.cacheArr[d][i]);
		    	    }
		    	    for (var i=0;me.usedSlots[d] && i<me.usedSlots[d].length;i++) 
		    	    {
		    	        me.usedSlots[d][i].t1 = me.usedSlots[d][i].t1 || me.usedSlots[d][i].h1*60+me.usedSlots[d][i].m1*1;
		    	        me.usedSlots[d][i].t2 = me.usedSlots[d][i].t2 || me.usedSlots[d][i].h2*60+me.usedSlots[d][i].m2*1;
		    	        if (me.usedSlots[d][i].t1>=me.usedSlots[d][i].t2)
				             me.usedSlots[d][i].t2 += 24 * 60;
		    	        if (st<=me.usedSlots[d][i].t1 && et>=me.usedSlots[d][i].t1)
		    	            htmlSlots[htmlSlots.length] = jQuery.extend({}, me.usedSlots[d][i]);
		    	    }
		    	}
		    	return htmlSlots;		        
		    }
		    var day = $.datepicker.parseDate("yy-mm-dd", d);
		    if (this.tz==0)
		    {
		        me.htmlUsedSlots[d] = setHtmlUsedSlots(d,0,24);
		        var arr = this.getAvailablePartialSlots(d,[{h1:0,m1:0,h2:0,m2:0}],s);
		    }    
		    else if (this.tz > 0)
		    {
		        day.setDate(day.getDate() - 1);
		        var d1 = $.datepicker.formatDate("yy-mm-dd",day);
		        var arr = $.merge(this.getAvailablePartialSlots(d1,[{h1:0,m1:0,h2:24-this.tz,m2:0}],s),this.getAvailablePartialSlots(d,[{h1:24-this.tz,m1:0,h2:24,m2:0}],s));
		    	me.htmlUsedSlots[d] = $.merge(setHtmlUsedSlots(d1,24-this.tz,24), setHtmlUsedSlots(d,0,24-this.tz));
		        
		    }  
		    else
		    {
		        day.setDate(day.getDate() + 1);
		        var d1 = $.datepicker.formatDate("yy-mm-dd",day);
		        var arr = $.merge(this.getAvailablePartialSlots(d,[{h1:0,m1:0,h2:this.tz*-1,m2:0}],s),this.getAvailablePartialSlots(d1,[{h1:this.tz*-1,m1:0,h2:24,m2:0}],s));
		        me.htmlUsedSlots[d] = $.merge(setHtmlUsedSlots(d,this.tz*-1,24), setHtmlUsedSlots(d1,0,this.tz*-1));
		        	        
		    }
		    me.slotsDate[c] = arr;
		    return arr;
        },
		getAvailableSlots: function(d)
		{     
		    var me = this;
            var c = "s"+(me.showAllServices?"":me.service_selected)+"q"+me.quantity_selected+"d"+d;
            if (typeof me.slotsDate[c]!== 'undefined')
                return me.slotsDate[c];
		    var a_max = [];
			if (!me.showAllServices)
			    a_max = this.getAvailableSlotsByService(d,me.service_selected);
			else
			{
			    me.availableSlotsByService = [];
			    for (var i=0; i< me.services.length;i++)
			    {
			        me.availableSlotsByService[i] = this.getAvailableSlotsByService(d,i);
			        if (me.availableSlotsByService[i].length > a_max.length)
			            a_max = me.availableSlotsByService[i].slice(0);			                
			    }
			}
		    me.slotsDate[c] = a_max;
		    return a_max;    
			    
		},
		rC: function(d) 
		{      
		    var me = this;
		    var day = $.datepicker.formatDate('yy-mm-dd', d);
            var c =  new Array(day,"d"+day);
            if (me.working_dates[d.getDay()]==0  && ($.inArray(day, me.special_days) == -1))
                c.push("nonworking","ui-datepicker-unselectable","ui-state-disabled");
            for( var i = 0, l = me.invalidDates.length; i < l; i++ )
            {
            	if (d.getTime() === me.invalidDates[i].getTime()   && ($.inArray(day, me.special_days) == -1))
            	    c.push("nonworking","ui-datepicker-unselectable","ui-state-disabled","invalidDate");
            }
            if (me.minDate!=="" && me.getMinDate!="" && day < $.datepicker.formatDate('yy-mm-dd', me.getMinDate))
                c.push("nonworking","ui-datepicker-unselectable","ui-state-disabled","beforemindate");
            if (me.maxDate!=="" && me.getMaxDate!="" && day > $.datepicker.formatDate('yy-mm-dd', me.getMaxDate))
                c.push("nonworking","ui-datepicker-unselectable","ui-state-disabled","aftermaxdate");    
            if (($.inArray("ui-datepicker-unselectable",c)==-1) &&  !me.emptySelectCheckbox || (me.emptySelectCheckbox && $(".fieldCalendarService"+me.name+" select option:selected").index() > 0 ))
            {
                var arr = me.getAvailableSlots(day);
                if (arr.length==0 && me.notShowBookedDate)
                    c.push("nonworking","ui-datepicker-unselectable","ui-state-disabled","notavailslot");
                if (typeof cp_hourbk_cmpublic !== 'undefined')
                {   
                    var used = 0; 
                    var cclass = c.join(" ");
                    var q = 0;
                    var total = 0;
                    if (!me.showAllServices) 
                    {      
                        var htmlSlots = me.getCurrentSlots(arr,day,me.service_selected);
		                for (var i=0;i<htmlSlots.length;i++)
                            if (htmlSlots[i].html!="")
                            {
                                q++;
                                used += ((typeof htmlSlots[i].quantity !== 'undefined')?htmlSlots[i].quantity:0) ;
                            }
                        total += me.services[me.service_selected].capacity*q;    
                    }        
		            else
		            {
		                for (var ii=0; ii< me.services.length;ii++)
		                {    
		                    q = 0;
		                    var htmlSlots = me.getCurrentSlots(arr,day,ii);
		                    for (var i=0;i<htmlSlots.length;i++)
                                if (htmlSlots[i].html!="")
                                {
                                    q++;
                                    used += ((typeof htmlSlots[i].quantity !== 'undefined')?htmlSlots[i].quantity:0) ;
                                }
                            total += me.services[ii].capacity*q;        
		                }    
		            }                        
                    if (cclass.indexOf("nonworking")==-1)
                        cclass +=" ahb_booked"+Math.floor(10*used/total);
                }    
                    
            } 
            if (typeof cclass === 'undefined') 
                var cclass = c.join(" ");          
            return [(cclass.indexOf("nonworking")==-1),cclass];		        
		},		
		after_show:function()
		{
		    function closeOtherDatepicker(){
		        $('#ui-datepicker-div').css("display","none");
		    }
		    setTimeout(closeOtherDatepicker,100);
		    try {$.fn.datepicker.noConflict();} catch (e) {}
		    if (typeof cp_hourbk_timezone !== 'undefined') 
		    {
		        var gmt = (parseFloat(cp_hourbk_timezone));
		        var local = (new Date().getTimezoneOffset()  * -1)/60;			 
                this.tz = local - gmt;
		    }		    		    
		  	var me  = this,
		  	    e   = $( '#field' + me.form_identifier + '-' + me.index + ' .fieldCalendar'+me.name ),
		  	    d   = $( '#field' + me.form_identifier + '-' + me.index + ' .fieldCalendarService'+me.name ),
		  	    str = "",
		  	    op = "";
		  	var capacity = "";
		    for (var i=0; i< me.services.length;i++)
		        capacity += ((i!=0)?";":"")+me.services[i].capacity;
		    $('#field' + me.form_identifier + '-' + me.index + ' #'+me.name+'_capacity').val(capacity);
		  	$('#field' + me.form_identifier + '-' + me.index).parents("form").bind('invalid-form.validate', function () {
		  	    setTimeout(function(){
		  	        if ($('#field' + me.form_identifier + '-' + me.index + ' #'+me.name).hasClass("cpefb_error") && $('#field' + me.form_identifier + '-' + me.index).parents("form").find(".field.cpefb_error").attr("id")==$('#field' + me.form_identifier + '-' + me.index + ' #'+me.name).attr("id"))
		  	        {
		  	            $("html, body").animate({ 
                            scrollTop: $('#field' + me.form_identifier + '-' + me.index + ' #'+me.name).parents(".dfield").find(".ahbfield_service").offset().top 
                        }, 100); 
		  	        }
		  	    },100);
            });  
		  	e.addClass("notranslate")
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
			var dd = "";
			if (me.initialapp!="")
			{   
			    try{
			    var s = me.initialapp.split(";");
			    var s2 = "";
			    var ind = 0;
			    for (var i=0;i<s.length;i++)
			    {
			        if (s[i]!="")
			        {
			            s2 = s[i].split(" ");
			            var tt = s2[1].split("/");
			            var t1 = tt[0].split(":");
			            var t2 = tt[1].split(":");
			            var ind = s2[2]*1;
			            var q = s2[3]*1; 
			            dd = s2[0];
			            me.usedSlots[dd] = me.usedSlots[dd] || [];
			            obj = {h1:t1[0]*1,m1:t1[1]*1,h2:t2[0]*1,m2:t2[1]*1,d:dd,serviceindex:ind,price:parseFloat(me.services[ind].price)*parseFloat(q),quantity:q};	            	
		  			    me.usedSlots[dd][me.usedSlots[dd].length] = obj; 
		  			    me.allUsedSlots[me.allUsedSlots.length] = obj;
		  			}
			    } 
			    me.initialServiceInd = ind;  
			    } catch (e) {}
			}
			for (var i=0; i< me.services.length;i++)
			    me.services[i].ohindex = me.services[i].ohindex || 0;
			if (me.autonum==0)
			    for (var i=0; i< me.services.length;i++)
			    {   
			        me.autonum++; 
			        me.services[i].idx = me.autonum; 
			    }
			me.initcacheOpenHours();           
		    function onChangeDateOrService(d)
		    {
		        if (!(!me.emptySelectCheckbox || (me.emptySelectCheckbox && $(".fieldCalendarService"+me.name+" select option:selected").index() > 0 )))
		        {
		            $( '#field' + me.form_identifier + '-' + me.index + ' .slotsCalendar'+me.name ).html("");
		  	        return;
		  	    }   
		  	    function getSlots(d)
		  		{		
		            var data1 = me.cacheArr[d];
		  			var duration = me.duration;
		  			me.bduration = me.duration;
		  		    if (!me.bSlotsCheckbox)
		  		        me.bduration = me.bSlots*1;	
		  			var arr = me.getAvailableSlots(d);
		  			var nextdateAvailable = $.datepicker.parseDate("yy-mm-dd", d);
		  			var c = "s"+(me.showAllServices?"":me.service_selected)+"q"+me.quantity_selected;
		  			var s = $( '#field' + me.form_identifier + '-' + me.index + ' .slotsCalendar'+me.name );
		  			var i =0;
		  			if (me.notShowBookedDate && (me.maxNumberOfApp==0 || me.allUsedSlots.length<me.maxNumberOfApp) && arr.length==0 && (!me.usedSlots[d] || me.usedSlots[d].length==0 || me.service_change))
		    		{
		    		    me.service_change = false;
                        while ((!DisableSpecificDates(nextdateAvailable) || (arr.length==0)) && i<400)
                        {
                            i++;
                            nextdateAvailable.setDate(nextdateAvailable.getDate() + 1);
                            arr = me.getAvailableSlots($.datepicker.formatDate("yy-mm-dd",nextdateAvailable));
                        }  
                        if (arr.length>0 )  
                        {
                            e.datepicker("setDate", nextdateAvailable);
                            me.getD = nextdateAvailable;
		                    onChangeDateOrService($.datepicker.formatDate("yy-mm-dd", nextdateAvailable));  
                        }
                        else
                        {
                            e.datepicker("setDate", me.getMinDate);
                            s.html("<div class=\"slots\">"+cp_hourbk_nomore_label+"</div>");                           
                        }
                        return;
		    		}
		    		me.service_change = false;
		    		function getStrSlots(arr,d,s)
		    		{	     
		  			    var str = "";				
		    		    var htmlSlots = me.getCurrentSlots(arr,d,s);
		    		    var capacity_service = me.services[s].capacity;
		  			    
		  			    for (var i=0;i<htmlSlots.length;i++)
		  			    {
		  			        if (typeof cp_hourbk_cmpublic !== 'undefined')
		  			        {
		  			            htmlSlots[i].html = htmlSlots[i].html.replace("ahbslotavailabilityP",capacity_service);
		  			            htmlSlots[i].html = htmlSlots[i].html.replace("ahbslotavailabilityT",capacity_service);
		  			        }
		  			        str += htmlSlots[i].html;          
		  			    }
		  			    return str;
		    	    }
		    	    var str = "";
		    	    if (!me.showAllServices)
			            str = getStrSlots(arr,d,me.service_selected)
			        else
			        {
			            for (var i=0; i< me.services.length;i++)
			            {    
			                str_s = getStrSlots(me.availableSlotsByService[i],d,i);
			                if (str_s!="")
			                    str += '<div class="service service'+i+'"><div class="service_title">'+me.services[i].name+'</div>'+str_s+'</div>';
			            }    
			        } 
		  			if (str=="") str = cp_hourbk_nomore_label;
		  			var before = "";
		  			if (s.find(".slots").length>0)
		  			{
		  			    before = s.find(".slots").attr("d");
		  			}  
		  			s.html("<div class=\"slots\" d=\""+d+"\"><span>"+$.datepicker.formatDate(me.dateFormat, $.datepicker.parseDate("yy-mm-dd", d))+"</span><br />"+str+"</div>");
		  			if (before!="" && before!=d)
		  			{
		  			    s.find(".slots span:first").hide().show(200);
		  			}
		  			var str1="",str2="";
		  			me.allUsedSlots = me.allUsedSlots || [];
		  			me.allUsedSlots.sort(function(a, b){ return ($.datepicker.parseDate("yy-mm-dd", a.d).getTime()+(a.h1*60+a.m1)*60*1000) - ($.datepicker.parseDate("yy-mm-dd", b.d).getTime()+(b.h1*60+b.m1)*60*1000)});
		  			j = 0;
		  			var total = 0;
		  			for (var i=0;i<me.allUsedSlots.length;i++)
		  			{
		  			    total += me.allUsedSlots[i].price;		  			    
		  			    str1 += "<div class=\"ahb_list\" d=\""+me.allUsedSlots[i].d+"\" quantity=\""+me.allUsedSlots[i].quantity+"\" s=\""+me.allUsedSlots[i].serviceindex+"\" h1=\""+me.allUsedSlots[i].h1+"\" m1=\""+me.allUsedSlots[i].m1+"\" h2=\""+me.allUsedSlots[i].h2+"\" m2=\""+me.allUsedSlots[i].m2+"\" ><span class=\"ahb_list_time\">"+me.formatString(me.allUsedSlots[i],true,me.tz)+"</span><span class=\"ahb_list_service\">"+me.services[me.allUsedSlots[i].serviceindex].name+"</span><span class=\"ahb_list_quantity ahb_list_quantity"+me.allUsedSlots[i].quantity+"\">("+me.allUsedSlots[i].quantity+")</span><a href=\"\" class=\"cancel\" d=\""+d+"\" i=\""+j+"\" iall=\""+i+"\">["+(cp_hourbk_cancel_label?cp_hourbk_cancel_label:'cancel')+"]</a>"+(((typeof cp_hourbk_repeat !== 'undefined'))?showrepeat(me,i):"")+"</div>";
		  			    str2 += ((str2=="")?"":";")+me.allUsedSlots[i].d+" "+me.formattime(me.allUsedSlots[i].h1*60+me.allUsedSlots[i].m1*1,2)+"/"+me.formattime(me.allUsedSlots[i].h2*60+me.allUsedSlots[i].m2*1,2)+" "+me.allUsedSlots[i].serviceindex+" "+me.allUsedSlots[i].quantity;
		  			    if (me.allUsedSlots[i].d==d)
		  			      j++;
		  			}
		  			me.sub_cost = total;
		  			total = me.sub_cost + me.extras;
		  			total = total*(1+me.percent/100);
				    total = total.toFixed(2);
		  			if (me.showTotalCost && (str1!=""))
		  			    str1 += '<div class="totalCost"><span>'+cp_hourbk_cost_label+'</span><span class="n"> '+me.showTotalCostFormat.replace("{0}", total)+'</span></div>';
		  			$( '.usedSlots'+me.name ).html(str1);	
		  			$( '#field' + me.form_identifier + '-' + me.index + ' #'+me.name ).val(str2);		  		    
		  			$( '#field' + me.form_identifier + '-' + me.index + ' #tcost'+me.name ).val(total);
		  		    $( '#field' + me.form_identifier + '-' + me.index + ' #'+me.name ).change();
		  			try {
		  			    $( "#fbuilder .slots div a" ).tooltip({
                          position: {
                            my: "left top+10"
                          },
                          open: function (event, ui) {
                              $(this).tooltip( "option", "content", $(this).parent().find(".ahbmoreinfo").html() );
                          },
                          
                          tooltipClass: "ahbtooltip"
                        });
                    } catch (e) {}
		  			$( '.slotsCalendar' + me.name + ' .slots a').off("click").on("click", function() 
		  			{
		  			    var q = parseFloat($(".fieldCalendarService"+me.name+" select.ahbfield_quantity option:selected").val());
		  			    if ((me.maxNumberOfApp==1 && me.allUsedSlots.length==me.maxNumberOfApp) || (me.allUsedSlots.length>0 && me.allUsedSlots[0].quantity!=q && !me.allowDifferentQuantities)) //cancel previous slot
		  			    {
		  			        for (var i = 0; (i<me.allUsedSlots.length); i++)
		  			        {
		  			            var c = "s"+me.allUsedSlots[i].serviceindex+"q"+me.allUsedSlots[i].quantity+"d"+me.allUsedSlots[i].d;
		  			            var c1 = "sq"+me.allUsedSlots[i].quantity+"d"+me.allUsedSlots[i].d;
		  			            delete me.slotsDate[c];
		  			            delete me.slotsDate[c1];
		  			            me.usedSlots[me.allUsedSlots[i].d] = [];		  			            
		  			        }
		  			        me.allUsedSlots = [];
		  			    }
		  			    if ($(this).parents("fieldset").hasClass("ahbgutenberg_editor"))
		  			        return false;
		  			    $( "#field" + me.form_identifier + "-" + me.index + " div.cpefb_error").remove();	
		  			    if ($(this).parent().hasClass("htmlUsed"))
		  			        return false;
		  			    if ($(this).parent().hasClass("currentSelection"))
			                return false;    
		  				me.allUsedSlots = me.allUsedSlots || [];
		  				if (me.maxNumberOfApp==0 || me.allUsedSlots.length<me.maxNumberOfApp)
		  				{	
		  				    var d = $(this).attr("d");
		  				    me.usedSlots[d] = me.usedSlots[d] || [];	
		  				    var ind = $(this).attr("s")*1;  				    
		  				    obj = {currentSelection:true,h1:$(this).attr("h1")*1,m1:$(this).attr("m1")*1,h2:$(this).attr("h2")*1,m2:$(this).attr("m2")*1,d:d,serviceindex:ind,price:parseFloat(me.services[ind].price)*q,quantity:q};	            	
		  				    me.usedSlots[d][me.usedSlots[d].length] = obj; 
		  				    me.allUsedSlots[me.allUsedSlots.length] = obj;
		  				    $(document).trigger("beforeClickSlot",{name:me.name, d:d});
		  				    var c = "s"+ind+"q"+q+"d"+d;
		  			        var c1 = "sq"+q+"d"+d;
		  			        delete me.slotsDate[c];
		  			        delete me.slotsDate[c1];
		  				    onChangeDateOrService($.datepicker.formatDate('yy-mm-dd', me.getD));
		  			    }
		  			    else
		  			        alert($.validator.messages.maxapp.replace("{0}",me.maxNumberOfApp));
		  				return false;
		  			});		  			
		  			$( '.usedSlots'+me.name+ ' a.cancel').off("click").on("click", function() 
		  			{
		  			    var d = $(this).parents(".ahb_list").attr("d");
		  				var h1 = $(this).parents(".ahb_list").attr("h1");
		  				var m1 = $(this).parents(".ahb_list").attr("m1");
		  				var h2 = $(this).parents(".ahb_list").attr("h2");
		  				var m2 = $(this).parents(".ahb_list").attr("m2");
		  				var s = $(this).parents(".ahb_list").attr("s");
		  				me.usedSlots[d] = me.usedSlots[d] || [];
		  				var find = false;
		  		        for (var i = 0; (i<me.usedSlots[d].length && !find); i++)
		  		            if (me.usedSlots[d][i].d==d && me.usedSlots[d][i].h1==h1 && me.usedSlots[d][i].m1==m1 && me.usedSlots[d][i].h2==h2 && me.usedSlots[d][i].m2==m2 && me.usedSlots[d][i].serviceindex==s)
		  		            {
		  		                find = true;
		  		                me.usedSlots[d].splice(i, 1);    
		  		            }	
		  		        var find = false;
		  		        for (var i = 0; (i<me.allUsedSlots.length && !find); i++)
		  		            if (me.allUsedSlots[i].d==d && me.allUsedSlots[i].h1==h1 && me.allUsedSlots[i].m1==m1 && me.allUsedSlots[i].h2==h2 && me.allUsedSlots[i].m2==m2 && me.allUsedSlots[i].serviceindex==s)
		  		            {
		  		                find = true;
		  		                me.allUsedSlots.splice(i, 1);    
		  		            }
		  			    var c = "s"+s+"q"+me.quantity_selected+"d"+d;
		  			    var c1 = "sq"+me.quantity_selected+"d"+d;
		  			    delete me.slotsDate[c];
		  			    delete me.slotsDate[c1];
		  			    e.datepicker("setDate", me.getD);
		  			    onChangeDateOrService($.datepicker.formatDate('yy-mm-dd', me.getD));
		  			    return false;
		  			});
		  		}		  					
		  		getSlots(d);	  
		  		$(document).trigger("afterOnChange",{name:me.name, me:me});			
		    }		  	
		  	if (typeof cpapphourbk_in_admin !== 'undefined')
	  	  	{	  	  	      
	  	        me.minDate = "";
	  	        me.maxDate = "";
                me.maxNumberOfApp = 0;
	  	  	}
	  	  	if (!me.loadOK)
		  	{  	
		  	    me.formId = $(".fieldCalendarService"+me.name).parents("form").find('input[type="hidden"][name$="cp_appbooking_id"]').val();
		  	    $.ajax(
		  	    {
		  		    dataType : 'json',
		  		    type: "POST",
		  		    url : document.location.href,
		  		    cache : true,
		  		    data :  { cp_app_action: 'get_slots',
		  			    formid: me.formId,
		  			    initialID: me.initialID,
		  			    formfield: me.name.replace(me.form_identifier, "")   
		  			},
		  		    success : function( data ){
		  		    
		  		        for (var i=0;i<data.length;i++)
		  		        {
		  		            var dd = data[i].d;
		  		            if (typeof data[i].sid !== 'undefined')
		  		                data[i].serviceindex = me.getServiceInd(data[i].sid);
		  		            if (data[i].serviceindex==-1)
                                delete data[i].serviceindex;
		  		            me.cacheArr[dd] = me.cacheArr[dd] || [];
		  		            me.cacheArr[dd][me.cacheArr[dd].length] = data[i];
		  		            if ((data[i].h1>data[i].h2 && data[i].h2!=0) || data[i].h2>24)
		  		            {
		  		                if (data[i].h1>data[i].h2)
		  		                    data[i].h2 += 24;    
		  		                var obj = jQuery.extend({}, data[i]);
				                obj.h2 = data[i].h2 - 24;
				                obj.h1 = 0;obj.m1 = 0;				                
				                var d = $.datepicker.parseDate("yy-mm-dd", dd);
		                        d.setDate(d.getDate() + 1);
				                obj.d = $.datepicker.formatDate("yy-mm-dd", d);				                
				                data[i].h2 = 24;
				                me.cacheArr[obj.d] = me.cacheArr[obj.d] || [];
				                me.cacheArr[obj.d][me.cacheArr[obj.d].length] = obj;    
		  		            }				    
		  		        }
		  		        me.slotsDate = [];
		  			    me.loadOK = true;				      			
		  		    }
		  	    });	
		  	}
		  	this.invalidDates = this.invalidDates.replace( /\s+/g, '' );
		  	try{
		  	var df = "mm/dd/yy";
		  	if (this.invalidDates.indexOf(".")!=-1)
		  	    df = me.dateFormat;
		  	    
		  	if( !/^\s*$/.test( this.invalidDates ) )
		  	{
		  	    var counter = 0, dates = this.invalidDates.split( ',' );
		  	    this.invalidDates = [];
		  	    for( var i = 0, h = dates.length; i < h; i++ )
		  	    {
		  	        var range = dates[ i ].split( '-' );                    
		  	        if( range.length == 2 )
		  	        {
		  	            var fromD = $.datepicker.parseDate(df,range[ 0 ]),
		  	                toD = $.datepicker.parseDate(df,range[ 1 ]);
		  	            while( fromD <= toD )
		  	            {
		  	                if (fromD !== null)
		  	                {
		  	            	    this.invalidDates[ counter ] = fromD;
		  	            	    var tmp = new Date( fromD.valueOf() );
		  	            	    tmp.setDate( tmp.getDate() + 1 );
		  	            	    fromD = tmp;
		  	            	    counter++;  
		  	            	}
		  	            }
		  	        }
		  	        else
		  	        {
		  	            for( var j = 0, k = range.length; j < k; j++ )
		  	            {
		  	                if ($.datepicker.parseDate(df,range[ j ]) !== null)
		  	                {
		  	                    this.invalidDates[ counter ] = $.datepicker.parseDate(df,range[ j ]);
		  	                    counter++;
		  	                }
		  	            }
		  	        }
		  	    }
		  	}
		  	} catch (e) {}
		  	if ($.validator.messages.date_format && $.validator.messages.date_format!="")	
		  	    me.dateFormat = $.validator.messages.date_format;
		  	var capacity = 1;    
		  	for (var i=0;i<me.services.length;i++)
		  	{    
		  	    str += '<option value="'+me.services[i].duration+'">'+me.services[i].name+'</option>';
		  	    me.services[i].capacity = (parseFloat(me.services[i].capacity)>0)?me.services[i].capacity:1;
		  	    if (capacity<me.services[i].capacity)
		  	        capacity = me.services[i].capacity;
		  	}
		  	if (me.emptySelectCheckbox) 
			    str = '<option value="">'+ me.emptySelect +'</option>'+ str ;
		  	var str2 = "";    
		  	for (var i=1;i<=me.services[0].capacity;i++)
		  	    str2 += '<option value="'+i+'">'+i+'</option>';
		  	d.html('<select class="ahbfield_service">'+str+'</select><div class="ahbfield_quantity_div" '+((!me.showQuantity)?"style='display:none'":"")+'><label class="ahbfield_quantity_label">'+((typeof cp_hourbk_quantity_label !== 'undefined')?cp_hourbk_quantity_label:'Quantity')+'</label><br /><select class="ahbfield_quantity" autocomplete="off">'+str2+'</select></div>');
		  	me.service_selected = me.normalizeSelectIndex($(".fieldCalendarService"+me.name+" select.ahbfield_service option:selected").index());
		  	me.quantity_selected = parseFloat($(".fieldCalendarService"+me.name+" select.ahbfield_quantity option:selected").val());
		  	me.duration = parseFloat(me.services[me.service_selected].duration);		  	
		  	me.pa = me.services[me.service_selected].pa * 1 || 0;		  			  	
		  	me.pb = me.services[me.service_selected].pb * 1 || 0;
		  	$(".fieldCalendarService"+me.name+" select.ahbfield_service").bind("change", function() 
		  	{
		  	     me.service_change = true;
		  	     me.service_selected = me.normalizeSelectIndex($(".fieldCalendarService"+me.name+" select.ahbfield_service option:selected").index());	
		  	     me.duration = parseFloat(me.services[me.service_selected].duration);	
		  	     me.pa = me.services[me.service_selected].pa * 1 || 0;		  			  	
		  	     me.pb = me.services[me.service_selected].pb * 1 || 0;
		  	     //me.cacheOpenHours = new Array();
		  	     me.special_days = me.getSpecialDays();
		  	     var str2 = "";    
		  	     for (var i=1;i<=me.services[me.service_selected].capacity;i++)
		  	         str2 += '<option value="'+i+'">'+i+'</option>';
		  	     $(".fieldCalendarService"+me.name+" select.ahbfield_quantity").html(str2);
		  	     me.quantity_selected = parseFloat($(".fieldCalendarService"+me.name+" select.ahbfield_quantity option:selected").val());
		  	     if (typeof me.getDMin!='undefined') me.getD = me.getDMin;
		  	     $( '#field' + me.form_identifier + '-' + me.index + ' .fieldCalendar'+me.name ).datepicker( "option", "beforeShowDay", function(d){return me.rC(d)} );
		  		 onChangeDateOrService($.datepicker.formatDate('yy-mm-dd', me.getD));
		  	});
		  	$(".fieldCalendarService"+me.name+" select.ahbfield_quantity").bind("change", function() 
		  	{
		  	     if (!me.allowDifferentQuantities)
		  	     {
		  	         me.quantity_selected = parseFloat($(".fieldCalendarService"+me.name+" select.ahbfield_quantity option:selected").val());
		  	         me.allUsedSlots = me.allUsedSlots || [];
		  	         for (var i=0;i<me.allUsedSlots.length;i++)
		  	         {
		  	             var find = false;
		  	             var s = me.allUsedSlots[i];
		  	             var arr = me.getAvailableSlotsByService(s.d,s.serviceindex);
		  	             for (j=0;j<arr.length && !find;j++)
		  	             {
		  	                 if (s.h1*60+s.m1*1>=arr[j].t1 && s.h2*60+s.m2*1<=arr[j].t2)
		  	                    find = true;
		  	             }		  	         
		  	             me.usedSlots[s.d] = me.usedSlots[s.d] || [];
		  	             f = false;
		  	             for (var j=0;j<me.usedSlots[s.d].length && !f;j++)
		  	             {   
		  	                if (me.usedSlots[s.d][j].h1==s.h1 && me.usedSlots[s.d][j].m1==s.m1 && me.usedSlots[s.d][j].h2==s.h2 && me.usedSlots[s.d][j].m2==s.m2 && me.usedSlots[s.d][j].serviceindex==s.serviceindex)
		  		            {
		  		                f = true;
		  		                if (find)// change the quantity or remove if not available
		  		                {
		  		                    me.allUsedSlots[i].quantity = me.quantity_selected;
		  		                    me.usedSlots[s.d][j].quantity = me.quantity_selected;
		  		                    me.allUsedSlots[i].price = parseFloat(me.services[s.serviceindex].price)*me.quantity_selected;
		  		                    me.usedSlots[s.d][j].price = me.allUsedSlots[i].price;
		  		                }
		  		                else
		  		                {
		  		                    me.usedSlots[s.d].splice(j, 1);
		  		                    me.allUsedSlots.splice(i, 1);
			                	    i--;
		  		                }    
		  		            }
		  	             }
		  	         }
		  	     }
		  	     $( '#field' + me.form_identifier + '-' + me.index + ' .fieldCalendar'+me.name ).datepicker( "option", "beforeShowDay", function(d){return me.rC(d)} );
		  	     onChangeDateOrService($.datepicker.formatDate('yy-mm-dd', me.getD));
		  	});
		  	$("#"+me.name).bind("change", function() 
		  	{
		  	     if ($(this).attr("reload")=="reload")
		  	     {              
		  	         $(this).attr("reload","");
		  	         onChangeDateOrService($.datepicker.formatDate('yy-mm-dd', me.getD));
		  	         $( '#field' + me.form_identifier + '-' + me.index + ' .fieldCalendar'+me.name ).datepicker( "option", "beforeShowDay", function(d){return me.rC(d)} );
		  	     }
		  	});
		  	try{
		  	me.special_days = me.getSpecialDays();
		  	} catch (e) {} 
		  	var hrs = 0;
		  	var mindatetime = "";
		  	me.getMinDate = "";
		  	if (me.minDate!=="")
		    {	
		        
		        if (me.minDate.indexOf("@")!= -1)
		        {
		            var a = me.minDate.split("@")
		            me.minDate = a[0];
		            mindatetime = a[1];   
		        }
		        if ((me.minDate.length < 6) && me.minDate.indexOf("h")!= -1)
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
		                        hrs = parseFloat(a[i].replace("h",""));
		                        me.minDate = me.minDate.replace(a[i],"");
		                    }
		                }
		            }
		            else
		            {
		                hrs = parseFloat(me.minDate.replace("h",""));
		                me.minDate = 0;
		            }
		        }
		    }
		    if (me.maxDate!=="")
		    {	
		        
		        if ((me.maxDate.length < 6) && me.maxDate.indexOf("h")!= -1)
		        {		            
		            if (me.maxDate.indexOf(" ")!= -1)
		            {
		                var a = me.maxDate.split(" ");
		                var find = false;
		                for (var i=0;(i<a.length && !find);i++)
		                {
		                    if (a[i].indexOf("h")!= -1)
		                    {
		                        find = true;
		                        var hrsMax = parseFloat(a[i].replace("h",""));
		                        me.maxDate = me.maxDate.replace(a[i],"");
		                    }
		                }
		            }
		            else
		            {
		                var hrsMax = parseFloat(me.maxDate.replace("h",""));
		                var htmp = hrsMax % 24;
		                me.maxDate = (hrsMax-htmp)/24; 
		                hrsMax = htmp;
		            }
		        }
		    }       
		  	e.datepicker({numberOfMonths:parseFloat(me.numberOfMonths),
		  		//firstDay:parseFloat(me.firstDay),
		  		//minDate:me.minDate,
		  		//maxDate:me.maxDate,
		  		showWeek: me.showWeek,
		  		dateFormat:me.dateFormat,
		  		changeMonth: me.showDropdown, 
		  		changeYear: me.showDropdown,
		  		yearRange: ((me.showDropdown)?me.dropdownRange:""),
		  		onSelect: function(d,inst) {
		  			me.getD = e.datepicker("getDate");
		  			onChangeDateOrService($.datepicker.formatDate("yy-mm-dd", me.getD));
		  			$( "#field" + me.form_identifier + "-" + me.index + " div.cpefb_error").remove();
		  			
           	    },
		  		//beforeShowDay: function(d){return me.rC(d)}
		    });
		    
		    e.datepicker("option", $.datepicker.regional[$.validator.messages.language]);
		    $.datepicker.setDefaults($.datepicker.regional[$.validator.messages.language]);
		    e.datepicker("option", "firstDay", me.firstDay );
		    e.datepicker("option", "dateFormat", me.dateFormat );
		    e.datepicker("option", "minDate", me.minDate );
		    e.datepicker("option", "maxDate", me.maxDate );
		    if (me.minDate!=="")
		    {	
		        me.getMinDate = e.datepicker("getDate");
		        var t = new Date();
		        var isRelativeDate = 1;
		        try{
		          $.datepicker.parseDate(me.dateFormat,me.minDate);
		          isRelativeDate = 0;
		        } catch (e) {}		            
		        me.getMinDate = new Date((me.getMinDate.getTime() + isRelativeDate * t.getHours() * 60 * 60 * 1000 + isRelativeDate * t.getMinutes() * 60 * 1000 + hrs * 60 * 60 * 1000) );
		        if (mindatetime!="")
		        {
		            var a = mindatetime.split(":")
		            if (parseFloat(a[0])>=0 && parseFloat(a[0]) < 24 && parseFloat(a[1])>=0 && parseFloat(a[1]) < 60 )
		                me.getMinDate = new Date(me.getMinDate.getFullYear(),me.getMinDate.getMonth(),me.getMinDate.getDate(),parseFloat(a[0]),parseFloat(a[1]));
		        }       
		        e.datepicker("option", "minDate", me.getMinDate );
		        e.datepicker("setDate", me.getMinDate);
		    } 
		    if (me.maxDate!=="")
		        try{me.getMaxDate = $.datepicker._getMinMaxDate( e.data('datepicker'), 'max' ); me.getMaxDate.setHours(23, 59, 59, 999);} catch (e) {} 
		        if (typeof hrsMax !== 'undefined')
		        {
		            var t = new Date();
		            me.getMaxDate.setHours(t.getHours(), t.getMinutes(), t.getSeconds());
		            me.getMaxDate = new Date((me.getMaxDate.getTime() + hrsMax * 60 * 60 * 1000) );
		        }		    
		    try{
		    if (me.defaultDate!=="")
		        e.datepicker("setDate", me.defaultDate);
		    } catch (e) {}
		    e.datepicker("option", "maxDate", me.maxDate );
		    if (me.getMaxDate!="" && me.tz!=0) 	e.datepicker("option", "maxDate", new Date(me.getMaxDate.getTime()+me.tz*60*60*1000) );
		    me.tmpinvalidDatestime = new Array();
            try {
                  for (var i=0;i<me.tmpinvalidDates.length;i++)
                      me.tmpinvalidDatestime[i]=me.invalidDates[i].getTime();              
            } catch (e) {}
            function DisableSpecificDates(date) {                
                var ohindex = me.services[me.normalizeSelectIndex($(".fieldCalendarService"+me.name+" select option:selected").index())].ohindex;
			  	for (var i=0;i<me.allOH[ohindex].openhours.length;i++)
			  	    if (me.allOH[ohindex].openhours[i].type=="special" && me.allOH[ohindex].openhours[i].d==$.datepicker.formatDate("yy-mm-dd",date))
			  	        return true; 
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
            for (var key in me.cacheOpenHours[me.service_selected])
                sum ++;
            if (sum>0)
            {       
		       var nextdateAvailable = e.datepicker("getDate");
               while (!DisableSpecificDates(nextdateAvailable))
                   nextdateAvailable.setDate(nextdateAvailable.getDate() + 1);
               e.datepicker("setDate", nextdateAvailable);  
               me.getD = nextdateAvailable;
               function ifLoadOk()
               {
                   if (!me.loadOK)
		               setTimeout(ifLoadOk,100);
		           else
		           { 
		               $( '#field' + me.form_identifier + '-' + me.index + ' .fieldCalendar'+me.name ).datepicker( "option", "beforeShowDay", function(d){return me.rC(d)} );
		               onChangeDateOrService($.datepicker.formatDate('yy-mm-dd', me.getD));
		               $( '#field' + me.form_identifier + '-' + me.index + ' .fieldCalendar'+me.name ).datepicker( "option", "beforeShowDay", function(d){return me.rC(d)} );
		           }    
               } 
               ifLoadOk();
		    }
		    preselect_service = function(v)
		    {
		        $(".fieldCalendarService"+me.name+" select.ahbfield_service").children().removeAttr("selected");
		        if (me.emptySelectCheckbox)		            
                    $(".fieldCalendarService"+me.name+" select.ahbfield_service").children().eq(v+1).prop('selected', 'selected').change();
		        else
		            $(".fieldCalendarService"+me.name+" select.ahbfield_service").children().eq(v).prop('selected', 'selected').change();
		    }
		    if (typeof cp_hourbk_preselect !== 'undefined' && cp_hourbk_preselect!="")
		        preselect_service(cp_hourbk_preselect*1);
		    else
		    if (me.initialapp!="" && dd!="")
		    {   
		        try{
		        me.getD = $.datepicker.parseDate("yy-mm-dd",dd);
		        e.datepicker("setDate", me.getD);
		        preselect_service(me.initialServiceInd);
		        onChangeDateOrService(dd);
		        } catch (e) {}
		    }
		    getExtrasVisible = function(f)
		    {
		        try{
		            var p = f.attr("id").split( '_' );
		            var items = $.fbuilder[ 'forms' ]["_"+p[p.length-1]].getItems();
		            for (var i=0;i<items.length;i++)
		                if (items[i].ftype=="fapp" && ($("#"+items[i].name).parent().is(":visible") || $("#"+items[i].name).parents(".fields").hasClass("cp_active") ))
		                    getExtras(items[i],f)
		        } catch (e) {}        
		    }    
		    getExtras=function(me,f)
		    {
		        var v = 0;
		        var find = false;
		        var e = f.find(".ahb_service").find(':checked:not(.ignore)');
		        if( e.length)
				{
				    find = true;
					e.each( function(){
					    if (($(this).parents(".fields").hasClass("cp_active") || $(this).is(":visible") || ($(this).prop("tagName")=="OPTION" && $(this).parent().is(":visible"))) &&  $.isNumeric(this.value))
						    v += this.value*1;
					});
				}
				me.percent = 0;
				var e = f.find(".ahb_service_percent").find(':checked:not(.ignore)');
		        if( e.length)
				{
				    find = true;
					e.each( function(){
					    if (($(this).parents(".fields").hasClass("cp_active") || $(this).is(":visible") || ($(this).prop("tagName")=="OPTION" && $(this).parent().is(":visible"))) &&  $.isNumeric(this.value))
						    me.percent += this.value*1;
					});
				}
				e = f.find(".ahb_service_per_slot").find(':checked:not(.ignore)');
				me.allUsedSlots = me.allUsedSlots || [];
				var s = me.allUsedSlots.length;
		        if( e.length)
				{
				    find = true;
					e.each( function(){
						if (($(this).parents(".fields").hasClass("cp_active") || $(this).is(":visible") || ($(this).prop("tagName")=="OPTION" && $(this).parent().is(":visible"))) &&  $.isNumeric(this.value))
						    v += this.value * s;
					} );
				}
				e = f.find(".ahb_service_per_quantity_selection").find(':checked:not(.ignore)');
				var q = f.find(".ahbfield_quantity").val();
                if (!parseFloat(q))
                    q = 1;
		        if( e.length)
				{
				    find = true;
					e.each( function(){
						if (($(this).parents(".fields").hasClass("cp_active") || $(this).is(":visible") || ($(this).prop("tagName")=="OPTION" && $(this).parent().is(":visible"))) &&  $.isNumeric(this.value))
						    v += this.value * q;
					} );
				}
				f.find('#'+me.name+'_services').val(v);
				//if (find)
				{
				    me.extras = v;
				    var total = me.sub_cost + me.extras;
				    total = total*(1+me.percent/100);
				    total = total.toFixed(2);
				    $( '#field' + me.form_identifier + '-' + me.index ).find(".totalCost .n").html(" " +me.showTotalCostFormat.replace("{0}",total));
				    $( '#field' + me.form_identifier + '-' + me.index + ' #tcost'+me.name ).val(total);
				    me.changeAutomatic = true;
				    $( '#field' + me.form_identifier + '-' + me.index + ' #'+me.name ).change();
				}
		    }    
		    $( '#field' + me.form_identifier + '-' + me.index ).parents( "form" ).find(".ahb_service,.ahb_service_percent,.ahb_service_per_slot,.ahb_service_per_quantity_selection").on("click", function(){
		        getExtrasVisible($(this).parents( "form" ));
		    });
		    $( '#field' + me.form_identifier + '-' + me.index ).parents( "form" ).find(".ahb_service,.ahb_service_percent,.ahb_service_per_slot,.ahb_service_per_quantity_selection").on("change", function(){
		        getExtrasVisible($(this).parents( "form" ));
		    });
		    
		    $( '#field' + me.form_identifier + '-' + me.index + ' #'+me.name ).change(function(  ) {
		        if (!me.changeAutomatic)
		            getExtrasVisible($(this).parents( "form" ));
		        me.changeAutomatic = false;     
            });
            if (typeof cp_hourbk_overlapping_label != "undefined")
                $.extend($.validator.messages, {avoid_overlapping: $.validator.format(cp_hourbk_overlapping_label)});        
			if(!('avoid_overlapping' in $.validator.methods))
			{ 
			    function avoid_over_function(value, element){
                    var validator = this,
                        previous = validator.previousValue( element );
                    if ( previous.old === value ) {
                        return previous.valid;
                    }
                    previous.old = value;
                    validator.startRequest( element );
                    
                    var p = element.id.split( '_' ),
					    _index = ( p.length > 1 ) ? '_'+p[ 1 ] : '',
					    me = ( 
						    typeof $.fbuilder[ 'forms' ] != 'undefined' && 
						    typeof $.fbuilder[ 'forms' ][ _index ] != 'undefined'  
					        ) ? $.fbuilder[ 'forms' ][ _index ].getItem( p[ 0 ]+'_'+p[ 1 ] ) : null;
                    
                    if( me != null )  
                    {
                        $.ajax({
                            dataType : 'json',
		  		            type: "POST",
		  		            url : document.location.href,
		  		            data :  { cp_app_action: 'get_slots',
		  		                formid: me.formId,
		  		                initialID: me.initialID,
		  		                formfield: me.name.replace(me.form_identifier, "")   
		  		            },
                            success: function(data) {
                                var overlapping = false;
                                var find = false;
                                me.ignoreUsedSlots = true;
                                me.cacheArr = new Array(); 
                                for (var i=0;i<data.length;i++)
		  		                {
		  		                    var dd = data[i].d;
		  		                    if (typeof data[i].sid !== 'undefined')
		  		                        data[i].serviceindex = me.getServiceInd(data[i].sid);
		  		                    me.cacheArr[dd] = me.cacheArr[dd] || [];
		  		                    me.cacheArr[dd][me.cacheArr[dd].length] = data[i];	
		  		                }
		  		                me.slotsDate = [];
		  			            me.loadOK = true;
                                for (var i = 0; (i<me.allUsedSlots.length && !overlapping); i++)
                                {
                                    me.service_selected = me.allUsedSlots[i].serviceindex;
                                    me.quantity_selected = me.allUsedSlots[i].quantity;
                                    me.duration = parseFloat(me.services[me.service_selected].duration);
                                    var d = me.allUsedSlots[i].d;
                                    var t1 = me.allUsedSlots[i].h1 * 60 + me.allUsedSlots[i].m1;
                                    var t2 = me.allUsedSlots[i].h2 * 60 + me.allUsedSlots[i].m2;
                                    if (me.tz != 0)
                                    {
                                        var d1 = $.datepicker.parseDate("yy-mm-dd",d);
                                        var d2 = new Date(d1.getTime()+t1*60*1000+me.tz*60*60*1000);
                                        d = $.datepicker.formatDate("yy-mm-dd",d2);
                                    }
                                    var arr = me.getAvailableSlots(d);
                                    if (me.showAllServices)
			                            arr = me.availableSlotsByService[me.service_selected];
                                    find = false;
                                    for (var j=0;(j<arr.length  && !find);j++)
                                    {
                                        if (arr[j].t1<=t1 && arr[j].t2>=t2)
                                            find = true;    
                                    }
                                    overlapping = !find; 
                                } 
                                me.ignoreUsedSlots = false;
                                var isValid = !overlapping;
                                if (true === isValid) {
                                    var submitted = validator.formSubmitted;
                                    validator.prepareElement( element );
                                    validator.formSubmitted = submitted;
                                    validator.successList.push( element );
                                    delete validator.invalid[ element.name ];
                                    validator.showErrors();
                                  
                                } else {
                                    for (var i=0;i<data.length;i++)
		  		                    {
		  		                        var dd = data[i].d;
		  		                        me.cacheArr[dd] = me.cacheArr[dd] || [];
		  		                        me.cacheArr[dd][me.cacheArr[dd].length] = data[i];	
		  		                    }
		  		                    me.slotsDate = [];
		  			                me.loadOK = true;
                                    var errors = {};
                                    errors[ element.name ] = validator.defaultMessage( element, "avoid_overlapping" );
                                    validator.invalid[ element.name ] = true;
                                    validator.showErrors( errors );
                                    element.focus();
                                }
                                previous.valid = isValid;
                                validator.stopRequest( element, isValid );
                                cp_hourbk_avoid_overlapping--;    
                            }
                        });
                        return "pending";
                    }
					return true;    
                }
			    $.validator.addMethod('avoid_overlapping', avoid_over_function);
			}                          
		},
		val:function()
		{
		    return 0;
		}
	}         
);