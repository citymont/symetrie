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
	createPage($varsModel,$varsPage, true);

}
if (isset($_GET['model'])) {
	// variable URI
	$varsModel = htmlspecialchars($_GET['model']);
	$varsPage = htmlspecialchars($_GET['page']);
	createPage($varsModel,$varsPage, true);
}

function createPage($varsModel,$varsPage,$result) {

	if(is_dir(__DIR__.'/../data/'.$varsModel.'/'.$varsPage)) {

		if($result) {
			print '----------------

			';
			print '
			Erreur : Page existante

			----------------';
		} else {
			return 'error';
		}
	} else {

		if(!is_dir(__DIR__.'/../data/'.$varsModel)) {
			mkdir(__DIR__.'/../data/'.$varsModel);
		}

		mkdir(__DIR__.'/../data/'.$varsModel.'/'.$varsPage);

		if($result) {
			print '----------------

			';
			print 'Generation de la Page : '.$varsPage.'

			Pour le Template  : '.$varsModel.' ';

			print '

			----------------';
		} else {
			return 'success';
		}

	}

}
