/*
|---------------------------------------------------------------------------------
|Initing Job Titles Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initPos = Backbone.View.extend({
	
	//editModal: new App.Views.EditPos(),
	initialize : function(){
		
		vent.on('position:edit', this.editPosition, this);
		vent.on('position:delete', this.deletePosition, this);

		var positionCollection = new App.Collections.Positions();
		// Fetch Values From Server
		positionCollection.fetch({reset: true});
		// Initing Create Button 
		var createbutton = new App.Views.CreatePosModal({collection: positionCollection});
		// Generating Actions Cell 
		actGenedCell = this.generateActions();
		// Generate and Show Grid		
		this.generateGrid( { posCollection: positionCollection, actionsCell:actGenedCell } );
		// Generate and Show Pagination
		this.generatePaginator({ posCollection: positionCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ posCollection: positionCollection});
	},


	//Edit Position Modal Render and Show Event Triggering
	//####################################################
	editPosition: function(position){
		//console.log('editing '+this.model.get('name'));
		var editPosModalView = new App.Views.EditPosModal({model: position});
		//console.log(editPosModalView);
		$("#posModalDiv").html(editPosModalView.el);

		$("#posDepEdit option[value=\""+position.get('depId')+"\"]").prop('selected',true);
		// Showing Bootstrap Modal
		$("#mdlEditPosition").modal('toggle');
	},

	//Delete Position Confirmation Event Triggering
	//#############################################
	deletePosition: function(position){
		console.log('vend deleting');
		//console.log('editing '+this.model.get('name'));
		var confirmDelPosModalView = new App.Views.DeletePosModal({model: position});
		console.log(confirmDelPosModalView.el);
		//console.log(editPosModalView);
		$("#posDelConfModalDiv").html(confirmDelPosModalView.el);
		// Showing Bootstrap Modal
		$("#mdlDeleteConfirm").modal('toggle');
	},


	// Generate Grid
	//##############
	generateGrid : function(params){
			
		posGrid = new Backgrid.Grid({

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
					label: 'Job Title',
					editable: false,
					cell: 'string'
					
				},{
					name: 'department',
					label: 'Department',
					editable: false,
					cell: 'string'
					
				},{
					name: 'note',
					label: 'Note',
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
			collection: params.posCollection
			
		});//#posGrid

		$('#gridPositions').append(posGrid.render().el);
		return posGrid;
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

				collection: params.posCollection
		});//#paginator
		
		// Render the paginator
		$('#gridPositions').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			  	collection: params.posCollection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Search Job Title" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#gridPositions").before(serverSideFilter.render().el);
		$("#gridPositions").before($("#btnCreatePos"));
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
				'click .edit': 'editPosRow',
				'click .delete': 'deletePosRow'
			},
			
			//editPosModalView: params.editModal,

			editPosRow : function(e){
				e.preventDefault();
				//console.log('editing again');
				vent.trigger('position:edit', this.model);
			},
			deletePosRow : function(e){
				e.preventDefault();
				//console.log('deleting');
				vent.trigger('position:delete', this.model);
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
|Create Job Title Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreatePosModal = Backbone.View.extend({
	el: '#dCreatePosition',
	events: {
		'click button#sbmt_pos_create': 'createPos'
	},

	initialize : function(){
		this.render();
	},

	createPos : function(e){
		e.preventDefault();

		this.createForm = {posname: $('#name'),
						   posnote: $('#note'),
							posdep: $('#posDep option:selected')};

		if (this.createForm.posdep.val() == '') {
			this.createForm.posdepText = "";
		}
		else{
			this.createForm.posdepText = this.createForm.posdep.text();
		}
		var posModel = {name: this.createForm.posname.val(),
						depId: this.createForm.posdep.val(),
						department: this.createForm.posdepText,
						note: this.createForm.posnote.val()};
		console.log(this.createForm.posdepText);
		console.log(this.createForm.posdep.text());
		this.collection.create( new App.Models.PositionModel(posModel), 
				{
					wait:true,
					success: function(model,response){
						//Showing Success Message
						$.notify({ message : "New job Title Created Successfully."},
								    { type : 'success',
								   z_index : 10000 });

						//empty tag values
						$('#name').val('');
						$('#note').val('');
						$('select option:disabled').prop("selected",true);
						//Closing Modal
						$('#mdlCreatePosition').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 
	}//#createPos
});



/*
|---------------------------------------------------------------------------------
|Edit Position View
|---------------------------------------------------------------------------------
*/
App.Views.EditPosModal = Backbone.View.extend({
	
	template: App.template('tmplPosEditModal'),

	events: {
		"click button#sbmt_pos_edit": 'submitPositionEdit'
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
	submitPositionEdit : function(e){
		e.preventDefault();
		var save_values = {};

		save_values.name = this.$('#name').val();
		save_values.depId = this.$('#posDepEdit option:selected').val();
		save_values.department = this.$('#posDepEdit option:selected').text();
		save_values.note = this.$('#note').val();

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
					$("#mdlEditPosition").modal('hide');
					
					//Clearing Values
					this.$('#name').val('');
					this.$('#note').val('');
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
App.Views.DeletePosModal = Backbone.View.extend({
	
	template: App.template('tmplDeleteNote'),
	
	events: {
		"click button#confirmDelete": 'confirmDelete'
	},

	initialize : function(){
		this.render();
	},

	//
	render : function(){
		//console.log('hello from modal view');
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
				$.notify({ message: model.attributes.name +" "+ response.message},
					     { type: 'success',
					 	z_index: 10000}
				);
			},//success
			error: function(model,response){
						//console.log(response);
						//Showing Success Message
						$.notify({ message: response.responseJSON.message},
								    { type: 'danger',
								   z_index: 10000}
						);
			}//error
		});
		//Hiddin Bootstrap Modal
		$("#mdlDeleteConfirm").modal('hide');

	}//#confirm
	
});



