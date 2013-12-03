<?php 
if( !session_id() ) session_start();
define("ADMIN",true);

require(__DIR__."/../app/src/conf.php");
require(__DIR__."/../vendor/torophp/torophp/src/Toro.php");
require(__DIR__."/../app/src/actions.php");
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
				$login->error401();
			}
		} else {
			$login->error401();
		}
}
	

});

ToroHook::add("before_handler", function($vars) { });

ToroHook::add("after_handler", function() { 

	$app = new App();
	print $app->getFlash();

});


$app->startRoutes();
