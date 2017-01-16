
App.Views.initReports = Backbone.View.extend({
	initialize : function(){
		var reportsCollection = new App.Collections.Reports();
		//console.log(reportsCollection);
		var reportsView = new App.Views.Reports({ collection: reportsCollection});
	}
});



// Reports Advance Filter
App.Views.Reports = Backbone.View.extend({
	el:'#dReports',

	templateUC: App.template("tmpUserClientRep"),
	templateCU: App.template("tmpClientUserRep"),
	templateMX: App.template("tmpMatrix"),
	

	reportTableUC: '#tblRepUserClient',
	reportTableCU: '#tblRepClientUser',
	reportTableMX: '#tblMatrix',

	events:{
		'click .rdAdvancedFilter'	 	: 'filterTypeSelect',
		'click button#repGenUC'		 	: 'generateFilter',
		'click button#repGenCU'		 	: 'generateFilter',
		'click button#repGenMX'		 	: 'generateFilter',
		'click a#exportExcel'		 	: 'generateFilter',
		'select2:select #fltrUserUC' 	: 'userSelect',
		'select2:select #fltrClientCU' 	: 'clientSelect'

	},

	initialize : function(){
		
		this.initFilterBar();
	},

	render: function(collection) {

		if (this.filterInfo.type == 'uc') {
			var repTable = this.reportTableUC;
		} else if (this.filterInfo.type == 'cu') {
			var repTable = this.reportTableCU;
		}
		self = this;

		if (this.filterInfo.type == 'uc' || this.filterInfo.type == 'cu')
		{
			_.each(collection.report,function(model,idx){
				$(repTable + ' tbody').append(self.addRow(model));
			});
			
		} 
		else if( this.filterInfo.type == 'mx' )
		{
			var repTable = this.reportTableMX;
			//console.log('MX Render');
			//console.log(collection.report.projects);
			var proj_row = '<tr>'+ self.addMxProjRow(collection.report.projects) + '</tr>';
			//console.log(proj_row);
			$(repTable + ' thead').html('');
			$(repTable + ' thead').append(proj_row);

			var time_row = '';

			_.each(collection.report.timing, function(model,idx){
				//console.log(model);
				var temp = self.addRow(model);
				//console.log(temp);
				$(repTable + ' tbody').append(temp);
			});



		}
		
		return this;
	},

	addMxProjRow: function (model) {
		var row_str='<th>#</th>';
		_.each(model, function(item,idx){
			row_str+='<th>'+item+'</th>';
		});
		row_str+='<th>User Total (Hours)<th>'
		return row_str;
	},

	addRow: function(model) {
		
		if (this.filterInfo.type == 'uc') {
			var row = this.templateUC(model);
		} else if (this.filterInfo.type == 'cu') {
			var row = this.templateCU(model);
		} else if (this.filterInfo.type == 'mx') {
			var row = this.templateMX({time_row:model});
		}
		return row;
	},

	// user select event
	userSelect: function (e) {
		var user_id = $.trim(e.params.data.id);
		var ucClientSelectElement = $('#fltrClientUC');

		$.ajax({
			url: '/'+App.myRoot+'/c_reports/ax_user_clients',
			type: 'get',
			data: {
					user_id: user_id
			},
			success: function(data){
					//console.log(data);
					ucClientSelectElement.html('<option value="00" selected="">All</option>');
					ucClientSelectElement.select2({data:data}).trigger('change');
			},//#success
			error: function(data){
					$.notify({ message : data.responseJSON.message},
							    	{ type : 'error',
								   z_index : 10001 });
			}//#error
			 
		});//#ajax
		
	},

	// Client Select Event
	clientSelect: function (e) {
		var client_id = $.trim(e.params.data.id);
		var cuUserSelectElement = $('#fltrUserCU');

		$.ajax({
			url: '/'+App.myRoot+'/c_reports/ax_client_users',
			type: 'get',
			data: {
					client_id: client_id
			},
			success: function(data){
					//console.log(data);
					cuUserSelectElement.html('<option value="00" selected="">All</option>');
					cuUserSelectElement.select2({data:data}).trigger('change');
			},//#success
			error: function(data){
					//console.log(data);
					$.notify({ message : data.responseJSON.message},
							    	{ type : 'error',
								   z_index : 10001 });
			}//#error
			 
		});//#ajax
	},

	// Filter Type  Radio button change event
	filterTypeSelect : function(e) {
		//console.log(e.currentTarget);
		if ( $(e.currentTarget).val() == 'uc' ) {
			$('#ucBlock').css('display','block');
			$('#cuBlock').css('display','none');
			$('#mxBlock').css('display','none');
		} else if( $(e.currentTarget).val() == 'cu'){
			$('#ucBlock').css('display','none');
			$('#cuBlock').css('display','block');
			$('#mxBlock').css('display','none');
		} else if( $(e.currentTarget).val() == 'mx'){
			$('#ucBlock').css('display','none');
			$('#cuBlock').css('display','none');
			$('#mxBlock').css('display','block');
		}
	},

	// Filter Button Click Event
	generateFilter : function(e){
		//e.preventDefault();
		//console.log(e.currentTarget);
		this.filterInfo = [];
		this.filterInfo.type = $('input[name=rdAdvancedFilter]:checked').val();
		
		if ( this.filterInfo.type == 'uc' ) 
		{
			this.filterInfo.user_id = $('#fltrUserUC option:selected').val();
			this.filterInfo.client_id = $('#fltrClientUC option:selected').val();
			this.filterInfo.ass_id = '-1';
			this.filterInfo.from = $('#fltrFromUC').val();
			this.filterInfo.to = $('#fltrToUC').val();

			var repTable = this.reportTableUC;
		} 
		else if ( this.filterInfo.type == 'cu' )
		{
			this.filterInfo.user_id = $('#fltrUserCU option:selected').val();
			this.filterInfo.client_id = $('#fltrClientCU option:selected').val();
			this.filterInfo.ass_id = '-1';
			this.filterInfo.from = $('#fltrFromCU').val();
			this.filterInfo.to = $('#fltrToCU').val();
			var repTable = this.reportTableCU;
		} 
		else if ( this.filterInfo.type == 'mx' )
		{
			this.filterInfo.user_id = '00';//$('#fltrUserMX option:selected').val();
			this.filterInfo.client_id = '00';//$('#fltrClientMX option:selected').val();
			this.filterInfo.ass_id = $('#fltrAssignmentMX').val();
			this.filterInfo.from = $('#fltrFromMX').val();
			this.filterInfo.to = $('#fltrToMX').val();
			var repTable = this.reportTableMX;
		} 
		else 
		{
			return;
		}
		
		// getting Weeks first day
		this.filterInfo.from_x = this.getWeekDate(this.filterInfo.from,'first')

		// getting Weeks last day
		this.filterInfo.to_x = this.getWeekDate(this.filterInfo.to,'last')


		if ( $(e.currentTarget).attr('id') == 'exportExcel' )
		{
			this.filterInfo.is_exp = 1;
			this.filterInfo.exp_type = 'xlsx';
			var gazan = 
			'?type='+this.filterInfo.type
			+'&user_id='+this.filterInfo.user_id
			+'&ass_id='+this.filterInfo.ass_id
			+'&client_id='+this.filterInfo.client_id
			+'&from='+this.filterInfo.from
			+'&to='+this.filterInfo.to
			+'&from_x='+this.filterInfo.from_x
			+'&to_x='+this.filterInfo.to_x
			+'&is_exp='+this.filterInfo.is_exp
			+'&exp_type='+this.filterInfo.exp_type;
			window.open('http://192.168.220.110/index.php/c_reports/generate'+gazan,'_blank');
			return;
		}
		console.log(this.filterInfo.from_x+'--'+this.filterInfo.to_x);
		//window.location.href('http://192.168.220.110/index.php/reports'+gazan);
		//appRouter.navigate('http://192.168.220.110/index.php/reports'+gazan,{trigger:false,replace:false});
		//return;
		//console.log(this.filterInfo);
		var self = this;
		self.collection
			.fetch({ 
					data : {
							type 	  : this.filterInfo.type,
							user_id   : this.filterInfo.user_id,
							ass_id	  : this.filterInfo.ass_id,
							client_id : this.filterInfo.client_id,
							from 	  : this.filterInfo.from,
							from_x 	  : this.filterInfo.from_x,
							to 		  : this.filterInfo.to,
							to_x 	  : this.filterInfo.to_x,
							is_exp    : this.filterInfo.is_exp,
							exp_type  : this.filterInfo.exp_type
						}
					})
			.then(function(response){
				
				//console.log(repTable);
				$(repTable + ' tbody').html("");
				self.render(response);

			});
	},

	// Init Filter Bar
	initFilterBar : function(){
		
		$('.fltrSearchable').select2({
			
		});

		$('.fltrFromToDatePicer').datepicker({
								format: "yyyy-mm-dd",
								placeholder:'Select Date',
								weekStart:1,
								daysOfWeekHighlighted: "0,6",
							    calendarWeeks: true,
							    autoclose: true,
							    todayHighlight: true,
								todayBtn: "linked"
							});
	},

	getWeekDate: function(date,what){
		if (date != '') {

			var stamp=[];
			date = new Date(date);
			stamp['year']  	= date.getFullYear();
			stamp['month']	= date.getMonth()+1;
			stamp['date'] 	= date.getDate();
			stamp['wDay'] 	= date.getDay();
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
			
			var period = false;
			
			if (what == 'first' ) {
				var startDate 	= new Date( stamp['year'], stamp['month']-1, stamp['date'] );
				period = startDate.getFullYear()+'-'+(startDate.getMonth()+1)+'-'+startDate.getDate();
			}else if(what == 'last' ){
				var endDate 	= new Date( stamp['year'], stamp['month']-1, stamp['date']+6 );
				period 	= endDate.getFullYear()+'-'+(endDate.getMonth()+1)+'-'+endDate.getDate();
			}
		}else{
			period='';
		}



		return period;
	}
});