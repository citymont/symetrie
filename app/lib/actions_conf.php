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
        $this->admin_conf_url = $a->adminConfUrl; 

	}

}

class TwigDataOrigin {

	function __construct(){


	}

}