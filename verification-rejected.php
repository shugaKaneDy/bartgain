<?php
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'functions.php';

  if(!isset($_SESSION['user_details'])) {
    header("location: logout.php");
    exit;
  }
  if($_SESSION['user_details']['verified'] == "Y") {
    header("location: itemplace.php");
    exit;
  }

  if(isset($_GET['v_id'])) {
    $vId = $_GET['v_id'];
    if(empty($vId)) {
      header("Location: itemplace.php");
    } else {
      $verificationInfo = selectQueryFetch(
        $pdo,
        "SELECT * FROM verification
        WHERE verification_random_id = :vId",
        [
          ":vId" => $vId,
        ]
      );

      if(!$verificationInfo) {
        header("Location: itemplace.php");
      }
      if($verificationInfo['verification_user_id'] != $_SESSION['user_details']['user_id']) {
        header("Location: itemplace.php");
      }
      if($verificationInfo['verification_status'] != "rejected") {
        header("Location: itemplace.php");
      }
    }

  } else {
    // header("Location: itemplace.php");
    $getVerification = selectQueryFetch(
      $pdo,
      "SELECT * FROM verification
      WHERE verification_status = 'rejected'
      AND verification_user_id = :userId
      ORDER BY verification_id DESC",
      [
        ":userId" => $_SESSION['user_details']['user_id'],
      ]
    );

    if(empty($getVerification)) {
      header("Location: itemplace.php");
    } else {
      header("Location: verification-rejected.php?v_id=" . $getVerification['verification_random_id']);
      exit;
    }
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
  <title>Rejected Verification</title>

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
          <h3 class="pt-5">Rejected Verification</h3>
          <div class="text-danger" style="font-size: 96px;">
            <i class="bi bi-x-circle-fill"></i>
          </div>
          <p class="fw-bold text-danger">Reject Reason:</p>
          <p class="text-center text-muted">
            <?= $verificationInfo['verification_reject_reason'] ?>
          </p>
          <a href="verification.php" class="btn btn-outline-success">Submit another verification</a>
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