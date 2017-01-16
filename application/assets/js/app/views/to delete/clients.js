/*
|---------------------------------------------------------------------------------
|Initing Clients Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initClient = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('client:details', this.detailsItem, this);
		vent.on('client:edit', this.editItem, this);
		vent.on('client:delete', this.deleteItem, this);

		var clientCollection = new App.Collections.Clients();
		// Fetch Values From Server
		clientCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateClientModal({collection: clientCollection});
		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: clientCollection});
		// Generate and Show Grid		
		this.generateGrid({ collection: clientCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: clientCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: clientCollection});
	},


	//Client Profile Page Redirect
	//############################
	detailsItem : function(rowmodel){
		appRouter.navigate("clientprofile/"+rowmodel.get('id'),{trigger:true});
		//window.location.href(App.myroot + '/clientprofile' + rowmodel.get('id'));
		console.log("clientprofile/"+rowmodel.get('id'));
		//Bacbone.history.navogate("clientprofile/"+rowmodel.get('id'));
	},

	//Edit Client Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		//appRouter.navigate("#clients/edit");
		var editModalView = new App.Views.EditClientModal({model: rowmodel});
		
		$("#clientModalDiv").html(editModalView.el);
		 $('#clientSectorsEdit').SumoSelect({
			okCancelInMulti: true,
			search:true
		});
		
		$('#clientDepartmentsEdit').SumoSelect({
			okCancelInMulti: true,
			search:true
		});

		/*$('#clientBtypeEdit').SumoSelect({
			okCancelInMulti: true,
			search:true
		});*/
		$('#clientSectorsEdit').SumoSelect({
			okCancelInMulti: true,
			search:true
		});
		//Setting Cursor to Name Field
		$('#mdlEditClient').on('shown.bs.modal', function () {
	    	$('#clientNameEdit').focus();
		});
		
		// Check And Fill Departments SumoSelect
		if (rowmodel.get('dep_ids_str')) {
			var entireDepIds = rowmodel.get('dep_ids_str').split(",");
			
			$.each( entireDepIds, function(idx,item){
				$('#clientDepartmentsEdit')[0].sumo.selectItem($.trim(item));
			});
			
		}

		// Check And Fill Sectors SumoSelect
		if (rowmodel.get('sec_ids_str')) {
			var entireSecIds = rowmodel.get('sec_ids_str').split(",");
			
			$.each( entireSecIds, function(idx,item){
				$('#clientSectorsEdit')[0].sumo.selectItem($.trim(item));
			});
		}

		// Check And Fill Btype SumoSelect
		/*if (rowmodel.get('btype_id')) {
			var entireBtypeId = rowmodel.get('btype_id');
				$('#clientBtypeEdit')[0].sumo.selectItem($.trim(entireBtypeId));
		}*/

		// Showing Bootstrap Modal
		$("#mdlEditClient").modal('toggle');
	},

	//Delete Client Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){
		//console.log('vend deleting');
		//console.log('editing '+this.model.get('name'));
		var confirmDelModalView = new App.Views.DeleteClientModal({model: rowmodel});
		//console.log(confirmDelModalView.el);

		$("#clientDelConfModalDiv").html(confirmDelModalView.el);
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
					label: 'Client',
					editable: false,
					cell: 'string'
				},{
					name: 'abbr',
					label: 'Abbr',
					editable: false,
					cell: 'string'
				},/*{
					name: 'email',
					label: 'Email',
					editable: false,
					cell: 'string'
				},*/{
					name: 'phone',
					label: 'Phone',
					editable: false,
					cell: 'string'
				},{
					name: 'dep_names',
					label: 'Departments',
					editable: false,
					cell: 'string'
				},/*{
					name: 'sec_names',
					label: 'sectors',
					editable: false,
					cell: 'string'
				},*//*{
					name: 'address',
					label: 'Address',
					editable: false,
					cell: 'string'
				},*/{
					name: 'tin',
					label: 'Tax Code',
					editable: false,
					cell: 'string'
				},{
					name: 'reg_num',
					label: 'Reg Num',
					editable: false,
					cell: 'string'
				},{
					name: 'is_visible',
					label: 'Visibility',
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
			
		});//#clientGrid
		
		$('#gridClients').append(grid.render().el);
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
		$('#gridClients').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			  	collection: params.collection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Search Client" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#gridClients").before(serverSideFilter.render().el);
		$("#gridClients").before($("#btnCreateClient"));
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
				'click .details' : 'detailsRow',
				'click .edit'    : 'editRow',
				'click .delete'  : 'deleteRow'
			},
			
			detailsRow : function(e){
				//e.preventDefault();
				vent.trigger('client:details', this.model);
			},

			editRow : function(e){
				e.preventDefault();
				vent.trigger('client:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('client:delete', this.model);
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
|Create Client Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateClientModal = Backbone.View.extend({
	el: '#dCreateClient',
	events: {
		'click button#sbmtClientCreate': 'createClient',
		'keydown input#clientName': 'preventEnter'
	},

	formish: {},
	initialize : function(){
		$('#clientSectors').SumoSelect({
			okCancelInMulti: true,
			search:true
		});
		
		$('#clientDepartments').SumoSelect({
			okCancelInMulti: true,
			search:true
		});
		
		/*$('#clientBtype').SumoSelect({
			okCancelInMulti: true,
			search:true
		});*/
		
		this.render();
		this.initCreateModal();

	},

	initCreateModal : function(){
		this.formish.clientName 			= $( "#clientName");
 		this.formish.clientAbbr 			= $( "#clientAbbr");
 		this.formish.clientContact 			= $( "#clientContact");
 		this.formish.clientEmail 			= $( "#clientEmail");
 		this.formish.clientPhone 			= $( "#clientPhone");
 		this.formish.clientAddress 			= $( "#clientAddress");
 		this.formish.clientAcc 				= $( "#clientAcc");
 		/*this.formish.clientBtype 			= $( "#clientBtype option:selected");
 		this.formish.clientBtypeId 			= $( "#clientBtype option:selected");*/
 		//this.formish.clientDepartments 		= $( "#clientDepartments")
 		//this.formish.clientDepartmentsIds 	= $( "#clientDepartments option:selected");
 		//this.formish.clientSectors 			= $( "#clientSectors");
 		//this.formish.clientSectorsIds		= $( "#clientSectors option:selected");
 		this.formish.clientRegNum			= $( "#clientRegNum");
 		this.formish.clientTin 				= $( "#clientTin");
 		this.formish.clientIsVisible 		= $( "#clientIsVisible");
 		//console.log(this.formish);
	},

	emptyCreateModal : function(form){
		this.formish.clientName.val(''); 
		this.formish.clientAbbr.val(''); 
		this.formish.clientContact.val(''); 
		this.formish.clientEmail.val(''); 
		this.formish.clientPhone.val(''); 
		this.formish.clientAddress.val(''); 
		this.formish.clientAcc.val(''); 	
		//this.formish.clientBtype.val(''); 
		$('select option:disabled').prop("selected",true);
		//this.formish.clientDepartments 	
		//this.formish.clientDepartments 	
		//this.formish.clientSectors 		
		//this.formish.clientSectors 		
		this.formish.clientRegNum.val('');
		this.formish.clientTin.val('');
		this.formish.clientIsVisible.prop('checked',false);
	},
	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	createClient : function(e){
		e.preventDefault();
		 
		console.log('create');
		clientForm = {};
		
		clientForm['name']			 = this.formish.clientName.val(); 		
		clientForm['abbr']			 = this.formish.clientAbbr.val(); 		
		clientForm['contact_person'] = this.formish.clientContact.val(); 		
		clientForm['email']			 = this.formish.clientEmail.val(); 		
		clientForm['phone']			 = this.formish.clientPhone.val(); 		
		clientForm['address']		 = this.formish.clientAddress.val(); 		
		clientForm['bank_acc']		 = this.formish.clientAcc.val(); 			
		/*clientForm['btype']		 = this.formish.clientBtype.text();
		clientForm['btypeId']		 = this.formish.clientBtypeId.val(); 		*/
		clientForm['reg_num']		 = this.formish.clientRegNum.val();
		clientForm['tin']			 = this.formish.clientTin.val();
		clientForm['is_visible']	 = this.formish.clientIsVisible 	
		clientForm['fullName']		 = clientForm['name'];

		clientForm['departmentsIds'] = $( "#clientDepartments").val();
		clientForm['sectorsIds'] 	 = $( "#clientSectors").val();

		/*
		DEPARTMENTS CHECK
		*/

		// Get Selected Departmets Text and Put It in Array
		var deps=[];
		$( "#clientDepartments option:selected" )
				.each( function(i,item ){
					deps.push( $(item).text() );
		});

		// Generate String of Departmetns Names separated with comma ","
		clientForm['dep_names'] = deps.toString();

		// Generate String of Departmetns IDs separated with comma ","
		if (clientForm['departmentsIds']) 
		{
			clientForm['dep_ids_str'] = clientForm['departmentsIds'].toString();
		}

		/*
		SECTORS CHECK
		*/
		
		// Get Selected Sectors Text and Put It in Array
		var secs=[];
		$( "#clientSectors option:selected" )
				.each( function(i,item ){
					secs.push( $(item).text() );
		});

		// Generate String of Sectors Names separated with comma ","
		clientForm['sec_names'] = secs.toString();

		// Generate String of Sectors IDs separated with comma ","
		if (clientForm['sectorsIds']) 
		{
			clientForm['sec_ids_str'] = clientForm['sectorsIds'].toString();
		}


		// Client Visibility Value Check
		if ( this.formish.clientIsVisible.is(':checked') ) {
			clientForm['is_visible'] = 1;
		}
		else{
			clientForm['is_visible'] = 0;
		}
		
		// this to self		
		var self = this;

		this.collection.create( new App.Models.Client(clientForm), 
				{
					wait:true,
					success: function(model,response){
						//Showing Success Message
						$.notify({ message : "<b>* "+model.get('fullName')+" *</b>"+" Created Successfully."},
								    { type : 'success',
								   z_index : 10000 });
						
						//empty tag values
						self.emptyCreateModal( self.formish );
						$('#clientDepartments')[0].sumo.unSelectAll();
						$('#clientSectors')[0].sumo.unSelectAll();
						/*$('#clientBtype')[0].sumo.unSelectAll();*/
						//Closing Modal
						$('#mdlCreateClient').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 
	

	}//#createClient
	

});



/*
|---------------------------------------------------------------------------------
|Edit Client View
|---------------------------------------------------------------------------------
*/
App.Views.EditClientModal = Backbone.View.extend({
	
	template: App.template('tmplClientEditModal'),

	events: {
		"click button#sbmtClientEdit": 'submitClientEdit'
	},
	initialize : function(){
		this.render();
	},

	render : function(){
		html = this.template( this.model.toJSON() )
		this.$el.html( html );
		return this;
	},

	// Edit Button Click On MODAL
	submitClientEdit : function(e){
		e.preventDefault();
		var save_values = {};
		console.log('update Submit');
		
		save_values['name'] 			= $( "#clientNameEdit").val();
 		save_values['abbr'] 			= $( "#clientAbbrEdit").val();
 		save_values['contact_person']	= $( "#clientContactEdit").val();
 		save_values['email'] 			= $( "#clientEmailEdit").val();
 		save_values['phone'] 			= $( "#clientPhoneEdit").val();
 		save_values['address'] 			= $( "#clientAddressEdit").val();
 		save_values['bank_acc']			= $( "#clientAccEdit").val();
 		/*save_values['btype'] 			= $( "#clientBtypeEdit option:selected").text();
 		save_values['btypeId'] 			= $( "#clientBtypeEdit option:selected").val();*/
 		save_values['reg_num']			= $( "#clientRegNumEdit").val();
 		save_values['tin'] 				= $( "#clientTinEdit").val();
 		save_values['departmentsIds'] 	= $( "#clientDepartmentsEdit").val();
 		//save_values['dep_ids'] 			= $( "#clientDepartmentsEdit").val();
		save_values['sectorsIds'] 	  	= $( "#clientSectorsEdit").val();
 		save_values['fullName']			= save_values['name'];

		var deps=[];
		$( "#clientDepartmentsEdit option:selected" ).each( function(i,item ){
								deps.push( $(item).text() );
		});
		//save_values['departments'] = deps.toString();
		save_values['dep_names']   = deps.toString();

		if (save_values['departmentsIds']) 
		{
			save_values['dep_ids_str'] = save_values['departmentsIds'].toString();
		}

		var secs=[];
		$( "#clientSectorsEdit option:selected" ).each( function(i,item ){
								secs.push( $(item).text() );
		});

		//save_values['sectors'] 	   = secs.toString();
		save_values['sec_names']   = secs.toString();

		if (save_values['sectorsIds']) 
		{
			save_values['sec_ids_str'] = save_values['sectorsIds'].toString();
		}

		// Client Visibility
		if ( $( "#clientIsVisibleEdit").is(':checked') ) 
		{
			save_values['is_visible'] = 1;
		}
		else{
			save_values['is_visible'] = 0;
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
					$("#mdlEditClient").modal('hide');
					
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
|Delete Client Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteClientModal = Backbone.View.extend({
	
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
	  //=====================//
	 //  DESTROY Client     //
	//=====================//
	confirmDelete : function(e){
		e.preventDefault();
	
		// Delete
		this.deleteClient();

		//Hiding Bootstrap Modal
		$("#mdlDeleteConfirm").modal('hide');

	},//#confirm
	
	deleteClient: function(){
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

	}
});//#DeleteClientModal