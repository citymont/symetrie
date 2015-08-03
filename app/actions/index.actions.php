<?php

class IndexHandler {

	private $modelName = "";
	private $docId = "";

    function __construct() {

    	$app = new App();
    	$infos = $app->getRouteInfos('/');
    	$this->modelName = $infos['model'];
    	$this->docId = $infos['id'];

    	if($app->devMode) $app->devModeAutoParser($infos['model']);
 
    	
    }

    function get($name = null, $b = null) {

    	$appActions = new Actions(); 

		    if( defined('CACHE_FLAG') ) { 
		    	
				$twig = $appActions->Twigloader();

				$appActions->renderView($twig, $this->modelName,$this->docId);

			}

			if( defined('ADMIN') ) { 
				
				$appActions->Admin($this->modelName); 
		
			}

      
    }
}


