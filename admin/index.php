<?php 
	
define("ASSETS", "http://localhost:8080/test/nocms/vendors/zenpen/");
define("ADMIN",true);

require(__DIR__."/../vendors/ToroPHP/src/Toro.php");
require(__DIR__."/../app/src/index.actions.php");
require(__DIR__."/../app/src/app.php"); 

/* ---- */

$app = new App();

ToroHook::add("before_handler", function($vars) { 
	
	/* Header */
	
	print'<!doctype html>	<html>';

	$vendor = ASSETS;
	include(__DIR__."/../app/views/main/editheader.tpl.html"); 

	print'<body class="yin">';

	include(__DIR__."/../app/views/main/editUI.tpl.html"); 

});


ToroHook::add("after_handler", function() { 

	/* Footer */

	$vendor = ASSETS;
	include(__DIR__."/../app/views/main/editJs.tpl.html"); 

	print'</body>	</html>';

});


$app->startRoutes();
