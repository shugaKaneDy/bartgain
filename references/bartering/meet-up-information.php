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

  if(isset($_GET["meetUpId"])) {
    $meetUpId = $_GET["meetUpId"];
    // echo $messageId;
  } else {
    ?>
      <script>
        alert("You are not permited to access this page");
        window.location.href = "meet-up.php"
      </script>
    <?php
    die();
  }

  $selectQuery = "SELECT * FROM meet_up WHERE meet_up_id = $meetUpId";
  $selectStmt = $conn->query($selectQuery);
  $selectStmt->setFetchMode(PDO::FETCH_OBJ);
  $selectResult = $selectStmt->fetch();

  $accessGranted = false;

  if($selectResult->receiver_id == $userId || $selectResult->sender_id == $userId) {
    $accessGranted = true;
  } else {
    $accessGranted = false;
  }

  if(!$accessGranted) {
    ?>
      <script>
        alert("You are not permited to access this page");
        window.location.href = "meet-up.php"
      </script>
    <?php
    die();
  }

  // print_r($selectResult);

  if($selectResult->receiver_id == $userId) {
        
    $partnerQuery = " SELECT * 
                      FROM meet_up
                      INNER JOIN users ON meet_up.sender_id = users.user_id
                      INNER JOIN offers ON meet_up.offer_id = offers.offer_id
                      INNER JOIN items ON offers.item_id = items.item_id
                      WHERE meet_up.sender_id = $selectResult->sender_id
                      AND meet_up.meet_up_id = $selectResult->meet_up_id";
    $partnerStmt = $conn->query($partnerQuery);
    $partnerStmt->setFetchMode(PDO::FETCH_OBJ);
    $partnerResult = $partnerStmt->fetch();
    $userReason = "cancel_receiver_reason";


    $partnerImgURls = explode(",", $partnerResult->item_url_picture); 

    $leftItem = "Your Item";
    $rightItem = "Partner Offer";

    // print_r($partnerResult);
  } else {
    $partnerQuery = " SELECT * 
                      FROM meet_up
                      INNER JOIN users ON meet_up.receiver_id = users.user_id
                      INNER JOIN offers ON meet_up.offer_id = offers.offer_id
                      INNER JOIN items ON offers.item_id = items.item_id
                      WHERE meet_up.receiver_id = $selectResult->receiver_id
                      AND meet_up.meet_up_id = $selectResult->meet_up_id";
    $partnerStmt = $conn->query($partnerQuery);
    $partnerStmt->setFetchMode(PDO::FETCH_OBJ);
    $partnerResult = $partnerStmt->fetch();
    $userReason = "cancel_sender_reason";

    



    $partnerImgURls = explode(",", $partnerResult->item_url_picture);

    $leftItem = "Partner Item";
    $rightItem = "Your Offer";

    // print_r($partnerResult);

  }

  if($selectResult->receiver_id == $userId) {
    $userCancel = "Your cancel reason: " . $selectResult->cancel_receiver_reason;
    $partnerUserCancel = "Partner cancel reason: " . $selectResult->cancel_sender_reason;
  } else {
    $userCancel = "Your cancel reason: " . $selectResult->cancel_sender_reason;
    $partnerUserCancel = "Partner cancel reason: " . $selectResult->cancel_receiver_reason;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meet up information</title>
  <link rel="icon" href="B.png">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- rsponsive table -->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" />

  <!-- leaflet cdn css -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

  <!-- leaflet routing machine css -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

  

  <link rel="stylesheet" href="css/general.css">

  <style>
    .img-size {
      width: 200px;
      height: 150px;
      object-fit: cover;
    }
  </style>
</head>
<body>

  <?php include 'layout/navbar.php'; ?>
  <?php
    include "verified-authentication.php";
  ?>
  
  <h3 class="text-center">Meet up information</h3>
  <div class="container shadow rounded pb-3 mb-3">
    <div class="row mt-3 p-3 mb-3">

      <a href="meet-up.php" class="float-start text-dark"><i class="bi bi-arrow-left"></i></a>
      <h4 class="text-center">Swap</h4>

      <div class="col-12 col-md-6 d-flex justify-content-between align-items-center px-5">
        <div>
          <div>
            <p class="text-center m-0"><?= $leftItem ?></p>
            <img src="item-photo/<?= $partnerImgURls[0] ?>" class="img-size border rounded" alt="">
          </div>
          <p class="text-center"><?= $partnerResult->item_title ?></p>
        </div>
        <div>
          <i class="bi bi-arrow-right text-dark h3"></i>
        </div>
      </div>

      <div class="col-12 col-md-6 d-flex justify-content-between align-items-center px-5">
        <div>
          <i class="bi bi-arrow-left text-dark h3"></i>
        </div>
        <div>
          <div>
            <p class="text-center m-0"><?= $rightItem ?></p>
            <img src="offer-item-photo/<?= $partnerResult->offer_url_picture ?>" class="img-size border rounded" alt="">
          </div>
          <p class="text-center"><?= $partnerResult->offer_title ?></p>
        </div>
      </div>

    </div>

    <?php
      if(!($selectResult->meet_up_status == 'completed' || $selectResult->meet_up_status == 'cancelled')) {
        ?>
        <div class="mb-3">
          <button class="btn btn-sm btn-light mapBtn">view map</button>
          <button class="btn btn-sm btn-light qrScanner <?= $selectResult->sender_id == $userId ? "": "d-none" ?>">Scan Qr</button>
          <button class="btn btn-sm btn-light qrCode <?= $selectResult->receiver_id == $userId ? "": "d-none" ?>">QR Code</button>
          <button class="btn btn-sm btn-light detailsBtn">Details</button>
        </div>
        <?php
      }
    
    ?>

    

    <div id="map" class="mb-3" style="width: 100%; height: 40vh;"></div>

    <div class="row mb-3 forQr">
      <div class="col-md-6">
        <div id="reader" style="width:100%;"></div>
      </div>
      <div class="col-md-6 d-flex flex-column align-items-start">
        <p class="text-muted">scan the qr code to finish transaction</p>
        <div id="result"></div>
      </div>
    </div>

    <div class="qrDisplay w-100 d-flex justify-content-center flex-column">
      <p class="text-center">QR Code</p>
      <div id="qrcode" class="mx-auto"></div>
    </div>

    <div class="details px-3 py-2 border rounded">
      <p class="text-sm">Details</p>
      <div>
        <p>Status: <?= $selectResult->meet_up_status ?></p>
        <?php
          if($selectResult->meet_up_status == "cancelled") {
            if(empty($selectResult->cancel_receiver_reason)) {
              
            } else {
              if($userId == $selectResult->receiver_id) {
                ?>
                  <p><?= $userCancel ?></p>
                <?php
              } else {
                ?>
                  <p><?= $partnerUserCancel ?></p>
                <?php
              }
            }

            if(empty($selectResult->cancel_sender_reason)) {
              
            } else {
              if($userId == $selectResult->sender_id) {
                ?>
                  <p><?= $userCancel ?></p>
                <?php
              } else {
                ?>
                  <p><?= $partnerUserCancel ?></p>
                <?php
              }
            }
          }
        ?>
        <p class="m-0">Barter Partner: <?= $partnerResult->fullname ?></p>
        <p>Barter Partner current location: <?= $partnerResult->address ?></p>
        <p class="m-0">Meet up place: <?= $selectResult->meet_up_place ?></p>
        <p class="m-0">Date and Time: <?= $selectResult->meet_up_date ?></p>
      </div>
      <div>
        <?php
          if(!($selectResult->meet_up_status == 'completed' || $selectResult->meet_up_status == 'cancelled')) {
            ?>
              <a id="cancelOfferBtn"  class="btn btn-sm btn-danger border" data-bs-toggle="modal" data-bs-target="#cancelMeetUpModal">Cancel</a>
            <?php
          }
        ?>
      </div>
    </div>
    <form id="completeTransactionForm" action="complete_transaction.php" method="post">
      <input type="hidden" name="qrCode" id="inputQrCode">
    </form>
    
  </div>

  <!-- Reject Modal -->
  <div class="modal fade" id="cancelMeetUpModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelModalLabel">Reason for Cancelation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="confirmMeetUpCancelForm" action="cancel_meet_up.php" method="post">
            <input type="hidden" name="meetUpId" value="<?= $selectResult->meet_up_id ?>">
            <input type="hidden" name="userReason" value="<?= $userReason ?>">
            <div class="mb-3">
              <label for="cancelReason" class="form-label">Reason</label>
              <textarea class="form-control" id="cancelReason" name="cancelReason" rows="3" required></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="confirmCancelMeetUpBtn" type="button" class="btn btn-danger">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  

<!-- resposinve data table -->
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="js/plugins/sweetalert2/swal.js"></script>

<!-- leaflet cdn -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- leaflet routing js -->
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<!-- QrScanner -->
<script src="html5-qrcode.min.js"></script>

<!-- QrCode -->
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>

<!-- update location -->
<?php
  if(isset($_SESSION['user_details'])) {
    ?>
      <script src="update-location.js"></script>
    <?php
  }
?>  

<script>

  $(document).ready(function() {

    $('.mapBtn').on('click', function() {
      $('#map').toggleClass("d-none");
      $('.forQr').addClass("d-none");
      $('.qrDisplay').addClass("d-none");
      $('.details').addClass("d-none");
    });

    $('.qrScanner').on('click', function() {
      $('.forQr').toggleClass("d-none");
      $('#map').addClass("d-none");
      $('.qrDisplay').addClass("d-none");
      $('.details').addClass("d-none");

    });

    $('.qrCode').on('click', function() {
      $('.qrDisplay').toggleClass("d-none");
      $('.forQr').addClass("d-none");
      $('#map').addClass("d-none");
      $('.details').addClass("d-none");
    });

    $('.detailsBtn').on('click', function() {
      $('.details').toggleClass("d-none");
      $('.qrDisplay').addClass("d-none");
      $('.forQr').addClass("d-none");
      $('#map').addClass("d-none");
    });

    $('#map').addClass("d-none");
    $('.forQr').addClass("d-none");
    $('.qrDisplay').addClass("d-none");


    /* QR Code */
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: "<?= $selectResult->qrCode ?>",
        width: 128,
        height: 128
    });


    $('#confirmCancelMeetUpBtn').on('click', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to cancel this meet-up?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#confirmMeetUpCancelForm').submit();
        }
      });
    });


  });

  // leaflet map with osm titlelayer
  var map = L.map('map').setView([14.4238702, 120.9199225], 11);
  var tileLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', { attribution: "OSM" }).addTo(map);

  var myLng = <?= json_encode($_SESSION["user_details"]["lng"]) ?>;
  var myLat = <?= json_encode($_SESSION["user_details"]["lat"]) ?>;

  var meetUpLng = <?= json_encode($selectResult->meet_up_lng) ?>;
  var meetUpLat = <?= json_encode($selectResult->meet_up_lat) ?>;
  var meetUpPlace = <?= json_encode($selectResult->meet_up_place) ?>;

  var partnerLng = <?= json_encode($partnerResult->lng) ?>;
  var partnerLat = <?= json_encode($partnerResult->lat) ?>;

  console.log(myLng);
  console.log(myLat);

  console.log(meetUpLng);
  console.log(meetUpLat);

  console.log(partnerLng);
  console.log(partnerLat);

  // markers with labels (tooltips), fixed and not draggable
  var startMarker = L.marker([myLat, myLng], { draggable: false }).addTo(map).bindTooltip('You', {permanent: true, className: 'start-label'});
  var middleMarker = L.marker([meetUpLat, meetUpLng], { draggable: false }).addTo(map).bindTooltip(meetUpPlace, {permanent: true, className: 'middle-label'});
  var endMarker = L.marker([partnerLat, partnerLng], { draggable: false }).addTo(map).bindTooltip('Barter Partner', {permanent: true, className: 'end-label'});

  // Routing control with fixed waypoints
  L.Routing.control({
    waypoints: [
      L.latLng(myLat, myLng), // start
      L.latLng(meetUpLat, meetUpLng), // middle
      L.latLng(partnerLat, partnerLng)  // end
    ],
    draggableWaypoints: false, // Disable dragging of waypoints
    addWaypoints: false, // Disable adding additional waypoints
    routeWhileDragging: true, // Allow route updating while dragging waypoints
    show: false // Hide instructions panel
  }).addTo(map);
</script>

<script type="text/javascript">
  $(document).ready(function() {
    function onScanSuccess(qrCodeMessage) {
      $('#result').html('<span class="result">' + qrCodeMessage + '</span>');

      if ($('#inputQrCode').val()) {
        // If the input already has a value, do not proceed to submit
        return;
      }

      $('#inputQrCode').val(qrCodeMessage);

      // Disable the submit button to prevent multiple submissions
      $('#completeTransactionForm').submit(function(){
        $(this).find(':submit').attr('disabled', 'disabled');
      });

      $('#completeTransactionForm').submit();
    }

    function onScanError(errorMessage) {
      // handle scan error
    }

    var html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess, onScanError);
  });
</script>
</body>
</html>