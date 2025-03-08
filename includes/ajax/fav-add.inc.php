<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $item_id = $_POST['item_id'];

  $itemInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM items WHERE item_random_id = :item_id",
    [
      ":item_id" => $item_id
    ]
  );

  try {

    $pdo->beginTransaction();

    insertQuery(
      $pdo,
      "INSERT INTO favorites
      (
        fav_user_id,
        fav_item_id,
        fav_created_at
      )
      VALUES
      (
        :userId,
        :itemId,
        :createdAt
      )
      ",
      [
        ":userId" => $_SESSION['user_details']['user_id'],
        ":itemId" => $itemInfo['item_id'],
        ":createdAt" => $currentTime,
      ]
    );

    insertQuery(
      $pdo,
      "INSERT INTO activity_logs
      (
        act_log_user_id,
        act_log_act_type,
        act_log_description,
        act_log_ip_add,
        act_log_device,
        act_log_created_at
      )
      VALUES
      (
        :userId,
        :actType,
        :description,
        :ipAdd,
        :device,
        :createdAt
      )
      ",
      [
        ":userId" => $_SESSION["user_details"]["user_id"],
        ":actType" => "favortie add",
        ":description" => $itemInfo['item_title'] . " added to favorites",
        ":ipAdd" => $_SERVER['REMOTE_ADDR'],
        ":device" => $_SERVER['HTTP_USER_AGENT'],
        ":createdAt" => $currentTime
      ]
    );

    $pdo->commit();

    successResponse("Added successfully");
  
  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }



  print_r($itemInfo);
  exit();
  

}

?>