<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../../../includes/dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $vId = $_POST['vId'];
  $rejectReason = htmlspecialchars($_POST['rejectReason']);

  if ($rejectReason === "Other.") {
    $rejectReason = htmlspecialchars($_POST['otherRejectReason']);
  }

  if(empty($rejectReason)) {
    errorResponse("Fill out all the fields!");
  }

  $verificationInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM verification
    INNER JOIN users on verification.verification_user_id = users.user_id
    WHERE verification.verification_random_id = :vId",
    [
      ":vId" => $vId,
    ]
  );

  if($verificationInfo['verification_status'] != 'pending') {
    errorResponse("This ticket has already been solved.");
  }

  // print_r($_POST);
  // exit;

  try {

    $pdo->beginTransaction();

    // UPDATE VERIFICATION
    updateQuery(
      $pdo,
      "UPDATE verification SET 
      verification_status = :verificationStatus,
      verification_reject_reason = :verificationRejectReason
      WHERE verification_random_id = :vId",
      [
        ":verificationStatus" => "rejected",
        ":verificationRejectReason" => $rejectReason,
        ":vId" => $vId,
      ]
    );

    // INSERT NOTIFICATIONS
    insertQuery(

      $pdo,
      "INSERT INTO user_notifications (user_notification_user_id, user_notification_by_user_id, user_notification_verification_random_id, user_notification_message, user_notification_type, user_notification_created_at)
      VALUES (:userNotificationUserId, :userNotificationByUserId, :verRandomId, :userNotificationMessage, :userNotificationType, :userNotificationCreatedAt)",
      [
        ":userNotificationUserId" => $verificationInfo['user_id'],
        ":verRandomId" => $verificationInfo['verification_random_id'],
        ":userNotificationByUserId" => $_SESSION['user_details']['user_id'],
        ":userNotificationMessage" => "Verification rejected.",
        ":userNotificationType" => "verification rejected",
        ":userNotificationCreatedAt" => $currentTime,
      ]
    );


    $pdo->commit();

    successResponse("Verification rejected successfully!");


  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }



}

?>