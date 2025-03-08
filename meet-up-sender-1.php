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

  if(isset($_GET["offer_id"])) {
    $getOfferId = $_GET["offer_id"];
    $itemPartner = selectQueryFetch(
                                  $pdo,
                                  "SELECT * FROM items
                                  INNER JOIN offers ON items.item_id = offers.offer_item_id
                                  INNER JOIN users ON items.item_user_id = users.user_id
                                  INNER JOIN meet_ups ON offers.offer_id = meet_ups.meet_up_offer_id
                                  WHERE offers.offer_user_id = :offerUserId
                                  AND offers.offer_random_id = :offerRandomId",
                                  [
                                    ":offerUserId" => $_SESSION['user_details']['user_id'],
                                    ":offerRandomId" => $getOfferId,
                                  ]
                                );

    $offerUrlFiles = explode(',' , $itemPartner['offer_url_file']);
    $offerFirstFile = $offerUrlFiles[0];
    $offerExt = explode('.', $offerFirstFile);
    $offerExt = end($offerExt);

    $itemUrlFiles = explode(',' , $itemPartner['item_url_file']);
    $itemFirstFile = $itemUrlFiles[0];
    $itemExt = explode('.', $itemFirstFile);
    $itemExt = end($itemExt);
  } else {

    header('location: message-proposals.php');
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
  <title>Meet Up | Sender</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/meet-up-receiver.css">


</head>
<body class="bg-light">

  <!-- nav -->
  <?php
    include "layouts/nav.php"
  ?>


  <!-- Add item -->
  <?php
    include "layouts/add-div.php"
  ?>

  <!-- Offcanvas -->
  <?php
    include "layouts/aside.php"
  ?>

  <!-- pre load -->
  <?php
    include "layouts/preload.php"
  ?>

  <main>
    <div class="container-xl">
      <header>
        <h3 class="bg-success rounded py-3 px-4 text-center text-white shadow-sm">Meet up</h3>
      </header>
      <div class="d-flex rounded mb-2 p-2 justify-content-center justify-content-md-around gap-2 gap-md-5 ">
        <div class="card text-center overflow-hidden border-2 border-secondary shadow-sm" style="width: 18rem;">
          <!-- <img src="item-uploads/2024-09-28-15297-kawali.jpg" class="card-img-top" alt="..."> -->
          <?php if(in_array($itemExt, $allowedImages)): ?>
            <img src="item-uploads/<?= $itemFirstFile ?>" class="img-item-size w-100 align-self-center m">
          <?php else: ?>
            <video src="item-uploads/<?= $itemFirstFile ?>" class="img-item-size w-100 align-self-center m"></video>
          <?php endif ?>
          <div class="card-body">
            <p class="small-text fw-bold text-secondary m-0">Partner Item</p>
            <div class="row">
              <div class="col-12 text-truncate text-secondary">
              <?= $itemPartner['item_title'] ?>
              </div>
            </div>
            <button value="<?= $itemPartner['item_random_id'] ?>" class="forViewItemModal btn btn-sm btn-outline-secondary"
            data-bs-toggle="modal" data-bs-target="#itemModalView"
            >
            View Item
            </button>
          </div>
        </div>

        <div class="d-flex align-items-center">
          <p class="m-0 px-2 py-1 px-md-4 py-md-3 bg-warning bg-gradient rounded text-white shadow-sm"><i class="bi bi-arrow-left-right"></i></p>
        </div>

        <div class="card text-center overflow-hidden border-2 border-success shadow-sm" style="width: 18rem;">
          <!-- <img src="item-uploads/2024-09-28-15297-kawali.jpg" class="card-img-top" alt="..."> -->
          <?php if(in_array($offerExt, $allowedImages)): ?>
            <img src="offer-uploads/<?= $offerFirstFile ?>" class="img-item-size w-100 align-self-center m">
          <?php else: ?>
            <video src="offer-uploads/<?= $offerFirstFile ?>" class="img-item-size w-100 align-self-center m"></video>
          <?php endif ?>
          <div class="card-body">
            <p class="small-text fw-bold text-secondary m-0 text-success">Your Offer</p>
            <div class="row">
              <div class="col-12 text-truncate text-secondary">
                <?= $itemPartner['offer_title'] ?>
              </div>
            </div>
            <button value="<?= $itemPartner['offer_random_id'] ?>" class="forViewOfferModal btn btn-sm btn-outline-success bg-green"
            data-bs-toggle="modal" data-bs-target="#offerModalView"
            >
              View Offer
            </button>
          </div>
        </div>
      </div>

      <?php if($itemPartner['meet_up_status'] == 'completed'): ?>
        <div class="row p-3 border-top mb-2 justify-content-center">
          <div class="col-12 col-md-8">
            <p class="m-0">Status:
                <span class="fw-bold text-success"><?= $itemPartner['meet_up_status'] ?></span>
            </p>
            <div class="w-100 border border-success border-2 p-2 mb-3 rounded shadow">
              <div class="d-flex mb-3 gap-2 align-items-center">
                <img src="profile-uploads/default.jpg" alt="" class="img-profile rounded">
                <div>
                  <p class="m-0 fw-bold"><?= $itemPartner['fullname'] ?></p>
                  <p class="m-0 fw-bold">5 <span class="text-warning"><i class="bi bi-star-fill"></i></span></p>
                </div>
              </div>
              <p class="m-0">Meet up location: </p>
                <p class="mb-2 ps-3 pe-2 text-secondary"><?= $itemPartner['meet_up_loc'] ?></p>
              <p class="m-0">Date and Time Met: </p>
                <p class="mb-2 ps-3 pe-2 text-secondary"><?= date("M d, Y h:i A", strtotime($itemPartner['meet_up_date'])) ?></p>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="row p-3 border-top mb-2 justify-content-center">
          <div class="col-12 mb-3">
            <ul class="nav nav-pills" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Map</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Scan QR</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Details</button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade pt-3 show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div id="map" class="w-100"></div>
              </div>
              <div class="tab-pane fade pt-3" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <div class="row mb-3 forQr justify-content-center">
                  <div class="col-12 col-md-6">
                    <div id="reader" style="width:100%;"></div>
                    <p class="text-muted text-center mt-3">scan the qr code to finish transaction</p>
                    <div id="result" class="text-center"></div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade pt-3" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                <div class="row justify-content-center">
                  <div class="col-12 col-md-8">
                    <p class="m-0">Status:
                      <?php if($itemPartner['meet_up_status'] == 'active'): ?>
                        <span class="fw-bold text-success"><?= $itemPartner['meet_up_status'] ?></span>
                      <?php else: ?>
                      <?php endif ?>
                    </p>
                    <div class="w-100 border border-success border-2 p-2 mb-3 rounded shadow">
                      <div class="d-flex mb-3 gap-2 align-items-center">
                        <img src="profile-uploads/default.jpg" alt="" class="img-profile rounded">
                        <div>
                          <p class="m-0 fw-bold"><?= $itemPartner['fullname'] ?></p>
                          <p class="m-0 fw-bold">5 <span class="text-warning"><i class="bi bi-star-fill"></i></span></p>
                        </div>
                      </div>
                      <p class="m-0">Meet up location: </p>
                      <?php if(empty($itemPartner['offer_date_time_meet'])): ?>
                      <?php else: ?>
                        <p class="mb-2 ps-3 pe-2 text-secondary"><?= $itemPartner['offer_meet_up_place'] ?></p>
                      <?php endif; ?>
                      <p class="m-0">Meet up date and time: </p>
                      <?php if(empty($itemPartner['offer_date_time_meet'])): ?>
                      <?php else: ?>
                        <p class="mb-2 ps-3 pe-2 text-secondary"><?= date("M d, Y h:i A", strtotime($itemPartner['offer_date_time_meet'])) ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif ?>


    </div>
  </main>

  <form id="qrCodeForm">
    <input type="hidden" name="qrCode" id="inputQrCode">
    <input type="hidden" name="meetUpLoc" id="meetUpLoc">
    <input type="hidden" name="meetUpLng" id="meetUpLng">
    <input type="hidden" name="meetUpLat" id="meetUpLat">
  </form>

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

  <!-- spinner -->
  <?php
    include 'layouts/spinner-overlay.php';
  ?>

  <?php
    require_once 'layouts/bottom-link.php';
  ?>

  <script>

  </script>



  <script>
    $(document).ready(function() {
      
      var map;
      var routingControl;
      var partnerRoutingControl;

      function meetupLocationDirection() {
        // leaflet map with osm tile layer
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

        var meetUpLng = <?= json_encode($itemPartner['offer_lng']) ?>;
        var meetUpLat = <?= json_encode($itemPartner['offer_lat']) ?>;
        var meetUpPlace = <?= json_encode($itemPartner['offer_meet_up_place']) ?>;

        var partnerLng = <?= json_encode($itemPartner['lng']) ?>;
        var partnerLat = <?= json_encode($itemPartner['lat']) ?>;

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

        if (partnerRoutingControl) {
            map.removeControl(routingControl); // Remove previous route from the map
        }

        // Markers with labels (tooltips), fixed and not draggable
        var startMarker = L.marker([myLat, myLng], { draggable: false }).addTo(map).bindTooltip('You', {permanent: true, className: 'start-label'});
        
        var middleMarker = L.marker([meetUpLat, meetUpLng], { draggable: false }).addTo(map).bindTooltip(meetUpPlace, {permanent: true, className: 'middle-label'});
        
        var partnerMarker = L.marker([partnerLat, partnerLng], { draggable: false }).addTo(map).bindTooltip('Partner', {permanent: true, className: 'partner-label'});

        // Add a new routing control
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(myLat, myLng),    // Start (your location)
                L.latLng(meetUpLat, meetUpLng)    // Meet-up location
            ],
            draggableWaypoints: false, // Disable dragging of waypoints
            addWaypoints: false, // Disable adding additional waypoints
            routeWhileDragging: true, // Allow route updating while dragging waypoints
            show: false, // Show instructions panel
            createMarker: function() { return null; }, // Disable default markers, as we are adding custom markers
            lineOptions: {
                styles: [{ color: '#168855', opacity: 1, weight: 3 }]  // Red color for your route
            }
        }).addTo(map);

        partnerRoutingControl = L.Routing.control({
            waypoints: [
                L.latLng(partnerLat, partnerLng), // Partner's location
                L.latLng(meetUpLat, meetUpLng)    // Meet-up location
            ],
            draggableWaypoints: false, // Disable dragging of waypoints
            addWaypoints: false, // Disable adding additional waypoints
            routeWhileDragging: true, // Allow route updating while dragging waypoints
            show: false, // Show instructions panel
            createMarker: function() { return null; }, // Disable default markers, as we are adding custom markers
            lineOptions: {
                styles: [{ color: '#fec107', opacity: 0.8, weight: 3 }]  // Blue color for partner's route
            }
        }).addTo(map);
      }

      <?php if($itemPartner['meet_up_status'] == 'completed'): ?>
      <?php else: ?>
        meetupLocationDirection();
      <?php endif ?>



      /* QR Scanner */

      function onScanSuccess(qrCodeMessage) {
        $('#result').html('<span class="result">' + qrCodeMessage + '</span>');

        if ($('#inputQrCode').val()) {
          // If the input already has a value, do not proceed to submit
          return;
        }

        $('#inputQrCode').val(qrCodeMessage);

        var lat;
        var lon;
        var meetUpLoc;

        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            lat = position.coords.latitude;  // Get latitude
            lon = position.coords.longitude; // Get longitude

            $.ajax({
            method: "GET",
            url: `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`,
            success: function(res) {
                console.log(res);
                meetUpLoc = res.display_name;
                console.log(meetUpLoc);
                console.log(lat);
                console.log(lon);
                $('#meetUpLoc').val(meetUpLoc);
                $('#meetUpLng').val(lon);
                $('#meetUpLat').val(lat);

        

                $('.spinner-overlay').removeClass('d-none');

                let formData = $('#qrCodeForm').serializeArray();

                $.ajax({
                  method: 'POST',
                  url: "includes/ajax/update-qr.inc.php?function=addItem",
                  data: formData,
                  dataType: "JSON",
                }).done(function (res) {

                  if(res.status == 'success') {
                    // $('.spinner-overlay').addClass('d-none');

                    Swal.fire({
                      icon: res.status,
                      title: res.title,
                      showConfirmButton: true
                    }).then(result => {
                      if (result.isConfirmed) {
                        location.reload();
                      }
                    });
                  }
                });
              }
            });
          })
        }

      }

      function onScanError(errorMessage) {
        // handle scan error
      }

      <?php if($itemPartner['meet_up_status'] == 'completed'): ?>
      <?php else: ?>
        var html5QrcodeScanner = new Html5QrcodeScanner(
          "reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess, onScanError);        
      <?php endif ?>



      const contentViewItem = $('.content-view-item');

      $(document).on('click', '.forViewItemModal', function(e) {

        e.preventDefault();
        let itemValue = $(this).attr('value');

        console.log(itemValue);

        $.ajax({
          method: 'GET',
          url: `includes/ajax/view-item.inc.php?item_random_id=${itemValue}`,
        }).done(res => {
          contentViewItem.html(res);
        })
      });

      // $('#reader button').addClass('btn btn-success');


      const contentViewOffer = $('.content-view-offer');

      $(document).on('click', '.forViewOfferModal', function(e) {

        e.preventDefault();
        let offerValue = $(this).attr('value');

        console.log(offerValue);

        $.ajax({
          method: 'GET',
          url: `includes/ajax/view-offer.inc.php?offer_random_id=${offerValue}`,
        }).done(res => {
          contentViewOffer.html(res);
        })
      });

      
    })
  </script>

</body>
</html>