App.Views.filterView = Backbone.View.extend({
	el:'#tsAdvancedFilters',
	events:{
		'change #tsFilterYears' : 'yearChange',
		'change #tsFilterWeeks' : 'weekChange',
		'change #tsFilterUsers' : 'userChange',
		'click #btnFilterAll'   : 'filterEvent'
	},

	initialize : function(){
		
		this.initFilterBar();
		this.initYear();
		this.initWeek();
		this.initUser();

	},

	// Get Year Value And Set To Collection
	initYear : function(){
		this.collection.queryParams.year = $.trim($('#tsFilterYears option:selected').val());
	},

	// Get Week Value And Set To Collection
	initWeek : function(){
		this.collection.queryParams.week = $.trim($('#tsFilterWeeks option:selected').val());
	},

	// Get User Value And Set To Collection
	initUser : function(){
		this.collection.queryParams.user = $.trim($('#tsFilterUsers option:selected').val());
	},

	// Filter Button Click Event
	filterEvent : function(){
		//this.collection.queryParams.page = 1;
		this.collection.state.currentPage = 1;
		this.collection.fetch({reset:true});
	},

	// Year Option Change Event
	yearChange : function(e){
		this.initYear();
	},

	// Week Option Change Event
	weekChange : function(e){
		this.initWeek();
	},

	// User Option Change Event
	userChange : function(e){
		this.initUser();
	},

	// Append Filter Bar
	initFilterBar : function(){
		//Appending Create Button
		$("#tsAdvancedFilters").append($("#btnCreateTimesheet"));

		// Append filter button
		$("#tsAdvancedFilters").append('<button class="btn btn-sm" id="btnFilterAll">Search</button>');

		//Append Year Select Filter 
		$("#tsAdvancedFilters").append($('#tmpTsYears').html());

		//Append Week Select Filter 
		$("#tsAdvancedFilters").append($('#tmpTsWeeks').html());

		//Append User Select Filter 
		$("#tsAdvancedFilters").append($('#tmpTsUsers').html());

		
		$('.myTsSelect2').select2({
			minimumResultsForSearch: Infinity
			
		});
		
		$('.myTsSelect2Searchable').select2({
			
		});

		$(".select2").css({float: "right", margin: "20px"});
		$("#btnFilterAll").css({float: "right", margin: "20px"});
		/*$("#tsFilterYears").css({float: "left", margin: "20px"});
		$("#tsFilterWeeks").css({float: "left", margin: "20px"});
		$("#tsFilterUsers").css({float: "left", margin: "20px"});*/
	},
});

/*
────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
─██████████████─██████████─██████──────────██████─██████████████─██████████████─██████──██████─██████████████─██████████████─██████████████─
─██░░░░░░░░░░██─██░░░░░░██─██░░██████████████░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░██──██░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
─██████░░██████─████░░████─██░░░░░░░░░░░░░░░░░░██─██░░██████████─██░░██████████─██░░██──██░░██─██░░██████████─██░░██████████─██████░░██████─
─────██░░██───────██░░██───██░░██████░░██████░░██─██░░██─────────██░░██─────────██░░██──██░░██─██░░██─────────██░░██─────────────██░░██─────
─────██░░██───────██░░██───██░░██──██░░██──██░░██─██░░██████████─██░░██████████─██░░██████░░██─██░░██████████─██░░██████████─────██░░██─────
─────██░░██───────██░░██───██░░██──██░░██──██░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─────██░░██─────
─────██░░██───────██░░██───██░░██──██████──██░░██─██░░██████████─██████████░░██─██░░██████░░██─██░░██████████─██░░██████████─────██░░██─────
─────██░░██───────██░░██───██░░██──────────██░░██─██░░██─────────────────██░░██─██░░██──██░░██─██░░██─────────██░░██─────────────██░░██─────
─────██░░██─────████░░████─██░░██──────────██░░██─██░░██████████─██████████░░██─██░░██──██░░██─██░░██████████─██░░██████████─────██░░██─────
─────██░░██─────██░░░░░░██─██░░██──────────██░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░██──██░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─────██░░██─────
─────██████─────██████████─██████──────────██████─██████████████─██████████████─██████──██████─██████████████─██████████████─────██████─────
────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────

*/
/*
|---------------------------------------------------------------------------------
|Initing Timesheets Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initTimesheet = Backbone.View.extend({
	
	

	initialize : function(){

		vent.on('timesheet:details',	this.detailsItem, this);
		vent.on('timesheet:edit',		this.editItem,    this);
		vent.on('timesheet:createCopy',	this.createCopyItem,    this);
		vent.on('timesheet:delete',	 	this.deleteItem,  this); 

		var timesheetCollection = new App.Collections.Timesheets();
		// Initing filtersyear
		
		var advancedFilter = new App.Views.filterView({collection: timesheetCollection});

		// Fetch Values From Server
		timesheetCollection.fetch(); // { data: {year:"-1",week:"-1", user_id:"-1"}}
		
		
		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: timesheetCollection});
		// Generating Status Cell 
		//statGenedCell = this.generateStatus({collection: timesheetCollection});
		//console.log(actGenedCell);
		
		// Generate and Show Grid		
		this.generateGrid({ collection: timesheetCollection, actionsCell:actGenedCell});
		// Generate and Show Pagination
		this.generatePaginator({ collection: timesheetCollection});
		// Generate and Show Search Field(Filter)
		//this.generateFilter({ collection: timesheetCollection});
	},

	//Timesheet Details Page Redirect
	//###############################
	detailsItem : function(rowmodel){
		//window.open('/index.php/timesheetdetails/'+rowmodel.get('user_id')+'/'+rowmodel.get('id'));
		appRouter.navigate("timesheetdetails/"+rowmodel.get('user_id')+'/'+rowmodel.get('id'),{trigger:false,replace:false});
		return;
		//window.location.href(App.myRoot + '/timesheetdetails' + rowmodel.get('id'));
		//console.log("timesheetdetails/"+rowmodel.get('id'));
		//Bacbone.history.navogate("timesheetdetails/"+rowmodel.get('id'));
	},

	//Timesheet Edit Page Redirect
	//############################
	editItem : function(rowmodel){
		appRouter.navigate("tsactions/e/"+rowmodel.get('id'),{trigger:true});
	},

	//Timesheet Create From Copy Page Redirect
	//########################################
	createCopyItem : function(rowmodel){
		appRouter.navigate("tsactions/cc/"+rowmodel.get('id'),{trigger:true});
	},

	// Generate Grid
	//##############
	generateGrid : function(params){
		self = this;
		grid = new Backgrid.Grid({

			//body: ScrollableBody,
			className: 'table table-hover',
			// Initing Table Columns
			columns:[{
					name:'',
					cell: 'select-row',
					headerCell: 'select-all'
				},{
					name: 'user',
					label: 'User',
					editable: false,
					cell: 'string'
				},{
					name: 'ts_year',
					label: 'Year',
					editable: false,
					cell: 'string'
				},{
					name: 'w_no',
					label: 'Week',
					editable: false,
					cell: 'string'
				},{
					name: 'w_start',
					label: 'From',
					editable: false,
					cell: 'string'
				},{
					name: 'w_end',
					label: 'To',
					editable: false,
					cell: 'string'
				}/*,{
					name: 'created',
					label: 'Created',
					editable: false,
					cell: 'string'
				}*/,{
					name: 'total',
					label: 'Total Hours',
					editable: false,
					cell: 'string'
				},{
					name: 'items_in_ts',
					label: 'Item Count',
					editable: false,
					cell: 'string'
				},{
					name: 'status_id',
					label: 'Status',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var status_id = this.model.get('status_id');

							//console.log(this.model);
							var myClass = '';
							if(status_id == 1){ 
								myClass = 'label label-warning';
							} else if (status_id ==2){ 
								myClass = 'label label-info';
							} else if (status_id ==3){ 
								myClass = 'label label-success';
							} else if (status_id ==4) { 
								myClass = 'label label-danger';
							} 
							this.$el.html( '<span class="'+myClass+'" >'+this.model.get('status')+'</span>' );
							return this;
						},
					})
				}/*,{
					name: 'overall_status',
					label: 'Additional Acceptance',
					editable: false,
					cell: Backgrid.Cell.extend({
						render : function(){
							var overall_status = this.model.get('overall_status');
							//console.log(overall_status);
							var myClass = '';
							var myMessage = '';

							if(overall_status <= 0){ 
								myClass = 'label label-danger';
								myMessage = 'Not Accepted'

							} else if (overall_status > 0){ 
								myClass = 'label label-success';
								myMessage = 'Fully Accepted'
							} 
							this.$el.html( '<span class="'+myClass+'" >'+myMessage+'</span>' );
							return this;
						},
					})
				}*/,{
					name: '1',
					label: 'Actions',
					editable: false,
					sortable: false,
					cell: params.actionsCell
					
				}],

			//Data Collection For Table 
			collection: params.collection
			
		});//#TS Grid
		//console.log(grid.render().el);
		$('#gridTimesheets').append(grid.render().el);
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
		$('#gridTimesheets').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			
			  	collection: params.collection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Search Timesheet" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#tsAdvancedFilters").append(serverSideFilter.render().el);
		
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
				'click .details' 	: 'detailsRow',
				'click .edit'    	: 'editRow',
				'click .createCopy'	: 'createCopyRow',
				'click .delete'  	: 'deleteRow'
			},
			
			detailsRow : function(e){
				//e.preventDefault();
				vent.trigger('timesheet:details', this.model);
			},

			editRow : function(e){
				//e.preventDefault();
				//console.log('edit');
				vent.trigger('timesheet:edit', this.model);
			},
			
			createCopyRow : function(e){
				//e.preventDefault();
				//console.log('createCopy');
				vent.trigger('timesheet:createCopy', this.model);
			},

			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('timesheet:delete', this.model);
			},

			render : function(){
				//console.log(params.collection);
				this.$el.append( this.template(params.collection));
				this.delegateEvents();
				return this;
			}
		});//#actionsCell

		return actionsCell;
	}//#generateActions

});

