<?php 
	
define("ASSETS", "http://localhost:8080/symetrie/public/");
define("ADMIN",true);

require(__DIR__."/../vendor/torophp/torophp/src/Toro.php");
require(__DIR__."/../app/src/index.actions.php");
require(__DIR__."/../app/src/admin/history.actions.php");
require(__DIR__."/../app/src/app.php"); 

/* ---- */

$app = new App();

ToroHook::add("before_handler", function($vars) { 
	
	/* Header */
	if( ! defined('BRUT') ) { 
	print'<!doctype html>	<html>';

	$vendor = ASSETS;
	include(__DIR__."/../app/views/main/editheader.tpl.html"); 

	print'<body class="yin">';

	include(__DIR__."/../app/views/main/editUI.tpl.html");
	 }

});


ToroHook::add("after_handler", function() { 

	/* Footer */
	if( ! defined('BRUT') ) { 
	$vendor = ASSETS;
	include(__DIR__."/../app/views/main/editJs.tpl.html"); 

	print'</body>	</html>';
	 }

});


$app->startRoutes();
