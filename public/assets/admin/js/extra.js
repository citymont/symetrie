var extra = (function() {

	var saveDocElement;
	
	// DOM elements
	var allElements = [];
	var allElementsField = [];

	function init() {

		bindElements();
		loadHistoryData('choose.json', $('body').addClass('ready'));

		return findElementsEditable();
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

			if($(this).data('type') =="oneline") {
				document.querySelector('.' + className).onkeypress = onelineKeyPress;
			}
			editorContentOption($(this),className);
			editorContentFocusable($(this));

			var localV = (localStorage[className]) ? localStorage[className] : '' ; 
			if ( $(this).text().trim() === '' && localV.trim() === '' || localV.trim() === '&nbsp;') {
				$(this).addClass('empty');
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
			console.log( msg );
			$('.saveDoc, .undo').removeClass('sym-display');
			if(!publishState) { 
				$('.saveDocPublish').addClass('sym-display'); 
			} else { 
				$('.saveDocPublish').removeClass('sym-display'); 
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

	return {
		init: init,
		menuResize : menuResize
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
