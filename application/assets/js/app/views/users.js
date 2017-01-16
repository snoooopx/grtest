/*
|---------------------------------------------------------------------------------
|Initing Users Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initUser = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('user:details', this.detailsItem, this);
		vent.on('user:edit', this.editItem, this);
		vent.on('user:delete', this.deleteItem, this);

		var userCollection = new App.Collections.Users();
		// Fetch Values From Server
		userCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateUserModal({collection: userCollection});
		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: userCollection});
		// Generate and Show Grid		
		this.generateGrid({ collection: userCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: userCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: userCollection});
	},


	//User Profile Page Redirect
	//############################
	detailsItem : function(rowmodel){
		appRouter.navigate("userprofile/"+rowmodel.get('id'),{trigger:true});
		//window.location.href(App.myroot + '/userprofile' + rowmodel.get('id'));
		console.log("userprofile/"+rowmodel.get('id'));
		//Bacbone.history.navogate("userprofile/"+rowmodel.get('id'));
	},

	//Edit User Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		//appRouter.navigate("#users/edit");
		var editModalView = new App.Views.EditUserModal({model: rowmodel});
		
		$("#userModalDiv").html(editModalView.el);

		//Setting Cursor to Name Field
		$('#mdlEditUser').on('shown.bs.modal', function () {
	    	$('#userNameEdit').focus();
		});
		
		/*$('#userAvatarEdit').dropzone({
			init : function(){
				this.on('addedfile', function(test){
					console.log(test);
					$.notify({ message : "Avatar Upload Success"},
								    { type : 'success',
								   z_index : 10001 });
				});

				this.on('removedfile', function(){
					$("#userAvatarNameHdnEdit").val("");
					$.notify({ message : "Avatar Removed. Old Avatar is Remaining."},
								    { type : 'info',
								   z_index : 10001 });
				});
			},
			url: '/'+App.myRoot+'/c_users/upload_avatar',
			paramName: "file", // The name that will be used to transfer the file
			uploadMultiple: false,
			clickabel: true,
			addRemoveLinks: true,
			maxFilesize: 2, // MB
			maxFiles: 1, //single file upload

			//On Upload Success Change New file Name Hidden Value
			success: function(file, response){
				$("#userAvatarNameHdnEdit").val(response.upload_statusx.newFileName);
				//console.log(response);
				//console.log(file);
			},
			accept: function(file, done) {
				done(); 
			}
		});*/

		var entireSexId = rowmodel.get('sex');
		var entirePosId = rowmodel.get('positionId');
		
		

		$("#userSexEdit option[value=\""+entireSexId+"\"]").prop('selected',true);
		$("#userPositionIdEdit option[value=\""+entirePosId+"\"]").prop('selected',true);
		// Showing Bootstrap Modal
		$("#mdlEditUser").modal('toggle');
	},

	//Delete User Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){
		//console.log('vend deleting');
		//console.log('editing '+this.model.get('name'));
		var confirmDelModalView = new App.Views.DeleteUserModal({model: rowmodel});
		//console.log(confirmDelModalView.el);

		$("#userDelConfModalDiv").html(confirmDelModalView.el);
		// Showing Bootstrap Modal
		$("#mdlDeleteConfirm").modal('toggle');
	},


	// Generate Grid
	//##############
	generateGrid : function(params){
		var ScrollableBody = new Backgrid.Body.extend({

		});
		grid = new Backgrid.Grid({

			//body: ScrollableBody,
			className: 'table table-hover',
			// Initing Table Columns
			columns:[/*{
					name: 'id',
					label: 'ID',
					editable: false,
					cell: Backgrid.IntegerCell.extend({
		      		orderSeparator: ''
					})},*/{
					name: 'fullName',
					label: 'User',
					editable: false,
					cell: 'string'
				},{
					name: 'login',
					label: 'Login',
					editable: false,
					cell: 'string'
				},/*{
					name: 'sex',
					label: 'Sex',
					editable: false,
					cell: 'string'
				},*/{
					name: 'email',
					label: 'Email',
					editable: false,
					cell: 'string'
				},/*{
					name: 'phone',
					label: 'Phone',
					editable: false,
					cell: 'string'
				},{
					name: 'address',
					label: 'Address',
					editable: false,
					cell: 'string'
				},{
					name: 'position',
					label: 'Position',
					editable: false,
					cell: 'string'
				},{
					name: 'inAppStatus',
					label: 'Status',
					editable: false,
					cell: 'string'
				},*/{
					name: 'isActive',
					label: 'Login Allowed',
					editable: false,
					cell: 'string'
				},{
					name: '1',
					label: 'Actions',
					editable: false,
					sortable: false,
					cell: params.actionsCell
					
				}],

			//Data Collection For Table 
			collection: params.collection
			
		});//#userGrid
		
		$('#gridUsers').append(grid.render().el);
		return grid;
	},//generateGrid


	// Generate Paginator
	//####################
	generatePaginator : function(params){

		// Initialize the paginator
		var paginator = new Backgrid.Extension.Paginator({
				windowSize: 5,
				lideScale: 0.25, // Default is 0.5

				// Whether sorting should go back to the first page
				goBackFirstOnSort: false, // Default is true

				collection: params.collection
		});//#paginator
		
		// Render the paginator
		$('#gridUsers').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			  	collection: params.collection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Search Job Title" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#gridUsers").before(serverSideFilter.render().el);
		$("#gridUsers").before($("#btnCreateUser"));
		// Add some space to the filter and move it to the right
		$(serverSideFilter.el).css({float: "right", margin: "20px"});

		return serverSideFilter;
	},//#generateFilter


	//Generate Actions Buttons
	//########################
	generateActions: function(params){
		
		//Actions button Generation
		var actionsCell = Backgrid.Cell.extend({
			template: _.template($("#action_buttons").html()),
			events:{
				'click .details': 'detailsRow',
				'click .edit'   : 'editRow',
				'click .delete' : 'deleteRow'
			},
			
			detailsRow : function(e){
				//e.preventDefault();
				vent.trigger('user:details', this.model);
			},

			editRow : function(e){
				e.preventDefault();
				vent.trigger('user:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('user:delete', this.model);
			},

			render : function(){
				this.$el.append( this.template(params.collection));
				this.delegateEvents();
				return this;
			}
		});//#actionsCell

		return actionsCell;
	}//#generateActions
});



