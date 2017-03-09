<?php

require_once(__DIR__."/../lib/app.php");

class App extends AppOrigin {

	public function __construct()
	{
    // Key login
		$this->loginKey = "$2y$12$7eVYMvmhlCUTjTviJ07OxOd6DKCGRXT7mGtz5DETM/zKzOD.Ufe6a";

    // Routes
		$this->routes = array(
			"/" => "IndexHandler",
      //"/:alpha" => "PageHandler",
		  //"/uuuu/:alpha" => "IndexHandler",
		  //"/index/:alpha/:alpha" => "IndexHandler"
		);

    // Twig Conf Assets
		$this->assets = "/assets/";
		$this->adminAssets = "/assets/admin/";
		$this->adminConfBase = "/admin.php";
		$this->urlUpload = "/contents/";

    // Dev Mode : model autoparser
		$this->devMode = true;

    // Cache Expire
		$this->cacheExpire = 0;
		$this->cacheTwig = false;

	}


}
