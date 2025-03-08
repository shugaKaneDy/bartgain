<?php
  session_start();
  require_once 'dbcon.php';

  if(!empty($_SESSION["user_details"])) {
    $userId = $_SESSION["user_details"]["user_id"];
  } else {
    ?>
      <script>
        alert("You must login first");
        window.location.href = "sign-in.php"
      </script>
    <?php
    die();
  }

  $getOfferId = 0;
  $today = date('Y-m-d\TH:i', strtotime('+1 hour'));

  if(isset($_GET["offerId"])) {
    $getOfferId = $_GET["offerId"];
  }

  


  

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages Offers</title>
  <link rel="icon" href="B.png">


  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Items Place</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/general.css">
  <style>
    body {
      padding-left: 330px;
      padding-right: 330px;
      padding-top: 70px;
    }
    .side-left {
      position: fixed;
      left: 0;
      top: 0;
      width: 330px;
      bottom: 0;
      padding-top: 70px;
      padding-bottom: 100px;
    }
    .side-right {
      position: fixed;
      right: 0;
      top: 0;
      width: 330px;
      bottom: 0;
      padding-top: 70px;
      overflow-y: auto;
    }
    .main-message {
      height: calc(100vh - 70px);
    }
    .message-body {
      height: 70vh;
      overflow-y: auto;
    }
    .image-cover {
      height: 60px; /* Fixed height */
      width: 100px;  /* Fixed width */
      object-fit: cover; /* Adjust image aspect ratio */
    } 

    @media (max-width: 568px) {
      body {
        padding-left: 0;
        padding-right: 0;
        padding-top: 100px;
      }
      .image-container {
        height: 140px;
      }

      .main-message {
        height: calc(100vh - 100px);
      }

      .side-left, .side-right {
        padding-top: 100px;
        width: 100%;
      }
      .message-body {
        height: 65vh;
      }

      
    }


    /* for data tables */
    .top {
      text-align: center;
    }
    .dataTables_filter {
      display: inline-block;
      float: none !important;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_paginate,
    .dataTables_wrapper .dataTables_info {
      display: none;
    }

    /* Ensuring 100% width for td and a on all screen sizes */
    table {
      width: 100%;
    }
    td {
      white-space: nowrap;
    }

    /* start of auto search */

    #suggestions {
      position: absolute;
      background-color: white;
      z-index: 1000;
      max-width: 100%;
      max-height: 200px;
      overflow-y: auto;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      border-radius: 5px;
      padding: 10px;
      display: none;
    }

    .suggestion {
      cursor: pointer;
      padding: 10px;
      margin-bottom: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .suggestion:hover {
      background-color: #f1f1f1;
    }

    .suggestion-title {
      font-weight: bold;
    }

    /* END */


  </style>
</head>
<body>
  <?php include 'layout/navbar.php'; ?>
  <?php
    include "verified-authentication.php";
  ?>

  <div class="side-left border bg-white <?= $getOfferId == 0 ?  "" : "d-none" ?> d-md-block px-2">
    <h5 class="fw-bold py-3">Chats</h5>
    <div class="mb-3">
      <a href="messages-offers.php" class="btn btn-success btn-sm rounded-pill px-5">Offers</a>
      <a href="messages-proposals.php" class="btn btn-outline-success btn-sm rounded-pill px-5">Proposal</a>
    </div>
    
    <div class="rounded" style="max-height: 70vh; overflow-y: auto;">
      
      <table id="myTable">
        <thead class="thead table-success table-hover table-striped">
          <th class="text-center">Inforamtion</th>
        </thead>
        <tbody>
          <?php
            $query = "SELECT offers.*, users.*, items.*, MAX(messages.message_created_at) AS latest_message_created_at
            FROM offers
            INNER JOIN users ON offers.sender_id = users.user_id
            INNER JOIN items ON offers.item_id = items.item_id
            LEFT JOIN messages ON offers.offer_id = messages.offer_id
            WHERE offers.r_receiver_id = $userId
            GROUP BY offers.offer_id
            ORDER BY latest_message_created_at DESC";
            $stmt = $conn->query($query);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $results = $stmt->fetchAll();

            $accessGranted = false;

            if(isset($_GET["offerId"])) {
              foreach($results as $row) {
                if ($getOfferId == $row->offer_id) {
                  $accessGranted = true;
                  break;
                } else {
                  $accessGranted = false;
                }
              }

              if(!$accessGranted) {
                ?>
                  <script>
                    alert("You are not permitted to access this conversation");
                    window.location.href = "messages-offers.php";
                  </script>
                <?php
              }
            }

            foreach($results as $row) {

              $imgURls = explode(",", $row->item_url_picture);

              $totalRating = 0;
              if($row->user_rate_count == 0) {
                $totalRating = 0;
              } else {
                $totalRating = $row->user_rating / $row->user_rate_count;
              }
              $totalRating = round($totalRating, 1);
              
              ?>
              <tr>
                <td>
                  <a href="messages-offers.php?offerId=<?= $row->offer_id ?>" <?= $row->offer_id == $getOfferId ? 'id="focusElement"' : '' ?> class="text-decoration-none text-dark position-relative w-100">
                    <div class="px-2 py-2 <?= $row->offer_id == $getOfferId ? "bg-primary-subtle" : "" ?> rounded border">
                      <div class="row">
                        <div class="col-5 d-flex flex-column align-items-center border-end">
                          <p class="m-0" style="font-size: 10px;">Item Id: <?= $row->item_id ?></p>
                          <img src="item-photo/<?= $imgURls[0] ?>" alt="Profile Picture" class="rounded" style="width: 90px; height: 70px; object-fit: cover;">
                          <p class="m-0 text-xs fw-bold text-center text-wrap"><?= $row->item_title ?></p>
                          <p class="m-0 text-xs fw-bold text-center text-wrap text-success"><?= $row->item_swap_option ?></p>
                          <p class="m-0 text-xs fw-bold text-center text-wrap text-muted"><?= $row->item_status ?></p>
                        </div>
                        <div class="col-7">
                          <div class="d-flex gap-1">
                            <img src="profile-picture/<?= $row->profile_picture ? $row->profile_picture : "default.jpg" ?>" alt="Profile Picture" class="rounded-circle" style="width: 30px; height: 30px;">
                            <div class="">
                              <div>
                                <p class="fw-bold m-0 text-xs text-wrap"><?= $row->fullname?></p>
                              </div>
                              <p class="fw-bold m-0 text-xs"><?= $totalRating ?> <i class="bi bi-star-fill text-warning"></i></p>
                            </div>
                          </div>
                          <p class="title m-0 text-sm"><?= $row->offer_title ?></p>
                          <p class="date-applied m-0 text-xs text-muted text-wrap">Last Message: <?= $row->latest_message_created_at ?></p>
                          <p class="status m-0 text-muted text-sm"><?= $row->offer_status ?></p>
                        </div>
                      </div>
                    </div>
                  </a>
                </td>
              </tr>
              <?php
            }
          ?>
        </tbody>
      </table>

    </div>
  </div>

  <div class="side-right bg-white border px-2 d-none d-md-block">
    <h5 class="text-center my-3">Make Plan</h5>


    <div class="p-1 pt-2">
      <div class="border p-2 rounded mb-2">
        <p class="offerRightFullname text-sm fw-bold m-0"></p>
        <p class="offerRightUserID text-sm">User ID: </p>
        <div class="d-flex gap-2 mb-3">
          <img src="" class="image-cover rounded offerRightImg" alt="">
          <div>
            <p class="offerRightTitle text-sm fw-bold m-0"></p>
            <p class="offerRightOfferId text-sm m-0"></p>
            <p class="offerRightDescription text-sm m-0"></p>
            <p class="offerRightCondition text-sm m-0">Condition: New</p>
          </div>
        </div>
      </div>

      <div class="border p-2 rounded mb-4">
        <p class="fw-bold">Plan</p>
        <div>
          <p class="offerMeetUpPlace text-sm m-0">
            Meet up place: 
          </p>
        </div>
        <div>
          <p class="offerMeetUpDateTime text-sm m-0">
            Meet up date and time: 
          </p>
        </div>
      </div>


      <form id="updatePlanForm" action="">
        <div class="mb-2">
          <label for="">Meet-up Place</label>
          <input id="search-input" name="offerMeetUpPlace" type="text" class="form-control offerRightMeet" placeholder="Search location in Cavite..." value="" required>
          <div id="suggestions"></div>
          <!-- Hidden fields for latitude and longitude -->
          <input type="hidden" id="offerLat" name="offerLat">
          <input type="hidden" id="offerLng" name="offerLng">
          <input type="hidden" id="offerId" name="offerId" value="<?= $getOfferId ?>">
        </div>
        <div class="mb-2">
          <label for="">Date and Time</label>
          <input name="offerDateTime" type="datetime-local" min="<?= $today ?>" class="form-control offerRightDate" required>
          
        </div>
        <button id="updatePlanBtn" type="button" class="btn btn-sm border btn-light mb-3">Update</button>
      </form>
      <div id="acceptBtn">
        <div class="d-flex gap-3">
          <button id="acceptOfferBtn" href="accept_offer.php?offerId=<?=$getOfferId?>" class="btn btn-success w-100">Accept Offer</button>
          <button id="rejectOfferBtn" class="btn btn-sm btn-light border w-100" data-bs-toggle="modal" data-bs-target="#rejectOfferModal">Reject</button>
        </div>
      </div>
    </div>
  </div>

  <main class="fluid border main-message">

    <div class="message-top border-bottom px-3 py-2">
      <div class="d-flex gap-3 align-items-center">
        <img src="" alt="" class="offerItemTopImg rounded-circle" style="width: 50px; height: 50px;">
        <div class="">
          <p class="offerItemTopTitle fw-bold m-0 text-sm"></p>
          <p class="offerItemTopName fw-bold m-0 text-sm"></p>
        </div>
      </div>
    </div>

    <div id="messageBody" class="message-body px-3 pt-5">
      <!-- message content -->
    </div>
    <div id="sendMessageDiv" class="border-top">
      <form id="sendMessageForm">
        <div class="d-flex">
          <input type="hidden" name="messageOfferId" id="messageOfferId" value="<?= $getOfferId ?>">
          <input type="text" class="form-control" name="receiverMessage" id="receiverMessage">
          <button type="submit" class="btn btn-info btn-sm px-4"><i class="bi bi-send"></i></button>
        </div>
      </form>
    </div>
  </main>

  <!-- Reject Modal -->
  <div class="modal fade" id="rejectOfferModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rejectModalLabel">Reason for Rejection</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="confirmOfferRejectForm" action="reject_cancel_offer.php" method="post">
            <input type="hidden" name="offerId" value="<?= $getOfferId ?>">
            <input type="hidden" name="status" value="rejected">
            <div class="mb-3">
              <label for="rejectReason" class="form-label">Reason</label>
              <textarea class="form-control" id="rejectReason" name="rejectReason" rows="3" required></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="confirmRejectOfferBtn" type="button" class="btn btn-danger">Reject</button>
        </div>
      </div>
    </div>
  </div>
  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="js/plugins/sweetalert2/swal.js"></script>


<!-- update location -->
<?php
  if(isset($_SESSION['user_details'])) {
    ?>
      <script src="update-location.js"></script>
    <?php
  }
?>  

<script>

  $(document).ready( function () {
    $('#myTable').DataTable({
      "ordering": false, // Disable ordering (sorting) for the entire table
      "paging": false, // Disable pagination
      "dom": '<"top"f>rt<"bottom"lp><"clear">',
      "language": {
        "search": ""
      }
    });

    $('#myTable_filter label').prepend('<i class="bi bi-search"></i> ');

    renderOfferInfo();

    loadMessages();
    // setInterval(loadMessages, 500);



    /* Start of search */

    var $searchInput = $('#search-input');
    var $suggestionsContainer = $('#suggestions');
    var $offerLat = $('#offerLat');
    var $offerLng = $('#offerLng');

    // Event listener for input in the search box
    $searchInput.on('input', function() {
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
      $suggestionsContainer.empty();
      data.forEach(item => {
          var $suggestion = $('<div class="suggestion"></div>').text(item.display_name);
          $suggestion.on('click', function() {
              // Step 4: Handle suggestion click
              handleSuggestionClick(item);
          });
          $suggestionsContainer.append($suggestion);
      });
      $suggestionsContainer.show(); // Step 5: Show suggestions container
    }

     // Function to handle suggestion click
     function handleSuggestionClick(suggestion) {
        // Step 6: Update search input, selected title, and coordinates display
        $searchInput.val(suggestion.display_name);
        $offerLat.val(suggestion.lat);
        $offerLng.val(suggestion.lon);
        $suggestionsContainer.hide(); // Hide suggestions container
      }

      // Function to clear suggestions and selected info
      function clearSuggestions() {
        $suggestionsContainer.empty().hide(); // Clear and hide suggestions container
      }

      // Close suggestions when clicking outside the input field or suggestions container
      $(document).on('click', function(e) {
        if (!$(e.target).closest('#search-input, #suggestions').length) {
            $suggestionsContainer.hide();
        }
      });

      /* End */

      $('#updatePlanBtn').on('click', function() {

        var searchInput = $("#search-input").val();
        var meetUpDateTime = $("#meetUpDateTime").val();
        console.log(searchInput);


        if(searchInput == "") {
          alert("Please add meetup place");
          return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to update the plan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#updatePlanForm').submit(); // Submit the form if confirmed
            }
        });
      });

      $('#updatePlanForm').on('submit', async function(e) {
        e.preventDefault();

        try {
            const formData = $(this).serialize();

            const response = await $.ajax({
                url: 'update_plan.php?function=offer',
                type: 'POST',
                data: formData,
                dataType: 'json'
            });

            if (response.status === 'success') {
                alert(response.message);
                // Optionally, update the UI or reload the page
                loadMessages();
            } else {
                alert(response.message);
            }
        } catch (error) {
            console.error('Error updating plan:', error);
            alert('An error occurred while updating the plan.');
        }
      });

      $('#acceptOfferBtn').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Are you sure?',
          text: 'Do you want to accept this offer?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, accept it!'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = $(this).attr('href'); // Redirect if confirmed
          }
        });
      });

      $('#confirmRejectOfferBtn').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Are you sure?',
          text: 'Do you want to reject this offer?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#confirmOfferRejectForm').submit();
          }
        });
      });




  });

  $('.forSide-left').on('click', function() {
    $('.side-right').addClass('d-none');
    $('.side-left').toggleClass('d-none');
  });

  $('.forSide-right').on('click', function() {
    $('.side-left').addClass('d-none');
    $('.side-right').toggleClass('d-none');
  });

  // for focus element
  $(document).ready(function() {
    var element = $("#focusElement");
    if (element.length > 0) {
        element[0].scrollIntoView({block: "center" });
        element.focus({ preventScroll: true });
    }
  });

  function renderOfferInfo() {
    
    var offerValue = <?= json_encode($getOfferId) ?> || 0;

    if (offerValue == 0) {
      console.log("no ittem found");
      $('.main-message').html('Messages');
      $('.side-right').html('Make Plan');
    }

    $.ajax({
      url: 'fetch_item_messages.php',
      type: 'GET',
      data: { offer_id: offerValue },
      success: function(response) {
        // console.log(response);

        var imageUrls = response.item_url_picture.split(',');
        // console.log(imageUrls);

        //for top section
        $('.offerItemTopImg').attr('src', 'item-photo/' + imageUrls[0]);
        $('.offerItemTopTitle').text(response.item_title);
        $('.offerItemTopName').text(response.fullname);
        // console.log(response.r_url_picture);

        //for right-side section
        $('.offerRightImg').attr('src', 'offer-item-photo/' + response.offer_url_picture);
        $('.offerRightFullname').text(response.fullname + "'s proposal");
        $('.offerRightUserID').text("User ID: " + response.user_id);
        $('.offerRightTitle').text(response.offer_title);
        $('.offerRightOfferId').text("Offer ID: " + response.offer_id);
        $('.offerRightDescription').text(response.offer_description);
        $('.offerRightCondition').text("Condition: " + response.offer_item_condition);

       
        
        $('.offerMeetUpPlace').text("Meet up place: " + response.offer_meet_up_place);
        $('.offerMeetUpDateTime').text("Meet up date and time: " + response.offer_date_time_meet);
        
        $('.offerRightDate').val(response.offer_date_time_meet);

        if(response.offer_status == 'accepted') {
          $('#updatePlanForm').html(' ');
          $('#acceptBtn').html(' ');
          $('.side-right').append('<p class="text-white"> <span class="bg-success py-2 px-4 rounded-pill">Offer Accepted</span></p>');
          $('.side-right').append('<a href="meet-up.php?offerId='+ response.offer_id +'" class="text-center">Go to Meet Up</a>')
          
        }

        if(response.item_status == "completed" || response.item_status == "deleted"){
          $('#updatePlanForm').html(' ');
          $('#acceptBtn').html(' ');
          $('#sendMessageDiv').html(' ');
          $('#sendMessageDiv').append(`<p class="m-0 text-center"> Item ${response.item_status}</p>`);

        }
        
        if(response.offer_status == "rejected") {
          $('#sendMessageDiv').html(' ');
          $('#updatePlanForm').html(' ');
          $('#acceptBtn').html(' ');
          $('#sendMessageDiv').append(`<p class="m-0 text-center text-sm text-danger"> Offer ${response.offer_status}</p>`);
          $('#sendMessageDiv').append(`<p class="m-0 text-center text-sm text-danger"> Reason: ${response.offer_cancelled_rejected_reason}</p>`);
          $('.side-right').append(`<p class="m-0 text-center text-sm text-danger"> Offer ${response.offer_status}</p>`);
          $('.side-right').append(`<p class="m-0 text-center text-sm text-danger"> Reason: ${response.offer_cancelled_rejected_reason}</p>`);
        }

        if(response.offer_status == "cancelled") {
          $('#sendMessageDiv').html(' ');
          $('#updatePlanForm').html(' ');
          $('#acceptBtn').html(' ');
          $('#sendMessageDiv').append(`<p class="m-0 text-center text-sm text-danger"> Offer ${response.offer_status}</p>`);
          $('#sendMessageDiv').append(`<p class="m-0 text-center text-sm text-danger"> Reason: ${response.offer_cancelled_rejected_reason}</p>`);
          $('.side-right').append(`<p class="m-0 text-center text-sm text-danger"> Offer ${response.offer_status}</p>`);
          $('.side-right').append(`<p class="m-0 text-center text-sm text-danger"> Reason: ${response.offer_cancelled_rejected_reason}</p>`);
        }


      }
    });
  }

  function loadMessages() {
    $.ajax({
        url: 'offers_load_messages.php', // Replace with your PHP script to fetch messages
        type: 'GET',
        dataType: 'html',
        data: { offer_id: <?= $getOfferId ?> }, // Pass offer_id to fetch messages for this offer
        success: function (response) {
            $('#messageBody').html(response); // Update message body with fetched messages
            scrollMessageBodyToBottom();
        },
        error: function (xhr, status, error) {
            console.error('Error fetching messages:', error);
        }
    });
  }

  $('#sendMessageForm').on('submit', function (e) {
    e.preventDefault();
    var formData = $(this).serialize(); // Serialize form data

    $.ajax({
        url: 'offers_send_messages.php', // Replace with your PHP script to handle message sending
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            if (response.status == 'success') {
                $('#receiverMessage').val(''); // Clear input field
                loadMessages(); // Reload messages after sending
            } else {
                console.error('Message sending failed:', response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error sending message:', error);
        }
    });
  });

  function scrollMessageBodyToBottom() {
    var messageBody = document.getElementById('messageBody');
    messageBody.scrollTop = messageBody.scrollHeight;
  }

  if ($('#messageBody').html().trim().length > 0) {
    scrollMessageBodyToBottom();
  }
</script>
</body>
</html>