/*
──────────────────────────────────────────────────────────────────────────────────────────────────────────────
─██████████████─██████████████─██████████████─██████████─██████████████─██████──────────██████─██████████████─
─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░██─██░░░░░░░░░░██─██░░██████████──██░░██─██░░░░░░░░░░██─
─██░░██████░░██─██░░██████████─██████░░██████─████░░████─██░░██████░░██─██░░░░░░░░░░██──██░░██─██░░██████████─
─██░░██──██░░██─██░░██─────────────██░░██───────██░░██───██░░██──██░░██─██░░██████░░██──██░░██─██░░██─────────
─██░░██████░░██─██░░██─────────────██░░██───────██░░██───██░░██──██░░██─██░░██──██░░██──██░░██─██░░██████████─
─██░░░░░░░░░░██─██░░██─────────────██░░██───────██░░██───██░░██──██░░██─██░░██──██░░██──██░░██─██░░░░░░░░░░██─
─██░░██████░░██─██░░██─────────────██░░██───────██░░██───██░░██──██░░██─██░░██──██░░██──██░░██─██████████░░██─
─██░░██──██░░██─██░░██─────────────██░░██───────██░░██───██░░██──██░░██─██░░██──██░░██████░░██─────────██░░██─
─██░░██──██░░██─██░░██████████─────██░░██─────████░░████─██░░██████░░██─██░░██──██░░░░░░░░░░██─██████████░░██─
─██░░██──██░░██─██░░░░░░░░░░██─────██░░██─────██░░░░░░██─██░░░░░░░░░░██─██░░██──██████████░░██─██░░░░░░░░░░██─
─██████──██████─██████████████─────██████─────██████████─██████████████─██████──────────██████─██████████████─
──────────────────────────────────────────────────────────────────────────────────────────────────────────────

*/

/*
|---------------------------------------------------------------------------------
|Initing Create Timesheet Page View
|---------------------------------------------------------------------------------
*/
App.Views.initTimesheetActions = Backbone.View.extend({
	
	initialize : function(initialValues){

		var mainView = new App.Views.TimesheetActionsView( {initialValues} );
	}


});



