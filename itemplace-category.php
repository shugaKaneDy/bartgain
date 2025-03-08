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


  $_SESSION['indicator'] = 0;

  $cat = $_GET['cat'];

  $categories = [
    "Appliance",
    "Arts and Crafts",
    "Baby and Kids",
    "Books",
    "Building Materials",
    "Clothing and Accessories",
    "Collectibles",
    "Computers and Accessories",
    "Electronics",
    "Foods",
    "Furniture",
    "Gardening Tools",
    "Gift Cards and Vouchers",
    "Health and Beauty",
    "Home and Garden",
    "Jewelry and Watches",
    "Movies and Music",
    "Musical Instruments",
    "Office Supplies",
    "Pet Supplies",
    "Photography",
    "Seasonal Items",
    "Safety and Security",
    "Sports and Outdoors",
    "Tools and Equipment",
    "Toys and Games",
    "Travel and Luggage",
    "Video Games",
    "Kitchen and Dining",
    "Household Items",
    "Outdoor Gear",
    "Bicycles",
    "Antiques",
    "Camping Equipment",
    "Board Games",
    "DIY Supplies",
    "Handmade Items"
  ];

  // Sort the array alphabetically
  sort($categories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Itemplace</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/itemplace.css">


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
      
      <div class="row">
        <div class="col-8 col-md-9">
          <h4 class="m-0"><?= $cat ?></h4>
        </div>
        <div class="col-4 col-md-3">
          <button class="btn btn-sm btn-success rounded-pill px-3 float-end"
          type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"
          >
            <i class="bi bi-funnel-fill"></i>
            Filters
          </button>
        </div>
      </div>
      <div class="text-end mb-2">
      </div>

      <!-- boosted -->
      <div class="row bg-success bg-light-green boosted p-2 mb-2">
        <div class="col-6 col-md-4 col-xl-3 p-0 p-1">
          <div class="p-1 bg-white rounded border border-success">
            <a class="text-decoration-none text-dark forPopup">
              <div class="mb-2">
                <img src="assets/laptop.jpg" alt="" class="image-container w-100 rounded">
              </div>
              <div class="d-flex justify-content-between align-items-center top-header">
                <p class="fw-bold fs-5 text-success m-0">Swap</p>
                <p class="badge text-bg-warning text-white m-0">Nearby</p>
              </div>
              <div class="text-truncate">
                <span class="m-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo magnam provident, sunt, voluptatem ducimus illo ea, voluptatibus optio dolor facilis nulla vel rerum doloremque quam qui soluta veritatis. Atque, ex!</span>
              </div>
              <div class="text-truncate">
                <span class="m-0 text-warning">Est Value: <b>P5000</b></span>
              </div>
              <div class="row">
                <div class="col-8 text-truncate">
                  <span class="m-0 text-secondary smaller-text">General Trias, Cavite</span>
                </div>
                <div class="col-4 text-truncate text-end">
                  <span class="m-0 text-secondary smaller-text">5km</span>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>


      <!-- not boosted -->
      <div class="row not-boosted p-2">
        
      </div>

      <!-- Loading spinner at the bottom -->
      <div id="loadingSpinner" class="text-center my-3" style="display: none;">
          <div class="spinner-border text-success" role="status">
              <span class="visually-hidden">Loading...</span>
          </div>
      </div>


    </div>


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

    <!-- Right Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
      <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Filters</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        
        <!-- sorting -->
        <div class="mb-3">
          <p class="fw-bold text-muted mb-2">Sort</p>
          <div>
            <a href="itemplace.php" class="btn btn-sm btn-outline-success mb-2 me-1 rounded-pill">
              Most relevant
            </a>
            <a href="itemplace-nearest.php" class="btn btn-sm btn-outline-success mb-2 me-1 rounded-pill">
              Nearest
            </a>
          </div>
        </div>

        <!-- category -->
        <div class="mb-3">
          <p class="fw-bold text-muted mb-2">Category</p>
          <div>
          <?php foreach ($categories as $category): ?>
            <a href="itemplace-category.php?cat=<?= $category ?>" class="btn btn-sm btn<?= $cat == $category ? "": "-outline" ?>-success mb-2 me-1 rounded-pill">
                <?= htmlspecialchars($category); ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
    </div>


  </main>


  <?php
    require_once 'layouts/bottom-link.php';
  ?>

  <!-- Update Location -->
  <script>
    $(document).ready(function() {
      function updateLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) { // Pass the position parameter here
                var lat = position.coords.latitude;  // Get latitude
                var lon = position.coords.longitude; // Get longitude

                $.ajax({
                  method: "GET",
                  url: `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}`,
                  success: function(res) {
                    var location = res.city + ', ' + res.localityInfo.administrative[2].name;

                    $.ajax({

                      method: 'POST',
                      url: "includes/ajax/update-location.inc.php",
                      data: {
                        lat : lat,
                        lon : lon,
                        location : location
                      },
                      dataType: "JSON"
                    }).done(function(data) {
                      if(data.status == 'error') {
                        Swal.fire({
                            icon: data.status,
                            title: data.title,
                            showConfirmButton: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "signin.php";
                            }
                        });
                      }
                    })
                  }
                });
            }, function(error) {
              errorLocation(error);
            });
        } else {
            Swal.fire({
              title: "Geolocation Not Supported",
              text: "Your browser does not support geolocation. Please use a different browser.",
              icon: "error",
            });
        }
      }

      updateLocation();
    })
  </script>



  <script>
    $(document).ready(function() {
      
      // $('#itemModalView').modal('show');

      const boosted = $('.boosted');
      const notBoosted = $('.not-boosted');
      const contentView = $('.content-view');

      function itemBoosted() {
        $.ajax({
          method: 'GET',
          url: "includes/ajax/item-boosted-filters.php?function=category",
          data: {
            cat : <?= json_encode($cat) ?>
          }
        }).done(res => {
          boosted.html(res);
        })
      }

      let isLoading = false; // Prevent multiple requests
      let currentPage = 1;

      function itemNotBoosted(page) {
          $.ajax({
              method: 'GET',
              url: "includes/ajax/item-not-boosted-filters.php?function=category",
              data: { 
                page: page,
                cat : <?= json_encode($cat) ?>
              }
          }).done(res => {
              notBoosted.append(res); // Append new items instead of replacing

              // Hide the loading spinner once data is loaded
              $('#loadingSpinner').hide();
              isLoading = false; // Reset loading state

              currentPage++; // Increment page for the next request
          });
      }

      itemBoosted();
      itemNotBoosted(currentPage);

      // Lazy load on scroll
      $(window).on('scroll', function () {
          if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
              isLoading = true; // Set loading state to true
              $('#loadingSpinner').show(); // Show the loading spinner
              itemNotBoosted(currentPage); // Load next page when near the bottom
          }
      });


      $(document).on('click', '.forPopup', function(e){
        
        e.preventDefault();
        let itemValue = $(this).attr('value');


        $.ajax({
          method: 'GET',
          url: `includes/ajax/item-modal.php?item_random_id=${itemValue}`,
        }).done(res => {
          contentView.html(res);
        })

        $.ajax({
          method: 'POST',
          data: {
            itemId : itemValue
          },
          url: `includes/ajax/clicks.inc.php`,
          dataType: "JSON",
        })
      })

      
    })
  </script>

<script>
    $(document).ready(function() {
      $(document).on('click', '.favBtn', function() {
        let itemValue = $(this).attr('favValue');
        // console.log(itemValue);

        // Target the <i> element inside the clicked button
        let icon = $(this).find('i');

        // Example: Toggle a class on the <i> element
        if (icon.hasClass('bi-heart')) {
            icon.removeClass('bi-heart').addClass('bi-heart-fill');

            $.ajax({
              method: 'POST',
              url: 'includes/ajax/fav-add.inc.php',
              data: { item_id: itemValue },
              dataType: "JSON"
            }).done(function (res) {
              if(res.status == 'success') {
                // SweetAlert Toast for Added
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Added to Favorites',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                });
              }
            })

        } else {
            icon.removeClass('bi-heart-fill').addClass('bi-heart');

            $.ajax({
              method: 'POST',
              url: 'includes/ajax/fav-remove.inc.php',
              data: { item_id: itemValue },
              dataType: "JSON"
            }).done(function(res) {

              if(res.status == 'success') {
                // SweetAlert Toast for Removed
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Removed from Favorites',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                });
              }
            })
            
        }
      })
    })
  </script>

</body>
</html>