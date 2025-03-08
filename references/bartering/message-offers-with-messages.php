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

  if(isset($_GET["offerId"])) {
    $getOfferId = $_GET["offerId"];
  }

  


  

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages</title>

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


  </style>
</head>
<body>
  <?php include 'layout/navbar.php'; ?>

  <div class="side-left border bg-white <?= $getOfferId == 0 ?  "" : "d-none" ?> d-md-block px-2">
    <h5 class="fw-bold py-3">Chats</h5>
    <div class="mb-3">
      <a href="#" class="btn btn-success btn-sm rounded-pill px-5">Offers</a>
      <a href="#" class="btn btn-outline-success btn-sm rounded-pill px-5">Proposal</a>
    </div>
    
    <div class="rounded" style="max-height: 70vh; overflow-y: auto;">
      
      <table id="myTable">
        <thead class="thead table-success table-hover table-striped">
          <th class="text-center">Inforamtion</th>
        </thead>
        <tbody>
          <?php
            $query = "SELECT * FROM offers 
            INNER JOIN users ON offers.sender_id = users.user_id
            INNER JOIN items ON offers.item_id = items.item_id  
            WHERE offers.r_receiver_id = $userId ORDER BY offers.offer_id DESC";
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
              
              ?>
              <tr>
                <td>
                  <a href="messages-offers.php?offerId=<?= $row->offer_id ?>" <?= $row->offer_id == $getOfferId ? 'id="focusElement"' : '' ?> class="text-decoration-none text-dark position-relative w-100">
                    <div class="px-2 py-2 <?= $row->offer_id == $getOfferId ? "bg-primary-subtle" : "" ?> rounded border">
                      <div class="row">
                        <div class="col-5 d-flex flex-column align-items-center border-end">
                          <img src="item-photo/<?= $row->item_url_picture ?>" alt="Profile Picture" class="rounded" style="width: 90px; height: 70px; object-fit: cover;">
                          <p class="m-0 text-xs fw-bold text-center text-wrap"><?= $row->item_title ?></p>
                        </div>
                        <div class="col-7">
                          <div class="d-flex gap-1">
                            <img src="profile-picture/<?= $row->profile_picture ? $row->profile_picture : "default.jpg" ?>" alt="Profile Picture" class="rounded-circle" style="width: 30px; height: 30px;">
                            <div class="">
                              <div>
                                <p class="fw-bold m-0 text-xs text-wrap"><?= $row->fullname?></p>
                              </div>
                              <p class="fw-bold m-0 text-xs">3.3 <i class="bi bi-star-fill text-warning"></i></p>
                            </div>
                          </div>
                          <p class="title m-0 text-sm"><?= $row->offer_title ?></p>
                          <p class="date-applied m-0 text-xs text-muted"><?= $row->offer_created_at ?></p>
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
      <div class="border p-2 rounded mb-4">
        <p class="offerRightFullname text-sm fw-bold"></p>
        <div class="d-flex gap-2 mb-3">
          <img src="" class="image-cover rounded offerRightImg" alt="">
          <div>
            <p class="offerRightTitle text-sm fw-bold m-0"></p>
            <p class="offerRightDescription text-sm m-0"></p>
            <p class="offerRightCondition text-sm m-0">Condition: New</p>
          </div>
        </div>
      </div>
      <div class="mb-2">
        <label for="">Meet-up Place</label>
        <input type="text" class="form-control offerRightMeet" value="">
      </div>
      <div class="mb-2">
        <label for="">Date and Time</label>
        <input type="datetime-local" min="<?= $today ?>" class="form-control offerRightDate">
      </div>
      <div>
        <button class="btn btn-sm btn-light mb-3">Update</button>
        <div class="d-flex gap-3">
          <button class="btn btn-sm btn-primary w-100">Accept</button>
          <button class="btn btn-sm btn-danger w-100">Reject</button>
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

    <div class="message-body px-3 pt-5">
      <?php
        $queryMessages = "SELECT * FROM messages
        INNER JOIN users ON messages.sender_user_id = users.user_id
        INNER JOIN offers ON messages.offer_id = offers.offer_id
        WHERE messages.offer_id = $getOfferId";
        $stmtMessages = $conn->query($queryMessages);
        $stmtMessages->setFetchMode(PDO::FETCH_OBJ);
        $resultsMessages = $stmtMessages->fetchAll();

        

        foreach ($resultsMessages as $rowMessage) {

          ?>
            <input type="hidden" name="senderUserId" id="senderUserId" value="<?= $rowMessage->sender_user_id ?>">
            <input type="hidden" name="messagesOfferId" id="messagesOfferId" value="<?= $getOfferId ?>">
          <?php

          if ($rowMessage->sender_message) {
            ?>
              <div class="d-flex justify-content-start mb-2 gap-2 w-100">
                <div class="d-flex align-items-end gap-3">
                  <img src="profile-picture/<?= $rowMessage->profile_picture ? $rowMessage->profile_picture : "default.jpg" ?>" alt="Profile Picture" class="rounded-circle" style="width: 30px; height: 30px;">
                </div>
                <div class="w-50 ">
                  <p class="text-xs text-muted m-0"><?= $rowMessage->fullname ?></p>
                  <div class="border border-secondary p-1 rounded bg-light text-xs"><?= $rowMessage->sender_message ?></div>
                  <p class="text-xs text-muted m-0"><?= $rowMessage->message_created_at ?></p>
                </div>
              </div>

            <?php

          }

          if ($rowMessage->receiver_message) {
            ?>
              <div class="d-flex justify-content-end gap-2 mb-2 w-100">
                <div class="w-50 ">
                  <div class=" border border-primary p-1 rounded bg-primary-subtle text-xs">Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>
                  <p class="text-xs text-muted m-0">10/26/2023 10:30:11</p>
                </div>
              </div>

            <?php
          }
        }

      ?>
    </div>
    <div class="border-top">
      <form action="">
        <div class="d-flex">
          <input type="text" class="form-control" name="receiverMessage" id="receiverMessage">
          <button class="btn btn-info btn-sm px-4"><i class="bi bi-send"></i></button>
        </div>
      </form>
    </div>
  </main>
  

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="js/plugins/sweetalert2/swal.js"></script>

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
        console.log(response);

        //for top section
        $('.offerItemTopImg').attr('src', 'item-photo/' + response.item_url_picture);
        $('.offerItemTopTitle').text(response.item_title);
        $('.offerItemTopName').text(response.fullname);
        // console.log(response.r_url_picture);

        //for right-side section
        $('.offerRightImg').attr('src', 'offer-item-photo/' + response.offer_url_picture);
        $('.offerRightFullname').text(response.fullname + "'s proposal");
        $('.offerRightTitle').text(response.offer_title);
        $('.offerRightDescription').text(response.offer_description);
        $('.offerRightCondition').text("Condition: " + response.offer_item_condition);

        $('.offerRightMeet').val(response.offer_meet_up_place);
        $('.offerRightDate').val(response.offer_date_time_meet);
        


      }
    });
  }
</script>
</body>
</html>