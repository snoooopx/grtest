App.Router = Backbone.Router.extend({
routes: {
	'orders'							: 'orders',
	'orderdetails/:order_id'	: 'orderdetails',
	'settings'							: 'settings',
	'clients'							: 'clients',
	'clientactions'						: 'clientactions',
	'clientactions/:action'				: 'clientactions',
	'clientactions/:action(/:client_id)': 'clientactions',
	'sets'								: 'sets',
	'setactions'						: 'setactions',
	'setactions/:action'				: 'setactions',
	'setactions/:action(/:set_id)'		: 'setactions',
	'coupons'							: 'coupons',
	/*'couponactions'						: 'couponactions',
	'couponactions/:action'				: 'couponactions',
	'couponactions/:action(/:coupon_id)': 'couponactions',	*/
	'products' 							: 'products',
	'projectdetails/:project_id'		: 'projectDetails',
	'flavors' 							: 'flavors',
	'colors' 							: 'colors',
	'deserts' 							: 'deserts',
	'attributes' 						: 'attributes',
	'attrgroups' 						: 'attrgroups',
	'users' 	 						: 'users',
	'userprofile/:user_id'				: 'userProfile',
	'userprofile/:user_id/:tab'			: 'userProfile',
},

invalidRequest : function(){
	alert('invalid Request');
},

/*
|---------------------------------------------------------------------------------
| Orders Page Load
|---------------------------------------------------------------------------------
*/
orders: function(){
	console.log('orders');
	new App.Views.initOrders();
},
	
/*
|---------------------------------------------------------------------------------
| Order Details Page Load
|---------------------------------------------------------------------------------
*/
orderdetails: function(order_id){
	(order_id===undefined)?order_id=-1:order_id=order_id
	console.log('orderdetails');
	new App.Views.initOrderDetails();
},

/*
|---------------------------------------------------------------------------------
| Settings Page Load
|---------------------------------------------------------------------------------
*/
settings: function(){
	console.log('settings');
	new App.Views.initSettings();
},


/*
|---------------------------------------------------------------------------------
| Client Actions Page Load
|---------------------------------------------------------------------------------
*/
clientactions: function(action,client_id){
	(client_id===undefined)?client_id=0:client_id=client_id;
	(action===undefined)?action=0:action=action;
	console.log('clientactions/'+action+'/'+client_id);
	/*if ((action == 'e' && client_id == 0) || (action == 0 && client_id == 0) || (action == 'c' && client_id !== 0) || (action !== 'e' || action !== 'c')) {
		//appRouter.navigate("userprofile/"+rowmodel.get('id'),{trigger:true});
		appRouter.navigate('/clients',{trigger:true});
	}*/
	new App.Views.initClientActionsPage({ action:action, client_id:client_id});
	Backbone.history.stop();
	return;
},

/*
|---------------------------------------------------------------------------------
| Clients Page Load
|---------------------------------------------------------------------------------
*/
clients: function(){
	console.log('clients');
	new App.Views.initClient();

	$('#mdlCreateClient').on('shown.bs.modal', function () {
    	$('#clientName').focus();
	});
	$('#mdlEditClient').on('shown.bs.modal', function () {
    	$('#clientNameEdit').focus();
	});
},

/*
|---------------------------------------------------------------------------------
| Set Actions Page Load
|---------------------------------------------------------------------------------
*/
setactions: function(action,set_id){
	(set_id===undefined)?set_id=0:set_id=set_id;
	(action===undefined)?action=0:action=action;
	console.log('setactions/'+action+'/'+set_id);
	/*if ((action == 'e' && set_id == 0) || (action == 0 && set_id == 0) || (action == 'c' && set_id !== 0) || (action !== 'e' || action !== 'c')) {
		//appRouter.navigate("userprofile/"+rowmodel.get('id'),{trigger:true});
		appRouter.navigate('/sets',{trigger:true});
	}*/
	new App.Views.initSetActionsPage({ action:action, set_id:set_id});
	Backbone.history.stop();
	return;
},

/*
|---------------------------------------------------------------------------------
| Sets Page Load
|---------------------------------------------------------------------------------
*/
sets: function(){
	console.log('sets');
	new App.Views.initSet();
	//tinymce.suffix = '.min';
	//alert(tinymce.baseURL);
	tinymce.init({
						selector : '#setDescription',
						  plugin : 'a_tinymce_plugin',
		  		 a_plugin_option : true,
		  a_configuration_option : 400

				});

	$('#mdlCreateSet').on('shown.bs.modal', function () {
    	$('#setName').focus();
	});
	$('#mdlEditSet').on('shown.bs.modal', function () {
    	$('#setNameEdit').focus();
	});
},

/*
|---------------------------------------------------------------------------------
| Coupons Page Load
|---------------------------------------------------------------------------------
*/
coupons: function(){
	console.log('coupons');
	new App.Views.initCoupon();
	
	$('#mdlCouponActions').on('shown.bs.modal', function () {
    	$('#couponCode').focus();
	});
},

/*
|---------------------------------------------------------------------------------
| Attributes Page Load
|---------------------------------------------------------------------------------
*/
attributes: function(){
	console.log('attributes');
	new App.Views.initAttribute();
	
	$('#mdlCreateAttribute').on('shown.bs.modal', function () {
    	$('#attributeName').focus();
	});
	$('#mdlEditAttribute').on('shown.bs.modal', function () {
    	$('#attributeNameEdit').focus();
	});
},


/*
|---------------------------------------------------------------------------------
| AttrGroups Page Load
|---------------------------------------------------------------------------------
*/
attrgroups: function(){
	console.log('attrgroups');
	new App.Views.initAttrGroup();

	$('#mdlCreateAttrGroup').on('shown.bs.modal', function () {
    	$('#attrgroupName').focus();
	});
	$('#mdlEditAttrGroup').on('shown.bs.modal', function () {
    	$('#attrgroupNameEdit').focus();
	});
},

/*
|---------------------------------------------------------------------------------
| Deserts Page Load
|---------------------------------------------------------------------------------
*/
deserts: function(){
	console.log('deserts');
	new App.Views.initDesert();
	//tinymce.suffix = '.min';
	//alert(tinymce.baseURL);
	tinymce.init({
						selector : '#desertDescription',
						  plugin : 'a_tinymce_plugin',
		  		 a_plugin_option : true,
		  a_configuration_option : 400

				});

	$('#mdlCreateDesert').on('shown.bs.modal', function () {
    	$('#desertName').focus();
	});
	$('#mdlEditDesert').on('shown.bs.modal', function () {
    	$('#desertNameEdit').focus();
	});
},

/*
|---------------------------------------------------------------------------------
| Flavors Page Load
|---------------------------------------------------------------------------------
*/
flavors: function(){
	console.log('flavors');
	new App.Views.initFlavor();

	$('#mdlCreateFlavor').on('shown.bs.modal', function () {
    	$('#flavorName').focus();
	});
	$('#mdlEditFlavor').on('shown.bs.modal', function () {
    	$('#flavorNameEdit').focus();
	});
},

/*
|---------------------------------------------------------------------------------
| Colors Page Load
|---------------------------------------------------------------------------------
*/
colors: function(){
	console.log('colors');
	new App.Views.initColor();
	$('#colorHex').colorpicker();
	$('#mdlCreateColor').on('shown.bs.modal', function () {
    	$('#colorName').focus();
	});
	$('#mdlEditColor').on('shown.bs.modal', function () {
    	$('#colorNameEdit').focus();
	});
},


/*
|---------------------------------------------------------------------------------
| Products Page Load
|---------------------------------------------------------------------------------
*/
products: function(){
	console.log('products');
	new App.Views.initProduct();

	tinymce.init({
						selector : '#productDescription',
						  plugin : 'a_tinymce_plugin',
		  		 a_plugin_option : true,
		  a_configuration_option : 400

				});
	
	$('#mdlCreateProduct').on('shown.bs.modal', function () {
    	$('#productName').focus();
	});
	$('#mdlEditProduct').on('shown.bs.modal', function () {
    	$('#productNameEdit').focus();
	});
},



/*
|---------------------------------------------------------------------------------
|Users Page Load
|---------------------------------------------------------------------------------
*/
users: function(){
	console.log('users');
	
	new App.Views.initUser();

	$('#mdlCreateUser').on('shown.bs.modal', function () {
    	$('#userName').focus();
	});
	
	// "myAwesomeDropzone" is the camelized version of the HTML element's ID
	/*App.DZ.userAvatar = new Dropzone('#userAvatar', 
		{
		  url: '/'+App.myRoot+'/c_users/upload_avatar',
		  paramName: "file", // The name that will be used to transfer the file
		  uploadMultiple: false,
		  clickabel: true,
		  addRemoveLinks: true,
		  maxFilesize: 2, // MB
		  maxFiles: 1, //single file upload
		  
		  //On Upload Success Change New file Name Hidden Value
		  success: function(file, response){
		  	$("#userAvatarNameHdn").val(response.newFileName);
		  //	console.log(response);
		  },
		  accept: function(file, done) {
	 		done(); 
		  }
	});*/
},


/*
|---------------------------------------------------------------------------------
| Userprofile Page Load
|---------------------------------------------------------------------------------
*/
userProfile : function( user_id, tab ){
	new App.Views.Permissions({idx:user_id});
	//console.log('userprofile route'+tab);
	//var navTab = new App.Views.NavTabs();
	switch (tab){
		case 'permissions':
				//var userPermissions = new App.Collections.UserPermissions();
				// Fetching Permissions for Specified User
				//userPermissions.fetch( {data:{user:user_id}} ).then( function(){
				//		var permissionsView = new App.Views.Permissions({collection:userPermissions});
				//		$('#userPermsTable').append(permissionsView.render().el);
				//	});// #fetch.then
				break;
		case 'activity':
				console.log('activity');
				break;
		case 'timeline':
				console.log('timeline');
				break;
		case 'about':
				console.log('about');
				break;
		default:
		break;
	}

},








/*########################################################################################################################################################################
########################################################################################################################################################################
########################################################################################################################################################################*/












reports : function(){
	console.log(Backbone.history.fragment);
	new App.Views.initReports();
	/*new App.Views.acceptTimesheet();*/

},


tsdetails : function(p1,p2,p3){
	console.log('ts details');
	console.log(Backbone.history.fragment);
	new App.Views.acceptTimesheet();

},
/*
|---------------------------------------------------------------------------------
| Edit Timesheets page load
|---------------------------------------------------------------------------------
*/
timesheetedit : function(){
	console.log('Edit Timesheets');
	//new App.Views.acceptTimesheet();
},


/*
|---------------------------------------------------------------------------------
| Pending Timesheets page load
|---------------------------------------------------------------------------------
*/
pending_timesheets : function(){
	console.log('Pending Timesheets');
	//console.log(Backbone.history.fragment);
	//Backbone.history.loadUrl();
	new App.Views.acceptTimesheet();
	$(function () {
	  $('[data-toggle="popover"]').popover()
	});
	return;
},

/*
|---------------------------------------------------------------------------------
| Timesheet Create Page Load
| when action is 'c' 
|	then
| 		p1 is year 
|		p2 is week
|
| when action is 'e'
|	then
| 		p1 is ts_id
|---------------------------------------------------------------------------------
*/
/*tsactions: function(action=false,p1=false,p2=false){
	console.log('tsactions/'+action+'/'+p1+'/'+p2);

	new App.Views.initTimesheetActions({ action:action, p1:p1, p2:p2 });
	Backbone.history.stop();
	return;
},*/
/*
|---------------------------------------------------------------------------------
| Timesheets Page Load
|---------------------------------------------------------------------------------
*/
timesheets: function(){
	console.log('timesheets');
	var temp = new App.Views.initTimesheet();
},



/*
|---------------------------------------------------------------------------------
| Project Details Page
|---------------------------------------------------------------------------------
*/
projectDetails : function(p1){
	console.log('projectdetails details');
	//console.log(Backbone.history.fragment);
	//new App.Views.acceptTimesheet();

},




/*
|---------------------------------------------------------------------------------
| Assignments Page Load
|---------------------------------------------------------------------------------
*/
assignments: function(){
	console.log('assignments');
	new App.Views.initAssignment();

	$('#mdlCreateAssignment').on('shown.bs.modal', function () {
    	$('#assignmentName').focus();
	});
	$('#mdlEditAssignment').on('shown.bs.modal', function () {
    	$('#assignmentNameEdit').focus();
	});
},



/*
|---------------------------------------------------------------------------------
| Clientprofile Page Load
|---------------------------------------------------------------------------------
*/
clientProfile : function( user_id, tab ){
	console.log('clientprofile route'+tab);
	//var navTab = new App.Views.NavTabs();
},
/*
|---------------------------------------------------------------------------------
| Sectors Page Load
|---------------------------------------------------------------------------------
*/
sectors: function(){
	console.log('sector');
	new App.Views.initSec();

	$('#mdlCreateSector').on('shown.bs.modal', function () {
    	$('#secName').focus();
	});
	$('#mdlEditSector').on('shown.bs.modal', function () {
    	$('#secNameEdit').focus();
	});
},






/*
|---------------------------------------------------------------------------------
|Job Titles Page Load
|---------------------------------------------------------------------------------
*/
jobtitles: function(){
	//console.log('job titles');
	
	new App.Views.initPos();

	$('#mdlCreatePosition').on('shown.bs.modal', function () {
    	$('#name').focus();
	});
	$('#mdlEditPosition').on('shown.bs.modal', function () {
    	$('#name').focus();
	});
},

/*
|---------------------------------------------------------------------------------
|Departments Page Load
|---------------------------------------------------------------------------------
*/
departments: function(){
	//console.log('departments');
	$('#mdlCreateDepartment').on('shown.bs.modal', function () {
    	$('#depName').focus();
	});
	$('#mdlEditDepartment').on('shown.bs.modal', function () {
    	$('#depName').focus();
	});
	new App.Views.initDep();
},

/*
|---------------------------------------------------------------------------------
|Company Page Load
|---------------------------------------------------------------------------------
*/
company: function(){
	//console.log('company');
}




});//#App.Router