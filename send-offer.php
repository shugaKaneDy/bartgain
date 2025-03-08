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

  $csrfToken = bin2hex(string: random_bytes(32));
  $_SESSION['csrf_token_send_offer'] = $csrfToken;

  $itemRandomId = $_GET["item_id"];

  $itemResult = selectQueryFetch(
                                  $pdo,
                                  "SELECT * FROM items INNER JOIN users
                                  ON items.item_user_id = users.user_id
                                  WHERE items.item_random_id = :itemRandomId AND items.item_status = :status AND users.user_id != :userId",
                                  [
                                    ":itemRandomId" => $itemRandomId,
                                    ":status" => "available",
                                    ":userId" => $_SESSION['user_details']['user_id'],
                                  ]
                                );


  $urlFiles = explode(",", $itemResult["item_url_file"]);
  $total = count($urlFiles);

  if(!$itemResult) {

    header("Location: itemplace.php");
    exit();
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
  <title>Send Offer</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/send-offer.css">


</head>
<body class="bg-light">

  <!-- nav -->
  <?php
    include "layouts/nav.php"
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
      <header>
        <h3 class="bg-success rounded py-3 px-4 text-center text-white shadow-sm">Send Offer</h3>
      </header>

      <div class="row">
        <div class="col-12 col-md-5 mb-3">
          <div class="accordion shadow-sm" id="accordionExample">
            <div class="accordion-item">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Item
              </button>
            </div>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
              <div class="accordion-body border bg-white">
                <div class="d-flex gap-2 align-items-center mb-3">
                  <img src="assets/profile.jpg" class="rounded-circle" style="height: 50px; width: 50px">
                  <div class="">
                    <a href="" class="link link-dark text-decoration-none fw-bold"><?= $itemResult["fullname"] ?></a>
                    <p class="m-0 fw-bold">5 <span class="text-warning"><i class="bi bi-star-fill"></i></span></p>
                    <p class="m-0 smaller-text"><?= $itemResult["current_location"] ?></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <!-- Carousel -->
                    <div id="carouselExampleIndicators" class="carousel slide">
                      <div class="carousel-indicators">
                        <?php
                          for($i = 0; $i < $total; $i++) {
                            ?>
                              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?=$i?>"
                              <?= $i == 0 ? 'class="active" aria-current="true"':"" ?>
                                aria-label="Slide <?= $i + 1 ?>"></button>
                            <?php
                          }
                        ?>
                      </div>
                      <div class="carousel-inner">
                        <?php
                          for($i = 0; $i < $total; $i++) {
                            $ext = explode('.', $urlFiles[$i]);
                            $ext = end($ext);
                            ?>
                              <div class="carousel-item <?= $i == 0 ? 'active' : '' ?> bg-danger">
                                <div class="my-img-container-height bg-dark-subtle d-flex justify-content-center align-items-center">
                                  <?= in_array($ext, $allowedImages) ? '<img src="item-uploads/'.$urlFiles[$i].'" >' : ' <video src="item-uploads/'.$urlFiles[$i].'" autoplay muted loop></video>'; ?>
                                </div>
                              </div>
                            <?php
                          }
                        ?>
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
                  <div class="col-4">
                    <p class="fw-bold m-0"><?= $itemResult["item_title"] ?></p>
                    <p class="fw-bold text-success m-0"><?= $itemResult["item_swap_option"] ?></p>
                    <p class="text-warning small-text m-0">Est Val: <b><?= number_format($itemResult['item_est_val']) ?></b></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-7">
          <div class="card shadow-sm">
            <div class="card-header text-center">
              Offer
            </div>
            <div class="card-body bg-white">
              <form id="addItemForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <input type="hidden" name="item_id" value="<?= $itemResult['item_random_id'] ?>">
                <div class="form-floating mb-3">
                  <input name="itemUrlPicture[]" type="file" class="form-control my-input" id="itemUrlPicture" placeholder="Upload pictures" accept="image/*, video/*" multiple required>
                  <label for="itemUrlPicture">Upload Pictures/Videos</label>
                </div>
                <div class="file-wrapper mb-3">
                  <p class="text-secondary">Uploaded Pictures/Videos</p>
                  <div class="show-filebox d-flex flex-column bg-light p-1" id="uploadedImagesContainer"></div>
                </div>

                <div class="form-floating mb-3">
                  <input name="title" type="text" class="form-control my-input" id="title" placeholder="Title">
                  <label for="title">Title</label>
                </div>

                <div class="form-floating mb-3">
                  <select name="category" class="form-select my-input" id="category" aria-label="Category">
                    <?php if($itemResult['item_swap_option'] == 'Donate'): ?>
                      <option value="Donation">Donation</option>
                    <?php else: ?>
                      <?php foreach ($categories as $category): ?>
                          <option value="<?= $category; ?>"><?= $category; ?></option>
                      <?php endforeach; ?>
                    <?php endif ?>
                  </select>
                  <label for="category">Category</label>
                </div>

                <div class="form-floating mb-3">
                  <select name="condition" class="form-select my-input" id="condition" aria-label="Condition">
                    <?php if($itemResult['item_swap_option'] == 'Donate'): ?>
                      <option value="Donation">Donation</option>
                    <?php else: ?>
                      <option value="New">New</option>
                      <option value="Used - Like New">Used - Like New</option>
                      <option value="Used - Good">Used - Good</option>
                      <option value="Used - Fair">Used - Fair</option>
                    <?php endif ?>
                  </select>
                  <label for="condition">Condition</label>
                </div>

                <div class="form-floating mb-3">
                  <?php if($itemResult['item_swap_option'] == 'Donate'): ?>
                      <input name="estimatedValue" type="Number" class="form-control my-input" id="estimatedValue" placeholder="Estimated Value" value="1" readonly>
                    <?php else: ?>
                      <input name="estimatedValue" type="Number" class="form-control my-input" id="estimatedValue" placeholder="Estimated Value">
                    <?php endif ?>
                  <label for="estimatedValue">Estimated Value (₱)</label>
                </div>

                <div class="form-floating mb-3">
                  <textarea class="form-control my-input" placeholder="Description" id="description" name="description" style="height: 100px"></textarea>
                  <label for="floatingTextarea2">Description</label>
                </div>

                <div class="mt-3">
                  <button class="btn btn-success py-2 w-100 submitBtn">
                    Submit
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      
      
    </div>

  </main>

  <?php
    require_once 'layouts/spinner-overlay.php';
  ?>

<?php
    require_once 'layouts/bottom-link.php';
  ?>


<script>
    $(document).ready(function() {
      const itemUrlPicture = $("#itemUrlPicture");
      const fileWrapper = $("#uploadedImagesContainer");

      // To store the files
      let filesArray = [];

      itemUrlPicture.on("change", function(e) {
        fileWrapper.empty(); // Clear previous images
        filesArray = Array.from(e.target.files); // Store the selected files

        if (filesArray.length > 2) {

          Swal.fire({
            title: 'Ooops...',
            text: "You can only upload 2 files.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Buy Premium',
            cancelButtonText: 'Okay'
          }).then(result => {
            
            if(result.isConfirmed) {
              console.log("Goes to premium page");
            }
          })


          itemUrlPicture.val('');
          return;
        }

        displayFiles(filesArray); // Display the files in the container
      });

      // Function to display the files in the container
      function displayFiles(files) {
        fileWrapper.empty(); // Clear the container

        files.forEach((file, index) => {
          let reader = new FileReader();

          reader.onload = function(e) {
            let preview;

            if (file.type.startsWith('image/')) {
              // Image file preview
              preview = `
                <div class="d-flex justify-content-between align-items-center my-1 uploaded-image-item" data-index="${index}">
                  <div class="uploadImages border rounded" style="max-height: 50px; max-width: 50px">
                    <img src="${e.target.result}" class="rounded w-100" style="max-height: 50px;">
                  </div>
                  <div class="uploadTitle flex-grow-1 ps-3 text-truncate">
                    <span class="m-0 text-secondary">${file.name}</span>
                  </div>
                  <div class="left">
                    <button type="button" class="btn bg-transparent btn-light text-secondary fs-5 border-0 remove-btn">
                      <i class="bi bi-x-circle"></i>
                    </button>
                  </div>
                </div>
              `;
            } else if (file.type.startsWith('video/')) {
              // Video file preview (show a placeholder image)
              preview = `
                <div class="d-flex justify-content-between align-items-center my-1 uploaded-image-item" data-index="${index}">
                  <div class="uploadImages border rounded d-flex justify-content-center align-items-center" style="height: 50px; width: 50px">
                    <i class="bi bi-play-circle-fill text-success fs-3"></i>
                  </div>
                  <div class="uploadTitle flex-grow-1 ps-3 text-truncate">
                    <span class="m-0 text-secondary">${file.name}</span>
                  </div>
                  <div class="left">
                    <button type="button" class="btn bg-transparent btn-light text-secondary fs-5 border-0 remove-btn">
                      <i class="bi bi-x-circle"></i>
                    </button>
                  </div>
                </div>
              `;
            }

            fileWrapper.append(preview);
          };

          reader.readAsDataURL(file); // Read the image or video file as a data URL
        });
      }


      // Add event listener for removing images
      fileWrapper.on('click', '.remove-btn', function() {
        let indexToRemove = $(this).closest('.uploaded-image-item').data('index');
        
        // Remove the file from filesArray
        filesArray.splice(indexToRemove, 1);

        // Update the file input to remove the file
        updateFileInput(filesArray);

        // Re-display the remaining files
        displayFiles(filesArray);
      });

      // Function to update the file input with the remaining files
      function updateFileInput(files) {
        // Create a new DataTransfer object to simulate a new FileList
        let dataTransfer = new DataTransfer();

        files.forEach(file => {
          dataTransfer.items.add(file); // Add each file to the new FileList
        });

        // Update the file input with the new FileList
        itemUrlPicture[0].files = dataTransfer.files;
      }

      $(".submitBtn").on("click", function(e) {
        e.preventDefault();

        // Create a FormData object to include file inputs
        let formData = new FormData($('#addItemForm')[0]);

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to submit this offer?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          
          if(result.isConfirmed) {
            $(".spinner-overlay").removeClass('d-none');
            $.ajax({
              method: 'POST',
              url: "includes/ajax/send-offer.inc.php?function=addItem",
              data: formData,
              processData: false, // Prevent jQuery from processing the data
              contentType: false, // Prevent jQuery from setting content type
              dataType: "JSON",
              beforeSend: function () {
                // Optional: Add loading spinner or disable button
              }
            }).done(function (res) {
              if(res.status == 'error') {

                $(".spinner-overlay").addClass('d-none');
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
                    window.location.href = 'message-proposals.php';
                  }
                });
              }
            });
          }
        })

        
      });
    });

  </script>

</body>
</html>