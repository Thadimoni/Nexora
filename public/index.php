<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../app/Config/Config.php";
require_once "../app/Helpers/functions.php";
require_once "../app/Bootstrap/Autoload.php";

ErrorHandler::register();

$db = new Database();
$conn = $db->connect();

App::run();