<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();

  $function = $_GET["function"];
  $currentTime  = date("Y-m-d H:i:s");

  $userChats = selectQuery(
    $pdo,
    "SELECT * FROM items
    INNER JOIN offers ON items.item_id = offers.offer_item_id
    INNER JOIN users ON offers.offer_user_id = users.user_id
    LEFT JOIN messages ON messages.message_id = (
        SELECT message_id 
        FROM messages 
        WHERE messages.message_offer_id = offers.offer_id
        ORDER BY messages.message_created_at DESC
        LIMIT 1
    )
    WHERE items.item_user_id = :itemUserId
    ORDER BY messages.message_created_at DESC",
    [
      ":itemUserId" => $_SESSION['user_details']['user_id']
    ]
  );

  


}