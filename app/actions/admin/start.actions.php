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


}


