<?php

require_once "functions.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();

  $itemRandomId = $_GET["item_random_id"];
  

  $currentTime  = date("Y-m-d H:i:s");
  $itemViewModal = selectQueryFetch(
    $pdo,
    "SELECT items.*, users.*, 
        (6371 * ACOS(COS(RADIANS(:lat)) * COS(RADIANS(users.lat)) * COS(RADIANS(users.lng) - RADIANS(:lng)) + SIN(RADIANS(:lat)) * SIN(RADIANS(users.lat)))) AS distance
    FROM items 
    INNER JOIN users ON items.item_user_id = users.user_id 
    WHERE items.item_random_id = :itemRandomId",
    [
        ':lat' => $_SESSION["user_details"]["lat"],
        ':lng' => $_SESSION["user_details"]["lng"],
        ':itemRandomId' => $itemRandomId
    ]
  );

  $distanceKm = (int)$itemViewModal["distance"];

  $totalRating = 0;
  if($itemViewModal['user_rate_count'] == 0) {
    $totalRating = 0;
  } else {
    $totalRating = $itemViewModal['user_rating'] / $itemViewModal['user_rate_count'];
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


  $urlFiles = explode(',' , $itemViewModal['item_url_file']);
  // $firstFile = $UrlFiles[0];
  // $ext = explode('.', $firstFile);
  // $ext = end($ext);
  // $distanceKm = (int)$boostedItem["distance"];

  ?>
    <div class="row d-block d-md-flex align-items-center justify-content-center h-100">
      <div class="col-12 col-md-8 p-0">
        <!-- Carousel -->
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
          <?php
            $total = count($urlFiles);
            for($i = 0; $i < $total; $i++) {
              ?>
                  <li data-target="#carouselExampleIndicators" data-slide-to="<?=$i?>" <?= $i == 0 ? 'class="active"' : "" ?>></li>
                    <?php
            }
            ?>
          </ol>
          <div class="carousel-inner">
            <?php
              $total = count($urlFiles);
              for($i = 0; $i < $total; $i++) {
                $ext = explode('.', $urlFiles[$i]);
                $ext = end($ext);
                ?>
                  <div class="carousel-item <?= $i == 0 ? 'active' : '' ?> bg-danger">
                    <div class="my-img-container-height bg-dark-subtle d-flex justify-content-center align-items-center">
                      <?= in_array($ext, $allowedImages) ? '<img src="../item-uploads/'.$urlFiles[$i].'" class="d-block w-100" alt="...">' : ' <video src="../item-uploads/'.$urlFiles[$i].'" autoplay muted loop class="d-block w-100"></video>'; ?>
                    </div>
                  </div>
                <?php
              }
              ?>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
      <div class="col-12 col-md-4 content-container py-2 bg-white">
        <?php if($itemViewModal['user_id'] == $_SESSION['user_details']['user_id']): ?>
        <?php else: ?>
          <div class="d-flex justify-content-end">
            <button class="btn btn-light rounded-circle btn-sm" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bi bi-flag">
  
              </i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="report-item.php?itemId=<?= $itemViewModal['item_random_id'] ?>">Report Item</a>
              <a class="dropdown-item" href="report-user.php?userId=<?= $itemViewModal['user_random_id'] ?>">Report User</a>
            </div>
          </div>
        <?php endif ?>
        <div class="mb-3 d-flex gap-2">
          <img src="profile-uploads/<?= empty($itemViewModal['profile_picture']) ? "default.jpg": $itemViewModal['profile_picture']; ?>" class="rounded-circle border-0 my-profile" style="width: 50px; height: 50px">
          <div>
            <a href="" class="link link-dark text-decoration-none fw-bold m-0"><?= $itemViewModal['fullname'] ?></a>
            <p class="m-0 fw-bold"><?= $totalRating ?> <span class="text-warning"><i class="bi bi-star-fill"></i></span></p>
          </div>
        </div>
        <div class="mb-3">
          <p class="fs-4 fw-bold m-0"><?= $itemViewModal['item_title'] ?></p>
          <div class="d-flex justify-content-between align-items-center">
            <p class="fs-5 text-success m-0 fw-bold"><?= $itemViewModal['item_swap_option'] ?></p>
            <?= $distanceKm < 5 ? '<p class="badge badge-warning text-white m-0">Nearby</p>' : "";?>
          </div>
          <p class="fs-5 text-warning m-0">Est: <b><?= number_format($itemViewModal['item_est_val']) ?></b></p>
          <div class="d-flex justify-content-between">
            <p class="m-0 text-secondary"><?= $itemViewModal['current_location'] ?></p>
            <p class="m-0 text-dark"><?= (int)$itemViewModal['distance'] ?>km</p>
          </div>
          <p class="mb-5 text-dark">Status: <span class="text-success"><?= $itemViewModal['item_status'] ?></span></p>
          <p class="fw-bold">Details</p>
          <div class="mb-3">
            <p class="m-0">Reference ID: <span class="text-secondary">#<?= $itemViewModal['item_random_id'] ?></span></p>
            <p class="m-0">Condition: <span class="text-secondary"><?= $itemViewModal['item_condition'] ?></span></p>
            <p class="m-0">Category:  <span class="text-secondary"><?= $itemViewModal['item_category'] ?></span></p>
            <p class="m-0">Listed:  <span class="text-secondary"><?= date("M d, Y", strtotime($itemViewModal['item_created_at'])) ?></span></p>
          </div>
          <p>Description:</p>
          <p class="ps-3 text-secondary"><?= $itemViewModal['item_description'] ?></p>
        </div>
      </div>
    </div>
  <?php


}

?>
