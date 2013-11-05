#!/usr/bin/php
<?php 
/**
 * CLI : $ php app/commands/page.php MODEL PAGE 
 * URI : /app/commands/page.php?model=yyyy&page=xxxx
 * 
 */

if ( isset($_SERVER['argv']) ) {
	// variable command line
	$vars = $_SERVER['argv'];
	$varsModel = $vars[1];
	$varsPage = $vars[2];

} else {
	// variable URI
	$varsModel = $_GET['model'];
	$varsPage = $_GET['page'];
}

if(is_dir(__DIR__.'/../data/'.$varsModel.'./'.$varsPage)) {
	print '----------------

';
print '
Erreur : Page existante

----------------';
} else {
	mkdir(__DIR__.'/../data/'.$varsModel.'./'.$varsPage);

print '----------------

';
print 'Generation de la Page : '.$varsPage.' 

Pour le Template  : '.$varsModel.' ';

print '

----------------';

}