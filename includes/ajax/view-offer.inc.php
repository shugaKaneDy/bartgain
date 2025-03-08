<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();

  $offerRandomId = $_GET["offer_random_id"];
  

  $currentTime  = date("Y-m-d H:i:s");
  $offerViewModal = selectQueryFetch(
    $pdo,
    "SELECT offers.*, users.*, 
        (6371 * ACOS(COS(RADIANS(:lat)) * COS(RADIANS(users.lat)) * COS(RADIANS(users.lng) - RADIANS(:lng)) + SIN(RADIANS(:lat)) * SIN(RADIANS(users.lat)))) AS distance
    FROM offers 
    INNER JOIN users ON offers.offer_user_id = users.user_id 
    WHERE offers.offer_random_id = :offerRandomId",
    [
        ':lat' => $_SESSION["user_details"]["lat"],
        ':lng' => $_SESSION["user_details"]["lng"],
        ':offerRandomId' => $offerRandomId
    ]
  );

  $distanceKm = (int)$offerViewModal["distance"];

  $totalRating = 0;
  if($offerViewModal['user_rate_count'] == 0) {
    $totalRating = 0;
  } else {
    $totalRating = $offerViewModal['user_rating'] / $offerViewModal['user_rate_count'];
  }
  $totalRating = round($totalRating, 1);


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


  $urlFiles = explode(',' , $offerViewModal['offer_url_file']);
  // $firstFile = $UrlFiles[0];
  // $ext = explode('.', $firstFile);
  // $ext = end($ext);
  // $distanceKm = (int)$boostedItem["distance"];

  ?>
    <div class="row d-block d-md-flex align-items-center justify-content-center h-100">
      <div class="col-12 col-md-8 p-0">
        <!-- Carousel -->
        <div id="carouselExampleIndicators" class="carousel slide">
          <div class="carousel-indicators">
          <?php
            $total = count($urlFiles);
            for($i = 0; $i < $total; $i++) {
              ?>
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?=$i?>"
                  <?= $i == 0 ? 'class="active" aria-current="true"':"" ?>
                    aria-label="Slide <?= $i + 1 ?>"></button>
                    <?php
            }
            ?>
          </div>
          <div class="carousel-inner">
            <?php
              $total = count($urlFiles);
              for($i = 0; $i < $total; $i++) {
                $ext = explode('.', $urlFiles[$i]);
                $ext = end($ext);
                ?>
                  <div class="carousel-item <?= $i == 0 ? 'active' : '' ?> bg-danger">
                    <div class="my-img-container-height bg-dark-subtle d-flex justify-content-center align-items-center">
                      <?= in_array($ext, $allowedImages) ? '<img src="offer-uploads/'.$urlFiles[$i].'" >' : ' <video src="offer-uploads/'.$urlFiles[$i].'" autoplay muted loop></video>'; ?>
                    </div>
                  </div>
                <?php
              }
              ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
      <div class="col-12 col-md-4 content-container py-2 bg-white">

        <?php if($offerViewModal['user_id'] == $_SESSION['user_details']['user_id']): ?>
        <?php else: ?>
          <div class="d-flex justify-content-end">
            <button class="btn btn-light rounded-circle btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-flag">
  
              </i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="report-offer.php?offerId=<?= $offerViewModal['offer_random_id'] ?>">Report Offer</a></li>
              <li><a class="dropdown-item" href="report-user.php?userId=<?= $offerViewModal['user_random_id'] ?>">Report User</a></li>
            </ul>
          </div>
        <?php endif ?>
        <div class="mb-3 d-flex gap-2">
          <img src="profile-uploads/<?= empty($offerViewModal['profile_picture']) ? "default.jpg": $offerViewModal['profile_picture']; ?>" class="rounded-circle border-0 my-profile" style="width: 50px; height: 50px">
          <div>
            <a href="" class="link link-dark text-decoration-none fw-bold m-0"><?= $offerViewModal['fullname'] ?></a>
            <p class="m-0 fw-bold"><?= $totalRating ?> <span class="text-warning"><i class="bi bi-star-fill"></i></span></p>
          </div>
        </div>
        <div class="mb-3">
          <p class="fs-4 fw-bold m-0"><?= $offerViewModal['offer_title'] ?></p>
          <div class="d-flex justify-content-between align-items-center">
            <?= $distanceKm < 5 ? '<p class="badge text-bg-warning text-white m-0">Nearby</p>' : "";?>
          </div>
          <p class="fs-5 text-warning m-0">Est: <b><?= number_format($offerViewModal['offer_est_val']) ?></b></p>
          <div class="d-flex justify-content-between mb-3">
            <p class="m-0 text-secondary"><?= $offerViewModal['current_location'] ?></p>
            <p class="m-0 text-dark"><?= (int)$offerViewModal['distance'] ?>km</p>
          </div>
          <p class="m-0 text-dark">Status:
            <?php if($offerViewModal['offer_status'] == 'rejected'): ?>
              <span class="text-danger fw-bold"><?= $offerViewModal['offer_status'] ?></span>
            <?php elseif($offerViewModal['offer_status'] == 'accepted'): ?>
              <span class="text-success fw-bold"><?= $offerViewModal['offer_status'] ?></span>
            <?php else: ?>
              <span class="text-secondary fw-bold"><?= $offerViewModal['offer_status'] ?></span>
            <?php endif ?>
          </p>
          <?php if(empty($offerViewModal['offer_cancelled_reject_reason'])): ?>
            <div class="w-100 border border-success border-2 p-2 mb-3 rounded shadow">
              <p class="fw-bold text-center">Plan</p>
              <p class="m-0">Meet up location: </p>
              <?php if(empty($offerViewModal['offer_date_time_meet'])): ?>
              <?php else: ?>
                <p class="mb-2 ps-3 pe-2 text-secondary"><?= $offerViewModal['offer_meet_up_place'] ?></p>
              <?php endif; ?>
              <p class="m-0">Meet up date and time: </p>
              <?php if(empty($offerViewModal['offer_date_time_meet'])): ?>
              <?php else: ?>
                <p class="mb-2 ps-3 pe-2 text-secondary"><?= date("M d, Y h:i A", strtotime($offerViewModal['offer_date_time_meet'])) ?></p>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="w-100 border border-danger bg-danger-subtle border-2 p-2 mb-3 rounded shadow">
              <p class="fw-bold text-danger text-center">Offer Rejected</p>
              <p class="m-0">Reject Reason:</p>
              <p class="text-danger m-0 ps-3">
                <?= $offerViewModal['offer_cancelled_reject_reason'] ?>
              </p>
            </div>
          <?php endif; ?>
          <p class="fw-bold">Details</p>
          <div class="mb-3">
            <p class="m-0">Reference ID: <span class="text-secondary">#<?= $offerViewModal['offer_random_id'] ?></span></p>
            <p class="m-0">Condition: <span class="text-secondary"><?= $offerViewModal['offer_condition'] ?></span></p>
            <p class="m-0">Category:  <span class="text-secondary"><?= $offerViewModal['offer_category'] ?></span></p>
            <p class="m-0">Offered:  <span class="text-secondary"><?= date("M d, Y", strtotime($offerViewModal['offer_created_at'])) ?></span></p>
          </div>
          <p>Description:</p>
          <p class="ps-3 text-secondary"><?= $offerViewModal['offer_description'] ?></p>
        </div>
      </div>
    </div>
  <?php


}

?>