<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  // print_r($_POST);
  // exit();

  $rating = $_POST['rating'];
  $rateId = $_POST['rateId'];
  $ratingComment = $_POST['ratingComment'];

  $selectedRate = selectQueryFetch(
    $pdo,
    "SELECT * FROM ratings
    INNER JOIN users ON users.user_id = ratings.rate_user_id
    WHERE ratings.rate_random_id = :rateId",
    [
      ":rateId" => $rateId
    ]
  );
  // print_r($selectedRate);
  // exit();

  // rating error
  if(empty($rating)) {
    errorResponse("Please add rating!");
  }
  

  try {

    $pdo->beginTransaction();

    // UPDATE RATINGS
    updateQuery(
      $pdo,
      "UPDATE ratings SET rate_ratings = :rateRatings, rate_feedback = :rateFeedback, rate_status = :rateStatus, rate_date = :rateDate
      WHERE rate_random_id = :rateId",
      [
        ":rateRatings" => $rating,
        ":rateFeedback" => $ratingComment,
        ":rateStatus" => "completed",
        ":rateDate" => $currentTime,
        ":rateId" => $rateId,
      ]
    );

    // UPDATE USERS
    updateQuery(
      $pdo,
      "UPDATE users SET user_rating = user_rating + :userRating, user_rate_count = user_rate_count + :userRateCount
      WHERE user_id = :userId",
      [
        ":userRating" => $rating,
        ":userRateCount" => 1,
        ":userId" => $selectedRate['user_id'],
      ]
    );

    // INSERT NOTIFICATION 
    insertQuery(

      $pdo,
      "INSERT INTO user_notifications (user_notification_user_id, user_notification_by_user_id, user_notification_message, user_notification_type, user_notification_created_at)
      VALUES (:userNotificationUserId, :userNotificationByUserId, :userNotificationMessage, :userNotificationType, :userNotificationCreatedAt)",
      [
        ":userNotificationUserId" => $selectedRate['user_id'],
        ":userNotificationByUserId" => $_SESSION['user_details']['user_id'],
        ":userNotificationMessage" =>  $_SESSION['user_details']['fullname'] . " rated you with " . $rating . " star",
        ":userNotificationType" => "rate complete",
        ":userNotificationCreatedAt" => $currentTime,
      ]
    );

    

    $pdo->commit();

    successResponse("Thanks for rating your barter partner!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

}

?>