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

  if(isset($_GET['itemId'])) {
    $itemId = $_GET['itemId'];

    $selectedItems = selectQueryFetch(
      $pdo,
      "SELECT * FROM items
      WHERE item_random_id = :itemId",
      [
        ":itemId" => $itemId,
      ]
    );

    $itemUrlFiles = explode(',' , $selectedItems['item_url_file']);
    $itemFirstFile = $itemUrlFiles[0];
    $itemExt = explode('.', $itemFirstFile);
    $itemExt = end($itemExt);

    // print_r($selectedUser);
    // exit();

    $reportOptions = [
      'Nudity'        => 'Nudity',
      'Scam'          => 'Scam',
      'Illegal'       => 'Illegal',
      'Violence'      => 'Violence',
      'Hate Speech'   => 'Hate Speech',
      'Harassment'    => 'Harassment',
      'Spam'          => 'Spam',
      'Intellectual Property' => 'Intellectual Property',
      'Fraud'         => 'Fraud',
    ];

    $lastValue = end($reportOptions);

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
  <title>Report Item</title>

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
            Report Item
          </h3>
          <div class="d-flex flex-column gap-2 align-items-center mb-3 p-2 border rounded" style="width: auto; display: inline-flex !important;">
            <div>
              <?php if (in_array($itemExt, $allowedImages)): ?>
                <img src="item-uploads/<?= $itemFirstFile ?>" class="plan-img shadow-sm">
              <?php else: ?>
                <video src="item-uploads/<?= $itemFirstFile ?>" class="plan-img shadow-sm"></video>
              <?php endif ?>
            </div>
              <p class="fw-bold m-0"><?= $selectedItems['item_title'] ?></p>
              <button value="<?= $selectedItems['item_random_id'] ?>" class="forViewItemModal btn btn-sm btn-outline-success bg-green"
              data-bs-toggle="modal" data-bs-target="#itemModalView"
              >
              View Details</button>
          </div>
          <p>Why are you reporting this item?</p>
          <p class="small-text text-muted">Your report is anonymous</p>
          <div>
            <form id="reportItemForm">
              <input type="hidden" name="itemId" value="<?= $selectedItems['item_random_id'] ?>">
              <div class="radios mb-3">
                <?php foreach ($reportOptions as $value => $label): ?>
                  <div class="mb-2 d-inline-block"> <!-- Add margin between buttons -->
                      <input type="radio" class="btn-check" name="reportType" value="<?php echo $value; ?>" id="radio<?php echo str_replace(' ', '', $value); ?>" autocomplete="off"
                      checked
                      >
                      <label class="btn btn-outline-danger rounded-pill" for="radio<?php echo str_replace(' ', '', $value); ?>"><?php echo $label; ?></label>
                  </div>
                <?php endforeach; ?>
              </div>

              <button type="button" id="submitReportBtn" class="btn btn-danger py-2 w-100">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </main>

  <!-- Modal View Item -->

  <div class="modal fade" id="itemModalView" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 fw-bolder text-success" id="exampleModalLabel">Item View</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="content-view-item modal-body bg-light">
        </div>
      </div>
    </div>
  </div>


  <?php
    require_once 'layouts/bottom-link.php';
  ?>


  <script>
    $(document).ready(function() {
      
      const contentViewItem = $('.content-view-item');

      $(document).on('click', '.forViewItemModal', function(e) {

        e.preventDefault();
        let itemValue = $(this).attr('value');

        console.log(itemValue);

        $.ajax({
          method: 'GET',
          url: `includes/ajax/view-item.inc.php?item_random_id=${itemValue}`,
        }).done(res => {
          contentViewItem.html(res);
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
              url: "includes/ajax/report-item.inc.php",
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
                    window.location.href = "itemplace.php";
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