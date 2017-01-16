/*
|---------------------------------------------------------------------------------
|Initing Flavors Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initFlavor = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('flavor:edit', 	 this.editItem,   this);
		vent.on('flavor:delete', this.deleteItem, this);

		var flavorCollection = new App.Collections.Flavors();
		// Fetch Values From Server
		flavorCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateFlavorModal({collection: flavorCollection});

		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: flavorCollection});

		// Generate and Show Grid		
		this.generateGrid({ collection: flavorCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: flavorCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: flavorCollection});
	},


	//Edit Flavor Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		//appRouter.navigate("#flavors/edit");
		var editModalView = new App.Views.EditFlavorModal({model: rowmodel});
		
		$("#flavorModalDiv").html(editModalView.el);
		
		//Setting Cursor to Name Field
		$('#mdlEditFlavor').on('shown.bs.modal', function () {
	    	$('#flavorNameEdit').focus();
		});
		
		// Showing Bootstrap Modal
		$("#mdlEditFlavor").modal('toggle');
	},

	//Delete Flavor Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){

		var confirmDelModalView = new App.Views.DeleteFlavorModal({model: rowmodel});

		$("#flavorDelConfModalDiv").html(confirmDelModalView.el);
	
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
			columns:[{
					name: 'name',
					label: 'Название',
					editable: false,
					cell: 'string'
				},{
					name: 'description',
					label: 'Описание',
					editable: false,
					cell: 'string'
				},{
					name: 'actions',
					label: '#',
					editable: false,
					sortable: false,
					cell: params.actionsCell
					
				}],

			//Data Collection For Table 
			collection: params.collection
			
		});//#flavorGrid
		
		$('#gridFlavors').append(grid.render().el);
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
		$('#gridFlavors').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			  	collection: params.collection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Найти Вкус" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#gridFlavors").before(serverSideFilter.render().el);
		$("#gridFlavors").before($("#btnCreateFlavor"));
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
				vent.trigger('flavor:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('flavor:delete', this.model);
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
|Create Flavor Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateFlavorModal = Backbone.View.extend({
	el: '#dCreateFlavor',
	events: {
		'click button#sbmtFlavorCreate' : 'createFlavor',
		'keydown input#flavorName' 		: 'preventEnter'
	},

	formish: {},
	initialize : function(){

		this.render();
		this.initCreateModal();
	},

	initCreateModal : function(){
		this.formish.flavorName 	= $("#flavorName");
 		this.formish.flavorDescr 	= $("#flavorNote");
	},

	emptyCreateModal : function(form){
		this.formish.flavorName.val(''); 
		this.formish.flavorDescr.val(''); 
		//this.formish.flavorIsVisible.prop('checked',false);
		
	},
	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	createFlavor : function(e){
		e.preventDefault();
		 
		console.log('submit click');
		flavorForm = {};
		
		flavorForm['name']			 = $.trim(this.formish.flavorName.val());
		flavorForm['description']	 = $.trim(this.formish.flavorDescr.val());
		
		// this to self		
		var self = this;

		this.collection.create( 
				new App.Models.Flavor( flavorForm ), 
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
						$('#mdlCreateFlavor').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						console.log(response);
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 
	}//#createFlavor
});



/*
|---------------------------------------------------------------------------------
|Edit Flavor View
|---------------------------------------------------------------------------------
*/
App.Views.EditFlavorModal = Backbone.View.extend({
	
	template: App.template('tmplFlavorEditModal'),

	events: {
		"click button#sbmtFlavorEdit": 'submitFlavorEdit'
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
	submitFlavorEdit : function(e){
		e.preventDefault();
		var save_values = {};
		
		save_values['name'] 			= $( "#flavorNameEdit").val();
 		save_values['description'] 		= $( "#flavorNoteEdit").val();

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
					$("#mdlEditFlavor").modal('hide');
					
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
|Delete Flavor Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteFlavorModal = Backbone.View.extend({
	
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

	//  DESTROY Flavor
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
	
});//#DeleteFlavorModal