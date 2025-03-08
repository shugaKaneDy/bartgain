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

  $myFavs = selectQuery(
    $pdo,
    "SELECT * FROM favorites
    INNER JOIN items ON favorites.fav_item_id = items.item_id
    WHERE favorites.fav_user_id = :userId
    AND items.item_status = 'available'
    ORDER BY favorites.fav_id DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id']
    ]
  );

  // print_r($myFavs);

  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fovorites</title>

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
          <h3>Favorites</h3>
          <?php if($myFavs): ?>
            <?php foreach($myFavs as $myFav): ?>
              <?php
                $UrlFiles = explode(',' , $myFav['item_url_file']);
                $firstFile = $UrlFiles[0];
                $ext = explode('.', $firstFile);
                $ext = end($ext);
              ?>
              <div class="card mb-2">
                <div class="card-body">
                  <div class="d-flex gap-2">
                    <div>
                      <?php if(in_array($ext, $allowedImages)): ?>
                        <img src="item-uploads/<?= $firstFile ?>" alt="" class="my-img-preview border rounded">
                      <?php else: ?>
                        <video src="item-uploads/<?= $firstFile ?>" autoplay muted loop class="my-img-preview border rounded"></video>
                      <?php endif; ?>
                    </div>
                    <div>
                      <p class="m-0">
                        <?= $myFav['item_title'] ?>
                      </p>
                      <p class="smaller-text text-muted">
                        <?= date("M d, Y", strtotime($myFav['fav_created_at'])) ?>
                      </p>
                      <div>
                        <a href="send-offer.php?item_id=<?= $myFav['item_random_id'] ?>" class="btn btn-sm btn-success border me-1 mb-2">
                          Send Offer
                        </a>
                        <button class="btn btn-sm btn-light border me-1 mb-2 forPopup"
                          value="<?= $myFav['item_random_id'] ?>"
                          data-bs-toggle="modal" data-bs-target="#itemModalView">
                            View
                        </button>
                        <button class="btn btn-sm btn-danger me-1 mb-2 deleteFav"
                        favValue="<?= $myFav['item_random_id'] ?>"
                        >
                          <i class="bi bi-trash"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach ?>
          <?php else: ?>
            <div class="card">
              <div class="card-body">
                <p class="m-0 text-muted fw-bold text-center">No Favorites</p>
              </div>
            </div>
          <?php endif ?>
          
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

  <?php
    require_once 'layouts/bottom-link.php';
  ?>


  <script>
    $(document).ready(function() {

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

      $(document).on('click', '.deleteFav', function() {
        let itemValue = $(this).attr('favValue');
        // console.log(itemValue);

        $.ajax({
          method: 'POST',
          url: 'includes/ajax/fav-remove.inc.php',
          data: { item_id: itemValue },
          dataType: "JSON"
        }).done(function(res) {

          if(res.status == 'success') {
            location.reload();
          }
        })
      })


    })
  </script>

</body>
</html>