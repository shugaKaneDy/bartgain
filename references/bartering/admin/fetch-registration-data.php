<?php
require_once '../dbcon.php'; // Adjust path as per your file structure

header('Content-Type: application/json');

$query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(user_id) as registrations 
          FROM users 
          GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
          ORDER BY month";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>
