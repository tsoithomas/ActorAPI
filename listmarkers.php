<?php
require_once("./sql.php");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/javascript');
?>
import { addMarker, clearMarkers } from "./map.js";
clearMarkers();
<?php

$actorsPerPage = 10;
$page = isset($_GET["p"]) ? $_GET["p"] : 1;
$offset = ($page-1) * $actorsPerPage;

$result = $mysqli->query("SELECT actorname, birthyear, city, `state`, country, lat, lng FROM actors, cities WHERE actors.cityid = cities.cityid LIMIT $offset, 10");
while ($row = $result->fetch_assoc()) {
    $parts = array();
    if ($row["city"] != "")
        array_push($parts, $row["city"]);
    if ($row["state"] != "")
        array_push($parts, $row["state"]);
    if ($row["country"] != "")
        array_push($parts, $row["country"]);
    $fullcity = implode(", ", $parts);
    ?>
addMarker(
    {lat: <?php echo $row["lat"];?>, lng: <?php echo $row["lng"];?>}, 
    "<?php echo $row["actorname"];?>",
    "<?php echo $row["birthyear"];?>",
    "<?php echo $fullcity;?>"
    );
    <?php
}
