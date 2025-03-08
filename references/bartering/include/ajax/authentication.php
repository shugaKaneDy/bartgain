<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../mail/PHPMailer/src/Exception.php';
require '../../mail/PHPMailer/src/PHPMailer.php';
require '../../mail/PHPMailer/src/SMTP.php';


if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
    require_once '../../dbcon.php';
    session_start();

    
    $function = $_GET['function'];

    if ($function == "sign-in") {
      $email = $_POST['email'];
      $password = $_POST['password'];
      $location = $_POST['address'];
      $ip_address = $_SERVER['REMOTE_ADDR'];
  
      $query = "SELECT * FROM users WHERE email = :email";
      $stmt = $conn->prepare($query);
      $data = [
          ":email" => $email
      ];
  
      $stmt->execute($data);
      $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
  
      $respond = [
          'status' => 'error',
          'title' => "Failed to login",
      ];
  
      if ($user_data && password_verify($password, $user_data['password'])) {

        if($user_data["email_verification"] == 0) {

          $otp = rand(100000,999999);
          $_SESSION['otp'] = $otp;
          $_SESSION['mail'] = $email;

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

          $respond = [
            'status' => 'question',
            'title' => "Verify your email!",
          ];
        } else {

          $_SESSION['user_details'] = $user_data;

          $loginTime = date('Y-m-d H:i:s');

          $logQuery = "INSERT INTO login_logs (user_id, ip_address, location, login_time)
                        VALUES (:user_id, :ip_address, :location, :login_time)";
          $logStmt = $conn->prepare($logQuery);
          $logData = [
            ":user_id" => $user_data["user_id"],
            ":ip_address" => $ip_address,
            ":location" => $location,
            ":login_time" => $loginTime
          ];
          $logStmt->execute($logData);

          $respond = [
              'status' => 'success',
              'title' => "login success"
          ];

        }
        
      }
  
      echo json_encode($respond);
    }

    if ($function == "sign-up") {
        $full_name    = $_POST['fullname'];
        $email        = $_POST['email'];
        $password     = $_POST['password'];
        $address      = $_POST['address'];
        $lng          = $_POST['lng'];
        $lat          = $_POST['lat'];
        $checkAgree   = $_POST['checkAgree'] ?? "";

        if(empty($checkAgree)) {
          $respond = [
            'status' => 'error',
            'title' => "You must agree in terms and conditions"
          ];
          echo json_encode($respond);
          exit;
        }

        if(empty($email) || empty($password) || empty($full_name)) {
          $respond = [
            'status' => 'error',
            'title' => "Fill up all the fields"
          ];
          echo json_encode($respond);
          exit;
        }
        $currentTime  = date("Y-m-d H:i:s");

        // Password validation
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password)) {
            $respond = [
                'status' => 'error',
                'title' => "Password must be at least 6 characters long and contain both letters and numbers."
            ];
            echo json_encode($respond);
            exit;
        }

        // Check if the email already exists in the database
        $query_check_email = "SELECT * FROM users WHERE email = :email";
        $stmt_check_email = $conn->prepare($query_check_email);
        $data_check_email = [
            ":email" => $email
        ];
        $stmt_check_email->execute($data_check_email);
        $existing_user = $stmt_check_email->fetch(PDO::FETCH_ASSOC);

        if ($existing_user) {
          // If email already exists, respond with an error
          $respond = [
              'status' => 'error',
              'title' => 'Email address already registered'
          ];
          echo json_encode($respond);
          exit;
        }



        $query = "INSERT INTO users(`role_id`, `fullname`, `email`, `password`, `address`, `lng`, `lat`, `created_at`, `updated_at`) 
        VALUES (:role_id, :fullname, :email, :password, :address, :lng, :lat, :createdAt, :updatedAt)";
        $stmt = $conn->prepare($query);

        // Bind the data to the query parameters
        $data = [
            ":role_id" => 1,
            ":fullname" => $full_name,
            ":email" => $email,
            ":password" => password_hash($password, PASSWORD_DEFAULT),
            ":address" => $address,
            ":lng" => $lng,
            ":lat" => $lat,
            ":createdAt" => $currentTime,
            ":updatedAt" => $currentTime,
        ];

        // Execute the query
        $query_execute = $stmt->execute($data);

        if ($query_execute) {
            $otp = rand(100000,999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['mail'] = $email;

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

            $respond = [
                'status' => "success",
                'title' => "Account created successfully"
            ];
        } else {
            $respond = [
                'status' => "error",
                'title' => "Failed to create account"
            ];
        }

        echo json_encode($respond);
        exit;
    }

    if ($function == "email-verification") {
      $otpCode = $_POST["otpCode"];
      $email = $_SESSION["mail"];

      if($otpCode == $_SESSION["otp"]) {
        $query = "UPDATE users SET email_verification = 1 WHERE email = :email";
        $stmt = $conn->prepare($query);
        $data = [
          ":email" => $email
        ];

        $query_execute = $stmt->execute($data);

        if ($query_execute) {
          $respond = [
            'status' => "success",
            'title' => "Email verified successfully"
          ];
        } else {
          $respond = [
            'status' => "error",
            'title' => "Failed to verify Email"
          ];
        }
        echo json_encode($respond);
      } else {
        $respond = [
          'status' => "error",
          'title' => "Wrong OTP Code"
        ];
        echo json_encode($respond);
      }
    }

    if ($function == 'change-password') {

      $currentPassword = $_POST["currentPassword"];
      $newPassword = $_POST["newPassword"];
      $confirmNewPassword = $_POST["confirmNewPassword"];
      $userId = $_SESSION["user_details"]["user_id"];


      $query = "SELECT * FROM users WHERE user_id = :userId";
      $stmt = $conn->prepare($query);
      $data = [
          ":userId" => $userId
      ];
  
      $stmt->execute($data);
      $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

      if(empty($newPassword) || empty($confirmNewPassword || empty($currentPassword))) {
        $respond = [
          'status' => 'error',
          'title' => "Please fill in all the fields"
        ];
        echo json_encode($respond);
        exit;
      }


      if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $newPassword)) {
        $respond = [
            'status' => 'error',
            'title' => "Password must be at least 6 characters long and contain both letters and numbers."
        ];
        echo json_encode($respond);
        exit;
      }

      if($newPassword != $confirmNewPassword) {

        $respond = [
          'status' => "error",
          'title' => "New password and confirm new password do not match"
        ];
        echo json_encode($respond);
        exit;
      }

      if(!password_verify($currentPassword, $user_data['password'])) {
        $respond = [
          'status' => "error",
          'title' => "Password not match!"
        ];
        echo json_encode($respond);
        exit;
      }

      if(password_verify($currentPassword, $user_data['password'])) {

        // password_hash($password, PASSWORD_DEFAULT)

        $updateQuery = "UPDATE users SET password = :password WHERE user_id = :user_id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateData = [
          ":user_id" => $userId,
          ":password" => password_hash($newPassword, PASSWORD_DEFAULT),
        ];
        $updateQueryExecute = $updateStmt->execute($updateData);

        if($updateQueryExecute) {
          $respond = [
            'status' => "success",
            'title' => "Password updated!"
          ];
          echo json_encode($respond);
          exit;

        } else {
          $respond = [
            'status' => "error",
            'title' => "Password not updated!"
          ];
          echo json_encode($respond);
          exit;
        }


      }
      
    }

} else {
    echo "You don't have permission to access this section.";
}
