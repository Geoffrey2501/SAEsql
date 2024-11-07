<?php


require_once "vendor/autoload.php";

use iutnc\NRV\dispatch\Dispatcher;



error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

$dispatcher = new Dispatcher();
$dispatcher->run();


