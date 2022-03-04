var cpappbk_appointments_fpanel = function($){
            var cpappbk_counter = 0;
          	function loadWindow(){
          	    cpappbk_counter++;
                const formOptions = apphourbk_formsclassic.forms;
                var myoptions = '';            
                for (var i=0; i<formOptions.length; i++)
                    myoptions += '<option value="'+formOptions[i].value+'">'+formOptions[i].label+'</option>';
          		$(' <div title="Appointment Hour Booking"><div style="padding:20px;">'+
          		   'Select Calendar:<br /><select id="cpappbk_calendar_sel'+cpappbk_counter+'" name="cpappbk_calendar_sel'+cpappbk_counter+'">'+myoptions+'</select>'+
          		   '</div></div>'
          		  ).dialog({
          			dialogClass: 'wp-dialog',
                      modal: true,
                      closeOnEscape: true,
                      buttons: [
                          {text: "Insert", click: function() {
          						if(send_to_editor){
          							var id = $('#cpappbk_calendar_sel'+cpappbk_counter)[0].options[$('#cpappbk_calendar_sel'+cpappbk_counter)[0].options.selectedIndex].value;
                                    send_to_editor('[CP_APP_HOUR_BOOKING id="'+id+'"]');
          						}
          						$(this).dialog("close");
          				}}
                      ]
                  });
          	}
          	var obj = {};
          	obj.open = loadWindow;
          	return obj;
          }(jQuery);
         