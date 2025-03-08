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

  if(isset($_GET["item_id"])) {
    $getItemId = $_GET["item_id"];
    $item = selectQueryFetch(
      $pdo,
      "SELECT * FROM items WHERE item_random_id = :itemId",
      [
        "itemId" => $getItemId
      ]
    );

    $itemUrlFiles = explode(',' , $item['item_url_file']);
    $itemFirstFile = $itemUrlFiles[0];
    $itemExt = explode('.', $itemFirstFile);
    $itemExt = end($itemExt);

    if($item['item_user_id'] != $_SESSION['user_details']['user_id']) {
      header('location: itemplace.php');
    }
    
  } else {

    header('location: itemplace.php');
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
  <title>Boost Item</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/boost-item.css">


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
          <h3>Boost Item</h3>
          <div class="row justify-content-center">
            
            <div class="col-12 col-md-8 mb-3">
              <div class="px-3 py-4 bg-white rounded border shadow-sm">
                <p class="h5">
                  Boost Your Listing!
                </p>
                <p class="text-muted small-text">
                  Get noticed faster—boost for any number of days you choose!
                </p>

                <!-- Button Form -->
                <div id="button-form">
                  <form id="radioForm">
                    <div class="radios mb-3">
                      <input type="hidden" name="itemId" value="<?= $getItemId ?>">
                      <div class="mb-3">
                        <input type="radio" class="btn-check" value="5" data-cost="120" name="numDays" id="option1" autocomplete="off" checked>
                        <label class="btn btn-outline-success py-3 w-100" for="option1">5 days | <span class="fw-bold">₱120.00</span> </label>
                      </div>

                      <div class="mb-3">
                        <input type="radio" class="btn-check" value="15" data-cost="360" name="numDays" id="option2" autocomplete="off">
                        <label class="btn btn-outline-success py-3 w-100" for="option2">15 days | <span class="fw-bold">₱360.00</span></label>
                      </div>

                      <div class="mb-3">
                        <input type="radio" class="btn-check" value="30" data-cost="720" name="numDays" id="option3" autocomplete="off">
                        <label class="btn btn-outline-success py-3 w-100" for="option3">30 days | <span class="fw-bold">₱720.00</span></label>
                      </div>

                      <div class="mb-5">
                        <a id="customizeDaysBtn" class="link link-success">Customize Days</a>
                      </div>

                      <!-- Summary section -->
                      <div id="summaryButtonForm" class="mb-5">
                        <p class="fw-bold m-0">Payment Summary</p>
                        <p class="small-text text-muted summary-description">Your Item will boost for 5 days.</p>
                        <div class="rounded p-2 bg-light">
                          <div class="border-bottom border-secondary">
                            <div class="d-flex justify-content-between">
                              <p class="m-0">Total budget</p>
                              <p class="m-0 total-budget">₱120.00</p>
                            </div>
                            <p class="small-text text-muted summary-days">5 days</p>
                          </div>
                          <div class="d-flex justify-content-between">
                            <p class="m-0">Total Amount</p>
                            <p class="m-0 total-amount">₱120.00</p>
                          </div>
                        </div>
                      </div>

                      <div class="d-flex justify-content-end">
                        <button id="buttonFormBtn" type="button" class="btn btn-success">Proceed to payment</button>
                      </div>
                    </div>
                  </form>
                </div>

                <!-- Dynamic Form with Summary -->
                <div id="dynamic-form" class="d-none">
                  <form id="boostDaysForm">
                    <input type="hidden" name="itemId" value="<?= $getItemId ?>">
                    <label for="customDays" class="form-label">Enter Number of Days (Min. 5):</label>
                    <input type="number" id="customDays" name="numDays" class="form-control my-input mb-3" min="5" value="5">

                    <!-- Explanation for the user -->
                    <p class="text-muted small-text">
                      The cost is ₱120 for every 5 days, with each additional day costing an extra ₱30. For example, 7 days would cost ₱120 + (2 × ₱30) = ₱180.
                    </p>

                    <div class="mb-5">
                      <a id="selectBudgetBtn" class="link link-success mb-5">
                        Select Budget
                      </a>
                    </div>

                    <!-- Payment Summary for Custom Days -->
                    <div id="dynamicSummary" class="mb-5">
                      <p class="fw-bold m-0">Payment Summary</p>
                      <p class="small-text text-muted dynamic-summary-description">Your item will boost for <span class="dynamic-days">5</span> days.</p>
                      <div class="rounded p-2 bg-light">
                        <div class="border-bottom border-secondary">
                          <div class="d-flex justify-content-between">
                            <p class="m-0">Total budget</p>
                            <p class="m-0 dynamic-total-budget">₱120.00</p>
                          </div>
                          <p class="small-text text-muted dynamic-summary-days">5 days</p>
                        </div>
                        <div class="d-flex justify-content-between">
                          <p class="m-0">Total Amount</p>
                          <p class="m-0 dynamic-total-amount">₱120.00</p>
                        </div>
                      </div>
                    </div>

                    <div class="d-flex justify-content-end">
                      <button id="dynamicFormBtn" type="button" class="btn btn-success">Proceed to Payment</button>
                    </div>

                  </form>
                </div>

              </div>
            </div>

            <!-- Item Preview -->
            <div class="col-10 col-md-4">
              <div class="px-3 py-4 bg-light-green rounded border shadow-sm">
                <div class="border-bottom border-dark mb-3">
                  <p class="h6">Item Preview</p>

                </div>
                <div class="p-2 bg-white rounded border border-2 border-success">
                  <div class="d-flex justify-content-center">
                    <?php
                      if (in_array($itemExt, $allowedImages)) {
                        ?>
                          <img src="item-uploads/<?= $itemFirstFile ?>" class="image-container rounded">
                        <?php
                      } else {
                        ?>
                          <video src="item-uploads/<?= $itemFirstFile ?>" class="image-container rounded">
                        <?php
                      }
                    ?>
                  </div>
                  <div class="d-flex justify-content-between align-items-center top-header">
                    <p class="fw-bold fs-5 text-success m-0"><?= $item['item_swap_option'] ?></p>
                  </div>
                  <div class="text-truncate">
                    <span class="m-0"><?= $item['item_title'] ?></span>
                  </div>
                  <div class="text-truncate">
                    <span class="m-0 text-warning">Est: <b>₱ <?= number_format($item['item_est_val']) ?></b></span>
                  </div>
                  <div class="row">
                    <div class="col-8 text-truncate">
                      <span class="m-0 text-secondary smaller-text"><?= $_SESSION['user_details']['current_location'] ?></span>
                    </div>
                    <div class="col-4 text-truncate text-end">
                      <span class="m-0 text-secondary smaller-text">0 km</span>
                    </div>
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

      // Function to update the summary
      function updateSummary() {
        const selectedOption = $('input[name="numDays"]:checked');
        const days = selectedOption.val();
        const cost = selectedOption.data("cost");

        // Update summary section with selected values
        $(".summary-description").text(`Your Item will boost for ${days} days.`);
        $(".total-budget").text(`₱${cost}.00`);
        $(".summary-days").text(`${days} days`);
        $(".total-amount").text(`₱${cost}.00`);
      }

      // Function to update the summary in dynamic form
      function updateDynamicSummary(days) {
        if (isNaN(days) || days < 5) days = 5;

        let cost = Math.floor(days / 5) * 120 + (days % 5) * 30;

        // Update dynamic summary
        $(".dynamic-summary-description").text(`Your item will boost for ${days} days.`);
        $(".dynamic-total-budget").text(`₱${cost}.00`);
        $(".dynamic-summary-days").text(`${days} days`);
        $(".dynamic-total-amount").text(`₱${cost}.00`);
      }

      // Initialize summary on page load
      updateSummary();

      // Initialize summary on page load with default value
      updateDynamicSummary(5);

      // Event listener for radio buttons
      $('input[name="numDays"]').on("change", updateSummary);

      // Toggle button for customizing days
      $('#customizeDaysBtn').click(function() {
        $('#button-form').addClass('d-none'); // Hide default form
        $('#dynamic-form').removeClass('d-none'); // Show custom day form
      });

      $('#selectBudgetBtn').click(function() {
        $('#dynamic-form').addClass('d-none'); // Hide default form
        $('#button-form').removeClass('d-none'); // Show custom day form
      });

      // Event listener for custom days input
      $('#customDays').on('input', function() {
        const days = parseInt($(this).val());
        updateDynamicSummary(days);
      });

      // Form submit
      $('#buttonFormBtn').on('click', function(e) {
        e.preventDefault();

        let formData = $('#radioForm').serializeArray();

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to submit this bosting?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "includes/ajax/create-payment.inc.php",
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

      // Dynamic Form submit
      $('#dynamicFormBtn').on('click', function(e) {
        e.preventDefault();

        let formData = $('#boostDaysForm').serializeArray();

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to submit this boosting?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "includes/ajax/create-payment.inc.php",
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