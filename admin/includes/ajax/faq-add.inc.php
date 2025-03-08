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

  $question = htmlspecialchars($_POST['question']);
  $answer = htmlspecialchars($_POST['answer']);

  if(empty($question) || empty($answer)) {
    errorResponse("Fill out all the fields");
  }

  // print_r($_POST);
  // exit;

  
  try {

    $pdo->beginTransaction();

    insertQuery(
      $pdo,
      "INSERT INTO faqs
      (
        faq_question,
        faq_answer,
        faq_created_at
      )
      VALUES
      (
        :question,
        :answer,
        :createdAt
      )",
      [
        ":question" => $question,
        ":answer" => $answer,
        ":createdAt" => $currentTime
      ]
    );

    $pdo->commit();

    successResponse("User added successfully!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }
  

}

?>