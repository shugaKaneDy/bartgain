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


  $expenseId = $_POST['expenseId'];

  try {

    $pdo->beginTransaction();

    updateQuery(
      $pdo,
      "DELETE FROM expenses WHERE expense_id = :id",
      [
        "id" => $expenseId,
      ]
    );

    $pdo->commit();

    successResponse("Expense added successfully!");

  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }


  // print_r($_POST);
  // exit();


}

?>