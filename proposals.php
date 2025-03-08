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

  
  $proposals = selectQuery(
    $pdo,
    "SELECT * FROM offers
    INNER JOIN items ON items.item_id = offers.offer_item_id
    WHERE offers.offer_status = 'pending'
    AND offers.offer_user_id = :userId
    AND items.item_status != 'completed'
    ORDER BY offers.offer_id DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
    ]
  );
  // print_r($proposals);
  // exit;

  $proposalsHistory = selectQuery(
    $pdo,
    "SELECT * FROM offers
    INNER JOIN items ON items.item_id = offers.offer_item_id
    WHERE offers.offer_status != 'deleted'
    AND offers.offer_user_id = :userId
    ORDER BY offers.offer_id DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
    ]
  );

  // print_r($proposalsHistory);
  // exit;

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
  <title>Proposals</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/item-list.css">


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
          <h3>Proposals</h3>
          <div class="px-3 py-4 bg-white rounded border shadow-sm">
            <ul class="nav nav-pills" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Proposals</button>
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
                      <th>

                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($proposals as $proposal): ?>
                      <?php
                        $itemUrlFiles = explode(',' , $proposal['item_url_file']);
                        $itemFirstFile = $itemUrlFiles[0];
                        $itemExt = explode('.', $itemFirstFile);
                        $itemExt = end($itemExt);

                        $offerUrlFiles = explode(',' , $proposal['offer_url_file']);
                        $offerFirstFile = $offerUrlFiles[0];
                        $offerExt = explode('.', $offerFirstFile);
                        $offerExt = end($offerExt);
                      ?>
                      <tr>
                        <td>
                          <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                              <p class="small-text mb-1 badge text-bg-success">Your Offer</p>
                              <div class="d-flex gap-3">
                                <div>
                                  <?php if(in_array($offerExt, $allowedImages)): ?>
                                    <img src="offer-uploads/<?= $offerFirstFile ?>" alt="" class="my-img-preview border rounded">
                                  <?php else: ?>
                                    <video src="offer-uploads/<?= $offerFirstFile ?>" autoplay muted loop class="my-img-preview border rounded"></video>
                                  <?php endif; ?>
                                </div>
                                <div>
                                  <p class="m-0">
                                    <?= $proposal['offer_title'] ?>
                                  </p>
                                  <p class="m-0 small-text text-muted">
                                    Status:
                                    <?php if($proposal['offer_status'] == 'available'): ?>
                                      <span class="text-success">
                                        <?= $proposal['offer_status'] ?>
                                      </span>
                                    <?php else: ?>
                                        <?= $proposal['offer_status'] ?>
                                    <?php endif ?>
                                  </p>
                                  <p class="smaller-text text-muted">
                                    <?= date("M d, Y", strtotime($proposal['offer_created_at'])) ?>
                                  </p>
                                  <div>
                                    <button class="btn btn-sm btn-light border me-1 mb-2 forViewOfferModal w-100"
                                    value="<?= $proposal['offer_random_id'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#offerModalView">
                                      View
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                              <p class="small-text m-0">Partner Item</p>
                              <div class="d-flex gap-3">
                                <div>
                                  <?php if(in_array($itemExt, $allowedImages)): ?>
                                    <img src="item-uploads/<?= $itemFirstFile ?>" alt="" class="my-img-preview border rounded">
                                  <?php else: ?>
                                    <video src="item-uploads/<?= $itemFirstFile ?>" autoplay muted loop class="my-img-preview border rounded"></video>
                                  <?php endif; ?>
                                </div>
                                <div>
                                  <p class="m-0">
                                    <?= $proposal['item_title'] ?>
                                  </p>
                                  <p class="m-0 small-text text-muted">
                                    Status:
                                    <?php if($proposal['item_status'] == 'available'): ?>
                                      <span class="text-success">
                                        <?= $proposal['item_status'] ?>
                                      </span>
                                    <?php else: ?>
                                        <?= $proposal['item_status'] ?>
                                    <?php endif ?>
                                  </p>
                                  <p class="smaller-text text-muted">
                                    <?= date("M d, Y", strtotime($proposal['item_created_at'])) ?>
                                  </p>
                                  <div>
                                    <button class="btn btn-sm btn-light border me-1 mb-2 forPopup w-100"
                                    value="<?= $proposal['item_random_id'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#itemModalView">
                                      View
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="d-flex justify-content-end">
                            <a href="message-proposals.php?offer_id=<?= $proposal['offer_random_id'] ?>" class="btn btn-outline-success">Check Messages</a>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
                
              </div>
              <div class="tab-pane fade pt-3" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <div class="input-group">
                  <span class="input-group-text" id="search">
                    <i class="bi bi-search"></i>
                  </span>
                  <input type="text" id="my-input-2" class="form-control my-input" placeholder="Search" aria-label="Search Offers" aria-describedby="search">
                </div>
                <table id="table2" class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>

                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($proposalsHistory as $proposalHistory): ?>
                      <?php
                        $itemUrlFiles2 = explode(',' , $proposalHistory['item_url_file']);
                        $itemFirstFile2 = $itemUrlFiles2[0];
                        $itemExt2 = explode('.', $itemFirstFile2);
                        $itemExt2 = end($itemExt2);

                        $offerUrlFiles2 = explode(',' , $proposalHistory['offer_url_file']);
                        $offerFirstFile2 = $offerUrlFiles2[0];
                        $offerExt2 = explode('.', $offerFirstFile2);
                        $offerExt2 = end($offerExt2);
                      ?>
                      <tr>
                        <td>
                          <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                              <p class="small-text mb-1 badge text-bg-success">Your Offer</p>
                              <div class="d-flex gap-3">
                                <div>
                                  <?php if(in_array($offerExt2, $allowedImages)): ?>
                                    <img src="offer-uploads/<?= $offerFirstFile2 ?>" alt="" class="my-img-preview border rounded">
                                  <?php else: ?>
                                    <video src="offer-uploads/<?= $offerFirstFile2 ?>" autoplay muted loop class="my-img-preview border rounded"></video>
                                  <?php endif; ?>
                                </div>
                                <div>
                                  <p class="m-0">
                                    <?= $proposalHistory['offer_title'] ?>
                                  </p>
                                  <p class="m-0 small-text text-muted">
                                    Status:
                                    <?php if($proposalHistory['offer_status'] == 'available'): ?>
                                      <span class="text-success">
                                        <?= $proposalHistory['offer_status'] ?>
                                      </span>
                                    <?php else: ?>
                                        <?= $proposalHistory['offer_status'] ?>
                                    <?php endif ?>
                                  </p>
                                  <p class="smaller-text text-muted">
                                    <?= date("M d, Y", strtotime($proposalHistory['offer_created_at'])) ?>
                                  </p>
                                  <div>
                                    <button class="btn btn-sm btn-light border me-1 mb-2 forViewOfferModal w-100"
                                    value="<?= $proposalHistory['offer_random_id'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#offerModalView">
                                      View
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                              <p class="small-text m-0">Partner Item</p>
                              <div class="d-flex gap-3">
                                <div>
                                  <?php if(in_array($itemExt2, $allowedImages)): ?>
                                    <img src="item-uploads/<?= $itemFirstFile2 ?>" alt="" class="my-img-preview border rounded">
                                  <?php else: ?>
                                    <video src="item-uploads/<?= $itemFirstFile2 ?>" autoplay muted loop class="my-img-preview border rounded"></video>
                                  <?php endif; ?>
                                </div>
                                <div>
                                  <p class="m-0">
                                    <?= $proposalHistory['item_title'] ?>
                                  </p>
                                  <p class="m-0 small-text text-muted">
                                    Status:
                                    <?php if($proposalHistory['item_status'] == 'available'): ?>
                                      <span class="text-success">
                                        <?= $proposalHistory['item_status'] ?>
                                      </span>
                                    <?php else: ?>
                                        <?= $proposalHistory['item_status'] ?>
                                    <?php endif ?>
                                  </p>
                                  <p class="smaller-text text-muted">
                                    <?= date("M d, Y", strtotime($proposalHistory['item_created_at'])) ?>
                                  </p>
                                  <div>
                                    <button class="btn btn-sm btn-light border me-1 mb-2 forPopup w-100"
                                    value="<?= $proposalHistory['item_random_id'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#itemModalView">
                                      View
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="d-flex justify-content-end">
                            <a href="message-proposals.php?offer_id=<?= $proposalHistory['offer_random_id'] ?>" class="btn btn-outline-success">Check Messages</a>
                          </div>
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

  <!-- Modal View Item -->
  <div class="modal fade" id="itemModalView" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 fw-bolder text-success" id="exampleModalLabel">BartGain</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="content-view modal-body bg-light">
          <div class="row d-block d-md-flex align-items-center justify-content-center h-100">
            <div class="col-12 col-md-8 p-0">
              <!-- Carousel -->
              <div id="carouselExampleIndicators" class="carousel slide">
                <div class="carousel-indicators">
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                  class="active" aria-current="true"
                  aria-label="Slide 1"
                  ></button>
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                  <div class="carousel-item active bg-danger">
                    <div class="my-img-container-height bg-dark-subtle d-flex justify-content-center align-items-center">
                      <img src="assets/logo-2.png" >
                    </div>
                  </div>
                  <div class="carousel-item">
                    <div class="my-img-container-height bg-dark-subtle d-flex justify-content-center align-items-center">
                      <img src="assets/laptop.jpg" >
                    </div>
                  </div>
                  <div class="carousel-item">
                    <div class="my-img-container-height bg-dark-subtle d-flex justify-content-center align-items-center">
                      <video src="assets/sample-vid.mp4" autoplay muted loop></video>
                    </div>
                  </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
            </div>
            <div class="col-12 col-md-4 content-container py-2 bg-white">
              <div class="d-flex justify-content-end">
                <button class="btn btn-light rounded-circle btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-flag">

                  </i>
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Report Item</a></li>
                  <li><a class="dropdown-item" href="#">Report User</a></li>
                </ul>
              </div>
              <div class="mb-3 d-flex gap-2">
                <img src="assets/profile.jpg" class="rounded-circle border-0 my-profile" style="width: 50px; height: 50px">
                <div>
                  <a href="" class="link link-dark text-decoration-none fw-bold m-0">Maryloi Yves Ricalde</a>
                  <p class="m-0 fw-bold">5 <span class="text-warning"><i class="bi bi-star-fill"></i></span></p>
                </div>
              </div>
              <div class="mb-3">
                <p class="fs-4 fw-bold m-0">Kawali na malupet</p>
                <p class="fs-5 text-success m-0 fw-bold">Swap</p>
                <p class="fs-5 text-warning m-0">Est: <b>10000</b></p>
                <p class="m-0 text-secondary">General Trias, Cavite</p>
              </div>
              <div class="d-flex w-100 gap-2 mb-3">
                <button class="btn btn-success flex-grow-1">Send Offer</button>
                <button class="btn border border-secondary"><i class="bi bi-heart text-success"></i></button>
              </div>
              <div>
                <p class="fw-bold">Details</p>
                <div class="mb-3">
                  <p class="m-0">Condition: <span class="text-secondary">Used - Good</span></p>
                  <p class="m-0">Category:  <span class="text-secondary">Electronics</span></p>
                </div>
                <p>Description:</p>
                <p class="ps-3 text-secondary">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sint distinctio quis ea autem error exercitationem debitis nesciunt nobis, quasi possimus maiores accusamus dolore ipsa esse maxime nostrum consequuntur! Sit, facilis.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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

      var table1 = $('#table1').DataTable({
        paging: false,
        ordering: false,
        lengthChange: false,
        info: false 
        // searching: false
      });
      var table2 = $('#table2').DataTable({
        paging: true,
        ordering: false,
        lengthChange: false,
        // searching: false
      });

      $('#my-input-1').on('input', function () {
        table1.search(this.value).draw(); // Trigger the DataTables search
      });

      $('#my-input-2').on('input', function () {
        table2.search(this.value).draw(); // Trigger the DataTables search
      });

      $('.dt-search').remove();
      $("#table1_wrapper div:first").remove();

      const contentView = $('.content-view');

      $(document).on('click', '.forPopup', function(e){
        
        e.preventDefault();
        let itemValue = $(this).attr('value');

        console.log(itemValue);

        $.ajax({
          method: 'GET',
          url: `includes/ajax/item-modal.php?item_random_id=${itemValue}`,
        }).done(res => {
          contentView.html(res);
        })
      })

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


    })
  </script>

</body>
</html>