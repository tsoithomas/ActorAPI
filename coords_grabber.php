<?php
error_reporting(E_ALL ^ E_WARNING); 
require_once('vendor/autoload.php');
require_once("./sql.php");

$apiKey = $_ENV["GOOGLE_API_KEY"];

$result = $mysqli->query("SELECT * FROM cities ORDER BY cityid");

while ($row = $result->fetch_assoc()) {
    $parts = array();
    if ($row["city"] != "") array_push($parts, $row["city"]);
    if ($row["state"] != "") array_push($parts, $row["state"]);
    if ($row["country"] != "") array_push($parts, $row["country"]);

    $address = implode(", ", $parts);

    echo "[".$row["cityid"]."] Querying $address...\n";

    // Construct the Geocoding API URL
    $apiUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;

    // Make a cURL request to the Geocoding API
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if the request was successful
    if ($data['status'] === 'OK') {
        // Extract latitude and longitude
        $latitude = $data['results'][0]['geometry']['location']['lat'];
        $longitude = $data['results'][0]['geometry']['location']['lng'];

        // Update databse with coordinates
        $stmt = $mysqli->prepare("UPDATE cities SET lat=?, lng=? WHERE cityid=?");
        $stmt->bind_param("ddi", $latitude, $longitude, $row["cityid"]);
        $stmt->execute();

        // Output the coordinates
        echo "Result: $latitude, $longitude\n";
    } else {
        // Output an error message
        echo "Geocoding API request failed with status: " . $data['status'];
    }
}
