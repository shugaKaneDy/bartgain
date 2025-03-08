<?php

require_once 'dbcon.php';

$id = $_GET["id"];

$query = "SELECT * FROM items WHERE item_random_id = '$id'";
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

print_r($results);