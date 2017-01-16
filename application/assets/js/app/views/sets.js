/*
|---------------------------------------------------------------------------------
|Initing Sets Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initSet = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('set:edit', 	 this.editItem,   this);
		vent.on('set:delete', this.deleteItem, this);

		var setCollection = new App.Collections.Sets();
		// Fetch Values From Server
		setCollection.fetch();
		
		// Initing Create Button 
		//var createbutton = new App.Views.CreateSetModal({collection: setCollection});

		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: setCollection});

		// Generate and Show Grid		
		this.generateGrid({ collection: setCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: setCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: setCollection});
	},

	
	//Delete Set Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){

		var confirmDelModalView = new App.Views.DeleteSetModal({model: rowmodel});

		$("#setDelConfModalDiv").html(confirmDelModalView.el);
	
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
					name: 'defined_count',
					label: 'Кол. Дес(шт.)',
					editable: false,
					cell: 'string'
				},{
					name: 'type',
					label: 'Тип',
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
			
		});//#setGrid
		
		$('#gridSets').append(grid.render().el);
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
		$('#gridSets').after(paginator.render().el);
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
		$("#gridSets").before(serverSideFilter.render().el);
		$("#gridSets").before($("#btnCreateSet"));
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
				vent.trigger('set:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('set:delete', this.model);
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


App.Views.initSetActionsPage = Backbone.View.extend({

	initialize: function(params){
		
		var createsetpage = new App.Views.SetActionsPage({ action: params.action, set_id: params.set_id });
		//console.log(App.template('tmplSetFields'));
	}
});


/*
|---------------------------------------------------------------------------------
|Create Set Page
|---------------------------------------------------------------------------------
*/
App.Views.SetActionsPage = Backbone.View.extend({
	el: '.box',

	template: App.template('tmplSetFields'),

	events: {
		/*'click button#sbmtSet' 		: 'submitSet',*/
		'submit #setSubmit' 		: 'submitSet',
		'keydown input#setName' 	: 'preventEnter',
		'change #setItemList' 		: 'setItemSelectChange',
		'change #setAttrList' 		: 'setAttributeSelectChange',
		/*'change #setCount' 			: 'setCountChange',*/
		/*'change #setType' 			: 'setTypeChange',*/
		'click .removeSetItem' 		: 'removeSetItem',
		'click .removeSetAttr' 		: 'removeSetAttr',
		'change input#itemQtyInSet' : 'itemQtyChange'
	},

	formish: {},

	initialize : function(params){
		var self = this;
		this.initProcess = false;

		if (params.action=='e') { //Edit Action Check 
			this.initProcess = true;
			this.globalAction = 'e';
			var editSetCollection = new App.Collections.EditSet();
			editSetCollection
			.fetch({
						data:{ 		
								action : params.action,
									id : params.set_id
							},
				 })
			.then( function(response){
				
				if ( response.status == 'failure' ) {
					$.notify({ message : response.message},
							    	{ type : 'danger',
								   z_index : 10001 });
					return;
				}
				self.render(response);
				
				self.initCreatePage();				
				
				$("#setType").val(response.info.type).trigger("change");
				$("#setInDesertPage").val(response.info.in_desert_page).trigger("change");
				$("#setCount").val(response.info.defined_count).trigger("change");
				$("#setIsEnabled").val(response.info.is_enabled).trigger("change");
				$("#setIsNew").val(response.info.is_new).trigger("change");
			});

			this.setModelInfo = new App.Models.SetTmplInfo();
			this.setModelInfo.set('set_id',params.set_id);
			//console.log(this.setModelInfo);
		} else if(params.action == 'c') { //Check Create Action
			this.setModelInfo 		= new App.Models.SetTmplInfo();
			this.globalAction = 'c';
			this.render();
			this.initCreatePage();
		} else {
			return;
		}
		this.setCollectionItems = new App.Collections.SetActionTmplItem();
		this.setCollectionAttrs = new App.Collections.SetActionTmplAttribute();
	},

	initCreatePage : function(){

		this.formish.setName			=$("#setName");
		this.formish.setSKU				=$("#setSKU");
		this.formish.setFeaturedImgImg	=$("#setFeaturedImgImg");
		this.formish.setType			=$("#setType");
		this.formish.setInDesertPage	=$("#setInDesertPage");
		this.formish.setCount			=$("#setCount");
		this.formish.setPrice			=$("#setPrice");
		this.formish.setMMT				=$("#setMMT");
		this.formish.setIsEnabled		=$("#setIsEnabled");
		this.formish.setIsNew			=$("#setIsNew");
		this.formish.setItemsTbl		=$("#"+this.setItemsTable);
		this.formish.setAttrsTbl		=$("#"+this.setAttributesTable);
	},


	setItemsTable: 'tblSetItems',
	setAttributesTable: 'tblSetAttributes',

	removeSetItem: function(e) {
		// Get tr element
		var closest_tr = $(e.currentTarget).closest('tr');
		// Remove Table Row
		closest_tr.remove();
		// Renumerate
		this.renumerateTable(this.setItemsTable);
	},

	removeSetAttr: function(e) {
		// Get tr element
		var closest_tr = $(e.currentTarget).closest('tr');
		// Remove Table Row
		closest_tr.remove();
		// Renumerate
		this.renumerateTable(this.setAttributesTable);
	},

	setAttributeSelectChange: function(e){
		var selectElementId = $(e.target).attr('id');
		
		var data = $('#'+selectElementId).select2('data')[0];

		//Check for duplicates
		if (!this.duplicateCheck(this.setAttributesTable, data.id)) {
			$.notify({message:"Этот Аттрибут уже в таблице.."},
					    { type : 'warning',
					   z_index : 10000 });	
			return;
		}

		//Add New item into table
		this.addAttrRow(this.setAttributesTable,data);
		// Add Item to Collection
		this.setCollectionAttrs.add( {id:data.id} );
		//console.log(this.setCollectionAttrs);
		this.renumerateTable(this.setAttributesTable);
	},


	setItemSelectChange: function(e) {
		//console.log('setItemSelectChange');
		var selectElementId = $(e.target).attr('id');
		var data = $('#'+selectElementId).select2('data')[0];
		
		if (data === undefined) {
			return;
		}
		//Check type
		var setType = $.trim($("#setType").val());
		//Get setItem Count
		var setCount = $.trim($("#setCount option:selected").val());

		//Check for duplicates
		if (!this.duplicateCheck(this.setItemsTable, data.id)) {
			$.notify({message:"Этот десерт уже в таблице.."},
					    { type : 'warning',
					   z_index : 10000 });	
			return;
		}

		// Add New Item into table
		this.addItemRow(this.setItemsTable,data);
		
		// Renumerate
		this.renumerateTable(this.setItemsTable);
	},

	itemQtyChange: function(e){
		var res = this.validateItemQtyValue($(e.target).val());
		if (res===false) {
			$(e.target).val('1');
		}
	},

	validateItemQtyValue: function(value){
		if (!value.toString().match(/^\d*$/)) {
			return false;
		}

	},

	validateSetItemsQty: function(){
		//Get Table Items Qty
		var qty = this.getTableItemsQty();
		//Get setCount
		var setCount = this.formish.setCount.val();

		var setType = this.formish.setType.val();

		if (setType == 'static') {
			if (setCount) {
				if (qty) {
					if (qty!=setCount) {
						$.notify({message:"Неверное количество десертов. Должно быть ("+setCount+")"},
						    { type : 'error',
						   z_index : 10000 });

						return false;
					} else {
						return true;
					}
				} else {
					$.notify({message:"Неверное Значение в таблице десертов.("+qty+")"},
						    { type : 'error',
						   z_index : 10000 });
					return false;
				}
			} else {
				$.notify({message:"Заполните поле Количество Десертов."},
						    { type : 'error',
						   z_index : 10000 });
				return false;
			}
		} else {
			desTblStat = true;
			_.each( $('#'+this.setItemsTable+' tbody tr'), function(row, id) {
				var qty=parseInt($(row).find('#itemQtyInSet').val());
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
		this.setCollectionItems.reset();
		self=this;
		//Get desert row 'Id' And "qty" and add to items collection
		_.each( $('#'+this.setItemsTable+' tbody tr'), function(row, id) {
			self.setCollectionItems.add({
											id  : $(row).attr('id'),
											qty : parseInt($.trim($(row).find('#itemQtyInSet').val()))
										})
		});
	},

	collectAttrsTable: function(){
		//Reset collection
		this.setCollectionAttrs.reset();
		self=this;
		//Get Attribute row 'Id' And "price" and add to attributes collection
		_.each( $('#'+this.setAttributesTable+' tbody tr'), function(row, id) {
			self.setCollectionAttrs.add({
											id  : $(row).attr('id'),
											price : $.trim($(row).find('#setAttrPrice').val()),
											mmt_id : $.trim($(row).find('#setAttrPrice').val())
										})
			
		});
	},


	getTableItemsQty: function(){
		var qty = 0;
		_.each( $('#'+this.setItemsTable+' tbody tr'), function(row, id) {
			qty+=parseInt($(row).find('#itemQtyInSet').val());
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
			}
		});
		return checker;
	},

	addItemRow: function(table,data){
		
		$('#'+table+' tbody').prepend('<tr id='+data.id+'>'+
									  ' <td id="numbering"></td>'+
									  ' <td>'+data.name+'</td>'+
									  ' <td><input type="text" size="5" id="itemQtyInSet" value="1"></td>'+
									  ' <td>'+data.price+'</td>'+
									  ' <td>'+data.mmt_name+'</td>'+
									  ' <td><input class="removeSetItem" type="button" value="X"></td>'+
									  '</tr>')
	},

	addAttrRow: function(table,data){
		$('#'+table+' tbody').prepend('<tr id='+data.id+'>'+
									  ' <td id="numbering"></td>'+
									  ' <td>'+data.name+'</td>'+
									  ' <td>'+data.attrgroup_name+'</td>'+
									  ' <td><input type="text" id="setAttrPrice" data-mmtid="'+data.mmt_id+'" size="5" value="'+data.price+'"></td>'+
									  ' <td>'+data.mmt_name+'</td>'+
									  ' <td><input class="removeSetAttr" type="button" value="X"></td>'+
									  '</tr>')
	},

	render: function(fullSet){
		
		this.$el.html(this.template(fullSet));

		// Making Set Description an WYSYWYG Editor
		tinymce.init({
						selector : '#setDescription',
						  plugin : 'a_tinymce_plugin',
		  		 a_plugin_option : true,
		  a_configuration_option : 400

		});
		var locator	= '';
		if (this.globalAction == 'e') {
			$('.box-header h3 span').html('Редактировать набор <b>'+fullSet.info.name+'</b>.');
			locator = '../../';
		}else if( this.globalAction == 'c'){
			$('.box-header h3 span').html('Создать новый набор');
			locator = '../';
		}
		/*
		| Making Set Attribute list select2
		*/
		//###################################
		$("#setAttrList").select2({
				  ajax: {
				    url: locator+ "sets/getattributes",
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

								  	var markup = '<div class="row">'+
													'<div class="col-md-3">'+repo.name+'</div>'+
													'<div class="col-md-3">'+repo.attrgroup_name+'</div>'+
													'<div class="col-md-3">'+repo.mmt_name+'</div>'+
												'</div>';
				          return markup;
				  },
				  templateSelection: function(repo){
				  	return repo.name;
				  }

		});
		
		/*
		| Making Set Item list select2
		*/
		//###################################
		$("#setItemList").select2({
				  ajax: {
				    url: locator+"sets/getitems",
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
				  		
				  		var markup = '<div class="row">'+
										'<div class="col-md-3">'+repo.name+'</div>'+
										'<div class="col-md-3">'+repo.price+'</div>'+
										'<div class="col-md-3">'+repo.mmt_name+'</div>'+
									'</div>';
			        return markup;
				  },
				  templateSelection: function(repo){
				  		return repo.name;
				  }
		});
		//###################################
		
		//Making featured image fineuploader
		var config = {};
		config.element 		 = 'setFeaturedImg';
		config.imageTag 	 = 'setFeaturedImgImg';
		config.sizeLimit 	 = 1024000;
		config.itemLimit 	 = 1;

		App.makeFineUploader(config);
	},

	emptyCreatePage : function(){
	
		$("#"+this.setItemsTable+' tbody').html('');
		$("#"+this.setAttributesTable+' tbody').html('');

		this.formish.setName.val('');
		this.formish.setSKU.val('');
		this.formish.setType.val('static').trigger('change');
		this.formish.setInDesertPage.val('').prop('disabled',true).trigger('change');
		this.formish.setCount.val('3').trigger('change');
		this.formish.setPrice.val('');
		this.formish.setIsEnabled.val('0').trigger('change');
		this.formish.setFeaturedImgImg.attr('data-hash','');
		this.formish.setFeaturedImgImg.attr('data-filename','');
		this.formish.setFeaturedImgImg.attr('src',this.formish.setFeaturedImgImg.attr('data-dir') + '/img/gallery/' + this.formish.setFeaturedImgImg.attr('data-defimgdef'));
		tinymce.activeEditor.setContent('');
		
		this.setModelInfo.clear();
		this.setCollectionItems.reset();
		this.setCollectionAttrs.reset();
		//console.log(this.setModelInfo);
		//console.log(this.setCollectionItems);
		//console.log(this.setCollectionAttrs);
	},

	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	submitSet : function(e){
		e.preventDefault();
		console.log('submit click');
		 
		if(!this.validateSetItemsQty()){
			return;
		}
		
		//Collect items form items table
		this.collectItemsTable();

		//Collect attrs form attrs table
		this.collectAttrsTable();

		var setSbmtCollection 	= new App.Collections.SetActionTmplCreate();
		
		var featured_image_hash = this.formish.setFeaturedImgImg.attr('data-hash');
		var featured_image_name = this.formish.setFeaturedImgImg.attr('data-filename');
		var data_defimgdef 		= this.formish.setFeaturedImgImg.attr('data-defimgdef');
		//console.log(this.formish);
		this.setModelInfo.set('name', 					this.formish.setName.val());
		this.setModelInfo.set('sku', 					this.formish.setSKU.val());
		this.setModelInfo.set('description',			tinymce.activeEditor.getContent());
		this.setModelInfo.set('type',					this.formish.setType.val());
		this.setModelInfo.set('in_desert_page',			this.formish.setInDesertPage.val());
		this.setModelInfo.set('count',					this.formish.setCount.val());
		this.setModelInfo.set('price', 					this.formish.setPrice.val());
		this.setModelInfo.set('mmt_id', 				this.formish.setMMT.val());
		this.setModelInfo.set('is_enabled', 			this.formish.setIsEnabled.val());
		this.setModelInfo.set('is_new', 				this.formish.setIsNew.val());
		this.setModelInfo.set('featured_image_hash',	featured_image_hash	);
		this.setModelInfo.set('featured_image_name',	featured_image_name	);
		console.log(this.formish.setInDesertPage.val());
		/*if ( this.setModelInfo.get('type') == undefined || this.setModelInfo.get('type') == '') {

			this.setModelInfo.set('type', this.formish.setType.val());
		}

		//Check And Set Count
		if (this.setModelInfo.get('count') == undefined || this.setModelInfo.get('count') == '' || this.setModelInfo.get('count') == '0') {
			this.setModelInfo.set('count', 			this.formish.setCount.val());
		}
*/
		// Check and set featured name
		if ( featured_image_name !== '' && featured_image_name !== undefined) {
			this.setModelInfo.set('featured_image', featured_image_hash + '.' + featured_image_name.split('.').pop())
		}else{
			this.setModelInfo.set('featured_image', data_defimgdef)
		}

		var sbmtModel = new App.Models.SetTmpl({
												info  : this.setModelInfo,
												items : this.setCollectionItems.models,
												attrs : this.setCollectionAttrs.models
											});
		//console.log(sbmtModel);
				
		// this to self		
		var self = this;

		setSbmtCollection.create( 
								sbmtModel, 
								{
									wait:true,
									success: function(model,response){
										var setName = model.get('info').get('name');
										var tempMesage = setName+' Updated ';
										if (self.globalAction == 'c') {
											tempMesage = setName+' Created ';
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
	}//#createSet
});

/*
|---------------------------------------------------------------------------------
|Delete Set Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteSetModal = Backbone.View.extend({
	
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

	//  DESTROY Set
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
	
});//#DeleteSetModal