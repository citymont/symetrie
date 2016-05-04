<?php 

if( !session_id() ) session_start();
define("ADMIN",true);

require(__DIR__."/../app/config/conf.php");
require_once(__DIR__."/../app/actions/autoload.php");

$app = new App();

$app->startPrivateAdmin();

$app->startRoutes();