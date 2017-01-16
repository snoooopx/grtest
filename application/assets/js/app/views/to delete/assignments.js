/*
|---------------------------------------------------------------------------------
|Initing Assignments Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initAssignment = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('assignment:edit', this.editItem, this);
		vent.on('assignment:delete', this.deleteItem, this);

		var assignmentCollection = new App.Collections.Assignments();
		// Fetch Values From Server
		assignmentCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateAssignmentModal({collection: assignmentCollection});
		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: assignmentCollection});
		// Generate and Show Grid		
		this.generateGrid({ collection: assignmentCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: assignmentCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: assignmentCollection});
	},


	//Edit Assignment Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		//appRouter.navigate("#assignments/edit");
		var editModalView = new App.Views.EditAssignmentModal({model: rowmodel});
		
		$("#assignmentModalDiv").html(editModalView.el);
		
		$('#assignmentDepartmentsEdit').SumoSelect({
			okCancelInMulti: true,
			search:true
		});

		$('#assignmentOperationsEdit').SumoSelect({
			okCancelInMulti: true,
			search:true
		});

		//Setting Cursor to Name Field
		$('#mdlEditAssignment').on('shown.bs.modal', function () {
	    	$('#assignmentNameEdit').focus();
		});
		
		// Check And Fill Departments SumoSelect
		if (rowmodel.get('dep_ids_str')) {
			var entireDepIds = rowmodel.get('dep_ids_str').split(",");
			
			$.each( entireDepIds, function(idx,item){
				$('#assignmentDepartmentsEdit')[0].sumo.selectItem($.trim(item));
			});
		}
		console.log(rowmodel);
		// Check And Fill Operations SumoSelect
		if (rowmodel.get('oper_ids_str')) {
			var entireOperIds = rowmodel.get('oper_ids_str').split(",");
			
			$.each( entireOperIds, function(idx,item){
				$('#assignmentOperationsEdit')[0].sumo.selectItem($.trim(item));
			});
		}

		// Showing Bootstrap Modal
		$("#mdlEditAssignment").modal('toggle');
	},

	//Delete Assignment Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){
		//console.log('vend deleting');
		//console.log('editing '+this.model.get('name'));
		var confirmDelModalView = new App.Views.DeleteAssignmentModal({model: rowmodel});
		//console.log(confirmDelModalView.el);

		$("#assignmentDelConfModalDiv").html(confirmDelModalView.el);
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
					name: 'name',
					label: 'Assignment',
					editable: false,
					cell: 'string'
				},{
					name: 'oper_names',
					label: 'Operations',
					editable: false,
					cell: 'string'
				},{
					name: 'dep_names',
					label: 'Departments',
					editable: false,
					cell: 'string'
				},{
					name: 'description',
					label: 'Description',
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
			
		});//#assignmentGrid
		
		$('#gridAssignments').append(grid.render().el);
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
		$('#gridAssignments').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			  	collection: params.collection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Search Assignment" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#gridAssignments").before(serverSideFilter.render().el);
		$("#gridAssignments").before($("#btnCreateAssignment"));
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
				'click .edit'    : 'editRow',
				'click .delete'  : 'deleteRow'
			},
			
			editRow : function(e){
				e.preventDefault();
				vent.trigger('assignment:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('assignment:delete', this.model);
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
|Create Assignment Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateAssignmentModal = Backbone.View.extend({
	el: '#dCreateAssignment',
	events: {
		'click button#sbmtAssignmentCreate': 'createAssignment',
		'keydown input#assignmentName': 'preventEnter'
	},

	formish: {},
	initialize : function(){
		// init Departments Sumoselect			
		$('#assignmentDepartments').SumoSelect({
			okCancelInMulti: true,
			search:true
		});
		// init Operations Sumoselect
		$('#assignmentOperations').SumoSelect({
			okCancelInMulti: true,
			search:true
		});
		
		this.render();
		this.initCreateModal();
	},

	initCreateModal : function(){
		this.formish.assignmentName 			= $( "#assignmentName");
 		this.formish.assignmentDescr 			= $( "#assignmentDescription");
 		this.formish.assignmentIsVisible 		= $( "#assignmentIsVisible");
 		//console.log(this.formish);
	},

	emptyCreateModal : function(form){
		this.formish.assignmentName.val(''); 
		this.formish.assignmentDescr.val(''); 
		this.formish.assignmentIsVisible.prop('checked',false);
		$('#assignmentDepartments')[0].sumo.unSelectAll();
		$('#assignmentOperations')[0].sumo.unSelectAll();
	},
	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	createAssignment : function(e){
		e.preventDefault();
		 
		console.log('create');
		assignmentForm = {};
		
		assignmentForm['name']			 = this.formish.assignmentName.val(); 		
		assignmentForm['description']	 = this.formish.assignmentDescr.val(); 		
		assignmentForm['is_visible']	 = this.formish.assignmentIsVisible 	
		assignmentForm['departmentsIds'] = $( "#assignmentDepartments").val();
		assignmentForm['operationsIds'] = $( "#assignmentOperations").val();

		/*
		DEPARTMENTS CHECK
		*/

		// Get Selected Departmets Text and Put It in Array
		var deps=[];
		$( "#assignmentDepartments option:selected" )
				.each( function(i,item ){
					deps.push( $(item).text() );
		});

		// Generate String of Departmetns Names separated with comma ","
		assignmentForm['dep_names'] = deps.toString();

		// Generate String of Departmetns IDs separated with comma ","
		if (assignmentForm['departmentsIds']) 
		{
			assignmentForm['dep_ids_str'] = assignmentForm['departmentsIds'].toString();
		}

		/*
		OPERATIONS CHECK
		*/

		// Get Selected Operations Text and Put It in Array
		var opers=[];
		$( "#assignmentOperations option:selected" )
				.each( function(i,item ){
					opers.push( $(item).text() );
		});

		// Generate String of Operations Names separated with comma ","
		assignmentForm['oper_names'] = opers.toString();

		// Generate String of Operations IDs separated with comma ","
		if (assignmentForm['operationsIds']) 
		{
			assignmentForm['oper_ids_str'] = assignmentForm['operationsIds'].toString();
		}


		// Assignment Visibility Value Check
		if ( this.formish.assignmentIsVisible.is(':checked') ) {
			assignmentForm['is_visible'] = 1;
		}
		else{
			assignmentForm['is_visible'] = 0;
		}
		
		// this to self		
		var self = this;

		this.collection.create( new App.Models.Assignment(assignmentForm), 
				{
					wait:true,
					success: function(model,response){
						//Showing Success Message
						$.notify({ message : "<b>* "+model.get('name')+" *</b>"+" Created Successfully."},
								    { type : 'success',
								   z_index : 10000 });
						
						//empty tag values
						self.emptyCreateModal( self.formish );

						//Closing Modal
						$('#mdlCreateAssignment').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 
	

	}//#createAssignment
	

});



