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

  // print_r($_POST);
  // exit;

  $expenseId = $_POST['expenseId'];
  $expenseCategory = $_POST['expenseCategory'];
  $expenseDetails = $_POST['expenseDetails'];
  $expenseAmount = $_POST['expenseAmount'];
  $expenseDate = $_POST['expenseDate'];



  if(!empty( $_POST['otherCategory'])) {
    $expenseCategory = $_POST['otherCategory'];
  }

  if(empty($expenseAmount) || empty($expenseDate)) {
    errorResponse("Please Fill in all the required fields");
  }


  try {

    $pdo->beginTransaction();

    updateQuery(
      $pdo,
      "UPDATE expenses SET
      expense_amount = :amount,
      expense_category = :category,
      expense_details = :details,
      expense_created_at = :createdAt
      WHERE expense_id = :id",
      [
        ":amount" => $expenseAmount,
        ":category" => $expenseCategory,
        ":details" => $expenseDetails,
        ":createdAt" => $expenseDate,
        ":id" => $expenseId
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