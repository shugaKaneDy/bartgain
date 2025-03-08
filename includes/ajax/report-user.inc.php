<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token_report_user']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_report_user']) {
    errorResponse("Invalid CSRF token");
  }

  // print_r($_POST);
  // print_r($_FILES);
  // exit;

  $userId = htmlspecialchars($_POST['userId']);
  $reportType = htmlspecialchars($_POST['reportType']);
  $reportReason = htmlspecialchars($_POST['reportReason']);

  if(empty($reportType)) {
    errorResponse("Invalid input...");
  }

  $selectedIUser = selectQueryFetch(
    $pdo,
    "SELECT * FROM users WHERE user_random_id = :userId",
    [
      ":userId" => $userId
    ]
  );

  if(empty($selectedIUser)) {
    errorResponse("Invalid input.");
  }


  $reportOptions = [
    'Violence' => 'Violence',
    'Hate Speech' => 'Hate Speech',
    'Something else' => 'Something else'
  ];

  if(!in_array($reportType, $reportOptions)) {
    errorResponse("Invalid Input");
  }

  if(empty($_FILES['reportUserPicture']['name'][0])) {
    
    try {
  
      $pdo->beginTransaction();
      insertQuery(
        $pdo,
        "INSERT INTO reports
        (
          report_random_id,
          report_by_user_id,
          report_user_id,
          report_category,
          report_reason,
          report_type,
          report_created_at
        )
        VALUES
        (
          :reportRandomId,
          :reportByUserId,
          :reportItemId,
          :reportCategory,
          :reportReason,
          :reportType,
          :reportCreatedAt      
        )
        ",
        [
          ":reportRandomId" => uniqid(),
          ":reportByUserId" => $_SESSION['user_details']['user_id'],
          ":reportItemId" => $selectedIUser['user_id'],
          ":reportCategory" => $reportType,
          ":reportReason" => $reportReason,
          ":reportType" => "user",
          ":reportCreatedAt" => $currentTime,
        ]
      );
  
  
      $pdo->commit();

      unset($_SESSION['csrf_token_report_user']);
  
      successResponse("Thank you! Your report has been submitted successfully. We’ll review it and take appropriate action shortly.");
  
  
  
    } catch(PDOException $e) {
  
      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }
  } else {

    $total = count($_FILES['reportUserPicture']['name']);
    // number of items
    if($total > 2) {

      errorResponse("You can only upload 2 photos");
    }

    $uploadedFiles = [];

    for($i = 0; $i < $total; $i++) {

      $date = date('Y-m-d');
      $rand = rand(10000, 99999);
  
      $uniqueName = $date . '-' . $rand . '-' . $_FILES['reportUserPicture']['name'][$i];
      move_uploaded_file($_FILES['reportUserPicture']['tmp_name'][$i], "../../report-uploads/$uniqueName");
      $uploadedFiles[] = $uniqueName;
    }


    try {
  
      $pdo->beginTransaction();
      insertQuery(
        $pdo,
        "INSERT INTO reports
        (
          report_random_id,
          report_by_user_id,
          report_user_id,
          report_category,
          report_reason,
          report_photos,
          report_type,
          report_created_at
        )
        VALUES
        (
          :reportRandomId,
          :reportByUserId,
          :reportItemId,
          :reportCategory,
          :reportReason,
          :reportPhotos,
          :reportType,
          :reportCreatedAt      
        )
        ",
        [
          ":reportRandomId" => uniqid(),
          ":reportByUserId" => $_SESSION['user_details']['user_id'],
          ":reportItemId" => $selectedIUser['user_id'],
          ":reportCategory" => $reportType,
          ":reportReason" => $reportReason,
          ":reportPhotos" => implode(',', $uploadedFiles),
          ":reportType" => "user",
          ":reportCreatedAt" => $currentTime,
        ]
      );
  
  
      $pdo->commit();

      unset($_SESSION['csrf_token_report_user']);
  
      successResponse("Thank you! Your report has been submitted successfully. We’ll review it and take appropriate action shortly.");
  
  
  
    } catch(PDOException $e) {
  
      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }
  }



}

?>