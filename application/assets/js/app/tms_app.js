(function() {
	window.App = {
		Models: {},
		Views: {},
		Collections: {},
		Router: {},
		DZ:{},
		myRoot: 'backend'
	};

	window.vent = _.extend({}, Backbone.Events);

	App.template = function(id){
		if ( $('#'+id).length ) {
			return _.template( $('#'+id).html() );
		}
	};

	

	// Hidding Loading Spinner
	$('.loading').css('visibility','hidden');

	// Ajax Load Loading Spinner
	$(function(){
	    $(document).ajaxStart(function(){ $('.loading').css('visibility','visible'); });
	    $(document).ajaxComplete(function(){ $('.loading').css('visibility','hidden'); });
	});

	App.makeFineUploader = function(configs) {
		uploadDirectory = '/'+App.myRoot+'/products/upload_image';
		allowedExtensions = ['jpeg', 'jpg', 'png'];
		var fu_element = configs.element;
		//var fu_hiddenElement = configs.hiddenElement;
		var fu_imageTag = configs.imageTag;
		var fu_sizeLimit = configs.sizeLimit;
		var fu_itemLimit = configs.itemLimit;
		
		var x = $('#'+fu_element).fineUploader({
	            template: 'qq-template',
	            request: {
	                endpoint: uploadDirectory
	            }, 
	           
	            thumbnails: {
	                placeholders: {
	                    waitingPath: '/application/assets/js/plugins/fine-uploader/placeholders/waiting-generic.png',
	                    notAvailablePath: '/application/assets/js/plugins/fine-uploader/placeholders/not_available-generic.png'
	                }
	            },
	            validation: {
	                allowedExtensions: allowedExtensions,
	                itemLimit: fu_itemLimit,
	                sizeLimit: fu_sizeLimit // 50 kB = 50 * 1024 bytes
	            },
	            deleteFile: {
	            	enabled: true,
	            	endpoint: uploadDirectory
	            },
	            callbacks: {
	            	onComplete: function(id,name,responseJSON,xhr){
	            		if (responseJSON.success == true) {
	            			
	            			//Set temp folder name to hidden input
	            			$("#"+fu_imageTag).attr('data-hash', responseJSON.uuid);
	            			$("#"+fu_imageTag).attr('data-filename',name);
	            			
	            			var src = $("#"+fu_imageTag).attr('data-dir');
	            			src += '/uploads/'+responseJSON.uuid+'/'+name;

	            			$("#"+fu_imageTag).attr('src',src)
	            			//Showing Success Message
							$.notify({ message : "Upload Success"},
									    { type : 'success',
									   z_index : 10000 });
	            		}
	            	},
	            	onError: function(id,name,errorMessage,xhr){
	            			//Showing Error Message
							$.notify({ message : errorMessage},
									    { type : 'error',
									   z_index : 10000 });
	            	},
	            	onDeleteComplete: function(id,xhr,isError){
	            					console.log(xhr);

					            		if (!isError) {
					            			$("#"+fu_imageTag).attr('data-hash','');
					            			$("#"+fu_imageTag).attr('data-filename','');
					            			
					            			var src = $("#"+fu_imageTag).attr('data-dir');
					            			//var defimgdef = $("#"+fu_imageTag).attr('data-defimgdef');
					            			src += '/img/gallery/'+$("#"+fu_imageTag).attr('data-defimgdef');
					            			$("#"+fu_imageTag).attr('src',src)
					            			
					            			$.notify({ message : "Delete Success"},
													    { type : 'success',
													   z_index : 10000 });	
					            		}
					            		else
					            		{
					            				$.notify({ message : "Delete Error. Try again or refresh the page..."},
													    { type : 'error',
													   z_index : 10000 });		
					            		}
	            	}
	            }
	        });
	};


})();