<?php
date_default_timezone_set('Asia/Manila');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bart_gain";

$conn = new mysqli($servername, $username, $password, $dbname);

// $currentTimestamp = date('Y-m-d H:i:s');
// echo "Current Timestamp (Manila Time): " . $currentTimestamp;

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>