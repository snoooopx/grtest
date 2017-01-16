App.Views.initSettings = Backbone.View.extend({
	initialize: function(){
		new App.Views.EditSettingsView();
	}
});

/*
|---------------------------------------------------------------------------------
|Edit Settings View
|---------------------------------------------------------------------------------
*/
App.Views.EditSettingsView = Backbone.View.extend({
	el: '#dSettings',

	events: {
		"submit form#frmSocials"	 : 'submitSettings',
		"submit form#frmOpening"	 : 'submitSettings',
		"submit form#frmCompanyInfo" : 'submitSettings',
		"submit form#frmAboutUs"	 : 'submitSettings'
		
	},

	initialize : function(){
		console.log('settings view loaded');

	},	

	// save Button Click On any 
	submitSettings : function(e){
		e.preventDefault();

		console.log($(e.currentTarget).serializeArray());
		//return;

		var save_values = {};
		if ( $(e.target).attr('id') == 'frmSocials') {
				save_values.fb  	 					=	$(e.target).find("[name='fb']").val();
				save_values.inst  					= 	$(e.target).find("[name='inst']").val();
				save_values.twt	 						=	$(e.target).find("[name='twt']").val();	
				save_values.submitSocial	  =	$("#submitSocial").val();	
				//save_values = $(e.currentTarget).serializeArray();
		}

		if ( $(e.target).attr('id') == 'frmOpening') {
				save_values.openingHours				= $(e.target).find("[name='openingHours']").val();
				save_values.submitOpeningHours	=	$(e.target).find("[name='submitOpeningHours']").val();	
		}

		if ( $(e.target).attr('id') == 'frmCompanyInfo') {
				save_values.address			 = $(e.target).find("[name='address']").val();
				save_values.phone				 = $(e.target).find("[name='phone']").val();
				save_values.email				 = $(e.target).find("[name='email']").val();	
				save_values.name				 = $(e.target).find("[name='name']").val();	
				save_values.submitInfo	 =	$(e.target).find("[name='submitInfo']").val();
		}

		if ( $(e.target).attr('id') == 'frmAboutUs') {
				save_values.aboutUsShort		= $(e.target).find("[name='aboutUsShort']").val();
				save_values.aboutUsLong			= $(e.target).find("[name='aboutUsLong']").val();	
				save_values.submitAboutUs		= $(e.target).find("[name='submitAboutUs']").val();	
		}
		console.log(save_values);
		var settingsCollection = new App.Collections.SettingsActions();

		settingsCollection.create(
									save_values,
									{
										wait:true,
										success: function(model,response){
													console.log(response);
													$.notify({ message: response.message},
													    { type: 'success',
													   z_index: 10000}
													);
										},
										error: function(model,response){
													console.log(response);
													$.notify({ message: response.responseJSON.message},
													    { type: 'danger',
													   z_index: 10000}
													);
										}
									}
		);
	}
});


