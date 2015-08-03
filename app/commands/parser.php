<?php 
/**
 * CLI : $ php app/commands/parser.php index
 * URI : /app/commands/parser.php?model=index
 * 
 */

require(__DIR__.'/../lib/simple_html_dom.php');

if ( isset($_SERVER['argv']) ) {
	// variable command line
	$vars = $_SERVER['argv'];
	$varsTpl = $vars[1];
	initParser($varsTpl,true);
}

if (isset($_GET['model'])) {
	// variable URI
	$varsTpl = $_GET['model'];
	initParser($varsTpl,true);
}


function initParser($varsTpl,$result) {

// Create DOM from HTML PAGE
$html = file_get_html(__DIR__.'/../model/'.$varsTpl.'.editable.html');

// trouve tous les champs éditable   
$ret = $html->find('*[contenteditable]'); 
	foreach ( $ret as $e ) {
	// Remove a attribute, set it's value as null! 	
		$e->contenteditable = null;
		$e->{'data-type'} = null;
		$class = explode(" ", $e->class);
		$e->innertext='{{ '.$class[0].'|raw }}';
	}
	
$html = str_replace(array("\n", "\r", "\t"), "", $html);

// nom du template FINAL
$dossier = __DIR__.'/../../app/views/';

$file = $dossier.$varsTpl.'.html.twig';

$data = '{% extends "base.html.twig" %}{% block content %}';
$data .= trim($html);
$data .='{% endblock %}';

// Création du dossier de stockage si n'existe pas
if(!is_dir(__DIR__.'/../data/'.$varsTpl)) {
	mkdir(__DIR__.'/../data/'.$varsTpl, 0, true);
}

if(!file_exists(__DIR__.'/../actions/'.$varsTpl.'.actions.php')) {
	$data = '<?php

class '.ucfirst($varsTpl).'Handler {

	private $modelName = "";
	private $docId = "";

    function __construct() {

    	$app = new App();
    	$infos = $app->getRouteInfos(\'/\');
    	$this->modelName = $infos[\'model\'];
    	$this->docId = $infos[\'id\'];

    }

    function get($name = null, $b = null) {

    	$appActions = new Actions(); 

		    if( defined(\'CACHE_FLAG\') ) { 
		    	
				$twig = $appActions->Twigloader();

				$appActions->renderView($twig, $this->modelName,$this->docId);

			}

			if( defined(\'ADMIN\') ) { 
				
				$appActions->Admin($this->modelName); 
		
			}

      
    }
}


';
	file_put_contents(__DIR__.'/../actions/'.$varsTpl.'.actions.php', $data);
}

// Sauvegarde du template dans un fichier
file_put_contents($file, $data);

if($result) {

	print '----------------

	';
	print 'Generation du Template  : '.$varsTpl.' ';
	print '

	----------------';
		
	}

}