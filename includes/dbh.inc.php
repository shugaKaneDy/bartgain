<?php

date_default_timezone_set('Asia/Manila');
$host = "localhost";
$dbname = "bartgain";
$dbusername = "root";
$dbpassword = "";

try {

  // $pdo = new PDO($dsn, $dbusername, $dbpassword);
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;", $dbusername, $dbpassword);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

