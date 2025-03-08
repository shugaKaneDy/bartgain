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

  if(isset($_GET["ref_number"])) {
    if(!empty($_GET["ref_number"])) {

      $refNumber = $_GET["ref_number"];
      $paymentInfo = selectQueryFetch(
        $pdo,
        "SELECT * FROM payments
        WHERE payment_ref_num = :refNumber",
        [
          ":refNumber" => $refNumber
        ]
      );

      if($paymentInfo['payment_user_id'] != $_SESSION['user_details']['user_id']) {
        header("Location: transactions.php" );
      }

      if($paymentInfo['payment_status'] == 'paid') {
        header("Location: transactions.php" );
      }


    } else {
      
      header("Location: transactions.php" );
    }
  } else {

    header("Location: transactions.php" );
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
  <title>Payment Check</title>

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
        <!-- PENDING -->
        <div class="col-12 pending-check pt-3 d-flex flex-column justify-content-center align-items-center">
          <div class="text-warning" style="font-size: 96px;">
            <i class="bi bi-exclamation-circle-fill"></i>
          </div>
          <p class="fw-bold text-warning m-0">PENDING PAYMENT</p>
          <p class="fw-bold">For <?= $paymentInfo['payment_type'] ?></p>
          <p class="fw-bold fs-3 text-muted">REF #: <?= $refNumber ?></p>
          <a href="<?= $paymentInfo['payment_ref_link'] ?>" target="_blank" class="btn btn-outline-success">
            CLICK HERE TO PAY <i class="bi bi-arrow-right"></i>
          </a>

        </div>

        <!-- SUCCESSFUL -->
        <div class="col-12 success-check pt-3 d-flex flex-column justify-content-center align-items-center d-none">
          <div class="text-success" style="font-size: 96px;">
            <i class="bi bi-check-circle-fill"></i>
          </div>
          <p class="fw-bold text-success">PAYMENT SUCCESSFUL</p>
          <p class="fw-bold fs-3 text-muted">REF #: <?= $refNumber ?></p>
          <a href="transactions.php" class="btn btn-success">
            DONE
          </a>

        </div>
      </div>
    </div>

  </main>

  <?php
    require_once 'layouts/bottom-link.php';
  ?>


  <script>
    $(document).ready(function() {

      // Define the function to check payment status
      function checkPaymentStatus() {
          $.ajax({
              method: "POST",
              url: "includes/ajax/check-payment.inc.php",
              data: { refNum: <?= json_encode($refNumber) ?> },
              dataType: "JSON",
          }).done(function(res) {
              if (res.status == "success") {
                  $('.pending-check').addClass('d-none');
                  $('.success-check').removeClass('d-none');
              }
              if (res.status == 'error') {
                  Swal.fire({
                      icon: res.status,
                      title: res.title,
                      showConfirmButton: true
                  });
              }
          });
      }

      // Run the function immediately
      checkPaymentStatus();

      // Then run the function every 5 seconds
      setInterval(checkPaymentStatus, 2500);
            
    })
  </script>

</body>
</html>