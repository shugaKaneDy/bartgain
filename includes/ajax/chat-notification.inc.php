<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $forOffersIndicator = 0;
  $forProposalsIndicator = 0;

  $proposalsChats = selectQuery(
    $pdo,
    "SELECT * FROM items
    INNER JOIN offers ON items.item_id = offers.offer_item_id
    INNER JOIN users ON items.item_user_id = users.user_id
    LEFT JOIN messages ON messages.message_id = (
        SELECT message_id 
        FROM messages 
        WHERE messages.message_offer_id = offers.offer_id
        ORDER BY messages.message_created_at DESC
        LIMIT 1
    )
    WHERE offers.offer_user_id = :offerUserId
    ORDER BY messages.message_created_at DESC",
    [
      ":offerUserId" => $_SESSION['user_details']['user_id']
    ]
  );

  $offerChats = selectQuery(
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

  foreach ($proposalsChats as $proposalsChat) {
    if($proposalsChat['message_is_read'] == 0 && $proposalsChat['message_user_id'] != $_SESSION['user_details']['user_id']) {
      $forProposalsIndicator += 1;
    }
  }

  foreach ($offerChats as $offerChat) {
    if($offerChat['message_is_read'] == 0 && $offerChat['message_user_id'] != $_SESSION['user_details']['user_id']) {
      $forOffersIndicator += 1;
    }
  }

  ?>

  <?php if($forProposalsIndicator == 0 && $forOffersIndicator == 0): ?>
  <?php else: ?>
    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
      <span class="visually-hidden">New alerts</span>
    </span>
  <?php endif ?>
  
  <?php
}

?>