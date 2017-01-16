/*
|---------------------------------------------------------------------------------
|Initing Departments Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initDep = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('department:edit', this.editItem, this);
		vent.on('department:delete', this.deleteItem, this);

		var departmentCollection = new App.Collections.Departments();
		// Fetch Values From Server
		departmentCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateDepModal({collection: departmentCollection});
		// Generating Actions Cell 
		actGenedCell = this.generateActions();
		// Generate and Show Grid		
		this.generateGrid({ collection: departmentCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: departmentCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: departmentCollection});
	},


	//Edit Department Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){

		var editModalView = new App.Views.EditDepModal({model: rowmodel});
		
		console.log(rowmodel);
		
		var entireBoss = rowmodel.get('depHeadId');
		
		console.log(entireBoss);

		$("#depModalDiv").html(editModalView.el);
		$("#depHeadId option[value=\""+entireBoss+"\"]").prop('selected',true);
		// Showing Bootstrap Modal
		$("#mdlEditDepartment").modal('toggle');
	},

	//Delete Department Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){
		console.log('vend deleting');
		//console.log('editing '+this.model.get('name'));
		var confirmDelModalView = new App.Views.DeleteDepModal({model: rowmodel});
		console.log(confirmDelModalView.el);

		$("#depDelConfModalDiv").html(confirmDelModalView.el);
		// Showing Bootstrap Modal
		$("#mdlDeleteConfirm").modal('toggle');
	},


	// Generate Grid
	//##############
	generateGrid : function(params){
			
		grid = new Backgrid.Grid({

			className: 'table table-hover',

			// Initing Table Columns
			columns:[/*{
					name: 'id',
					label: 'ID',
					editable: false,
					cell: Backgrid.IntegerCell.extend({
		      		orderSeparator: ''
					})},*/{
					name: 'depName',
					label: 'Department',
					editable: false,
					cell: 'string'
					
				},{
					name: 'head',
					label: 'Head',
					editable: false,
					cell: 'string'
					
				},{
					name: 'company',
					label: 'Company',
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
			
		});//#depGrid
		console.log(params.collection);
		$('#gridDepartments').append(grid.render().el);
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
		$('#gridDepartments').after(paginator.render().el);
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
		$("#gridDepartments").before(serverSideFilter.render().el);
		$("#gridDepartments").before($("#btnCreateDep"));
		// Add some space to the filter and move it to the right
		$(serverSideFilter.el).css({float: "right", margin: "20px"});

		return serverSideFilter;
	},//#generateFilter


	//Generate Actions Buttons
	//########################
	generateActions: function(){
		
		//Actions button Generation
		var actionsCell = Backgrid.Cell.extend({
			template: _.template($("#action_buttons").html()),
			events:{
				'click .edit': 'editRow',
				'click .delete': 'deleteRow'
			},
			
			editRow : function(e){
				e.preventDefault();
				vent.trigger('department:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('department:delete', this.model);
			},

			render : function(){
				this.$el.append( this.template());
				this.delegateEvents();
				return this;
			}
		});//#actionsCell

		return actionsCell;
	}//#generateActions
});



/*
|---------------------------------------------------------------------------------
|Create Departments Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateDepModal = Backbone.View.extend({
	el: '#dCreateDepartment',
	events: {
		'click button#sbmtDepCreate': 'createDep',
		'keydown input#depName': 'preventEnter'
	},

	initialize : function(){
		this.render();
	},

	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	createDep : function(e){
		e.preventDefault();
		 
		console.log('create');
		this.createForm = {	  depName : $('#depName'),
							depHeadId : $('#depHeadId option:selected'),
						 depCompanyId : $('#depCompanyId option:selected')};

		var depModel = {	 depName : this.createForm.depName.val(),
						   depHeadId : this.createForm.depHeadId.val(),
						depCompanyId : this.createForm.depCompanyId.val(),
								head : $.trim(this.createForm.depHeadId.text()),
							 company : $.trim(this.createForm.depCompanyId.text())};
		
		this.collection.create( new App.Models.Department(depModel), 
				{
					wait:true,
					success: function(model,response){
						//Showing Success Message
						$.notify({ message : "<b>* "+model.get('depName')+" *</b>"+" Created Successfully."},
								    { type : 'success',
								   z_index : 10000 });

						//empty tag values
						//this.createForm.depName.val('');
						$('#depName').val('');
						$('#depHeadId').val('');
						//this.createForm.depHeadId.val('');
						//this.createForm.depCompanyId.val('');
						
						//Closing Modal
						$('#mdlCreateDepartment').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 
	}//#createDep
});



/*
|---------------------------------------------------------------------------------
|Edit Department View
|---------------------------------------------------------------------------------
*/
App.Views.EditDepModal = Backbone.View.extend({
	
	template: App.template('tmplDepEditModal'),

	events: {
		"click button#sbmtDepEdit": 'submitDepartmentEdit'
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
	submitDepartmentEdit : function(e){
		e.preventDefault();
		var save_values = {};

		save_values.depName 	 = this.$('#depName').val();
		save_values.depHeadId 	 = this.$('#depHeadId option:selected').val();
		save_values.head 		 = $.trim(this.$('#depHeadId option:selected').text());
		save_values.depCompanyId = this.$('#depCompanyId option:selected').val();
		save_values.company 	 = $.trim(this.$('#depCompanyId option:selected').text());

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
					$("#mdlEditDepartment").modal('hide');
					
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
|Delete Job Title Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteDepModal = Backbone.View.extend({
	
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

	//  DESTROY JOB TITLE
	//=====================
	confirmDelete : function(e){
		e.preventDefault();
		console.log('confirm');
		// Destroying Selected Model
		this.model.destroy( { 
			wait:true,	
			success: function( model, response){
				//Showing Success Message
				$.notify({ message: model.attributes.depName +" "+ response.message},
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
	
});



