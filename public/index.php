<?php

require(__DIR__."/../app/src/conf.php");
require(__DIR__."/../vendor/torophp/torophp/src/Toro.php");
require(__DIR__."/../app/src/cache.php");
require(__DIR__."/../app/src/actions.php");
require(__DIR__."/../app/src/app.php");
require(__DIR__."/../app/src/index.actions.php");

/* ---- */

$app = new App();

ToroHook::add("before_handler", function($vars) { 

	$appCache = new Cache(); 
	
	$cacheName = md5($_SERVER['REQUEST_URI']);
	$cache = __DIR__.'/../app/storage/cache/'.$cacheName.'.cache.html';

    if($appCache->check_cache($cache) == true) {
		readfile($cache);	 
	}
	else { 
		define("CACHE_FLAG", true); 
		$appCache->start();

	}
});

ToroHook::add("after_handler", function() { 

	if( defined('CACHE_FLAG') ) { 

		$appCache = new Cache(); 
		$cacheName = md5($_SERVER['REQUEST_URI']);
		$cache = __DIR__.'/../app/storage/cache/'.$cacheName.'.cache.html';

		$cachecontent = ob_get_contents();

		$appCache->end($cache,$cachecontent);
	}

});

$app->startRoutes();