/*
|---------------------------------------------------------------------------------
|Initing Projects Grid System
|---------------------------------------------------------------------------------
*/
App.Views.initProject = Backbone.View.extend({
	
	initialize : function(){
		
		vent.on('project:details', this.detailsItem, this);
		vent.on('project:edit', this.editItem, this);
		vent.on('project:delete', this.deleteItem, this);

		var projectCollection = new App.Collections.Projects();
		// Fetch Values From Server
		projectCollection.fetch();
		
		// Initing Create Button 
		var createbutton = new App.Views.CreateProjectModal({collection: projectCollection});
		// Generating Actions Cell 
		actGenedCell = this.generateActions({collection: projectCollection});
		// Generate and Show Grid		
		this.generateGrid({ collection: projectCollection, actionsCell:actGenedCell });
		// Generate and Show Pagination
		this.generatePaginator({ collection: projectCollection});
		// Generate and Show Search Field(Filter)
		this.generateFilter({ collection: projectCollection});
	},


	//Project Details Page Redirect
	//############################
	detailsItem : function(rowmodel){
		appRouter.navigate("projectdetails/"+rowmodel.get('id'),{trigger:true});
		//window.location.href(App.myroot + '/projectdetails' + rowmodel.get('id'));
		console.log("projectdetails/"+rowmodel.get('id'));
		//Bacbone.history.navogate("projectdetails/"+rowmodel.get('id'));
	},

	//Edit Project Modal Render and Show Event Triggering
	//####################################################
	editItem: function(rowmodel){
		//appRouter.navigate("#projects/edit");
		var editModalView = new App.Views.EditProjectModal({model: rowmodel});
		
		$("#projectModalDiv").html(editModalView.el);
		
		$('.mySumoSelectEdit').SumoSelect({
			okCancelInMulti: true,
			search:true
		});

		$('#projectTeamEdit').SumoSelect({
			okCancelInMulti: true,
			search:true
		});


		$('.myDatePicker').datepicker({
			format: "yyyy-mm-dd",
			weekStart:1,
			daysOfWeekHighlighted: "0,6",
		    calendarWeeks: true,
		    autoclose: true,
		    todayHighlight: true,
			todayBtn: "linked"
		});

		//Setting Cursor to Name Field
		$('#mdlEditProject').on('shown.bs.modal', function () {
	    	$('#projectNameEdit').focus();
		});
		

		// Set Selected Assignment
		$('#projectAssignmentEdit')[0].sumo.selectItem(rowmodel.get('ass_id'));

		// Set Selected Client
		$('#projectClientEdit')[0].sumo.selectItem(rowmodel.get('client_id'));

		// Set Selected Manager
		$('#projectManagerEdit')[0].sumo.selectItem(rowmodel.get('manager_id'));

		// Set Selected Status
		$('#projectStatusEdit')[0].sumo.selectItem(rowmodel.get('status_id'));

		// Set Selected APT Status
		$('#projectAptStatusEdit')[0].sumo.selectItem(rowmodel.get('apt_status_id'));

		// Check And Fill Project Team SumoSelect
		if (rowmodel.get('team_ids_str')) {
			var entireTeamIds = rowmodel.get('team_ids_str').split(",");
			
			$.each( entireTeamIds, function(idx,item){
				$('#projectTeamEdit')[0].sumo.selectItem($.trim(item));
			});
		}


		// Showing Bootstrap Modal
		$("#mdlEditProject").modal('toggle');
	},

	//Delete Project Confirmation Event Triggering
	//#############################################
	deleteItem: function(rowmodel){
		//console.log('vend deleting');
		//console.log('editing '+this.model.get('name'));
		var confirmDelModalView = new App.Views.DeleteProjectModal({model: rowmodel});
		//console.log(confirmDelModalView.el);

		$("#projectDelConfModalDiv").html(confirmDelModalView.el);
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
			columns:[/*{
					name: 'id',
					label: 'ID',
					editable: false,
					cell: Backgrid.IntegerCell.extend({
		      		orderSeparator: ''
					})},*/{
					name: 'name',
					label: 'Project',
					editable: false,
					cell: 'string'
				},{
					name: 'code',
					label: 'Code',
					editable: false,
					cell: 'string'
				},/*{
					name: 'email',
					label: 'Email',
					editable: false,
					cell: 'string'
				},*/{
					name: 'client',
					label: 'Client',
					editable: false,
					cell: 'string'
				},{
					name: 'assignment',
					label: 'Assignment',
					editable: false,
					cell: 'string'
				},{
					name: 'project_status',
					label: 'Status',
					editable: false,
					cell: 'string'
				},/*{
					name: 'address',
					label: 'Address',
					editable: false,
					cell: 'string'
				},*//*{
					name: 'tin',
					label: 'Tax Code',
					editable: false,
					cell: 'string'
				},{
					name: 'reg_num',
					label: 'Reg Num',
					editable: false,
					cell: 'string'
				},*/{
					name: '1',
					label: 'Actions',
					editable: false,
					sortable: false,
					cell: params.actionsCell
					
				}],

			//Data Collection For Table 
			collection: params.collection
			
		});//#projectGrid
		
		$('#gridProjects').append(grid.render().el);
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
		$('#gridProjects').after(paginator.render().el);
		return paginator;
	},//#generatePaginator
	

	// Generate Filter
	//##############
	generateFilter : function(params){
		var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
			  	collection: params.collection,
			  	// the name of the URL query parameter
			  	name: "q",
			  	placeholder: "Search Project" // HTML5 placeholder for the search box
			});//#serverSideFilter

		//Appending Searchbar
		$("#gridProjects").before(serverSideFilter.render().el);
		$("#gridProjects").before($("#btnCreateProject"));
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
				vent.trigger('project:details', this.model);
			},

			editRow : function(e){
				e.preventDefault();
				vent.trigger('project:edit', this.model);
			},
			deleteRow : function(e){
				e.preventDefault();
				vent.trigger('project:delete', this.model);
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
|Create Project Modal
|---------------------------------------------------------------------------------
*/
App.Views.CreateProjectModal = Backbone.View.extend({
	el: '#dCreateProject',
	events: {
		'click button#sbmtProjectCreate': 'createProject',
		'keydown input#projectName': 'preventEnter'
	},

	formish: {},
	initialize : function(){
		$('#projectAssignment').SumoSelect({
			//okCancelInMulti: true,
			search:true
		});

		$('#projectClient').SumoSelect({
			//okCancelInMulti: true,
			search:true
		});
		
		$('#projectManager').SumoSelect({
			okCancelInMulti: true,
			search:true
		});

		$('#projectTeam').SumoSelect({
			okCancelInMulti: true,
			search:true
		});

		$('#projectStatus').SumoSelect({
			okCancelInMulti: true,
			search:true
		});

		$('#projectAptStatus').SumoSelect({
			okCancelInMulti: true,
			search:true
		});
		

		$('.myDatePicker').datepicker({
			format: "yyyy-mm-dd",
			weekStart:1,
			daysOfWeekHighlighted: "0,6",
		    calendarWeeks: true,
		    autoclose: true,
		    todayHighlight: true,
			todayBtn: "linked"
		});

		this.render();
		this.initCreateModal();

	},

	initCreateModal : function(){
		this.formish.projectName 		= $( "#projectName");
 		this.formish.projectCode 		= $( "#projectCode");
 		this.formish.projectAgrSD 		= $( "#projectAgrSD");
 		this.formish.projectAgrED 		= $( "#projectAgrED");
 		this.formish.projectActSD 		= $( "#projectActSD");
 		this.formish.projectActED 		= $( "#projectActED");
 		this.formish.projectStatus		= $( "#projectStatus");
 		this.formish.projectNote 		= $( "#projectNote");
 		this.formish.projectIsVisible 	= $( "#projectIsVisible");
		this.formish.projectAssignment	= $( "#projectAssignment");
		this.formish.projectClient		= $( "#projectClient ");
		this.formish.projectManager		= $( "#projectManager");
		this.formish.projectStatus		= $( "#projectStatus");
		//this.formish.projectAptStatus	= $( "#projectAptStatus");
	},

	emptyCreateModal : function(form){
		this.formish.projectName.val('');
		this.formish.projectCode.val('');
		this.formish.projectAgrSD.val('');
		this.formish.projectAgrED.val('');
		this.formish.projectActSD.val('');
		this.formish.projectActED.val('');
		this.formish.projectStatus.val('');
		this.formish.projectNote.val('');
		this.formish.projectIsVisible.prop('checked',false);
	},
	preventEnter : function(e){
		if (e.which === 13) {
		e.preventDefault();
			return;
		}
	},

	createProject : function(e){
		e.preventDefault();
		 
		console.log('create');
		projectForm = {};
		
		projectForm['name'] 	 	 = this.formish.projectName.val();
		projectForm['code'] 	 	 = this.formish.projectCode.val();
		projectForm['agrSD'] 	 	 = this.formish.projectAgrSD.val();
		projectForm['agrED'] 	 	 = this.formish.projectAgrED.val();
		projectForm['actSD']		 = (this.formish.projectActSD.val())? this.formish.projectActSD.val():'1901-01-01';
		projectForm['actED']		 = (this.formish.projectActED.val())? this.formish.projectActED.val():'1901-01-01';
		//projectForm['actED'] 	 	 = this.formish.projectActED.val();
		projectForm['status'] 	 	 = this.formish.projectStatus.val();
		projectForm['note'] 	 	 = this.formish.projectNote.val();
		projectForm['is_visible'] 	 = this.formish.projectIsVisible.val();
 
		projectForm['ass_id'] 	 		 = $.trim( $( 'option:selected',this.formish.projectAssignment ).val());
		projectForm['assignment'] 		 = $.trim( $( 'option:selected',this.formish.projectAssignment ).text());
		projectForm['client_id'] 		 = $.trim( $( 'option:selected',this.formish.projectClient ).val());
		projectForm['client']	 		 = $.trim( $( 'option:selected',this.formish.projectClient ).text());
		projectForm['manager_id'] 		 = $.trim( $( 'option:selected',this.formish.projectManager ).val());
		projectForm['manager']	 		 = $.trim( $( 'option:selected',this.formish.projectManager ).text());
		projectForm['project_status_id'] = $.trim( $( 'option:selected',this.formish.projectStatus ).val());
		projectForm['project_status']	 = $.trim( $( 'option:selected',this.formish.projectStatus ).text());
		//projectForm['aptStatus'] 	 = $.trim( $( 'option:selected',this.formish.projectAptStatus ).val());
		//projectForm['aptStatusTxt']	 = $.trim( $( 'option:selected',this.formish.projectAptStatus ).text());
		
		projectForm['teamIds'] 	 	 	 = $( "#projectTeam").val();
		
		/*
		USER TEAM CHECK
		*/

		// Get Selected Team Users Text and Put It in Array
		var team=[];
		$( "#projectTeam option:selected" )
				.each( function(i,item ){
					team.push( $(item).text() );
		});

		// Generate String of Team Names separated with comma ","
		projectForm['team_names'] = team.toString();

		// Generate String of Team IDs separated with comma ","
		if (projectForm['teamIds']) 
		{
			projectForm['team_ids_str'] = projectForm['teamIds'].toString();
		}

		
		// Project Visibility Value Check
		if ( this.formish.projectIsVisible.is(':checked') ) {
			projectForm['is_visible'] = 1;
		}
		else{
			projectForm['is_visible'] = 0;
		}
		
		// this to self		
		var self = this;

		this.collection.create( new App.Models.Project(projectForm), 
				{
					wait:true,
					success: function(model,response){
						//Showing Success Message
						$.notify({ message : "<b>* "+model.get('name')+" *</b>"+" Created Successfully."},
								    { type : 'success',
								   z_index : 10000 });
						
						//empty tag values
						self.emptyCreateModal( self.formish );
						$('#projectAssignment')[0].sumo.unSelectAll();
						$('#projectClient')[0].sumo.unSelectAll();
						$('#projectManager')[0].sumo.unSelectAll();
						$('#projectTeam')[0].sumo.unSelectAll();
						$('#projectStatus')[0].sumo.unSelectAll();
						/*$('#projectBtype')[0].sumo.unSelectAll();*/
						
						//Closing Modal
						$('#mdlCreateProject').modal('hide');
					},
					error: function(model,response){
						//Showing Success Message
						console.log(response);
						$.notify({ message : response.responseJSON.message},
								    { type : 'danger',
								   z_index : 10000 });
					}
				});//#collection.create 

	}//#createProject

});



/*
|---------------------------------------------------------------------------------
|Edit Project View
|---------------------------------------------------------------------------------
*/
App.Views.EditProjectModal = Backbone.View.extend({
	
	template: App.template('tmplProjectEditModal'),

	events: {
		"click button#sbmtProjectEdit": 'submitProjectEdit'
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
	submitProjectEdit : function(e){
		e.preventDefault();
		var save_values = {};
		console.log('update Submit');
		
		save_values['name'] 			 = $( "#projectNameEdit" ).val();
 		save_values['code'] 			 = $( "#projectCodeEdit" ).val();
 		save_values['agrSD'] 			 = $( "#projectAgrSDEdit" ).val();
 		save_values['agrED'] 			 = $( "#projectAgrEDEdit" ).val();
 		save_values['actSD'] 			 = ($( "#projectActSDEdit" ).val())? $( "#projectActSDEdit" ).val():'1901-01-01';
 		save_values['actED'] 			 = ($( "#projectActEDEdit" ).val())? $( "#projectActEDEdit" ).val():'1901-01-01';
 		save_values['note'] 			 = $( "#projectNoteEdit" ).val();
 		//save_values['is_visible'] 		 = $( "#projectIsVisibleEdit" ).val();
 		save_values['ass_id'] 	 		 = $.trim( $('#projectAssignmentEdit option:selected' ).val());
		save_values['assignment'] 		 = $.trim( $('#projectAssignmentEdit option:selected' ).text());
		save_values['client_id'] 		 = $.trim( $('#projectClientEdit option:selected' ).val());
		save_values['client']	 		 = $.trim( $('#projectClientEdit option:selected' ).text());
		save_values['manager_id'] 		 = $.trim( $('#projectManagerEdit option:selected' ).val());
		save_values['manager']	 		 = $.trim( $('#projectManagerEdit option:selected' ).text());
		save_values['project_status_id'] = $.trim( $('#projectStatusEdit option:selected' ).val());
		save_values['project_status']	 = $.trim( $('#projectStatusEdit option:selected' ).text());
		save_values['apt_status_id'] 	 = $.trim( $('#projectAptStatusEdit option:selected' ).val());
		save_values['apt_status']	 	 = $.trim( $('#projectAptStatusEdit option:selected' ).text());

		save_values['teamIds'] 	 	 	 = $( "#projectTeamEdit").val();
		console.log(save_values['actSD']);
		console.log(save_values['actED']);
		var team=[];
		$( "#projectTeamEdit option:selected" ).each( function(i,item ){
								team.push( $(item).text() );
		});
		
		//save_values['departments'] = team.toString();
		save_values['team_names']   = team.toString();


		if (save_values['teamIds']) 
		{
			save_values['team_ids_str'] = save_values['teamIds'].toString();
		}
		else
		{
			//If nothing has selected send empty string an array
			save_values['teamIds']=[];
			save_values['team_ids_str']="";
		}


		// Project Visibility
		if ( $( "#projectIsVisibleEdit").is(':checked') ) 
		{
			save_values['is_visible'] = 1;
		}
		else{
			save_values['is_visible'] = 0;
		}


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
					$("#mdlEditProject").modal('hide');
					
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
|Delete Project Confirmation View
|---------------------------------------------------------------------------------
*/
App.Views.DeleteProjectModal = Backbone.View.extend({
	
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

	//  DESTROY Project
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
	
});//#DeleteProjectModal