/*
|---------------------------------------------------------------------------------
|Initing Coupons Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initCoupon = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('coupon:edit', 	 this.editItem,   this);
		vent.on('coupon:delete', this.deleteItem, this);

		var couponCollection = new App.Collections.Coupons();
		// Fetch Values From Server
		couponCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateCouponModal({collection: couponCollection});

		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: couponCollection});

		// Generate and Show Grid		
		this.generateGrid({ collection: couponCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: couponCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: couponCollection});
	},

	//Edit Coupon Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		console.log(rowmodel);
		//appRouter.navigate("#coupons/edit");
		var editModalView = new App.Views.EditCouponModal({model: rowmodel});
		
		$("#couponModalDiv").html(editModalView.el);
		edit_modal = $("#mdlCouponActions"+"_"+rowmodel.get('id'));
		//Setting Cursor to Name Field
		edit_modal.on('shown.bs.modal', function () {
	    	$('#couponCode').focus();
		});
		
		edit_modal.find("#couponType option[value="+rowmodel.get('type')+"]").prop('selected',true);
		edit_modal.find("#couponIsEnabled option[value="+rowmodel.get('is_enabled') +"]").prop('selected',true);

		// Showing Bootstrap Modal
		edit_modal.modal('toggle');
	},
	
	//Delete Coupon Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){

		var confirmDelModalView = new App.Views.DeleteCouponModal({model: rowmodel});

		$("#couponDelConfModalDiv").html(confirmDelModalView.el);
	
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
					name: 'code',
					label: 'Код',
					editable: false,
					cell: 'string'
				},{
					name: 'type',
					label: 'Тип',
					editable: false,
					cell: 'string'
				},{
					name: 'discount',
					label: 'Дисконт',
					editable: false,
					cell: 'string'
				},{
					name: 'start_date',
					label: 'Начало',
					editable: false,
					cell: 'string'
				},{
					name: 'end_date',
					label: 'Конец',
					editable: false,
					cell: 'string'
				},{
					name: 'is_enabled',
					label: 'Активно',
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
					name: 'actions',
					label: '#',
					editable: false,
					sortable: false,
					cell: params.actionsCell
					
				}],

			//Data Collection For Table 
			collection: params.collection
			
		});//#couponGrid
		
		$('#gridCoupons').append(grid.render().el);
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
		$('#gridCoupons').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			  	collection: params.collection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Найти Купон" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#gridCoupons").before(serverSideFilter.render().el);
		$("#gridCoupons").before($("#btnCreateCoupon"));
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
				vent.trigger('coupon:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('coupon:delete', this.model);
			},

			render : function(){
				
				this.$el.append( this.template(this.model));
				this.delegateEvents();
				return this;
			}
		});//#actionsCell

		return actionsCell;
	}//#generateActions
});


/*
|---------------------------------------------------------------------------------
|Create Coupon Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateCouponModal = Backbone.View.extend({
	el: '#dCreateCoupon',
	template: App.template('tmplCouponModal'),


	events: {
		'submit #couponSubmit' 	   : 'createCoupon',
		'keydown input#couponCode' : 'preventEnter'
	},

	formish: {},
	initialize : function(){
		this.render();
		this.initCreateModal();

	},

	initCreateModal : function(){
		this.render();
		this.formish.couponCode				=$("#couponCode");
		this.formish.couponDescr			=$("#couponDescription");
		this.formish.couponType				=$("#couponType");
		this.formish.couponDiscount			=$("#couponDiscount");
		this.formish.couponStartDate		=$("#couponStartDate");
		this.formish.couponEndDate			=$("#couponEndDate");
		this.formish.couponIsEnabled		=$("#couponIsEnabled");
		
	},

	render: function(){
		this.$el.html(this.template());
		return this;
	},

	emptyCreateModal : function(form){

		this.formish.couponCode.val('');
		this.formish.couponDescr.val('');
		this.formish.couponType.val('fix').trigger('change');
		this.formish.couponDiscount.val('');
		this.formish.couponStartDate.val('');
		this.formish.couponEndDate.val('');
		this.formish.couponIsEnabled.val('0').trigger('change');
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
		config.element 		 = 'couponFeaturedImg';
		config.imageTag 	 = 'couponFeaturedImgImg';
		config.sizeLimit 	 = 1024000;
		config.itemLimit 	 = 1;

		App.makeFineUploader(config);
	},


	createCoupon : function(e){
		e.preventDefault();
		 
		console.log('create');
		
		couponForm = {};
		
		couponForm['code'] 		 		 = this.formish.couponCode.val();
		couponForm['description'] 	 	 = this.formish.couponDescr.val();
		couponForm['type']		 		 = this.formish.couponType.find(':selected').val();
		couponForm['type_name']  		 = this.formish.couponType.find(':selected').text();
		couponForm['start_date'] 	 	 = this.formish.couponStartDate.val();
		couponForm['end_date'] 	 		 = this.formish.couponEndDate.val();
		couponForm['discount'] 	 		 = this.formish.couponDiscount.val();
		couponForm['is_enabled'] 		 = this.formish.couponIsEnabled.find(':selected').val();
		
		// this to self		
		var self = this;

		this.collection.create( new App.Models.Coupon(couponForm), 
				{
					wait:true,
					success: function(model,response){
						//Showing Success Message
						$.notify({ message : "<b>* "+model.get('code')+" *</b>"+" Created Successfully."},
								    { type : 'success',
								   z_index : 10000 });
						
						//empty tag values
						self.emptyCreateModal( self.formish );
												
						//Closing Modal
						$('#mdlCouponActions').modal('hide');
					},
					error: function(model,response){
						//Showing error Message
						//console.log(response);
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 

	}//#createCoupon

});

/*
|---------------------------------------------------------------------------------
|Edit Coupon View
|---------------------------------------------------------------------------------
*/
App.Views.EditCouponModal = Backbone.View.extend({
	
	template: App.template('tmplCouponModal'),

	events: {
		"submit #couponSubmit": 'submitCoupon'
	},
	initialize : function(){
		this.render();
	},

	render : function(){
		console.log(this.model.toJSON());
		html = this.template( this.model.toJSON() )
		this.$el.html( html );
		return this;
	},

	// Edit Button Click On MODAL
	submitCoupon : function(e){
		e.preventDefault();
		var save_values = {};
		var edit_modal = $('#mdlCouponActions'+'_'+this.model.get('id'));
		save_values['code'] 		 	 = edit_modal.find("#couponCode").val();
		save_values['description'] 	 	 = edit_modal.find("#couponDescription").val();
		save_values['type']		 		 = edit_modal.find("#couponType").find(':selected').val();
		save_values['type_name']  		 = edit_modal.find("#couponType").find(':selected').text();
		save_values['start_date'] 	 	 = edit_modal.find("#couponStartDate").val();
		save_values['end_date'] 	 	 = edit_modal.find("#couponEndDate").val();
		save_values['discount'] 	 	 = edit_modal.find("#couponDiscount").val();
		save_values['is_enabled'] 		 = edit_modal.find("#couponIsEnabled").find(':selected').val();

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
					edit_modal.modal('hide');
					
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
|Delete Coupon Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteCouponModal = Backbone.View.extend({
	
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

	//  DESTROY Coupon
	//=====================
	confirmDelete : function(e){
		e.preventDefault();
		
		// Destroying Selected Model
		this.model.destroy( { 
			wait:true,	
			success: function( model, response){
				//Showing Success Message
				$.notify({ message: model.get('code') +" "+ response.message},
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
	
});//#DeleteCouponModal