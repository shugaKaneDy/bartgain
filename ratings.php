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

  $selectedRatings = selectQuery(
    $pdo,
    "SELECT
        ratings.*,
        meet_ups.*, 
        offers.*, 
        items.*, 
        users.*,
        CASE 
            WHEN items.item_user_id = :userId THEN 'receiver'
            ELSE 'sender'
        END AS your_role
     FROM ratings
     INNER JOIN meet_ups ON ratings.rate_meet_up_id = meet_ups.meet_up_id
     INNER JOIN offers ON offers.offer_id = meet_ups.meet_up_offer_id
     INNER JOIN items ON offers.offer_item_id = items.item_id
     INNER JOIN users 
       ON CASE 
            WHEN items.item_user_id = :userId THEN offers.offer_user_id
            ELSE items.item_user_id 
          END = users.user_id
     WHERE ratings.rate_by_user_id = :userId
     AND rate_status = 'pending'
     ORDER BY ratings.rate_id DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
    ]
  );

  $selectedRatingsHistory = selectQuery(
    $pdo,
    "SELECT
        ratings.*,
        meet_ups.*, 
        offers.*, 
        items.*, 
        users.*,
        CASE 
            WHEN items.item_user_id = :userId THEN 'receiver'
            ELSE 'sender'
        END AS your_role
     FROM ratings
     INNER JOIN meet_ups ON ratings.rate_meet_up_id = meet_ups.meet_up_id
     INNER JOIN offers ON offers.offer_id = meet_ups.meet_up_offer_id
     INNER JOIN items ON offers.offer_item_id = items.item_id
     INNER JOIN users 
       ON CASE 
            WHEN items.item_user_id = :userId THEN offers.offer_user_id
            ELSE items.item_user_id 
          END = users.user_id
     WHERE ratings.rate_by_user_id = :userId
     AND rate_status = 'completed'
     ORDER BY ratings.rate_date DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
    ]
  );

  $selectedRates = selectQuery(
    $pdo,
    "SELECT * FROM ratings
    INNER JOIN users ON ratings.rate_by_user_id = users.user_id
    WHERE ratings.rate_user_id = :userId
    AND ratings.rate_status = 'completed'
    ORDER BY ratings.rate_id DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id']
    ]
  );

  // print_r($selectedRates);
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
  <title>Ratings</title>

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
          <h3>Ratings</h3>
          <div class="px-3 py-4 bg-white rounded border shadow-sm">
            <ul class="nav nav-pills" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">To Rate</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link link-sm" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">History</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Your Ratings</button>
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
                      <th>Partner</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($selectedRatings as $selectedRating): ?>
                      <?php
                        $totalRating = 0;
                        if($selectedRating['user_rate_count'] == 0) {
                          $totalRating = 0;
                        } else {
                          $totalRating = $selectedRating['user_rating'] / $selectedRating['user_rate_count'];
                        }
                        $totalRating = round($totalRating, 1);
                      ?>
                      <tr class="forClickTr" trRole = "<?= $selectedRating['your_role'] ?>" trOfferId="<?= $selectedRating['offer_random_id'] ?>" trRatingId="<?= $selectedRating['rate_random_id'] ?>" trUserRate="<?= $totalRating ?>" trFullname="<?= $selectedRating['fullname'] ?>"
                      data-bs-toggle="modal" data-bs-target="#ratingModal"
                      >
                        <td>
                          <div class="d-flex gap-2 align-items-center">
                            <img src="profile-uploads/profile.jpg" alt="" class="my-profile rounded-circle">
                            <div>
                              <p class="fw-bold m-0"><?= $selectedRating['fullname'] ?></p>
                              <p class="fw-bold m-0"><?= $totalRating ?> <i class="bi bi-star-fill text-warning"></i></p>
                            </div>
                          </div>
                        </td>
                        <td class="smaller-text">
                          <?= date("M d, Y", strtotime($selectedRating['meet_up_date']))?>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>

              </div>
              <div class="tab-pane fade pt-3" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>Partner</th>
                      <th>Rate</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($selectedRatingsHistory as $selectedRatingHistory): ?>
                      <?php if($selectedRatingHistory['your_role'] != 'receiver'): ?>
                        <tr data-href="meet-up-sender.php?offer_id=<?= $selectedRatingHistory['offer_random_id'] ?>">
                      <?php else: ?>
                        <tr data-href="meet-up-receiver.php?offer_id=<?= $selectedRatingHistory['offer_random_id'] ?>">
                      <?php endif ?>
                        <td>
                          <p class="m-0"><?= $selectedRatingHistory['fullname'] ?></p>
                        </td>
                        <td>
                          <p class="m-0"><?= $selectedRatingHistory['rate_ratings'] ?> <i class="bi bi-star-fill text-warning"></i></p> 
                        </td>
                        <td class="smaller-text">
                          <?= date("M d, Y", strtotime($selectedRatingHistory['rate_date']))?>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>

              <div class="tab-pane fade pt-3" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                <?php
                  $yourTotalRating = 0;
                  if($_SESSION['user_details']['user_rate_count'] == 0) {
                    $yourTotalRating = 0;
                  } else {
                    $yourTotalRating = $_SESSION['user_details']['user_rating'] / $_SESSION['user_details']['user_rate_count'];
                  }
                  $yourTotalRating = round($yourTotalRating, 1);
                ?>
                <div class="row justify-content-center">
                  <div class="col-8 col-md-4 mb-3 border border-dark rounded py-3 text-center">
                    <h1>
                      <?= $yourTotalRating ?>
                      <i class="bi bi-star-fill text-warning">
                        <span>ratings</span>
                      </i>
                    </h1>
                    <p class="m-0"><?= $_SESSION['user_details']['user_rate_count'] ?> person rated</p>
                  </div>
                  <div class="col-12 border-top py-3">
                    <?php foreach($selectedRates as $selectedRate): ?>
                      <?php
                        $totalRatedRating = 0;
                        if($selectedRate['user_rate_count'] == 0) {
                          $totalRatedRating = 0;
                        } else {
                          $totalRatedRating = $selectedRate['user_rating'] / $selectedRate['user_rate_count'];
                        }
                        $totalRatedRating = round($totalRatedRating, 1);
                      ?>
                      <div class="p-2 border shadow-sm mb-3">
                        <div class="d-flex justify-content-between align-items-end p-2 mb-2">
                          <div class="d-flex gap-2 align-items-center">
                            <img src="profile-uploads/profile.jpg" alt="" class="my-profile rounded-circle">
                            <div>
                              <p class="fw-bold m-0"><?= $selectedRate['fullname'] ?></p>
                              <p class="fw-bold m-0"><?= $totalRatedRating ?> <i class="bi bi-star-fill text-warning"></i></p>
                            </div>
                          </div>
                          <p class="m-0 smaller-text text-secondary">
                            <?= date("M d, Y", strtotime($selectedRate['rate_created_at']))?>
                          </p>
                        </div>
                        <?php
                        $rateRating = $selectedRate['rate_ratings']
                        ?>
                        <div class="d-flex px-2 gap-1 mb-2">
                          <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="bi bi-star-fill <?= $i <= $rateRating ? 'text-warning' : 'text-secondary' ?>"></i>
                          <?php endfor; ?>
                        </div>
                        <p class="m-0 text-secondary px-2">
                          <?= $selectedRate['rate_feedback'] ?>
                        </p>
                      </div>
                    <?php endforeach ?>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

  </main>

  <!-- Bootstrap Modal -->
  <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ratingModalLabel">Rate Your Barter Experience</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="d-flex gap-2 align-items-center mb-3">
            <img src="profile-uploads/profile.jpg" alt="" class="my-profile rounded-circle">
            <div>
              <p class="modalFullname fw-bold m-0"><?= $selectedRating['fullname'] ?></p>
              <p class="fw-bold m-0"><span class="modalUserRating"></span> <i class="bi bi-star-fill text-warning"></i></p>
            </div>
          </div>

          <p class="text-muted">How was your bartering experience with your partner?</p>
          <!-- Star Rating -->
          <div class="text-center">
            <div class="star-rating">
              <i class="bi bi-star-fill text-secondary rating-star" data-rating="1"></i>
              <i class="bi bi-star-fill text-secondary rating-star" data-rating="2"></i>
              <i class="bi bi-star-fill text-secondary rating-star" data-rating="3"></i>
              <i class="bi bi-star-fill text-secondary rating-star" data-rating="4"></i>
              <i class="bi bi-star-fill text-secondary rating-star" data-rating="5"></i>
            </div>
            <form id="rateForm">
              <!-- Hidden input to store the selected rating -->
              <input type="hidden" id="selectedRating" name="rating" value="">
              <input type="hidden" id="rateId" name="rateId">
  
              <!-- Comment Box -->
              <div class="mt-3">
                <textarea class="form-control my-input" name="ratingComment" id="ratingComment" rows="3" placeholder="Leave a comment..."></textarea>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer">
          <a class="forViewDetails btn btn-secondary">View Details</a>
          <button type="button" class="btn btn-success" id="submitRating">Submit Rating</button>
        </div>
      </div>
    </div>
  </div>

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

      

      $('#my-input-1').on('input', function () {
        table1.search(this.value).draw(); // Trigger the DataTables search
      });

      $('.dt-search').remove();
      $("#table1_wrapper div:first").remove();

      $(document).on('click', '.forClickTr', function() {
        // Get the attribute values
        var trRole = $(this).attr('trRole');
        var trOfferId = $(this).attr('trOfferId');
        var trRatingId = $(this).attr('trRatingId');
        var trUserRate = $(this).attr('trUserRate');
        var trFullname = $(this).attr('trFullname');

        $('.forViewDetails').attr('href', `meet-up-${trRole}.php?offer_id=${trOfferId}`);
        $('.modalFullname').html(trFullname);
        $('.modalUserRating').html(trUserRate);
        $('#rateId').val(trRatingId);
        
      });


      $('.rating-star').on('click', function() {
        var rating = $(this).data('rating');
        
        // Set the rating value in the hidden input
        $('#selectedRating').val(rating);
        
        // Reset stars and highlight up to the clicked star
        $('.rating-star').removeClass('text-warning').addClass('text-secondary');
        $(this).prevAll().addBack().removeClass('text-secondary').addClass('text-warning');
        
        // console.log("Selected Rating: " + rating); // Debug: show selected rating
      });

      $('#submitRating').on('click', function(e) {
        e.preventDefault();

        let formData = $('#rateForm').serializeArray();
        

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to submit this rating?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "includes/ajax/rate.inc.php",
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

      $("tr[data-href]").click(function() {
        window.location.href = $(this).data("href");
      });

    })

    
  </script>

</body>
</html>