<?php

class AdminLogoutHandler {

    function __construct() {
    	
	    if( !defined('ADMIN') ) { 
	    	
	    	header('HTTP/1.0 404 Not Found');
    		echo "Not found"; 
    		exit;  

	    }
    }

    function get() {
    	
		if ( isset($_SESSION['key']) ) {

			unset($_SESSION['key']); 
			unset($_SESSION['role']); 
			unset($_SESSION);
			session_unset();
    		session_destroy();

			print 'Bye Bye';

		} else {
			
			print 'Bye Bye';
			
		}
      
    }

}


