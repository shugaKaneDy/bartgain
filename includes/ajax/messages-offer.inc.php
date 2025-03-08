<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $offerId = $_POST['offerId'];

  if($offerId == 0) {
    echo 'No message';
    exit();
  }

  $userMessages = selectQuery(
                            $pdo,
                            "SELECT * FROM items
                            INNER JOIN offers ON items.item_id = offers.offer_item_id
                            INNER JOIN users ON offers.offer_user_id = users.user_id
                            INNER JOIN messages ON offers.offer_id = messages.message_offer_id
                            WHERE offers.offer_random_id = :offerRandomId",
                            [
                              ":offerRandomId" => $offerId
                            ]
                          );

                          // print_r($userMessages);

  ?>
    <!-- <div class="row mb-3">
      <div class="col-8 col-md-5">
        <div class="w-100 border border-secondary bg-body-tertiary rounded py-3">
          <p class="text-center fw-bold m-0">Sent an offer</p>
          <div class="px-4">
            <button class="btn btn-outline-success bg-green btn-sm w-100">View Offer</button>
          </div>
        </div>
        <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
      </div>
    </div>
    <div class="row justify-content-end mb-3">
      <div class="col-10 col-md-8">
        <div class="w-100 border border-warning bg-warning text-white rounded p-2">
          <p class="m-0 message-text">Hello po. Available pa po ba?</p>
        </div>
        <p class="m-0 smaller-text float-end">10/3/2024 10:40 PM</p>
      </div>
    </div>
    <div class="row justify-content-start">
      <div class="col-10 col-md-8">
        <div class="w-100 border border-secondary bg-body-tertiary rounded p-2">
          <p class="m-0 message-text">Yes po. Ready to plan na po?</p>
        </div>
        <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
      </div>
    </div> -->

    <?php foreach($userMessages as $userMessage): ?>
      <?php if($userMessage['item_user_id'] == $userMessage['message_user_id']): ?>
        <?php if($userMessage['message_type'] == 'update plan'): ?>
          <div class="row mb-3 justify-content-end">
            <div class="col-8 col-md-5">
              <div class="w-100 border border-warning bg-warning text-white rounded py-3">
                <p class="text-center fw-bold m-0"><?= $userMessage['message_message'] ?></p>
                <div class="px-4">
                  <button value="<?= $userMessage['offer_random_id'] ?>" class="forViewOfferModal btn btn-outline-success bg-green btn-sm w-100"
                  data-bs-toggle="modal" data-bs-target="#offerModalView"
                  >View Details</button>
                </div>
              </div>
              <p class="m-0 smaller-text float-end"><?= date("M d, Y h:i A", strtotime($userMessage['message_created_at'])) ?></p>
            </div>
          </div>
        <?php elseif($userMessage['message_type'] == 'offer accepted'): ?>
          <div class="row mb-3 justify-content-end">
            <div class="col-8 col-md-5">
              <div class="w-100 border border-warning bg-warning text-white rounded py-3">
                <p class="text-center fw-bold m-0"><?= $userMessage['message_message'] ?></p>
                <div class="px-4">
                  <a href="meet-up-receiver.php?offer_id=<?= $userMessage['offer_random_id'] ?>" class="forViewMeetUpModal btn btn-outline-success bg-green btn-sm w-100">View Meet Up</a>
                </div>
              </div>
              <p class="m-0 smaller-text float-end"><?= date("M d, Y h:i A", strtotime($userMessage['message_created_at'])) ?></p>
            </div>
          </div>
        <?php elseif($userMessage['message_type'] == 'offer rejected'): ?>
          <div class="row mb-3 justify-content-end">
            <div class="col-8 col-md-5">
              <div class="w-100 border border-warning bg-warning text-white rounded py-3">
                <p class="text-center fw-bold m-0"><?= $userMessage['message_message'] ?></p>
                <div class="px-4">
                  <button value="<?= $userMessage['offer_random_id'] ?>" class="forViewOfferModal btn btn-danger btn-sm w-100"
                  data-bs-toggle="modal" data-bs-target="#offerModalView"
                  >View Details</button>
                </div>
              </div>
              <p class="m-0 smaller-text float-end"><?= date("M d, Y h:i A", strtotime($userMessage['message_created_at'])) ?></p>
            </div>
          </div>
        <?php else: ?>
          <div class="row justify-content-end mb-3">
            <div class="col-10 col-md-8">
              <div class="w-100 border border-warning bg-warning text-white rounded p-2">
                <p class="m-0 message-text"><?= $userMessage['message_message'] ?></p>
              </div>
              <p class="m-0 smaller-text float-end"><?= date("M d, Y h:i A", strtotime($userMessage['message_created_at'])) ?></p>
            </div>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <?php if($userMessage['message_type'] == 'offer'): ?>
          <div class="row mb-3">
            <div class="col-8 col-md-5">
              <div class="w-100 border border-secondary bg-body-tertiary rounded py-3">
                <p class="text-center fw-bold m-0"><?= $userMessage['message_message'] ?></p>
                <div class="px-4">
                  <button value="<?= $userMessage['offer_random_id'] ?>" class="forViewOfferModal btn btn-outline-success bg-green btn-sm w-100"
                  data-bs-toggle="modal" data-bs-target="#offerModalView"
                  >View Offer</button>
                </div>
              </div>
              <p class="m-0 smaller-text"><?= date("M d, Y h:i A", strtotime($userMessage['message_created_at'])) ?></p>
            </div>
          </div>
        <?php elseif($userMessage['message_type'] == 'update plan'): ?>
          <div class="row mb-3">
            <div class="col-8 col-md-5">
              <div class="w-100 border border-secondary bg-body-tertiary rounded py-3">
                <p class="text-center fw-bold m-0"><?= $userMessage['message_message'] ?></p>
                <div class="px-4">
                  <button value="<?= $userMessage['offer_random_id'] ?>" class="forViewOfferModal btn btn-outline-success bg-green btn-sm w-100"
                  data-bs-toggle="modal" data-bs-target="#offerModalView"
                  >View Details</button>
                </div>
              </div>
              <p class="m-0 smaller-text"><?= date("M d, Y h:i A", strtotime($userMessage['message_created_at'])) ?></p>
            </div>
          </div>
        <?php elseif($userMessage['message_type'] == 'offer rejected'): ?>
          <div class="row mb-3">
            <div class="col-8 col-md-5">
              <div class="w-100 border border-secondary bg-body-tertiary rounded py-3">
                <p class="text-center fw-bold m-0"><?= $userMessage['message_message'] ?></p>
                <div class="px-4">
                  <button value="<?= $userMessage['offer_random_id'] ?>" class="forViewOfferModal btn btn-danger btn-sm w-100"
                  data-bs-toggle="modal" data-bs-target="#offerModalView"
                  >View Details</button>
                </div>
              </div>
              <p class="m-0 smaller-text"><?= date("M d, Y h:i A", strtotime($userMessage['message_created_at'])) ?></p>
            </div>
          </div>
        <?php else: ?>
          <div class="row mb-3 justify-content-start">
            <div class="col-10 col-md-8">
              <div class="w-100 border border-secondary bg-body-tertiary rounded p-2">
                <p class="m-0 message-text"><?= $userMessage['message_message'] ?></p>
              </div>
              <p class="m-0 smaller-text"><?= date("M d, Y h:i A", strtotime($userMessage['message_created_at'])) ?></p>
            </div>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    <?php endforeach ?>




  <?php
  
  


}

?>