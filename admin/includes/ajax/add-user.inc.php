<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../../../includes/dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  if(isset($_SESSION['user_details'])) {
    if($_SESSION['user_details']['role_id'] != 2) {
      exit;
    }
  } else {
    exit;
  }

  $fullname = htmlspecialchars($_POST['fullname']) ;
  $email = htmlspecialchars($_POST['email']) ;
  $pwd = htmlspecialchars($_POST['pwd']) ;
  $cpwd = htmlspecialchars($_POST['cpwd']) ;
  $address = htmlspecialchars($_POST['address']) ;
  $contact = htmlspecialchars($_POST['contact']) ;
  $birthDate = htmlspecialchars($_POST['birthDate']) ;
  $role = htmlspecialchars($_POST['role']) ;
  $verified = htmlspecialchars($_POST['verified']) ;
  $emailVerified = htmlspecialchars($_POST['emailVerified']) ;

  // print_r($_POST);
  // exit();

  // Empty Inputs
  if(empty($fullname) || empty($email) || empty($pwd) || empty($cpwd) || empty($address) || empty($contact) || empty($birthDate) || empty($role) || empty($verified) || empty($emailVerified)) {

    errorResponse("Fill up all the fields");
  }

  if($emailVerified == "Yes") {
    $emailVerified = 1;
  } else {
    $emailVerified = 0;
  }

  // print_r($emailVerified);
  // exit();

  $birthDateTimestamp = strtotime($birthDate);
  $eighteenYearsAgo = strtotime('-18 years');
  if ($birthDateTimestamp > $eighteenYearsAgo) {
    errorResponse("Your age must be 18 or above.");
  }

  // Email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    errorResponse("Invalid email address. Please enter a valid email.");
  }

  // Password validation
  if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $pwd)) {
    errorResponse("Password must be at least 6 characters long and contain both letters and numbers.");
  }


  // Confirm Password
  if ($pwd != $cpwd) {

    errorResponse("Password and Confirm Password do not match.");
  }

  // check if email exist
  $existEmail = selectQuery(
    $pdo,
    "SELECT email FROM users WHERE email = :email",
    ["email" => $email]
  );

  if($existEmail) {

    errorResponse("Email address already registered");
  }

  try {

    $pdo->beginTransaction();

    // INSERT QUERY
    insertQuery(
      $pdo,
      "INSERT INTO users
      (
          user_random_id,
          role_id,
          fullname,
          birth_date,
          email,
          password,
          user_contact,
          address,
          current_location,
          lng,
          lat,
          email_verification,
          verified,
          user_created_at
      )
      VALUES
      (
          :userRandomId,
          :roleId,
          :fullname,
          :birthDate,
          :email,
          :password,
          :userContact,
          :address,
          :currentLocation,
          :lng,
          :lat,
          :emailVerification,
          :verified,
          :userCreatedAt
      )",
      [
          ':userRandomId' => rand(100000000, 999999999),
          ':roleId' => $role,
          ':fullname' => $fullname,
          ':birthDate' => $birthDate,
          ':email' => $email,
          ':password' => password_hash($pwd, PASSWORD_DEFAULT), // hashed password for security
          ':userContact' => $contact,
          ':address' => $address,
          ':currentLocation' => $_SESSION['user_details']['current_location'],
          ':lng' => $_SESSION['user_details']['lng'],
          ':lat' => $_SESSION['user_details']['lat'],
          ':emailVerification' => $emailVerified, // e.g., verification token
          ':verified' => $verified, // 0 for unverified, 1 for verified
          ':userCreatedAt' => $currentTime // current timestamp
      ]
    );

    $pdo->commit();

    successResponse("User added successfully!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }


  // print_r($_POST);
  // exit();


}

?>