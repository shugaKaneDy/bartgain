<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  

  $currentTime  = date("Y-m-d H:i:s");
  $boostedItems = selectQuery(
    $pdo,
    "SELECT items.*, users.*, 
        (6371 * ACOS(COS(RADIANS(:lat)) * COS(RADIANS(items.item_lat)) * COS(RADIANS(items.item_lng) - RADIANS(:lng)) + SIN(RADIANS(:lat)) * SIN(RADIANS(items.item_lat)))) AS distance
    FROM items 
    INNER JOIN users ON items.item_user_id = users.user_id 
    WHERE items.item_boosted = :itemBoosted
    AND items.item_status != 'completed'
    ORDER BY users.user_is_prem = 'Yes' DESC",
    [
        ':lat' => $_SESSION["user_details"]["lat"],
        ':lng' => $_SESSION["user_details"]["lng"],
        ':itemBoosted' => "No"
    ]
  );


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

  foreach ($boostedItems as $boostedItem) {

    $UrlFiles = explode(',' , $boostedItem['item_url_file']);
    $firstFile = $UrlFiles[0];
    $ext = explode('.', $firstFile);
    $ext = end($ext);
    $distanceKm = (int)$boostedItem["distance"];

    ?>
      <div class="col-6 col-md-3 col-xl-2 p-0 p-1 position-relative">
        <?php if($boostedItem['user_is_prem'] == 'Yes'): ?>
          <span class="ribbon">PRM<i class="bi bi-gem"></i></span>
        <?php endif ?>
        <div class="p-2 bg-white rounded border">
          <a value="<?= $boostedItem['item_random_id'] ?>" class="text-decoration-none text-dark forPopup"
          data-bs-toggle="modal" data-bs-target="#itemModalView"
          >
            <div class="">
              <?php
                if (in_array($ext, $allowedImages)) {
                  ?>
                    <img src="item-uploads/<?= $firstFile ?>" class="image-container rounded">
                  <?php
                } else {
                  ?>
                    <video src="item-uploads/<?= $firstFile ?>" class="image-container rounded">
                  <?php
                }
              ?>
            </div>
            <div class="d-flex justify-content-between align-items-center top-header">
              <p class="fw-bold fs-5 text-success m-0"><?= $boostedItem['item_swap_option'] ?></p>
              <?= $distanceKm < 5 ? '<p class="badge text-bg-warning text-white m-0">Nearby</p>' : "";?>
              <!-- <p class="badge text-bg-warning text-white m-0">Nearby</p> -->
            </div>
            <div class="text-truncate">
              <span class="m-0"><?= $boostedItem['item_title'] ?></span>
            </div>
            <div class="text-truncate">
              <span class="m-0 text-warning">Est: <b>₱ <?= number_format($boostedItem['item_est_val']) ?></b></span>
            </div>
            <div class="row">
              <div class="col-8 text-truncate">
                <span class="m-0 text-secondary smaller-text"><?= $boostedItem['item_current_location'] ?></span>
              </div>
              <div class="col-4 text-truncate text-end">
                <span class="m-0 text-secondary smaller-text"><?= $distanceKm ?>km</span>
              </div>
            </div>
          </a>
        </div>
      </div>
    <?php
  }

  for ($i = 0; $i < 10; $i++) {
    ?>
      <div class="col-6 col-md-3 col-xl-2 p-0 p-1 position-relative d-flex align-items-stretch">
        <div class="p-2 bg-white rounded border">
          <div class="">
            <img src="item-uploads/testproduct.jpg" class="image-container rounded">
          </div>
          <div class="text-truncate">
            Test
          </div>
        </div>
      </div>
    <?php
  }

  // echo "<pre>";
  //   print_r($boostedItems);
  // echo "</pre>";

  

}

?>#