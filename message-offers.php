<?php
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'functions.php';

  if(!isset($_SESSION['user_details'])) {
    header("location: logout.php");
    exit;
  }
  if($_SESSION['user_details']['verified'] == "N") {
    header("location: itemplace.php");
    exit;
  }

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

  $getOfferId = 0;
  $offerPartner = [];

  if(isset($_GET["offer_id"])) {
    if(!empty($_GET["offer_id"])) {

      $_SESSION['indicator'] = 0;
      $getOfferId = $_GET["offer_id"];
      $offerPartner = selectQueryFetch(
                                    $pdo,
                                    "SELECT * FROM items
                                    INNER JOIN offers ON items.item_id = offers.offer_item_id
                                    INNER JOIN users ON offers.offer_user_id = users.user_id
                                    WHERE items.item_user_id = :itemUserId
                                    AND offers.offer_random_id = :offerRandomId",
                                    [
                                      ":itemUserId" => $_SESSION['user_details']['user_id'],
                                      ":offerRandomId" => $getOfferId,
                                    ]
                                  );
  
      $offerUrlFiles = explode(',' , $offerPartner['offer_url_file']);
      $offerFirstFile = $offerUrlFiles[0];
      $offerExt = explode('.', $offerFirstFile);
      $offerExt = end($offerExt);

      updateQuery(
        $pdo,
        "UPDATE messages SET message_is_read = 1
        WHERE message_is_read = 0
        AND message_offer_id = :messageOfferId
        AND message_user_id = :messageUserId",
        [
          ":messageOfferId" => $offerPartner['offer_id'],
          ":messageUserId" => $offerPartner['offer_user_id'],
        ]
      );
    } else {
      $_SESSION['indicator'] += 1;
      if($_SESSION['indicator'] == 5) {
        header("Location: itemplace.php");
        
      } else {
        header("Location: message-proposals.php");
      }
    }
  } else {

    $pagePartner = selectQueryFetch(
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

    header("Location: message-offers.php?offer_id=" . $pagePartner['offer_random_id']);
  }


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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Message Offers</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/message-offers.css">


</head>
<body class="bg-light">

  <!-- nav -->
  <?php
    include "layouts/nav.php"
  ?>



  <!-- Offcanvas -->
  <?php
    include "layouts/aside.php"
  ?>

  <!-- pre load -->
  <?php
    include "layouts/preload.php"
  ?>


  <main class="messages-info bg-white">
    <div class="row p-0 m-0 h-100">
      <div class="col-12 col-md-3 chats border-end pt-2 d-flex flex-column d-md-block">
        <div class="d-flex justify-content-between mb-3 align-items-center">
          <h5 class="fw-bold m-0">Chats</h5>
          <div class="d-flex gap-2 justify-content-end align-items-center d-md-none">
            <button class="btn btn-white forMessages tt" data-bs-placement="bottom" data-bs-title="Messages">
              <i class="bi bi-chat"></i>
            </button>
            <button class="btn btn-white forPlan tt" data-bs-placement="bottom" data-bs-title="Plan ">
              <i class="bi bi-info-circle-fill"></i>
            </button>
          </div>
        </div>
        <div class="input-group mb-3">
          <span class="input-group-text" id="search">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" id="my-input" class="form-control my-input" placeholder="Search Offers" aria-label="Search Offers" aria-describedby="search">
        </div>
        <div class="row m-0 p-0">
          <div class="col-6 px-1">
            <a href="message-offers.php" class="btn btn-sm btn-success rounded-pill w-100">
              Offers
            </a>
          </div>
          <div class="col-6 px-1">
            <a href="message-proposals.php" class="btn btn-sm btn-outline-success rounded-pill w-100">
              Proposals
            </a>
          </div>
        </div>
        <div class="w-100 table-container">
          <table id="example" class="display table table-hover" style="width:100%" >
            <thead>
              <tr>
                <th class="">
                  
                </th>
              </tr>
            </thead>
            <tbody class="chatTable">
              
            </tbody>
          </table>
        </div>

        <div class="p-0 w-100 table-container">
          <table id="searchTable" class="display table table-hover" style="width:100%" >
            <thead>
              <tr>
                <th class="text-muted">
                  search
                </th>
              </tr>
            </thead>
            <tbody class="chatTableSearch">
              <?php foreach($userChats as $userChat): ?>
                <?php
                  $UrlFiles = explode(',' , $userChat['item_url_file']);
                  $firstFile = $UrlFiles[0];
                  $ext = explode('.', $firstFile);
                  $ext = end($ext);
                ?>
                <tr>
                  <td class="border-0 <?= $getOfferId == $userChat['offer_random_id'] ? 'bg-success-subtle' : '' ?>">
                    <a href="message-offers.php?offer_id=<?= $userChat['offer_random_id'] ?>" class="text-decoration-none link-dark d-flex align-items-start">
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
                          <span class="d-block text-truncate text-secondary message-text" style="max-width: 150px;">
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
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-12 col-md-6 message border-end d-md-block h-100">
        <div class="row border-bottom first-top">
          <div class="col-8 p-2 d-flex align-items-center">
            <img src="profile-uploads/<?= isset($offerPartner['profile_picture']) ? $offerPartner['profile_picture'] : 'default.jpg' ?>" alt="" class="img-fluid rounded-pill" style="height: 40px; width: 40px">
            <div class="ps-2">
              <p class="fw-bold m-0"><?= isset($offerPartner['fullname']) ? $offerPartner['fullname'] : '' ?></p>
              <p class="fw-bold m-0">5 <i class="bi bi-star-fill text-warning"></i></p>
            </div>
          </div>
          <div class="col-4 d-flex gap-2 justify-content-end align-items-center d-md-none">
            
            <button class="btn btn-white forChats tt" data-bs-placement="bottom" data-bs-title="Chats">
              <i class="bi bi-chat-dots"></i>
            </button>
            <button class="btn btn-white forPlan tt" data-bs-placement="bottom" data-bs-title="Plan ">
              <i class="bi bi-info-circle-fill"></i>
            </button>
          </div>
        </div>
        <div class="row border-bottom p-2 second-top shadow-sm">
          <?php if(isset($offerPartner['item_random_id'])): ?>
            <div class="col-6 p-1">
              <button value="<?= $offerPartner['item_random_id'] ?>" class="forViewItemModal btn btn-outline-secondary w-100"
              data-bs-toggle="modal" data-bs-target="#itemModalView"
              >
                View Item
              </button>
            </div>
            <div class="col-6 p-1">
              <button value="<?= $offerPartner['offer_random_id'] ?>" class="forViewOfferModal btn btn-outline-success bg-green w-100"
              data-bs-toggle="modal" data-bs-target="#offerModalView"
              >
                View Offer
              </button>
            </div>
          <?php endif; ?>
        </div>
        <div class="third-top p-2" id="message-container">
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
          </div>
          <div class="row justify-content-start">
            <div class="col-10 col-md-8">
              <div class="w-100 border border-secondary bg-body-tertiary rounded p-2">
                <p class="m-0 message-text">Yes po. Ready to plan na po?</p>
              </div>
              <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
            </div>
          </div>
          <div class="row justify-content-start">
            <div class="col-10 col-md-8">
              <div class="w-100 border border-secondary bg-body-tertiary rounded p-2">
                <p class="m-0 message-text">Yes po. Ready to plan na po?</p>
              </div>
              <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
            </div>
          </div>
          <div class="row justify-content-start">
            <div class="col-10 col-md-8">
              <div class="w-100 border border-secondary bg-body-tertiary rounded p-2">
                <p class="m-0 message-text">Yes po. Ready to plan na po?</p>
              </div>
              <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
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
        </div>
        <div class="row bg-white p-2 border-top bottom-top">
          <?php if(!empty($offerPartner['item_status']) && $offerPartner['item_status'] == 'completed'):?>
            <p class="text-warning text-center m-0">This item has already been bartered.</p>
          <?php elseif(empty($offerPartner['offer_cancelled_reject_reason'])):?>
            <form id="messageForm" class="d-flex gap-2">
              <input name="message" id="messageInput" type="text" class="flex-grow-1 form-control my-input" placeholder="Aa">
              <input name="offerItemForMessage" type="hidden" class="flex-grow-1 form-control my-input" value="<?= $getOfferId ?>">
              <button type="button" class="btn btn-info border border-dark messageBtn">
                <i class="bi bi-send"></i>
              </button>
            </form>
          <?php else:?>
            <p class="fw-bold text-danger text-center m-0">Offer Rejected</p>
          <?php endif?>
        </div>
      </div>
      <div class="col-12 col-md-3 plan d-md-block pt-2 pb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold m-0">Plan</h5>
          <div class="d-flex gap-2 justify-content-end align-items-center d-md-none">
            <button class="btn btn-white forMessages tt" data-bs-placement="bottom" data-bs-title="Messages">
              <i class="bi bi-chat"></i>
            </button>
            <button class="btn btn-white forChats tt" data-bs-placement="bottom" data-bs-title="Chats">
              <i class="bi bi-chat-dots"></i>
            </button>
          </div>
        </div>
        <div class="border rounded p-2 mb-3">
          <p class="small-text">Item Offered</p>
          <?php if(isset($offerPartner['offer_url_file'])): ?>
            <div class="row align-items-end">
              <div class="col-4 d-flex justify-content-center">
                <?php if (in_array($offerExt, $allowedImages)): ?>
                  <img src="offer-uploads/<?= $offerFirstFile ?>" class="plan-img shadow-sm">
                <?php else: ?>
                  <video src="offer-uploads/<?= $offerFirstFile ?>" class="plan-img shadow-sm"></video>
                <?php endif ?>
              </div>
              <div class="col-8">
                <p class="fw-bold m-0"><?= $offerPartner['offer_title'] ?></p>
                <button value="<?= $offerPartner['offer_random_id'] ?>" class="forViewOfferModal btn btn-sm btn-outline-success bg-green w-100"
                data-bs-toggle="modal" data-bs-target="#offerModalView"
                >
                View Details</button>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div>
          <?php if(empty($offerPartner['offer_status'])): ?>
          <?php elseif($offerPartner['offer_status'] == 'accepted' && isset($offerPartner['offer_url_file'])): ?>
            <h5 class="text-center bg-success rounded py-2 text-white">Offer Accepted</h5>
            <div class="w-100 d-flex justify-content-center mb-3">
              <a href="meet-up-receiver.php?offer_id=<?= $getOfferId ?>" class="link-success">View Meet Up <i class="bi bi-geo-alt-fill"></i></a>
            </div>
            <div class="w-100 d-flex justify-content-center">
              <img src="assets/offer-accepted.png" style="height: 180px">
            </div>
          <?php elseif($offerPartner['offer_status'] == 'rejected' && isset($offerPartner['offer_url_file'])): ?>
            <h5 class="text-center bg-danger rounded py-2 text-white">Offer Rejected</h5>
            <p class="m-0">Reject Reason: </p>
            <p class="m-0 ps-3 pe-2 text-secondary"><?= $offerPartner['offer_cancelled_reject_reason'] ?> </p>
          <?php elseif($offerPartner['item_status'] == 'completed' && isset($offerPartner['offer_url_file'])): ?>
            <h5 class="text-center bg-warning rounded py-2 text-white">This item has already been bartered.</h5>
          <?php elseif(isset($offerPartner['offer_url_file'])): ?>
            <form id="planForm" action="">
              <input name="planLng" id="planLng" type="hidden" value="<?= $offerPartner['offer_lng'] ?>">
              <input name="planLat" id="planLat" type="hidden" value="<?= $offerPartner['offer_lat'] ?>">
              <input name="itemOfferForPlan" id="itemOfferForPlan" type="hidden" value="<?= $getOfferId ?>">
              <div class="form-floating mb-3">
                <input name="locationMeetUp" id="locationMeetUp" type="text" class="form-control my-input" id="locationMeetUp" placeholder="location" value="<?= $offerPartner['offer_meet_up_place'] ?>" required>
                <input type="hidden" name="locationMeetUpReal" id="locationMeetUpReal" value="<?= $offerPartner['offer_meet_up_place'] ?>">
                <label for="locationMeetUp">Landmark</label>
              </div>
              <div id="suggestions" class="bg-white"></div>
              <div id="map" class="w-100 mb-3"></div>
              <div class="form-floating mb-1">
                <input itemid="dateMeetup" name="dateMeetup" type="datetime-local" class="form-control my-input" id="dateMeetup" placeholder="Birth date" required value="<?= $offerPartner['offer_date_time_meet'] ?>">
                <label for="dateMeetup">Date of Meet up</label>
              </div>
              <div class="d-flex justify-content-end mb-3">
                <button type="button" id="updatePlan" class="btn btn-sm btn-light border">Update</button>
              </div>
              <div class="w-100">
                <button type="button" id="acceptOffer" class="btn btn-sm btn-outline-success w-100 mb-2">Accept</button>
                <button type="button" class="btn btn-sm btn-outline-danger w-100"
                data-bs-toggle="modal" data-bs-target="#rejectReasonModal"
                >Reject</button>
              </div>
            </form>
          <?php else: ?>
          <?php endif ?>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal View Item -->

  <div class="modal fade" id="itemModalView" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 fw-bolder text-success" id="exampleModalLabel">Item View</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="content-view-item modal-body bg-light">
        </div>
      </div>
    </div>
  </div>


  <!-- Modal View Offers -->

  <div class="modal fade" id="offerModalView" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 fw-bolder text-success" id="exampleModalLabel">Offer View</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="content-view-offer modal-body bg-light">
        </div>
      </div>
    </div>
  </div>

  <!-- Reject reason Modal -->
  <div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-labelledby="cancelReasonLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelReasonLabel">Reason for Rejection</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="cancelReasonText" class="form-label">Please provide your reason:</label>
            <textarea class="form-control" id="rejectReasonText" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" id="rejectOffer" class="btn btn-danger">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Spinner -->

  <?php
    require_once 'layouts/spinner-overlay.php';
  ?>


  <?php
    require_once 'layouts/bottom-link.php';
  ?>

  <script>
    $(document).ready(function() {


      function renderMessage(numIndicator) {
        
        var container = $('#message-container');
        $.ajax({
          
          method: 'POST',
          data: {offerId: <?= $getOfferId ?>},
          url: "includes/ajax/messages-offer.inc.php"
        }).done(res => {
          $('.third-top').html(res);
          if(numIndicator == 1) {
            container.scrollTop(container[0].scrollHeight);
          }
        })

      }

      function renderChats() {
        
        $.ajax({
          
          method: 'POST',
          data: {offerId: <?= $getOfferId ?>},
          url: "includes/ajax/chats-offers.inc.php"
        }).done(res => {
          $('.chatTable').html(res);
        })

      }
      
      $('body').css('padding-top', `${myNavHeight}px`);
      $('.messages-info').css('padding-top', `${myNavHeight}px`);

      let firstTop = $('.first-top').outerHeight();
      let secondTop = $('.second-top').outerHeight();
      let thirdTop = $('.third-top');
      let bottomTop = $('.bottom-top').outerHeight();

      // console.log(firstTop, secondTop, bottomTop);

      // Get the actual height of the viewport excluding mobile UI elements
      let vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty('--vh', `${vh}px`);

      // Set height based on adjusted viewport height
      thirdTop.css('height', `calc(var(--vh, 1vh) * 100 - ${myNavHeight + firstTop + secondTop + bottomTop}px)`);

      renderMessage(1);
      renderChats();
      

      setInterval(function() {
        renderMessage(2);
        renderChats();
      }, 1500);

      const contentViewItem = $('.content-view-item');

      $(document).on('click', '.forViewItemModal', function(e) {

        e.preventDefault();
        let itemValue = $(this).attr('value');

        $.ajax({
          method: 'GET',
          url: `includes/ajax/view-item.inc.php?item_random_id=${itemValue}`,
        }).done(res => {
          contentViewItem.html(res);
        })
      });

      const contentViewOffer = $('.content-view-offer');

      $(document).on('click', '.forViewOfferModal', function(e) {

        e.preventDefault();
        let offerValue = $(this).attr('value');

        $.ajax({
          method: 'GET',
          url: `includes/ajax/view-offer.inc.php?offer_random_id=${offerValue}`,
        }).done(res => {
          contentViewOffer.html(res);
        })
      });


      // var table = $('#example').DataTable({
      //   paging: false,
      //   ordering: false,
      //   lengthChange: false,
      //   info: false,
      //   searching: false
      // });

      var searchTable = $('#searchTable').DataTable({
        paging: false,
        ordering: false,
        lengthChange: false,
        info: false 
        // searching: false
      });

      $('#my-input').on('focus', function () {
        // Show searchTable when input is focused
        $('#searchTable').show();
        $('#example').hide();
      });

      $('#my-input').on('blur', function () {
          // If input is empty on blur, reverse display logic
          if (this.value === '') {
              $('#searchTable').hide();
              $('#example').show();
          }
      });

      // Update searchTable results as user types
      $('#my-input').on('input', function () {
          searchTable.search(this.value).draw();
      });


      $('#searchTable').hide();

      

      $('.dt-search').remove();

      // send message
      $(document).on('click', '.messageBtn', function (e) {

        e.preventDefault();
        
        let messageFormData = $("#messageForm").serializeArray();
        
        $.ajax({
          
          method: 'POST',
          url: "includes/ajax/send-message.inc.php?function=sendMessage",
          data: messageFormData,
          dataType: "JSON",
          
        }).done(res => {

          if(res.status == 'error') {
            Swal.fire({
              icon: res.status,
              title: res.title
            })
          }

        });

        $('#messageInput').val('');
        renderMessage(1);
        renderChats();
      })

      

      
      /* SEARCH LOCATION STARTS HERE */

      var locationMeetUp = $('#locationMeetUp');
      var locationMeetUpReal = $('#locationMeetUpReal');
      var suggestionsContainer = $('#suggestions');
      var planLng = $('#planLng');
      var planLat = $('#planLat');

      // Event listener for input in the search box
      locationMeetUp.on('input', function() {
        var query = $(this).val().trim();
        if (query.length >= 2) {
            // Step 1: Fetch suggestions from Nominatim API
            fetchSuggestions(query);
        } else {
            // Step 2: Clear suggestions if input is less than 3 characters
            clearSuggestions();
        }
      });


      // Function to fetch suggestions from Nominatim API
      function fetchSuggestions(query) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&bounded=1&viewbox=120.9166,14.1297,121.1981,14.4737`)
            .then(response => response.json())
            .then(data => {
                // Step 3: Display suggestions
                displaySuggestions(data);
            })
            .catch(error => {
                console.error(error);
            });
      }

      function displaySuggestions(data) {
        suggestionsContainer.empty();
        data.forEach(item => {
            var suggestion = $('<div class="suggestion"></div>').text(item.display_name);
            suggestion.on('click', function() {
                // Step 4: Handle suggestion click
                handleSuggestionClick(item);
            });
            suggestionsContainer.append(suggestion);
        });
        suggestionsContainer.show(); // Step 5: Show suggestions container
      }

      // Function to handle suggestion click
      function handleSuggestionClick(suggestion) {
        // Step 6: Update search input, selected title, and coordinates display
        locationMeetUp.val(suggestion.display_name);
        locationMeetUpReal.val(suggestion.display_name);
        planLat.val(suggestion.lat);
        planLng.val(suggestion.lon);
        meetupLocationDirection(suggestion.lon, suggestion.lat, suggestion.display_name);
        suggestionsContainer.hide(); // Hide suggestions container
      }

      // Function to clear suggestions and selected info
      function clearSuggestions() {
        suggestionsContainer.empty().hide(); // Clear and hide suggestions container
      }

      /* END SEARCH */

      $(document).on('click', '#updatePlan', function(e) {

        e.preventDefault();

        let planFormData = $("#planForm").serializeArray();
        
        $.ajax({
          
          method: 'POST',
          url: "includes/ajax/update-plan.inc.php?function=planUpdate",
          data: planFormData,
          dataType: "JSON",
          
        }).done(res => {

          if(res.status == 'error') {
            Swal.fire({
              icon: res.status,
              title: res.title
            })
          }

          if(res.status == 'success') {
            Swal.fire({
              icon: res.status,
              title: res.title
            }).then(result => {
              if(result.isConfirmed) {
                location.reload();
              }
            })
          }

        });
      })

      $(document).on('click', '#acceptOffer', function(e) {

        e.preventDefault();

        let planFormData = $("#planForm").serializeArray();

        Swal.fire({
          title: 'Do you want accept this offer?',
          icon: 'question',
          showCancelButton: true, // Adds the "No" button
          confirmButtonText: 'Yes', // Text for the "Yes" button
          cancelButtonText: 'No', // Text for the "No" button
        }).then((result) => {
          if (result.isConfirmed) {
            $(".spinner-overlay").removeClass('d-none');
            $.ajax({
              
              method: 'POST',
              url: "includes/ajax/update-plan.inc.php?function=acceptOffer",
              data: planFormData,
              dataType: "JSON",
            }).done(data => {
              Swal.fire({
                icon: data.status,
                title: data.title
              }).then(res => {
                if(res.isConfirmed) {
                  location.reload();
                }
              })
            })
          }
        });

      })

      // reject offer
      $(document).on('click', '#rejectOffer', function(e) {

        e.preventDefault();

        let planFormData = $("#planForm").serializeArray();
        planFormData.push({ name: "rejectReason", value: $("#rejectReasonText").val() });

        Swal.fire({
          title: 'Are you sure you want reject this offer?',
          icon: 'warning',
          showCancelButton: true, // Adds the "No" button
          confirmButtonText: 'Yes', // Text for the "Yes" button
          cancelButtonText: 'No', // Text for the "No" button
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              
              method: 'POST',
              url: "includes/ajax/update-plan.inc.php?function=rejectOffer",
              data: planFormData,
              dataType: "JSON",
            }).done(data => {
              Swal.fire({
                icon: data.status,
                title: data.title
              }).then(res => {
                if(res.isConfirmed) {
                  location.reload();
                }
              })
            })
          }
        });

      })

      var map;
      var routingControl;

      function meetupLocationDirection(meetUpLng, meetUpLat, meetUpPlace) {
        // leaflet map with osm titlelayer
        // Check if the map is already initialized
        if (!map) {
            // Initialize the map only if it hasn't been created yet
            map = L.map('map').setView([<?= json_encode($_SESSION["user_details"]["lat"]) ?>, <?= json_encode($_SESSION["user_details"]["lng"]) ?>], 13);
            var tileLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', { attribution: "OSM" }).addTo(map);
        } else {
            // If the map exists, just set the view to the new coordinates
            map.setView([<?= json_encode($_SESSION["user_details"]["lat"]) ?>, <?= json_encode($_SESSION["user_details"]["lng"]) ?>], 13);
        }
  
        var myLng = <?= json_encode($_SESSION["user_details"]["lng"]) ?>;
        var myLat = <?= json_encode($_SESSION["user_details"]["lat"]) ?>;

        // Clear any existing layers (markers and routes) from the map
        map.eachLayer(function (layer) {
            if (layer instanceof L.Marker || layer instanceof L.Routing.Control) {
                map.removeLayer(layer);
            }
        });

        // Clear the previous routing control if it exists
        if (routingControl) {
            map.removeControl(routingControl); // Remove previous route from the map
        }
  
        // markers with labels (tooltips), fixed and not draggable
        var startMarker = L.marker([myLat, myLng], { draggable: false }).addTo(map).bindTooltip('You', {permanent: true, className: 'start-label'});
        
        var middleMarker = L.marker([meetUpLat, meetUpLng], { draggable: false }).addTo(map).bindTooltip(meetUpPlace, {permanent: true, className: 'middle-label'});
  
        // Add a new routing control
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(myLat, myLng), // start
                L.latLng(meetUpLat, meetUpLng) // destination
            ],
            draggableWaypoints: false, // Disable dragging of waypoints
            addWaypoints: false, // Disable adding additional waypoints
            routeWhileDragging: true, // Allow route updating while dragging waypoints
            show: false // Hide instructions panel
        }).addTo(map);
      }

      function yourCurrentLocation() {

        // leaflet map with osm titlelayer
        // Check if the map is already initialized
        if (!map) {
            // Initialize the map only if it hasn't been created yet
            map = L.map('map').setView([<?= json_encode($_SESSION["user_details"]["lat"]) ?>, <?= json_encode($_SESSION["user_details"]["lng"]) ?>], 13);
            var tileLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', { attribution: "OSM" }).addTo(map);
        } else {
            // If the map exists, just set the view to the new coordinates
            map.setView([<?= json_encode($_SESSION["user_details"]["lat"]) ?>, <?= json_encode($_SESSION["user_details"]["lng"]) ?>], 13);
        }
  
        var myLng = <?= json_encode($_SESSION["user_details"]["lng"]) ?>;
        var myLat = <?= json_encode($_SESSION["user_details"]["lat"]) ?>;

        // Clear any existing layers (markers and routes) from the map
        map.eachLayer(function (layer) {
            if (layer instanceof L.Marker || layer instanceof L.Routing.Control) {
                map.removeLayer(layer);
            }
        });

        // markers with labels (tooltips), fixed and not draggable
        var startMarker = L.marker([myLat, myLng], { draggable: false }).addTo(map).bindTooltip('You', {permanent: true, className: 'start-label'});
      }
      

      <?php if($offerPartner['item_status'] == 'completed'): ?>
      <?php elseif($offerPartner['offer_status'] == 'rejected'): ?>
      <?php elseif(empty($offerPartner['offer_lng'])): ?>
        yourCurrentLocation()
      <?php elseif($offerPartner['offer_status'] == 'pending'): ?>
        meetupLocationDirection(
                                  <?= json_encode($offerPartner['offer_lng']) ?>,
                                  <?= json_encode($offerPartner['offer_lat']) ?>,
                                  <?= json_encode($offerPartner['offer_meet_up_place']) ?>
                                )
      <?php else: ?>
      <?php endif; ?>

      // DISPLAY MANIPULATION
      const chat = $('.chats');
      const message = $('.message');
      const plan = $('.plan');

      <?php if(isset($_GET['offer_id'])): ?>
        message.removeClass('d-none');
        plan.addClass('d-none');
        chat.addClass('d-none');
      <?php else: ?>
        message.addClass('d-none');
        plan.addClass('d-none');
        chat.removeClass('d-none');
      <?php endif ?>

      $(document).on('click', '.forChats', function(e) {

        e.preventDefault();

        // message.addClass('d-none');
        // plan.addClass('d-none');
        // chat.removeClass('d-none');

        message.fadeOut(150, function() {
          message.addClass('d-none');
        });
        plan.fadeOut(150, function() {
          plan.addClass('d-none');
        });
        chat.fadeIn(150, function() {
          chat.removeClass('d-none');
        });
      })

      $(document).on('click', '.forMessages', function(e) {

        e.preventDefault();

        // message.removeClass('d-none');
        // plan.addClass('d-none');
        // chat.addClass('d-none');

        message.fadeIn(150, function() {
          message.removeClass('d-none');
        });
        plan.fadeOut(150, function() {
          plan.addClass('d-none');
        });
        chat.fadeOut(150, function() {
          chat.addClass('d-none');
        });
      })

      $(document).on('click', '.forPlan', function(e) {

        e.preventDefault();

        // message.addClass('d-none');
        // plan.removeClass('d-none');
        // chat.addClass('d-none');

        message.fadeOut(150, function() {
          message.addClass('d-none');
        });
        plan.fadeIn(150, function() {
          plan.removeClass('d-none');
        });
        chat.fadeOut(150, function() {
          chat.addClass('d-none');
        });
      })
      


    });

    
  </script>



</body>
</html>