/*
|---------------------------------------------------------------------------------
|Timesheet Actions View
|---------------------------------------------------------------------------------
*/
App.Views.TimesheetActionsView = Backbone.View.extend({
	el: '#dCreateTimesheet',
	// Main Table Row Template
	templateMainRow	: 	 App.template('tmpTsMainRow'),
	// Absence Table Row Template
	templateAbsenceRow : App.template('tmpTsAbsenceRow'),
	wds : ['Mo','Tu','We','Th','Fr','Sa','Su'],
	wdsLong : ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
	months : ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
	// Main Table Row Counter(allways increments )
	mainCounter:0,
	// Absence Table Row Counter(allways increments )
	absenceCounter:0,

	action : 'n',
	events: {
		
		'select2:select #tsProjActivityType' 					: 'projActivityTypeSelect',
		'select2:select #tsProjAbsenceType' 					: 'projAbsenceTypeSelect',
		'select2:select #tsOperation' 							: 'projOperationSelect',
		'select2:select #tsProject' 							: 'projSelect',
		'click #addTsMainRow' 									: 'addTsMainRow',
		'click #addTsAbsenceRow' 								: 'addTsAbsenceRow',
		'click #removeTsMainRow' 								: 'removeRow',
		'click #removeTsAbsenceRow'								: 'removeRow',
		'click input.tsWDMain' 									: 'wdValueClick',
		'click input.tsWDAbsence' 								: 'wdValueClick',
		'change input.tsWDMain' 								: 'wdValueChange',
		'change input.tsWDAbsence' 								: 'wdValueChange',
		'change tbody#tblMainTs tr td input#tsComment' 			: 'commentChange',
		'change tbody#tblAbsenceTs tr td input#tsComment' 		: 'commentChange',
		'change tbody#tblMainTs tr td input#tsCommentAdditonal'	: 'commentChange',
		'changeDate #tsWeek' 									: 'tsWeekChange',
		'click #sbmtTimesheetSave'  							: 'saveTimesheet',
		'click #sbmtTimesheetSaveAndSubmit'  					: 'saveTimesheet',
		'change .myTsSelect2' 									: 'yearChange',
	},
	/*
	|---------------------------------------------------------------------------------
	| CONSTRUCTOR Bebe
	|---------------------------------------------------------------------------------
	*/
	initialize : function(params){
		//console.log(params);
		this.mainCollection 	= new App.Collections.TimesheetActionTmplMain();
		this.absenceCollection 	= new App.Collections.TimesheetActionTmplAbsence();
		
		this.infoModel 			= new App.Models.TimesheetTmplInfo();
		//console.log(params.initialValues.action);
		if ( params.initialValues.action == 'c' ) 
		{
				this.action = 'c';
				this.addTsMainRow();
				//this.addTsAbsenceRow();
				$('#bcrbsActionPage').text('Create New Timesheet')
				$('#dDateSelect').html('<label for="tsWeek" ></label>'+
				                      '<input type="text" readonly="" id="tsWeek">'+
				                      '<label for="tsWeek" > <i style="font-size: 1.4em;" class="fa fa-calendar" aria-hidden="true"></i></label>');

				$('#tsWeek').datepicker({
					format: "yyyy-MM-dd",
					placeholder:'Select Week',
					weekStart:1,
					daysOfWeekHighlighted: "0,6",
				    calendarWeeks: true,
				    autoclose: true,
				    todayHighlight: true,
					todayBtn: "linked",
					startDate: '2016-01-01'
				});

				this.$el.css('display','block');

		} 
		else if( params.initialValues.action == 'e' )
		{
			this.action='e';
			self=this;
			// setting bcrbs->Breadcrumbs active portion
			$('#bcrbsActionPage').text('Edit Timesheet')
			var editTimesheetCollection = new App.Collections.EditTimesheet();

			editTimesheetCollection
					.fetch({
								data:{ 		
											id : params.initialValues.p1,
										action : params.initialValues.action
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
						// JSONING fetched collections
						editCollectionJSON = editTimesheetCollection.toJSON();

						// Distribute Fetched Values to Their collections
						_.each(editCollectionJSON, function(row){
							if (row['ts_type'] == 1) 
							{

								// Initing Row Values
								model={
											id 					: row['ts_main_id'],
											tsActivityType 		: row['activity_id'],
											tsActivityTypeCode	: row['activity_code'],
											tsProject 			: row['project_id'],
											tsOperation 		: row['operation_id'],
											tsRowNeedToAccept	: row['is_accepted'],
											tsWD1 				: row['wd1'],
											tsWD2 				: row['wd2'],
											tsWD3 				: row['wd3'],
											tsWD4 				: row['wd4'],
											tsWD5 				: row['wd5'],
											tsWD6 				: row['wd6'],
											tsWD7 				: row['wd7'],
											tsProjectManager	: row['project_manager'],
											tsComment 			: row['note']
								};
								// Add Main Row--> also adding row to mainCollection
								// false - for not empty row
								self.addTsMainRow(false, model);
								// Getting Current Main Row
								currentRow = $('#tblTsActions tbody#tblMainTs').find('tr[id='+row['ts_main_id']+']');

								// Set selected Main Type
								$(currentRow).find('#tsProjActivityType').val(row['activity_id']).trigger('change');

								// check for project or admin work
								if ( row['activity_code'] == 'at1' || row['activity_code'] == 'at2' ) {
									//console.log(row);
									// Make Project Select2
									$(currentRow).find('#tsProject').select2({
									 		minimumResultsForSearch: 'Infinity'
									});
									//console.log(row['project_id']);
									// Set selected Project
									$(currentRow).find('#tsProject').val(row['project_id']).trigger('change');
									// Append selected opeeration
									$(currentRow).find('#tsOperation').select2({
																					data: [{
																								id:row['operation_id'], 
																							  text:row['operation']
																						  }]
																				})
																		.trigger('change');// append('<option value="'+row['operation_id']+'">'+row['operation']+'</option>');
									// Set selected Operation
									$(currentRow).find('#tsOperation').val(row['operation_id']).trigger('change');
									// Recalculate --> Subtotal / Total / Grand Total <-- After Row Append

								} else if ( row['activity_code'] == 'at3' || row['activity_code'] == 'at4' || row['activity_code'] == 'at5' || row['activity_code'] == 'at6' ) {
									$(currentRow).find('#tsCommentAdditonal').css('display','block');
									$(currentRow).find('#tsComment').prop('disabled','true');
									//$(currentRow).find('#tsProject').prop('disabled','true');
									//$(currentRow).find('#tsOperation').prop('disabled','true');
								}

								self.reCheckSubtotals('tblMainTs');
								
							} 
							else if (row['ts_type'] == 2) 
							{
								// Initing Row Values
								model = {
										  id 				: row['ts_main_id'],
										  tsAbsenceType 	: row['absence_id'],
										  tsRowNeedToAccept	: row['is_accepted'],
										  tsWD1 			: row['wd1'],
										  tsWD2 			: row['wd2'],
										  tsWD3 			: row['wd3'],
										  tsWD4 			: row['wd4'],
										  tsWD5 			: row['wd5'],
										  tsWD6 			: row['wd6'],
										  tsWD7 			: row['wd7'],
										  tsProjectManager  : row['project_manager'],
										  tsComment 		: row['note']
								};
								// Add Absence Row--> also adding row to absenceCollection
								self.addTsAbsenceRow(false,model);
								// Getting Current Absence Row
								currentRow = $('#tblTsActions tbody#tblAbsenceTs').find('tr[id='+row['ts_main_id']+']');
								//console.log(currentRow);
								// Set Rows Selected Absence Type
								$(currentRow).find('#tsProjAbsenceType').val(row['absence_id']).trigger('change');
								// Recalculate --> Subtotal / Total / Grand Total <-- After Row Append
								self.reCheckSubtotals('tblAbsenceTs');
							}
						}); // Each

						if ( editCollectionJSON.length > 0 ) 
						{
							// Initing Fetched Editable Timesheet Info
							self.infoModel.set('ts_id', editCollectionJSON[0]['ts_id']);
							self.infoModel.set('year', editCollectionJSON[0]['ts_year']);
							self.infoModel.set('week', editCollectionJSON[0]['w_no']);
							self.infoModel.set('weekStart', editCollectionJSON[0]['w_start']);
							self.infoModel.set('weekEnd', editCollectionJSON[0]['w_end']);
							
							// Showing in From TS Info
							$('#tsWeekNo').html('<h3>'+editCollectionJSON[0]['ts_year']+' W#'+editCollectionJSON[0]['w_no']+'</h3>');
							$('#tsPeriod').html('<p>'+editCollectionJSON[0]['w_start']+'-->'+editCollectionJSON[0]['w_end']+'</p>');
							self.$el.css('display','block');
						}
						else
						{
							self.$el.html('<h3>This Timesheet does not exist or is not editable!!!</h3>');
							self.$el.append('<a href="'+window.location.origin+'/'+App.myRoot+'/timesheets">Back To Timesheets</a>');
							self.$el.css('display','block');
						}
					}); // Then
		}
		else if( params.initialValues.action == 'cc' )
		{
			this.action='cc';
			self=this;
			// setting bcrbs->Breadcrumbs active portion
			$('#bcrbsActionPage').text('Create From Copy')
			var editTimesheetCollection = new App.Collections.EditTimesheet();

			editTimesheetCollection
					.fetch({	
								data:{
										id 		: params.initialValues.p1,
										action	: params.initialValues.action
									}

							})
					.then( function(response){
						if ( response.status == 'failure' ) 
						{
							$.notify({ message : response.message},
									    	{ type : 'danger',
										   z_index : 10001 });
							return;
						}
						
						// JSONING fetched collections
						editCollectionJSON = editTimesheetCollection.toJSON();

						// Distribute Fetched Values to Their collections
						_.each(editCollectionJSON, function(row){
							if (row['ts_type'] == 1) 
							{

								// Initing Row Values
								model={
											id 					: row['ts_main_id'],
											tsActivityType 		: row['activity_id'],
											tsActivityTypeCode	: row['activity_code'],
											tsProject 			: row['project_id'],
											tsOperation 		: row['operation_id'],
											tsRowNeedToAccept	: row['is_accepted'],
											tsWD1 				: row['wd1'],
											tsWD2 				: row['wd2'],
											tsWD3 				: row['wd3'],
											tsWD4 				: row['wd4'],
											tsWD5 				: row['wd5'],
											tsWD6 				: row['wd6'],
											tsWD7 				: row['wd7'],
											tsProjectManager	: row['project_manager'],
											tsComment 			: row['note']
								};
								// Add Main Row--> also adding row to mainCollection
								// false - for not empty row
								self.addTsMainRow(false, model);
								// Getting Current Main Row
								currentRow = $('#tblTsActions tbody#tblMainTs').find('tr[id='+row['ts_main_id']+']');

								// Set selected Main Type
								$(currentRow).find('#tsProjActivityType').val(row['activity_id']).trigger('change');
								
								// check for project or admin work
								if ( row['activity_code'] == 'at1' || row['activity_code'] == 'at2' ) {
										console.log(row);
									// Make Project Select2
									$(currentRow).find('#tsProject').select2({
									 		//minimumResultsForSearch: Infinity
									});
									//console.log(row['project_id']);
									// Set selected Project
									$(currentRow).find('#tsProject').val(row['project_id']).trigger('change');
									// Append selected opeeration
									$(currentRow).find('#tsOperation').select2({data: [{id:row['operation_id'], text:row['operation']}] }).trigger('change');// append('<option value="'+row['operation_id']+'">'+row['operation']+'</option>');
									// Set selected Operation
									$(currentRow).find('#tsOperation').val(row['operation_id']).trigger('change');
									// Recalculate --> Subtotal / Total / Grand Total <-- After Row Append
								} else if ( row['activity_code'] == 'at3' || row['activity_code'] == 'at4' || row['activity_code'] == 'at5' || row['activity_code'] == 'at6' ) {
									$(currentRow).find('#tsCommentAdditonal').css('display','block');
									$(currentRow).find('#tsComment').prop('disabled','true');
									//$(currentRow).find('#tsProject').prop('disabled','true');
									//$(currentRow).find('#tsOperation').prop('disabled','true');
								}
								
								// Recalculate --> Subtotal / Total / Grand Total <-- After Row Append
								self.reCheckSubtotals('tblMainTs');
																
							} 
							else if (row['ts_type'] == 2) 
							{
								// Initing Row Values
								model = {
										  id 				: row['ts_main_id'],
										  tsAbsenceType 	: row['absence_id'],
										  tsRowNeedToAccept	: row['is_accepted'],
										  tsWD1 			: row['wd1'],
										  tsWD2 			: row['wd2'],
										  tsWD3 			: row['wd3'],
										  tsWD4 			: row['wd4'],
										  tsWD5 			: row['wd5'],
										  tsWD6 			: row['wd6'],
										  tsWD7 			: row['wd7'],
										  tsProjectManager  : row['project_manager'],
										  tsComment 		: row['note']
								};
								// Add Absence Row--> also adding row to absenceCollection
								self.addTsAbsenceRow(false,model);
								// Getting Current Absence Row
								currentRow = $('#tblTsActions tbody#tblAbsenceTs').find('tr[id='+row['ts_main_id']+']');
								//console.log(currentRow);
								// Set Rows Selected Absence Type
								$(currentRow).find('#tsProjAbsenceType').val(row['absence_id']).trigger('change');
								// Recalculate --> Subtotal / Total / Grand Total <-- After Row Append
								self.reCheckSubtotals('tblAbsenceTs');
							}
						}); // Each

						if ( editCollectionJSON.length > 0 ) 
						{
							$('#dDateSelect').html('<label for="tsWeek" ></label>'+
				                      '<input type="text" readonly="" id="tsWeek">'+
				                      '<label for="tsWeek" > <i style="font-size: 1.4em;" class="fa fa-calendar" aria-hidden="true"></i></label>');

							$('#tsWeek').datepicker({
								format: "yyyy-MM-dd",
								placeholder:'Select Week',
								weekStart:1,
								daysOfWeekHighlighted: "0,6",
							    calendarWeeks: true,
							    autoclose: true,
							    todayHighlight: true,
								todayBtn: "linked",
								startDate: '2016-01-01'
							});

							// Showing in From TS Info
							$('#pageStatus').html('<h3> Create timesheet from *<b>'+editCollectionJSON[0]['ts_year']+' W#'+editCollectionJSON[0]['w_no']+'</b>* copy</h3>');
							//$('#tsWeekNo').html('<h3>'+editCollectionJSON[0]['ts_year']+' W#'+editCollectionJSON[0]['w_no']+'</h3>');
							//$('#tsPeriod').html('<p>'+editCollectionJSON[0]['w_start']+'-->'+editCollectionJSON[0]['w_end']+'</p>');
							self.$el.css('display','block');
						}
						else
						{
							self.$el.html('<h3>This Timesheet does not exist or is not editable!!!</h3>');
							self.$el.append('<a href="'+window.location.origin+'/'+App.myRoot+'/timesheets">Back To Timesheets</a>');
							self.$el.css('display','block');
						}
					}); // Then
		}
		else
		{
			$('#dCreateTimesheet').html('<a href="'+window.location.origin+'/'+App.myRoot+'/timesheets">Back To Timesheets</a>');
			$('#dCreateTimesheet').css('display','block')
			$.notify({ message : "Invalid Action Performed."},
					    	{ type : 'danger',
						   z_index : 10001 });
			return;
		}
	},

	/*
	|---------------------------------------------------------------------------------
	| Clear All After Create
	|---------------------------------------------------------------------------------
	*/
	resetTs : function(){
		this.mainCollection.reset();
		this.absenceCollection.reset();
		this.tsCollection.reset()
		this.infoModel.clear({id:null}).set(this.infoModel.defaults);

		$('#tsWeek').datepicker('clearDates');
		$('#tblTsActions tbody#tblMainTs').empty();
		$('#tblTsActions tbody#tblAbsenceTs').empty();

		this.updateTableHeaders(false,true);
		this.updateTableFooters();
		
		$('#tsGrandTotal').text('');
		$('#tsWeekNo').html('');
		$('#tsPeriod').html('');
		$('#tsWeek').css('background-color','#ffffff');
		if (this.action == 'e') {
			$('#dCreateTimesheet').html('<a href="'+window.location.origin+'/'+App.myRoot+'/timesheets">Back To Timesheets</a>');
		}
		this.addTsMainRow();
		//console.log(this.infoModel);
	},


	/*
	|---------------------------------------------------------------------------------
	| Save Timesheet
	|---------------------------------------------------------------------------------
	*/
	saveTimesheet : function(e){
		window.setTimeout(function(){},2000);
		saveButtonType = $(e.currentTarget).attr('id');
		//save Type variable for sve status
		// 1 - created (save)
		// 2 - submited (save and submit)
		//saveType= 1;
		if ( saveButtonType == 'sbmtTimesheetSave' ) {
			//saveType= 1;
			// Set Save TYPE-> (save) / (save_and_submit)/
			this.infoModel.set('saveType',1);
			//console.log('save');
		}else if( saveButtonType == 'sbmtTimesheetSaveAndSubmit' ){
			//saveType= 2;
			this.infoModel.set('saveType',2);
			//console.log('save And Submit');			
		}
		else
		{
			return;
		}


		// Set Action Type
		this.infoModel.set('action',this.action);

		// SOME VALIDATION
		// 1. Validate info
		if (this.infoModel.get('year') == "") {
				$.notify({ message : "Date Is Required"},
				    	{ type : 'info',
					   z_index : 10001 });
			return;
		}
		
		// 2. VALIDATE MAIN AND ABSENCE TABLE LENGTH.
		if ( this.mainCollection.models.length < 1 && this.absenceCollection.models.length < 1 ) {
		
			$.notify({ message : "At Least One Row Required."},
		    	{ type : 'info',
			   z_index : 10001 });
			return;
		}


		//########################################################
		// Validate Required Fields MAIN TABLE
		//########################################################
		var resRequireds = this.validateRequireds();
		//console.log(resRequireds);
		//console.log(resRequireds);
		if (resRequireds['main'].length > 0) 
		{
			isMainValidated = true;
			//isManMessage = "";
			_.each(resRequireds['main'], function(res,id){
				if ( !res.is_validated ) 
				{
					isMainValidated = false;
					//isManMessage += res.row+", ";
				}
			})

			if (!isMainValidated) {
					$.notify({ message : 'Main table Row(s) has Required Fields'},
				    	{ type : 'error',
					   z_index : 10001 });		
				return;
			}
		}
		

		//########################################################
		//Validate Required Fields ABSENCE TABLE
		//########################################################
		if (resRequireds['absence'].length > 0) 
		{
			isAbsenceValidated = true;
			//isAbsenceMessage = "";
			_.each(resRequireds['absence'], function(res,id){
				if ( !res.is_validated ) 
				{
					isAbsenceValidated = false;
					//isAbsenceMessage += res.row+", ";
				}
			})
			if (!isAbsenceValidated) {
					$.notify({ message : 'Absence table Row(s) has Required Fields'},
				    	{ type : 'error',
					   z_index : 10001 });		
				return;
			}
		}

		// Check for Save Type, 
		// If Only Save Pressed DO NOT Validate WD Hours And Totals
		if ( this.infoModel.get('saveType') != 1 ) {

			//########################################################
			//Validate WD Hour Fields
			//########################################################
			var resValidateTotal = this.validateDateTotal();
			self=this;
			var i=0;
			areWDTimesValidated = true;

			_.each(resValidateTotal,function(res,id){
				
				// Grand Total Validation 40<= X >=77
				if (id == 'gTotal') {
					if (!res) {
						// Grand Total Is not Validated. Must Be between 40 and 48."
						$.notify({ message : "Grand Total Is not Validated. Must Be More or Equal to 40."},
						    	{ type : 'error',
							   z_index : 10001 });
						areWDTimesValidated = false;
					}
					return;
				}

				// Skip Sunday
				if (id=='tsWD7') {
					return;
				}

				// Check for holyday and include Saturday
				if (id=='tsWD6') {
					return;
				}

				// Column Total Validation 8<= X >=18
				if (!res) {
					// Is not Validated. Must Be between 8 and 12.
					$.notify({ message : self.wdsLong[i]+" Is not Validated. Must Be More or Equal to 8."},
						    	{ type : 'error',
							   z_index : 10001 });
					areWDTimesValidated = false;
				}
				i++;
			});
			
			if (!areWDTimesValidated) {
				return;
			}
		}


		if (this.action == 'c' || this.action == 'cc' ) {
				this.tsCollection = new App.Collections.TimesheetActionTmplCreate();
			
		} else if(this.action='e') {
				this.tsCollection = new App.Collections.TimesheetActionTmplEdit();
		}
		
		this.tsCollection.create( 
							new App.Models.TimesheetTmpl({
											info : this.infoModel,
											main : this.mainCollection.models,
										 absence : this.absenceCollection.models
										}),
										{
											wait:true,
											success:function(data,response){
												
												if (response.action_type == 2) {
													$.notify({ message : response.message},
												    	{ type : 'success',
													   z_index : 10001 });

												} else {

													$.notify({ message : response.message},
												    	{ type : 'warning',
													   z_index : 10001 });	
												}

											// RESET ALL FIELDS
											self.resetTs();

											
											//$('#sbmtTimesheetSave').prop('disabled',true);
											//$('#sbmtTimesheetSaveAndSubmit').prop('disabled',true);
												
											},//success
											error:function(data,response){
												//console.log(response);
												if (response.responseJSON.status=='failure') {
													$.notify({ message : response.responseJSON.message},
												    	{ type : 'danger',
													   z_index : 10001 });
												}
												$('#sbmtTimesheetSave').prop('disabled',true);
												$('#sbmtTimesheetSaveAndSubmit').prop('disabled',true);
											}//error

										}
						);//create

	},




	/*
	|---------------------------------------------------------------------------------
	| Activity Type Select Change Event
	|---------------------------------------------------------------------------------
	*/
	projActivityTypeSelect : function(e){
		// Get closest Row Element
		closest_tr = $(e.currentTarget).closest('tr');
		//console.log(closest_tr);
		// Get Table Row Id
		row_id = $(e.currentTarget).closest('tr').attr('id');
		
		//Get Activity Type Code
		//console.log($(e.params.data.element).attr('data-code'));
		activityTypeCode = $(e.params.data.element).attr('data-code');
		
		// Set Value To Collection
		this.mainCollection.get(row_id).set('tsActivityType', e.currentTarget.value);
		this.mainCollection.get(row_id).set('tsActivityTypeCode', activityTypeCode);

		if ( activityTypeCode == 'at1' || activityTypeCode == 'at2') 
		{
			// Make Project select2
			$(closest_tr).find('#tsProject').select2({
			 	//minimumResultsForSearch: Infinity
			});

			// Make Operation select2
			$(closest_tr).find('#tsOperation').select2({
			 	//minimumResultsForSearch: Infinity
			});

			//Enable Refresh button
			$(closest_tr).find('#tsOpRefresh').css('display','inline');

			// Enable and Clear Default Comment field
			$(closest_tr).find('#tsComment').prop('disabled',false);
			$(closest_tr).find('#tsComment').val('');

			// Hide And Clear Additional Comment Field
			$(closest_tr).find('#tsCommentAdditonal').css('display','none');
			$(closest_tr).find('#tsCommentAdditonal').val('');

			// Clear Comment Value in Main Collection
			this.mainCollection.get(row_id).set('tsComment', '');
		}
		else if( activityTypeCode == 'at3' || activityTypeCode == 'at4' || activityTypeCode == 'at5' || activityTypeCode == 'at6' )
		{
			//Disable Refresh button
			$(closest_tr).find('#tsOpRefresh').css('display','none');

			// Disable and Clear Default Comment field
			$(closest_tr).find('#tsComment').prop('disabled',true);
			$(closest_tr).find('#tsComment').val('');

			// Show And Clear Additional Comment Field
			$(closest_tr).find('#tsCommentAdditonal').val("");
			$(closest_tr).find('#tsCommentAdditonal').css('display','block');

			// Clear Comment Value in Main Collection
			this.mainCollection.get(row_id).set('tsComment', '');

			// Hide - Unselect - Clear  Project 
			$(closest_tr).find('#tsProject').next('span').css('display','none');
			$(closest_tr).find('#tsProject').val(null).trigger('change');
			this.mainCollection.get(row_id).set('tsProject', '');
			
			// Hide - Unselect - Clear Operations
			$(closest_tr).find('#tsOperation').next('span').css('display','none');
			$(closest_tr).find('#tsOperation').val(null).trigger('change');
			this.mainCollection.get(row_id).set('tsOperation', '');

			// Clear Project Manager Field
			$(closest_tr).find('#tsProjectManager').text('');

			// Set Need To Accept Row to "0"
			this.mainCollection.get(row_id).set('tsRowNeedToAccept', '0');
		}
	},

	
	/*
	|---------------------------------------------------------------------------------
	| Absence Type Select Change Event
	|---------------------------------------------------------------------------------
	*/
	projAbsenceTypeSelect : function(e){
		// Get Table Row Id
		row_id = $(e.currentTarget).closest('tr').attr('id');

		//  New Value From DOM
		var newAbsence = $.trim($(e.currentTarget).val());
		
		// Get Previous Value from Collection
		var previousValue = this.absenceCollection.get(row_id).get('tsAbsenceType');

		// Killer
		var isKillable = false;

		this.absenceCollection.each(function(a,b){
			if ( a.get('tsAbsenceType') == newAbsence ) {
					
					isKillable = true;

					// Set Previous Value 
					$(e.currentTarget).val(previousValue).trigger('change');

					$.notify({ message : 'This Absence Type is Already in Timesheet'},
						    	{ type : 'warning',
							   z_index : 10001 });

					return false;
			}
		})

		if (!isKillable) {
			// Set Value To Collection
			this.absenceCollection.get(row_id).set('tsAbsenceType', e.currentTarget.value);
		}
	},


	/*
	|---------------------------------------------------------------------------------
	| TS Project Select Change Event
	|---------------------------------------------------------------------------------
	*/
	projSelect : function(e){
		// Get Table Row id
		row_id = $(e.currentTarget).closest('tr').attr('id');
		
		// Get project_id From event
		var project_id = e.params.data.id;
		
		// Set project_id in mainCollection
		this.mainCollection.get(row_id).set('tsProject', project_id);

		// Get Project Manager From projects Select Option data attribute
		var projectManager = $(e.params.data.element).attr('data-manager');

		// Set Project Manager in mainCollection
		this.mainCollection.get(row_id).set('tsProjectManager', projectManager);

		// Set Manager Name in TS Project Row`s Manager Fieled
		$(e.currentTarget).closest('tr').find('#tsProjectManager').text(projectManager);

		// Get Need To Accept Data From projects Select Option data attribute
		var projectNeedToAccept = $(e.params.data.element).attr('data-needtoaccept');

		// Set Need To Accept in mainCollection
		this.mainCollection.get(row_id).set('tsRowNeedToAccept', projectNeedToAccept);

		// Clear Rows Projects Operation
		this.mainCollection.get(row_id).set('tsOperation', '00');		

		// Fill in Operations (Sub Projects) Drop Down
		this.axGetOperations(e);
	},


	/*
	|---------------------------------------------------------------------------------
	| Project Operation (Sub Project) Select change event
	|---------------------------------------------------------------------------------
	*/
	projOperationSelect : function(e){
		/*console.log(this.mainCollection);
		console.log(this.mainCollection.toJSON());
		_.each(this.mainCollection.toJSON(), function(){
		})*/

		// Get Table Row id From DOM
		var row_id = $(e.currentTarget).closest('tr').attr('id');
		
		//  New Value From DOM
		var newOperation = $.trim($(e.currentTarget).val());
		
		// Get Previous Value from Collection
		var previousValue = this.mainCollection.get(row_id).get('tsOperation');
		
		// Current Project value From Collection
		var currentProject = this.mainCollection.get(row_id).get('tsProject');;

		// Killer
		var isKillable = false;

		this.mainCollection.each(function(a,b){
			if ( a.get('tsProject') == currentProject ) {
				if ( a.get('tsOperation') == newOperation ) {
					
					$.notify({ message : 'This Projects Subproject is Already in Timesheet'},
						    	{ type : 'warning',
							   z_index : 10001 });

					isKillable=true;
					
					// Set Previous Value 
					$(e.currentTarget).val(previousValue).trigger('change');
					return false;
				}
			}
		})

		if (!isKillable) {
			// Set operation_id in mainCollection
			this.mainCollection.get(row_id).set('tsOperation', e.currentTarget.value);
		}
	},


	/*
	|---------------------------------------------------------------------------------
	| Week Day tsWD Value Change 
	|---------------------------------------------------------------------------------
	*/
	wdValueChange : function(e){
		//Get Table ID
		var tbody_id = $(e.currentTarget).closest('tbody').attr('id');
		//console.log(tbody_id);
		
		// VALIDATE Inserted WD Hour Value
		var valRes = this.validateWDHours(e.currentTarget.value);
		//console.log(valRes);

		if ( valRes.status == -2 ) {
			//Emptyness
			$.notify({ message : valRes.message},
				    	{ type : 'info',
					   z_index : 10001 });
			$(e.currentTarget).val(valRes.newVal);
			$(e.currentTarget).css('background-color','#f56954');
		}
		else if( valRes.status < 0 ){
			// Negative, string and so on 
			$(e.currentTarget).val(valRes.newVal);
			$(e.currentTarget).css('background-color','#f56954');
			$.notify({ message : valRes.message},
				    	{ type : 'danger',
					   z_index : 10001 });
		}
		else{
			$(e.currentTarget).css('background-color','#FFFFFF');
		}

		// Get Table Row id
		row_id = $(e.currentTarget).closest('tr').attr('id');

		// Check And Set values
		if (tbody_id=='tblMainTs') {
			// Set Changed Dates Value to Collection
			this.mainCollection.get(row_id).set(e.currentTarget.id, valRes.newVal);
		}
		else if(tbody_id=='tblAbsenceTs'){
			// Set Changed Dates Value to Collection
			this.absenceCollection.get(row_id).set(e.currentTarget.id, valRes.newVal);
		}
		//var closest_tr_id = $(e.currentTarget).closest('tr').attr('id');
		
		// Update Project Row Total
		//$(e.currentTarget).closest('tr').find('#tsProjectTotal').text(this.getRowSubtotal(tbody_id,closest_tr_id));
		
			// Update subtotal
		this.updateSubtotal(tbody_id, e.currentTarget.id);
	},

	/*
	|---------------------------------------------------------------------------------
	| ON TS WD Click Select Content
	|---------------------------------------------------------------------------------
	*/
	wdValueClick : function(e){
		$(e.currentTarget).select();
	},
	

	/*
	|---------------------------------------------------------------------------------
	| ts Row Comment filed Value Change Event
	|---------------------------------------------------------------------------------
	*/
	commentChange : function(e){
		// Get Table ID
		var tbody_id = $(e.currentTarget).closest('tbody').attr('id');
		// Get Table Row ID
		var row_id = $(e.currentTarget).closest('tr').attr('id');
		console.log(e.currentTarget.value);
		// Check And Set values
		if (tbody_id=='tblMainTs') {
			this.mainCollection.get(row_id).set('tsComment', e.currentTarget.value);
			//console.log(this.mainCollection.toJSON());
		}else if(tbody_id=='tblAbsenceTs'){
			this.absenceCollection.get(row_id).set('tsComment', e.currentTarget.value);
			//console.log(this.absenceCollection.toJSON());
		}
	},



	/*
	|---------------------------------------------------------------------------------
	| TimeSheet Week Pick Event
	|---------------------------------------------------------------------------------
	*/
	tsWeekChange : function(e){

		if ($(e.currentTarget).val() == "") {
			return;
		}
		var stamp=[];
		stamp['year'] = e.date.getFullYear();
		stamp['month'] = e.date.getMonth()+1;
		dateToPass = stamp['date'] = e.date.getDate();
		stamp['wDay'] = e.date.getDay();
		//console.log(year+'-'+month+'-'+date+'=>'+wDay);
		//console.log(stamp['date']);
		
		// Check For Sunday
		// In bootstrap Datepicker Week Starts on Sunday
		// Some Hack
		if (stamp['wDay']==0) {
			stamp['date'] = stamp['date']-6;
		}
		else{
			stamp['date'] = stamp['date']-stamp['wDay']+1
		}
		//console.log(stamp['date']);
		
		var startDate 	= new Date( stamp['year'], stamp['month']-1, stamp['date'] );
		var endDate 	= new Date( stamp['year'], stamp['month']-1, stamp['date']+6 );
		var startPeriod = startDate.getFullYear()+'-'+(startDate.getMonth()+1)+'-'+startDate.getDate();
		var endPeriod 	= endDate.getFullYear()+'-'+(endDate.getMonth()+1)+'-'+endDate.getDate();

		// Set start date to info model
		this.infoModel.set('weekStart', startPeriod );
		// Set end date to info model
		this.infoModel.set('weekEnd', endPeriod );
		
		self=this;

		$.ajax({
			url: '/'+App.myRoot+'/c_timesheets/ax_check_ts_date',
			type: 'get',
			data: {
				year: stamp['year'],
				month: stamp['month'],
				day: dateToPass
			},
			success: function(response){
					//console.log(data);
					if (response.status=='success') {

						$('#tsWeekNo').html('<label> Year: </label> <span> '+response.year+' </span> '+'<label> Week # </label><span>'+response.week_no+'</span>');
						//$('#tsPeriod').html('<label>Period: </label> <span> '+startPeriod+	'</span> => <span>'+endPeriod+'</span>');
						self.infoModel.set('year',response.year);
						self.infoModel.set('week',response.week_no);
						self.updateTableHeaders(stamp,reset=false);
						$(e.currentTarget).css('background-color','#00c0ef');
						//console.log(self.infoModel);
						$.notify({ message : response.message},
							    	{ type : 'info',
							   	   z_index : 10001 });
					}
					else
					{
						$.notify({ message : response.message},
							    	{ type : 'error',
								   z_index : 10001 });
						self.updateTableHeaders(false,reset=true);
						$(e.currentTarget).css('background-color','RED');
						return;
					}
			}//#success
		})//#ajax

	},

	/*
	|---------------------------------------------------------------------------------
	| Function For Updating Table Headers for Selected period of Dates
	|---------------------------------------------------------------------------------
	*/
	updateTableHeaders:function(stamp=false,reset=false){
		if (!reset || stamp) {
			// Filling Main And Absence Headers
			//console.log(stamp);
			for (var i = 0; i < 7; i++) {
				var tempDate = new Date( stamp['year'], stamp['month']-1, stamp['date']+i );	

				wdHeader = '<span>'+this.wds[i]+'</span><br/><span>'+this.months[tempDate.getMonth()]+''+tempDate.getDate()+'</span>'
				//console.log(tempDate.getDay());
				$('#tblTsActions thead#tblTsActionsMainHeader th#tsWDTh'+(i+1)).html( wdHeader );
				//$('#tblAbsenceTs thead th#tsWDTh'+(i+1)).html( wdHeader );
			}
		}
		else
		{
			// Filling Main And Absence Headers
			for (var i = 0; i < 7; i++) {
				wdHeader = '<span>WD'+(i+1)+'</span>'
				$('#tblTsActions thead#tblTsActionsMainHeader th#tsWDTh'+(i+1)).html(wdHeader);
				//$('#tblAbsenceTs thead th#tsWDTh'+(i+1)).html(wdHeader);
			}
		}
	},

	/*
	|---------------------------------------------------------------------------------
	| Function For Updating Table Footers for Selected period of Dates
	|---------------------------------------------------------------------------------
	*/
	updateTableFooters : function(){
		// Resetting Main And Absence Footers
		for (var i = 0; i < 7; i++) {
			$('#tblTsActions tr#tblMainTsFooter 		td span#tsSubTotaltsWD'+(i+1)).text('0');
			$('#tblTsActions tr#tblAbsenceTsFooter		td span#tsSubTotaltsWD'+(i+1)).text('0');
			$('#tblTsActions tfoot tr#tblGrandTotalFooter	td span#tsSubGrandTotaltsWD'+(i+1)).text('0');
			
		}
		// Reset Main Total
		$('#tblTsActions tr#tblMainTsFooter td span#tsTotal').text('');
		// Reset Absence Total
		$('#tblTsActions tr#tblAbsenceTsFooter td span#tsTotal').text('');
		//Reset Grand Total
		$('#tblTsActions tfoot tr td span#tsGrandTotal').text('');
	},



	/*
	|---------------------------------------------------------------------------------
	| Render New Main Row
	|---------------------------------------------------------------------------------
	*/
	renderTsMainRow : function(model){
		//console.log(model);
	return	this.$('#tblTsActions tbody#tblMainTs').append(this.templateMainRow(model))
	//	return this;
	},


	/*
	|---------------------------------------------------------------------------------
	| Render New Absence Row
	|---------------------------------------------------------------------------------
	*/
	renderTsAbsenceRow : function(model){
		this.$('#tblTsActions tbody#tblAbsenceTs').append(this.templateAbsenceRow(model))
		return this;
	},


	/*
	|---------------------------------------------------------------------------------
	| Add Row to MAIN Table
	|---------------------------------------------------------------------------------
	*/
	addTsMainRow : function(isEmptyRow=true, model=false) {
		if ( isEmptyRow ) {
			this.mainCounter+=1
			this.mainCollection.add({id:this.mainCounter});
			model = {id:this.mainCounter}

			var renderedRow = this.renderTsMainRow({model});
		} else {
			this.mainCollection.add(model);
			var renderedRow = this.renderTsMainRow({model});
		}


		//console.log(renderedRow);

		/*$('.myTsSelect2').select2({
			minimumResultsForSearch: Infinity
			
		});
		
		$('.myTsSelect2Searchable').select2({
			
		});*/
		
		$(renderedRow.selector +" tr#"+model.id+' td #tsProjActivityType').select2({});

		// $(renderedRow.selector + " tr#" + model.id +' td #tsProject').select2({
		// 	minimumResultsForSearch: Infinity
			
		// });
		// $(renderedRow.selector + " tr#" + model.id +' td #tsProject').next('span').css('display','none');
		
		/*$('#tsProjActivityType').select2({
			//minimumResultsForSearch: Infinity
			
		});
		
		$('.myTsSelect2Searchable').select2({
			
		});*/

	},

	/*
	|---------------------------------------------------------------------------------
	| Add Row to ABSENCE Table
	|---------------------------------------------------------------------------------
	*/
	addTsAbsenceRow : function(isEmptyRow=true, model=false){
		if ( isEmptyRow ) {
			
			this.absenceCounter+=1
			this.absenceCollection.add({id:this.absenceCounter});
			model = {id:this.absenceCounter}

		} else {

			this.absenceCollection.add(model);

		}
		
		this.renderTsAbsenceRow({model});

		$('.myTsSelect2').select2({
			minimumResultsForSearch: Infinity
		});
		
		$('.myTsSelect2Searchable').select2({
			
		});
	},


	/*
	|---------------------------------------------------------------------------------
	| Get Subtotal
	|---------------------------------------------------------------------------------
	*/
	getSubtotal : function(tbody_id, col_id){
		// Make Column an array of values
		tsWDColumn = $('#tblTsActions tbody#'+tbody_id+' tr td input#'+col_id).map(function(){
			return $(this).val();
		}).get();

		// Summarize
		var sum = 0;
		for( var i=0; i < tsWDColumn.length; i++ ){
			sum+= parseFloat(tsWDColumn[i]);
		}

		return sum;
	},

	/*
	|---------------------------------------------------------------------------------
	| Get Total For Specified Row
	|---------------------------------------------------------------------------------
	*/
	getRowSubtotal : function(tbody_id, row_id){
		var row_sum = 0;
		for (var i = 1; i <= 7; i++) {
			row_sum+= parseFloat($('#tblTsActions tbody#'+tbody_id+' tr td input#tsWD'+i).val());
		}

		return row_sum;
	},


	/*
	|---------------------------------------------------------------------------------
	| Update Subtotal on Requested Table Column
	|---------------------------------------------------------------------------------
	*/
	updateSubtotal : function(tbody_id, col_id){
		// Get Sum
		var subtotal = this.getSubtotal(tbody_id, col_id);
		// Update Subtotal
		$('#tblTsActions #'+tbody_id+'Footer td span#tsSubTotal'+col_id).text(subtotal);
		this.updateGrandSubtotal(col_id);
		// Update TS Main Total
		this.updateTotal(tbody_id);
	},

	/*
	|---------------------------------------------------------------------------------
	| Update GrandSubtotal on Requested Table Column
	|---------------------------------------------------------------------------------
	*/
	updateGrandSubtotal : function(col_id){
		// Get Grand Subtotal Sum Main
		var grSubMainCol = this.getSubtotal('tblMainTs', col_id);

		// Get Grand Subtotal Sum Absence
		var grSubAbsenceCol = this.getSubtotal('tblAbsenceTs', col_id);
	
		// Grand Subtotal		
		var grSubtotal = grSubMainCol + grSubAbsenceCol;
		var subtotalElement = $('#tblTsActions tfoot tr#tblGrandTotalFooter td span#tsSubGrandTotal'+col_id);
		if (grSubtotal > 8 && grSubtotal <= 12) 
		{
			subtotalElement.css('color','DARKORANGE');
		}
		else if(grSubtotal > 12)
		{
			subtotalElement.css('color','RED');
		}
		else
		{
			subtotalElement.css('color','BLACK');	
		}
		// Update Subtotal
		$('#tblTsActions tfoot tr#tblGrandTotalFooter td span#tsSubGrandTotal'+col_id).text(grSubtotal);
	},

	/*
	|---------------------------------------------------------------------------------
	| Get Total
	|---------------------------------------------------------------------------------
	*/
	getTotal : function(table_id){
		// Get SubTotals
		subTotals = $('#tblTsActions #'+table_id+'Footer td span[id^=\'tsSubTotal\']').map(function(){
			return $.trim($(this).text());
		}).get();

		total = 0
		// Calculate Total
		for (var i = 0; i < subTotals.length; i++) {
			total += parseFloat(subTotals[i]);
		}

		return total;
	},
	/*
	|---------------------------------------------------------------------------------
	| Update Total For Requested Table
	|---------------------------------------------------------------------------------
	*/
	updateTotal : function(tbody_id, total=false){

		if (!total) {
			total = this.getTotal(tbody_id);
		}

		// Set Table Total
		$('#tblTsActions #'+tbody_id+'Footer td span#tsTotal').text(total);
		this.updateGrandTotal();
	},

	/*
	|---------------------------------------------------------------------------------
	| Get Grand Total
	|---------------------------------------------------------------------------------
	*/
	getGrandTotal : function(){
		// Get SubTotals
		subTotalsMain = $('#tblMainTsFooter td span[id^=\'tsSubTotal\']').map(function(){
			return $.trim($(this).text());
		}).get();
		sumMain = 0
		// Calculate Total
		for (var i = 0; i < subTotalsMain.length; i++) {
			sumMain += parseFloat(subTotalsMain[i]);
		}

		subTotalsAbsence = $('#tblAbsenceTsFooter td span[id^=\'tsSubTotal\']').map(function(){
			return $.trim($(this).text());
		}).get();

		sumAbsence = 0
		// Calculate Total
		for (var i = 0; i < subTotalsAbsence.length; i++) {
			sumAbsence += parseFloat(subTotalsAbsence[i]);
		}
		 
		return sumMain+sumAbsence;
	},

	/*
	|---------------------------------------------------------------------------------
	| Set Grand Total
	|---------------------------------------------------------------------------------
	*/
	updateGrandTotal : function(){

		grandTotal = this.getGrandTotal();
		grandTotalElement = $('#tblTsActions tfoot tr td span#tsGrandTotal');
		if ( (grandTotal) > 48) 
		{
			$('#tsGrandTotal').css('color','RED');
			grandTotalElement.css('color','RED')
		}
		else if( (grandTotal) <= 48 && (grandTotal) > 40 )
		{
			$('#tsGrandTotal').css('color','DARKORANGE');
			grandTotalElement.css('color','DARKORANGE')	
		}
		else
		{
			$('#tsGrandTotal').css('color','BLACK');
			grandTotalElement.css('color','BLACK')
		}
		grandTotalElement.text(grandTotal)
		$('#tsGrandTotal').text(grandTotal);
	},
	/*
	|---------------------------------------------------------------------------------
	|Remove Row From Table
	|---------------------------------------------------------------------------------
	*/
	removeRow : function(e){
		// Remove Row
		var tbody_id = $(e.currentTarget).closest('tbody').attr('id');
		var row_id = $(e.currentTarget).closest('tr').attr('id');

		if (tbody_id=='tblAbsenceTs') {
			this.absenceCollection.remove({id:row_id});
			//console.log(this.absenceCollection.toJSON());
		}else if(tbody_id=='tblMainTs'){
			this.mainCollection.remove({id:row_id});
			//console.log(this.mainCollection.toJSON());
		}

		$(e.currentTarget).closest('tr').remove();
		
		// Recheck Subtotals
		this.reCheckSubtotals(tbody_id);
	},

	reCheckSubtotals : function(tbody_id){
		// Recheck Subtotals
		this.updateSubtotal(tbody_id,'tsWD1');
		this.updateSubtotal(tbody_id,'tsWD2');
		this.updateSubtotal(tbody_id,'tsWD3');
		this.updateSubtotal(tbody_id,'tsWD4');
		this.updateSubtotal(tbody_id,'tsWD5');
		this.updateSubtotal(tbody_id,'tsWD6');
		this.updateSubtotal(tbody_id,'tsWD7');

		// Update TS Main Total
		this.updateTotal(tbody_id);
	},

	/*
	|---------------------------------------------------------------------------------
	| Get Operations From DB For Selected Project
	|---------------------------------------------------------------------------------
	*/
	axGetOperations : function(e){
		
		// Get Table Row id
		//row_id = $(e.currentTarget).closest('tr').attr('id');
		
		// Get project_d From event
		var project_id = e.params.data.id;

		// Get tsOperation Element For Filling Via Ajax Request
		var closestTsOperation = $(e.currentTarget).closest('tr').find('#tsOperation');

		//console.log(this.mainCollection.toJSON());
		
		// Get Operations for Selected Project
		// And Fill tsOperations Drop Down
		$.ajax({
			url: '/'+App.myRoot+'/c_timesheets/get_pr_operations',
			type: 'get',
			data: {
					project_id: project_id
			},
			success: function(data){
					//console.log(data);
					closestTsOperation.html('<option value="00" selected="" disabled=""></option>  ');
					closestTsOperation.select2({data:data,dropdownAutoWidth: false}).trigger('change');
			},//#success
			error: function(data){
					$.notify({ message : 'Cannot Retreive Operations.'},
							    	{ type : 'error',
								   z_index : 10001 });
			}//#error
			 
		});//#ajax
	},
	
	
	preventEnter : function(e){
			if (e.which === 13) {
			e.preventDefault();
			return;
		}
	},
	
	/*
	|---------------------------------------------------------------------------------
	| Validate Time
	|---------------------------------------------------------------------------------
	*/
	validateWDHours: function(value)
	{
		var validation=[]
		//console.log(value);
		if( $.trim(value) == "" )
		{
			validation['status'] = -2;
			validation['message'] = 'Field Cannot be Empty, Autoset to "0"';
			validation['newVal'] = 0;
			return validation;
		}

		if( !value.toString().match(/^[0-9]*(?:\.\d{1,2})?$/) )
		{
			validation['status'] = -1;
			validation['message'] = 'Must Be Positive, Numeric Value like 2, 3.5, 8.0... ';
			validation['newVal'] = 0;
			return validation;
		}
		else
		{
			value = +value;

			if( value >= 0)
			{
				validation['status'] = '1';
				validation['message'] = '';
				validation['newVal'] = value;
				return validation;
			}
			else
			{
				validation['status'] = -3;
				validation['message'] = 'Must Be Positive, Numeric Value (1)';
				validation['newVal'] = 0;
				return validation;
			}
		}	
	},// #validateWDHours

	/*
	|---------------------------------------------------------------------------------
	| Validate Date Total 
	|---------------------------------------------------------------------------------
	*/
	validateDateTotal : function(){
		var colTotal={};

		colTotal.tsWD1 = 0;
		colTotal.tsWD2 = 0;
		colTotal.tsWD3 = 0;
		colTotal.tsWD4 = 0;
		colTotal.tsWD5 = 0;
		colTotal.tsWD6 = 0;
		colTotal.tsWD7 = 0;
		self = this;
		_.each(this.mainCollection.models, function(colCell){
				//console.log(colCell.get('tsWD1'));
				colTotal.tsWD1 += self.validateWDHours(colCell.get('tsWD1'))['newVal'];
				colTotal.tsWD2 += self.validateWDHours(colCell.get('tsWD2'))['newVal'];
				colTotal.tsWD3 += self.validateWDHours(colCell.get('tsWD3'))['newVal'];
				colTotal.tsWD4 += self.validateWDHours(colCell.get('tsWD4'))['newVal'];
				colTotal.tsWD5 += self.validateWDHours(colCell.get('tsWD5'))['newVal'];
				colTotal.tsWD6 += self.validateWDHours(colCell.get('tsWD6'))['newVal'];
				colTotal.tsWD7 += self.validateWDHours(colCell.get('tsWD7'))['newVal'];
		});

		_.each(this.absenceCollection.models, function(colCell){
			//console.log(colCell.get('tsWD1'));
				colTotal.tsWD1 += self.validateWDHours(colCell.get('tsWD1'))['newVal'];
				colTotal.tsWD2 += self.validateWDHours(colCell.get('tsWD2'))['newVal'];
				colTotal.tsWD3 += self.validateWDHours(colCell.get('tsWD3'))['newVal'];
				colTotal.tsWD4 += self.validateWDHours(colCell.get('tsWD4'))['newVal'];
				colTotal.tsWD5 += self.validateWDHours(colCell.get('tsWD5'))['newVal'];
				colTotal.tsWD6 += self.validateWDHours(colCell.get('tsWD6'))['newVal'];
				colTotal.tsWD7 += self.validateWDHours(colCell.get('tsWD7'))['newVal'];
		});

		var is_validated = {};
		var grandTotal = 0;
		//var i = 0;
		_.each(colTotal, function(col,id){
			grandTotal+=col;

			if ( col >= 8 && col <= 18 ) 
			{
				is_validated[id] = true;
			}
			else
			{
				is_validated[id] = false;
			}

			//i++;
		});

		if ( grandTotal>=40 && grandTotal<=77) {
			is_validated['gTotal'] = true;
		}
		else{
			is_validated['gTotal'] = false;
		}

		return is_validated;
	},// #validateDateTotal

	/*
	|---------------------------------------------------------------------------------
	| Validate requireds
	|---------------------------------------------------------------------------------
	*/
	validateRequireds : function(){
		colValidate = [];
		colValidate['main'] = [];
		colValidate['absence'] = [];

		//console.log(this.mainCollection.models);
		_.each(this.mainCollection.models, function(colCell,idx){
				
				colValidate['main'][idx] = []
				colValidate['main'][idx]['row'] = colCell.get('id');
				colValidate['main'][idx]['is_validated'] = true;
				
				if (colCell.get('tsActivityType') == '00') 
				{
					colValidate['main'][idx]['is_validated'] = false;
					colValidate['main'][idx]['tsActivityType'] = false;
				}
				else
				{
					colValidate['main'][idx]['tsActivityType'] = true;
					//colValidate['main'][idx]['tsProjActivityType']=true;
				}

				if ( colCell.get('tsActivityTypeCode') == 'at1' || colCell.get('tsActivityTypeCode') == 'at2' ) 
				{
					if (colCell.get('tsProject') == '00') 
					{
						colValidate['main'][idx]['is_validated'] = false;
						colValidate['main'][idx]['tsProject']=false;
					}
					else
					{
						colValidate['main'][idx]['tsProject']=true;
					}

					if (colCell.get('tsOperation') == '00') 
					{
						colValidate['main'][idx]['is_validated'] = false;
						colValidate['main'][idx]['tsOperation']=false;
					}
					else
					{
						colValidate['main'][idx]['tsOperation']=true;
					}
				}
				else if ( colCell.get('tsActivityTypeCode') == 'at3' || colCell.get('tsActivityTypeCode') == 'at4' || colCell.get('tsActivityTypeCode') == 'at5' || colCell.get('tsActivityTypeCode') == 'at6' )
				{
					if ($.trim(colCell.get('tsComment')) == '') 
					{
						colValidate['main'][idx]['is_validated'] = false;
						colValidate['main'][idx]['tsComment']=false;
					}
					else
					{
						colValidate['main'][idx]['tsComment']=true;
					}					
				}
				else
				{
					colValidate['main'][idx]['is_validated'] = false;
					colValidate['main'][idx]['tsActivityType'] = false;
				}

				
			});

		_.each(this.absenceCollection.models, function(colCell,idx){

				colValidate['absence'][idx] = []
				colValidate['absence'][idx]['row'] = colCell.get('id');
				colValidate['absence'][idx]['is_validated'] = true;
				
				if (colCell.get('tsAbsenceType') == '00') 
				{
					colValidate['absence'][idx]['is_validated'] = false;
					colValidate['absence'][idx]['tsAbsenceType'] = false;
				}
				else
				{
					colValidate['absence'][idx]['tsAbsenceType'] = true;
				}
			});

		return colValidate;

	}//#validateRequireds


});

/*
|---------------------------------------------------------------------------------
| Accept TS
|---------------------------------------------------------------------------------
*/

App.Views.acceptTimesheet = Backbone.View.extend({
	el:'#tsPendingsDiv',

	events: {
		'click #tblTsPendings .tsFullAccept' 			: 'tsFullAcceptReject',
		'click #tblTsPendings .tsFullReject' 			: 'tsFullAcceptReject',
		'click #tblTsPendings .tsFullView' 	 			: 'tsFullAcceptReject',
		'click #tblTsPendings .tsFullRejectAcceptBtn' 	: 'tsFullAcceptReject',
		'click #actFrTSDetails .tsFullAcceptFrDet'		: 'tsFullAcceptReject',
		'click #actFrTSDetails .tsFullRejectFrDet'		: 'tsFullAcceptReject',
		'click #actFrTSDetails .tsFullRejectAcceptBtnFrDet': 'tsFullAcceptReject',
		
		/*'click #testbtn'  			: 'testclick',*/

		'click #tblTsPendings .tsRowAccept'  			: 'tsRowAcceptReject',
		'click #tblTsPendings .tsRowReject'  			: 'tsRowAcceptReject',
		'click #tblTsPendings .tsRowView' 	 			: 'tsRowAcceptReject',
		'click #tblTsPendings .tsRowRejectAcceptBtn' 	: 'tsRowAcceptReject'


	},

/*	testclick: function(){
		//alert('poop');
		$.notify({ message : 'response.message'},
							    	{ type : 'success',
							    	placement:{align: 'left',
							    				from:'bottom'},
							   	   z_index : 10001 });
	},*/

	initialize : function(){

	},

	tsFullAcceptReject : function(e){
		$('[data-toggle=popover]').not(e.currentTarget).popover('hide');
		//e.preventDefault();
		// action variable
		// 1- accept
		// 2- reject
		var action = 0;
		var rej_comment = "";
		var from_details = false;
		// Check for request source(pendings or details)
		if ( $(e.currentTarget).attr('name') == 'tsFullAcceptFrDet' || $(e.currentTarget).attr('name') == 'tsFullRejectAcceptBtnFrDet' || $( e.currentTarget).attr('name') == 'tsFullRejectFrDet') 
		{
			var ts_id = $(e.currentTarget).attr('data-tsid');
			var user_id = $(e.currentTarget).attr('data-userid');
			from_details = true;
		}
		else
		{
			var closest_tr = $(e.currentTarget).closest('tr');
			var user_id = closest_tr.find('#tsPendingUserId').attr('data-userid');
			var ts_id = closest_tr.attr('id');
			//return;
		}

		if ( $( e.currentTarget).attr('name') == 'tsFullAccept' || $( e.currentTarget).attr('name') == 'tsFullAcceptFrDet' ) {
				action = 1;
		} else if( $( e.currentTarget).attr('name') == 'tsFullReject' || $( e.currentTarget).attr('name') == 'tsFullRejectFrDet' ) {
				$(e.currentTarget).popover('toggle');
				//action = 2;
				return;
		} else if( $( e.currentTarget).attr('name') == 'tsFullView' ) {
				appRouter.navigate("timesheetdetails/"+user_id+'/'+ts_id+'/p', {trigger:true});
				return;
		} else if( $( e.currentTarget).attr('name') == 'tsFullRejectAcceptBtn' || $( e.currentTarget).attr('name') == 'tsFullRejectAcceptBtnFrDet' ) {
				
				action = 2;
				rej_comment = $(e.currentTarget).closest('div').find('.tsFullRejectComment').val();
				if ($.trim(rej_comment) == "") {
					$.notify({ message : 'Comment Field is Required.'},
							    	{ type : 'error',
								   z_index : 10001 });
					return;
				}
		} else {
			return;
		}
		
		console.log('user_id: '+user_id+' ts_id:'+ts_id+'comment:'+rej_comment);
		//return;
	
		mywindow = window;
		$.ajax({
			url: '/'+App.myRoot+'/c_timesheets/ax_ts_actions',
			type: 'get',
			data: {
				id 		: ts_id,
				user_id : user_id,
				action 	: action,
				comment : rej_comment
			},
			success: function(response){
					
					//console.log(response.status);
					if (response.status == 'success') {

						$.notify({ message : response.message},
							    	{ 
							    	  type : 'success',
							     placement :{ 
							     				align: 'left',
							    				from:'bottom'
							    			},
							   	   z_index : 10001 });

						$(e.currentTarget).closest('tr').css('background-color','LIMEGREEN');
						mywindow.setTimeout(function(){},30000);
						$(e.currentTarget).closest('tr').remove();
						_.each( $('#tblTsPendings tbody tr'), function(row, id) {
							$(row).find('#numbering').html(id+1);
						})

						if ( from_details ) 
						{
							$('#actFrTSDetails').remove();
							$('#tsUserTSInfo tbody tr td span#tsUserTSInfoStatus').text(response.ts_status_info.status);
							$('#tsHistory tbody').prepend('<tr>'+'<td>'+response.ts_status_info.action_date+'</td>'
																+'<td>'+response.ts_status_info.touched_object+'</td>'
																+'<td>'+response.ts_status_info.user+'</td>'
																+'<td>'+response.ts_status_info.status+'</td>'
																+'<td>'+response.ts_status_info.comment+'</td>'+'</tr>')
							return;
						}
						
					}
					else
					{
						$.notify({ message : response.message},
							    	{ type : 'error',
								   z_index : 10001 });
						$(e.currentTarget).css('background-color','RED');
						return;
					}
			},//#success
			error: function(response){
				//console.log(response.responseJSON.status);
				if (response.responseJSON.status == 'failure') {

						$.notify({ message : response.responseJSON.message},
							    	{ type : 'error',
								   z_index : 10001 });
						return;
						
					}
					else
					{
					$.notify({ message : response.responseJSON.message},
				    	{ type : 'error',
					   z_index : 10001 });
					}
			}
		})//#ajax
	},


	tsRowAcceptReject : function(e){

		// Closing Popovers that are opened
		$('[data-toggle=popover]').not(e.currentTarget).popover('hide');

		//e.preventDefault();
		// action variable
		// 2- accept
		// 3- reject()
		ts_project_action = 0;
		
		var closest_tr 		= $(e.currentTarget).closest('tr');
		var ts_id 			= closest_tr.attr('id');
		var ts_project_id 	= closest_tr.attr('data-projectid');
		var ts_operation_id = closest_tr.attr('data-operationid');
		var rej_comment		= '';
		var user_id 		= closest_tr.find('#tsPendingUserId').attr('data-userid');
		
		if ( $( e.currentTarget).attr('name') == 'tsRowAccept' ) {
				ts_project_action = 2;
		} else if( $( e.currentTarget).attr('name') == 'tsRowReject' ) {
				$(e.currentTarget).popover('toggle');
				//ts_project_action = 3;
				return;
		} else if( $( e.currentTarget).attr('name') == 'tsRowView' ) {
				$(e.currentTarget).popover('toggle');
				//appRouter.navigate("timesheetdetails/"+user_id+'/'+ts_id, {trigger:false});
				return;
		} else if( $( e.currentTarget).attr('name') == 'tsRowRejectAcceptBtn' ) {
				//$(e.currentTarget).popover('toggle');
				ts_project_action = 3;
				rej_comment = $(e.currentTarget).closest('div').find('.tsRowRejectComment').val();
				if ($.trim(rej_comment) == "") {
					$.notify({ message : 'Comment Field is Required.'},
							    	{ type : 'error',
								   z_index : 10001 });
					return;
				}
		} else {
				return;
		}
				

		mywindow = window;
		$.ajax({
				 url: '/'+App.myRoot+'/c_timesheets/ax_ts_project_actions',
				type: 'get',
				data: {
						 id : ts_id,
					user_id : user_id,
				 project_id : ts_project_id,
			   operation_id : ts_operation_id,
					 action : ts_project_action,
					 comment: rej_comment
				},
			success: function(response){
					
					//console.log(response.status);
					if (response.status == 'success') {

						$.notify({ message : response.message},
							    	{ type : 'success',
							   	   z_index : 10001 });

						$(e.currentTarget).closest('tr').css('background-color','LIMEGREEN');
						mywindow.setTimeout(function(){},3000);
						$(e.currentTarget).closest('tr').remove();
						_.each( $('#tblTsPendings tbody tr'), function(row, id) {
							$(row).find('#numbering').html(id+1);
						})
						
					}
					else
					{
						$.notify({ message : response.message},
							    	{ type : 'error',
								   z_index : 10001 });
						$(e.currentTarget).css('background-color','RED');
						return;
					}
			},//#success
			error: function(response){
				//console.log(response.responseJSON.status);
				if (response.responseJSON.status == 'failure') {

						$.notify({ message : response.responseJSON.message},
							    	{ type : 'error',
								   z_index : 10001 });
						return;
						
					}
					else
					{
					$.notify({ message : response.responseJSON.message},
				    	{ type : 'error',
					   z_index : 10001 });
					}
			}
		})//#ajax
	}

});

