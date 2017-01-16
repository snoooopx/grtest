/*
|---------------------------------------------------------------------------------
|Initing Attributes Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initAttribute = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('attribute:details', this.detailsItem, this);
		vent.on('attribute:edit', this.editItem, this);
		vent.on('attribute:delete', this.deleteItem, this);

		var attributeCollection = new App.Collections.Attributes();
		// Fetch Values From Server
		attributeCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateAttributeModal({collection: attributeCollection});
		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: attributeCollection});
		// Generate and Show Grid		
		this.generateGrid({ collection: attributeCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: attributeCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: attributeCollection});
	},


	//Attribute Details Page Redirect
	//############################
	detailsItem : function(rowmodel){
		//appRouter.navigate("attributeview/"+rowmodel.get('id'));
		//window.location.href(App.myroot + '/attributedetails' + rowmodel.get('id'));
		//console.log("attributedetails/"+rowmodel.get('id'));
		//Bacbone.history.navogate("attributedetails/"+rowmodel.get('id'));
	},

	//Edit Attribute Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		//appRouter.navigate("#attributes/edit");
		var editModalView = new App.Views.EditAttributeModal({model: rowmodel});
		
		$("#attributeModalDiv").html(editModalView.el);
		//Setting Cursor to Name Field
		$('#mdlEditAttribute').on('shown.bs.modal', function () {
	    	$('#attributeNameEdit').focus();
		});
		
		$("#attributeGroupEdit option[value="+rowmodel.get('attrgroup_id')+"]").prop('selected',true);
		$("#attributeMMTEdit option[value="+rowmodel.get('mmt_id') +"]").prop('selected',true);
		$("#attributeAllowUsertextEdit option[value="+rowmodel.get('allow_user_text') +"]").prop('selected',true);
		$("#attributeIsActiveEdit option[value="+rowmodel.get('is_active') +"]").prop('selected',true);

		// Showing Bootstrap Modal
		$("#mdlEditAttribute").modal('toggle');
	},

	//Delete Attribute Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){
		//console.log('vend deleting');
		//console.log('editing '+this.model.get('name'));
		var confirmDelModalView = new App.Views.DeleteAttributeModal({model: rowmodel});
		//console.log(confirmDelModalView.el);

		$("#attributeDelConfModalDiv").html(confirmDelModalView.el);
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
					name: 'attrgroup_name',
					label: 'Тип Аттрибута',
					editable: false,
					cell: 'string'
				},{
					name: 'price',
					label: 'Цена',
					editable: false,
					cell: 'string'
				},{
					name: 'sign',
					label: '$',
					editable: false,
					cell: 'string'
				},{
					name: 'allow_user_text',
					label: 'Текст Пользователя',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var allow_user_text = this.model.get('allow_user_text');
							var text = '';
							if(allow_user_text == 1){ 
								text='Да';
							} else {
								text='Нет';
							}
							
							this.$el.html( '<span>'+text+'</span>' );
							return this;
						},
				})},{
					name: 'is_active',
					label: 'Активно',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var is_active = this.model.get('is_active');
							var text = '';
							if(is_active == 1){ 
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
			
		});//#attributeGrid
		
		$('#gridAttributes').append(grid.render().el);
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
		$('#gridAttributes').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			  	collection: params.collection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Search Attribute" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#gridAttributes").before(serverSideFilter.render().el);
		$("#gridAttributes").before($("#btnCreateAttribute"));
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
				vent.trigger('attribute:details', this.model);
			},

			editRow : function(e){
				e.preventDefault();
				vent.trigger('attribute:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('attribute:delete', this.model);
			},

			render : function(){
				this.$el.append( this.template(this.model));
				//console.log(this.model);
				this.delegateEvents();
				return this;
			}
		});//#actionsCell

		return actionsCell;
	}//#generateActions
});



/*
|---------------------------------------------------------------------------------
|Create Attribute Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateAttributeModal = Backbone.View.extend({
	el: '#dCreateAttribute',


	events: {
		'click button#sbmtAttributeCreate': 'createAttribute',
		'keydown input#attributeName': 'preventEnter'
	},

	formish: {},
	initialize : function(){
		this.render();
		this.initCreateModal();
		this.uploadishe();

	},

	initCreateModal : function(){

		this.formish.attributeName				=$("#attributeName");
		this.formish.attributeDescr				=$("#attributeDescription");
		this.formish.attributeFeaturedImg		=$("#attributeFeaturedImg");
		this.formish.attributeFeaturedImgImg	=$("#attributeFeaturedImgImg");
		this.formish.attributeGroup				=$("#attributeGroup");
		this.formish.attributePrice				=$("#attributePrice");
		this.formish.attributeMMT				=$("#attributeMMT");
		this.formish.attributeAllowUserText		=$("#attributeAllowUserText");
		this.formish.attributeIsActive			=$("#attributeIsActive");
		
	},

	emptyCreateModal : function(form){

		this.formish.attributeName.val('');
		this.formish.attributeDescr.val('');
		this.formish.attributeGroup.val('');
		this.formish.attributePrice.val('');
		//this.formish.attributeMMT.val('');
		//this.formish.attributeFeaturedImg.empty();
		this.formish.attributeFeaturedImgImg.attr('data-hash','');
		this.formish.attributeFeaturedImgImg.attr('data-filename','');
		this.formish.attributeFeaturedImgImg.attr('src',this.formish.attributeFeaturedImgImg.attr('data-dir')
											+'/img/gallery/'
											+this.formish.attributeFeaturedImgImg.attr('data-defimgdef'));	
		this.formish.attributeAllowUserText.val('0');
		this.formish.attributeIsActive.val('0');
		//$('.qq-upload-list-selector').empty();
		this.uploadishe();
	},

	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	/*
	Image Upload Function
	jslint unparam: true, regexp: true */
	/*global window, $ */
	uploadishe: function (){
		var config = {};
		config.element 		 = 'attributeFeaturedImg';
		config.imageTag 	 = 'attributeFeaturedImgImg';
		config.sizeLimit 	 = 1024000;
		config.itemLimit 	 = 1;

		App.makeFineUploader(config);
	},


	createAttribute : function(e){
		e.preventDefault();
		 
		console.log('create');
		
		attributeForm = {};
		
		attributeForm['name'] 		 		 = this.formish.attributeName.val();
		attributeForm['description'] 	 	 = this.formish.attributeDescr.val();
		attributeForm['attrgroup_id'] 		 = this.formish.attributeGroup.find(':selected').val();
		attributeForm['attrgroup_name']  	 = this.formish.attributeGroup.find(':selected').text();
		attributeForm['price']	 	 		 = this.formish.attributePrice.val();
		attributeForm['mmt_id'] 		 	 = this.formish.attributeMMT.find(':selected').val();
		attributeForm['sign']		 		 = this.formish.attributeMMT.find(':selected').text();
		attributeForm['featured_image_hash'] = this.formish.attributeFeaturedImgImg.attr('data-hash');
		attributeForm['featured_image_name'] = this.formish.attributeFeaturedImgImg.attr('data-filename');
		attributeForm['allow_user_text']	 = this.formish.attributeAllowUserText.val();
		attributeForm['is_active'] 			 = this.formish.attributeIsActive.find(':selected').val();

		
		if (attributeForm['price'] == '') {
			attributeForm['price'] = 0;
		}

		// Check and set featured name
		if ( attributeForm['featured_image_name'] !== '' && attributeForm['featured_image_name'] !== undefined) {
			attributeForm['featured_image'] = attributeForm['featured_image_hash'] + '.' + attributeForm['featured_image_name'].split('.').pop();
		}else{
			attributeForm['featured_image'] = this.formish.attributeFeaturedImgImg.attr('data-defimgdef');
		}
		// this to self		
		var self = this;

		this.collection.create( new App.Models.Attribute(attributeForm), 
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
						$('#mdlCreateAttribute').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						//console.log(response);
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 

	}//#createAttribute

});



