<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  // print_r($_POST);

  // successResponse("Meet-up complete! The transaction has been successfully finalized.");

  $qrCode = $_POST['qrCode'];
  $meetUpLoc = $_POST['meetUpLoc'];
  $meetUpLng = $_POST['meetUpLng'];
  $meetUpLat = $_POST['meetUpLat'];
  
  $qrInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM meet_ups
    INNER JOIN offers ON meet_ups.meet_up_offer_id = offers.offer_id
    INNER JOIN items ON offers.offer_item_id = items.item_id
    INNER JOIN users ON users.user_id = items.item_user_id
    WHERE meet_ups.meet_up_qr_code = :meetQr",
    [
      ":meetQr" => $qrCode
    ]
  );
  // print_r($qrInfo);
  // exit();

  try {

    $pdo->beginTransaction();

    // UPDATE ITEM
    updateQuery(
      $pdo,
      "UPDATE items SET item_status = :itemStatus
      WHERE item_id = :itemId",
      [
        ":itemStatus" => "completed",
        ":itemId" => $qrInfo['item_id'],
      ]
    );

    // UPDATE MEET UP
    updateQuery(
      $pdo,
      "UPDATE meet_ups SET meet_up_status = :meetUpStatus,
      meet_up_lng = :meetUpLng,
      meet_up_lat = :meetUpLat,
      meet_up_loc = :meetUpLoc,
      meet_up_date = :meetUpDate
      WHERE meet_up_id = :meetUpId",
      [
        ":meetUpStatus" => "completed",
        ":meetUpLng" => $meetUpLng,
        ":meetUpLat" => $meetUpLat,
        ":meetUpLoc" => $meetUpLoc,
        ":meetUpDate" => $currentTime,
        ":meetUpId" => $qrInfo['meet_up_id'],
      ]
    );


    // INSERT RATINGS 1
    insertQuery(
      $pdo,
      "INSERT INTO ratings (rate_random_id, rate_meet_up_id, rate_user_id, rate_by_user_id, rate_created_at) 
       VALUES (:rateRandomId, :rateMeetUpId, :rateUserId, :rateByUserId, :rateCreatedAt)",
      [
        ":rateRandomId" => rand(10000000, 99999999),
        ":rateMeetUpId" => $qrInfo['meet_up_id'],
        ":rateUserId" => $qrInfo['item_user_id'],
        ":rateByUserId" => $qrInfo['offer_user_id'],
        ":rateCreatedAt" => $currentTime
      ]
    );

    // INSERT RATINGS 2
    insertQuery(
      $pdo,
      "INSERT INTO ratings (rate_random_id, rate_meet_up_id, rate_user_id, rate_by_user_id, rate_created_at) 
       VALUES (:rateRandomId, :rateMeetUpId, :rateUserId, :rateByUserId, :rateCreatedAt)",
      [
        ":rateRandomId" => rand(10000000, 99999999),
        ":rateMeetUpId" => $qrInfo['meet_up_id'],
        ":rateUserId" => $qrInfo['offer_user_id'],
        ":rateByUserId" => $qrInfo['item_user_id'],
        ":rateCreatedAt" => $currentTime
      ]
    );

    // INSERT NOTIFICATION 1
    insertQuery(

      $pdo,
      "INSERT INTO user_notifications (user_notification_user_id, user_notification_by_user_id, user_notification_message, user_notification_type, user_notification_created_at)
      VALUES (:userNotificationUserId, :userNotificationByUserId, :userNotificationMessage, :userNotificationType, :userNotificationCreatedAt)",
      [
        ":userNotificationUserId" => $qrInfo['item_user_id'],
        ":userNotificationByUserId" => $_SESSION['user_details']['user_id'],
        ":userNotificationMessage" => "BARTER COMPLETE WITH " . $_SESSION['user_details']['fullname'],
        ":userNotificationType" => "barter complete",
        ":userNotificationCreatedAt" => $currentTime,
      ]
    );

    // INSERT NOTIFICATION 1
    insertQuery(

      $pdo,
      "INSERT INTO user_notifications (user_notification_user_id, user_notification_by_user_id, user_notification_message, user_notification_type, user_notification_created_at)
      VALUES (:userNotificationUserId, :userNotificationByUserId, :userNotificationMessage, :userNotificationType, :userNotificationCreatedAt)",
      [
        ":userNotificationUserId" => $qrInfo['offer_user_id'],
        ":userNotificationByUserId" => $qrInfo['item_user_id'],
        ":userNotificationMessage" => "BARTER COMPLETE WITH " . $qrInfo['fullname'],
        ":userNotificationType" => "barter complete",
        ":userNotificationCreatedAt" => $currentTime,
      ]
    );

    $pdo->commit();

    successResponse("Trade complete! Your barter transaction has been successfully finalized. Enjoy your new item!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

}

?>