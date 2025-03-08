<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../mail/PHPMailer/src/Exception.php';
require '../../mail/PHPMailer/src/PHPMailer.php';
require '../../mail/PHPMailer/src/SMTP.php';

function errorResponse($message) {

  $respond = [
    'status' => 'error',
    'title' => $message
  ];
  echo json_encode($respond);
  exit;
}

function successResponse($message) {

  $respond = [
    'status' => 'success',
    'title' => $message
  ];
  echo json_encode($respond);
  exit;
}

function emailVerificationOTP($email) {

  $otp = rand(100000,999999);
  $_SESSION['otp'] = $otp;
  $_SESSION['mail'] = $email;
  $_SESSION['time'] = time() + 180;

  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP(); 
    $mail->SMTPAuth = true; 

    $mail->Host = 'smtp.gmail.com';
    $mail->Username = 'ahnyudaengz5@gmail.com'; 
    $mail->Password = 'eucojwskyjganbqb';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('ahnyudaengz5@gmail.com', 'Bart Gain');
    $mail->addAddress($email);

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'OTP Verification BartGain';
    $mail->Body = '<h3>Your OTP verification code is: '. $otp .'</h3>';

    $mail->send();

  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}

function selectQuery($pdo, $query, $data) {

  $stmt = $pdo->prepare($query);
  $stmt->execute($data);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return $results;
}

