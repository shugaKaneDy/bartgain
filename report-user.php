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
  $_SESSION['csrf_token_report_user'] = $csrfToken;

  if(isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    $selectedUser = selectQueryFetch(
      $pdo,
      "SELECT * FROM users
      WHERE user_random_id = :userId",
      [
        ":userId" => $userId,
      ]
    );

    if(empty($selectedUser)) {
      header("Location: itemplace.php");
      exit;
    }

    $pendingUserReport = selectQueryFetch(
      $pdo,
      "SELECT * FROM reports
      WHERE report_by_user_id = :userId
      AND report_user_id = :reportUserId
      AND report_status = :reportStatus",
      [
        ":userId" => $_SESSION['user_details']['user_id'],
        ":reportUserId" => $selectedUser['user_id'],
        ":reportStatus" => "pending",
      ]
    );

    if($pendingUserReport) {
      header("Location: report-user-pending.php");
      exit;
    }

    // print_r($selectedUser);
    // exit();

    $totalRating = 0;
    if($selectedUser['user_rate_count'] == 0) {
      $totalRating = 0;
    } else {
      $totalRating = $selectedUser['user_rating'] / $selectedUser['user_rate_count'];
    }
    $totalRating = round($totalRating, 1);

    $reportOptions = [
      'Violence' => 'Violence',
      'Hate Speech' => 'Hate Speech',
      'Something else' => 'Something else'
    ];

  } else {
    header("Location: itemplace.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report User</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/report.css">


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
        <div class="col-11 col-md-7 bg-white rounded shadow-sm py-3 px-3 border">
          <h3 class="text-danger mb-4">Report User</h3>
          <div class="d-flex gap-2 align-items-center mb-3 p-2 border rounded" style="width: auto; display: inline-flex !important;">
            <img src="profile-uploads/<?= $selectedUser['profile_picture'] ? $selectedUser['profile_picture'] : "default.jpg" ?>" alt="" class="my-profile rounded-circle">
            <div>
              <p class="fw-bold m-0"><?= $selectedUser['fullname'] ?></p>
              <p class="fw-bold m-0"><?= $totalRating ?> <i class="bi bi-star-fill text-warning"></i></p>
            </div>
          </div>
          <p>Why are you reporting this user?</p>
          <p class="small-text text-muted">Your report is anonymous</p>
          <div>
            <form id="reportUserForm" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
              <div class="radios mb-3">
                <?php foreach ($reportOptions as $value => $label): ?>
                  <div class="mb-2 d-inline-block"> <!-- Add margin between buttons -->
                      <input type="hidden" name="userId" value="<?= $selectedUser['user_random_id'] ?>">
                      <input type="radio" class="btn-check" name="reportType" value="<?php echo $value; ?>" id="radio<?php echo str_replace(' ', '', $value); ?>" autocomplete="off" checked>
                      <label class="btn btn-outline-danger rounded-pill" for="radio<?php echo str_replace(' ', '', $value); ?>"><?php echo $label; ?></label>
                  </div>
                <?php endforeach; ?>
              </div>

              <div class="form-floating mb-3" >
                <input name="reportUserPicture[]" type="file" class="form-control my-input-danger" id="reportUserPicture" placeholder="Upload pictures" accept="image/*" multiple required>
                <label for="reportUserPicture">Picture of evidence(Optional)</label>
              </div>
              <div class="file-wrapper mb-3">
                <p class="text-secondary">Uploaded Pictures</p>
                <div class="show-filebox d-flex flex-column bg-light p-1" id="uploadedImagesContainer"></div>
              </div>

              <p>Reason</p>
              <p class="small-text text-muted">Help us understand the problem.</p>

              <div class="mb-3">
                <textarea class="form-control my-input-danger" name="reportReason" id="reportReason" rows="3" placeholder="Write a message"></textarea>
              </div>

              <button type="button" id="submitReportBtn" class="btn btn-danger w-100">Submit</button>
            </form>
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
      
      const reportUserPicture = $("#reportUserPicture");
      const fileWrapper = $("#uploadedImagesContainer");

      // To store the files
      let filesArray = [];

      reportUserPicture.on("change", function(e) {
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


          reportUserPicture.val('');
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
        reportUserPicture[0].files = dataTransfer.files;
      }


      /* START FORM */
      $('#submitReportBtn').on('click', function(e) {
        e.preventDefault();

        // let formData = $('#reportUserForm').serializeArray();
        let formData = new FormData($('#reportUserForm')[0]);

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to submit this report?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "includes/ajax/report-user.inc.php",
              data: formData,
              processData: false, // Prevent jQuery from processing the data
              contentType: false, // Prevent jQuery from setting content type
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
                    window.location.href = "itemplace.php";
                  }
                });
              }

            })
          }
        })

      });
      /* END FORM */
    })

  </script>

</body>
</html>