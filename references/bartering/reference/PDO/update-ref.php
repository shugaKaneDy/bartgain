<?php
require 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $query = "UPDATE users SET name = :name, email = :email WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['name' => $name, 'email' => $email, 'id' => $id]);

    echo "Record updated successfully";
}