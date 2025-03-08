<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $getOfferId = $_POST['offerId'];

  // if($getOfferId == 0) {
  //   exit();
  // }

  $allowedImages = [
    'jpg',
    'jpeg',
    'png',
    'webp',
  ];

  $allowedVideos = [
    'mp4',
    'webm',
    'ogg',
  ];

  $userChats = selectQuery(
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

  ?>
    <?php if($userChats): ?>
      <?php foreach($userChats as $userChat): ?>
        <?php
          $UrlFiles = explode(',' , $userChat['item_url_file']);
          $firstFile = $UrlFiles[0];
          $ext = explode('.', $firstFile);
          $ext = end($ext);
        ?>
        <tr>
          <td class="border-0 <?= $getOfferId == $userChat['offer_random_id'] ? 'bg-success-subtle' : '' ?>">
            <a href="message-proposals.php?offer_id=<?= $userChat['offer_random_id'] ?>" class="text-decoration-none link-dark d-flex align-items-start">
              <?php if (in_array($ext, $allowedImages)): ?>
                <img src="item-uploads/<?= $firstFile ?>" class="left-image rounded-circle">
              <?php else: ?>
                <video src="item-uploads/<?= $firstFile ?>" class="left-image rounded-circle"></video>
              <?php endif; ?>
              <div class="ps-2 flex-fill">
                <div>
                  <p class="m-0 fw-bold m-0">
                    <?= $userChat['fullname'] ?>
                  </p>
                  <div>
                  <span class="d-block text-truncate text-secondary" style="max-width: 150px;">
                    <?php if($userChat['message_user_id'] == $_SESSION['user_details']['user_id']): ?>
                      You: <?= $userChat['message_message'] ?>
                    <?php else: ?>
                      <?= $userChat['message_message'] ?>
                    <?php endif ?>
                  </span>
                  </div>
                  <p class="smaller-text m-0">
                    <?= date("M d, Y h:i A", strtotime($userChat['message_created_at']))?>
                  </p>
                </div>
              </div>
              <?php if($userChat['message_user_id'] != $_SESSION['user_details']['user_id'] && $userChat['message_is_read'] != 1):?>
                <div class="d-flex align-items-center justify-content-center h-100">
                  <div class="bg-success rounded-circle" style="height: 10px; width: 10px"></div>
                </div>
              <?php endif ?>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
      
    <?php endif; ?>

  <?php
  
  


}

?>