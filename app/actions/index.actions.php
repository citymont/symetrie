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
		$arrayData = array();

    	// Ex : Load Slices data
      //
      // $dataloader = new TwigData();
    	// $dataSlice1 = $dataloader->getData('slices/person','person');
    	// $dataSlice2 = $dataloader->getData('slices/person2','person2');
    	// $arrayData = array_merge($dataSlice1,$dataSlice2);


		if( defined('CACHE_FLAG') ) {

			$twig = $appActions->Twigloader();

			$appActions->renderView($twig, $this->modelName,$this->docId);

                // Ex : Load Slices data
                //
				//$appActions->renderViewExtended($twig, $this->modelName,$this->docId,$arrayData);


		}

		if( defined('ADMIN') ) {

			$appActions->Admin($this->modelName,$arrayData);

		}


	}
}


