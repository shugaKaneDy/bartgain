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
  $message = htmlspecialchars($_POST['message']);

  if(empty($message)) {
    errorResponse("Please put a message!");
  }

  $ticketInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM tickets
    WHERE ticket_random_id = :tId",
    [
      ":tId" => $tId,
    ]
  );


  if(empty($ticketInfo)) {
    errorResponse("Invalid Input");
  }

  try {

    $pdo->beginTransaction();

    insertQuery(
      $pdo,
      "INSERT INTO ticket_responses
      (
        t_response_ticket_id,
        t_response_user_id,
        t_response_message,
        t_response_type,
        t_response_created_at
      )
      VALUES
      (
        :tResponseTicketId,
        :tResponseUserId,
        :tResponseMessage,
        :tResponseType,
        :tResponseCreatedAt
      )
      ",
      [
        ":tResponseTicketId" => $ticketInfo['ticket_id'],
        ":tResponseUserId" => $_SESSION['user_details']['user_id'],
        ":tResponseMessage" => $message,
        ":tResponseType" => "admin",
        ":tResponseCreatedAt" => $currentTime
      ]
    );

    $pdo->commit();

    unset($_SESSION['csrf_token_ticket_response']);

    successResponse("Ticket response submitted successfully!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

  print_r($_POST);
  exit;

}

?>