/*
|---------------------------------------------------------------------------------
|Edit Attribute View
|---------------------------------------------------------------------------------
*/
App.Views.EditAttributeModal = Backbone.View.extend({
	
	template: App.template('tmplAttributeEditModal'),

	events: {
		"click button#sbmtAttributeEdit": 'submitAttributeEdit'
	},
	initialize : function(){
		this.render();
		var config = {};
		
		config.element 		 = 'attributeFeaturedImgEdit';
		//config.hiddenElement = 'attributeFeaturedImgImgEdit';
		config.imageTag 	 = 'attributeFeaturedImgImgEdit';
		config.sizeLimit 	 = 1024000;
		config.itemLimit 	 = 1;
		App.makeFineUploader(config);
	  
	},

	render : function(){
		html = this.template( this.model.toJSON() )
		this.$el.html( html );
		return this;
	},

	// Edit Button Click On MODAL
	submitAttributeEdit : function(e){
		e.preventDefault();
		var save_values = {};
		//console.log('update Submit');
		save_values['name'] 		 		 =$("#attributeNameEdit").val();
		save_values['description'] 	 	 	 =$("#attributeDescriptionEdit").val();
		save_values['attrgroup_id'] 		 =$("#attributeGroupEdit").find(':selected').val();
		save_values['attrgroup_name']  	 	 =$("#attributeGroupEdit").find(':selected').text();
		save_values['price']	 	 		 =$("#attributePriceEdit").val();
		save_values['mmt_id'] 		 	 	 =$("#attributeMMTEdit").find(':selected').val();
		save_values['sign']		 			 =$("#attributeMMTEdit").find(':selected').text();
		save_values['featured_image_hash']   =$("#attributeFeaturedImgImgEdit").attr('data-hash');
		save_values['featured_image_name']   =$("#attributeFeaturedImgImgEdit").attr('data-filename');
		save_values['allow_user_text']		 =$("#attributeAllowUserTextEdit").val();
		save_values['is_active'] 			 =$("#attributeIsActiveEdit").val();


		// Check and set featured name
		if ( save_values['featured_image_name'] !== '' && save_values['featured_image_name'] !== undefined) {
			save_values['featured_image'] = save_values['featured_image_hash'] + '.' + save_values['featured_image_name'].split('.').pop();
		}else{
			save_values['featured_image'] = $("#attributeFeaturedImgImgEdit").attr('data-defimgdef');
		}

		//Saving Edited Values
		this.model.save(
			save_values,
			{
				wait:true,
				success: function(model,response){
					//console.log(response);
					
					//Showing Success Message
					$.notify({ message: response.message},
							    { type: 'success',
							   z_index: 10000}
							);
					
					//Hiddin Bootstrap Modal
					$("#mdlEditAttribute").modal('hide');
					
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
|Delete Attribute Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteAttributeModal = Backbone.View.extend({
	
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

	//  DESTROY Attribute
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
	
});//#DeleteAttributeModal