/*
|---------------------------------------------------------------------------------
|Edit Assignment View
|---------------------------------------------------------------------------------
*/
App.Views.EditAssignmentModal = Backbone.View.extend({
	
	template: App.template('tmplAssignmentEditModal'),

	events: {
		"click button#sbmtAssignmentEdit": 'submitAssignmentEdit'
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
	submitAssignmentEdit : function(e){
		e.preventDefault();
		var save_values = {};
		console.log('update Submit');
		
		save_values['name'] 			= $( "#assignmentNameEdit").val();
 		save_values['description'] 		= $( "#assignmentDescriptionEdit").val();
 		save_values['departmentsIds'] 	= $( "#assignmentDepartmentsEdit").val();
 		save_values['operationsIds'] 	= $( "#assignmentOperationsEdit").val();

		var deps=[];
		$( "#assignmentDepartmentsEdit option:selected" ).each( function(i,item ){
								deps.push( $(item).text() );
		});
		//save_values['departments'] = deps.toString();
		save_values['dep_names']   = deps.toString();

		if (save_values['departmentsIds']) 
		{
			save_values['dep_ids_str'] = save_values['departmentsIds'].toString();
		}

		var opers=[];
		$( "#assignmentOperationsEdit option:selected" ).each( function(i,item ){
								opers.push( $(item).text() );
		});

		save_values['oper_names']   = opers.toString();

		if (save_values['operationsIds']) 
		{
			save_values['oper_ids_str'] = save_values['operationsIds'].toString();
		}

		// Assignment Visibility
		if ( $( "#assignmentIsVisibleEdit").is(':checked') ) 
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
					$("#mdlEditAssignment").modal('hide');
					
				},//#success
				error: function(model,response){
					//Showing Success Message
					console.log(response);
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
|Delete Assignment Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteAssignmentModal = Backbone.View.extend({
	
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

	//  DESTROY Assignment
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
	
});//#DeleteAssignmentModal