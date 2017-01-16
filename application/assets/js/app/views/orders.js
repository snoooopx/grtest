/*
|---------------------------------------------------------------------------------
|Initing Orders Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initOrders = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('order:edit', 	 this.editItem,   this);
		vent.on('order:delete', this.deleteItem, this);

		var orderCollection = new App.Collections.Orders();
		// Fetch Values From Server
		orderCollection.fetch();
		
		// Initing Create Button 
		//var createbutton = new App.Views.CreateOrderModal({collection: orderCollection});

		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: orderCollection});

		// Generate and Show Grid		
		this.generateGrid({ collection: orderCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: orderCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: orderCollection});
	},

	
	//Delete Order Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){

		var confirmDelModalView = new App.Views.DeleteOrderModal({model: rowmodel});

		$("#orderDelConfModalDiv").html(confirmDelModalView.el);
	
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
					name: 'order_id',
					label: 'Заказ Но.',
					editable: false,
					cell: 'string'
				},{
					name: 'created',
					label: 'Создан',
					editable: false,
					cell: 'string'
				},{
					name: 'o_status_id',
					label: 'Статус',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var o_status_id = this.model.get('o_status_id');

							//console.log(this.model);
							var myClass = '';
							if(o_status_id == 11){ 
								myClass = 'label label-warning';
							} else if (o_status_id == 10){ 
								myClass = 'label label-info';
							} else if (o_status_id == 12 || o_status_id == 14){ 
								myClass = 'label label-success';
							} else if (o_status_id == 13) { 
								myClass = 'label label-danger';
							} 
							this.$el.html( '<span class="'+myClass+'" >'+this.model.get('order_status')+'</span>' );
							return this;
						},
				})},{
					name: 'total',
					label: 'Цена',
					editable: false,
					cell: 'string'
				},{
					name: 'shp_type',
					label: 'Метод Досатавки',
					editable: false,
					cell: 'string'
				},{
					name: '',
					label: 'Адрес',
					editable: false,
					cell: Backgrid.Cell.extend({
						render: function(){
							var address = this.model.get('shp_city')+' '+
														this.model.get('shp_street')+' Дом '+
														this.model.get('shp_bld')+' Кв.'+
														this.model.get('shp_apt');
							this.$el.html(address);
							return this;
						}
				})},{
					name: 'shp_period',
					label: 'Период',
					editable: false,
					cell: 'string'
				},{
					name: 'pmt_name',
					label: 'Платёжка',
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
			
		});//#orderGrid
		
		$('#gridOrders').append(grid.render().el);
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
		$('#gridOrders').after(paginator.render().el);
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
		$("#gridOrders").before(serverSideFilter.render().el);
		$("#gridOrders").before($("#btnCreateOrder"));
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
				//e.preventDefault();
				vent.trigger('order:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('order:delete', this.model);
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

App.Views.initOrderDetails = Backbone.View.extend({
	initialize: function(){
		new App.Views.EditOrdersView();
	}
});

/*
|---------------------------------------------------------------------------------
|Edit Orders View
|---------------------------------------------------------------------------------
*/
App.Views.EditOrdersView = Backbone.View.extend({
	el: '#dOrders',
	
	events: {
// 		"submit form#frmSocials"	 : 'submitOrders',
// 		"submit form#frmOpening"	 : 'submitOrders',
// 		"submit form#frmCompanyInfo" : 'submitOrders',
		"submit form#frmOrderDetails"	 : 'submitOrderDetails'
		//"click  form#btnOrdHistory"		 : 'showHistoryModal'
		
	},

	initialize : function(){
		console.log('orders view loaded');

	},	
	
	// save Button Click On any 
	submitOrderDetails : function(e){
		e.preventDefault();

		//console.log($(e.currentTarget).serializeArray());
		//return;

		var save_values = {};
		if ( $(e.target).attr('id') == 'frmOrderDetails') {
				save_values.order_id				= $(e.target).find("[name='ordTbId']").val();
				save_values.status_id				= $(e.target).find("[name='ordStatus'] :selected").val();
				save_values.pmt_status_id		= $(e.target).find("[name='ordPmtStatus'] :selected").val();
		}
		//console.log(save_values);
		var ordersCollection = new App.Collections.OrdersActions();

		ordersCollection.create(
									save_values,
									{
										wait:true,
										success: function(model,response){
													console.log(response);
													$.notify({ message: response.message},
													    { type: 'success',
													   z_index: 10000}
													);
										},
										error: function(model,response){
													console.log(response);
													$.notify({ message: response.responseJSON.message},
													    { type: 'danger',
													   z_index: 10000}
													);
										}
									}
		);
	}
});




/*
|---------------------------------------------------------------------------------
|Delete Order Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteOrderModal = Backbone.View.extend({
	
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

	//  DESTROY Order
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
	
});//#DeleteOrderModal