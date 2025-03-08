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
  $_SESSION['csrf_token_add_item'] = $csrfToken;

  $itemsResults = selectQuery(
                              $pdo,
                              "SELECT * FROM items WHERE item_user_id = :user_id
                              AND item_status = 'available'
                              ORDER BY item_created_at DESC",
                              [
                                ":user_id" => $_SESSION['user_details']['user_id'],
                              ]
                            );

  // $UrlFiles = explode(',' , $itemsResult['item_url_file']);
  // $firstFile = $UrlFiles[0];

  $countItems = count($itemsResults);
  

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
  <title>Add Item</title>

  <?php
    include "layouts/top-link.php";
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/add-item.css">


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
        <h3 class="bg-success rounded py-3 px-4 text-center text-white shadow-sm">Add item</h3>
      </header>
      <div class="row">
        <div class="col-12 col-md-7 mb-3">
          <div class="card bg-white border shadow shadow-sm">
            <div class="card-body">
              <form id="addItemForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
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
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category; ?>"><?= $category; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <label for="category">Category</label>
                </div>
  
                <div class="form-floating mb-3">
                  <select name="condition" class="form-select my-input" id="condition" aria-label="Condition">
                    <option value="New">New</option>
                    <option value="Used - Like New">Used - Like New</option>
                    <option value="Used - Good">Used - Good</option>
                    <option value="Used - Fair">Used - Fair</option>
                  </select>
                  <label for="condition">Condition</label>
                </div>
  
                <div class="form-floating mb-3">
                  <select name="swapOption" class="form-select my-input" id="swapOption" aria-label="Condition">
                    <option value="Swap">Swap</option>
                    <option value="Donate">Donate</option>
                  </select>
                  <label for="swapOption">Swap Option</label>
                </div>
  
                <div class="form-floating mb-3">
                  <input name="estimatedValue" type="Number" class="form-control my-input" id="estimatedValue" placeholder="Estimated Value">
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
        <div class="col-12 col-md-5">
          <div class="card bg-white">
            <div class="card-header d-flex justify-content-between">
              <p class="m-0">
                Item Listed
              </p>
              <?php if($_SESSION['user_details']['role_id'] == 2):  ?>
                <p class="m-0">
                  <?= $countItems ?>
                </p>
              <?php elseif($_SESSION['user_details']['user_is_prem'] == "Yes"):  ?>
                <p class="m-0">
                  <?= $countItems ?>/5
                </p>
              <?php else: ?>
                <p class="m-0">
                  <?= $countItems ?>/2
                </p>
              <?php endif ?>
            </div>
            <div class="card-body">
              <?php foreach($itemsResults as $itemResult): ?>
                <?php
                  $UrlFiles = explode(',' , $itemResult['item_url_file']);
                  $firstFile = $UrlFiles[0];

                  $ext = explode('.', $firstFile);
                  $ext = end($ext);
                ?>
                <div class="row p-2">
                  <div class="col-7 d-flex justify-content-center bg-secondary rounded align-items-center">
                    <?php if(in_array($ext, $allowedImages)): ?>
                      <img src="item-uploads/<?= $firstFile ?>" class="img-fluid img-vid-container" >
                    <?php else: ?>
                      <video src="item-uploads/<?= $firstFile ?>" autoplay muted loop class="img-fluid img-vid-container"></video>
                    <?php endif; ?>
                  </div>
                  <div class="col-5">
                    <p class="m-0"><?= $itemResult['item_title'] ?></p>
                    <p class="m-0 text-success"><?= $itemResult['item_swap_option'] ?></p>
                    <p class="m-0 text-warning">₱<?= number_format($itemResult['item_est_val']) ?></p>
                    <p class="m-0 small-text"><?= $itemResult['item_category'] ?></p>
                    <p class="m-0 text-secondary smaller-text"><?=date("M d, Y", strtotime($itemResult['item_created_at']))?></p>
                    <?php if($itemResult['item_boosted'] == "Yes"):?>
                      <button class="btn btn-success btn-sm disabled rounded-pill">
                        <i class="bi bi-rocket-takeoff-fill"></i> Boosted
                      </button>
                    <?php else:?>
                      <a href="boost-item.php?item_id=<?= $itemResult['item_random_id'] ?>" class="btn btn-outline-success btn-sm mt-2 bg-green rounded-pill px-3"><i class="bi bi-rocket-takeoff-fill"></i> Boost</a>
                    <?php endif?>
                  </div>
                </div>

              
              <?php endforeach; ?>
              


            </div>
          </div>
        </div>

      </div>
    </div>
  </main>

  <?php
    include "layouts/bottom-link.php";
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

        <?php if($_SESSION['user_details']['user_is_prem'] == "Yes" || $_SESSION['user_details']['role_id'] == 2): ?>
          if (filesArray.length > 5) {
  
            Swal.fire({
              title: 'Ooops...',
              text: "You can only upload 5 files.",
              icon: 'error',
            }).then(result => {
              
              if(result.isConfirmed) {
                console.log("Goes to premium page");
              }
            })


            itemUrlPicture.val('');
            return;
          }
        <?php else: ?>
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
                window.location.href = "premium.php";
              }
            })
  
  
            itemUrlPicture.val('');
            return;
          }
        <?php endif ?>


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
          text: "Do you want to add this item?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          
          if(result.isConfirmed) {

            $.ajax({
              method: 'POST',
              url: "includes/ajax/add-item.php?function=addItem",
              data: formData,
              processData: false, // Prevent jQuery from processing the data
              contentType: false, // Prevent jQuery from setting content type
              dataType: "JSON",
              beforeSend: function () {
                // Optional: Add loading spinner or disable button
              }
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
            });
          }
        })

        
      });
    });

  </script>

</body>
</html>