<?php

require_once(__DIR__."/lib/app.php");

class App extends AppOrigin {

	public function __construct() 
    { 
        // Key login
        $this->loginKey = "aa3261152486caad6c230b4b6d384361"; 
        
        // Routes
        $this->routes = array(
		    "/" => "IndexHandler",
		    "/uuuu/:alpha" => "IndexHandler",
		    "/index/:alpha/:alpha" => "IndexHandler"
		);

        // Twig Conf
        $this->viewsAssets = "/";
        $this->viewsAdminConfUrl = "/admin.php/admin/"; 

        // Cache Expire
        $this->cacheExpire = 0;

    }


}