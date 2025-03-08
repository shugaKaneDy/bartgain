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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Item Listing</title>
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- rsponsive table -->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" />

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
      background-color: #f8f9fa;
    }
    body {
      padding-left: 400px;
    }
    .image-container {
      height: 250px;
      width: 100%;
      object-fit: cover;
    }
    .nav-link {
      color: #333;
    }
    .nav-link:hover {
      color: #007bff;
    }
    .item-images {
      width: 120px;
      height: 80px;
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
      aside {
        width: 100%;
        z-index: 1;
      }
    }
  </style>
</head>
<body class="bg-light">

  <?php include 'layout/navbar.php'; ?>
  <?php include 'layout/dashboard-aside.php'; ?>
  <?php
    include "verified-authentication.php";
  ?>

  <div class="container mb-5">
    <h3>Item List</h3>
    <div>
      <nav class="nav">
        <a class="nav-link text-primary text-sm" href="item-listing.php">Item Listing</a>
        <a class="nav-link text-sm" href="item-list-history.php">Item List History</a>
        <a class="nav-link text-sm" href="add-item.php">Add Item</a>
      </nav>
    </div>
    <div class="table-responsive border p-4 border shadow rounded">
    <table id="myTable" class="table table-striped">
      <thead>
        <th>Your Items</th>
      </thead>
      <tbody>
        <?php
          $query = "SELECT * FROM items 
                    INNER JOIN users ON items.item_user_id = users.user_id 
                    WHERE items.item_user_id = $userId AND
                    (items.item_status = 'pending' OR items.item_status = 'available')
                    ORDER BY items.item_id DESC";
          $stmt = $conn->query($query);
          $stmt->setFetchMode(PDO::FETCH_OBJ);
          $results = $stmt->fetchAll();

          foreach ($results as $row) {
            $urlImgs = explode(",", $row->item_url_picture);
            ?>
              <tr>
                <td>
                  <div class="row justify-content-center">
                    <div class="col-12 col-md-8 mb-3">
                      <div class="d-flex gap-3">
                        <div>
                          <p class="text-center m-0 text-sm">Item ID: <?= $row->item_id ?></p>
                          <img src="item-photo/<?= $urlImgs[0] ?>" class="item-images rounded" alt="">
                        </div>
                        <div class="d-flex flex-column justify-content-end align-items-start">
                          <p class="text-center m-0 text-sm fw-bold"><?= $row->item_title ?></p>
                          <p class="text-center m-0 text-sm">Status: <?= $row->item_status ?></p>
                          <div>
                            <?php
                              if($row->item_status == 'pending') {
                                ?>
                               
                                  <form action="mark-as-available.php" method="post">
                                    <input type="hidden" name="itemId" value="<?= $row->item_id ?>">
                                    <button class="btn btn-primary bg-primary-subtle text-dark btn-sm mb-2">Mark as available</button>
                                  </form>
                                <?php
                              } else {
                                
                                ?>
                                  <form action="mark-as-pending.php" method="post">
                                    <input type="hidden" name="itemId" value="<?= $row->item_id ?>">
                                    <button class="btn btn-light border btn-sm mb-2">Mark as pending</button>
                                  </form>
                                <?php
                              }
                            ?>
                            <button class="btn btn-light border btn-sm mb-2"><i class="bi bi-rocket"></i> Boost</button>
                            <button value="<?= $row->item_id ?>" class="forItemPopup btn btn-light border btn-sm mb-2">View</button>
                            <form id="deleteItemForm" action="delete-item.php" method="post">
                              <input type="hidden" name="itemId" value="<?= $row->item_id ?>">
                              <button id="deleteItemBtn" type="button" class="btn btn-light border btn-sm">Delete</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php
          }


          
        ?>
      </tbody>
    </table>
    </div>
  </div>

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
                <div id="carouselItemControls" class="carousel slide" data-bs-ride="carousel">
                  <div class="carousel-inner" id="carouselItemInner">
                    <!-- Images will be inserted here by JavaScript -->
                  </div>
                  <button class="carousel-control-prev" type="button" data-bs-target="#carouselItemControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#carouselItemControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>
                </div>
              </div>
              <div class="col-12 col-md-4" style="overflow-y:auto;">
                <div class="d-flex gap-2 mb-4">
                  <img class="item-profile-picture" src="profile-picture/default.jpg" alt="Profile Picture" style="border-radius:50%; width: 50px; height: 50px; object-fit:cover;">
                  <div>
                    <p class="item-fullname fw-bold m-0 text-nowrap text-normal">User Name</p>
                    <p class="item-star-rate fw-bold m-0 text-normal">5.0 <i class="bi bi-star-fill text-warning"></i></p>
                  </div>
                </div>
                <p class="item-title fw-bold fs-5 m-0">Item Title</p>
                <p class="item-description fs-sm">Item Description</p>
                <p class="item-category text-muted m-0">Category: Electronics</p>
                <p class="item-condition text-muted">Condition: Good</p>
                <p class="item-status text-muted">Status: Pending</p>
                <div class="d-flex gap-5">
                  <p class="item-address text-muted"><i class="bi bi-geo-alt text-success"></i> Address</p>
                  <p class="item-distance text-muted">0.2 Km</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



<!-- resposinve data table -->
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="js/plugins/sweetalert2/swal.js"></script>

<!-- update location -->
<?php
  if(isset($_SESSION['user_details'])) {
    ?>
      <script src="update-location.js"></script>
    <?php
  }
?>  

<script>

  $(document).ready( function () {
    $('#myTable').DataTable({
      responsive: true,
      ordering: false
    });

    $('#deleteItemBtn').on('click', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#deleteItemForm').submit();
        }
      });
    });

  });

  $('.forFilterToggle').on('click', function() {
    $('.side-search').toggleClass('d-none');
  });

  $(document).ready(function() {
    $('.forItemPopup').on('click', function(e) {
      e.preventDefault();

      // Fetch item ID
      var itemId = $(this).attr('value');

      // AJAX request to fetch item details
      $.ajax({
        url: 'fetch_item.php', // Your PHP script to fetch item details
        type: 'GET',
        data: { item_id: itemId },
        success: function(response) {
          // Parse JSON response
          let profilePicture = response.profile_picture ?? "default.jpg"
          $('.item-fullname').text(response.fullname);
          $('.item-title').text(response.item_title);
          $('.item-description').text(response.item_description);
          $('.item-category').text("Category: " + response.item_category);
          $('.item-condition').text("Condition: " + response.item_condition);
          $('.item-status').text("Status: " + response.item_status);
          $('.item-address').text(response.address);
          $('.item-distance').text(response.distance_km + " km");

          // Populate carousel with images
          var imageUrls = response.item_url_picture.split(',');

          var carouselInner = $('#carouselItemInner');
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

          $('.item-profile-picture').attr('src', 'profile-picture/' + profilePicture);

          $('#itemModal').modal('show');
        },
        error: function(xhr, status, error) {
          console.error('Error fetching item details:', error);
          // Handle error if needed
        }
      });
    });

  });
</script>

</body>
</html>

