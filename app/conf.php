<?php

require_once(__DIR__."/lib/app.php");

class App extends AppOrigin {

	public function __construct() 
    { 
        // Key login
        $this->loginKey = "$2y$12$7eVYMvmhlCUTjTviJ07OxOd6DKCGRXT7mGtz5DETM/zKzOD.Ufe6a"; 

        // Routes
        $this->routes = array(
		    "/" => "IndexHandler",
		    //"/uuuu/:alpha" => "IndexHandler",
		    //"/index/:alpha/:alpha" => "IndexHandler"
		);

        // Twig Conf Assets
        $this->assets = "/public/assets/";
        $this->adminAssets = "/public/assets/admin/";
        $this->adminConfBase = "/public/admin.php";
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