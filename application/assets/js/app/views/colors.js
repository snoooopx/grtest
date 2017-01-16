/*
|---------------------------------------------------------------------------------
|Initing Colors Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initColor = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('color:edit', 	 this.editItem,   this);
		vent.on('color:delete', this.deleteItem, this);

		var colorCollection = new App.Collections.Colors();
		// Fetch Values From Server
		colorCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateColorModal({collection: colorCollection});

		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: colorCollection});

		// Generate and Show Grid		
		this.generateGrid({ collection: colorCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: colorCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: colorCollection});
	},


	//Edit Color Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		//appRouter.navigate("#colors/edit");
		var editModalView = new App.Views.EditColorModal({model: rowmodel});
		
		$("#colorModalDiv").html(editModalView.el);
		
		//Setting Cursor to Name Field
		$('#mdlEditColor').on('shown.bs.modal', function () {
	    	$('#colorNameEdit').focus();
		});
		
		// Showing Bootstrap Modal
		$("#mdlEditColor").modal('toggle');

		// Making Hex field ColorPicker
		$('#colorHexEdit').colorpicker();
	},

	//Delete Color Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){

		var confirmDelModalView = new App.Views.DeleteColorModal({model: rowmodel});

		$("#colorDelConfModalDiv").html(confirmDelModalView.el);
	
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
					name: 'hex',
					label: 'Цвет | Код',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var hex_code = this.model.get('hex');
							
							this.$el.html( '<span style="color:'+hex_code+';"><i class="fa fa-square fa-2x" aria-hidden="true"></i></span> <span>&nbsp '+hex_code+'</span>' );
							return this;
						},
				})
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
			
		});//#colorGrid
		
		$('#gridColors').append(grid.render().el);
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
		$('#gridColors').after(paginator.render().el);
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
		$("#gridColors").before(serverSideFilter.render().el);
		$("#gridColors").before($("#btnCreateColor"));
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
				vent.trigger('color:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('color:delete', this.model);
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
|Create Color Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateColorModal = Backbone.View.extend({
	el: '#dCreateColor',
	events: {
		'click button#sbmtColorCreate' : 'createColor',
		'keydown input#colorName' 		: 'preventEnter'
	},

	formish: {},
	initialize : function(){

		this.render();
		this.initCreateModal();
	},

	initCreateModal : function(){
		this.formish.colorName 	= $("#colorName");
		this.formish.colorHex 	= $("#colorHex input");
 		this.formish.colorDescr = $("#colorNote");
	},

	emptyCreateModal : function(form){
		this.formish.colorName.val(''); 
		this.formish.colorDescr.val(''); 
		this.formish.colorHex.val(''); 
		//this.formish.colorIsVisible.prop('checked',false);
		
	},
	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	createColor : function(e){
		e.preventDefault();
		 
		console.log('submit click');
		colorForm = {};
		
		colorForm['name']			 = $.trim(this.formish.colorName.val());
		colorForm['hex']			 = $.trim(this.formish.colorHex.val());
		colorForm['description']	 = $.trim(this.formish.colorDescr.val());
		
		// this to self		
		var self = this;

		this.collection.create( 
				new App.Models.Color( colorForm ), 
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
						$('#mdlCreateColor').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						console.log(response);
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 
	}//#createColor
});



/*
|---------------------------------------------------------------------------------
|Edit Color View
|---------------------------------------------------------------------------------
*/
App.Views.EditColorModal = Backbone.View.extend({
	
	template: App.template('tmplColorEditModal'),

	events: {
		"click button#sbmtColorEdit": 'submitColorEdit'
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
	submitColorEdit : function(e){
		e.preventDefault();
		var save_values = {};
		
		save_values['name'] 		= $("#colorNameEdit").val();
		save_values['hex'] 			= $("#colorHexEdit input").val();
 		save_values['description'] 	= $("#colorNoteEdit").val();

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
					$("#mdlEditColor").modal('hide');
					
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
|Delete Color Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteColorModal = Backbone.View.extend({
	
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

	//  DESTROY Color
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
	
});//#DeleteColorModal