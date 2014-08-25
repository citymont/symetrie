<?php

require_once(__DIR__."/lib/app.php");

class App extends AppOrigin {

	public function __construct() 
    { 
        // Key login
        $this->loginKey = "5d933eef19aee7da192608de61b6c23d"; 
        
        // Routes
        $this->routes = array(
		    "/" => "IndexHandler",
		    "/uuuu/:alpha" => "IndexHandler",
		    "/index/:alpha/:alpha" => "IndexHandler"
		);

        // Twig Conf Assets
        $this->assets = "/assets/";
        $this->adminAssets = "/assets/admin/";
        $this->adminConfUrl = "/admin.php/admin/";
        // TwigCustom
        
        // Cache Expire
        $this->cacheExpire = 0;
        $this->cacheTwig = false;

    }


}

class TwigData extends TwigDataOrigin {

    function __construct(){

        // Twig App Variables
        // https://gist.github.com/whitingx/3840905
        $this->meta_title = "";
        $this->meta_description = "single page editor";
        $this->meta_language = "";

    }

}