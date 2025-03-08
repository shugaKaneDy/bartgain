<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  
  // Sanitize input values
  $currentPassword = filter_var($_POST['currentPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $newPassword = filter_var($_POST['newPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $confirmNewPassword = filter_var($_POST['confirmNewPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $options = [
    'cost' => 12
  ];

  // Password confirmation validation
  if(!password_verify($currentPassword, $_SESSION['user_details']['password'])) {
    errorResponse("Current password not match");
  }

  // Password validation
  if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $password)) {
    errorResponse("Password must be at least 6 characters long and contain both letters and numbers.");
  }


  // Confirm Password
  if ($newPassword != $confirmNewPassword) {

    errorResponse("Password and Confirm Password do not match.");
  }

  
  
  // print_r($_POST);
  // exit;
  try {
    
    $pdo->beginTransaction();
    updateQuery(
      $pdo,
      "UPDATE users SET
      password = :newPassword
      WHERE user_id = :userId",
      [
        ":newPassword" => password_hash($newPassword, PASSWORD_BCRYPT, $options),
        ":userId" => $_SESSION['user_details']['user_id'],
      ]
    );
    
    $pdo->commit();
    successResponse("Password updated successfully! Login again...");


  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }


}