function selectQueryFetch($pdo, $query, $data) {

  $stmt = $pdo->prepare($query);
  $stmt->execute($data);
  $results = $stmt->fetch(PDO::FETCH_ASSOC);

  return $results;
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')  {

  require_once "../dbh.inc.php";
  session_start();

  $function = $_GET["function"];
  $currentTime  = date("Y-m-d H:i:s");

  if($function == 'signup') {

    // var_dump($_POST);


    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token_up']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_up']) {
      errorResponse("Invalid CSRF token");
    }

    $fullname = htmlspecialchars($_POST['fullname'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = htmlspecialchars($_POST['password'] ?? '');
    $confirmPassword = htmlspecialchars($_POST['confirmPassword'] ?? '');
    $currentLoc = htmlspecialchars($_POST['currentLoc'] ?? '');
    $lng = htmlspecialchars($_POST['lng'] ?? '');
    $lat = htmlspecialchars($_POST['lat'] ?? '');
    $checkAgree = htmlspecialchars($_POST['checkAgree'] ?? '');

    // Empty Inputs
    if(empty($email) || empty($password) || empty($fullname)) {

      errorResponse("Fill up all the fields");
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

      errorResponse("Invalid email address. Please enter a valid email.");
    }

    // Password validation
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $password)) {
      errorResponse("Password must be at least 6 characters long and contain both letters and numbers.");
    }

    // Confirm Password
    if ($password != $confirmPassword) {

      errorResponse("Password and Confirm Password do not match.");
    }

    // Agreement
    if(empty($checkAgree)) {

      errorResponse("You must agree to the terms and conditions");
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

      $query = "INSERT INTO users(user_random_id, role_id, fullname, email, password, current_location, lng, lat, user_created_at)
                VALUES (:userRandomId, :role_id, :fullname, :email, :password, :current_location, :lng, :lat, :user_created_at)";
      $stmt = $pdo->prepare($query);

      $options = [
        'cost' => 12
      ];

      $data = [
        ":userRandomId" => rand(100000000, 999999999),
        ":role_id" => 1,
        ":fullname" => $fullname,
        ":email" => $email,
        ":password" => password_hash($password, PASSWORD_BCRYPT, $options),
        ":current_location" => $currentLoc,
        ":lng" => $lng,
        ":lat" => $lat,
        ":user_created_at" => $currentTime
      ];
      
      $queryExecute = $stmt->execute($data);

      $pdo->commit();

      if ($queryExecute) {

        emailVerificationOTP($email);
        unset($_SESSION['csrf_token_up']);
        successResponse("Account created successfully");
      } else {
  
        errorResponse("Account not registered");
      }

    } catch (Exception $e) {

      $pdo->rollBack();
      echo "Failed to complete transaction: " . $e->getMessage();
      exit();
    }

  }

  if($function == "resend") {

    emailVerificationOTP($_SESSION["mail"]);
  }

  if($function == "email-verification") {

    $otpCode = $_POST["otpCode"];

    if(empty($otpCode)) {

      errorResponse("Fill up the field");
    }

    if(($_SESSION["time"] + 5) < time()) {

      errorResponse("OTP Expired. Resend OTP");
    }

    if($_SESSION["otp"] == $otpCode ) {

      $query = "UPDATE users SET email_verification = 1 WHERE email = :email";
      $stmt = $pdo->prepare($query);
      $data = [
        ":email" => $_SESSION["mail"]
      ];

      $query_execute = $stmt->execute($data);

      if($query_execute) {

        successResponse("Email verified successfully");
      } else {

        errorResponse("Failed to verify Email");
      }

    } else {

      errorResponse("Wrong OTP code. Try again!");
    }

  }

  if($function == "signin") {

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token_in']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_in']) {
      errorResponse("Invalid CSRF token");
    }

    $email = htmlspecialchars($_POST['email']) ;
    $password = htmlspecialchars($_POST['password']) ;
    $location = htmlspecialchars($_POST['location']);
    $ip_address = $_SERVER['REMOTE_ADDR'];


    $user_data = selectQueryFetch($pdo, "SELECT * FROM users WHERE email = :email", [":email" => $email]);

    if($user_data && password_verify($password, $user_data['password'])) {

      if($user_data['user_status'] == "inactive") {
        errorResponse("Your account is temporarily banned until " . date("F j, Y \a\t g:i A", strtotime($user_data['user_ban_exp'])) . " due to violation of community guidelines.");
      }

      if($user_data["email_verification"] == 0) {

        emailVerificationOTP($email);

        $respond = [
          'status' => 'question',
          'title' => "Verify your email!",
        ];

        echo json_encode($respond);
        unset($_SESSION['csrf_token_in']);
        exit();
        
      } else {

        $_SESSION['user_details'] = $user_data;

        // $logQuery = "INSERT INTO login_logs (user_id, ip_address, location, login_time)
        //               VALUES (:user_id, :ip_address, :location, :login_time)";
        // $logStmt = $pdo->prepare($logQuery);
        // $logData = [
        //   ":user_id" => $user_data["user_id"],
        //   ":ip_address" => $ip_address,
        //   ":location" => $location,
        //   ":login_time" => $currentTime
        // ];

        $logQuery = 
        "INSERT INTO activity_logs
        (
          act_log_user_id,
          act_log_act_type,
          act_log_description,
          act_log_ip_add,
          act_log_device,
          act_log_created_at
        )
        VALUES
        (
          :userId,
          :actType,
          :description,
          :ipAdd,
          :device,
          :createdAt
        )
        ";

        $logStmt = $pdo->prepare($logQuery);

        $logData = [
          ":userId" => $user_data["user_id"],
          ":actType" => "login",
          ":description" => $location,
          ":ipAdd" => $_SERVER['REMOTE_ADDR'],
          ":device" => $_SERVER['HTTP_USER_AGENT'],
          ":createdAt" => $currentTime
        ];
        $logStmt->execute($logData);


        unset($_SESSION['csrf_token_in']);
        successResponse("login success");
      }
    }

    errorResponse("Failed to login");

  }

  if($function == "forgot-password") {
    
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      errorResponse("Invalid CSRF token");
    }

    $email = $_POST['email'];

    // check if email exist
    $existEmail = selectQuery(
      $pdo,
      "SELECT email FROM users WHERE email = :email",
      ["email" => $email]
    );

    if(!$existEmail) {
      errorResponse("Email does not exist");
    }

    emailVerificationOTP($email);
    unset($_SESSION['csrf_token']);
    successResponse("Verify OTP");
    
  }

  if($function == "otp-verification") {

    // $otpCode = $_POST["otpCode"];

    // print_r($_POST);

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token_otp']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_otp']) {
      errorResponse("Invalid CSRF token");
    }

    $otpCode = $_POST["otpCode"];

    if(empty($otpCode)) {

      errorResponse("Fill up the field");
    }

    if(($_SESSION["time"] + 5) < time()) {

      errorResponse("OTP Expired. Resend OTP");
    }

    if($_SESSION["otp"] == $otpCode ) {

      unset($_SESSION['csrf_token_otp']);
      $_SESSION['otp_verified'] = true;
      successResponse("OTP verified!");
    } else {
      errorResponse("Wrong OTP code. Try again!");
    }

  }

  if($function == "forgot-change-password") {
    // print_r($_POST);

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token_change_pass']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_change_pass']) {
      errorResponse("Invalid CSRF token");
    }

    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Password validation
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $password)) {
      errorResponse("Password must be at least 6 characters long and contain both letters and numbers.");
    }


    // Confirm Password
    if ($password != $cpassword) {

      errorResponse("Password and Confirm Password do not match.");
    }

    try {

      $pdo->beginTransaction();
      $query = "UPDATE users SET password = :password WHERE email = :email";
      $stmt = $pdo->prepare($query);
      $options = [
        'cost' => 12
      ];

      $data = [
        ":password" => password_hash($password, PASSWORD_BCRYPT, $options),
        ":email" => $_SESSION["mail"]
      ];

      $queryExecute = $stmt->execute($data);

      $pdo->commit();
  
      unset($_SESSION['csrf_token_change_pass']);
      successResponse("Changed password successfully.");
    } catch (Exception $e) {
      $pdo->rollBack();
      echo "Failed to complete transaction: " . $e->getMessage();
      exit();
    }

  }

}


