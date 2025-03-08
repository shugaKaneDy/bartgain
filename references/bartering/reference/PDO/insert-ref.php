<?php

require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];

  $query = "INSERT INTO users (name, email) VALUES (:name, :email)";
  $stmt = $conn->prepare($query);
  $stmt->execute(['name' => $name, 'email' => $email]);

  echo "Record added successfully";
}