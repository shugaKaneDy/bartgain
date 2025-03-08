<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");
  
  $offer_id = $_POST['offer_id'];
  $cancelReason = htmlspecialchars($_POST['cancelReason']);


  $offerInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM items
    INNER JOIN offers ON items.item_id = offers.offer_item_id
    INNER JOIN users ON offers.offer_user_id = users.user_id
    WHERE offers.offer_random_id = :offerRandomId",
    [
      ":offerRandomId" => $offer_id,
    ]
  );

  

  $meetupInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM meet_ups WHERE meet_up_offer_id = :offerId",
    [
      ":offerId" => $offerInfo['offer_id'],
    ]
  );
  // print_r($offerInfo);
  // exit;

  if(empty($cancelReason)) {
    errorResponse("Please give a reason!");
  }



  try {

    $pdo->beginTransaction();

    // UPDATE MEETUP
    updateQuery(
      $pdo,
      "UPDATE meet_ups SET
      meet_up_cancel_reason = :cancelReason,
      meet_up_status = :status
      WHERE meet_up_id = :id",
      [
        ":cancelReason" => $cancelReason . " - " . $_SESSION['user_details']['fullname'],
        ":status" => "cancelled",
        ":id" => $meetupInfo['meet_up_id'],
      ]
    );

    // UPDATE OFFER
    updateQuery(
      $pdo,
      "UPDATE offers SET offer_status = :offerStatus, offer_cancelled_reject_reason = :rejectReason WHERE offer_random_id = :offerId",
      [
          ":offerStatus" => "rejected",
          ":rejectReason" => $cancelReason . " - " . $_SESSION['user_details']['fullname'] . " (Cancelled upon meetup)" ,
          ":offerId" => $offerInfo['offer_random_id'],
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
        ":actType" => "cancel meetup",
        ":description" => "Cancelled Meetup rejected with an offer id: " . $offerInfo['offer_random_id'] ,
        ":offerId" => $offerInfo['offer_id'],
        ":ipAdd" => $_SERVER['REMOTE_ADDR'],
        ":device" => $_SERVER['HTTP_USER_AGENT'],
        ":createdAt" => $currentTime
      ]
    );

    

    $pdo->commit();

    successResponse("Cancelled Successfully!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

}

?>