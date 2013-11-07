<?php

class AdminLoginHandler {

    function __construct() {
    	define('BRUT', true);
    	
	    if( !defined('ADMIN') ) { 
	    	
	    	header('HTTP/1.0 404 Not Found');
    		echo "Not found"; 
    		exit;  

	    }
    }

    function get() {
    	$app = new App;
		$loginkey =(isset($_GET['loginkey'])) ? $_GET['loginkey'] : die;

		if ( md5($loginkey) == $app->loginKey) {

			$_SESSION['key'] = $loginkey; 
			$_SESSION['role'] = 'ADMIN'; 
			header('Location: ../index');
			exit;

		} else {
			print 'pb connexion';
		}
      
    }

}


