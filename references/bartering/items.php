<?php
  session_start();
  require_once 'dbcon.php';

  if(!empty($_SESSION["user_details"])) {
    $userId = $_SESSION["user_details"]["user_id"];
  } else {
    ?>
      <script>
        alert("You must login first");
        window.location.href = "sign-in.php"
      </script>
    <?php
    die();
  }

  function distance($lat1, $lng1, $lat2, $lng2) {
    $earth_radius = 6371; // Radius of the earth in kilometers
    $dlat = deg2rad($lat2 - $lat1);
    $dlng = deg2rad($lng2 - $lng1);
    $a = sin($dlat / 2) * sin($dlat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlng / 2) * sin($dlng / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earth_radius * $c;
    return $distance;
  }

  $userVerify = $_SESSION["user_details"]["verified"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Items Place</title>

  <link rel="icon" href="B.png">


  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/general.css">

  <style>
    aside {
      position: fixed;
      left: 0;
      top: 0; 
      padding-top: 100px;
      width: 380px;
      height: 100vh;
      overflow-y: auto;
    }
    body {
      padding-left: 400px;
    }
    .image-container {
      height: 250px;
      width: 100%;
      object-fit: cover;
    }

    @media (max-width: 568px) {
      body {
        padding-left: 0;
        padding-top: 120px;
      }
      .image-container {
        height: 140px;
      }

    }
    .pagination {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }
    .page-item {
      margin: 0 5px;
    }

   
    .forPopup  {
      cursor: pointer;
    }
  </style>
</head>
<body class="bg-light">
  <?php include 'layout/navbar.php'; ?>
  <?php
    include "verified-authentication.php";
  ?>

  <aside class="px-3 side-search d-none d-md-block bg-white border border-right shadow shadow-sm">
    <h4 class="mb-3">Item Place</h4>
    <div class="input-group mb-5">
      <form action="items.php" method="get" class="input-group">
        <input name="searchText" type="text" class="form-control" placeholder="Search item" aria-label="Username" aria-describedby="basic-addon1">
        <button class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></button>
      </form>
    </div>
    <h6>Filter</h6>
    <div class="mb-3">
      <p class="m-0">Category</p>
      <select class="form-control" name="" id="">
        <option value="" selected disabled></option>
        <option value="Electronics">Electronics</option>
        <option value="Furniture">Furniture</option>
        <option value="Appliance">Appliance</option>
        <option value="Clothing and Accesories">Clothing and Accesories</option>
        <option value="Toys and Games">Toys and Games</option>
      </select>
    </div>
    <div class="mb-3">
      <p class="m-0">Condition</p>
      <select class="form-control" name="" id="">
        <option value="" selected disabled></option>
        <option value="Electronics">New</option>
        <option value="Furniture">Used - Like New</option>
        <option value="Appliance">Used - Good</option>
        <option value="Clothing and Accesories">Used - Fair</option>
      </select>
    </div>
  </aside>
  
  <section>
    <div class="container">
      <?php
        if (isset($_GET["searchText"])) {
          $searchText = $_GET["searchText"];
          ?>
            <h3 class="mb-3">Search: <?= $searchText ?></h3>
          <?php
        } else {
          ?>
            <h3 class="mb-3">Today's pick</h3>
          <?php
        }
      ?>
      <div class="row" id="itemsContainer">
        
        <?php
          if (isset($_GET["searchText"])) {
            $searchText = $_GET['searchText'];
            $searchText = '%' . htmlspecialchars($searchText, ENT_QUOTES) . '%';

            $query = "SELECT *
                      FROM items
                      INNER JOIN users ON items.item_user_id = users.user_id
                      WHERE (item_title LIKE '%$searchText%'
                              OR item_category LIKE '%$searchText%'
                              OR item_condition LIKE '%$searchText%'
                              OR item_description LIKE '%$searchText%'
                              OR item_swap_option LIKE '%$searchText%')
                          AND items.item_status = 'available'
                      ORDER BY item_id DESC";
          } else {
            $query = "SELECT * FROM items INNER JOIN users ON items.item_user_id = users.user_id WHERE items.item_status = 'available' ORDER BY item_id DESC";
          }
          $stmt = $conn->prepare($query);
          $stmt->execute();
          $results = $stmt->fetchAll(PDO::FETCH_OBJ);

          
          foreach ($results as $row) {
            $max_length = 17;
            $title = $row->item_title;
            $length = strlen($title);

            $imageUrls = explode(',', $row->item_url_picture);

            if ($length > $max_length) {
                $title = substr($title, 0, $max_length) . '...';
            }

            $distance_km = distance($_SESSION["user_details"]["lat"], $_SESSION["user_details"]["lng"], $row->lat, $row->lng);
            $distance_km = round($distance_km, 1);
            ?>
              <div class="col-6 col-md-4 rounded mb-3 item py-3">
                <a value="<?= $row->item_id ?>" class="forPopup text-decoration-none text-dark">
                  <div class="p-2">
                    <img src="item-photo/<?= $imageUrls[0] ?>" alt="" class="border shadow-sm image-container rounded mx-auto mb-2">
                    <div class="d-flex justify-content-between">
                      <p class="text-success m-0 text-normal"><?= $row->item_swap_option ?></p>
                      <p class="m-0 badge bg-warning text-xs <?= ($distance_km > 5) ? "d-none" : "" ?>">Nearby</p>
                    </div>
                    <p class="m-0 text-normal"><?= $title ?></p>
                    <div class="d-flex justify-content-between">
                      <p class="text-xs text-muted m-0"><?= $row->address ?></p>
                      <p class="text-xs text-muted m-0"><?= $distance_km ?> km</p>
                    </div>
                  </div>
                </a>
              </div>
            <?php
          }
        ?>
      </div>
      <nav class="pagination-container">
        <ul class="pagination">
          <!-- Pagination items will be inserted here by JavaScript -->
        </ul>
      </nav>
    </div>
  </section>

  <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="itemModalLabel">Item Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row">
              <div class="col-12 col-md-8 mb-3 rounded bg-secondary-subtle d-flex justify-content-center align-item-center">
                <!-- <img id="modalItemImage" src="item-photo/laptop.jpg" class="img-fluid" style="max-height: 400px;"  alt="Item Image"> -->
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                  <div class="carousel-inner" id="carouselInner">
                    <!-- Images will be inserted here by JavaScript -->
                  </div>
                  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>
                </div>
              </div>
              <div class="col-12 col-md-4" style="overflow-y:auto;">
                <div class="d-flex gap-2 mb-4">
                  <img class="profile-picture" src="profile-picture/colets.jpg" alt="Profile Picture" style="border-radius:50%; width: 50px; height: 50px; object-fit:cover;">
                  <div>
                    <p class="fullname fw-bold m-0 text-nowrap text-normal">Colete Bernardo Batumbakal</p>
                    <p class="fw-bold m-0 text-normal"><span class="star-rate"></span> <i class="bi bi-star-fill text-warning"></i></p>
                  </div>
                </div>
                <p class="nearby badge bg-warning text-xs">Nearby</p>
                <p class="title fw-bold fs-5 m-0">Sonny</p>
                <p class="description fs-sm">This is the best earphone</p>
                <p class="swap-option text-success fw-bold fs-sm">Swap</p>
                <p class="category text-muted m-0">Category: Electronics</p>
                <p class="condition text-muted">Condition: Good</p>
                <div class="d-flex gap-5">
                  <p class="address text-muted"><i class="bi bi-geo-alt text-success"></i> Dasmarinas, Cavite</p>
                  <p class="distance text-muted">0.2 Km</p>
                </div>
                <form action="make-offer.php" method="post">
                  <input type="hidden" id="item_id" name="item_id" value="" >
                  <?php
                    if($userVerify == "N") {
                      ?>
                        <p class="btn btn-sm btn-success m-0" id="verifyUser">Make Offer</p>
                      <?php
                    } else {
                      ?>
                        <button class="makeOfferBtn btn btn-sm btn-success">Make Offer</button>
                      <?php
                    }
                  ?>
                </form>

              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>

<!-- update location -->
<?php
  if(isset($_SESSION['user_details'])) {
    ?>
      <script src="update-location.js"></script>
    <?php
  }
?>

<script>
  $(document).ready(function() {
    var itemsPerPage = 12;
    var items = $('.item');
    var totalPages = Math.ceil(items.length / itemsPerPage);
    var currentPage = 1;

    function showPage(page) {
      items.hide();
      items.slice((page - 1) * itemsPerPage, page * itemsPerPage).show();
      currentPage = page;
      updatePagination();
    }

    function createPagination() {
      var pagination = $('.pagination');
      pagination.empty();

      // Previous button
      pagination.append('<li class="page-item"><a class="page-link" href="#" id="prevPage">Previous</a></li>');

      // Calculate start and end page numbers
      var startPage = Math.max(1, currentPage - 1);
      var endPage = Math.min(totalPages, startPage + 2);

      // Add page buttons
      for (var i = startPage; i <= endPage; i++) {
        var pageItem = $('<li>').addClass('page-item').append(
          $('<a>').addClass('page-link').attr('href', '#').text(i).data('page', i)
        );
        pagination.append(pageItem);
      }

      // Next button
      pagination.append('<li class="page-item"><a class="page-link" href="#" id="nextPage">Next</a></li>');
    }

    function updatePagination() {
      var pagination = $('.pagination');
      pagination.empty();

      // Previous button
      pagination.append('<li class="page-item"><a class="page-link" href="#" id="prevPage">Previous</a></li>');

      // Calculate start and end page numbers
      var startPage = Math.max(1, currentPage - 1);
      var endPage = Math.min(totalPages, startPage + 2);

      // Add page buttons
      for (var i = startPage; i <= endPage; i++) {
        var pageItem = $('<li>').addClass('page-item').append(
          $('<a>').addClass('page-link').attr('href', '#').text(i).data('page', i)
        );
        if (i === currentPage) {
          pageItem.addClass('active');
        }
        pagination.append(pageItem);
      }

      // Next button
      pagination.append('<li class="page-item"><a class="page-link" href="#" id="nextPage">Next</a></li>');

      // Disable previous/next buttons if necessary
      $('#prevPage').parent().toggleClass('disabled', currentPage === 1);
      $('#nextPage').parent().toggleClass('disabled', currentPage === totalPages);
    }

    $(document).on('click', '.page-link', function(e) {
      e.preventDefault();
      var page = $(this).data('page');
      if (page) {
        showPage(page);
      } else if ($(this).attr('id') === 'prevPage' && currentPage > 1) {
        showPage(currentPage - 1);
      } else if ($(this).attr('id') === 'nextPage' && currentPage < totalPages) {
        showPage(currentPage + 1);
      }
    });

    showPage(1);
    createPagination();
  });

  $('.sideSlide').on('click', function() {
    $('.side-search').slideToggle();
  });


  $('.forPopup').on('click', function(e) {
      e.preventDefault();

      // Fetch item ID
      var itemValue = $(this).attr('value');

      // AJAX request to fetch item details
      $.ajax({
        url: 'fetch_item.php',
        type: 'GET',
        data: { item_id: itemValue },
        success: function(response) {
          // Parse JSON response

          console.log(response);

          // Show the modal
          let profilePicture = response.profile_picture ?? "default.jpg"
          $('.fullname').text(response.fullname);
          $('.star-rate').text(response.totalRating);
          $('.title').text(response.item_title);
          $('.swap-option').text(response.item_swap_option);
          $('.description').text(response.item_description);
          $('.category').text("Category: " + response.item_category);
          $('.condition').text("Condition: " + response.item_condition);
          $('.address').text(response.address);
          $('.distance').text(response.distance_km + " km");
          $('#item_id').val(response.item_id);
          // $('#modalItemImage').attr('src', 'item-photo/' + response.item_url_picture);


          // Populate carousel with images

          var imageUrls = response.item_url_picture.split(',');

          var carouselInner = $('#carouselInner');
          carouselInner.empty();
          if (imageUrls.length > 0) {
            imageUrls.forEach((image, index) => {
                var carouselItem = $('<div>').addClass('carousel-item').append(
                    $('<img>').addClass('d-block w-100').attr('src', 'item-photo/' + image).css('max-height', '400px')
                );
                if (index === 0) {
                    carouselItem.addClass('active');
                }
                carouselInner.append(carouselItem);
            });
          } else {
            // Fallback if no images are provided
            carouselInner.append(
                $('<div>').addClass('carousel-item active').append(
                    $('<img>').addClass('d-block w-100').attr('src', 'item-photo/default.jpg').css('max-height', '400px')
                )
            );
          }




          $('.profile-picture').attr('src', 'profile-picture/' + profilePicture);
          if (response.distance_km > 5) {
            $('.nearby').addClass('d-none');
          } else {
            $('.nearby').removeClass('d-none');
          }

          $('#itemModal').modal('show');

          console.log(response);
          var tryId = <?= json_encode($_SESSION["user_details"]["user_id"]) ?>;

          if (response.user_id === tryId) {
            $('.makeOfferBtn').addClass('d-none');
          } else {
            $('.makeOfferBtn').removeClass('d-none');
          }
          console.log(tryId === response.user_id);
        },
        error: function(xhr, status, error) {
          console.error('Error fetching item details:', error);
          // Handle error if needed
        }
      });
    });

    $('.forFilterToggle').on('click', function() {
      $('.side-search').toggleClass('d-none');
    });

</script>

</body>
</html>