/*
|---------------------------------------------------------------------------------
|Create User Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateUserModal = Backbone.View.extend({
	el: '#dCreateUser',
	events: {
		'click button#sbmtUserCreate': 'createUser',
		'keydown input#userName': 'preventEnter'
	},

	formish: {},
	initialize : function(){
		this.render();
		this.initCreateModal();

	},

	initCreateModal : function(){
		this.formish.userName 		= $( "#userName" );
 		this.formish.userMiddle 	= $( "#userMiddle" );
 		this.formish.userSname 		= $( "#userSname" );
 		this.formish.userLogin 		= $( "#userLogin" );
 		this.formish.userEmail 		= $( "#userEmail" );
 		this.formish.userPhone 		= $( "#userPhone" );
 		this.formish.userAddress 	= $( "#userAddress" );
 		this.formish.userSex 		= $( "#userSex" );
 		/*this.formish.userPositionId = $( "#userPositionId" );
 		this.formish.userPositionIdTxt = $( "#userPositionId option:selected");*/
 		this.formish.userPassword	= $( "#userPassword" );
 		this.formish.userPasswordConfirm = $( "#userPasswordConfirm" );
 		/*this.formish.userAvatar = $( "#userAvatarNameHdn" );*/
 		/*this.formish.userStatus 	= $("#userStatus");*/
 		this.formish.userIsActive 	= $("#userIsActive")
 		//console.log(this.formish);
	},

	emptyCreateModal : function(form){
		this.formish.userName.val('');
		this.formish.userMiddle.val('');
		this.formish.userSname.val('');
		this.formish.userLogin.val('');
		this.formish.userEmail.val('');
		this.formish.userPhone.val('');
		this.formish.userAddress.val('');
		//this.formish.userSex.val('');
		//this.formish.userPositionId.val('');
		//this.formish.userPositionIdTxt.val('');
		$('select option:disabled').prop("selected",true);
		this.formish.userPassword.val('');
		this.formish.userPasswordConfirm.val('');
		/*this.formish.userAvatar.val('');
		this.formish.userStatus.prop('checked',false);*/
		this.formish.userIsActive.prop('checked',false);
		//console.log(App.DZ.userAvatar);
		//App.DZ.userAvatar.removeAllFiles();
	},
	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	createUser : function(e){
		e.preventDefault();
		 
		console.log('create');
		userForm = {};
		
		userForm[ 'name'			] = this.formish.userName.val();
		userForm[ 'middle'			] = this.formish.userMiddle.val();
		userForm[ 'sname'			] = this.formish.userSname.val();
		userForm[ 'login'			] = this.formish.userLogin.val();
		userForm[ 'email'			] = this.formish.userEmail.val();
		userForm[ 'phone'			] = this.formish.userPhone.val();
		userForm[ 'address'			] = this.formish.userAddress.val();
		userForm[ 'sex'				] = this.formish.userSex.val();
		/*userForm[ 'positionId'		] = this.formish.userPositionId.val();*/
		/*userForm[ 'position'		] = this.formish.userPositionIdTxt.text();*/
		userForm[ 'password'		] = this.formish.userPassword.val();
		userForm[ 'passwordConfirm'	] = this.formish.userPasswordConfirm.val();
		userForm[ 'fullName'] 		  = userForm['name'] + ' ' + 
										userForm['middle'] + ' ' + 
										userForm['sname'];
		
		/*// Check and Set Avatar Value /Default if empty/
		if (  this.formish.userAvatar.val() !== "") {
			userForm['avatar'] = this.formish.userAvatar.val();
		}
		else{
			userForm['avatar'] = userForm['sex'] + "s2d6s6s6s5d9w9w6s.png"
		}

		// In App Status Check
		if ( this.formish.userStatus.is(':checked') ) {
			userForm['inAppStatus'] = 1;
		}
		else{
			userForm['inAppStatus'] = 0;
		}*/
		
		// login Allowed Check 
		if ( this.formish.userIsActive.is(':checked') ) {
			userForm['isActive'] = 1;
		}
		else{
			userForm['isActive'] = 0;
		}
		
		var self = this;
		this.collection.create( new App.Models.User(userForm), 
				{
					wait:true,
					success: function(model,response){
						//Showing Success Message
						$.notify({ message : "<b>* "+model.get('fullName')+" *</b>"+" Created Successfully."},
								    { type : 'success',
								   z_index : 10000 });
						
						//empty tag values
						self.emptyCreateModal( self.formish );
						
						//Closing Modal
						$('#mdlCreateUser').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 
	

	}//#createUser
	

});



/*
|---------------------------------------------------------------------------------
|Edit User View
|---------------------------------------------------------------------------------
*/
App.Views.EditUserModal = Backbone.View.extend({
	
	template: App.template('tmplUserEditModal'),

	events: {
		"click button#sbmtUserEdit": 'submitUserEdit'
	},
	initialize : function(){
		this.render();
		/*$('#mdlEditUser').on('hidden.bs.modal', function () {
    		console.log("hidding modal");
    		appRouter.navigate('users/users');
		});
		$('#mdlEditUser').on('hide.bs.modal', function () {
	    	console.log("hide modal");
	    	appRouter.navigate('users/users');
		});*/
	},

	render : function(){
		html = this.template( this.model.toJSON() )
		this.$el.html( html );
		return this;
	},

	// Edit Button Click On MODAL
	submitUserEdit : function(e){
		e.preventDefault();
		var save_values = {};

		console.log('update Submit');
		
		
			
		save_values[ 'name'				] = $( "#userNameEdit"				).val();
		save_values[ 'middle'			] = $( "#userMiddleEdit"			).val();
		save_values[ 'sname'			] = $( "#userSnameEdit"				).val();
		save_values[ 'login'			] = $( "#userLoginEdit"				).val();
		save_values[ 'email'			] = $( "#userEmailEdit"				).val();
		save_values[ 'phone'			] = $( "#userPhoneEdit"				).val();
		save_values[ 'address'			] = $( "#userAddressEdit"			).val();
		save_values[ 'sex'				] = $( "#userSexEdit"				).val();
		/*save_values[ 'positionId'		] = $( "#userPositionIdEdit"		).val();*/
		/*save_values[ 'position'			] = $( "#userPositionIdEdit option:selected").text();*/
		save_values[ 'password'			] = $( "#userPasswordEdit"			).val();
		save_values[ 'passwordConfirm'	] = $( "#userPasswordConfirmEdit"	).val();
		/*save_values[ 'avatar'			] = $( "#userAvatarNameHdnEdit"		).val();*/
		save_values[ 'fullName'] 		  = save_values['name'] 
								+ ' ' + save_values['middle'] 
								+ ' ' + save_values['sname'];

		/*if ( $("#userStatusEdit").is(':checked') ) {
			save_values['inAppStatus'] = 1;
		}
		else{
			save_values['inAppStatus'] = 0;
		}*/
		

		if ( $("#userIsActiveEdit").is(':checked') ) {
			save_values['isActive'] = 1;
		}
		else{
			save_values['isActive'] = 0;
		}
		

		//Saving Edited Values
		this.model.save(
			save_values,
			{
				wait:true,
				success: function(model,response){
					console.log(response);
					
					//Showing Success Message
					$.notify({ message: response.message},
							    { type: 'success',
							   z_index: 10000}
							);
					
					//Hiddin Bootstrap Modal
					$("#mdlEditUser").modal('hide');
					
					/*//Clearing Values
					this.$('#name').val('');
					this.$('#note').val('');*/
				},//#success
				error: function(model,response){
					//Showing Success Message
					$.notify({ message: response.responseJSON.message},
							    { type: 'danger',
							   z_index: 10000}
							);
				}//#error
			}
		);//#save
		
	}//#end of submit
});


/*
|---------------------------------------------------------------------------------
| Delete User Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteUserModal = Backbone.View.extend({
	
	template: App.template('tmplDeleteNote'),
	
	events: {
		"click button#confirmDelete": 'confirmDelete'
	},

	initialize : function(){
		this.render();
	},

	//
	render : function(){
		html = this.template( this.model.toJSON() )
		this.$el.html( html );
		return this;
	},

	//  DESTROY User
	//=====================
	confirmDelete : function(e){
		e.preventDefault();
		console.log('confirm');
		// Destroying Selected Model
		this.model.destroy( { 
			wait:true,	
			success: function( model, response){
						//Showing Success Message
						$.notify({ message: model.attributes.name +" "+ response.message},
							     { type: 'success',
							 	z_index: 10000}
						);
			},//success
			error: function(model,response){
						//Showing Success Message
						$.notify({ message: response.responseJSON.message},
								    { type: 'danger',
								   z_index: 10000}
						);
			}//error
		});
		//Hiding Bootstrap Modal
		$("#mdlDeleteConfirm").modal('hide');

	}//#confirm
	
});//#DeleteUserModal



/*
|---------------------------------------------------------------------------------
| User Profile Permissions Table
|---------------------------------------------------------------------------------
*/
App.Views.Permissions = Backbone.View.extend({
	el: '#ursProfileBlock',
	
/*#userPermsTable*/
	events:{
		/*'change #userPermsTable tbody tr td input#r': 'readChecked',
		'change #userPermsTable tbody tr td input#c': 'createChecked',
		'change #userPermsTable tbody tr td input#u': 'updateChecked',
		'change #userPermsTable tbody tr td input#d': 'deleteChecked',*/
		'change #userPermsTable tbody tr td input#check_all' : 'checkAllChecked',
		'click #sbmtUserPermsEdit'							 : 'submitPermsEdit',
		'change #usrViewAllReports' 						 : 'changeSettings',
		'change #usrCombinedSidebar' 						 : 'changeSettings',
		'submit #frmPassChange' 							 : 'submitPassChange'

	},
	initialize : function(){
		permsArray = this.initPerms();
		this.collection = new App.Collections.UserPermissions();
		this.collection.reset(permsArray);
	},

	changeSettings: function(e) {
		var settingsName = 0;
		var settingsValue = 0;

		// Check for chekced value :)))
		if ( e.currentTarget.checked ) { settingsValue = '1';} else { settingsValue = '0';}
		
		// Get User Id
		var user_id = $(e.currentTarget).attr('data-user-id');

		// Check for chced attribute 
		if ( $(e.currentTarget).attr('id') == 'usrViewAllReports' ) {
			
			settingsName = 'viewAllReports';
		}
		else if ( $(e.currentTarget).attr('id') == 'usrCombinedSidebar' ) {
			
			settingsName = 'combinedSidebar';
		}
		else
		{
			return;
		}
		console.log('save' +'/'+user_id+'');
		$.ajax({
			 url: '/'+App.myRoot+'/c_users/update_settings',
			type: 'post',
			data: {
				user_id : user_id,
					key : settingsName,
				  value : settingsValue
			},
			success: function(response){
				//console.log(response);
				if (response) {
					$.notify({ message : response.message},
								    { type : 'success',
								   z_index : 10001 });
				}
			},
			error: function(response){
					//console.log(response);
				if (response.responseText) {
					//console.log(response.responseJSON.message);
					$.notify({ message : response.responseJSON.message},
								    { type : 'error',
								   z_index : 10001 });
				}	
			}
		})

	},

	checkAllChecked : function(e){
		
		if ( e.currentTarget.checked ) 
		{
			$(e.currentTarget).closest('tr').find('#r').prop('checked',true);
			$(e.currentTarget).closest('tr').find('#c').prop('checked',true);
			$(e.currentTarget).closest('tr').find('#u').prop('checked',true);
			$(e.currentTarget).closest('tr').find('#d').prop('checked',true);
		}
		else
		{
			$(e.currentTarget).closest('tr').find('#r').prop('checked',false);
			$(e.currentTarget).closest('tr').find('#c').prop('checked',false);
			$(e.currentTarget).closest('tr').find('#u').prop('checked',false);
			$(e.currentTarget).closest('tr').find('#d').prop('checked',false);
		}
	},

	submitPermsEdit : function(){
		var newPerms = this.initPerms();
		console.log('saving');
		this.collection.reset(newPerms);
		this.collection.savePerms();
	},


	initPerms : function(){
		perm = [];
		this.$el.find('#userPermsTable tbody tr').each(function(idx, item){
			
			if ( $( item ).find('input#c').is(':checked') ) { cPerm = 1; } else { cPerm = 0; }

			if ( $( item ).find('input#r').is(':checked') ) { rPerm = 1; } else { rPerm = 0; }
			
			if ( $( item ).find('input#u').is(':checked') ) { uPerm = 1; } else { uPerm = 0; }

			if ( $( item ).find('input#d').is(':checked') ) { dPerm = 1; } else { dPerm = 0; }

			perm[idx] = { 	section_name : $.trim( $( item ).find('span#section_name').text()),
							  section_id : $.trim( $( item ).find('span#section_name').data('section-id')),
						 subsection_name : $.trim( $( item ).find('span#subsection_name').text()),
						   subsection_id : $.trim( $( item ).find('span#subsection_name').data('subsection-id')),
							 section_seq : $.trim( $( item ).find('input#section_seq').val()),
						  subsection_seq : $.trim( $( item ).find('input#subsection_seq').val()),
						  		 user_id : $.trim( $( item ).data('user-id')),
									   r : rPerm,
									   c : cPerm,
									   u : uPerm,
									   d : dPerm}
 
		});
		return perm;
	},
	render : function(){
	/*	//console.log('this.collection');
		//console.log(this.collection);
		this.collection.each(function(item){
				//console.log(item);
				this.addOne(item);
		}, this)

		return this;*/
	},

	addOne : function(permission){

		//var section = new App.Views.Section({model: permission});
		//console.log('section');
		//console.log(section);

		//this.$el.append( section.render().el );
	},

	submitPassChange: function(e){
		e.preventDefault();
		$.ajax({
			url: '/'+App.myRoot+'/users/change_password',
			type:'post',
			data:{
					passEdit 	 : $(e.target).find('#passEdit').val(),
					confPassEdit : $(e.target).find('#confPassEdit').val()
			},
			success: function(response){
						$.notify({ message : response.message},
								    { type : 'success',
								   z_index : 10001 });
			},
			error: function(response){
						$.notify({ message : response.responseJSON.message},
								    { type : 'error',
								   z_index : 10001 });
			}

		});
	}
});


/*
|---------------------------------------------------------------------------------
| User Profile Permission Table Single Row
|---------------------------------------------------------------------------------
*/
App.Views.Section = Backbone.View.extend({
	render : function(){
		/*console.log('this.model');
		console.log(this.model);*/
		//this.$el.html( this.template( this.model.toJSON() ) );
		//return this;
	},
});


/*
|---------------------------------------------------------------------------------
| User Profile Nav Bar
|---------------------------------------------------------------------------------
*/
App.Views.NavTabs = Backbone.View.extend({
	el:'.nav-tabs-custom',

	events:{
		'click a#activityTab'	: 'activityTab',
		'click a#timelineTab'	: 'timelineTab',
		'click a#permissionsTab': 'permissionsTab',
		'click a#settingsTab'	: 'settingsTab',
		'click a#aboutTab'		: 'aboutTab'
	},

	activityTab : function(){
		console.log('click1');
		appRouter.navigate('activity');
	},
	timelineTab : function(e){
		e.preventDefault();
		console.log('click1');
		appRouter.navigate('timeline');
	},
});