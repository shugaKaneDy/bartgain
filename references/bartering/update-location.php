<?php
session_start();
require_once "dbcon.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];
    $address = $_POST['address'];
    $userId = $_SESSION['user_details']['user_id']; // Assuming user ID is stored in session

    try {
        // Prepare SQL query using named placeholders
        $query = "UPDATE users SET lat = :lat, lng = :lng, address = :address WHERE user_id = :user_id";
        $stmt = $conn->prepare($query);

        // Bind parameters to the query
        $data = [
            ":lat" => $lat,
            ":lng" => $lng,
            ":address" => $address,
            ":user_id" => $userId
        ];

        // Execute the query
        $query_execute = $stmt->execute($data);

        // Check if the update was successful
        if ($query_execute) {
            $_SESSION['user_details']['lat'] = $lat;
            $_SESSION['user_details']['lng'] = $lng;
            echo "Location updated successfully";
        } else {
            echo "Error updating location";
        }
    } catch (PDOException $e) {
        // Handle database connection or query execution errors
        echo "Database Error: " . $e->getMessage();
    }
}
?>
