/*
|---------------------------------------------------------------------------------
|Initing AttrGroups Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initAttrGroup = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('attrgroup:edit', 	 this.editItem,   this);
		vent.on('attrgroup:delete', this.deleteItem, this);

		var attrgroupCollection = new App.Collections.AttrGroups();
		// Fetch Values From Server
		attrgroupCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateAttrGroupModal({collection: attrgroupCollection});

		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: attrgroupCollection});

		// Generate and Show Grid		
		this.generateGrid({ collection: attrgroupCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: attrgroupCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: attrgroupCollection});
	},


	//Edit AttrGroup Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		//appRouter.navigate("#attrgroups/edit");
		var editModalView = new App.Views.EditAttrGroupModal({model: rowmodel});
		
		$("#attrgroupModalDiv").html(editModalView.el);
		
		//tinymce.remove('#attrgroupDescriptionEdit');
		// Making AttrGroup Description an WYSYWYG Editor
		
		/*$('#attrgroupShowInFooterEdit').val(rowmodel.get('show_in_footer'));
		$('#attrgroupShowInMenuEdit').val(rowmodel.get('show_in_menu'));*/
		$('#attrgroupIsEnabledEdit').val(rowmodel.get('is_enabled'));
		//console.log(rowmodel.get('is_enabled'));

		//Setting Cursor to Name Field
		$('#mdlEditAttrGroup').on('shown.bs.modal', function () {
	    	$('#attrgroupNameEdit').focus();
		});
		
		// Showing Bootstrap Modal
		$("#mdlEditAttrGroup").modal('toggle');
	},

	//Delete AttrGroup Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){

		var confirmDelModalView = new App.Views.DeleteAttrGroupModal({model: rowmodel});

		$("#attrgroupDelConfModalDiv").html(confirmDelModalView.el);
	
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
				})}/*,{
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
				})}*/,{
					name: 'actions',
					label: '#',
					editable: false,
					sortable: false,
					cell: params.actionsCell
					
				}],

			//Data Collection For Table 
			collection: params.collection
			
		});//#attrgroupGrid
		
		$('#gridAttrGroups').append(grid.render().el);
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
		$('#gridAttrGroups').after(paginator.render().el);
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
		$("#gridAttrGroups").before(serverSideFilter.render().el);
		$("#gridAttrGroups").before($("#btnCreateAttrGroup"));
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
				vent.trigger('attrgroup:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('attrgroup:delete', this.model);
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
|Create AttrGroup Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateAttrGroupModal = Backbone.View.extend({
	el: '#dCreateAttrGroup',
	events: {
		'click button#sbmtAttrGroupCreate' : 'createAttrGroup',
		'keydown input#attrgroupName' 		: 'preventEnter'
	},

	formish: {},
	initialize : function(){

		this.render();
		this.initCreateModal();
	},

	initCreateModal : function(){
		this.formish.attrgroupName 		= $("#attrgroupName");
 		this.formish.attrgroupDescr 	= $("#attrgroupDescription");
 		/*this.formish.attrgroupShFr 		= $("#attrgroupShowInFooter");
 		this.formish.attrgroupShMn 		= $("#attrgroupShowInMenu");*/
 		this.formish.attrgroupIsEnabled	= $("#attrgroupIsEnabled");
	},

	emptyCreateModal : function(form){
		this.formish.attrgroupName.val(''); 
		this.formish.attrgroupDescr.val('');
		/*tinymce.activeEditor.setContent('');
		this.formish.attrgroupShFr.val('0').change();
		this.formish.attrgroupShMn.val('0').change();*/
		this.formish.attrgroupIsEnabled.val('0').change();
		//this.formish.attrgroupIsVisible.prop('checked',false);

		
	},
	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	createAttrGroup : function(e){
		e.preventDefault();
		 
		console.log('submit click');
		attrgroupForm = {};
		
		attrgroupForm['name']			 = $.trim(this.formish.attrgroupName.val());
		attrgroupForm['description']	 = $.trim(this.formish.attrgroupDescr.val());
		/*attrgroupForm['show_in_footer']	 = $.trim(this.formish.attrgroupShFr.val());
		attrgroupForm['show_in_menu']	 = $.trim(this.formish.attrgroupShMn.val());*/
		attrgroupForm['is_enabled']	 = $.trim(this.formish.attrgroupIsEnabled.val());
		
		// this to self		
		var self = this;

		this.collection.create( 
				new App.Models.AttrGroup( attrgroupForm ), 
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
						$('#mdlCreateAttrGroup').modal('hide');
					},
					error: function(model,response){
						// Showing Success Message
						console.log(response);
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 
	}//#createAttrGroup
});



/*
|---------------------------------------------------------------------------------
|Edit AttrGroup View
|---------------------------------------------------------------------------------
*/
App.Views.EditAttrGroupModal = Backbone.View.extend({
	
	template: App.template('tmplAttrGroupEditModal'),

	events: {
		"click button#sbmtAttrGroupEdit": 'submitAttrGroupEdit'
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
	submitAttrGroupEdit : function(e){
		e.preventDefault();
		var save_values = {};
		
		save_values['name'] 		  = $.trim($("#attrgroupNameEdit").val());
 		save_values['description']	  = $.trim($("#attrgroupDescriptionEdit").val());
 		/*save_values['show_in_footer'] = $("#attrgroupShowInFooterEdit").val();
		save_values['show_in_menu']	  = $("#attrgroupShowInMenuEdit").val();*/
		save_values['is_enabled']	  = $("#attrgroupIsEnabledEdit").val();
		 
		 
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
					$("#mdlEditAttrGroup").modal('hide');
					
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
|Delete AttrGroup Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteAttrGroupModal = Backbone.View.extend({
	
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

	//  DESTROY AttrGroup
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
	
});//#DeleteAttrGroupModal