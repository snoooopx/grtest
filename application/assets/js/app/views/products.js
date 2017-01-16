/*
|---------------------------------------------------------------------------------
|Initing Products Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initProduct = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('product:details', this.detailsItem, this);
		vent.on('product:edit', this.editItem, this);
		vent.on('product:delete', this.deleteItem, this);

		var productCollection = new App.Collections.Products();
		// Fetch Values From Server
		productCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateProductModal({collection: productCollection});
		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: productCollection});
		// Generate and Show Grid		
		this.generateGrid({ collection: productCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: productCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: productCollection});
	},


	//Product Details Page Redirect
	//############################
	detailsItem : function(rowmodel){
		//appRouter.navigate("productview/"+rowmodel.get('id'));
		//window.location.href(App.myroot + '/productdetails' + rowmodel.get('id'));
		//console.log("productdetails/"+rowmodel.get('id'));
		//Bacbone.history.navogate("productdetails/"+rowmodel.get('id'));
	},

	//Edit Product Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		//appRouter.navigate("#products/edit");
		var editModalView = new App.Views.EditProductModal({model: rowmodel});
		
		$("#productModalDiv").html(editModalView.el);
		//Setting Cursor to Name Field
		$('#mdlEditProduct').on('shown.bs.modal', function () {
	    	$('#productNameEdit').focus();
		});
		
		// Remove Old MCE
		tinymce.remove("#productDescriptionEdit");
		
		// Init New MCE
		tinymce.init({
						selector : '#productDescriptionEdit',
						  plugin : 'a_tinymce_plugin',
		  		 a_plugin_option : true,
		  a_configuration_option : 400

				});

		// Set Content
		tinymce.activeEditor.setContent(rowmodel.get('description'));
		
		$("#productDesertEdit option[value="+rowmodel.get('desert_id')+"]").prop('selected',true);
		$("#productFlavorEdit option[value="+rowmodel.get('flavor_id')+"]").prop('selected',true);
		$("#productColorEdit  option[value="+rowmodel.get('color_id') +"]").prop('selected',true);
		$("#productMMTEdit  option[value="+rowmodel.get('mmt_id') +"]").prop('selected',true);
		$("#productUseInSetEdit  option[value="+rowmodel.get('use_in_set') +"]").prop('selected',true);
		$("#productShowInGalleryEdit  option[value="+rowmodel.get('show_in_gallery') +"]").prop('selected',true);
		$("#productIsActiveEdit  option[value="+rowmodel.get('is_active') +"]").prop('selected',true);

		// Showing Bootstrap Modal
		$("#mdlEditProduct").modal('toggle');
	},

	//Delete Product Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){
		//console.log('vend deleting');
		//console.log('editing '+this.model.get('name'));
		var confirmDelModalView = new App.Views.DeleteProductModal({model: rowmodel});
		//console.log(confirmDelModalView.el);

		$("#productDelConfModalDiv").html(confirmDelModalView.el);
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
					name: 'desert_type',
					label: 'Тип Десерта',
					editable: false,
					cell: 'string'
				},{
					name: 'flavor_name',
					label: 'Вкус',
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
			
		});//#productGrid
		
		$('#gridProducts').append(grid.render().el);
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
		$('#gridProducts').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			  	collection: params.collection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Search Product" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#gridProducts").before(serverSideFilter.render().el);
		$("#gridProducts").before($("#btnCreateProduct"));
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
				vent.trigger('product:details', this.model);
			},

			editRow : function(e){
				e.preventDefault();
				vent.trigger('product:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('product:delete', this.model);
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
|Create Product Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateProductModal = Backbone.View.extend({
	el: '#dCreateProduct',


	events: {
		'click button#sbmtProductCreate': 'createProduct',
		'keydown input#productName': 'preventEnter'
	},

	formish: {},
	initialize : function(){
		this.render();
		this.initCreateModal();
		this.uploadishe();

	},

	initCreateModal : function(){

		this.formish.productName			=$("#productName");
		this.formish.productSKU				=$("#productSKU");
		
		// tinymce-ov ****this.formish.productDescription		=$("#productDescription");
		
		this.formish.productAvatarBBImg		=$("#productAvatarBBImg");
		this.formish.productFeaturedImgImg	=$("#productFeaturedImgImg");
		this.formish.productDesert			=$("#productDesert");
		this.formish.productFlavor			=$("#productFlavor");
		this.formish.productColor			=$("#productColor");
		this.formish.productWeight			=$("#productWeight");
		this.formish.productPrice			=$("#productPrice");
		this.formish.productMMT				=$("#productMMT");
		this.formish.productUseInSet		=$("#productUseInSet");
		this.formish.productShowInGallery	=$("#productShowInGallery");
		this.formish.productIsActive		=$("#productIsActive");

	},

	emptyCreateModal : function(form){

		this.formish.productName.val('');
		this.formish.productSKU.val('');
		tinymce.activeEditor.setContent('');
		this.formish.productDesert.val('');
		this.formish.productFlavor.val('');
		this.formish.productColor.val('');
		this.formish.productWeight.val('');
		this.formish.productPrice.val('');
		//this.formish.productMMT.val('');
		this.formish.productAvatarBBImg.attr('data-hash','');
		this.formish.productAvatarBBImg.attr('data-filename','');
		this.formish.productAvatarBBImg.attr('src',this.formish.productAvatarBBImg.attr('data-dir')
													+'/img/gallery/'
													+this.formish.productAvatarBBImg.attr('data-defimgdef'));
		this.formish.productFeaturedImgImg.attr('data-hash','');
		this.formish.productFeaturedImgImg.attr('data-filename','');
		this.formish.productFeaturedImgImg.attr('src',this.formish.productFeaturedImgImg.attr('data-dir')
													+'/img/gallery/'
													+this.formish.productFeaturedImgImg.attr('data-defimgdef'));
		this.formish.productUseInSet.val('0');
		this.formish.productShowInGallery.val('0');
		this.formish.productIsActive.val('0');
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
		config.element 		 = 'productAvatarBB';
		//config.hiddenElement = 'productAvatarBBImg';
		config.imageTag 	 = 'productAvatarBBImg';
		config.sizeLimit 	 = 1024000;
		config.itemLimit 	 = 1;

		App.makeFineUploader(config);

		//var config = {};
		config.element 		 = 'productFeaturedImg';
		//config.hiddenElement = 'productFeaturedImgImg';
		config.imageTag 	 = 'productFeaturedImgImg';
		config.sizeLimit 	 = 1024000;
		config.itemLimit 	 = 1;

		App.makeFineUploader(config);
	},


	createProduct : function(e){
		e.preventDefault();
		 
		console.log('create');
		
		productForm = {};
		
		productForm['name'] 		 	 = this.formish.productName.val();
		productForm['sku'] 			 	 = this.formish.productSKU.val();
		productForm['description'] 	 	 = tinymce.activeEditor.getContent();
		productForm['desert_id'] 	 	 = this.formish.productDesert.find(':selected').val();
		productForm['desert_type'] 	 	 = this.formish.productDesert.find(':selected').text();
		productForm['flavor_id'] 	 	 = this.formish.productFlavor.find(':selected').val();
		productForm['flavor_name'] 	 	 = this.formish.productFlavor.find(':selected').text();
		productForm['color_id'] 	 	 = this.formish.productColor.find(':selected').val();
		productForm['color_name'] 	 	 = this.formish.productColor.find(':selected').text();
		productForm['mmt_id'] 		 	 = this.formish.productMMT.find(':selected').val();
		productForm['sign']		 		 = this.formish.productMMT.find(':selected').text();
		productForm['weight'] 		 	 = this.formish.productWeight.val();
		productForm['price'] 	 		 = this.formish.productPrice.val();
		productForm['avatar_hash'] 		 = this.formish.productAvatarBBImg.attr('data-hash');
		productForm['avatar_name'] 		 = this.formish.productAvatarBBImg.attr('data-filename');

		productForm['featured_image_hash'] = this.formish.productFeaturedImgImg.attr('data-hash');
		productForm['featured_image_name'] = this.formish.productFeaturedImgImg.attr('data-filename');
		productForm['use_in_set'] 		 = this.formish.productUseInSet.val();
		productForm['show_in_gallery']   = this.formish.productShowInGallery.val();
		productForm['is_active'] 		 = this.formish.productIsActive.val();

		// Check and set avatar name
		if ( productForm['avatar_name'] !== '' && productForm['avatar_name'] !== undefined) {
			productForm['avatar'] = productForm['avatar_hash'] + '.' + productForm['avatar_name'].split('.').pop();
		}else{
			productForm['avatar'] = this.formish.productAvatarBBImg.attr('data-defimgdef');
		}

		// Check and set featured name
		if ( productForm['featured_image_name'] !== '' && productForm['featured_image_name'] !== undefined) {
			productForm['featured_image'] = productForm['featured_image_hash'] + '.' + productForm['featured_image_name'].split('.').pop();
		}else{
			productForm['featured_image'] = this.formish.productFeaturedImgImg.attr('data-defimgdef');
		}
		// this to self		
		var self = this;

		this.collection.create( new App.Models.Product(productForm), 
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
						$('#mdlCreateProduct').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						//console.log(response);
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 

	}//#createProduct

});



/*
|---------------------------------------------------------------------------------
|Edit Product View
|---------------------------------------------------------------------------------
*/
App.Views.EditProductModal = Backbone.View.extend({
	
	template: App.template('tmplProductEditModal'),

	events: {
		"click button#sbmtProductEdit": 'submitProductEdit'
	},
	initialize : function(){
		this.render();
		var config = {};
		
		config.element 		 = 'productAvatarBBEdit';
		//config.hiddenElement = 'productAvatarBBImgEdit';
		config.imageTag 	 = 'productAvatarBBImgEdit';
		config.sizeLimit 	 = 1024000;
		config.itemLimit 	 = 1;
		App.makeFineUploader(config);

		config.element 		 = 'productFeaturedImgEdit';
		//config.hiddenElement = 'productFeaturedImgImgEdit';
		config.imageTag 	 = 'productFeaturedImgImgEdit';
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
	submitProductEdit : function(e){
		e.preventDefault();
		var save_values = {};
		//console.log('update Submit');
		
		save_values['name'] 		 	 	= $.trim($("#productNameEdit").val());
		save_values['sku'] 			 	 	= $.trim($("#productSKUEdit").val());
		save_values['description'] 	 	 	= tinymce.activeEditor.getContent();
		save_values['desert_id'] 	 	 	= $("#productDesertEdit").find(':selected').val();
		save_values['desert_type'] 	 	 	= $("#productDesertEdit").find(':selected').text();
		save_values['flavor_id'] 	 	 	= $("#productFlavorEdit").find(':selected').val();
		save_values['flavor_name'] 	 	 	= $("#productFlavorEdit").find(':selected').text();
		save_values['color_id'] 	 	 	= $("#productColorEdit").find(':selected').val();
		save_values['color_name'] 	 	 	= $("#productColorEdit").find(':selected').text();
		save_values['mmt_id'] 		 	 	= $("#productMMTEdit").find(':selected').val();
		save_values['sign']		 	 		= $("#productMMTEdit").find(':selected').text();
		save_values['weight'] 		 	 	= $.trim($("#productWeightEdit").val());
		save_values['price'] 	 		 	= $.trim($("#productPriceEdit").val());
		save_values['avatar_hash'] 		 	= $("#productAvatarBBImgEdit").attr('data-hash');
		save_values['avatar_name'] 		 	= $("#productAvatarBBImgEdit").attr('data-filename');
		save_values['featured_image_hash'] 	= $("#productFeaturedImgImgEdit").attr('data-hash');
		save_values['featured_image_name'] 	= $("#productFeaturedImgImgEdit").attr('data-filename');
		save_values['use_in_set'] 		 	= $("#productUseInSetEdit").val();
		save_values['show_in_gallery']   	= $("#productShowInGalleryEdit").val();
		save_values['is_active'] 		 	= $("#productIsActiveEdit").val();

		// Check and set avatar name
		if ( save_values['avatar_name'] !== '' && save_values['avatar_name'] !== undefined) {
			save_values['avatar'] = save_values['avatar_hash'] + '.' + save_values['avatar_name'].split('.').pop();
		}else{
			save_values['avatar'] = $("#productAvatarBBImgEdit").attr('data-defimgdef');
		}

		// Check and set featured name
		if ( save_values['featured_image_name'] !== '' && save_values['featured_image_name'] !== undefined) {
			save_values['featured_image'] = save_values['featured_image_hash'] + '.' + save_values['featured_image_name'].split('.').pop();
		}else{
			save_values['featured_image'] = $("#productFeaturedImgImgEdit").attr('data-defimgdef');
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
					$("#mdlEditProduct").modal('hide');
					
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
|Delete Product Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteProductModal = Backbone.View.extend({
	
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

	//  DESTROY Product
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
	
});//#DeleteProductModal