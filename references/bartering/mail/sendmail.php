<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST["submitContact"])) {

    $email = $_POST["email"];

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->SMTPAuth = true; //Enable SMTP authentication

        $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->Username = 'ahnyudaengz5@gmail.com'; //SMTP username
        $mail->Password = 'eucojwskyjganbqb'; //SMTP password

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
        $mail->Port = 587; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('ahnyudaengz5@gmail.com', 'Bart Gain');
        $mail->addAddress($email); //Add a recipient

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = 'OTP Verification BartGain';
        $mail->Body = '<h3>Hello Tang ina mo</h3>';
        if ($mail->send()) {
            $_SESSION['status'] = "Thank you for contacting us - Team BartGain";
            header("Location: {$_SERVER["HTTP_REFERER"]}");
            exit(0);
        } else {
            $_SESSION['status'] = "Message could not be sent. Mail Error: {$mail->ErrorInfo}";
            header("Location: {$_SERVER["HTTP_REFERER"]}");
            exit(0);
        }

        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    header("location: index.php");
}
