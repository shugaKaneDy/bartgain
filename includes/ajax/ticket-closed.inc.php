<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();

  $currentTime  = date("Y-m-d H:i:s");

  $allowed = [
    'jpg',
    'jpeg',
    'png',
  ];


  if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token_ticket_response']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_ticket_response']) {
    errorResponse("Invalid CSRF token");
  }

  $tId = $_POST['tId'];

  $ticketInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM tickets
    WHERE ticket_random_id = :tId
    AND ticket_user_id = :userId",
    [
      ":tId" => $tId,
      ":userId" => $_SESSION['user_details']['user_id'],
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

    unset($_SESSION['csrf_token_ticket_response']);

    successResponse("Ticket closed successfully!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }
 
  
  print_r($_POST);
  exit;

 

}