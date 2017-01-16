App.Models.Settings		= Backbone.Model.extend({});

App.Models.Order		= Backbone.Model.extend({});


App.Models.Client		= Backbone.Model.extend({});

App.Models.ClientTmpl = Backbone.Model.extend({
	defaults:{
	 	 info : [],
		items : [],
		attrs : []
	}
});

App.Models.ClientTmplInfo = Backbone.Model.extend({
	defaults:{
			name: 		 "",
			sku: 		 "",
			description: "",
			type: 		 "static",
			count: 		 "0",
			price: 		 "",
			mmt_id: 		 "",
			is_enabled:  "",
			featured_image:""
	}
});

App.Models.ClientTmplItem = Backbone.Model.extend({
	defaults:{
			id : "",
			qty: ""
	}
});


App.Models.Coupon				= Backbone.Model.extend({});

App.Models.Set				= Backbone.Model.extend({});

App.Models.SetTmpl = Backbone.Model.extend({
	defaults:{
	 	 info : [],
		items : [],
		attrs : []
	}
});

App.Models.SetTmplInfo = Backbone.Model.extend({
	defaults:{
			name: 		 	"",
			sku: 		 	"",
			description: 	"",
			type: 		 	"static",
			in_desert_page: "",
			count: 			"0",
			price: 		 	"",
			mmt_id: 		"",
			is_enabled: 	"",
			is_new: 		"",
			featured_image: ""
	}
});

App.Models.SetTmplItem = Backbone.Model.extend({
	defaults:{
			id : "",
			qty: ""
	}
});

App.Models.SetTmplAttribute = Backbone.Model.extend({
	defaults:{
			id 	  :"",
			price :""
	}
});

App.Models.SetEdit 			= Backbone.Model.extend({});

App.Models.Attribute		= Backbone.Model.extend({});

App.Models.AttrGroup		= Backbone.Model.extend({});

App.Models.Desert 			= Backbone.Model.extend({});

App.Models.Flavor 			= Backbone.Model.extend({});

App.Models.Color 			= Backbone.Model.extend({});

App.Models.User 			= Backbone.Model.extend({});

App.Models.Product  		= Backbone.Model.extend({});

App.Models.UserPermission 	= Backbone.Model.extend({
	default:{
		 section_name : "",
		   section_id : "",
		  section_seq :	"",
	  subsection_name : "",
	    subsection_id : "",
	   subsection_seq : "",
					r :	"",
					c :	"",
					u :	"",
					d :	""
	}
});
