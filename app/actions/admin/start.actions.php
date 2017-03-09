<?php

class AdminStartHandler {

	private $modelName = "";

	function __construct() {

		$this->modelName = 'admin/start';

		if( !defined('ADMIN') ) {

			header('HTTP/1.0 404 Not Found');
			echo "Not found";
			exit;

		}
	}

	function get() {


		if( defined('ADMIN') ) {

			$appActions = new Actions();
			$twig = $appActions->Twigloader();
			$appActions->renderViewStatic($twig, $this->modelName);

		}



	}

	function post() {

		if( defined('ADMIN') ) {

			$varsModel =(isset($_POST['model'])) ? $_POST['model'] : false;
			$varsPage =(isset($_POST['pagename'])) ? $_POST['pagename'] : false;

			$app = new App;
			$response = $app->createPage($varsModel,$varsPage);
			if($response == 'error') { $app->setFlash('Page exsiste deja'); }
			else { $app->setFlash('Page '.$varsPage.' crée'); }
			$this->get();


		}



	}


}


