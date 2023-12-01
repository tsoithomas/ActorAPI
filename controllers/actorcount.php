<?php
require_once("./sql.php");

$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
parse_str($query, $get);

$response = array();

if (!isset($get['city']) && !isset($get['state']) && !isset($get['country'])) {
    $response["status"] = "error";
    $response["message"] = "Parameters missing";
}

if (isset($get["state"])) {
    $stmt = $mysqli->prepare("SELECT city, COUNT(*) AS cnt FROM actors, cities WHERE actors.cityid=cities.cityid AND `state`=? GROUP BY city");
    $stmt->bind_param("s", $get["state"]);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cnt = 0;
    $response["count"] = 0;
    $response["cities"] = array();

    while ($row = $result->fetch_assoc()) {
        $response["cities"][$row["city"]] = $row["cnt"];
        $response["count"] += $row["cnt"];
    }
}

if (isset($get["country"])) {
    $stmt = $mysqli->prepare("SELECT city, `state`, COUNT(*) AS cnt FROM actors, cities WHERE actors.cityid=cities.cityid AND country=? GROUP BY city, `state` ORDER BY `state`, city");
    $stmt->bind_param("s", $get["country"]);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $count = 0;
    $curState = "";
    $stateCount = "";
    $response["count"] = 0;
    $response["states"] = array();
    while ($row = $result->fetch_assoc()) {
        $city = $row["city"];
        $state = $row["state"];
        $cnt = $row["cnt"];

        if (!isset($response["states"][$state])) {
            $response["states"][$state] = array();
            $response["states"][$state]["count"] = 0;
        }

        $response["states"][$state]["cities"][$city] = $cnt;

        $response["states"][$state]["count"] += $cnt;
        $response["count"] += $cnt;
    }
}


echo json_encode($response, JSON_PRETTY_PRINT);
