<?php

require_once(__DIR__."/../app/config/conf.php");
require_once(__DIR__."/../app/actions/autoload.php");

$app = new App();

$app->startCache();

$app->startRoutes();