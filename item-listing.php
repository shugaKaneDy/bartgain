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

  $myItems = selectQuery(
    $pdo,
    "SELECT * FROM items
    WHERE item_user_id = :userId
    AND item_status IN ('available', 'pending')
    ORDER BY item_id DESC",
    [
      ':userId' => $_SESSION['user_details']['user_id'],
    ]
  );

  $myItemsHistory = selectQuery(
    $pdo,
    "SELECT * FROM items
    WHERE item_user_id = :userId
    AND item_status != 'deleted'
    ORDER BY item_id DESC",
    [
      ':userId' => $_SESSION['user_details']['user_id'],
    ]
  );

  // print_r($myItems);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Item Listing</title>

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
          <h3>Item Listing</h3>
          <div class="px-3 py-4 bg-white rounded border shadow-sm">
            <ul class="nav nav-pills" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Items</button>
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
                    <?php foreach($myItems as $myItem): ?>
                      <?php
                        $UrlFiles = explode(',' , $myItem['item_url_file']);
                        $firstFile = $UrlFiles[0];
                        $ext = explode('.', $firstFile);
                        $ext = end($ext);
                      ?>
                      <tr>
                        <td>
                          <div class="d-flex gap-3">
                            <div>
                              <?php if(in_array($ext, $allowedImages)): ?>
                                <img src="item-uploads/<?= $firstFile ?>" alt="" class="my-img-preview border rounded">
                              <?php else: ?>
                                <video src="item-uploads/<?= $firstFile ?>" autoplay muted loop class="my-img-preview border rounded"></video>
                              <?php endif; ?>
                            </div>
                            <div>
                              <p class="m-0">
                                <?= $myItem['item_title'] ?>
                              </p>
                              <p class="m-0 small-text text-muted">
                                Status:
                                <?php if($myItem['item_status'] == 'available'): ?>
                                  <span class="text-success">
                                    <?= $myItem['item_status'] ?>
                                  </span>
                                <?php else: ?>
                                    <?= $myItem['item_status'] ?>
                                <?php endif ?>
                              </p>
                              <p class="smaller-text text-muted">
                                <?= date("M d, Y", strtotime($myItem['item_created_at'])) ?>
                              </p>
                              <div class="">
                                <?php if($myItem['item_status'] == 'available'): ?>
                                  <button class="btn btn-sm btn-light border me-1 mb-2 availability"
                                  itemAction="pending"
                                  itemId="<?= $myItem['item_random_id'] ?>"
                                  >
                                    Mark as pending
                                  </button>
                                <?php else: ?>
                                  <button class="btn btn-sm btn-success border me-1 mb-2 availability"
                                  itemAction="available"
                                  itemId="<?= $myItem['item_random_id'] ?>"
                                  >
                                    Mark as available
                                  </button>
                                <?php endif ?>
                                <?php if($myItem['item_boosted'] == 'Yes'):?>
                                  <button class="btn btn-sm btn-success border me-1 mb-2 disabled">
                                    <i class="bi bi bi-rocket-takeoff-fill"></i> Boosted
                                  </button>
                                <?php else:?>
                                  <a href="boost-item.php?item_id=<?= $myItem['item_random_id'] ?>" class="btn btn-sm btn-outline-success me-1 mb-2"><i class="bi bi-rocket-fill"></i> Boost</a>
                                <?php endif?>
                                <button class="btn btn-sm btn-light border me-1 mb-2 forPopup"
                                value="<?= $myItem['item_random_id'] ?>"
                                data-bs-toggle="modal" data-bs-target="#itemModalView">
                                  View
                                </button>
                                <button class="btn btn-sm btn-outline-danger me-1 mb-2 deleteBtn"
                                itemAction="deleted"
                                itemId="<?= $myItem['item_random_id'] ?>"
                                >
                                  Delete
                                </button>
                              </div>
                            </div>
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
                    <?php foreach($myItemsHistory as $myItemHistory): ?>
                      <?php
                        $UrlFiles2 = explode(',' , $myItemHistory['item_url_file']);
                        $firstFile2 = $UrlFiles2[0];
                        $ext2 = explode('.', $firstFile2);
                        $ext2 = end($ext2);
                      ?>
                      <tr>
                        <td>
                          <div class="d-flex gap-3">
                            <div>
                              <?php if(in_array($ext2, $allowedImages)): ?>
                                <img src="item-uploads/<?= $firstFile2 ?>" alt="" class="my-img-preview border rounded">
                              <?php else: ?>
                                <video src="item-uploads/<?= $firstFile2 ?>" autoplay muted loop class="my-img-preview border rounded"></video>
                              <?php endif; ?>
                            </div>
                            <div>
                              <p class="m-0">
                                <?= $myItemHistory['item_title'] ?>
                              </p>
                              <p class="m-0 small-text text-muted">
                                Status:
                                <?php if($myItemHistory['item_status'] == 'available' || $myItemHistory['item_status'] == 'completed'): ?>
                                  <span class="text-success">
                                    <?= $myItemHistory['item_status'] ?>
                                  </span>
                                <?php elseif($myItemHistory['item_status'] == 'on-meet-up'): ?>
                                  <span class="text-warning">
                                    <?= $myItemHistory['item_status'] ?>
                                  </span>
                                <?php else: ?>
                                    <?= $myItemHistory['item_status'] ?>
                                <?php endif ?>
                              </p>
                              <p class="smaller-text text-muted">
                                <?= date("M d, Y", strtotime($myItemHistory['item_created_at'])) ?>
                              </p>
                              <div class="">
                                <button class="btn btn-sm btn-light border me-1 mb-2 forPopup"
                                value="<?= $myItemHistory['item_random_id'] ?>"
                                data-bs-toggle="modal" data-bs-target="#itemModalView">
                                  View
                                </button>
                              </div>
                            </div>
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

      $(document).on('click', ".availability", function() {
        let itemId = $(this).attr("itemId");
        let itemAction = $(this).attr("itemAction");

        Swal.fire({
          title: 'Are you sure?',
          text: `Do you want to mark this as ${itemAction}?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "includes/ajax/mark-as.inc.php",
              data: {
                itemId : itemId,
                itemAction : itemAction
              },
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

      })

      $(document).on('click', ".deleteBtn", function() {
        let itemId = $(this).attr("itemId");
        let itemAction = $(this).attr("itemAction");


        Swal.fire({
          title: 'Are you sure?',
          text: `Do you want to delete this item?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "includes/ajax/mark-as.inc.php",
              data: {
                itemId : itemId,
                itemAction : itemAction
              },
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

      })




    })
  </script>

</body>
</html>