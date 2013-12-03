<?php

class IndexHandler {

	private $modelName = "index";

    function __construct() {

    	// Slug de la page
    	$app = new App();
    	$uri = $app->parseUrl('/');
    	
    	$dir = __DIR__."/../data/".$this->modelName."".$uri['id']."";

	    if (!file_exists($dir)) {
	    	
	    	$app->error404();

	    }
    }

    function get($name = null, $b = null) {

    	$appActions = new Actions(); 

		    if( defined('CACHE_FLAG') ) { 
		    	
				$twig = $appActions->Twigloader();

				// Slug de la page
    			$app = new App();
    			$uri = $app->parseUrl('/');

				$file = __DIR__."/../../app/data/".$this->modelName."".$uri['id']."/choose.json"; // last file

				try {
					if (!file_exists($file)) {
						throw new Exception('No data');
					} else {
						$json = file_get_contents($file);
						// Render
						echo $twig->render($this->modelName.'.html.twig', json_decode($json, true));
					}
					
				} catch (Exception $e) {
					 echo 'Erreur : ' . $e->getMessage();
				}

			}

			if( defined('ADMIN') ) { 
				
				$appActions->Admin($this->modelName); 
		
			}

      
    }
}


