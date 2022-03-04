function cpappbk_renderForm(id) {      
    if (jQuery("#form_structure"+id).length)
    {
        var cp_appbooking_fbuilder_myconfig = {"obj":"{\"pub\":true,\"identifier\":\"_"+id+"\",\"messages\": {}}"};
        var f = jQuery("#fbuilder_"+id).fbuilder(jQuery.parseJSON(cp_appbooking_fbuilder_myconfig.obj));
        f.fBuild.loadData("form_structure"+id);                     
    }
    else
    {
        setTimeout ('cpappbk_renderForm('+id+')',50);
    }
}  
jQuery(function()
{             
	(function( blocks, element ) {
        var el = wp.element.createElement,
        source 		= blocks.source,
        InspectorControls   = ('blockEditor' in wp) ? wp.blockEditor.InspectorControls : wp.editor.InspectorControls;        
		var category 	= {slug:'appointment-hour-booking', title : 'Appointment Hour Booking'};
				
		var _wp$components = wp.components,
        SelectControl = _wp$components.SelectControl,
        ServerSideRender = _wp$components.ServerSideRender;

		/* Plugin Category */
		blocks.getCategories().push({slug: 'cpapphourbk', title: 'Appointment Hour Booking'}) ;

		/* Form's shortcode */
		blocks.registerBlockType( 'cpapphourbk/form-rendering', {
            title: 'Appointment Hour Booking', 
            icon: 'calendar-alt',    
            category: 'cpapphourbk',
			      supports: {
			      	  customClassName: false,
			      	  className: false
			      },
		    attributes: {
			      	  formId: {
			            type: 'string'
		              },
			      	  instanceId: {
			            type: 'string'
		              }
			      },           
	        edit: function( { attributes, className, isSelected, setAttributes }  ) {             
                    const formOptions = apphourbk_forms.forms;
                    if (!formOptions.length)
                        return el("div", null, 'Please create a booking form first.' );
                    var iId = attributes.instanceId;
                    if (!iId)
                    {                        
                        iId = formOptions[0].value+parseInt(Math.random()*100000);
                        setAttributes({instanceId: iId });
                    }
                    if (!attributes.formId)
                        setAttributes({formId: formOptions[0].value });
                    cpappbk_renderForm(iId);
			    	var focus = isSelected;                                       
			    	return [
			    		!!focus && el(
			    			InspectorControls,
			    			{
			    				key: 'cpapphourbk_inspector'
			    			},
			    			[
			    				el(
			    					'span',
			    					{
			    						key: 'cpapphourbk_inspector_help',
			    						style:{fontStyle: 'italic'}
			    					},
			    					'If you need help: '
			    				),
			    				el(
			    					'a',
			    					{
			    						key		: 'cpapphourbk_inspector_help_link',
			    						href	: 'https://apphourbooking.dwbooster.com/contact-us',
			    						target	: '_blank'
			    					},
			    					'CLICK HERE'
			    				)
			    			]
			    		),			    		
			    		el(SelectControl, {
                                value: attributes.formId,
                                options: formOptions,
                                onChange: function(evt){         
                                    setAttributes({formId: evt});
                                    iId = evt+parseInt(Math.random()*100000);
                                    setAttributes({instanceId: iId });
                                    cpappbk_renderForm(iId);                                   
			    				},
                        }),
                        el(ServerSideRender, {
                             block: "cpapphourbk/form-rendering",
                             attributes: attributes
                        })			    		
			    	];
			    },
          
			save: function( props ) {
			    	return null; 
			    }
			});

		} )(
			window.wp.blocks,
			window.wp.element
		);
	}
);