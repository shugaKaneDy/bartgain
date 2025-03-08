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
          <div class="row justify-content-center">
            
            <div class="col-12 col-md-8 mb-3">
              <h3>Premium Membership</h3>
              <div class="px-3 py-4 bg-white rounded border shadow-sm">
                <p class="h5">
                  Unlock Exclusive Benefits!
                </p>
                <p class="text-muted small-text">
                  Gain access to premium features and boost your visibility within our platform!
                </p>

                <!-- Button Form -->
                <div id="button-form">
                  <form id="radioForm">
                    <div class="radios mb-3">
                      <div class="mb-3 position-relative">
                        <span class="ribbon">Popular</span>
                        <input type="radio" class="btn-check" value="1" data-cost="300" name="option" id="option1" autocomplete="off" checked>
                        <label class="btn btn-outline-success py-3 w-100 position-relative" for="option1">1 month | <span class="fw-bold">₱300.00</span> </label>
                      </div>

                      <div class="mb-3 position-relative">
                        <span class="ribbon">5% off</span>
                        <input type="radio" class="btn-check" value="3" data-cost="855" name="option" id="option2" autocomplete="off">
                        <label class="btn btn-outline-success py-3 w-100" for="option2">3 months | <span class="fw-bold">₱855.00</span></label>
                      </div>

                      <div class="mb-5 position-relative">
                        <span class="ribbon">15% off</span>
                        <input type="radio" class="btn-check" value="12" data-cost="3060" name="option" id="option3" autocomplete="off">
                        <label class="btn btn-outline-success py-3 w-100" for="option3">1 year | <span class="fw-bold">₱3060.00</span></label>
                      </div>

                      <!-- Summary section -->
                      <div id="summaryButtonForm" class="mb-5">
                        <p class="fw-bold m-0">Membership Payment Summary</p>
                        <p class="small-text text-muted summary-description">Enjoy premium access for the duration you select!</p>
                        <div class="rounded p-2 bg-light">
                          <div class="border-bottom border-secondary">
                            <div class="d-flex justify-content-between">
                              <p class="m-0">Selected Plan</p>
                              <p class="m-0 selected-plan">1 Month</p>
                            </div>
                            <p class="small-text text-muted summary-duration">Access to premium features</p>
                          </div>
                          <div class="d-flex justify-content-between">
                            <p class="m-0">Total Amount</p>
                            <p class="m-0 total-amount">₱300.00</p>
                          </div>
                        </div>
                      </div>

                      <div class="d-flex justify-content-end">
                        <button id="buttonFormBtn" type="button" class="btn btn-success">Proceed to payment</button>
                      </div>
                    </div>
                  </form>
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

      $('input[name="option"]').change(function() {
        const selectedOption = $(this).val();
        let planText = '';
        let amountText = '';

        switch(selectedOption) {
          case '1':
            planText = '1 Month';
            amountText = '₱300.00';
            break;
          case '3':
            planText = '3 Months';
            amountText = '₱855.00';
            break;
          case '12':
            planText = '1 Year';
            amountText = '₱3060.00';
            break;
        }

        $('.selected-plan').text(planText);
        $('.total-amount').text(amountText);
      });

      // Form submit
      $('#buttonFormBtn').on('click', function(e) {
        e.preventDefault();

        let formData = $('#radioForm').serializeArray();

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to submit this premium?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "includes/ajax/buy-prem.inc.php",
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
                    window.location.href = `payment-check.php?ref_number=${res.ref_num}`;
                  }
                });
              }

            })
          }
        })

      });

    })

    
  </script>

</body>
</html>