<?php
/**
 * Class ActionConf
 * -------------
 * fonctions liées à la configuration du templating
 * Cong : base
 * Custom : variables customisables
 */

class TwigConf {

	function __construct(){

		$a = new App();
		$this->assets = $a->assets;
		$this->assets_admin = $a->adminAssets;
		$this->admin_conf_base = $a->adminConfBase;  
        $this->admin_conf_url = $a->adminConfBase.'/admin/';
        $this->get_routes = $a->getRoutes();
        $this->get_real_routes = $a->getAllPages();
        $this->modelList = $a->getModelList();
        $this->assetsBuildCss = (isset($a->assetsBuildCss)) ? $a->assetsBuildCss : '';
        $this->assetsBuildJs = (isset($a->assetsBuildJs)) ? $a->assetsBuildJs : '';

	}

}

class TwigData {

	function __construct(){

		foreach ($this->getData('data','') as $key => $data) {
			$this->{$key} = $data;
		}


	}

	function getData($datafile,$sliceName) {
 		
 		$type = (strpos($datafile, 'slice') !== false) ? true : false ;

    	if ($type=="slice")  {
    		
    		list($t1, $datafile) = explode("/", $datafile);
    		$file =  __DIR__."/../data/slices/".$datafile.".json"; 
    		
    		try {
				if (!file_exists($file)) {
					// Create empty file 
					file_put_contents($file, "{}");
				} 
			} catch (Exception $e) {
				
			}

    	} else {
    		$file =  __DIR__."/../data/_".$datafile.".json"; // custom file
    	}

    	try {
			if (!file_exists($file)) {
				
				if($type=="slice") {
					$json2= '{ "'.$sliceName.'": ""}';
					return json_decode($json2, true);
				} else {
					throw new Exception('No readfile');
				}

			} else {
				
				if($type=="slice") {
					$json = file_get_contents($file);
					$json2= '{ "'.$sliceName.'": '. $json .'}';
				} else {
					$json2 = file_get_contents($file);
				}
				return json_decode($json2, true);
			}
			
		} catch (Exception $e) {
			if($type!="slice") { echo 'Erreur getdata() : ' . $e->getMessage(); }
		}
 
    }

}

