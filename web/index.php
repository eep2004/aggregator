<?php
define('DEBUG', 0);

define('ROOT', dirname(__DIR__));
define('ROOTWEB', __DIR__);
require_once ROOT.'/app/autoload.php';

session_start();

$router = new \app\Router();
$router->run();
