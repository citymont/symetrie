var extra = (function() {

	var saveDocElement;
	
	// DOM elements
	var allElements = [];
	var allElementsField = [];

	function init() {

		bindElements();
		//loadHistoryData('../../app/data/index/1383055390.json');

		// Load state if storage is supported
		if ( ! supportsHtmlStorage() ) {
			loadHistoryData('../../app/data/index/1383055390.json'); // load json file
		}

		return findElementsEditable();
	}
	
	function bindElements() {

		saveDocElement = document.querySelector( '.saveDoc' );
		saveDocElement.onclick = onSaveDoc;

		$('#simple-menu').sidr({side: 'right'});
		$('#simple-menu').on('mouseover', getJsonHistory());

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

	function onSaveDoc( event ) {

		//console.log(JSON.stringify(localStorage));

		var request = $.ajax({
			url: Conf.url +"history",
			type: "POST",
			data: { data : JSON.stringify(localStorage, null, '\t'), model : Conf.model, id : Conf.id },
			dataType: "html"
		});

		request.done(function( msg ) {
			console.log( msg );
			$('.saveDoc').css({color:'black'});
			// get new history
			getJsonHistory();
		});

		request.fail(function( jqXHR, textStatus ) {
			console.log( "Request failed: " + textStatus );
		});


	}

	function preState( v ){
		return v.slice(1);
	}

	function loadHistoryData( file ) {

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
		});
	}

	function getJsonHistory() { 

		$("#sidr-model").html(Conf.model);
		if (Conf.id) { $("#sidr-page").html(Conf.id); };
		
		$.ajax({
			url: Conf.url +"history",
			type: "GET",
			data: { model : Conf.model, id : Conf.id, method: 'list'},
			dataType: "html"
			}).done(function( msg ) {

				$("#sidr-history").html( msg );
				$("#sidr-history li").on('click',function() {
					loadHistoryData($(this).html());
			    });

		});
	}

	return {
		init: init
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