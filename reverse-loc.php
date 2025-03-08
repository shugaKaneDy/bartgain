<?php
// Function to get latitude and longitude from IP address using ipinfo.io
function getCoordinatesFromIP($ip) {
    $url = "http://ipinfo.io/{$ip}/json";

    // Make request to get location data
    $locationData = @file_get_contents($url);  // Suppress errors with @
    
    // Check if the request was successful
    if ($locationData === FALSE) {
        return "Error: Unable to connect to ipinfo.io";
    }

    $locationData = json_decode($locationData, true);

    // Check if latitude and longitude are available
    if (isset($locationData['loc'])) {
        list($latitude, $longitude) = explode(',', $locationData['loc']);
        return ['latitude' => $latitude, 'longitude' => $longitude];
    } else {
        // Debug output for more details on failure
        return "Error: IP geolocation data not found. Response: " . json_encode($locationData);
    }
}

// Test with a known IP address for development (replace with your IP address if testing locally)
//$ipAddress = "8.8.8.8"; // Example IP
$ipAddress = $_SERVER['REMOTE_ADDR'];

// Get latitude and longitude from IP
$coordinates = getCoordinatesFromIP($ipAddress);

if (is_array($coordinates)) {
    // Perform reverse geocoding using the coordinates
    $address = reverseGeocode($coordinates['latitude'], $coordinates['longitude']);
    echo "Address: " . $address;
} else {
    // Output error message
    echo $coordinates;
}
?>
