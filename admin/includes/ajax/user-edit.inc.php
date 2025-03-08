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

  $userId = htmlspecialchars($_POST['userId']) ;
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
  $status = htmlspecialchars($_POST['status']) ;

  // Email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    errorResponse("Invalid email address. Please enter a valid email.");
  }

  $birthDateTimestamp = strtotime($birthDate);
  $eighteenYearsAgo = strtotime('-18 years');
  if ($birthDateTimestamp > $eighteenYearsAgo || empty($birthDateTimestamp)) {
    errorResponse("Your age must be 18 or above.");
  }
  


  // print_r($_POST);

  // var_dump($userId);
  // var_dump($fullname);
  // var_dump($email);
  // var_dump($pwd);
  // var_dump($cpwd);
  // var_dump($address);
  // var_dump($contact);
  // var_dump($birthDate);
  // var_dump($role);
  // var_dump($verified);
  // var_dump($emailVerified);
  // var_dump($status);


  // exit();

  if(empty($_POST['pwd'])) {
    try {

      $pdo->beginTransaction();

      updateQuery(
        $pdo,
        "UPDATE users SET
        fullname = :fullname,
        email = :email,
        address = :address,
        user_contact = :contact,
        birth_date = :birthDate,
        role_id = :role,
        verified = :verified,
        email_verification = :emailVerified,
        user_status = :userStatus
        WHERE user_random_id = :userId
        ",
        [
          ":fullname" => $fullname,
          ":email" => $email,
          ":address" => $address,
          ":contact" => $contact,
          ":birthDate" => $birthDate,
          ":role" => $role,
          ":verified" => $verified,
          ":emailVerified" => $emailVerified,
          ":userStatus" => $status,
          ":userId" => $userId,
        ]
      );

      $pdo->commit();

      successResponse("User information edited successfully!");

    } catch(PDOException $e) {

      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }
  } else {

    // Password validation
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $pwd)) {
      errorResponse("Password must be at least 6 characters long and contain both letters and numbers.");
    }


    // Confirm Password
    if ($pwd != $cpwd) {

      errorResponse("Password and Confirm Password do not match.");
    }

    try {

      $pdo->beginTransaction();

      updateQuery(
        $pdo,
        "UPDATE users SET
        fullname = :fullname,
        email = :email,
        password = :password,
        address = :address,
        user_contact = :contact,
        birth_date = :birthDate,
        role_id = :role,
        verified = :verified,
        email_verification = :emailVerified,
        user_status = :userStatus
        WHERE user_random_id = :userId
        ",
        [
          ":fullname" => $fullname,
          ":email" => $email,
          ":password" => password_hash($pwd, PASSWORD_DEFAULT),
          ":address" => $address,
          ":contact" => $contact,
          ":birthDate" => $birthDate,
          ":role" => $role,
          ":verified" => $verified,
          ":emailVerified" => $emailVerified,
          ":userStatus" => $status,
          ":userId" => $userId,
        ]
      );

      $pdo->commit();

      successResponse("User information edited successfully!");

    } catch(PDOException $e) {

      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }



  }

  
  


}

?>