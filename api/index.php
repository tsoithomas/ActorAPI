<?php
require_once("../sql.php");

$action = $_GET["action"];


switch ($action) {
	case "actorcount":
		require_once("../controllers/actorcount.php");
		break;
    default:
		die("Error");
}


