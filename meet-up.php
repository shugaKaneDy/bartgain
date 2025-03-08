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

  $selectedMeetups = selectQuery(
    $pdo,
    "SELECT 
        meet_ups.*, 
        offers.*, 
        items.*, 
        users.*,
        CASE 
            WHEN items.item_user_id = :userId THEN 'receiver'
            ELSE 'sender'
        END AS your_role
     FROM meet_ups
     INNER JOIN offers ON offers.offer_id = meet_ups.meet_up_offer_id
     INNER JOIN items ON offers.offer_item_id = items.item_id
     INNER JOIN users 
       ON CASE 
            WHEN items.item_user_id = :userId THEN offers.offer_user_id
            ELSE items.item_user_id 
          END = users.user_id
     WHERE (items.item_user_id = :userId OR offers.offer_user_id = :userId)
     AND meet_ups.meet_up_status = 'active'
     ORDER BY meet_ups.meet_up_id DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
    ]
  );

  $selectedMeetupsHistory = selectQuery(
    $pdo,
    "SELECT 
        meet_ups.*, 
        offers.*, 
        items.*, 
        users.*,
        CASE 
            WHEN items.item_user_id = :userId THEN 'sender'
            ELSE 'receiver'
        END AS your_role
     FROM meet_ups
     INNER JOIN offers ON offers.offer_id = meet_ups.meet_up_offer_id
     INNER JOIN items ON offers.offer_item_id = items.item_id
     INNER JOIN users 
       ON CASE 
            WHEN items.item_user_id = :userId THEN offers.offer_user_id
            ELSE items.item_user_id 
          END = users.user_id
     WHERE (items.item_user_id = :userId OR offers.offer_user_id = :userId)
     AND meet_ups.meet_up_status != 'active'
     ORDER BY meet_ups.meet_up_id DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
    ]
  );

  // print_r($selectedMeetup);
  // exit();



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
  <title>Meet Up</title>

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
          <h3>Meet up</h3>
          <div class="px-3 py-4 bg-white rounded border shadow-sm">
            <ul class="nav nav-pills" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">On-going</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link link-sm" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">History</button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active mt-3" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div class="input-group">
                  <span class="input-group-text" id="search">
                    <i class="bi bi-search"></i>
                  </span>
                  <input type="text" id="my-input-1" class="form-control my-input" placeholder="Search" aria-label="Search Offers" aria-describedby="search">
                </div>
                <table id="table1" class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>Item</th>
                      <th>Partner</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($selectedMeetups as $selectedMeetup): ?>
                      <?php
                        $UrlFiles = explode(',' , $selectedMeetup['item_url_file']);
                        $firstFile = $UrlFiles[0];
                        $ext = explode('.', $firstFile);
                        $ext = end($ext);
                      ?>
                      <?php if($selectedMeetup['your_role'] == 'sender'): ?>
                        <tr data-href="meet-up-sender.php?offer_id=<?= $selectedMeetup['offer_random_id'] ?>">
                      <?php else: ?>
                        <tr data-href="meet-up-receiver.php?offer_id=<?= $selectedMeetup['offer_random_id'] ?>">
                      <?php endif ?>
                        <td class="d-flex flex-column align-items-center">
                          <div class="text-center text-md-start fw-bold">
                            <span class="d-inline-block text-truncate smaller-text" style="max-width: 60px;">
                              <?= $selectedMeetup['item_title'] ?>
                            </span>
                          </div>
                          <?php if (in_array($ext, $allowedImages)): ?>
                            <img src="item-uploads/<?= $firstFile ?>" class="img-item-size img-thumbnail">
                          <?php else: ?>
                            <video src="item-uploads/<?= $firstFile ?>" class="img-item-size"></video>
                          <?php endif ?>
                          <p class="small-text m-0 text-success"><?= $selectedMeetup['item_swap_option'] ?></p>
                        </td>
                        <td>
                          <p class="m-0 fw-bold small-text"><?= $selectedMeetup['fullname'] ?></p>
                          <p class="fw-bold m-0 small-text">5<span><i class="bi bi-star-fill text-warning"></i></span></p>
                          <p class="m-0 small-text text-secondary"><?= $selectedMeetup['your_role'] ?></p>
                        </td>
                        <td class="smaller-text">
                          <?= date("M d, Y", strtotime($selectedMeetup['offer_date_time_meet']))?>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>

              </div>
              <div class="tab-pane fade pt-3" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <table id="table2" class="table table-hover table-striped">
                    <thead>
                      <tr>
                        <th>Partner</th>
                        <th>Status</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($selectedMeetupsHistory as $selectedMeetupHistory): ?>
                        <?php if($selectedMeetupHistory['your_role'] == 'receiver'): ?>
                          <tr data-href="meet-up-sender.php?offer_id=<?= $selectedMeetupHistory['offer_random_id'] ?>">
                        <?php else: ?>
                          <tr data-href="meet-up-receiver.php?offer_id=<?= $selectedMeetupHistory['offer_random_id'] ?>">
                        <?php endif ?>
                          <td>
                            <p class="m-0 fw-bold small-text"><?= $selectedMeetupHistory['fullname'] ?></p>
                            <p class="fw-bold m-0 small-text">5<span><i class="bi bi-star-fill text-warning"></i></span></p>
                            <p class="m-0 small-text text-secondary"><?= $selectedMeetupHistory['your_role'] ?></p>
                          </td>
                          <td>
                            <span class="badge text-bg-success"><?= $selectedMeetupHistory['meet_up_status'] ?></span>
                          </td>
                          <td class="smaller-text">
                            <?= date("M d, Y", strtotime($selectedMeetupHistory['meet_up_date']))?>
                          </td>
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
      var table1 = $('#table1').DataTable({
        paging: false,
        ordering: false,
        lengthChange: false,
        info: false 
        // searching: false
      });

      var table2 = $('#table2').DataTable({
        paging: false,
        ordering: false,
        lengthChange: false,
        info: false, 
        searching: false
      });

      $('#my-input-1').on('input', function () {
        table1.search(this.value).draw(); // Trigger the DataTables search
      });

      $('.dt-search').remove();
      $("#table1_wrapper div:first").remove();

      $("tr[data-href]").click(function() {
        window.location.href = $(this).data("href");
      });
    })

    
  </script>

</body>
</html>