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

  $fId = htmlspecialchars($_POST['fId']);
  $question = htmlspecialchars($_POST['question']);
  $answer = htmlspecialchars($_POST['answer']);

  if(empty($question) || empty($answer) || empty($fId)) {
    errorResponse("Fill out all the fields");
  }

  try {

    $pdo->beginTransaction();

    updateQuery(
      $pdo,
      "UPDATE faqs SET
      faq_question = :question,
      faq_answer = :answer
      WHERE faq_id = :fId",
      [
        ":question" => $question,
        ":answer" => $answer,
        ":fId" => $fId,
      ]
    );

    $pdo->commit();

    successResponse("FAQ edited successfully!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

  // print_r($_POST);
  // exit;


}

?>