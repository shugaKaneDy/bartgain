<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../mail/PHPMailer/src/Exception.php';
require '../../mail/PHPMailer/src/PHPMailer.php';
require '../../mail/PHPMailer/src/SMTP.php';

require_once "functions.php";

function emailOffer($email, $name, $itemTitle) {

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
    $mail->Subject = 'Offer Sent BartGain';
    $mail->Body = '<h3>'. $name .' sent an offer to your item: '. $itemTitle .'</h3>';

    $mail->send();

  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();

  $function = $_GET["function"];
  $currentTime  = date("Y-m-d H:i:s");

  if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token_send_offer']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_send_offer']) {
    errorResponse("Invalid CSRF token");
  }

  $itemId = htmlspecialchars($_POST['item_id']);
  $title = htmlspecialchars($_POST['title']);
  $category = htmlspecialchars($_POST['category']);
  $condition = htmlspecialchars($_POST['condition']);
  $description = htmlspecialchars($_POST['description']);
  $estimatedValue = $_POST['estimatedValue'];

  // print_r($_FILES);

  // print_r($_POST);
  // exit();

  $allowed = [
    'jpg',
    'jpeg',
    'png',
    'webp',
    'mp4',
    'webm',
    'ogg',
  ];
  
  // fill up fields
  if(empty($title) || empty($description) || empty($estimatedValue) || empty($itemId)) {
    
    errorResponse("Please fill out all the fields!!!");
  }
  
  // empty file
  if(empty($_FILES['itemUrlPicture']['name'][0])) {
    
    errorResponse("Add at least 1 Picture/Video");
  }
  
  $total = count($_FILES['itemUrlPicture']['name']);

  // number of items
  if($total > 2) {

    errorResponse("You can only upload 2 files");
  }

  // size and file extension
  for ($i = 0; $i < $total; $i++) {

    $fileSizeKb = (int)getSize($_FILES['itemUrlPicture']['size'][$i]);
    $fileNameValidation = $_FILES['itemUrlPicture']['name'][$i];
    $extFileNameValidation = explode('.', $fileNameValidation);
    $extFileNameValidation = end($extFileNameValidation);

    if($fileSizeKb > 15000) {

      errorResponse("File Size must not exeed to 15mb");
    }

    if(!in_array($extFileNameValidation, $allowed)) {

      errorResponse("File submitted not allowed");
    }
  }

  $uploadedFiles = [];

  for($i = 0; $i < $total; $i++) {

    $date = date('Y-m-d');
    $rand = rand(10000, 99999);

    $uniqueName = $date . '-' . $rand . '-' . $_FILES['itemUrlPicture']['name'][$i];
    move_uploaded_file($_FILES['itemUrlPicture']['tmp_name'][$i], "../../offer-uploads/$uniqueName");
    $uploadedFiles[] = $uniqueName;
  }

  $selectedItem = selectQueryFetch(
                                    $pdo,
                                    "SELECT * FROM items
                                    INNER JOIN users ON items.item_user_id = users.user_id
                                    WHERE items.item_random_id = :itemId",
                                    [
                                      ":itemId" => $itemId
                                    ]
                                    );

  // print_r($selectedItem);

  try {

    $pdo->beginTransaction();

    // INSERT OFFER
    insertQuery(
      $pdo,
      "INSERT INTO offers (offer_random_id, offer_item_id, offer_user_id, offer_title, offer_est_val, offer_url_file, offer_category, offer_condition, offer_description, offer_created_at)
      VALUES (:offerRandomId, :offerItemId, :offerUserId, :offerTitle, :offerEstVal, :offerUrlFile, :offerCategory, :offerCondition, :offerDescription, :offerCreatedAt)",
      [
        ":offerRandomId" => rand(10000000, 99999999),
        ":offerItemId" => $selectedItem['item_id'],
        ":offerUserId" => $_SESSION['user_details']['user_id'],
        ":offerTitle" => $title,
        ":offerEstVal" => $estimatedValue,
        ":offerUrlFile" => implode(',', $uploadedFiles),
        ":offerCategory" => $category,
        ":offerCondition" => $condition,
        ":offerDescription" => $description,
        ":offerCreatedAt" => $currentTime,
      ]
    );

    $lastOfferId = $pdo->lastInsertId();

    // INSERT MESSAGE
    insertQuery(
      $pdo,
      "INSERT INTO messages (message_offer_id, message_user_id, message_message, message_type, message_created_at)
      VALUES (:messageOfferId, :messageUserId, :messageMessage, :messageType, :messageCreated)",
      [
        ":messageOfferId" => $lastOfferId,
        ":messageUserId" => $_SESSION['user_details']['user_id'],
        ":messageMessage" => "Offer Sent",
        ":messageType" => "offer",
        ":messageCreated" => $currentTime,
      ]
    );

    // INSERT NOTIFICATIONS
    insertQuery(

      $pdo,
      "INSERT INTO user_notifications (user_notification_user_id, user_notification_by_user_id, user_notification_message, user_notification_type, user_notification_created_at)
      VALUES (:userNotificationUserId, :userNotificationByUserId, :userNotificationMessage, :userNotificationType, :userNotificationCreatedAt)",
      [
        ":userNotificationUserId" => $selectedItem['item_user_id'],
        ":userNotificationByUserId" => $_SESSION['user_details']['user_id'],
        ":userNotificationMessage" => $_SESSION['user_details']['fullname'] ." sent an offer",
        ":userNotificationType" => "offer",
        ":userNotificationCreatedAt" => $currentTime,
      ]
    );

    insertQuery(
      $pdo,
      "INSERT INTO activity_logs
      (
        act_log_user_id,
        act_log_act_type,
        act_log_description,
        act_log_item_id,
        act_log_ip_add,
        act_log_device,
        act_log_created_at
      )
      VALUES
      (
        :userId,
        :actType,
        :description,
        :itemId,
        :ipAdd,
        :device,
        :createdAt
      )
      ",
      [
        ":userId" => $_SESSION["user_details"]["user_id"],
        ":actType" => "send offer",
        ":description" => "You sent an offer: " . $title . " to: " . $selectedItem['item_title'] ,
        ":itemId" => $selectedItem['item_id'],
        ":ipAdd" => $_SERVER['REMOTE_ADDR'],
        ":device" => $_SERVER['HTTP_USER_AGENT'],
        ":createdAt" => $currentTime
      ]
    );

    // EMAIL OFFER SENT
    emailOffer(
      $selectedItem['email'],
      $_SESSION['user_details']['fullname'],
      $selectedItem['item_title']
    );

    $pdo->commit();

    unset($_SESSION['csrf_token_send_offer']);

    successResponse("Offer sent successfully");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

}