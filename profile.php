<?php
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'functions.php';


  // print_r($selectedRates);
  // exit();

  $totalRating = 0;


  if(!empty($_SESSION['user_details']['user_rate_count'])) {
    $totalRating = $_SESSION['user_details']['user_rating'] / $_SESSION['user_details']['user_rate_count'];
  }
  
  if($totalRating != 5) {
    $totalRating = number_format($totalRating, 1);
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/profile.css">


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
          <h3>Profile</h3>
          <div class="px-3 py-4 bg-white rounded border shadow-sm">
            <ul class="nav nav-pills" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="false">Profile</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link link-sm" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="true">Edit Profile</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Edit Password</button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active mt-3" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                <div class="card border-0 rounded text-center position-relative">
                  <?php if($_SESSION['user_details']['user_is_prem'] == 'Yes'):?>
                    <span class="ribbon bg-success">PRM<i class="bi bi-gem"></i></span>
                  <?php endif?>
                  <!-- Header with background color and title -->
                  <div class="card-header my-bg-success text-white pt-4" style="height: 135px;">
                    <p class="mb-1"><?= $_SESSION['user_details']['fullname'] ?></p>
                    <h5 class="mb-0 fw-bold">ID: 660960590</h5>
                  </div>
                  
                  <!-- User image (centered) -->
                  <div class="card-body position-relative">
                    <div class="position-absolute top-0 start-50 translate-middle">
                      <a href="profile-uploads/<?= $_SESSION['user_details']['profile_picture'] ? $_SESSION['user_details']['profile_picture'] : "default.jpg" ?>">
                        <img src="profile-uploads/<?= $_SESSION['user_details']['profile_picture'] ? $_SESSION['user_details']['profile_picture'] : "default.jpg" ?>" alt="User Avatar" class="rounded-circle border border-2 border-white shadow" width="90" height="90">
                      </a>
                    </div>
                    
                    <!-- Rating information -->
                    <p class="mt-5 mb-1 font-weight-bold h4 fw-bold"><?= number_format($totalRating, 1) ?> <i class="text-warning bi bi-star-fill"></i></p>
                    <p class="text-muted"><?= $_SESSION['user_details']['user_rate_count'] ?> Person rated</p>
                  </div>

                  <div class="px-3 pb-3">
                    <div class="row align-items-center pt-3 px-2">
                      <div class="col-1 text-center">
                        <p class="h5">
                          <i class="bi bi-envelope-at-fill"></i>
                        </p>
                      </div>
                      <div class="col-11 text-start">
                        <p class="h6 text-muted ms-2"><?= $_SESSION['user_details']['email'] ?></p>
                      </div>
                      <div class="col-1 text-center">
                        <p class="h5">
                          <i class="bi bi-house-fill"></i>
                        </p>
                      </div>
                      <div class="col-11 text-start">
                        <p class="h6 text-muted ms-2"><?= $_SESSION['user_details']['address'] ?></p>
                      </div>
                      <div class="col-1 text-center">
                        <p class="h5">
                          <i class="bi bi-geo-alt-fill"></i>
                        </p>
                      </div>
                      <div class="col-11 text-start">
                        <p class="h6 text-muted ms-2"><?= $_SESSION['user_details']['current_location'] ?></p>
                      </div>
                      <div class="col-1 text-center">
                        <p class="h5">
                          <i class="bi bi-phone-fill"></i>
                        </p>
                      </div>
                      <div class="col-11 text-start">
                        <p class="h6 text-muted ms-2"><?= $_SESSION['user_details']['user_contact'] ?></p>
                      </div>
                      <div class="col-1 text-center">
                        <p class="h5">
                          <i class="bi bi-telephone-fill"></i>
                        </p>
                      </div>
                      <div class="col-11 text-start">
                        <p class="h6 text-muted ms-2"><?= $_SESSION['user_details']['user_contact_emergency'] ?></p>
                      </div>
                    </div>
                  </div>

                </div>

              </div>
              <div class="tab-pane fade pt-3" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                
                <div class="row">
                  <div class="col-12 col-md-6 p-1 mb-3">
                    <div class="border py-3 px-2 rounded">
                      <form  id="editProfilePictureForm" enctype="multipart/form-data">
                        <p class="text-center">Change Profile</p>
                        <div class="text-center mb-3">
                          <img id="profilePreview" class="profile-image rounded" src="profile-uploads/<?= $_SESSION['user_details']['profile_picture'] ? $_SESSION['user_details']['profile_picture'] : "default.jpg" ?>" alt="Profile Picture">
                        </div>
                        <div class="form-floating mb-3">
                          <input type="file" class="form-control my-input" id="profilePicture" name="profilePicture" accept="image/*" required>
                          <label for="profilePicture" style="z-index: 0;">Choose Picture</label>
                        </div>
                        <button id="uploadBtn" type="button" class="btn btn-success w-100">Upload</button>

                      </form>
                    </div>
                  </div>
                  <div class="col-12 col-md-6 p-1 mb-3">
                    <div class="border py-3 px-2 rounded">
                      <form action="" id="editProfileInformationForm">
                        <p class="text-center">Change Contact</p>
                        <p class="text-sm m-0 text-muted">Current Contact: <?= $_SESSION['user_details']['user_contact'] ?></p>
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control my-input" id="contact" name="contact" placeholder="09..." required>
                          <label for="contact" style="z-index: 0;">Contact</label>
                        </div>

                        <p class="text-sm m-0 text-muted">Current Emergency Contact: <?= $_SESSION['user_details']['user_contact_emergency'] ?></p>
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control my-input" id="emergencyContact" name="emergencyContact" placeholder="09..." required>
                          <label for="emergencyContact" style="z-index: 0;">Emergency Contact</label>
                        </div>

                        <p class="text-sm m-0 text-muted">Current Emergency Address: <?= $_SESSION['user_details']['address'] ?></p>
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control my-input" id="address" name="address" placeholder="09..." required>
                          <label for="address" style="z-index: 0;">Address</label>
                        </div>
                        <button id="saveBtn" type="button" class="btn btn-success w-100 mt-3">Save Changes</button>
                      </form>
                    </div>
                  </div>
                </div>

              </div>

              <div class="tab-pane fade pt-3" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                <div class="row justify-content-center">
                  <div class="col-12 col-md-8 p-1 mb-3">
                    <div class="border py-3 px-2 rounded">
                      <form action="" id="changePasswordForm">
                        <!-- Current password -->
                        <p class="text-center">Change Password</p>
                        <div class="form-floating mb-3">
                          <input type="password" class="form-control my-input" id="currentPassword" name="currentPassword" placeholder="Current Password" required>
                          <label for="currentPassword" style="z-index: 0;">Current Password</label>
                        </div>
                        <!-- New password -->
                        <div class="form-floating mb-3">
                          <input type="password" class="form-control my-input" id="newPassword" name="newPassword" placeholder="New Password" required>
                          <label for="newPassword" style="z-index: 0;">New Password</label>
                        </div>
                        <!-- Confirm new password -->
                        <div class="form-floating mb-3">
                          <input type="password" class="form-control my-input" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm New Password" required>
                          <label for="confirmNewPassword" style="z-index: 0;">Confirm New Password</label>
                        </div>
                        <button id="changePassBtn" type="button" class="btn btn-success w-100 mt-3">Change Password</button>
                      </form>
                    </div>
                  </div>
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
      
      $('#profilePicture').on('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            $('#profilePreview').attr('src', e.target.result);
          }
          reader.readAsDataURL(file);
        }
      });

      $('#uploadBtn').on('click', function() {
        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to upload this profile picture?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes, upload it!'
        }).then((result) => {
          if (result.isConfirmed) {
            let formData = new FormData($('#editProfilePictureForm')[0]);

            $.ajax({
              method: 'POST',
              url: "includes/ajax/profile-change.inc.php",
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
        });
      });

      $('#saveBtn').on('click', function() {
        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to change contact?",
          
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes, change it!'
        }).then((result) => {
          if (result.isConfirmed) {
            let formData = $('#editProfileInformationForm').serialize();

            $.ajax({
              method: 'POST',
              url: "includes/ajax/profile-change-info.inc.php",
              data: formData,
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
            });

          }
        });
      });

      $('#changePassBtn').on('click', function() {
        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to change your password?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Yes, change it!'
        }).then((result) => {
          if (result.isConfirmed) {
            let formData = $('#changePasswordForm').serialize();

            $.ajax({
              method: 'POST',
              url: "includes/ajax/profile-change-password.inc.php",
              data: formData,
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
                    window.location.href = 'logout.php';
                  }
                });
              }
            });

          }
        });
      });

    })

    
  </script>

</body>
</html>