<?php
require_once "Dispatcher.php";
require_once "NRVRepository.php";
require_once "Spectacle.php";
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

$dispatcher = new Dispatcher();
$dispatcher->run();


