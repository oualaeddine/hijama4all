	$.fbuilder.typeList.push(
		{
			id:"fButton",
			name:"Button",
			control_category:3
		}
	);
	$.fbuilder.controls[ 'fButton' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'fButton' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			ftype:"fButton",
            sType:"button", // button, reset, calculate
            sValue:"button",
            sOnclick:"",
			userhelp:"A description of the section goes here.",
			display:function()
				{
					return '- available only in commercial version of plugin -';
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'ffields' ].prototype.editItemEvents.call(this);
				}
	});