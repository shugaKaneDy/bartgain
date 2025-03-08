<?php
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'functions.php';
  
  if(!isset($_SESSION['user_details'])) {
    header("location: logout.php");
    exit;
  }
  if($_SESSION['user_details']['verified'] == "N") {
    header('location: itemplace.php');
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
  <title>Accepted Verification</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/meet-up.css">


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
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 d-flex flex-column justify-content-center align-items-center">
          <h3 class="pt-5">Accepted Verification</h3>
          <div class="text-success" style="font-size: 96px;">
            <i class="bi bi-check-circle-fill"></i>
          </div>
          <p class="text-center text-muted">
            Verification accepted! You can now find your best trading partner!
          </p>
          <a href="itemplace.php" class="btn btn-outline-success">itemplace</a>
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