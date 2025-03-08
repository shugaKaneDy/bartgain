<?php

require_once "functions.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../../../includes/dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  // Convert currentTime to UNIX timestamp
  $currentTimeUnix = strtotime($currentTime);

  if(isset($_SESSION['user_details'])) {
    if($_SESSION['user_details']['role_id'] != 2) {
      exit;
    }
  } else {
    exit;
  }

  $ru_id = $_POST['ru_id'];
  $actionReason = $_POST['actionReason'];
  $actionCategory = "warning";
  $banEnd = $currentTime;

  if(empty($actionReason)) {
    errorResponse("Please fill out all the fields");
  }

  if(!empty( $_POST['otherReason'])) {
    $actionReason = $_POST['otherReason'];
  }

  $reportInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM reports
    WHERE report_random_id = :reportId
    AND report_type = :reportType",
    [
      ":reportId" => $ru_id,
      ":reportType" => "user",
    ]
  );

  if(empty($reportInfo)) {
    errorResponse("Invalid Input");
  }

  $userActions = selectQuery(
    $pdo,
    "SELECT * FROM actions
    WHERE action_user_id = :userId",
    [
      ":userId" => $reportInfo['report_user_id'],
    ]
  );

  $totalUserActions = count($userActions);

  switch ($totalUserActions) {
    case 0:
        break;
    case 1:
        $actionCategory = "temporary1";
        $banEnd = strtotime("+3 days", $currentTimeUnix); // Add 3 days for case 1
        $banEnd = date("Y-m-d H:i:s", $banEnd);
        break;
    case 2:
        $actionCategory = "temporary2";
        $banEnd = strtotime("+14 days", $currentTimeUnix); // Add 14 days for case 2
        $banEnd = date("Y-m-d H:i:s", $banEnd);
        break;
    case 3:
    default:
        $actionCategory = "temporary3";
        $banEnd = strtotime("+10 years", $currentTimeUnix); // Add 10 years for case 3 and up
        $banEnd = date("Y-m-d H:i:s", $banEnd);
        break;
  }

  if($actionCategory == "warning") {
    try {

      $pdo->beginTransaction();
  
      insertQuery(
        $pdo,
        "INSERT INTO actions
        (
          action_user_id,
          action_category,
          action_reason,
          action_type,
          action_start,
          action_end,
          action_created_at
        )
        VALUES
        (
          :actionUserId,
          :actionCategory,
          :actionReason,
          :actionType,
          :actionStart,
          :actionEnd,
          :actionCreatedAt
        )",
        [
          ":actionUserId" => $reportInfo['report_user_id'],
          ":actionCategory" => $actionCategory,
          ":actionReason" => $actionReason,
          ":actionType" => "user",
          ":actionStart" => $currentTime,
          ":actionEnd" => $currentTime,
          ":actionCreatedAt" => $currentTime,
        ]
      );

      updateQuery(
        $pdo,
        "UPDATE reports SET
        report_status = 'resolved'
        WHERE report_user_id = :userId
        AND report_status = 'pending'",
        [
          ":userId" =>  $reportInfo['report_user_id']
        ]
      );
  
      $pdo->commit();
  
      successResponse("Report resolved successfully!");
  
    } catch(PDOException $e) {
  
      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }
  } else {
    try {

      $pdo->beginTransaction();
  
      insertQuery(
        $pdo,
        "INSERT INTO actions
        (
          action_user_id,
          action_category,
          action_reason,
          action_type,
          action_start,
          action_end,
          action_created_at
        )
        VALUES
        (
          :actionUserId,
          :actionCategory,
          :actionReason,
          :actionType,
          :actionStart,
          :actionEnd,
          :actionCreatedAt
        )",
        [
          ":actionUserId" => $reportInfo['report_user_id'],
          ":actionCategory" => $actionCategory,
          ":actionReason" => $actionReason,
          ":actionType" => "user",
          ":actionStart" => $currentTime,
          ":actionEnd" => $banEnd,
          ":actionCreatedAt" => $currentTime,
        ]
      );

      updateQuery(
        $pdo,
        "UPDATE reports SET
        report_status = 'resolved'
        WHERE report_user_id = :userId
        AND report_status = 'pending'",
        [
          ":userId" =>  $reportInfo['report_user_id']
        ]
      );

      updateQuery(
        $pdo,
        "UPDATE users SET
        user_status = 'inactive',
        user_ban_exp = :banEnd
        WHERE user_id = :userId",
        [
          ":userId" =>  $reportInfo['report_user_id'],
          ":banEnd" =>  $banEnd,
        ]
      );
  
      $pdo->commit();
  
      successResponse("Report resolved successfully!");
  
    } catch(PDOException $e) {
  
      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }
  }

  // print_r($actionCategory);
  // exit;

}
?>
