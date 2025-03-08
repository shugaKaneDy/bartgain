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
  <title>Buy Premium</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/premium.css">


</head>
<body class="bg-light">

  <!-- nav -->
  <?php
    include "layouts/nav.php"
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
      <div class="row justify-content-center">
        <div class="col-12 col-md-10">
          <h3>Buy Premium Membership</h3>
          <div class="card">
            <div class="card-header">
              <div class="d-flex justify-content-end">
                <a href="premium.php" class="btn btn-success btn-sm">Buy Premium</a>
              </div>
            </div>
            <div class="card-body">
              <div class="py-5 bg-light rounded">
                <?php if($_SESSION['user_details']['user_is_prem'] == 'No'): ?>
                  <h4 class="text-center text-muted fw-bold">No Premium</h4>
                <?php else: ?>
                  <p class="m-0 text-center">Premium expires in:</p>
                  <h4 class="text-center text-success fw-bold"><?= date("M d, Y", strtotime($_SESSION['user_details']['user_prem_expire'])) ?></h4>
                <?php endif ?>
              </div>
              <div class="my-3">
                <h5 class="text-center mb-3">Premium Benefits</h5>
                <div class="row">
                  <div class="col-2 text-center">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>
                  <div class="col-10">
                    <p><span class="fw-bold">Priority Listing:</span> Your items will be seen first.</p>
                  </div>
                  <div class="col-2 text-center">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>
                  <div class="col-10">
                    <p><span class="fw-bold">Ad-Free Experience:</span> No more distractions.</p>
                  </div>
                  <div class="col-2 text-center">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>
                  <div class="col-10">
                    <p><span class="fw-bold">Post More:</span> List up to 5 items at once.</p>
                  </div>
                  <div class="col-2 text-center">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>
                  <div class="col-10">
                    <p><span class="fw-bold">Showcase Your Items:</span> Upload 5 photos per listing.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>

  <?php
    require_once 'layouts/bottom-link.php';
  ?>


  <script>
    $(document).ready(function() {

      
    })

    
  </script>

</body>
</html>