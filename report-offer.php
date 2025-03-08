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

  if(isset($_GET['offerId'])) {
    $offerId = $_GET['offerId'];

    $selectedOffer = selectQueryFetch(
      $pdo,
      "SELECT * FROM offers
      WHERE offer_random_id = :offerId",
      [
        ":offerId" => $offerId,
      ]
    );

    $offerUrlFiles = explode(',' , $selectedOffer['offer_url_file']);
    $offerFirstFile = $offerUrlFiles[0];
    $offerExt = explode('.', $offerFirstFile);
    $offerExt = end($offerExt);

    // print_r($selectedUser);
    // exit();

    $reportOptions = [
      'Nudity' => 'Nudity',
      'Scam' => 'Scam',
      'Illegal' => 'Illegal',
      'Violence' => 'Violence',
      'Hate Speech' => 'Hate Speech',
      'Something else' => 'Something else'
    ];

  } else {
    header("Location: itemplace.php");
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
  <title>Report Offer</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/report.css">


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
        <div class="col-11 col-md-7 bg-white border rounded shadow-sm py-3 px-3">
          <h3 class="mb-4 text-danger">
            Report Offer
          </h3>
          <div class="d-flex flex-column gap-2 align-items-center mb-3 p-2 border rounded" style="width: auto; display: inline-flex !important;">
            <div>
              <?php if (in_array($offerExt, $allowedImages)): ?>
                <img src="offer-uploads/<?= $offerFirstFile ?>" class="plan-img shadow-sm">
              <?php else: ?>
                <video src="offer-uploads/<?= $offerFirstFile ?>" class="plan-img shadow-sm"></video>
              <?php endif ?>
            </div>
            <p class="fw-bold m-0"><?= $selectedOffer['offer_title'] ?></p>
            <button value="<?= $selectedOffer['offer_random_id'] ?>" class="forViewOfferModal btn btn-sm btn-outline-success bg-green w-100"
            data-bs-toggle="modal" data-bs-target="#offerModalView"
            >
            View Details</button>
          </div>
          <p>Why are you reporting this offer?</p>
          <p class="small-text text-muted">Your report is anonymous</p>
          <div>
            <form id="reportItemForm">
              <div class="radios mb-3">
                <?php foreach ($reportOptions as $value => $label): ?>
                  <input type="hidden" name="offerId" value="<?= $offerId ?>">
                  <div class="mb-2 d-inline-block"> <!-- Add margin between buttons -->
                      <input type="radio" class="btn-check" name="reportType" value="<?php echo $value; ?>" id="radio<?php echo str_replace(' ', '', $value); ?>" autocomplete="off" checked>
                      <label class="btn btn-outline-danger rounded-pill" for="radio<?php echo str_replace(' ', '', $value); ?>"><?php echo $label; ?></label>
                  </div>
                <?php endforeach; ?>
              </div>

              <p>Reason</p>
              <p class="small-text text-muted">Help us understand the problem.</p>

              <div class="mb-3">
                <textarea class="form-control my-input-danger" name="reportReason" id="reportReason" rows="3" placeholder="Write a message"></textarea>
              </div>

              <button type="button" id="submitReportBtn" class="btn btn-danger w-100">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </main>

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


  <?php
    require_once 'layouts/bottom-link.php';
  ?>


  <script>
    $(document).ready(function() {
      
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

      /* START FORM */
      $('#submitReportBtn').on('click', function(e) {
        e.preventDefault();

        let formData = $('#reportItemForm').serializeArray();

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to submit this report?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "includes/ajax/report-offer.inc.php",
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
      /* END FORM */

    })

  </script>

</body>
</html>