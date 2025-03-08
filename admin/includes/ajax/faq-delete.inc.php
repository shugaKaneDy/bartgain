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
  
  
  if(empty($fId)) {
    errorResponse("Fill out all the fields");
  }

  // print_r($_POST);
  // exit;

  try {

    $pdo->beginTransaction();

    updateQuery(
      $pdo,
      "DELETE FROM faqs WHERE faq_id = :fId",
      [
        ":fId" => $fId,
      ]
    );
    

    $pdo->commit();

    successResponse("FAQ edited successfully!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

  


}

?>