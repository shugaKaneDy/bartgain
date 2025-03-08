<?php
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'functions.php';

  if(!isset($_SESSION['user_details'])) {
    header("location: logout.php");
    exit;
  }

  // UPDATE ITEMS
  updateQuery(
    $pdo,
    "UPDATE items 
     SET item_boosted = 'No'
     WHERE (item_boost_expire <= :today OR item_boost_expire IS NULL)
     AND item_boosted = 'Yes'",
    [
        ":today" => date("Y-m-d H:i:s")
    ]
  );

  // UPDATE PREMIUM
  updateQuery(
    $pdo,
    "UPDATE users SET user_is_prem = 'No'
    WHERE (user_prem_expire <= :today OR user_prem_expire IS NULL)
    AND user_is_prem = 'Yes'",
    [
      ":today" => date("Y-m-d H:i:s")
    ]
  );

  $_SESSION['indicator'] = 0;

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

  /* USER CLICKS ALGO */
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

  // // Default case if no clicks are found
  // if (empty($categoryClickCounts)) {
  //   $categoryPriorityCase = "CASE WHEN 1 = 1 THEN 0 END AS category_priority"; // Default priority
  // } else {
  //   $categoryPriorityCase = "CASE ";
  //   foreach ($categoryClickCounts as $category => $clickCount) {
  //       $categoryPriorityCase .= "WHEN items.item_category = " . $pdo->quote($category) . " THEN $clickCount ";
  //   }
  //   $categoryPriorityCase .= "ELSE 0 END AS category_priority";
  // }
  /* ./USER CLICKS ALGO */

  /* USER RECENT CLICKS */
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

  /* ./USER RECENT CLICKS */

  $bestMatch = selectQuery(
    $pdo,
    "SELECT items.*, users.*, 
        (6371 * ACOS(COS(RADIANS(:lat)) * COS(RADIANS(items.item_lat)) * COS(RADIANS(items.item_lng) - RADIANS(:lng)) + SIN(RADIANS(:lat)) * SIN(RADIANS(items.item_lat)))) AS distance,
        $categoryPriorityCase
    FROM items 
    INNER JOIN users ON items.item_user_id = users.user_id 
    WHERE items.item_user_id != :itemUserId
    AND items.item_status = 'available'
    ORDER BY items.item_flagged ASC, category_priority DESC, distance ASC
    LIMIT 1",
    [
        ':lat' => $_SESSION["user_details"]["lat"],
        ':lng' => $_SESSION["user_details"]["lng"],
        ':itemUserId' => $_SESSION["user_details"]["user_id"]
    ]
  );

  $bestUrlFiles = explode(',' , $bestMatch[0]['item_url_file']);
  $bestFirstFile = $bestUrlFiles[0];
  $bestExt = explode('.', $bestFirstFile);
  $bestExt = end($bestExt);
  $bestDistanceKm = (int)$bestMatch[0]["distance"];

  $nearest = selectQuery(
    $pdo,
    "SELECT items.*, users.*, 
        (6371 * ACOS(COS(RADIANS(:lat)) * COS(RADIANS(items.item_lat)) * COS(RADIANS(items.item_lng) - RADIANS(:lng)) + SIN(RADIANS(:lat)) * SIN(RADIANS(items.item_lat)))) AS distance
    FROM items 
    INNER JOIN users ON items.item_user_id = users.user_id 
    WHERE items.item_user_id != :itemUserId
    AND items.item_status = 'available'
    ORDER BY items.item_flagged ASC, distance ASC, items.item_id DESC
    LIMIT 1",
    [
        ':lat' => $_SESSION["user_details"]["lat"],
        ':lng' => $_SESSION["user_details"]["lng"],
        ':itemUserId' => $_SESSION["user_details"]["user_id"]
    ]
  );

  $nearestUrlFiles = explode(',' , $nearest[0]['item_url_file']);
  $nearestFirstFile = $nearestUrlFiles[0];
  $nearestExt = explode('.', $nearestFirstFile);
  $nearestExt = end($nearestExt);
  $nearestDistanceKm = (int)$nearest[0]["distance"];

  // echo "<pre>";
  // print_r($nearest);
  // echo "</pre>";
  // exit;

  $categories = [
    "Appliance",
    "Arts and Crafts",
    "Baby and Kids",
    "Books",
    "Building Materials",
    "Clothing and Accessories",
    "Collectibles",
    "Computers and Accessories",
    "Electronics",
    "Foods",
    "Furniture",
    "Gardening Tools",
    "Gift Cards and Vouchers",
    "Health and Beauty",
    "Home and Garden",
    "Jewelry and Watches",
    "Movies and Music",
    "Musical Instruments",
    "Office Supplies",
    "Pet Supplies",
    "Photography",
    "Seasonal Items",
    "Safety and Security",
    "Sports and Outdoors",
    "Tools and Equipment",
    "Toys and Games",
    "Travel and Luggage",
    "Video Games",
    "Kitchen and Dining",
    "Household Items",
    "Outdoor Gear",
    "Bicycles",
    "Antiques",
    "Camping Equipment",
    "Board Games",
    "DIY Supplies",
    "Handmade Items"
  ];

  // Sort the array alphabetically
  sort($categories);

  

  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Itemplace</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/itemplace.css">

  <?php if($_SESSION['user_details']['user_is_prem'] == 'No'): ?>
  <script
      async
      src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"
      crossorigin="anonymous"
  ></script>
  <script>
    window.googletag = window.googletag || { cmd: [] };

    let adSlot;

    googletag.cmd.push(() => {
      // Define an ad slot for the "ad-slot" div.
      adSlot = googletag
        .defineSlot("/6355419/Travel/Europe", [300, 250], "ad-slot")
        .addService(googletag.pubads());

      // Enable the PubAdsService.
      googletag.enableServices();
    });

    document.addEventListener("DOMContentLoaded", (event) => {
      // Register click handlers.
      document.querySelector("#clear").addEventListener("click", (event) => {
        googletag.cmd.push(() => {
          googletag.pubads().clear([adSlot]);
        });
      });

      document.querySelector("#refresh").addEventListener("click", (event) => {
        googletag.cmd.push(() => {
          googletag.pubads().refresh([adSlot]);
        });
      });
    });
  </script>
  <style>
    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      row-gap: 10px;
    }
  </style>
  <?php endif ?>


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
      
      <div class="row">
        <div class="col-8 col-md-9">
          <h4 class="">Itemplace</h4>
        </div>
        <div class="col-4 col-md-3">
          <button class="btn btn-sm btn-success rounded-pill px-3 float-end"
          type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"
          >
            <i class="bi bi-funnel-fill"></i>
            Filters
          </button>
        </div>
      </div>
      <div class="text-end mb-2">
      </div>

      <!-- Best -->
      <div class="row mb-3 justify-content-center align-items-stretch">
        
        <div class="col-12 col-md-8 mb-2 mb-md-0">
          <!-- <img src="assets/graphic-content-white.jpg" alt="" class="img-fluid rounded shadow-sm"> -->
          <!-- <img src="assets/barterGraphic.jpg" alt="" class="img-fluid rounded shadow-sm"> -->
          <div id="carouselExampleRide" class="carousel slide" data-bs-ride="true">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="assets/barterGraphic.png" class="d-block img-fluid rounded shadow-sm" alt="...">
              </div>
              <div class="carousel-item">
                <a href="download-mobile.php">
                  <img src="assets/graphic-content-white.jpg" class="d-block img-fluid rounded shadow-sm" alt="...">
                </a>
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
        <div class="col-12 col-md-4 p-1 d-flex flex-column justify-content-center">
          <p class="">Suggestions <i class="bi bi-lightbulb-fill"></i>!</p>
          <div class="row p-1 g-2">
            <div class="col-6">
              <p class="fw-bold mb-2 text-warning">
                Best Match <i class="bi bi-puzzle-fill"></i>
              </p>
              <div class="position-relative">
                <?php if($bestMatch[0]['user_is_prem'] == 'Yes'): ?>
                  <span class="ribbon">PRM<i class="bi bi-gem"></i></span>
                <?php endif ?>
                <div class="p-2 bg-white rounded border border-2 border-warning ">
                  <a value="<?= $bestMatch[0]['item_random_id'] ?>" class="text-decoration-none text-dark forPopup"
                  data-bs-toggle="modal" data-bs-target="#itemModalView"
                  >
                    <div class="">
                      <?php
                        if (in_array($bestExt, $allowedImages)) {
                          ?>
                            <img src="item-uploads/<?= $bestFirstFile ?>" class="image-container rounded">
                          <?php
                        } else {
                          ?>
                            <video src="item-uploads/<?= $bestFirstFile ?>" class="image-container rounded">
                          <?php
                        }
                      ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center top-header">
                      <p class="fw-bold fs-5 text-success m-0"><?= $bestMatch[0]['item_swap_option'] ?></p>
                      <?= $bestDistanceKm < 5 ? '<p class="badge text-bg-warning text-white m-0">Nearby</p>' : "";?>
                      <!-- <p class="badge text-bg-warning text-white m-0">Nearby</p> -->
                    </div>
                    <div class="text-truncate">
                      <span class="m-0"><?= $bestMatch[0]['item_title'] ?></span>
                    </div>
                    <div class="text-truncate">
                      <span class="m-0 text-warning">Est: <b>₱ <?= number_format($bestMatch[0]['item_est_val']) ?></b></span>
                    </div>
                    <div class="row">
                      <div class="col-8 text-truncate">
                        <span class="m-0 text-secondary smaller-text"><?= $bestMatch[0]['item_current_location'] ?></span>
                      </div>
                      <div class="col-4 text-truncate text-end">
                        <span class="m-0 text-secondary smaller-text"><?= $bestDistanceKm ?>km</span>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>

            <div class="col-6">
              <p class="fw-bold mb-2 text-warning">
                Nearest <i class="bi bi-geo-fill"></i>
              </p>
              <div class="position-relative">
                <?php if($nearest[0]['user_is_prem'] == 'Yes'): ?>
                  <span class="ribbon">PRM<i class="bi bi-gem"></i></span>
                <?php endif ?>
                <div class="p-2 bg-white rounded border border-2 border-warning ">
                  <a value="<?= $nearest[0]['item_random_id'] ?>" class="text-decoration-none text-dark forPopup"
                  data-bs-toggle="modal" data-bs-target="#itemModalView"
                  >
                    <div class="">
                      <?php
                        if (in_array($nearestExt, $allowedImages)) {
                          ?>
                            <img src="item-uploads/<?= $nearestFirstFile ?>" class="image-container rounded">
                          <?php
                        } else {
                          ?>
                            <video src="item-uploads/<?= $nearestFirstFile ?>" class="image-container rounded">
                          <?php
                        }
                      ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center top-header">
                      <p class="fw-bold fs-5 text-success m-0"><?= $nearest[0]['item_swap_option'] ?></p>
                      <?= $nearestDistanceKm < 5 ? '<p class="badge text-bg-warning text-white m-0">Nearby</p>' : "";?>
                      <!-- <p class="badge text-bg-warning text-white m-0">Nearby</p> -->
                    </div>
                    <div class="text-truncate">
                      <span class="m-0"><?= $nearest[0]['item_title'] ?></span>
                    </div>
                    <div class="text-truncate">
                      <span class="m-0 text-warning">Est: <b>₱ <?= number_format($nearest[0]['item_est_val']) ?></b></span>
                    </div>
                    <div class="row">
                      <div class="col-8 text-truncate">
                        <span class="m-0 text-secondary smaller-text"><?= $nearest[0]['item_current_location'] ?></span>
                      </div>
                      <div class="col-4 text-truncate text-end">
                        <span class="m-0 text-secondary smaller-text"><?= $nearestDistanceKm ?>km</span>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>           
        </div>
        
      </div>
      <!-- ./Best -->

      <!-- ADS -->

      <?php if($_SESSION['user_details']['user_is_prem'] == 'No'): ?>
        <div class="my-3 d-flex justify-content-center flex-column align-items-center">
          <div id="host"></div>
          <div class="controls">
            <button id="clear">Clear ad</button>
            <button id="refresh">Refresh ad</button>
          </div>
        </div>
      <?php endif ?>

      <!-- ./ADS -->

      <h4 class="m-0">Just For You</h4>
      <!-- boosted -->
      <div class="row bg-success bg-light-green boosted p-2 mb-2">
        <div class="col-6 col-md-4 col-xl-3 p-0 p-1">
          <div class="p-1 bg-white rounded border border-success">
            <a class="text-decoration-none text-dark forPopup">
              <div class="mb-2">
                <img src="assets/laptop.jpg" alt="" class="image-container w-100 rounded">
              </div>
              <div class="d-flex justify-content-between align-items-center top-header">
                <p class="fw-bold fs-5 text-success m-0">Swap</p>
                <p class="badge text-bg-warning text-white m-0">Nearby</p>
              </div>
              <div class="text-truncate">
                <span class="m-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo magnam provident, sunt, voluptatem ducimus illo ea, voluptatibus optio dolor facilis nulla vel rerum doloremque quam qui soluta veritatis. Atque, ex!</span>
              </div>
              <div class="text-truncate">
                <span class="m-0 text-warning">Est Value: <b>P5000</b></span>
              </div>
              <div class="row">
                <div class="col-8 text-truncate">
                  <span class="m-0 text-secondary smaller-text">General Trias, Cavite</span>
                </div>
                <div class="col-4 text-truncate text-end">
                  <span class="m-0 text-secondary smaller-text">5km</span>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
      


      <!-- not boosted -->
      <div class="row not-boosted p-2">
        
      </div>

      <!-- Loading spinner at the bottom -->
      <div id="loadingSpinner" class="text-center my-3" style="display: none;">
          <div class="spinner-border text-success" role="status">
              <span class="visually-hidden">Loading...</span>
          </div>
      </div>


    </div>


    <!-- Modal View Item -->

    <div class="modal fade" id="itemModalView" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 fw-bolder text-success" id="exampleModalLabel">BartGain</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="content-view modal-body bg-light">
            <div class="row d-block d-md-flex align-items-center justify-content-center h-100">
              <div class="col-12 col-md-8 p-0">
                <!-- Carousel -->
                <div id="carouselExampleIndicators" class="carousel slide">
                  <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                    class="active" aria-current="true"
                    aria-label="Slide 1"
                    ></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                  </div>
                  <div class="carousel-inner">
                    <div class="carousel-item active bg-danger">
                      <div class="my-img-container-height bg-dark-subtle d-flex justify-content-center align-items-center">
                        <img src="assets/logo-2.png" >
                      </div>
                    </div>
                    <div class="carousel-item">
                      <div class="my-img-container-height bg-dark-subtle d-flex justify-content-center align-items-center">
                        <img src="assets/laptop.jpg" >
                      </div>
                    </div>
                    <div class="carousel-item">
                      <div class="my-img-container-height bg-dark-subtle d-flex justify-content-center align-items-center">
                        <video src="assets/sample-vid.mp4" autoplay muted loop></video>
                      </div>
                    </div>
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
                <div class="d-flex justify-content-end">
                  <button class="btn btn-light rounded-circle btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-flag">

                    </i>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Report Item</a></li>
                    <li><a class="dropdown-item" href="#">Report User</a></li>
                  </ul>
                </div>
                <div class="mb-3 d-flex gap-2">
                  <img src="assets/profile.jpg" class="rounded-circle border-0 my-profile" style="width: 50px; height: 50px">
                  <div>
                    <a href="" class="link link-dark text-decoration-none fw-bold m-0">Maryloi Yves Ricalde</a>
                    <p class="m-0 fw-bold">5 <span class="text-warning"><i class="bi bi-star-fill"></i></span></p>
                  </div>
                </div>
                <div class="mb-3">
                  <p class="fs-4 fw-bold m-0">Kawali na malupet</p>
                  <p class="fs-5 text-success m-0 fw-bold">Swap</p>
                  <p class="fs-5 text-warning m-0">Est: <b>10000</b></p>
                  <p class="m-0 text-secondary">General Trias, Cavite</p>
                </div>
                <div class="d-flex w-100 gap-2 mb-3">
                  <button class="btn btn-success flex-grow-1">Send Offer</button>
                  <button class="btn border border-secondary"><i class="bi bi-heart text-success"></i></button>
                </div>
                <div>
                  <p class="fw-bold">Details</p>
                  <div class="mb-3">
                    <p class="m-0">Condition: <span class="text-secondary">Used - Good</span></p>
                    <p class="m-0">Category:  <span class="text-secondary">Electronics</span></p>
                  </div>
                  <p>Description:</p>
                  <p class="ps-3 text-secondary">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sint distinctio quis ea autem error exercitationem debitis nesciunt nobis, quasi possimus maiores accusamus dolore ipsa esse maxime nostrum consequuntur! Sit, facilis.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
      <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Filters</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        
        <!-- sorting -->
        <div class="mb-3">
          <p class="fw-bold text-muted mb-2">Sort</p>
          <div>
            <a href="itemplace.php" class="btn btn-sm btn-success mb-2 me-1 rounded-pill">
              Most relevant
            </a>
            <a href="itemplace-nearest.php" class="btn btn-sm btn-outline-success mb-2 me-1 rounded-pill">
              Nearest
            </a>
          </div>
        </div>

        <!-- category -->
        <div class="mb-3">
          <p class="fw-bold text-muted mb-2">Category</p>
          <div>
          <?php foreach ($categories as $category): ?>
            <a href="itemplace-category.php?cat=<?= $category ?>" class="btn btn-sm btn-outline-success mb-2 me-1 rounded-pill">
                <?= htmlspecialchars($category); ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
    </div>


  </main>


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
    })
  </script>

  <!-- sweetAlert 2 -->
  <?php if($_SESSION['user_details']['verified'] == "N"): ?>
    <script>
       Swal.fire({
          icon: "question",
          title: "Verify Yourself",
          text: "Complete your verification to find your best trading partner!",
          showDenyButton: true,
          confirmButtonText: "Verify Now",
          denyButtonText: "Later"
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          window.location.href = "verification.php";
        }
      });
    </script>
  <?php endif ?>



  <script>
    $(document).ready(function() {
      
      // $('#itemModalView').modal('show');

      const boosted = $('.boosted');
      const notBoosted = $('.not-boosted');
      const contentView = $('.content-view');

      function itemBoosted() {
        $.ajax({
          method: 'GET',
          url: "includes/ajax/item-boosted.php"
        }).done(res => {
          boosted.html(res);
        })
      }

      let isLoading = false; // Prevent multiple requests
      let currentPage = 1;

      function itemNotBoosted(page) {
          $.ajax({
              method: 'GET',
              url: "includes/ajax/item-not-boosted.php",
              data: { page: page }
          }).done(res => {
              notBoosted.append(res); // Append new items instead of replacing

              // Hide the loading spinner once data is loaded
              $('#loadingSpinner').hide();
              isLoading = false; // Reset loading state

              currentPage++; // Increment page for the next request
          });
      }

      itemBoosted();
      itemNotBoosted(currentPage);

      // Lazy load on scroll
      $(window).on('scroll', function () {
          if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
              isLoading = true; // Set loading state to true
              $('#loadingSpinner').show(); // Show the loading spinner
              itemNotBoosted(currentPage); // Load next page when near the bottom
          }
      });


      $(document).on('click', '.forPopup', function(e){
        
        e.preventDefault();
        let itemValue = $(this).attr('value');

        $.ajax({
          method: 'GET',
          url: `includes/ajax/item-modal.php?item_random_id=${itemValue}`,
        }).done(res => {
          contentView.html(res);
        })

        $.ajax({
          method: 'POST',
          data: {
            itemId : itemValue
          },
          url: `includes/ajax/clicks.inc.php`,
          dataType: "JSON",
        })
      })

      
    })
  </script>

  <script>
    $(document).ready(function() {
      $(document).on('click', '.favBtn', function() {
        let itemValue = $(this).attr('favValue');
        // console.log(itemValue);

        // Target the <i> element inside the clicked button
        let icon = $(this).find('i');

        // Example: Toggle a class on the <i> element
        if (icon.hasClass('bi-heart')) {
            icon.removeClass('bi-heart').addClass('bi-heart-fill');

            $.ajax({
              method: 'POST',
              url: 'includes/ajax/fav-add.inc.php',
              data: { item_id: itemValue },
              dataType: "JSON"
            }).done(function (res) {
              if(res.status == 'success') {
                // SweetAlert Toast for Added
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Added to Favorites',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                });
              }
            })

        } else {
            icon.removeClass('bi-heart-fill').addClass('bi-heart');

            $.ajax({
              method: 'POST',
              url: 'includes/ajax/fav-remove.inc.php',
              data: { item_id: itemValue },
              dataType: "JSON"
            }).done(function(res) {

              if(res.status == 'success') {
                // SweetAlert Toast for Removed
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Removed from Favorites',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                });
              }
            })
            
        }
      })
    })
  </script>

<?php if($_SESSION['user_details']['user_is_prem'] == 'No'): ?>
  <script>
    // Attach a shadow DOM to the host element and insert an ad container.
    // Ensure the shadow DOM is in open mode, to allow GPT access.
    const shadow = document.querySelector("#host").attachShadow({ mode: "open" });
    const adContainer = document.createElement("div");
    adContainer.id = "ad-slot";
    adContainer.style.cssText = "height: 250px; width: 300px;";
    shadow.appendChild(adContainer);

    googletag.cmd.push(() => {
      // Locate the ad container in the shadow DOM and display an ad in it.
      const shadowRoot = document.querySelector("#host").shadowRoot;
      googletag.display(shadowRoot.querySelector("#ad-slot"));
    });
  </script>
<?php endif ?>

</body>
</html>