<?php
  session_start();
  if(isset($_SESSION['user_details'])) {
    header("Location: itemplace.php");
    exit;
  }

  $csrfToken = bin2hex(random_bytes(32));
  $_SESSION['csrf_token_up'] = $csrfToken;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign up</title>

  <!-- logo -->
  <link rel="icon" href="assets/logo.png">

  <!-- BT  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/signup.css">


  
</head>
<body class="bg-light d-flex justify-content-center align-items-center pt-3 pb-5">

  <main class="container-lg">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-end mb-3 d-none d-md-flex">
      <a href="index.php" class="d-flex gap-2 align-items-center text-decoration-none">
        <img src="assets/logo.png" class="my-logo">
        <h3 class="m-0 my-text-logo text-success">BartGain</h3>
      </a>

      <a href="signin.php" class="text-decoration-none">
        <span class="text-dark">
          Already a BartGain Member?
        </span>
        <span class="fw-bold text-success ms-3">
          Sign in
        </span>
      </a>
    </div>

    <!-- Form -->
    <div class="row g-0 rounded bg-light shadow-sm">
      <div class="col-12 col-md-6 border-end p-5 d-flex flex-column align-items-center justify-content-center d-none d-md-flex">
        <p class="m-0 fs-4 fw-bold fst-italic">
          BARTER FOR A BETTER TOMMOROW!
        </p>
        <img src="assets/side-graphic.png" style="width: 350px; height: 350px;">
      </div>

      <div class="col-12 col-md-6 p-3 pb-5 p-md-5">
        <div class="d-flex d-md-none gap-2 mt-3 mb-5 justify-content-center">
          <img src="assets/logo.png" class="my-logo">
          <a class="navbar-brand fs-4 text-success my-text-logo" href="index.php">BartGain</a>
        </div>
        <p class="text-success h3 mb-3 fw-bold">START TRADING TODAY</p>
        <p>Sign up and find your best trading partner</p>

        <!-- Form -->
        <form id="signUpForm" name="signUpForm">
          <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
          <div class="form-floating mb-3">
            <input name="fullname" type="text" class="form-control my-input" id="fullname"  placeholder="Fullname">
            <label for="fullname">Full Name</label>
          </div>
          <div class="form-floating mb-3">
            <input name="email" type="email" class="form-control my-input" id="email"  placeholder="Email">
            <label for="email">Email address</label>
          </div>
          <div class="form-floating mb-3">
            <input name="password" type="password" class="form-control my-input" id="password" placeholder="Password">
            <label for="password">Password</label>
          </div>
          <p class="my-2 small-text text-muted">(Password must be at least 6 characters long and contain both letters and numbers.)</p>
          <div class="form-floating mb-3">
            <input name="confirmPassword" type="password" class="form-control my-input" id="confirmPassword" placeholder="Confirm Password">
            <label for="confirmPassword">Confirm Password</label>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="checkAgree" value="set" id="agreeTerms" required>
            <label class="form-check-label" for="agreeTerms">
              I agree to the <a href="terms-and-conditions.php" target="_blank">Terms and Conditions</a>
            </label>
          </div>

          <input type="hidden" name="currentLoc" id="currentLoc"/>
          <input type="hidden" name="lng" id="lng"/>
          <input type="hidden" name="lat" id="lat"/>
          <button id="signUpBtn" class="btn btn-success mt-3 mb-5 rounded-5 px-5">Sign up</button>
        </form>


        <a class="d-md-none text-decoration-none text-dark pb-5" href="signin.php">Already a BartGain Member? <span class=" fw-bold text-success text-nowrap"> Sign in</span></a>
      </div>


    </div>
  </main>

  <?php
    include_once "layouts/bg-cirlces.php";
  ?>

  
  
  <!-- BT Link script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- SweetAlert2 -->
  <script src="js/sweetalert2/swal.js"></script>

  <!-- Error location function -->
  <script src="js/functions/error-location.js"></script>

  <script>

    $(document).on('click', '#signUpBtn', function(e) {

      e.preventDefault();

      // Show spinner and disable button
      $(this).addClass('disabled').html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing up...');

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var lat = position.coords.latitude;  // Get latitude
          var lon = position.coords.longitude; // Get longitude

          $.ajax({
            method: "GET",
            url: `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}`,
            success: function(res) {

              $('#currentLoc').val(res.city + ', ' + res.localityInfo.administrative[2].name);
              $('#lat').val(res.latitude);
              $('#lng').val(res.longitude);

              let formData = $('#signUpForm').serializeArray();

              $.ajax({

                method: 'POST',
                url: "includes/ajax/authentication.php?function=signup",
                data: formData,
                dataType: "JSON",
                beforeSend: function () {
                    // Optional: Add loading spinner or disable button
                    $("#signUpBtn").addClass("disabled").html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing up...');
                }

              }).done(function (data) {

                if(data.status == "error") {

                  Swal.fire({
                    icon: data.status,
                    title: data.title,
                    showConfirmButton: true
                  });
                  $("#signUpBtn").removeClass("disabled").html("Sign up");
                } else {

                  Swal.fire({
                    icon: data.status,
                    title: data.title,
                    confirmButtonText: "Verify email!"
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href="email-verification.php";
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
        // Handle browsers that don't support geolocation
        Swal.fire({
          title: "Geolocation Not Supported",
          text: "Your browser does not support geolocation. Please use a different browser.",
          icon: "error",
        });
        console.log("Geolocation is not supported by this browser.");
      }

    });
  </script>

</body>
</html>