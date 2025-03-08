<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $itemId = $_POST['itemId'];
  $itemAction = $_POST['itemAction'];

  $selectedItem = selectQueryFetch(
    $pdo,
    "SELECT * FROM items WHERE item_random_id = :itemId",
    [
      "itemId" => $itemId,
    ]
  );

  // print_r($selectedItem);
  // exit;

  if($itemAction == 'pending') {

    try {

      $pdo->beginTransaction();

      updateQuery(
        $pdo,
        "UPDATE items SET item_status = :status
        WHERE item_random_id = :randomId",
        [
          ":status" => $itemAction,
          ":randomId" => $itemId,
        ]
      );

      insertQuery(
        $pdo,
        "INSERT INTO activity_logs
        (
          act_log_user_id,
          act_log_act_type,
          act_log_description,
          act_log_item_id,
          act_log_ip_add,
          act_log_device,
          act_log_created_at
        )
        VALUES
        (
          :userId,
          :actType,
          :description,
          :itemId,
          :ipAdd,
          :device,
          :createdAt
        )
        ",
        [
          ":userId" => $_SESSION["user_details"]["user_id"],
          ":actType" => "item status",
          ":description" => "You set an item into " . $itemAction . ". Item ID: " . $itemId,
          ":itemId" => $selectedItem['item_id'],
          ":ipAdd" => $_SERVER['REMOTE_ADDR'],
          ":device" => $_SERVER['HTTP_USER_AGENT'],
          ":createdAt" => $currentTime
        ]
      );
  
      $pdo->commit();
  
      successResponse("Status updated successfully");
  
    } catch(PDOException $e) {
  
      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }

  }

  if($itemAction == 'available') {

    try {

      $pdo->beginTransaction();

      updateQuery(
        $pdo,
        "UPDATE items SET item_status = :status
        WHERE item_random_id = :randomId",
        [
          ":status" => $itemAction,
          ":randomId" => $itemId,
        ]
      );

      insertQuery(
        $pdo,
        "INSERT INTO activity_logs
        (
          act_log_user_id,
          act_log_act_type,
          act_log_description,
          act_log_item_id,
          act_log_ip_add,
          act_log_device,
          act_log_created_at
        )
        VALUES
        (
          :userId,
          :actType,
          :description,
          :itemId,
          :ipAdd,
          :device,
          :createdAt
        )
        ",
        [
          ":userId" => $_SESSION["user_details"]["user_id"],
          ":actType" => "item status",
          ":description" => "You set an item into " . $itemAction . ". Item ID: " . $itemId,
          ":itemId" => $selectedItem['item_id'],
          ":ipAdd" => $_SERVER['REMOTE_ADDR'],
          ":device" => $_SERVER['HTTP_USER_AGENT'],
          ":createdAt" => $currentTime
        ]
      );
  
      $pdo->commit();
  
      successResponse("Status updated successfully");
  
    } catch(PDOException $e) {
  
      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }
  }

  if($itemAction == 'deleted') {

    try {

      $pdo->beginTransaction();

      updateQuery(
        $pdo,
        "UPDATE items SET item_status = :status
        WHERE item_random_id = :randomId",
        [
          ":status" => $itemAction,
          ":randomId" => $itemId,
        ]
      );

      insertQuery(
        $pdo,
        "INSERT INTO activity_logs
        (
          act_log_user_id,
          act_log_act_type,
          act_log_description,
          act_log_item_id,
          act_log_ip_add,
          act_log_device,
          act_log_created_at
        )
        VALUES
        (
          :userId,
          :actType,
          :description,
          :itemId,
          :ipAdd,
          :device,
          :createdAt
        )
        ",
        [
          ":userId" => $_SESSION["user_details"]["user_id"],
          ":actType" => "item status",
          ":description" => "You deleted an item. Item ID: " . $itemId,
          ":itemId" => $selectedItem['item_id'],
          ":ipAdd" => $_SERVER['REMOTE_ADDR'],
          ":device" => $_SERVER['HTTP_USER_AGENT'],
          ":createdAt" => $currentTime
        ]
      );
  
      $pdo->commit();
  
      successResponse("Status updated successfully");
  
    } catch(PDOException $e) {
  
      $pdo->rollBack();
      errorResponse("Failed: " . $e->getMessage());
    }
  }


  
}

?>