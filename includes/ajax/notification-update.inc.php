<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");
  
  /* session validation */
  if(!isset($_SESSION['user_details'])) {
    exit;
  }

  $notifId = $_POST['notifId'];

  $notifInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM user_notifications
    WHERE user_notification_id = :notifId
    AND user_notification_user_id = :userId",
    [
      ":notifId" => $notifId,
      ":userId" => $_SESSION['user_details']['user_id']
    ]
  );

  if(!$notifInfo) {
    exit;
  }

  // print_r($notifInfo);
  // successResponse("Notification updated successfully");

  try {

    $pdo->beginTransaction();

    updateQuery(
      $pdo,
      "UPDATE user_notifications SET user_notification_is_read = 1
      WHERE user_notification_id = :notifId",
      [
        ":notifId" => $notifId
      ]
    );
    $pdo->commit();
    successResponse("Notification updated successfully");
    exit;


  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

  // print_r($_POST);
}

?>