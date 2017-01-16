/*
|---------------------------------------------------------------------------------
|Initing Sectors Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initSec = Backbone.View.extend({
	
	//editModal: new App.Views.EditSec(),
	initialize : function(){
		
		vent.on('sector:edit', this.editSector, this);
		vent.on('sector:delete', this.deleteSector, this);

		var sectorCollection = new App.Collections.Sectors();
		// Fetch Values From Server
		sectorCollection.fetch({reset: true});
		// Initing Create Button 
		var createbutton = new App.Views.CreateSecModal({collection: sectorCollection});
		// Generating Actions Cell 
		actGenedCell = this.generateActions();
		// Generate and Show Grid		
		this.generateGrid( { secCollection: sectorCollection, actionsCell:actGenedCell } );
		// Generate and Show Pagination
		this.generatePaginator({ secCollection: sectorCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ secCollection: sectorCollection});
	},


	//Edit Sector Modal Render and Show Event Triggering
	//####################################################
	editSector: function(sector){
		//console.log('editing '+this.model.get('name'));
		var editSecModalView = new App.Views.EditSecModal({model: sector});
		//console.log(editSecModalView);
		$("#secModalDiv").html(editSecModalView.el);

		// Showing Bootstrap Modal
		$("#mdlEditSector").modal('toggle');
	},

	//Delete Sector Confirmation Event Triggering
	//#############################################
	deleteSector: function(sector){
		//console.log('vend deleting');
		//console.log('editing '+this.model.get('name'));
		var confirmDelSecModalView = new App.Views.DeleteSecModal({model: sector});
		//console.log(confirmDelSecModalView.el);
		//console.log(editSecModalView);
		$("#secDelConfModalDiv").html(confirmDelSecModalView.el);
		// Showing Bootstrap Modal
		$("#mdlDeleteConfirm").modal('toggle');
	},


	// Generate Grid
	//##############
	generateGrid : function(params){
			
		secGrid = new Backgrid.Grid({

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
					label: 'Sector',
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
			collection: params.secCollection
			
		});//#secGrid

		$('#gridSectors').append(secGrid.render().el);
		return secGrid;
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

				collection: params.secCollection
		});//#paginator
		
		// Render the paginator
		$('#gridSectors').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			  	collection: params.secCollection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Search Sector" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#gridSectors").before(serverSideFilter.render().el);
		$("#gridSectors").before($("#btnCreateSec"));
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
				'click .edit'	: 'editSecRow',
				'click .delete' : 'deleteSecRow'
			},
			
			editSecRow : function(e){
				e.preventDefault();
				vent.trigger('sector:edit', this.model);
			},
			
			deleteSecRow : function(e){
				e.preventDefault();
				vent.trigger('sector:delete', this.model);
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
|Create Busines Sector Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateSecModal = Backbone.View.extend({
	el: '#dCreateSector',
	events: {
		'click button#sbmtSecCreate': 'createSec'
	},

	initialize : function(){
		this.render();
	},

	createSec : function(e){
		e.preventDefault();

		this.createForm = {
							secname: $('#secName'),
						   	secnote: $('#secNote'),
							};

		var secModel = {name: this.createForm.secname.val(),
						note: this.createForm.secnote.val()};

		// Create Sector
		this.collection.create( new App.Models.Sector(secModel), 
				{
					wait:true,
					success: function(model,response){
						//Showing Success Message
						$.notify({ message : "New Busines Sector Created Successfully."},
								    { type : 'success',
								   z_index : 10000 });

						//empty tag values
						$('#secName').val('');
						$('#secNote').val('');
						//Closing Modal
						$('#mdlCreateSector').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 
	}//#createSec
});

/*
|---------------------------------------------------------------------------------
|Edit Sector View
|---------------------------------------------------------------------------------
*/
App.Views.EditSecModal = Backbone.View.extend({
	
	template: App.template('tmplSecEditModal'),

	events: {
		"click button#sbmtSecEdit": 'submitSectorEdit'
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
	submitSectorEdit : function(e){
		e.preventDefault();
		var save_values = {};

		save_values.name = this.$('#secNameEdit').val();
		save_values.note = this.$('#secNoteEdit').val();

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
					$("#mdlEditSector").modal('hide');
					
					//Clearing Values
					this.$('#secNameEdit').val('');
					this.$('#secNoteEdit').val('');
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
|Delete Sector Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteSecModal = Backbone.View.extend({
	
	template: App.template('tmplDeleteNote'),
	
	events: {
		"click button#confirmDelete": 'confirmDelete'
	},

	initialize : function(){
		this.render();
	},


	render : function(){
		//console.log('hello from modal view');
		html = this.template( this.model.toJSON() )
		this.$el.html( html );
		return this;
	},

	//  DESTROY Sector
	//=====================
	confirmDelete : function(e){
		e.preventDefault();

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