<?php

/**
 * Validator for admin req
 */

class AdminValidator {

	/**
	 * Validator
	 * @param  string $var  get or post var
	 * @param  string $name name of filter
	 * @param  string $var2 other var
	 * @return string       string or 500
	 */
	function validator( $var, $name, $var2 = null) {

    	$var = htmlentities($var, ENT_QUOTES, "UTF-8");

    	if( $var2 ) {$var2 = htmlentities($var2, ENT_QUOTES, "UTF-8"); }

    	if( $var ) {

	    	switch ($name) {

	    		case 'model':
	    			$pass = $this->validatorModel(__DIR__.'/../data'); 
	    			return (in_array($var, $pass)) ? $var : $this->e500() ;
	    			break;

	    		case 'model_id':
	    			$passModel = $this->validatorModel(__DIR__.'/../data');
	    			$test = (in_array($var2, $passModel)) ? true : $this->e500();
	    			if($test === true ) {
	    				$passId = $this->validatorModel(__DIR__.'/../data/'.$var2);
	    				return (in_array($var, $passId)) ? $var : $this->e500() ;
	    			}
	    			break;

	    		case 'publish':
	    			$pass = array('true','false');
	    			return (in_array($var, $pass)) ? $var : $this->e500() ;
	    			break;

	    		case 'method':
	    			$pass = array('list','one');
	    			return (in_array($var, $pass)) ? $var : $this->e500() ;
	    			break;

	    		case 'filename':
	    			$info = pathinfo($var); 
	    			if( mb_strlen($info['filename']) === mb_strlen(intval($info['filename'])) or $var == "choose.json") {
	    				return ($info['extension'] == "json" ) ? $var : $this->e500();
	    			} else {
	    				$this->e500();
	    			}
	    			
	    			break;

	    	}
    	}

    }

    /**
     * Model validator
     * @param  string $dir path
     * @return array      array of existing models
     */
    function validatorModel($dir) {

    	$out= array();

	    foreach(glob($dir . '/*') as $file) {
	        if(is_dir($file)){
	        	$dirI = pathinfo($file);
	        	$out[] = $dirI['basename'];
	        }
	            
	    }

	    return $out;
	}
	/**
	 * Error 500 generator
	 * @return header
	 */
	function e500() {
		print 'oups';
		//header('HTTP/1.1 500 Internal Server Error');
		//print_r(debug_backtrace());
		exit;
	}
}