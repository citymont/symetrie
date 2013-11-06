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

		});
		

		return config(allElements,allElementsField);
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
			url: Conf.url +"history.php",
			type: "POST",
			data: { data : JSON.stringify(localStorage, null, '\t'), model : Conf.model, id : Conf.id },
			dataType: "html"
		});

		request.done(function( msg ) {
			console.log( msg );
		});

		request.fail(function( jqXHR, textStatus ) {
			console.log( "Request failed: " + textStatus );
		});


	}

	function preState( v ){
		return v.slice(1);
	}

	function loadHistoryData( file ) {

		$.getJSON( file , function( json ) {  
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