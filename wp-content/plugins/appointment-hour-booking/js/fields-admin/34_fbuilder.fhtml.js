	$.fbuilder.typeList.push(
		{
			id:"fhtml",
			name:"HTML content",
			control_category:3
		}
	);
	$.fbuilder.controls[ 'fhtml' ]=function(){  this.init();  };
	$.extend(
		$.fbuilder.controls[ 'fhtml' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			ftype:"fhtml",
			fcontent: "",
			display:function()
				{
					return '- available only in commercial version of plugin -';
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'ffields' ].prototype.editItemEvents.call(this);
				}
		}
	);