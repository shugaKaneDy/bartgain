<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $userNotification = selectQuery(
    $pdo,
    "SELECT * FROM user_notifications
    WHERE user_notification_user_id = :userId
    AND user_notification_is_read = 0",
    [
      "userId" => $_SESSION['user_details']['user_id'],
    ]
  );

  $notificationTotal = count($userNotification);
  

  ?>
      <?php if($notificationTotal >= 10):?>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          9+
          <span class="visually-hidden">unread messages</span>
        </span>
      <?php elseif($notificationTotal > 0):?>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          <?= $notificationTotal  ?>
          <span class="visually-hidden">unread messages</span>
        </span>
      <?php else:?>
      <?php endif?>
  <?php
}

?>