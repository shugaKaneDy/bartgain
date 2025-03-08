<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['id'];

  $sql = "DELETE FROM users WHERE id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['id' => $id]);

  echo "Record deleted successfully";
}