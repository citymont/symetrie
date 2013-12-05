#!/usr/bin/php
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

} else {
	// variable URI
	$varsTpl = $_GET['model'];
}
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

// Sauvegarde du template dans un fichier
file_put_contents($file, $data);
print '----------------

';
print 'Generation du Template  : '.$varsTpl.' ';
print '

----------------';