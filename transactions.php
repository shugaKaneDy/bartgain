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

  $selectedUnpaidTransactions = selectQuery(
    $pdo,
    "SELECT * FROM payments
    WHERE payment_user_id = :userId
    AND payment_status = :paymentStatus
    ORDER BY payment_id DESC LIMIT 15",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
      ":paymentStatus" => "unpaid",
    ]
  );

  $selectedTransactions = selectQuery(
    $pdo,
    "SELECT * FROM payments
    WHERE payment_user_id = :userId
    ORDER BY payment_id DESC LIMIT 15",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions</title>

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
        <div class="col-12 col-md-8">
          <h3>Transactions</h3>
          <div class="px-3 py-4 bg-white rounded border shadow-sm">
            <ul class="nav nav-pills" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">To Pay</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link link-sm" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">History</button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active mt-3" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>Ref #</th>
                      <th>Type</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($selectedUnpaidTransactions as $selectedUnpaidTransaction): ?>
                      <tr data-href="payment-check.php?ref_number=<?= $selectedUnpaidTransaction['payment_ref_num'] ?>">
                        <td><?= $selectedUnpaidTransaction['payment_ref_num'] ?></td>
                        <td><?= $selectedUnpaidTransaction['payment_type'] ?></td>
                        <td><?= date("M d, Y", strtotime($selectedUnpaidTransaction['payment_created_at'])) ?></td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>

              </div>
              <div class="tab-pane fade pt-3" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>Ref #</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($selectedTransactions as $selectedTransaction): ?>
                      <tr>
                        <td class="small-text"><?= $selectedTransaction['payment_ref_num'] ?></td>
                        <td class="small-text"><?= $selectedTransaction['payment_type'] ?></td>
                        <td>
                          <?php if($selectedTransaction['payment_status'] == 'paid'): ?>
                            <span class="badge text-bg-success">
                              <?= $selectedTransaction['payment_status'] ?>
                            </span>
                          <?php else: ?>
                            <span class="badge text-bg-warning">
                              <?= $selectedTransaction['payment_status'] ?>
                            </span>
                          <?php endif ?>
                        </td>
                        <td class="small-text"><?= date("M d, Y", strtotime($selectedTransaction['payment_created_at'])) ?></td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
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

      $("tr[data-href]").click(function() {
        window.location.href = $(this).data("href");
      });
    })
  </script>

</body>
</html>