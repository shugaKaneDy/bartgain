<?php
  session_start();
  require_once 'dbcon.php';

  if (!empty($_SESSION["user_details"])) {
    $userId = $_SESSION["user_details"]["user_id"];
  } else {
    echo "<script>
            alert('You must login first');
            window.location.href = 'sign-in.php';
          </script>";
    die();
  }

  $query = "SELECT * FROM users WHERE user_id = $userId";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->setFetchMode(PDO::FETCH_OBJ);
  $result = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Responsive table -->
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
    .profile-image {
      width: 170px;
      height: 170px;
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
      .profile-image {
        width: 100px;
        height: 100px;
      }
    }
  </style>
</head>
<body class="bg-light">

  <?php include 'layout/navbar.php'; ?>
  <?php include 'layout/dashboard-aside.php'; ?>
  <?php include "verified-authentication.php"; ?>

  <div class="container mb-5">
    <h3>Edit profile</h3>
    <div>
      <nav class="nav">
        <a class="nav-link text-sm" href="profile.php">Profile Preview</a>
        <a class="nav-link text-primary text-sm" href="profile-edit.php">Edit Profile</a>
        <a class="nav-link text-sm" href="profile-change-password.php">Change Password</a>
      </nav>
    </div>
    <div class="container px-3 py-3 p-md-5 rounded bg-white shadow-sm border">
      <div class="row">
        <div class="col-12 col-md-6 p-1 mb-3">
          <div class="border py-3 px-2">
            <form id="editProfilePictureForm" action="edit-profile-picture.php" method="POST" enctype="multipart/form-data">
              <p class="text-center">Change Profile</p>
              <div class="text-center mb-3">
                <img id="profilePreview" class="profile-image rounded" src="profile-picture/<?= $result->profile_picture ? $result->profile_picture : "default.jpg" ?>" alt="Profile Picture">
              </div>
              <div class="form-floating mb-3">
                <input type="file" class="form-control" id="profilePicture" name="profilePicture" accept="image/*" required>
                <label for="profilePicture" style="z-index: 0;">Choose Picture</label>
              </div>
              <button id="uploadBtn" type="button" class="btn btn-primary w-100">Upload</button>
            </form>
          </div>
        </div>
        <div class="col-12 col-md-6 p-1 mb-3">
          <div class="border py-3 px-2">
            <form id="editProfileInformationForm" action="edit-profile-information.php" method="POST">
              <!-- Additional form fields for profile information -->
               <p class="text-center">Change Contact</p>
              <p class="text-sm m-0 text-muted">Current Contact: <?= $result->user_contact ?></p>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="contact" name="contact" placeholder="09..." required>
                <label for="contact" style="z-index: 0;">Contact</label>
              </div>
              <p class="text-sm m-0 text-muted">Current Emergency Contact: <?= $result->user_emergency_contact ?></p>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="emergencyContact" name="emergencyContact" placeholder="09..." required>
                <label for="emergencyContact" style="z-index: 0;">Emergency Contact</label>
              </div>
              <button id="saveBtn" type="button" class="btn btn-primary w-100 mt-3">Save Changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Responsive data table -->
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="js/plugins/sweetalert2/swal.js"></script>

  <!-- Update location -->
  <?php if (isset($_SESSION['user_details'])): ?>
    <script src="update-location.js"></script>
  <?php endif; ?>

  <script>
    $(document).ready(function() {
      $('#myTable').DataTable({
        responsive: true,
        ordering: false
      });

      $('.forFilterToggle').on('click', function() {
        $('.side-search').toggleClass('d-none');
      });

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
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, upload it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#editProfilePictureForm').submit();
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
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, change it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#editProfileInformationForm').submit();
          }
        });
      });



    });
  </script>

</body>
</html>
