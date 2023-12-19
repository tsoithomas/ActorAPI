<?php
require_once("../sql.php");

$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
parse_str($query, $get);

$response = array();

$accountid = authenticate();
check_rate($accountid);
fetch_data();


function authenticate() {
	global $get, $mysqli;
	
	if (!isset($get['accountid'])) return_json(401);

	$stmt = $mysqli->prepare("SELECT secret FROM accounts WHERE accountid = ?");
	$stmt->bind_param("i", $get["accountid"]);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if ($result->num_rows == 0) return_json(401);
	
	list($secret) = $result->fetch_row();
	$timestamp = floor(time()/60);
	$hashed_secret = hash_hmac("sha512", $secret, $timestamp);
	
	$timestamp_prev = floor(time()/60)-1;
	$hashed_secret_prev = hash_hmac("sha512", $secret, $timestamp_prev);
	
	if ($get['hash'] != $hashed_secret && $get['hash'] != $hashed_secret_prev) return_json(401);	

	return $get['accountid'];
}

function check_rate($accountid) {
	global $get, $mysqli, $response;

	$stmt = $mysqli->prepare("SELECT COUNT(*) FROM calls WHERE accountid=? AND calltime> DATE_ADD(NOW(), INTERVAL -1 MINUTE)");
	$stmt->bind_param("i", $accountid);
	$stmt->execute();
	$result = $stmt->get_result();
	list($minute_rate) = $result->fetch_row();

	$stmt = $mysqli->prepare("SELECT ratelimit FROM accounts WHERE accountid=?");
	$stmt->bind_param("i", $accountid);
	$stmt->execute();
	$result = $stmt->get_result();
	list($ratelimit) = $result->fetch_row();

	if ($ratelimit != 0 && $minute_rate >= $ratelimit) return_json(429);
}

function fetch_data() {
	global $get, $mysqli, $response;
	if (!isset($get['city']) && !isset($get['state']) && !isset($get['country'])) return_json(400);

	if (isset($get["state"])) 
		fetch_state();
	elseif (isset($get["country"])) 
		fetch_country();
}

function fetch_state() {
	global $get, $mysqli, $response;

	$stmt = $mysqli->prepare("SELECT city, COUNT(*) AS cnt FROM actors, cities WHERE actors.cityid=cities.cityid AND `state`=? GROUP BY city");
	$stmt->bind_param("s", $get["state"]);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if ($result->num_rows == 0) return_json(400);

	$cnt = 0;
	$response["count"] = 0;
	$response["cities"] = array();

	while ($row = $result->fetch_assoc()) {
		$response["cities"][$row["city"]] = $row["cnt"];
		$response["count"] += $row["cnt"];
	}
	return_json(200);
}

function fetch_country() {
	global $get, $mysqli, $response;

	$stmt = $mysqli->prepare("SELECT city, `state`, COUNT(*) AS cnt FROM actors, cities WHERE actors.cityid=cities.cityid AND country=? GROUP BY city, `state` ORDER BY `state`, city");
	$stmt->bind_param("s", $get["country"]);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if ($result->num_rows == 0) return_json(400);

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
	return_json(200);
}

function return_json($code) {
	global $response;
	if ($code == 200) log_call();

	$response["status"] = $code;
	echo json_encode($response, JSON_PRETTY_PRINT);
	exit();
}

function log_call() {
	global $mysqli, $get;

	$stmt = $mysqli->prepare("INSERT INTO calls (accountid, calltime) VALUES(?, NOW())");
	$stmt->bind_param("i", $get['accountid']);
	$stmt->execute();
}
