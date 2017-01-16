

/*
|---------------------------------------------------------------------------------
|Actions Buttons Generation 
|---------------------------------------------------------------------------------
*/
/*//Actions button Generation
var actionsCell = Backgrid.Cell.extend({
	template: _.template($("#action_buttons").html()),
	events:{
		'click .edit': 'editPosRow',
		'click .delete': 'deletePosRow'
	},
		
	editPosRow : function(e){
		e.preventDefault();
		console.log('editing '+this.model.get('name'));
	},
	deletePosRow : function(e){
		e.preventDefault();
		console.log('deleting ' +this.model.get('name'));
	},

	render : function(){
		this.$el.append( this.template());
		console.log("rendering button");
		this.delegateEvents();
		return this;
	}
});*/

//var positionCollection = new App.Collections.Positions();

/*// Initialize the paginator
var paginator = new Backgrid.Extension.Paginator({
  windowSize: 5,
  lideScale: 0.25, // Default is 0.5

  // Whether sorting should go back to the first page
  goBackFirstOnSort: false, // Default is true

  collection: positionCollection

});*/


/*
|---------------------------------------------------------------------------------
|Position BackGrid
|---------------------------------------------------------------------------------
*/
/*posGrid = new Backgrid.Grid({

	className: 'table table-hover',
	
	// Initing Table Columns
	columns:[{
			name: 'id',
			label: 'ID',
			editable: false,
			cell: Backgrid.IntegerCell.extend({
      		orderSeparator: ''
			})},{
			name: 'name',
			label: 'Name',
			editable: false,
			cell: 'string'
			
		},{
			name: 'note',
			label: 'Note',
			editable: false,
			cell: 'string'
			
		},{
			name: '1',
			label: 'Actions',
			editable: false,
			sortable: false,
			cell: actionsCell
			
		}],

	//Data Collection For Table 
	collection: positionCollection
	
});*/

/*
|---------------------------------------------------------------------------------
|Render And Show Grid
|---------------------------------------------------------------------------------
*/
/*$('#gr_grid_test').append(posGrid.render().el);*/

/*
|---------------------------------------------------------------------------------
| ServerSideFilter delegates the searching to the server by submitting a query.
|---------------------------------------------------------------------------------
*/
/*var serverSideFilter = new Backgrid.Extension.ServerSideFilter({
  	collection: positionCollection,
  	// the name of the URL query parameter
  	name: "q",
  	placeholder: "Search Job Title" // HTML5 placeholder for the search box
});*/

/*//Appending Searchbar
$("#gr_grid_test").before(serverSideFilter.render().el);
// Add some space to the filter and move it to the right
$(serverSideFilter.el).css({float: "right", margin: "20px"});
*/


/*
// Render the paginator
$('#gr_grid_test').after(paginator.render().el);*/


/*
|---------------------------------------------------------------------------------
| View For All Positions And Create Button
|---------------------------------------------------------------------------------
*/
/*App.Views.PosLib = Backbone.View.extend({
	el: '.content',

	events: {
		'click #sbmt_pos_create': 'createPos'
	},


	
	initialize : function(){
		
		//console.log("1. =>"+this.collection);
		this.render();
		this.listenTo( this.collection, 'add', this.renderPos);
		//this.listenTo( this.collection, 'change', this.renderPos);

	},

	render : function(){
		this.collection.each(this.renderPos, this);
		return this;
	},

	renderPos : function(item){
		//console.log(item.toJSON());
		var singlePos = new App.Views.PosView({ model: item });
		//console.log(singlePos.render().el);
		$('#tablebody').append( singlePos.render().el );

	},

	
	// CREATE JOB TITLE
	createPos : function(e){
		e.preventDefault();
		//console.log("asd");
		this.createForm = {posname: $('#name'),
						   posnote: $('#note')};


		var posModel = {name: this.createForm.posname.val(),
						note: this.createForm.posnote.val()};

		//var newPos = new App.Collections.Position({model:posModel});
		//console.log(newPos.toJSON());

		var resCreate = this.collection.create( new App.Models.Positions(posModel), 
				{
					wait:true,
					success: function(model,response){
						console.log(response);
						
						//Showing Success Message
						$.notify({ message: "New job Title Created Successfully."},
								    { type: 'success',
								    z_index: 10000000}
								);
						
						//empty tag values
						$('#name').val('');
						$('#note').val('');
						
						//Closing Modal
						$('#mdlCreatePosition').modal('hide');
					},
					error: function(model,response){
						console.log(response);
						//Showing Success Message
						$.notify({ message: response.responseJSON.message},
								    { type: 'danger',
								   z_index: 10000000}

								);
					}
				});

	}//-->end of createPos

});*/



/*
|=================================================================================
|Single Position view
|=================================================================================
*/

/*App.Views.PosView = Backbone.View.extend({
	tagName : 'tr',

	className: 'posRow',
	
	templatePosRow : _.template( $("#position_row").html() ),

	events : {
		'click #edit'			: 'editPosition',
		'click #destroy'		: 'deletePosition'
		//'click sbmt_pos_edit'	: 'editSubmit'
		

	},

	//  Init
	//=====================
	initialize : function(){
		this.model.on('change', this.render, this);
	},

	//  RENDER ELEMENT
	//=====================
	render: function(){
		//console.log(this.model);
		this.$el.html( this.templatePosRow( this.model.toJSON() ) );
		//console.log("eheeey");
		return this;
	},

	
	//  EDIT JOB TITLE Button Click in Table
	//=====================
	editPosition: function(e){
		e.preventDefault();
		//console.log('triggering to position:edit');
		//vent.trigger('position:edit', this.model);
		new App.Views.EditPos({model: this.model});
		console.log("rendering view for modal and openning");
		//Appending Edit Position Template to Bootstrap Modal
		//$("#mdl_edit_modal_body_class").append( editPosView.el );
		
		//Showing Bootstrap Modal
		$("#mdlEditPosition").modal('toggle');
		
	},


	//  DESTROY JOB TITLE
	//=====================
	deletePosition : function(e){
		e.preventDefault();

		// Destroying Selected Model
		this.model.destroy( { success: function( model, response){
			//Showing Success Message
			$.notify({ message: model.attributes.name +" "+ response.message},
				     { type: 'success'}
			);
		} });

		//Removing Element From Table
		this.remove();
	}


});
*/




/*
|---------------------------------------------------------------------------------
|Create Position View Form
|---------------------------------------------------------------------------------
*/

/*App.Views.createPos = Backbone.View.extend({
	el : "#dCreatePosition",
	events:{
		'click #sbmt_pos_create': 'createPos'
	},

	initialize : function(){
		this.createForm = {
					posname: $('#name'),
					posnote: $('#note')
		};

	},
	createPos : function(e){
		e.preventDefault();
		var posModel = {name: this.createForm.posname.val(),
						note: this.createForm.posnote.val()};

		var newPos = new App.Collections.Position({model:posModel});
		console.log(newPos.toJSON());

		new App.Views.PosLib({collection:newPos});
	}

});*/
