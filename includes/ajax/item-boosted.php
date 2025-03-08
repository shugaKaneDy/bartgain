<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  // $userClickedCategories = selectQuery(
  //   $pdo,
  //   "SELECT items.item_category, COUNT(*) as click_count 
  //    FROM clicks 
  //    INNER JOIN items ON clicks.click_item_id = items.item_id
  //    WHERE clicks.click_user_id = :userId
  //    GROUP BY items.item_category 
  //    ORDER BY click_count DESC",
  //   [
  //     ':userId' => $_SESSION['user_details']['user_id'],
  //   ]
  // );

  // $categoryClickCounts = [];
  // foreach ($userClickedCategories as $category) {
  //     $categoryClickCounts[$category['item_category']] = $category['click_count'];
  // }


  // if (empty($categoryClickCounts)) {
  //   $categoryPriorityCase = "CASE WHEN 1 = 1 THEN 0 END AS category_priority"; // Default priority
  // } else {
  //   $categoryPriorityCase = "CASE ";
  //   foreach ($categoryClickCounts as $category => $clickCount) {
  //       $categoryPriorityCase .= "WHEN items.item_category = " . $pdo->quote($category) . " THEN $clickCount ";
  //   }
  //   $categoryPriorityCase .= "ELSE 0 END AS category_priority";
  // }

  $userRecentClickedCategories = selectQuery(
    $pdo,
    "SELECT i.item_category, MAX(c.click_id) AS most_recent_click_id
     FROM clicks c
     INNER JOIN items i ON i.item_id = c.click_item_id
     WHERE c.click_user_id = :userId
     GROUP BY i.item_category
     ORDER BY most_recent_click_id DESC",
    [
        ':userId' => $_SESSION['user_details']['user_id'],
    ]
  );

  $categoryPriorityCase = "CASE ";
  if (empty($userRecentClickedCategories)) {
    $categoryPriorityCase .= "WHEN 1 = 1 THEN 0 "; // Default priority
  } else {
    foreach ($userRecentClickedCategories as $category) {
        $categoryPriorityCase .= "WHEN items.item_category = " . $pdo->quote($category['item_category']) . 
                                  " THEN " . $category['most_recent_click_id'] . " ";
    }
  }
  $categoryPriorityCase .= "ELSE 0 END AS category_priority";

  // print_r($categoryPriorityCase);
  // exit;

  $boostedItems = selectQuery(
    $pdo,
    "SELECT items.*, users.*, 
        (6371 * ACOS(COS(RADIANS(:lat)) * COS(RADIANS(items.item_lat)) * COS(RADIANS(items.item_lng) - RADIANS(:lng)) + SIN(RADIANS(:lat)) * SIN(RADIANS(items.item_lat)))) AS distance,
        $categoryPriorityCase
    FROM items 
    INNER JOIN users ON items.item_user_id = users.user_id 
    WHERE items.item_boosted = :itemBoosted
    AND items.item_status = 'available'
    ORDER BY  items.item_flagged ASC, users.user_is_prem = 'Yes' DESC, category_priority DESC, distance ASC",
    [
        ':lat' => $_SESSION["user_details"]["lat"],
        ':lng' => $_SESSION["user_details"]["lng"],
        ':itemBoosted' => "Yes"
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
        <div class="p-2 bg-white rounded border border-2 border-success ">
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

  // echo "<pre>";
  //   print_r($boostedItems);
  // echo "</pre>";

  

}

?>