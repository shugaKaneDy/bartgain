<?php

session_start();
require_once 'dbcon.php';

$getOfferId = $_GET['offer_id'];

$queryMessages = "SELECT * FROM messages
INNER JOIN users ON messages.sender_user_id = users.user_id
INNER JOIN offers ON messages.offer_id = offers.offer_id
WHERE messages.offer_id = $getOfferId";
$stmtMessages = $conn->query($queryMessages);
$stmtMessages->setFetchMode(PDO::FETCH_OBJ);
$resultsMessages = $stmtMessages->fetchAll();



foreach ($resultsMessages as $rowMessage) {

  if ($rowMessage->sender_message) {

    if($rowMessage->message_type == 'title') {
      ?>
        <div class="d-flex justify-content-start mb-2 gap-2 w-100">
          <div class="d-flex align-items-end gap-3">
            <img src="profile-picture/<?= $rowMessage->profile_picture ? $rowMessage->profile_picture : "default.jpg" ?>" alt="Profile Picture" class="rounded-circle" style="width: 30px; height: 30px;">
          </div>
          <div class="w-50 ">
            <p class="text-xs text-muted m-0"><?= $rowMessage->fullname ?></p>
            <div class="border border-secondary p-1 rounded bg-light">
              <p class="m-0"> Offer Sent <i class="bi bi-gift"></i> </p>
              <p class="m-0 text-xs">
                TITLE: <?= $rowMessage->sender_message ?>
              </p>
            </div>
            <p class="text-xs text-muted m-0"><?= $rowMessage->message_created_at ?></p>
          </div>
        </div>
      <?php
    } else {
      ?>
        <div class="d-flex justify-content-start mb-2 gap-2 w-100">
          <div class="d-flex align-items-end gap-3">
            <img src="profile-picture/<?= $rowMessage->profile_picture ? $rowMessage->profile_picture : "default.jpg" ?>" alt="Profile Picture" class="rounded-circle" style="width: 30px; height: 30px;">
          </div>
          <div class="w-50 ">
            <p class="text-xs text-muted m-0"><?= $rowMessage->fullname ?></p>
            <div class="border border-secondary p-1 rounded bg-light">
              <?= $rowMessage->message_type == 'update' ?  "<p class='m-0'>Plan Updated <i class='bi bi-arrow-repeat'></i></p>":"" ?>
              <p class="m-0 text-xs">
                <?= $rowMessage->sender_message ?>
              </p>
            </div>
            <p class="text-xs text-muted m-0"><?= $rowMessage->message_created_at ?></p>
          </div>
        </div>

      <?php

    }
  }

  if ($rowMessage->receiver_message) {
    ?>
      <div class="d-flex justify-content-end gap-2 mb-2 w-100">
        <div class="w-50 ">
          <div class=" border border-primary p-1 rounded bg-primary-subtle">
            <?= $rowMessage->message_type == 'update' ?  "<p class='m-0'>Updated <i class='bi bi-arrow-repeat'></i></p>":"" ?>
            <p class="m-0 text-xs">
              <?= $rowMessage->receiver_message ?>
            </p>  
          </div>
          <p class="text-xs text-muted m-0"><?= $rowMessage->message_created_at ?></p>
        </div>
      </div>

    <?php
  }
}

?>