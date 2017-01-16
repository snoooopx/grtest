/*
|---------------------------------------------------------------------------------
| Update Orders
|---------------------------------------------------------------------------------
*/
App.Collections.OrdersActions = Backbone.Collection.extend({
	model: App.Models.Order,
	url: '/'+App.myRoot+"/orders/save",
});

/*
|---------------------------------------------------------------------------------
| Orders Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Orders = Backbone.PageableCollection.extend({
	model: App.Models.Order,
	url: '/'+App.myRoot+"/orders/orders",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 10,
	    sortKey: "order_id"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});


/*
|---------------------------------------------------------------------------------
| Update Settings
|---------------------------------------------------------------------------------
*/
App.Collections.SettingsActions = Backbone.Collection.extend({
	model: App.Models.Settings,
	url: '/'+App.myRoot+"/settings/save",
});

/*
|---------------------------------------------------------------------------------
| Clients Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Clients = Backbone.PageableCollection.extend({
	model: App.Models.Client,
	url: '/'+App.myRoot+"/clients/clients",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 10,
	    sortKey: "fname"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});


/*
|---------------------------------------------------------------------------------
| Edit Client
|---------------------------------------------------------------------------------
*/
App.Collections.EditClient = Backbone.Collection.extend({
	model: App.Models.ClientEdit,
	url: '/'+App.myRoot+"/clients/getfull",
});
/*


/*
|---------------------------------------------------------------------------------
| Create Client
|---------------------------------------------------------------------------------
*/
App.Collections.ClientActionTmplCreate = Backbone.Collection.extend({
	model: App.Models.ClientTmpl,
	url: '/'+App.myRoot+"/clients/createclientsbmt",
});

/*
|---------------------------------------------------------------------------------
| Create Client Info
|---------------------------------------------------------------------------------
*/
App.Collections.ClientActionTmplInfo = Backbone.Collection.extend({
	model: App.Models.ClientTmplInfo
});

/*
|---------------------------------------------------------------------------------
| Create Client Addresses
|---------------------------------------------------------------------------------
*/
App.Collections.ClientActionTmplAddress = Backbone.Collection.extend({
	model: App.Models.ClientTmplAddress

});



/*
|---------------------------------------------------------------------------------
| Coupons Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Coupons = Backbone.PageableCollection.extend({
	model: App.Models.Coupon,
	url: '/'+App.myRoot+"/coupons/coupons",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 10,
	    sortKey: "code"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});



/*
|---------------------------------------------------------------------------------
| Sets Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Sets = Backbone.PageableCollection.extend({
	model: App.Models.Set,
	url: '/'+App.myRoot+"/sets/sets",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 10,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});


/*
|---------------------------------------------------------------------------------
| Edit Set
|---------------------------------------------------------------------------------
*/
App.Collections.EditSet = Backbone.Collection.extend({
	model: App.Models.SetEdit,
	url: '/'+App.myRoot+"/sets/getfull",
});
/*


/*
|---------------------------------------------------------------------------------
| Create Set
|---------------------------------------------------------------------------------
*/
App.Collections.SetActionTmplCreate = Backbone.Collection.extend({
	model: App.Models.SetTmpl,
	url: '/'+App.myRoot+"/sets/createsetsbmt",
});

/*
|---------------------------------------------------------------------------------
| Create Set Items
|---------------------------------------------------------------------------------
*/
App.Collections.SetActionTmplItem = Backbone.Collection.extend({
	model: App.Models.SetTmplItem

});

/*
|---------------------------------------------------------------------------------
| Create Set Attributes
|---------------------------------------------------------------------------------
*/
App.Collections.SetActionTmplAttribute = Backbone.Collection.extend({
	model: App.Models.SetTmplAttribute
});

