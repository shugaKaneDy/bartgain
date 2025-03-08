<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();

  $function = $_GET["function"];
  $currentTime  = date("Y-m-d H:i:s");

  // print_r($_POST);
  if($function == 'sendMessage') {

    $message = htmlspecialchars($_POST['message']) ;
    $offerItemForMessage = $_POST['offerItemForMessage'];

    if(empty($message)) {

      exit();
    }

    $offerSelectInfo = selectQueryFetch(
                                        $pdo,
                                        "SELECT * FROM offers
                                        INNER JOIN items ON items.item_id = offers.offer_item_id
                                        INNER JOIN users ON offers.offer_user_id = users.user_id
                                        WHERE offer_random_id = :offerRandomId",
                                        [
                                          "offerRandomId" => $offerItemForMessage
                                        ]     
                                        );

    // print_r($offerSelectInfo);
    // send message filtering
    if($offerSelectInfo['item_user_id'] != $_SESSION['user_details']['user_id'] && $offerSelectInfo['offer_user_id'] != $_SESSION['user_details']['user_id']) {
      errorResponse("You are not allowed to send on this message");
    }


    insertQuery(
      $pdo,
      "INSERT INTO messages (message_offer_id, message_user_id, message_message, message_created_at)
      VALUES (:messageOfferId, :messageUserId, :messageMessage, :messageCreatedAt)",
      [
        ":messageOfferId" => $offerSelectInfo['offer_id'],
        ":messageUserId" => $_SESSION['user_details']['user_id'],
        ":messageMessage" => $message,
        ":messageCreatedAt" => $currentTime,
      ]
    );
  }

}