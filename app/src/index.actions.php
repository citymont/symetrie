<?php

class IndexHandler {

	private $modelName = "index";

    function __construct() {

    	// Slug de la page
    	$uri = App::parseUrl('/');
    	
    	$dir = __DIR__."/../data/".$this->modelName."".$uri['id']."";

	    if (!file_exists($dir)) {
	    	
	    	header('HTTP/1.0 404 Not Found');
    		echo "Not found"; 
    		exit;  

	    }
    }

    function get($name = null, $b = null) {

		    if( defined('CACHE_FLAG') ) { 

		    	$appActions = new Actions(); 
				$twig = $appActions->Twigloader();

				// Slug de la page
    			$uri = App::parseUrl('/');

				$file = __DIR__."/../../app/data/".$this->modelName."".$uri['id']."/choose.json"; // last file

				try {
					if (!file_exists($file)) {
						throw new Exception('No data');
					} else {
						$json = file_get_contents($file);
						// Render
						echo $twig->render($this->modelName.'.tpl.html', json_decode($json, true));
					}
					
				} catch (Exception $e) {
					 echo 'Erreur : ' . $e->getMessage();
				}

				

			}

			if( defined('ADMIN') ) { 

				include(__DIR__."/../../app/model/".$this->modelName.".editable.html"); 
		
			}

      
    }
}


