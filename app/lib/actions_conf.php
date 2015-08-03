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

	}

}

class TwigData {

	function __construct(){

		foreach ($this->getData('data') as $key => $data) {
			$this->{$key} = $data;
		}


	}

	function getData($datafile) {
 
    	$file = __DIR__."/../data/_".$datafile.".json"; // custom file
 
    	try {
			if (!file_exists($file)) {
				throw new Exception('No readfile');
			} else {
				$json2 = file_get_contents($file);
				return json_decode($json2, true);
			}
			
		} catch (Exception $e) {
			 echo 'Erreur getdata() : ' . $e->getMessage();
		}
 
    }

}

