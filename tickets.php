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
  $_SESSION['csrf_token_ticket'] = $csrfToken;

  $tickets = selectQuery(
    $pdo,
    "SELECT * FROM tickets
    WHERE ticket_user_id = :userId
    ORDER BY ticket_id DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
    ]
  );

  // print_r($tickets);


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
  <title>Tickets</title>

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
        <div class="col-12 col-md-10">
          <h3>Tickets</h3>
          <div class="card">
            <div class="card-body">
              <ul class="nav nav-pills" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Your Tickets</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link link-sm" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Submit a ticket</button>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active mt-3" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                <!-- tickets table -->
                <div class="input-group">
                  <span class="input-group-text" id="search">
                    <i class="bi bi-search"></i>
                  </span>
                  <input type="text" id="my-input-1" class="form-control my-input" placeholder="Search" aria-label="Search Ticket" aria-describedby="search">
                </div>
                <table id="table1" class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th class="text-start">Subject</th>
                      <th class="text-start">ID</th>
                      <th class="text-start">Created</th>
                      <th class="text-start">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($tickets as $ticket): ?>
                      <tr>
                        <td class="small-text text-start">
                          <a href="ticket-view.php?t_id=<?= $ticket['ticket_random_id'] ?>" class="link link-success">
                            <?= $ticket['ticket_subject'] ?>
                          </a>
                        </td>
                        <td class="small-text text-start">#<?= $ticket['ticket_random_id'] ?></td>
                        <td class="small-text text-start">
                          <?= date("M d, Y", strtotime($ticket['ticket_created_at']))?>
                        </td>
                        <td class="small-text text-start">
                          <?php if($ticket['ticket_status'] == "open"): ?>
                            <span class="badge text-bg-success">open</span>
                          <?php else: ?>
                            <span class="badge text-bg-danger">closed</span>
                          <?php endif ?>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
                <!-- ./tickets table -->

                </div>
                <div class="tab-pane fade pt-3" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                  <!-- submit Ticket -->
                  <div class="row justify-content-center">
                    <div class="col-12 col-md-10">
                      <div class="card border-0">
                        <div class="card-body">
                          <form id="submitTicketForm" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                            <div class="mb-3">
                              <label for="subject" class="form-label">Subject</label>
                              <input type="text" class="form-control my-input" name="subject" id="subject" placeholder="Enter a subject" required>
                            </div>
                            <div class="mb-3">
                              <label for="ticketUrlPicutre" class="form-label">Photo(Optional)</label>
                              <input name="ticketUrlPicutre[]" type="file" class="form-control my-input" id="ticketUrlPicutre" placeholder="Upload pictures" accept="image/*" multiple required>
                            </div>
                            <div class="file-wrapper mb-3">
                              <p class="text-secondary">Uploaded Pictures</p>
                              <div class="show-filebox d-flex flex-column bg-light p-1" id="uploadedImagesContainer"></div>
                            </div>
                            <div class="mb-3">
                              <label for="description" class="form-label">Description</label>
                              <textarea class="form-control my-input" name="description" id="description" placeholder="Aa" rows="3"></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                              <button id="submitTicketBtn" class="btn btn-success" type="button">Submit</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- ./submit Ticket -->
                </div>

              </div>
            </div>
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

      const ticketUrlPicutre = $("#ticketUrlPicutre");
      const fileWrapper = $("#uploadedImagesContainer");

      ticketUrlPicutre.on("change", function(e) {
        fileWrapper.empty(); // Clear previous images
        filesArray = Array.from(e.target.files); // Store the selected files

        if (filesArray.length > 2) {

          Swal.fire({
            title: 'Ooops...',
            text: "You can only upload 2 photos.",
            icon: 'error',
          })
          ticketUrlPicutre.val('');
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
        ticketUrlPicutre[0].files = dataTransfer.files;
      }


      /* submit button */
        $("#submitTicketBtn").on("click", function(e) {
          e.preventDefault();

          // Create a FormData object to include file inputs
          let formData = new FormData($('#submitTicketForm')[0]);

          Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit this ticket?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
          }).then(result => {
            
            if(result.isConfirmed) {

              $.ajax({
                method: 'POST',
                url: "includes/ajax/ticket-submit.inc.php?function=ticketSubmit",
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
      /* ./ submit button */

    })

    
  </script>

</body>
</html>