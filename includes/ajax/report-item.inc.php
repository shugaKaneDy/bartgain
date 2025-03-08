<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  
  $itemId = htmlspecialchars($_POST['itemId']) ;
  $reportType = htmlspecialchars($_POST['reportType']) ;

  if(empty($reportType)) {
    errorResponse("Invalid input...");
  }
  
  $selectedItem = selectQueryFetch(
    $pdo,
    "SELECT * FROM items WHERE item_random_id = :itemId",
    [
      ":itemId" => $itemId
    ]
  );


  $reportOptions = [
    'Nudity'        => 'Nudity',
    'Scam'          => 'Scam',
    'Illegal'       => 'Illegal',
    'Violence'      => 'Violence',
    'Hate Speech'   => 'Hate Speech',
    'Harassment'    => 'Harassment',
    'Spam'          => 'Spam',
    'Intellectual Property' => 'Intellectual Property',
    'Fraud'         => 'Fraud',
  ];

  

  if(empty($selectedItem)) {
    errorResponse("Invalid input");
  }

  if(!in_array($reportType, $reportOptions)) {
    errorResponse("Invalid Input");
  }
    
  // print_r($_POST);
  // exit();
  // successResponse("Success");
  

  try {

    $pdo->beginTransaction();
    
    insertQuery(
      $pdo,
      "INSERT INTO report_items
      (
        report_item_id,
        report_user_id,
        report_category,
        report_created_at
      )
      VALUES
      (
        :reportItemId,
        :reportUserId,
        :reportCategory,
        :reportCreatedAt      
      )
      ",
      [
        ":reportItemId" => $selectedItem['item_id'],
        ":reportUserId" => $_SESSION['user_details']['user_id'],
        ":reportCategory" => $reportType,
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