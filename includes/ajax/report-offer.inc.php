<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $offerId = htmlspecialchars($_POST['offerId']) ;
  $reportType = htmlspecialchars($_POST['reportType']) ;
  $reportReason = htmlspecialchars($_POST['reportReason']);

  if(empty($reportType)) {
    errorResponse("Invalid input...");
  }

  $selectedOffer = selectQueryFetch(
    $pdo,
    "SELECT * FROM offers WHERE offer_random_id = :offerId",
    [
      ":offerId" => $offerId
    ]
  );

  $reportOptions = [
    'Nudity' => 'Nudity',
    'Scam' => 'Scam',
    'Illegal' => 'Illegal',
    'Violence' => 'Violence',
    'Hate Speech' => 'Hate Speech',
    'Something else' => 'Something else'
  ];

  if(empty($selectedOffer)) {
    errorResponse("Invalid input");
  }

  if(!in_array($reportType, $reportOptions)) {
    errorResponse("Invalid Input");
  }

  

  try {

    $pdo->beginTransaction();

    insertQuery(
      $pdo,
      "INSERT INTO reports
      (
        report_random_id,
        report_by_user_id,
        report_offer_id,
        report_category,
        report_reason,
        report_type,
        report_created_at
      )
      VALUES
      (
        :reportRandomId,
        :reportByUserId,
        :reportOfferId,
        :reportCategory,
        :reportReason,
        :reportType,
        :reportCreatedAt      
      )
      ",
      [
        ":reportRandomId" => uniqid(),
        ":reportByUserId" => $_SESSION['user_details']['user_id'],
        ":reportOfferId" => $selectedOffer['offer_id'],
        ":reportCategory" => $reportType,
        ":reportReason" => $reportReason,
        ":reportType" => "offer",
        ":reportCreatedAt" => $currentTime,
      ]
    );

    $pdo->commit();

    successResponse("Thank you! Your report has been submitted successfully. We’ll review it and take appropriate action shortly.");



  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

}

?>