/*
|---------------------------------------------------------------------------------
| Create Set Info
|---------------------------------------------------------------------------------
*/
App.Collections.SetActionTmplInfo = Backbone.Collection.extend({
	model: App.Models.SetTmplInfo
});
/*
|---------------------------------------------------------------------------------
| Attributes Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Attributes = Backbone.PageableCollection.extend({
	model: App.Models.Attribute,
	url: '/'+App.myRoot+"/attributes/attributes",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 10,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});

/*
|---------------------------------------------------------------------------------
| AttrGroups Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.AttrGroups = Backbone.PageableCollection.extend({
	model: App.Models.AttrGroup,
	url: '/'+App.myRoot+"/attrgroups/attrgroups",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 10,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});

/*
|---------------------------------------------------------------------------------
| Deserts Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Deserts = Backbone.PageableCollection.extend({
	model: App.Models.Desert,
	url: '/'+App.myRoot+"/deserts/deserts",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 10,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});



/*
|---------------------------------------------------------------------------------
| Flavors Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Flavors = Backbone.PageableCollection.extend({
	model: App.Models.Flavor,
	url: '/'+App.myRoot+"/flavors/flavors",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 10,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});

/*
|---------------------------------------------------------------------------------
| Colors Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Colors = Backbone.PageableCollection.extend({
	model: App.Models.Color,
	url: '/'+App.myRoot+"/colors/colors",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 10,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});


/*
|---------------------------------------------------------------------------------
|Products Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Products = Backbone.PageableCollection.extend({
	model: App.Models.Product,
	url: '/'+App.myRoot+"/products/products",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 10,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});


/*
|---------------------------------------------------------------------------------
|Projects Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Projects = Backbone.PageableCollection.extend({
	model: App.Models.Project,
	url: '/'+App.myRoot+"/c_projects/projects",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 5,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});
/*
|---------------------------------------------------------------------------------
| Project Planning Collection
|---------------------------------------------------------------------------------
*/
App.Collections.ProjectPlanning = Backbone.Collection.extend({
	model: App.Models.ProjectPlanning,
	url: '/'+App.myRoot+"/c_projects/planning_actions",
});



/*
|---------------------------------------------------------------------------------
|Assignments Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Assignments = Backbone.PageableCollection.extend({
	model: App.Models.Assignment,
	url: '/'+App.myRoot+"/c_assignments/assignments",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 5,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});


/*
|---------------------------------------------------------------------------------
|Sectors Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Sectors = Backbone.PageableCollection.extend({
	model: App.Models.Sector,
	url: '/'+App.myRoot+"/c_sectors/sectors",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 5,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}
});


/*
|---------------------------------------------------------------------------------
|Positions Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Positions = Backbone.PageableCollection.extend({
	model: App.Models.PositionModel,
	url: '/'+App.myRoot+"/c_positions/positions",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 5,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}

});


/*
|---------------------------------------------------------------------------------
|Departments Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Departments = Backbone.PageableCollection.extend({
	model: App.Models.Department,
	url: '/'+App.myRoot+"/c_departments/departments",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 5,
	    sortKey: "depName"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}

});


/*
|---------------------------------------------------------------------------------
|Users Pageable Collection
|---------------------------------------------------------------------------------
*/
App.Collections.Users = Backbone.PageableCollection.extend({
	model: App.Models.User,
	url: '/'+App.myRoot+"/users/users",
	mode: 'server',
	// Initial pagination states
  	state: {
	    pageSize: 5,
	    sortKey: "name"
	    //order: 1
	  },
	// You can remap the query parameters from `state` keys from
	// the default to those your server supports
	queryParams: {
		totalPages: null,
		totalRecords: null,
		sortKey: "sort"/*,
		q: "state:closed repo:jashkenas/backbone"*/
	},
	parseState: function (resp, queryParams, state, options) {
    	return {totalRecords: resp.itemCount};
    },

	parseRecords: function (resp, options) {
		return resp.items;
	}

});


/*
|---------------------------------------------------------------------------------
|User Permission Collection
|---------------------------------------------------------------------------------
*/
App.Collections.UserPermissions = Backbone.Collection.extend({
	model: App.Models.UserPermission,
	url: '/'+App.myRoot+"/c_users/edit_permissions",
	
	initialize : function(){
		//this.model.set('id',this.idx);
	},
	savePerms: function() {
	    var collection = this;
	    options = {
	    	 wait:true,
	      success: function(model, response, xhr) {
	     /* 	console.log(model);
	      	console.log(response);
	      	console.log(xhr);*/
		        collection.reset(model);
		        //Showing Success Message
				$.notify({ message: model.message},
						    { type: 'success',
						   z_index: 10000}
						);
		      },//#success
	      error: function(model,response,xhr){
	      	console.log(model);
	      	//console.log(response);
	      	//console.log(xhr);
				//Showing Error Message

				$.notify({ message: model.responseText},
						    { type: 'danger',
						   z_index: 10000}
						);
			}//#error
	    };//#options
	    return Backbone.sync('update', this, options);
  	}

});