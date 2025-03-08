<?php
session_start();
require_once "../dbcon.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullname = $_POST['fullname'];
  $age = $_POST['age'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $latitude = $_POST['latitude'];
  $address = $_POST['address'];
  $longitude = $_POST['longitude'];

  $query = "INSERT INTO users (fullname, age, email, password, address, lat, longi) VALUES (:fullname, :age, :email, :password, :address, :lat, :longi)";
  $stmt = $conn->prepare($query);

  $data = [
    ":fullname" => $fullname,
    ":age" => $age,
    ":email" => $email,
    ":password" => $password,
    ":address" => $address,
    ":lat" => $latitude,
    ":longi" => $longitude
  ];

  $query_execute = $stmt->execute($data);

  if($query_execute) {
    $_SESSION['message'] = "Inserted Successfully";
    echo "Inserted Successfully";
    header("location: ../register.php");
    exit(0);
  } else {
    $_SESSION['message'] = "Not Inserted";
    header("location: ../register.ph");
    exit(0);
  }

}