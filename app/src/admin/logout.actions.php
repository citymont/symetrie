<?php

class AdminLogoutHandler {

    function __construct() {
    	define('BRUT', true);
    	
	    if( !defined('ADMIN') ) { 
	    	
	    	header('HTTP/1.0 404 Not Found');
    		echo "Not found"; 
    		exit;  

	    }
    }

    function get() {
    	
		if ( $_SESSION['key'] ) {

			unset($_SESSION['key']); 
			unset($_SESSION['role']); 
			unset($_SESSION);
			header('Location: ../index');
			exit;

		} else {
			print 'Already loggout';
		}
      
    }

}


