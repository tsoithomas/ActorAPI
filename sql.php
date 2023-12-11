<?php
require_once('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mysqli = new mysqli($_ENV["DB_HOST"],$_ENV["DB_USERNAME"],$_ENV["DB_PASSWORD"],$_ENV["DB_DATABASE"]);

// Check connection
if ($mysqli -> connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
	exit();
}

$mysqli->set_charset("utf8");
