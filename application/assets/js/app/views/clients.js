/*
|---------------------------------------------------------------------------------
|Initing Clients Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initClient = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('client:edit', 	 this.editItem,   this);
		vent.on('client:delete', this.deleteItem, this);

		var clientCollection = new App.Collections.Clients();
		// Fetch Values From Server
		clientCollection.fetch();
		
		// Initing Create Button 
		//var createbutton = new App.Views.CreateClientModal({collection: clientCollection});

		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: clientCollection});

		// Generate and Show Grid		
		this.generateGrid({ collection: clientCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: clientCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: clientCollection});
	},

	
	//Delete Client Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){

		var confirmDelModalView = new App.Views.DeleteClientModal({model: rowmodel});

		$("#clientDelConfModalDiv").html(confirmDelModalView.el);
	
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
					name: 'Имя',
					label: 'Название',
					editable: false,
					cell: 'string'
				},{
					name: 'sname',
					label: 'Фамилия',
					editable: false,
					cell: 'string'
				},{
					name: 'email',
					label: 'Почта',
					editable: false,
					cell: 'string'
				},{
					name: 'phone',
					label: 'Телефон',
					editable: false,
					cell: 'string'
				},{
					name: 'login_enabled',
					label: 'Доступ разрешён',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var login_enabled = this.model.get('login_enabled');
							var text = '';
							if(login_enabled == 1){ 
								text='Да';
							} else {
								text='Нет';
							}
							
							this.$el.html( '<span>'+text+'</span>' );
							return this;
						},
				})},{
					name: 'is_activated',
					label: 'Активирован',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var is_activated = this.model.get('is_activated');
							var text = '';
							if(is_activated == 1){ 
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
			
		});//#clientGrid
		
		$('#gridClients').append(grid.render().el);
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
		$('#gridClients').after(paginator.render().el);
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
		$("#gridClients").before(serverSideFilter.render().el);
		$("#gridClients").before($("#btnCreateClient"));
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
				vent.trigger('client:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('client:delete', this.model);
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


App.Views.initClientActionsPage = Backbone.View.extend({

	initialize: function(params){
		
		var createclientpage = new App.Views.ClientActionsPage({ action: params.action, client_id: params.client_id });
		//console.log(App.template('tmplClientFields'));
	}
});


/*
|---------------------------------------------------------------------------------
|Create Client Page
|---------------------------------------------------------------------------------
*/
App.Views.ClientActionsPage = Backbone.View.extend({
	el: '.box',

	template: App.template('tmplClientFields'),

	events: {
		/*'click button#sbmtClient' 		: 'submitClient',*/
		'submit #clientSubmit' 		: 'submitClient',
		'keydown input#clientName' 	: 'preventEnter',
		'change #clientItemList' 		: 'clientItemSelectChange',
		'change #clientAttrList' 		: 'clientAttributeSelectChange',
		/*'change #clientCount' 			: 'clientCountChange',*/
		/*'change #clientType' 			: 'clientTypeChange',*/
		'click .removeClientItem' 		: 'removeClientItem',
		'click .removeClientAttr' 		: 'removeClientAttr',
		'change input#itemQtyInClient' : 'itemQtyChange'
	},

	formish: {},

	initialize : function(params){
		var self = this;
		this.initProcess = false;
		if (params.action=='e') {
			this.initProcess = true;
			this.globalAction = 'e';
			var editClientCollection = new App.Collections.EditClient();
			editClientCollection
			.fetch({
						data:{ 		
								action : params.action,
									id : params.client_id
							},
				 })
			.then( function(response){
				
				if ( response.status == 'failure' ) 
				{
					$.notify({ message : response.message},
							    	{ type : 'danger',
								   z_index : 10001 });
					return;
				}
				self.render(response);
				
				self.initCreatePage();				
				
				$("#clientType").val(response.info.type).trigger("change");
				$("#clientCount").val(response.info.defined_count).trigger("change");
				$("#clientIsEnabled").val(response.info.is_enabled).trigger("change");
			});

			this.clientModelInfo = new App.Models.ClientTmplInfo();
			this.clientModelInfo.client('client_id',params.client_id);
			//console.log(this.clientModelInfo);
		}
		else if(params.action == 'c')
		{
			this.clientModelInfo 		= new App.Models.ClientTmplInfo();
			this.globalAction = 'c';
			this.render();
			this.initCreatePage();
		} 
		else 
		{

			return;
		}
		this.clientCollectionItems = new App.Collections.ClientActionTmplItem;
		this.clientCollectionAttrs = new App.Collections.ClientActionTmplAttribute;
	},

	initCreatePage : function(){

		this.formish.clientName			=$("#clientName");
		this.formish.clientSKU				=$("#clientSKU");
		this.formish.clientFeaturedImgImg	=$("#clientFeaturedImgImg");
		this.formish.clientType			=$("#clientType");
		this.formish.clientCount			=$("#clientCount");
		this.formish.clientPrice			=$("#clientPrice");
		this.formish.clientMMT				=$("#clientMMT");
		this.formish.clientIsEnabled		=$("#clientIsEnabled");
		this.formish.clientItemsTbl		=$("#"+this.clientItemsTable);
		this.formish.clientAttrsTbl		=$("#"+this.clientAttributesTable);
	},


	clientItemsTable: 'tblClientItems',
	clientAttributesTable: 'tblClientAttributes',

	removeClientItem: function(e) {
		// Get tr element
		var closest_tr = $(e.currentTarget).closest('tr');
		// Remove Table Row
		closest_tr.remove();
		// Renumerate
		this.renumerateTable(this.clientItemsTable);
	},

	removeClientAttr: function(e) {
		// Get tr element
		var closest_tr = $(e.currentTarget).closest('tr');
		// Remove Table Row
		closest_tr.remove();
		// Renumerate
		this.renumerateTable(this.clientAttributesTable);
	},

	clientAttributeSelectChange: function(e){
		var selectElementId = $(e.target).attr('id');
		
		var data = $('#'+selectElementId).select2('data')[0];

		//Check for duplicates
		if (!this.duplicateCheck(this.clientAttributesTable, data.id)) {
			$.notify({message:"Этот Аттрибут уже в таблице.."},
					    { type : 'warning',
					   z_index : 10000 });	
			return;
		}

		//Add New item into table
		this.addAttrRow(this.clientAttributesTable,data);
		// Add Item to Collection
		this.clientCollectionAttrs.add( {id:data.id} );
		//console.log(this.clientCollectionAttrs);
		this.renumerateTable(this.clientAttributesTable);
	},


	clientItemSelectChange: function(e) {
		//console.log('clientItemSelectChange');
		var selectElementId = $(e.target).attr('id');
		var data = $('#'+selectElementId).select2('data')[0];
		
		if (data === undefined) {
			return;
		}
		//Check type
		var clientType = $.trim($("#clientType").val());
		//Get clientItem Count
		var clientCount = $.trim($("#clientCount option:selected").val());

		//Check for duplicates
		if (!this.duplicateCheck(this.clientItemsTable, data.id)) {
			$.notify({message:"Этот десерт уже в таблице.."},
					    { type : 'warning',
					   z_index : 10000 });	
			return;
		}

		// Add New Item into table
		this.addItemRow(this.clientItemsTable,data);
		
		// Renumerate
		this.renumerateTable(this.clientItemsTable);
	},

	itemQtyChange: function(e){
		var res = this.validateItemQtyValue($(e.target).val());
		if (res==false) {
			$(e.target).val('1');
		}
	},

	validateItemQtyValue: function(value){
		if (!value.toString().match(/^\d*$/)) {
			return false;
		}

	},

	validateClientItemsQty: function(){
		//Get Table Items Qty
		var qty = this.getTableItemsQty();
		//Get clientCount
		var clientCount = this.formish.clientCount.val();

		var clientType = this.formish.clientType.val();

		if (clientType == 'static')
		{
			if (clientCount)
			{
				if (qty)
				{
					if (qty!=clientCount)
					{
						$.notify({message:"Неверное количество десертов. Должно быть ("+clientCount+")"},
						    { type : 'error',
						   z_index : 10000 });

						return false;
					}
					else
					{
						return true;
					}
				}
				else
				{
					$.notify({message:"Неверное Значение в таблице десертов.("+qty+")"},
						    { type : 'error',
						   z_index : 10000 });
					return false;
				}
			}
			else
			{
				$.notify({message:"Заполните поле Количество Десертов."},
						    { type : 'error',
						   z_index : 10000 });
				return false;
			}
		}
		else
		{
			desTblStat = true;
			_.each( $('#'+this.clientItemsTable+' tbody tr'), function(row, id) {
				var qty=parseInt($(row).find('#itemQtyInClient').val());
				if (qty != 1 ) {
					$.notify({message:"В клиентском наборе количество каждого десерта в таблице должно быть 1."},
						    { type : 'error',
						   z_index : 10000 });

					desTblStat = false;
					return false;
				}

			});
			return desTblStat;
		}
	},

	
	collectItemsTable: function(){
		//Reset collection
		this.clientCollectionItems.reset();
		self=this;
		//Get desert row 'Id' And "qty" and add to items collection
		_.each( $('#'+this.clientItemsTable+' tbody tr'), function(row, id) {
			self.clientCollectionItems.add({
											id  : $(row).attr('id'),
											qty : parseInt($.trim($(row).find('#itemQtyInClient').val()))
										})
		});
	},

	collectAttrsTable: function(){
		//Reset collection
		this.clientCollectionAttrs.reset();
		self=this;
		//Get Attribute row 'Id' And "price" and add to attributes collection
		_.each( $('#'+this.clientAttributesTable+' tbody tr'), function(row, id) {
			self.clientCollectionAttrs.add({
											id  : $(row).attr('id'),
											price : $.trim($(row).find('#clientAttrPrice').val()),
											mmt_id : $.trim($(row).find('#clientAttrPrice').val())
										})
			
		});
	},


	getTableItemsQty: function(){
		var qty = 0;
		_.each( $('#'+this.clientItemsTable+' tbody tr'), function(row, id) {
			qty+=parseInt($(row).find('#itemQtyInClient').val());
		});
		return qty;
	},

	renumerateTable: function(table){
		_.each( $('#'+table+' tbody tr'), function(row, id) {
							$(row).find('#numbering').html(id+1);
						});
	},

	duplicateCheck: function(table,rowId){
		var checker = true;
		_.each( $('#'+table+' tbody tr'), function(row, id) {
			var currentRowID  = $(row).attr('id')
			//console.log(currentRowID);
			if( currentRowID !== undefined && currentRowID == rowId ){
				checker=false;
			};
		});
		return checker;
	},

	addItemRow: function(table,data){
		
		$('#'+table+' tbody').prepend('<tr id='+data.id+'>'
											+' <td id="numbering"></td>'
											+' <td>'+data.name+'</td>'
											+' <td><input type="text" size="5" id="itemQtyInClient" value="1"></td>'
											+' <td>'+data.price+'</td>'
											+' <td>'+data.mmt_name+'</td>'
											+' <td><input class="removeClientItem" type="button" value="X"></td>'
									+'</tr>')
	},

	addAttrRow: function(table,data){
		$('#'+table+' tbody').prepend('<tr id='+data.id+'>'
											+' <td id="numbering"></td>'
											+' <td>'+data.name+'</td>'
											+' <td>'+data.attrgroup_name+'</td>'
											+' <td><input type="text" id="clientAttrPrice" data-mmtid="'+data.mmt_id+'" size="5" value="'+data.price+'"></td>'
											+' <td>'+data.mmt_name+'</td>'
											+' <td><input class="removeClientAttr" type="button" value="X"></td>'
									+'</tr>')
	},

	render: function(fullClient){
		
		this.$el.html(this.template(fullClient));

		// Making Client Description an WYSYWYG Editor
		tinymce.init({
						selector : '#clientDescription',
						  plugin : 'a_tinymce_plugin',
		  		 a_plugin_option : true,
		  a_configuration_option : 400

		});
		var locator	= '';
		if (this.globalAction == 'e') {
			$('.box-header h3 span').html('Редактировать набор <b>'+fullClient.info.name+'</b>.');
			locator = '../../';
		}else if( this.globalAction == 'c'){
			$('.box-header h3 span').html('Создать новый набор');
			locator = '../';
		}
		/*
		| Making Client Attribute list select2
		*/
		//###################################
		$("#clientAttrList").select2({
				  ajax: {
				    url: locator+ "clients/getattributes",
				    dataType: 'json',
				    delay: 400,
				    data: function (params) {
				      return {
				        q: params.term, // search term
				        page: params.page
				      };
				    },
				    processResults: function (data, params) {
				      // parse the results into the format expected by Select2
				      // since we are using custom formatting functions we do not need to
				      // alter the remote JSON data, except to indicate that infinite
				      // scrolling can be used
				      params.page = params.page || 1;

				      return {
				        results: data.items,
				        pagination: {
				          more: (params.page * 30) < data.total_count
				        }
				      };
				    },
				    cache: true
				  },
				  allowClear: true,
				  placeholder: "...",
				  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
				  minimumInputLength: 2,
				  templateResult: function(repo){
				  					if (repo.loading) return repo.text;

								  	var markup = '<div class="row">'
													+'<div class="col-md-3">'+repo.name+'</div>'
													+'<div class="col-md-3">'+repo.attrgroup_name+'</div>'
													+'<div class="col-md-3">'+repo.price+'</div>'
													+'<div class="col-md-3">'+repo.mmt_name+'</div>'
												+'</div>';
				          return markup;
				  },
				  templateSelection: function(repo){
				  	return repo.name;
				  }

		});
		
		/*
		| Making Client Item list select2
		*/
		//###################################
		$("#clientItemList").select2({
				  ajax: {
				    url: locator+"clients/getitems",
				    dataType: 'json',
				    delay: 400,
				    data: function (params) {
				      return {
				        q: params.term, // search term
				        page: params.page
				      };
				    },
				    processResults: function (data, params) {
				      // parse the results into the format expected by Select2
				      // since we are using custom formatting functions we do not need to
				      // alter the remote JSON data, except to indicate that infinite
				      // scrolling can be used
				      params.page = params.page || 1;

				      return {
				        results: data.items,
				        pagination: {
				          more: (params.page * 30) < data.total_count
				        }
				      };
				    },
				    cache: true
				  },
				  allowClear: true,
				  placeholder: "...",
				  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
				  minimumInputLength: 2,
				  templateResult: function(repo){
	  					if (repo.loading) return repo.text;
				  		
				  		var markup = '<div class="row">'
									+'<div class="col-md-3">'+repo.name+'</div>'
									+'<div class="col-md-3">'+repo.sku+'</div>'
									+'<div class="col-md-3">'+repo.price+'</div>'
									+'<div class="col-md-3">'+repo.mmt_name+'</div>'
								+'</div>';
			        return markup;
				  },
				  templateSelection: function(repo){
				  		return repo.name;
				  }
		});
		//###################################
		
		//Making featured image fineuploader
		var config = {};
		config.element 		 = 'clientFeaturedImg';
		config.imageTag 	 = 'clientFeaturedImgImg';
		config.sizeLimit 	 = 1024000;
		config.itemLimit 	 = 1;

		App.makeFineUploader(config);
	},

	emptyCreatePage : function(){
	
		$("#"+this.clientItemsTable+' tbody').html('');
		$("#"+this.clientAttributesTable+' tbody').html('');

		this.formish.clientName.val('');
		this.formish.clientSKU.val('');
		this.formish.clientType.val('static').trigger('change');
		this.formish.clientCount.val('3').trigger('change');
		this.formish.clientPrice.val('');
		this.formish.clientIsEnabled.val('0').trigger('change');
		this.formish.clientFeaturedImgImg.attr('data-hash','');
		this.formish.clientFeaturedImgImg.attr('data-filename','');
		this.formish.clientFeaturedImgImg.attr('src',this.formish.clientFeaturedImgImg.attr('data-dir')
												  +'/img/gallery/'
												  +this.formish.clientFeaturedImgImg.attr('data-defimgdef'));
		tinymce.activeEditor.clientContent('');
		
		this.clientModelInfo.clear();
		this.clientCollectionItems.reset();
		this.clientCollectionAttrs.reset();
		//console.log(this.clientModelInfo);
		//console.log(this.clientCollectionItems);
		//console.log(this.clientCollectionAttrs);
	},

	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	submitClient : function(e){
		e.preventDefault();
		console.log('submit click');
		 
		if(!this.validateClientItemsQty()){
			return;
		}
		
		//Collect items form items table
		this.collectItemsTable();

		//Collect attrs form attrs table
		this.collectAttrsTable();



		var clientSbmtCollection 	= new App.Collections.ClientActionTmplCreate();
		
		var featured_image_hash = this.formish.clientFeaturedImgImg.attr('data-hash');
		var featured_image_name = this.formish.clientFeaturedImgImg.attr('data-filename');
		var data_defimgdef 		= this.formish.clientFeaturedImgImg.attr('data-defimgdef');
		//console.log(this.formish);
		this.clientModelInfo.client('name', 					this.formish.clientName.val());
		this.clientModelInfo.client('sku', 					this.formish.clientSKU.val());
		this.clientModelInfo.client('description',			tinymce.activeEditor.getContent());
		this.clientModelInfo.client('type',					this.formish.clientType.val());
		this.clientModelInfo.client('count',					this.formish.clientCount.val());
		this.clientModelInfo.client('price', 					this.formish.clientPrice.val());
		this.clientModelInfo.client('mmt_id', 				this.formish.clientMMT.val());
		this.clientModelInfo.client('is_enabled', 			this.formish.clientIsEnabled.val());
		this.clientModelInfo.client('featured_image_hash',	featured_image_hash	);
		this.clientModelInfo.client('featured_image_name',	featured_image_name	);
		
		/*if ( this.clientModelInfo.get('type') == undefined || this.clientModelInfo.get('type') == '') {

			this.clientModelInfo.client('type', this.formish.clientType.val());
		}

		//Check And Client Count
		if (this.clientModelInfo.get('count') == undefined || this.clientModelInfo.get('count') == '' || this.clientModelInfo.get('count') == '0') {
			this.clientModelInfo.client('count', 			this.formish.clientCount.val());
		}
*/
		// Check and client featured name
		if ( featured_image_name !== '' && featured_image_name !== undefined) {
			this.clientModelInfo.client('featured_image', featured_image_hash + '.' + featured_image_name.split('.').pop())
		}else{
			this.clientModelInfo.client('featured_image', data_defimgdef)
		}

		var sbmtModel = new App.Models.ClientTmpl({
												info  : this.clientModelInfo,
												items : this.clientCollectionItems.models,
												attrs : this.clientCollectionAttrs.models
											});
		//console.log(sbmtModel);
				
		// this to self		
		var self = this;

		clientSbmtCollection.create( 
								sbmtModel, 
								{
									wait:true,
									success: function(model,response){
										var clientName = model.get('info').get('name');
										var tempMesage = clientName+' Updated ';
										if (self.globalAction == 'c') {
											tempMesage = clientName+' Created ';
											// Empty tag values
											self.emptyCreatePage( self.formish );
										}
										//console.log(model․previous());
										//console.log(model․previous('info'));
										
										// Showing Success Message
										$.notify({ message : tempMesage+"Successfully."},
												    { type : 'success',
												   z_index : 10000 });
										

									},
									error: function(model,response){
										// Showing Success Message
										//console.log(response);
										$.notify({ message : response.responseJSON.message},
												    { type : 'danger',
												   z_index : 10000 });
									}
								});//#collection.create 
	}//#createClient
});

/*
|---------------------------------------------------------------------------------
|Delete Client Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteClientModal = Backbone.View.extend({
	
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

	//  DESTROY Client
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
	
});//#DeleteClientModal