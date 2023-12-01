<?php
error_reporting(E_ALL ^ E_WARNING); 
require_once("./sql.php");

$apiKey = $_ENV["GOOGLE_API_KEY"];
$address = 'Toronto, Canada';

// Construct the Geocoding API URL
$apiUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;

// Make a cURL request to the Geocoding API
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Decode the JSON response
$result = json_decode($response, true);

// Check if the request was successful
if ($result['status'] === 'OK') {
    // Extract latitude and longitude
    $latitude = $result['results'][0]['geometry']['location']['lat'];
    $longitude = $result['results'][0]['geometry']['location']['lng'];

    // Output the coordinates
    echo "Latitude: $latitude, Longitude: $longitude";
} else {
    // Output an error message
    echo "Geocoding API request failed with status: " . $result['status'];
}

?>
