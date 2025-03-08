<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../mail/PHPMailer/src/Exception.php';
require '../../mail/PHPMailer/src/PHPMailer.php';
require '../../mail/PHPMailer/src/SMTP.php';

require_once "functions.php";

function emailAcceptOffer($email, $name, $offerTitle) {

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
    $mail->Body = '<h3>'. $name .' accept your offer: '. $offerTitle .'</h3>';

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

  // print_r($_POST);

  $planLng = $_POST['planLng'];
  $planLat = $_POST['planLat'];
  $itemOfferForPlan = $_POST['itemOfferForPlan'];
  $locationMeetUp = htmlspecialchars($_POST['locationMeetUpReal']) ;
  $dateMeetup = $_POST['dateMeetup'];

  

  $offerInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM items
    INNER JOIN offers ON items.item_id = offers.offer_item_id
    INNER JOIN users ON offers.offer_user_id = users.user_id
    WHERE offers.offer_random_id = :offerRandomId",
    [
      ":offerRandomId" => $itemOfferForPlan,
    ]
  );

  if ($function == 'rejectOffer') {


    $rejectReason = htmlspecialchars($_POST['rejectReason']) ;

    try {

      $pdo->beginTransaction();

      updateQuery(
        $pdo,
        "UPDATE offers SET offer_status = :offerStatus, offer_cancelled_reject_reason = :rejectReason WHERE offer_random_id = :offerId",
        [
            ":offerStatus" => "rejected",
            ":rejectReason" => $rejectReason . " - " . $_SESSION['user_details']['fullname'] ,
            ":offerId" => $itemOfferForPlan,
        ]
      );

      // INSERT MESSAGE
      insertQuery(
        $pdo,
        "INSERT INTO messages (message_offer_id, message_user_id, message_message, message_type, message_created_at)
        VALUES (:messageOfferId, :messageUserId, :messageMessage, :messageType, :messageCreated)",
        [
          ":messageOfferId" => $offerInfo['offer_id'],
          ":messageUserId" => $_SESSION['user_details']['user_id'],
          ":messageMessage" => "Offer Rejected",
          ":messageType" => "offer rejected",
          ":messageCreated" => $currentTime,
        ]
      );


      // INSERT NOTIFICATION
      insertQuery(

        $pdo,
        "INSERT INTO user_notifications (user_notification_user_id, user_notification_by_user_id, user_notification_message, user_notification_type, user_notification_created_at)
        VALUES (:userNotificationUserId, :userNotificationByUserId, :userNotificationMessage, :userNotificationType, :userNotificationCreatedAt)",
        [
          ":userNotificationUserId" => $offerInfo['offer_user_id'],
          ":userNotificationByUserId" => $_SESSION['user_details']['user_id'],
          ":userNotificationMessage" => $_SESSION['user_details']['fullname'] ." rejected your offer " . $offerInfo['offer_title'],
          ":userNotificationType" => "offer rejected",
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
          act_log_offer_id,
          act_log_ip_add,
          act_log_device,
          act_log_created_at
        )
        VALUES
        (
          :userId,
          :actType,
          :description,
          :offerId,
          :ipAdd,
          :device,
          :createdAt
        )
        ",
        [
          ":userId" => $_SESSION["user_details"]["user_id"],
          ":actType" => "offer reject",
          ":description" => "Offer rejected with an offer id: " . $offerInfo['offer_random_id'] ,
          ":offerId" => $offerInfo['offer_id'],
          ":ipAdd" => $_SERVER['REMOTE_ADDR'],
          ":device" => $_SERVER['HTTP_USER_AGENT'],
          ":createdAt" => $currentTime
        ]
      );

      $pdo->commit();
      successResponse("Rejected Successfully!");

    } catch(PDOException $e) {

      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }

  }

  if($_SESSION['user_details']['user_id'] != $offerInfo['item_user_id'] && $_SESSION['user_details']['user_id'] != $offerInfo['offer_user_id']) {
    errorResponse("You are not permitted to this offer");
  }

  if(empty($planLng) || empty($planLat) || empty($itemOfferForPlan) || empty($locationMeetUp) || empty($dateMeetup)) {

    errorResponse("Fill up all the fields");
  }

  if(!strpos(strtolower($locationMeetUp), 'cavite')) {
    errorResponse("Sorry, this app is only available for users in Cavite.");
  }
  
  if(strtotime($currentTime) >= strtotime($dateMeetup)) {

    errorResponse("Please choose a date in the future.");
  }

  if($function == 'planUpdate') {

    if(empty($planLng) || empty($planLat) || empty($itemOfferForPlan) || empty($locationMeetUp) || empty($dateMeetup)) {

      errorResponse("Fill up all the fields");
    }

    try {

      $pdo->beginTransaction();

      updateQuery(
        $pdo,
        "UPDATE offers SET offer_lng = :offerLng, offer_lat = :offerLat, offer_meet_up_place = :offerMeetUp, offer_date_time_meet = :offerDateMeetUp WHERE offer_random_id = :offerId",
        [
            ":offerLng" => $planLng,
            ":offerLat" => $planLat,
            ":offerMeetUp" => $locationMeetUp,
            ":offerDateMeetUp" => $dateMeetup,
            ":offerId" => $itemOfferForPlan,
        ]
      );

      // INSERT MESSAGE
      insertQuery(
        $pdo,
        "INSERT INTO messages (message_offer_id, message_user_id, message_message, message_type, message_created_at)
        VALUES (:messageOfferId, :messageUserId, :messageMessage, :messageType, :messageCreated)",
        [
          ":messageOfferId" => $offerInfo['offer_id'],
          ":messageUserId" => $_SESSION['user_details']['user_id'],
          ":messageMessage" => "Plan Updated",
          ":messageType" => "update plan",
          ":messageCreated" => $currentTime,
        ]
      );

      insertQuery(
        $pdo,
        "INSERT INTO activity_logs
        (
          act_log_user_id,
          act_log_act_type,
          act_log_description,
          act_log_offer_id,
          act_log_ip_add,
          act_log_device,
          act_log_created_at
        )
        VALUES
        (
          :userId,
          :actType,
          :description,
          :offerId,
          :ipAdd,
          :device,
          :createdAt
        )
        ",
        [
          ":userId" => $_SESSION["user_details"]["user_id"],
          ":actType" => "plan update",
          ":description" => "Plan updated with an offer id: " . $offerInfo['offer_random_id'] ,
          ":offerId" => $offerInfo['offer_id'],
          ":ipAdd" => $_SERVER['REMOTE_ADDR'],
          ":device" => $_SERVER['HTTP_USER_AGENT'],
          ":createdAt" => $currentTime
        ]
      );

      $pdo->commit();
      successResponse("Plan Updated!");

    } catch(PDOException $e) {

      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }
  }

  if ($function == 'acceptOffer') {

    if(empty($planLng) || empty($planLat) || empty($itemOfferForPlan) || empty($locationMeetUp) || empty($dateMeetup)) {

      errorResponse("Fill up all the fields");
    }

    $forQrCode = rand(100000000, 999999999);

    try {

      $pdo->beginTransaction();

      if(empty($offerInfo['offer_lat'])) {
        updateQuery(
          $pdo,
          "UPDATE offers SET offer_lng = :offerLng, offer_lat = :offerLat, offer_meet_up_place = :offerMeetUp, offer_date_time_meet = :offerDateMeetUp WHERE offer_random_id = :offerId",
          [
              ":offerLng" => $planLng,
              ":offerLat" => $planLat,
              ":offerMeetUp" => $locationMeetUp,
              ":offerDateMeetUp" => $dateMeetup,
              ":offerId" => $itemOfferForPlan,
          ]
        );
  
        // INSERT MESSAGE
        insertQuery(
          $pdo,
          "INSERT INTO messages (message_offer_id, message_user_id, message_message, message_type, message_created_at)
          VALUES (:messageOfferId, :messageUserId, :messageMessage, :messageType, :messageCreated)",
          [
            ":messageOfferId" => $offerInfo['offer_id'],
            ":messageUserId" => $_SESSION['user_details']['user_id'],
            ":messageMessage" => "Plan Updated",
            ":messageType" => "update plan",
            ":messageCreated" => $currentTime,
          ]
        );
  
        insertQuery(
          $pdo,
          "INSERT INTO activity_logs
          (
            act_log_user_id,
            act_log_act_type,
            act_log_description,
            act_log_offer_id,
            act_log_ip_add,
            act_log_device,
            act_log_created_at
          )
          VALUES
          (
            :userId,
            :actType,
            :description,
            :offerId,
            :ipAdd,
            :device,
            :createdAt
          )
          ",
          [
            ":userId" => $_SESSION["user_details"]["user_id"],
            ":actType" => "plan update",
            ":description" => "Plan updated with an offer id: " . $offerInfo['offer_random_id'] ,
            ":offerId" => $offerInfo['offer_id'],
            ":ipAdd" => $_SERVER['REMOTE_ADDR'],
            ":device" => $_SERVER['HTTP_USER_AGENT'],
            ":createdAt" => $currentTime
          ]
        );
      }

      updateQuery(
        $pdo,
        "UPDATE offers SET offer_status = :offerStatus WHERE offer_random_id = :offerId",
        [
            ":offerStatus" => "accepted",
            ":offerId" => $itemOfferForPlan,
        ]
      );

      updateQuery(
        $pdo,
        "UPDATE items SET item_status = :itemStatus WHERE item_random_id = :itemId",
        [
            ":itemStatus" => "pending",
            ":itemId" => $offerInfo['item_random_id'],
        ]
      );

      // INSERT MESSAGE
      insertQuery(
        $pdo,
        "INSERT INTO messages (message_offer_id, message_user_id, message_message, message_type, message_created_at)
        VALUES (:messageOfferId, :messageUserId, :messageMessage, :messageType, :messageCreated)",
        [
          ":messageOfferId" => $offerInfo['offer_id'],
          ":messageUserId" => $_SESSION['user_details']['user_id'],
          ":messageMessage" => "Offer Accepted",
          ":messageType" => "offer accepted",
          ":messageCreated" => $currentTime,
        ]
      );

      // INSERT MEET UP
      insertQuery(
        $pdo,
        "INSERT INTO meet_ups (meet_up_offer_id, meet_up_qr_code, meet_up_created_at)
        VALUES (:meetUpOfferId, :meetUpQrCode, :meetUpCreatedAt)",
        [
          ":meetUpOfferId" => $offerInfo['offer_id'],
          ":meetUpQrCode" => $forQrCode,
          ":meetUpCreatedAt" => $currentTime,
        ]
      );

      // INSERT NOTIFICATION
      insertQuery(

        $pdo,
        "INSERT INTO user_notifications (user_notification_user_id, user_notification_by_user_id, user_notification_message, user_notification_type, user_notification_created_at)
        VALUES (:userNotificationUserId, :userNotificationByUserId, :userNotificationMessage, :userNotificationType, :userNotificationCreatedAt)",
        [
          ":userNotificationUserId" => $offerInfo['offer_user_id'],
          ":userNotificationByUserId" => $_SESSION['user_details']['user_id'],
          ":userNotificationMessage" => $_SESSION['user_details']['fullname'] ." accepted your offer " . $offerInfo['offer_title'],
          ":userNotificationType" => "offer accepted",
          ":userNotificationCreatedAt" => $currentTime,
        ]
      );

      // INSERT LOGS
      insertQuery(
        $pdo,
        "INSERT INTO activity_logs
        (
          act_log_user_id,
          act_log_act_type,
          act_log_description,
          act_log_offer_id,
          act_log_ip_add,
          act_log_device,
          act_log_created_at
        )
        VALUES
        (
          :userId,
          :actType,
          :description,
          :offerId,
          :ipAdd,
          :device,
          :createdAt
        )
        ",
        [
          ":userId" => $_SESSION["user_details"]["user_id"],
          ":actType" => "accept offer",
          ":description" => "Offer accepted with an offer id: " . $offerInfo['offer_random_id'] ,
          ":offerId" => $offerInfo['offer_id'],
          ":ipAdd" => $_SERVER['REMOTE_ADDR'],
          ":device" => $_SERVER['HTTP_USER_AGENT'],
          ":createdAt" => $currentTime
        ]
      );

      emailAcceptOffer(
        $offerInfo['email'],
        $_SESSION['user_details']['fullname'],
        $offerInfo['offer_title']
      );

      $pdo->commit();
      successResponse("Offer Accepted!");

    } catch(PDOException $e) {

      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }

  }

}