<?php

require_once(__DIR__."/../lib/app.php");

class App extends AppOrigin {

	public function __construct()
	{
    // Key login
		$this->loginKey = "CHANGE";

    // Routes
		$this->routes = array(
			"/" => "IndexHandler"
		);

    // Twig Conf Assets
		$this->assets = "/public/assets/";
		$this->adminAssets = "/public/assets/admin/";
		$this->adminConfBase = "/public/admin.php";

    // Dev Mode : model autoparser
		$this->devMode = false;

    // Cache Expire
		$this->cacheExpire = 10000;
		$this->cacheTwig = true;

	}


}
