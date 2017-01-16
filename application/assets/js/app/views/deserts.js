/*
|---------------------------------------------------------------------------------
|Initing Deserts Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initDesert = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('desert:edit', 	 this.editItem,   this);
		vent.on('desert:delete', this.deleteItem, this);

		var desertCollection = new App.Collections.Deserts();
		// Fetch Values From Server
		desertCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateDesertModal({collection: desertCollection});

		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: desertCollection});

		// Generate and Show Grid		
		this.generateGrid({ collection: desertCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: desertCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: desertCollection});
	},


	//Edit Desert Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		//appRouter.navigate("#deserts/edit");
		var editModalView = new App.Views.EditDesertModal({model: rowmodel});
		
		$("#desertModalDiv").html(editModalView.el);
		
		tinymce.remove('#desertDescriptionEdit');
		// Making Desert Description an WYSYWYG Editor
		tinymce.init({
						selector : '#desertDescriptionEdit',
						  plugin : 'a_tinymce_plugin',
		  		 a_plugin_option : true,
		  a_configuration_option : 400

		});

		$('#desertShowInFooterEdit').val(rowmodel.get('show_in_footer'));
		$('#desertShowInMenuEdit').val(rowmodel.get('show_in_menu'));
		$('#desertIsEnabledEdit').val(rowmodel.get('is_enabled'));
		console.log(rowmodel.get('is_enabled'));

		//Desertting Cursor to Name Field
		$('#mdlEditDesert').on('shown.bs.modal', function () {
	    	$('#desertNameEdit').focus();
		});
		
		// Showing Bootstrap Modal
		$("#mdlEditDesert").modal('toggle');
	},

	//Delete Desert Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){

		var confirmDelModalView = new App.Views.DeleteDesertModal({model: rowmodel});

		$("#desertDelConfModalDiv").html(confirmDelModalView.el);
	
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
				}/*,{
					name: 'description',
					label: 'Описание',
					editable: false,
					cell: 'string'
				}*/,{
					name: 'is_enabled',
					label: 'Включено',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var is_enabled = this.model.get('is_enabled');
							var text = '';
							if(is_enabled == 1){ 
								text='Да';
							} else {
								text='Нет';
							}
							
							this.$el.html( '<span>'+text+'</span>' );
							return this;
						},
				})},{
					name: 'show_in_menu',
					label: 'Показать В Меню',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var show_in_menu = this.model.get('show_in_menu');
							var text = '';
							if(show_in_menu == 1){ 
								text='Да';
							} else {
								text='Нет';
							}
							
							this.$el.html( '<span>'+text+'</span>' );
							return this;
						},
				})},{
					name: 'show_in_footer',
					label: 'Показать В Футере',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var show_in_footer = this.model.get('show_in_footer');
							var text = '';
							if(show_in_footer == 1){ 
								text='Да';
							} else {
								text='Нет';
							}
							
							this.$el.html( '<span>'+text+'</span>' );
							return this;
						},
				})},{
					name: 'actions',
					label: '#',
					editable: false,
					sortable: false,
					cell: params.actionsCell
					
				}],

			//Data Collection For Table 
			collection: params.collection
			
		});//#desertGrid
		
		$('#gridDeserts').append(grid.render().el);
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
		$('#gridDeserts').after(paginator.render().el);
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
		$("#gridDeserts").before(serverSideFilter.render().el);
		$("#gridDeserts").before($("#btnCreateDesert"));
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
				vent.trigger('desert:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('desert:delete', this.model);
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
|Create Desert Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateDesertModal = Backbone.View.extend({
	el: '#dCreateDesert',
	events: {
		'click button#sbmtDesertCreate' : 'createDesert',
		'keydown input#desertName' 		: 'preventEnter'
	},

	formish: {},
	initialize : function(){

		this.render();
		this.initCreateModal();
	},

	initCreateModal : function(){
		this.formish.desertName 		= $("#desertName");
 		this.formish.desertDescr 		= $("#desertDescription");
 		this.formish.desertShFr 		= $("#desertShowInFooter");
 		this.formish.desertShMn 		= $("#desertShowInMenu");
 		this.formish.desertIsEnabled	= $("#desertIsEnabled");
	},

	emptyCreateModal : function(form){
		this.formish.desertName.val(''); 
		this.formish.desertDescr.val('');
		tinymce.activeEditor.setContent('');
		this.formish.desertShFr.val('0').change();
		this.formish.desertShMn.val('0').change();
		this.formish.desertIsEnabled.val('0').change();
		//this.formish.desertIsVisible.prop('checked',false);

		
	},
	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	createDesert : function(e){
		e.preventDefault();
		 
		console.log('submit click');
		desertForm = {};
		
		desertForm['name']			 = $.trim(this.formish.desertName.val());
		desertForm['description']	 = tinymce.activeEditor.getContent(); //$.trim(this.formish.desertDescr.val());
		desertForm['show_in_footer']	 = $.trim(this.formish.desertShFr.val());
		desertForm['show_in_menu']	 = $.trim(this.formish.desertShMn.val());
		desertForm['is_enabled']	 = $.trim(this.formish.desertIsEnabled.val());
		
		// this to self		
		var self = this;

		this.collection.create( 
				new App.Models.Desert( desertForm ), 
				{
					wait:true,
					success: function(model,response){
						
						// Showing Success Message
						$.notify({ message : "<b>* "+model.get('name')+" *</b>"+" Created Successfully."},
								    { type : 'success',
								   z_index : 10000 });
						
						// Empty tag values
						self.emptyCreateModal( self.formish );

						// Closing Modal
						$('#mdlCreateDesert').modal('hide');
					},
					error: function(model,response){
						// Showing Success Message
						console.log(response);
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 
	}//#createDesert
});



/*
|---------------------------------------------------------------------------------
|Edit Desert View
|---------------------------------------------------------------------------------
*/
App.Views.EditDesertModal = Backbone.View.extend({
	
	template: App.template('tmplDesertEditModal'),

	events: {
		"click button#sbmtDesertEdit": 'submitDesertEdit'
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
	submitDesertEdit : function(e){
		e.preventDefault();
		var save_values = {};
		
		save_values['name'] 		 = $("#desertNameEdit").val();
 		save_values['description']	 = tinymce.activeEditor.getContent();
 		save_values['show_in_footer'] = $("#desertShowInFooterEdit").val();
		save_values['show_in_menu']	 = $("#desertShowInMenuEdit").val();
		save_values['is_enabled']	 = $("#desertIsEnabledEdit").val();
		 
		 
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
					$("#mdlEditDesert").modal('hide');
					
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
|Delete Desert Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteDesertModal = Backbone.View.extend({
	
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

	//  DESTROY Desert
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
	
});//#DeleteDesertModal