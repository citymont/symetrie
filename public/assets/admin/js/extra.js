var extra = (function() {

	var saveDocElement;
	
	// DOM elements
	var allElements = [];
	var allElementsField = [];
	var urlUpload = Conf.urlUpload;
	var urlUploadDestinationFolder = '/img/';

	function init() {

		localStorage.clear();
		bindElements();
		createoverlay();
		createUploadDiv(); 
		editorFindSlice();
		loadHistoryData('choose.json', $('body').addClass('ready'));

		return findElementsEditable();
	}

	function setUrlUpload(val) {
		return extra.urlUpload = val;
	}
	
	function bindElements() {

		saveDocElement = document.querySelector( '.saveDoc' );
		saveDocElement.onclick = onSaveDoc;
		saveDocPublishElement = document.querySelector( '.saveDocPublish' );
		saveDocPublishElement.onclick = onSaveDoc;
		saveUndoElement = document.querySelector( '.undo' );
		saveUndoElement.onclick = onUndo;

		$('.ui.menu-ui .wrapper .logo').on('mouseover', getJsonHistory());
		$('.ui.menu-ui .wrapper .logo').on('click', function() { 
			
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$('.ui.menu-ui .wrapper').css({'right':'0px'});
				menuResize();
			} else {
				$(this).addClass('active');
				$('.ui.menu-ui .wrapper').css({'right':'300px'});
				$('.menu-ui-overlay').css({'width':'400px', 'height':'100%'});
			}
			
		});

	}

	function menuResize() {
		if($('.saveDoc').hasClass('sym-display') ) { 
			var heightOverlay = '150px'} 
		else {
			heightOverlay = '40px';
		}

		if($('.ui.menu-ui .wrapper .logo').hasClass('active')) {
			var widthOverlay = '400px';
			var heightOverlay = '100%'} 
		else {
			widthOverlay = '100px';
		}

		$('.menu-ui-overlay').css({'width':widthOverlay, 'height':heightOverlay});
	}

	/* Find element editable and options */
	function findElementsEditable() {

		$('*[contenteditable=true]').each(function() {

			var classNames = $(this).attr('class');
			var className = classNames.split(" ",1);

			allElements.push('.' + className);
			allElementsField.push(document.querySelector( '.' + className ));

			if($(this).data('type') =="image") {
				
				// Only for image field
				document.querySelector('.' + className).onkeypress = onelineKeyPress;

				$(this).on('click', function(event) {
					event.preventDefault();
					
					var infoSize = $(this).attr('data-size');
					setTargetImage($(this).attr('class'));

					$('.uploadInner span').remove();
					$('.uploadInner').append('<span>Format demandé : '+infoSize+'px </span>');
					$('.uploadInner').show();
					$('.overlay').show();
				});

			} else {

				if($(this).data('type') =="oneline") {
					document.querySelector('.' + className).onkeypress = onelineKeyPress;
				}

				editorContentOption($(this),className);
				editorContentFocusable($(this));

				var localV = (localStorage[className]) ? localStorage[className] : '' ; 
				if ( $(this).text().trim() === '' && localV.trim() === '' || localV.trim() === '&nbsp;') {
					$(this).addClass('empty');
				}
			}
			
		});

		editorContentEmpty();
		
		return config(allElements,allElementsField);
	}

	/**
	 * find empty editable.div and add custom class
	 */
	function editorContentEmpty () {

		$('*[contenteditable=true].empty').each(function() {

			$(this).on('click', function(event) {
				event.preventDefault();
				$(this).removeClass('empty');
			}).on('focusout', function(event) {
				event.preventDefault();
				if( $(this).text().trim()  === '' ) {
					$(this).addClass('empty');
				}
			});

		});
	}

	function editorContentFocusable ( target ){
		target.on('click', function(event) { 
			event.preventDefault();
			target.focus();
		});
	}

	function editorContentOption( target,className ) {

		var thisP = target.innerWidth();
		var $options = $('<div class="cEdit-option"></div>').css({left: thisP});
		
		// clean HTML on div
		var $box = $('<a href="clean">1</a> ')
				.on('click', function(event) {
					event.preventDefault();
					$('.'+className).html($('.'+className).text());
				});
		
		// unclean HTML on div
		var $box2 = $('<a href="clean">2</a>')
				.on('click', function(event) {
					event.preventDefault();
					$('.'+className).html(localStorage[className]);
				});
			
		$options.append($box).append($box2);
		target.before($options.hide());

	}

	function onelineKeyPress( event ) {

		if ( event.keyCode === 13 ) {
			event.preventDefault();
		}
	}

	function config(a, b) {
		return {
			allElements: a,
			allElementsField: b
		};
	}

	function localstorageToJson() {
		return JSON.stringify(localStorage);
	}

	function onUndo ( event ) {
		document.execCommand('undo', false, null);
		$('.saveDoc, .undo').removeClass('sym-display');
		$('.saveDocPublish').removeClass('sym-display'); 
		menuResize();
	}

	/*
		Find slices and create events
	 */
	function editorFindSlice () {

		$('.slice').each(function() {

			$(this).on('click', function(event) {
				event.preventDefault();
				$('.overlay').show();
				$('.sliceInner').show();

				setTargetSlice($(this).attr('data-source'));
				loadSlice($(this).attr('data-schema'), $(this).attr('data-source'));

			}).on('focusout', function(event) {
				event.preventDefault();
				
			});

		});
	}

	function onSaveDoc( event ) {
		var classNames = $(event.target).attr('class');
		
		var publishState = (classNames == "saveDocPublish sym-display") ? true : false;

		var request = $.ajax({
			url: Conf.url +"history",
			type: "POST",
			data: { data : JSON.stringify(localStorage, null, '\t'), model : Conf.model, id : Conf.id, publish : publishState },
			dataType: "html"
		});

		request.done(function( msg ) {
			//console.log( msg );
			$('.saveDoc, .undo').removeClass('sym-display');
			if(!publishState) { 
				$('.saveDocPublish').addClass('sym-display'); 
			} else { 
				$('.saveDocPublish').removeClass('sym-display'); 
				menuResize();
			}
			// get new history
			getJsonHistory();
			// clear quit
			window.onbeforeunload = function (e) { };
		});

		request.fail(function( jqXHR, textStatus ) {
			console.log( "Request failed: " + textStatus );
		});


	}

	function preState( v ){
		return v.slice(1);
	}

	function loadHistoryData( file , callback) {

		var callback = callback;
		
		$.getJSON( Conf.url +"history" , { model : Conf.model, id : Conf.id, file : file, method : 'one' }, function( json ) {
			$.each( json, function( i, value ) {
				localStorage[i] = value;
			});

			// dynamic
			for (var i = allElements.length - 1; i >= 0; i--) {

				if(localStorage[preState(allElements[i])]) {
					allElementsField[i].innerHTML = localStorage[preState(allElements[i])];	
				}
				
			};
			
			callback;
		});
	}

	function getJsonHistory() { 

		$("#extra-menu-model").html(Conf.model);
		if (Conf.id) { $("#extra-menu-page").html(Conf.id); };
		
		$.ajax({
			url: Conf.url +"history",
			type: "GET",
			data: { model : Conf.model, id : Conf.id, method: 'list'},
			dataType: "html"
			}).done(function( msg ) {

				$("#extra-menu-history").html( msg );
				$("#extra-menu-history li").on('click',function() {
					loadHistoryData($(this).attr('data-val'));
					$("#extra-menu-history li").removeClass('active');
					$(this).addClass('active');
					$('.saveDocPublish').addClass('sym-display'); 
			    });

		});
	}

	function setTargetImage(value) {
		targetImage = value;
	}

	function getTargetImage() {
		return targetImage;
	}

	function setTargetSlice(value) {
		targetSlice = value;
	}

	function getTargetSlice() {
		return targetSlice;
	}
	function createoverlay() {
		var $uploadClose = $( "<div id='uploadClose'>X</div>" ),
			$uploadOverlay = $( "<div class='overlay'></div>" );

		$uploadOverlay.add($uploadClose).on('click',function(event) {
				$('.overlay').hide();
				$('.uploadInner').hide();
				//reinit dropzone
				$('#dropzone-area').removeClass('dz-started');
				$('#dropzone-area').html('<div class="dz-default dz-message"/>');
				//reinit slice editor
				$('.sliceInner').hide();
				$('.sliceInner #editor_holder').html('');
			});
		$(".sym-editor").append($uploadOverlay);

	}
	function createUploadDiv() {

		var $uploadFunc = $('<div class="uploadzone" id="dropzone-area"/>'),
			$uploadInner = $( "<div class='uploadInner'><h2>UPLOAD IMAGE</h2><input type='text' id='imageUrl' value=''/></div>" );
			
			$uploadInner.append($uploadFunc);
			$(".sym-editor").append($uploadInner);
				
		var myDropzone = new Dropzone(document.getElementById('dropzone-area'), {
				paramName: "file",
				uploadMultiple: false,
				acceptedFiles:'.jpg,.png,.jpeg,.gif',
				parallelUploads: 1,
				maxFiles:1,
				autoDiscover:false,
				url:Conf.urlUpload,
				fallback: function(){ alert("vous ne pouvez pas uploader d'images");}
			});

			myDropzone.on('complete', function (file, xhr, formData) {
				
				data = JSON.parse(file.xhr.responseText);

				if($('input#imageUrl')) { 
					//TO DO
					var dataURL = $('input#imageUrl').val();
					
					$('.'+extra.getTargetImage())
						.html('<a href="'+dataURL+'"><img src="'+Conf.urlUpload+urlUploadDestinationFolder+data.name+'"></a>');

				} else {

					$('.'+extra.getTargetImage())
						.html('<img src="'+Conf.urlUpload+urlUploadDestinationFolder+data.name+'">'); 
				}

				editor.saveState(event);

			});


	}
	/*
	 * Submit slice
	 */
	function onSaveSlice( data, file ) {
		
		var request = $.ajax({
			url: Conf.url +"history",
			type: "POST",
			data: { data : JSON.stringify(data), method : 'slice', file : file },
			dataType: "html"
		});

		request.done(function( msg ) {

			var tpl = $('.slice[data-source='+extra.getTargetSlice()+']').attr('data-tpl');
			
			var request = $.ajax({
				url: Conf.url +"history",
				type: "GET",
				data: { file : extra.getTargetSlice()+'.json', method: 'slicecomplete', template: tpl},
				dataType: "html"
			});

			request.done(function( data ) {
				if($('body').hasClass('admin')) {} else {
					$('.slice[data-source='+extra.getTargetSlice()+']').html(data);
				}
				$('.overlay').trigger('click');
			});
			
		});

		request.fail(function( jqXHR, textStatus ) {
			console.log( "Request failed: " + textStatus );
		});


	}
	function loadSlice(schema,source) {
		
		$.ajax({
			url: Conf.url +"history",
			type: "GET",
			data: { file : source+'.json', method: 'slice'},
			dataType: "html"
		}).done(function( msg ) {
			delete editor;
			editJSon(schema,source,msg);

		});

	}
	function editJSon(schema,source,starting_value) {
    
    	var starting_value= JSON.parse(starting_value);
      
	    // Initialize the editor
	    var editor = new JSONEditor(document.getElementById('editor_holder'),{
	        ajax: true,
	        disable_collapse: true,
	        disable_edit_json: true,
	        disable_properties : true,
	        no_additional_properties: true,
	        required_by_default: true,
	        iconlib: "fontawesome4",
	        // The schema for the editor
	        schema: {
	          $ref: Conf.url +"history?file="+schema+".json&method=slice_schema"
	        },
	        
	        // Seed the form with a starting value
	        startval: starting_value
	    });
      	$('.sliceInner').attr('id','');
    	$('.sliceInner').attr('id',schema);
      	$('.sliceInner #submit').on('click',function() {
          	onSaveSlice(editor.getValue(),source+'.json');        
      	});
      	$('.sliceInner #close').on('click',function() {
          	$('.overlay').trigger('click');
      	});


      	// Hook up the validation indicator to update its 
      	// status whenever the editor changes
      	editor.on('change',function() {
	        // Get an array of errors from the validator
	        var errors = editor.validate();
	        var indicator = document.getElementById('valid_indicator');
	        
	        // Not valid
	        if(errors.length) {
	          indicator.style.color = 'red';
	          indicator.textContent = "not valid";
	        }
	        // Valid
	        else {
	          indicator.style.color = 'green';
	          indicator.textContent = "valid";
	        }
      	});
	}

	return {
		init: init,
		menuResize : menuResize,
		getTargetImage : getTargetImage,
		getTargetSlice : getTargetSlice,
		editorFindSlice : editorFindSlice,
		createoverlay : createoverlay
	};


})();

// Utility functions

String.prototype.trim = function(){ return this.replace(/^\s+|\s+$/g, ''); };

function supportsHtmlStorage() {
	try {
		return 'localStorage' in window && window['localStorage'] !== null;
	} catch (e) {
		return false;
	}
}

// Json Editor Specify upload handler
JSONEditor.defaults.options.upload = function(type, file, cbs) {
	if (type === 'root.upload_fail') cbs.failure('Upload failed');
	else {

	    // Set up the request.
	    //var urlUpload = '/public/contents/';
	    var files = file;
		// Create a new FormData object.
		var formData = new FormData();
		formData.append('file', file, file.name);   

		var request = $.ajax({
			url: Conf.urlUpload,
			type: "POST",
			data: formData,
					processData: false,  // indique à jQuery de ne pas traiter les données
					contentType: false
				});

		request.done(function( msg ) {
			var data = JSON.parse(msg);
			$('.json-editor-btn-upload').hide();
			cbs.success(data.name);
			
		});

		request.fail(function( jqXHR, textStatus ) {
			console.log( "Request failed: " + textStatus );
		});


	}
};