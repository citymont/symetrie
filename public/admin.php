<?php 
if( !session_id() ) session_start();
define("ADMIN",true);

require(__DIR__."/../app/src/conf.php");
require(__DIR__."/../vendor/torophp/torophp/src/Toro.php");
require(__DIR__."/../app/src/index.actions.php");
require(__DIR__."/../app/src/admin/history.actions.php");
require(__DIR__."/../app/src/admin/login.actions.php");
require(__DIR__."/../app/src/admin/logout.actions.php");
require(__DIR__."/../app/src/app.php"); 

/* ---- */

$app = new App();

ToroHook::add("before_request", function($vars) { 

	if( isset($_GET['loginkey']) ) {
		// LoginHandler
	} else {
		$login = new App();
		if( isset($_SESSION['role']) and isset($_SESSION['key'])) {
			if ($_SESSION['role'] = 'ADMIN' and md5($_SESSION['key']) == $login->loginKey ) {

			} else {
				header('HTTP/1.0 401 Unauthorized');
		    	echo "Unauthorized"; 
		    	exit;
			}
		} else {
			header('HTTP/1.0 401 Unauthorized');
	    	echo "Unauthorized"; 
	    	exit;
		}
}
	

});

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
	$app = new App();
	print $app->getFlash();
	print'</body>	</html>';
	 }

});


$app->startRoutes();
