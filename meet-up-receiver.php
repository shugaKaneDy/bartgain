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
    $offerPartner = selectQueryFetch(
                                  $pdo,
                                  "SELECT * FROM items
                                  INNER JOIN offers ON items.item_id = offers.offer_item_id
                                  INNER JOIN users ON offers.offer_user_id = users.user_id
                                  INNER JOIN meet_ups ON offers.offer_id = meet_ups.meet_up_offer_id
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

    $itemUrlFiles = explode(',' , $offerPartner['item_url_file']);
    $itemFirstFile = $itemUrlFiles[0];
    $itemExt = explode('.', $itemFirstFile);
    $itemExt = end($itemExt);
  } else {

    header('location: message-offers.php');
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
  <title>Meet Up | Receiver</title>

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

      <div class="d-flex bg-gradient rounded mb-2 p-2 justify-content-between justify-content-md-around gap-2 gap-md-5 ">
        <div class="card text-center overflow-hidden border-2 border-success shadow-sm" style="width: 18rem;">
          <!-- <img src="item-uploads/2024-09-28-15297-kawali.jpg" class="card-img-top" alt="..."> -->
          <?php if(in_array($itemExt, $allowedImages)): ?>
            <img src="item-uploads/<?= $itemFirstFile ?>" class="img-item-size w-100 align-self-center m">
          <?php else: ?>
            <video src="item-uploads/<?= $itemFirstFile ?>" class="img-item-size w-100 align-self-center m"></video>
          <?php endif ?>
          <div class="card-body">
            <p class="small-text fw-bold text-success m-0">Your Item</p>
            <div class="row">
              <div class="col-12 text-truncate text-secondary">
              <?= $offerPartner['item_title'] ?>
              </div>
            </div>
            <!-- <p class="text-secondary m-0"></p> -->
            <button value="<?= $offerPartner['item_random_id'] ?>" class="forViewItemModal btn btn-sm btn-outline-success bg-green"
            data-bs-toggle="modal" data-bs-target="#itemModalView"
            >
            View Item
            </button>
          </div>
        </div>

        <div class="d-flex align-items-center">
          <p class="m-0 px-2 py-1 px-md-4 py-md-3 bg-warning bg-gradient rounded text-white shadow-sm"><i class="bi bi-arrow-left-right"></i></p>
        </div>

        <div class="card text-center overflow-hidden border-2 border-secondary shadow-sm" style="width: 18rem;">
          <!-- <img src="item-uploads/2024-09-28-15297-kawali.jpg" class="card-img-top" alt="..."> -->
          <?php if(in_array($offerExt, $allowedImages)): ?>
            <img src="offer-uploads/<?= $offerFirstFile ?>" class="img-item-size w-100 align-self-center m">
          <?php else: ?>
            <video src="offer-uploads/<?= $offerFirstFile ?>" class="img-item-size w-100 align-self-center m"></video>
          <?php endif ?>
          <div class="card-body">
            <p class="small-text fw-bold text-secondary m-0 text-secondary">Partner Offer</p>
            <div class="row">
              <div class="col-12 text-truncate text-secondary">
              <?= $offerPartner['offer_title'] ?>
              </div>
            </div>
            <button value="<?= $offerPartner['offer_random_id'] ?>" class="forViewOfferModal btn btn-sm btn-outline-secondary"
            data-bs-toggle="modal" data-bs-target="#offerModalView"
            >
              View Offer
            </button>
          </div>
        </div>
      </div>
      
      <?php if($offerPartner['meet_up_status'] == 'cancelled'): ?>
        <div class="row p-3 border-top mb-2 justify-content-center">
          <div class="col-12 col-md-8">
            <p class="m-0">Status:
                <span class="fw-bold text-danger"><?= $offerPartner['meet_up_status'] ?></span>
            </p>
            <div class="w-100 border border-danger border-2 p-2 mb-3 rounded shadow">
              <div class="d-flex mb-3 gap-2 align-items-center">
                <img src="profile-uploads/default.jpg" alt="" class="img-profile rounded">
                <div>
                  <p class="m-0 fw-bold"><?= $offerPartner['fullname'] ?></p>
                  <p class="m-0 fw-bold">5 <span class="text-warning"><i class="bi bi-star-fill"></i></span></p>
                </div>
              </div>
              <p class="m-0">Cancel Reason: </p>
                <p class="mb-2 ps-3 pe-2 text-secondary"><?= $offerPartner['meet_up_cancel_reason'] ?></p>
            </div>
          </div>
        </div>
      <?php elseif($offerPartner['meet_up_status'] == 'completed'): ?>
        <div class="row p-3 border-top mb-2 justify-content-center">
          <div class="col-12 col-md-8">
            <p class="m-0">Status:
                <span class="fw-bold text-success"><?= $offerPartner['meet_up_status'] ?></span>
            </p>
            <div class="w-100 border border-success border-2 p-2 mb-3 rounded shadow">
              <div class="d-flex mb-3 gap-2 align-items-center">
                <img src="profile-uploads/default.jpg" alt="" class="img-profile rounded">
                <div>
                  <p class="m-0 fw-bold"><?= $offerPartner['fullname'] ?></p>
                  <p class="m-0 fw-bold">5 <span class="text-warning"><i class="bi bi-star-fill"></i></span></p>
                </div>
              </div>
              <p class="m-0">Meet up location: </p>
                <p class="mb-2 ps-3 pe-2 text-secondary"><?= $offerPartner['meet_up_loc'] ?></p>
              <p class="m-0">Date and Time Met: </p>
                <p class="mb-2 ps-3 pe-2 text-secondary"><?= date("M d, Y h:i A", strtotime($offerPartner['meet_up_date'])) ?></p>
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
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">QR Code</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Details</button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active pt-3" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div id="map" class="w-100"></div>
              </div>
              <div class="tab-pane fade pt-3" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <div class="qrDisplay w-100 d-flex justify-content-center flex-column">
                  <p class="text-center">QR Code</p>
                  <div id="qrcode" class="mx-auto"></div>
                </div>
              </div>
              <div class="tab-pane fade pt-3" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                <div class="row justify-content-center">
                  <div class="col-12 col-md-8">
                    <p class="m-0">Status:
                      <?php if($offerPartner['meet_up_status'] == 'active'): ?>
                        <span class="fw-bold text-success"><?= $offerPartner['meet_up_status'] ?></span>
                      <?php else: ?>
                      <?php endif ?>
                    </p>
                    <div class="w-100 border border-success border-2 p-2 mb-3 rounded shadow">
                      <div class="d-flex mb-3 gap-2 align-items-center">
                        <img src="profile-uploads/default.jpg" alt="" class="img-profile rounded">
                        <div>
                          <p class="m-0 fw-bold"><?= $offerPartner['fullname'] ?></p>
                          <p class="m-0 fw-bold">5 <span class="text-warning"><i class="bi bi-star-fill"></i></span></p>
                        </div>
                      </div>
                      <p class="m-0">Meet up location: </p>
                      <?php if(empty($offerPartner['offer_date_time_meet'])): ?>
                      <?php else: ?>
                        <p class="mb-2 ps-3 pe-2 text-secondary"><?= $offerPartner['offer_meet_up_place'] ?></p>
                      <?php endif; ?>
                      <p class="m-0">Meet up date and time: </p>
                      <?php if(empty($offerPartner['offer_date_time_meet'])): ?>
                      <?php else: ?>
                        <p class="mb-2 ps-3 pe-2 text-secondary"><?= date("M d, Y h:i A", strtotime($offerPartner['offer_date_time_meet'])) ?></p>
                      <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-end">
                      <button class="btn btn-danger"
                      data-bs-toggle="modal" data-bs-target="#cancelMeetupModal"
                      >
                        Cancel
                      </button>
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

  <!-- Cancel Meetup Modal -->
  <div class="modal fade" id="cancelMeetupModal" tabindex="-1" aria-labelledby="cancelMeetupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelMeetupModalLabel">Cancel Meet-up</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="cancelMeetupForm">
          <input type="hidden" name="offer_id" value="<?= $offerPartner['offer_random_id'] ?>">
          <div class="modal-body">
            <div class="mb-3">
              <label for="cancelReason" class="form-label">Reason for Cancellation:</label>
              <textarea class="form-control my-input-danger" id="cancelReason" name="cancelReason" rows="3" placeholder="Enter your reason..." required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" id="cancelMeetupBtn">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>



  <?php
    require_once 'layouts/bottom-link.php';
  ?>

  <!-- Update Location -->
  <script>
    $(document).ready(function() {
      function updateLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) { // Pass the position parameter here
                var lat = position.coords.latitude;  // Get latitude
                var lon = position.coords.longitude; // Get longitude

                $.ajax({
                  method: "GET",
                  url: `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}`,
                  success: function(res) {
                    var location = res.city + ', ' + res.localityInfo.administrative[2].name;
                    

                    $.ajax({

                      method: 'POST',
                      url: "includes/ajax/update-location.inc.php",
                      data: {
                        lat : lat,
                        lon : lon,
                        location : location
                      },
                      dataType: "JSON"
                    }).done(function(data) {
                      if(data.status == 'error') {
                        Swal.fire({
                            icon: data.status,
                            title: data.title,
                            showConfirmButton: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "signin.php";
                            }
                        });
                      }
                    })
                  }
                });
            }, function(error) {
              errorLocation(error);
            });
        } else {
            Swal.fire({
              title: "Geolocation Not Supported",
              text: "Your browser does not support geolocation. Please use a different browser.",
              icon: "error",
            });
        }
      }

      updateLocation();
      setInterval(updateLocation, 5000); 

    })
  </script>



  <script>
    $(document).ready(function() {
      
      var map;
      var routingControl;
      var partnerRoutingControl;

      function initializeMap() {
          // Initialize the map if it hasn't been created yet
          if (!map) {
              map = L.map('map', {
                  zoomControl: true,
              }).setView(
                  [<?= json_encode($_SESSION["user_details"]["lat"]) ?>, <?= json_encode($_SESSION["user_details"]["lng"]) ?>], 
                  13 // Initial zoom level
              );
              var tileLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', { attribution: "OSM" }).addTo(map);
          }
      }

      function updateMeetupLocation() {
          var myLng = <?= json_encode($_SESSION["user_details"]["lng"]) ?>;
          var myLat = <?= json_encode($_SESSION["user_details"]["lat"]) ?>;

          var meetUpLng = <?= json_encode($offerPartner['offer_lng']) ?>;
          var meetUpLat = <?= json_encode($offerPartner['offer_lat']) ?>;
          var meetUpPlace = <?= json_encode($offerPartner['offer_meet_up_place']) ?>;

          $.ajax({
              method: 'POST',
              url: "includes/ajax/get-partner-location.php",
              data: {
                  partnerId: <?= json_encode($offerPartner['user_random_id']) ?>
              },
              dataType: "JSON",
          }).done(function (result) {
              var partnerLng = result.lng;
              var partnerLat = result.lat;
              myLng = result.myLng;
              myLat = result.myLat;


              // Clear only the markers and routes, not the base map
              map.eachLayer(function (layer) {
                  if (layer instanceof L.Marker || layer instanceof L.Routing.Control) {
                      map.removeLayer(layer);
                  }
              });

              // Clear previous routing controls if they exist
              if (routingControl) {
                  map.removeControl(routingControl);
              }
              if (partnerRoutingControl) {
                  map.removeControl(partnerRoutingControl);
              }

              // Add markers with tooltips
              var startMarker = L.marker([myLat, myLng], { draggable: false }).addTo(map).bindTooltip('You', { permanent: true, className: 'start-label' });
              var middleMarker = L.marker([meetUpLat, meetUpLng], { draggable: false }).addTo(map).bindTooltip(meetUpPlace, { permanent: true, className: 'middle-label' });
              var partnerMarker = L.marker([partnerLat, partnerLng], { draggable: false }).addTo(map).bindTooltip('Partner', { permanent: true, className: 'partner-label' });

              // Add new routing controls without affecting the map's view or zoom
              routingControl = L.Routing.control({
                  waypoints: [
                      L.latLng(myLat, myLng),    // Start (your location)
                      L.latLng(meetUpLat, meetUpLng)    // Meet-up location
                  ],
                  draggableWaypoints: false,
                  addWaypoints: false,
                  routeWhileDragging: true,
                  show: false,
                  createMarker: function () { return null; },
                  lineOptions: {
                      styles: [{ color: '#168855', opacity: 1, weight: 3 }]
                  },
                  fitSelectedRoutes: false // Prevents the map from auto-zooming to fit the route
              }).addTo(map);

              partnerRoutingControl = L.Routing.control({
                  waypoints: [
                      L.latLng(partnerLat, partnerLng), // Partner's location
                      L.latLng(meetUpLat, meetUpLng)    // Meet-up location
                  ],
                  draggableWaypoints: false,
                  addWaypoints: false,
                  routeWhileDragging: true,
                  show: false,
                  createMarker: function () { return null; },
                  lineOptions: {
                      styles: [{ color: '#fec107', opacity: 0.8, weight: 3 }]
                  },
                  fitSelectedRoutes: false // Prevents the map from auto-zooming to fit the route
              }).addTo(map);
          });
      }

      <?php if($offerPartner['meet_up_status'] == 'cancelled'): ?>
      <?php elseif($offerPartner['meet_up_status'] == 'completed'): ?>
      <?php else: ?>
          initializeMap();
          updateMeetupLocation(); // Initial update
          setInterval(updateMeetupLocation, 5000); // Update every 5 seconds

          /* QR Code */
          var qrcode = new QRCode(document.getElementById("qrcode"), {
              text: "<?= json_encode($offerPartner['meet_up_qr_code']) ?>",
              width: 128,
              height: 128
          });
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

  <!-- CANCEL MEETUP -->
  <script>
    $(document).ready(function() {
      $('#cancelMeetupBtn').on('click', function(e) {
        e.preventDefault();

        let formData = $('#cancelMeetupForm').serializeArray();
        
        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to cancel this meetup?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "includes/ajax/cancel-meetup.inc.php",
              data: formData,
              dataType: "JSON",
            }).done(function (res) {

              if(res.status == 'error') {
                Swal.fire({
                  icon: res.status,
                  title: res.title,
                  showConfirmButton: true
                });
              }

              if(res.status == 'success') {
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

            })
          }
        })

      });
    })
  </script>

</body>
</html>