<?php

require_once "functions.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../../../includes/dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $ru_id = $_POST['ru_id'];

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

  try {

    $pdo->beginTransaction();
    

    updateQuery(
      $pdo,
      "UPDATE reports SET
      report_status = 'dismissed'
      WHERE report_random_id = :ru_id
      AND report_status = 'pending'",
      [
        ":ru_id" =>  $ru_id
      ]
    );

    $pdo->commit();

    successResponse("Report resolved successfully!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

  // print_r($reportInfo);
  // exit;

}
?>
