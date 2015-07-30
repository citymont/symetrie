<?php

class AdminLoginHandler {

	private $modelName = "";

    function __construct() {

    	$this->modelName = 'admin/login';
    	
	    if( !defined('ADMIN') ) { 
	    	
	    	header('HTTP/1.0 404 Not Found');
    		echo "Not found"; 
    		exit;  

	    }
    }

    function get() {

    	$app = new App;
		$loginkey =(isset($_GET['loginkey'])) ? $_GET['loginkey'] : false; 
		//print $loginkey;
		
		if(isset($_SESSION['role']) &&  $_SESSION['role'] == 'ADMIN') {
			$app->setFlash('Already Logged');
			
			header('Location: start');
			exit;
		}

		if (!$loginkey) { 
			$appActions = new Actions(); 
		    $twig = $appActions->Twigloader();
			$appActions->renderViewStatic($twig, $this->modelName);
		}
      
    }

    function post() {

    	$app = new App;
		$loginkey =(isset($_POST['loginkey'])) ? $_POST['loginkey'] : false; 
		//print $loginkey;
		if (!$loginkey) { 
			$appActions = new Actions(); 
		    $twig = $appActions->Twigloader();
			$appActions->renderViewStatic($twig, $this->modelName);
		}

		if ( $app->passwordCheck($loginkey, $app->loginKey) ) {

			$_SESSION['key'] = $loginkey; 
			$_SESSION['role'] = 'ADMIN'; 
			$app->setFlash('Logged in with success');
			
			header('Location: start');
			exit;

		} else if ($loginkey) {
			$appActions = new Actions(); 
		    $twig = $appActions->Twigloader();
			$appActions->renderViewStatic($twig, $this->modelName);
			$app->setFlash('Unknown password');
		}


      
    }

}


