<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $itemId = $_POST['itemId'];

  $itemInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM items
    WHERE item_random_id = :itemId",
    [
      "itemId" => $itemId
    ]
  );

  if($itemInfo['item_user_id'] == $_SESSION['user_details']['user_id']) {
    exit;
  }

  // print_r($itemInfo);

  insertQuery(
    $pdo,
    "INSERT INTO clicks
    (
      click_item_id,
      click_user_id,
      click_created_at
    )
    VALUES
    (
      :clickITemId,
      :clickUserId,
      :clickDate
    )",
    [
      ":clickITemId" => $itemInfo['item_id'],
      ":clickUserId" => $_SESSION['user_details']['user_id'],
      ":clickDate" => $currentTime,
    ]
  );

  
}

?>