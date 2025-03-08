<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();

  $function = $_GET["function"];
  $currentTime  = date("Y-m-d H:i:s");

  $allowed = [
    'jpg',
    'jpeg',
    'png',
  ];


  if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token_ticket']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_ticket']) {
    errorResponse("Invalid CSRF token");
  }

  $subject = htmlspecialchars($_POST['subject']);
  $description = htmlspecialchars($_POST['description']);

  // fill up fields
  if(empty($subject) || empty($description)) {

    errorResponse("Please fill out all the fields!!!");
  }

  if(empty($_FILES['ticketUrlPicutre']['name'][0])) {
    
    try {

      $pdo->beginTransaction();

      insertQuery(
        $pdo,
        "INSERT INTO tickets
        (
          ticket_random_id,
          ticket_user_id,
          ticket_subject,
          ticket_description,
          ticket_created_at
        )
        VALUES
        (
          :ticketRandomId,
          :ticketUserId,
          :ticketSubject,
          :ticketDescription,
          :ticketCreatedAt
        )
        ",
        [
          ":ticketRandomId" => rand(10000000, 99999999),
          ":ticketUserId" => $_SESSION['user_details']['user_id'],
          ":ticketSubject" => $subject,
          ":ticketDescription" => $description,
          ":ticketCreatedAt" => $currentTime
        ]
      );

      $pdo->commit();

      unset($_SESSION['csrf_token_ticket']);

      successResponse("Ticket submitted successfully!");

    } catch(PDOException $e) {

      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }
    
  } else {
    $total = count($_FILES['ticketUrlPicutre']['name']);
    // number of items
    if($total > 2) {

      errorResponse("You can only upload 2 photos");
    }

    $uploadedFiles = [];

    for($i = 0; $i < $total; $i++) {

      $date = date('Y-m-d');
      $rand = rand(10000, 99999);
  
      $uniqueName = $date . '-' . $rand . '-' . $_FILES['ticketUrlPicutre']['name'][$i];
      move_uploaded_file($_FILES['ticketUrlPicutre']['tmp_name'][$i], "../../ticket-uploads/$uniqueName");
      $uploadedFiles[] = $uniqueName;
    }

    try {

      $pdo->beginTransaction();

      insertQuery(
        $pdo,
        "INSERT INTO tickets
        (
          ticket_random_id,
          ticket_user_id,
          ticket_subject,
          ticket_description,
          ticket_url_file,
          ticket_created_at
        )
        VALUES
        (
          :ticketRandomId,
          :ticketUserId,
          :ticketSubject,
          :ticketDescription,
          :ticketUrlFile,
          :ticketCreatedAt
        )
        ",
        [
          ":ticketRandomId" => rand(10000000, 99999999),
          ":ticketUserId" => $_SESSION['user_details']['user_id'],
          ":ticketSubject" => $subject,
          ":ticketDescription" => $description,
          ":ticketUrlFile" => implode(',', $uploadedFiles),
          ":ticketCreatedAt" => $currentTime
        ]
      );

      $pdo->commit();

      unset($_SESSION['csrf_token_ticket']);

      successResponse("Ticket submitted successfully!");

    } catch(PDOException $e) {

      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }


  }

  // print_r($_POST);
  // print_r($_FILES);
  // exit;

 

}