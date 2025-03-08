<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");
  
  /* session validation */
  if(!isset($_SESSION['user_details'])) {
    exit;
  }

  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $itemsPerPage = 10;
  $offset = ($page - 1) * $itemsPerPage;
  

  $notifications = selectQuery(
    $pdo,
    "SELECT * FROM user_notifications
    WHERE user_notification_user_id = :userId
    ORDER BY user_notification_is_read ASC, user_notification_id DESC
    LIMIT $offset, $itemsPerPage",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
    ]
  );

  if($notifications) {
    foreach($notifications as $notification) {

      if($notification['user_notification_is_read'] == 0) {

        switch($notification['user_notification_type']) {
          case "item deleted":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="item-deleted.php?reason=<?= $notification['user_notification_message'] ?>"
              >
                <div>
                  <p class="h6 m-0 fw-bold"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success" style="height:10px; width: 10px;"></div>
                </div>
              </div>
            <?php
            
            break;

          case "item flagged":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="item-flagged.php?reason=<?= $notification['user_notification_message'] ?>"
              >
                <div>
                  <p class="h6 m-0 fw-bold"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success" style="height:10px; width: 10px;"></div>
                </div>
              </div>
            <?php
            
            break;
    
          case "barter complete":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="ratings.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success" style="height:10px; width: 10px;"></div>
                </div>
              </div>
            <?php
            
            break;
    
          case "offer":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="message-offers.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success" style="height:10px; width: 10px;"></div>
                </div>
              </div>
            <?php
            break;
    
          case "offer accepted":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="message-proposals.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success" style="height:10px; width: 10px;"></div>
                </div>
              </div>
            <?php
            break;
      
          case "offer rejected":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="message-proposals.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success" style="height:10px; width: 10px;"></div>
                </div>
              </div>
            <?php
            break;
      
          case "rate complete":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="ratings.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success" style="height:10px; width: 10px;"></div>
                </div>
              </div>
            <?php
            break;
      
          case "verification accepted":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="verification-accepted.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success" style="height:10px; width: 10px;"></div>
                </div>
              </div>
            <?php
            break;
      
          case "verification rejected":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="verification-rejected.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success" style="height:10px; width: 10px;"></div>
                </div>
              </div>
            <?php
            break;
      
          default:
            // Handle unknown or unexpected actions
            break;
        }

      } else {

        switch($notification['user_notification_type']) {
          
          case "item deleted":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2 bg-body-tertiary"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="item-deleted.php?reason=<?= $notification['user_notification_message'] ?>"
              >
                <div>
                  <p class="h6 m-0 fw-bold text-muted"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate text-muted">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text text-muted"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
              </div>
            <?php
            
            break;

          case "item flagged":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2 bg-body-tertiary"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="item-flagged.php?reason=<?= $notification['user_notification_message'] ?>"
              >
                <div>
                  <p class="h6 m-0 fw-bold text-muted"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate text-muted">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text text-muted"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
              </div>
            <?php
            
            break;

          case "barter complete":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2 bg-body-tertiary"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="ratings.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold text-muted"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate text-muted">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text text-muted"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
              </div>
            <?php
            
            break;
    
          case "offer":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2 bg-body-tertiary"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="message-offers.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold text-muted"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate text-muted">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text text-muted"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
              </div>
            <?php
            break;
    
          case "offer accepted":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2 bg-body-tertiary"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="message-proposals.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold text-muted"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate text-muted">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text text-muted"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
              </div>
            <?php
            break;
      
          case "offer rejected":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2 bg-body-tertiary"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="message-proposals.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold text-muted"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate text-muted">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text text-muted"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
              </div>
            <?php
            break;
      
          case "rate complete":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2 bg-body-tertiary"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="ratings.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold text-muted"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate text-muted">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text text-muted"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
              </div>
            <?php
            break;
      
          case "verification accepted":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2 bg-body-tertiary"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="verification-accepted.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold text-muted"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate text-muted">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text text-muted"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
              </div>
            <?php
            break;
      
          case "verification rejected":
            ?>
              <div class="notificationInfo d-flex justify-content-between p-2 bg-body-tertiary"
              data-notif-id="<?= $notification['user_notification_id'] ?>"
              data-href="verification-rejected.php"
              >
                <div>
                  <p class="h6 m-0 fw-bold text-muted"><?= strtoupper($notification['user_notification_type']) ?></p>
                  <div>
                    <span class="d-inline-block text-truncate for-text-truncate text-muted">
                      <?= $notification['user_notification_message'] ?>
                    </span>
                  </div>
                  <p class="m-0 text-success small-text text-muted"><?= date("M d, Y h:i A", strtotime($notification['user_notification_created_at'])) ?></p>
                </div>
              </div>
            <?php
            break;
      
          default:
            // Handle unknown or unexpected actions
            break;
        }

      }


      
    }

  } else {
    // echo "No notifications";
  }

}

?>