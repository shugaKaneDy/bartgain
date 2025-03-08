<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../../../includes/dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  if(isset($_SESSION['user_details'])) {
    if($_SESSION['user_details']['role_id'] != 2) {
      exit;
    }
  } else {
    exit;
  }

  $tId = $_POST['tId'];
  
  $ticketInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM tickets
    WHERE ticket_random_id = :tId",
    [
      ":tId" => $tId
    ]
  );


  if(empty($ticketInfo)) {
    errorResponse("Invalid Input");
  }

  try {

    $pdo->beginTransaction();

    updateQuery(
      $pdo,
      "UPDATE tickets
      SET ticket_status = :ticketStatus
      WHERE ticket_id = :ticketId",
      [
        ":ticketStatus" => "closed",
        ":ticketId" => $ticketInfo['ticket_id']
      ]
    );

    $pdo->commit();

    successResponse("Ticket closed successfully!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

  print_r($_POST);
  exit;

}

?>