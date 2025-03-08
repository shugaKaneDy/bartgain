<?php
  session_start();

  $csrfToken = bin2hex(random_bytes(32));
  $_SESSION['csrf_token'] = $csrfToken;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>

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

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/email-verification.css">

  

</head>
<body class="bg-light d-flex justify-content-center align-items-center p-3">

  <main class="container myVerification p-3 bg-white rounded shadow">
    <a class="navbar-brand d-flex justify-content-center align-items-center gap-1 mb-3" href="#">
      <img class="rounded my-logo" src="assets/logo.png" alt="">
      <span class="fw-bold my-text-logo text-success p-0 m-0">BartGain</span>
    </a>

    <div class="card border-0">
      <div class="card-body">
        <h4 class="card-title text-center mb-3">
          Forgot Password?
        </h4>
        <form id="forgotForm" class="mb-3">
          <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
          <div class="mb-3">
            <input id="email" name="email" type="text" class="form-control my-input" placeholder="Enter Your Email">
          </div>
          <div>
            <button id="submitBtn" type="button" class="btn btn-success w-100">Submit</button>
          </div>
        </form>
        <a href="signin.php" class="float-end link link-success">Sign In</a>
      </div>
    </div>
  </main>

  <?php
    include "layouts/spinner-overlay.php"
  ?>
  

  <!-- BT Link script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- SweetAlert2 -->
  <script src="js/sweetalert2/swal.js"></script>


  <script>

    $(document).ready(function() {
      let spinner = $(".spinner-overlay");

      $(document).on("click", '#submitBtn', function() {

        let formData = $('#forgotForm').serializeArray();

        $.ajax({
          url: "includes/ajax/authentication.php?function=forgot-password",
          method: "POST",
          data: formData,
          dataType: "json",
          beforeSend: function () {
            spinner.removeClass("d-none");
          }
        }).done(function(data) {
          if(data.status == 'success') {
            window.location.href = "otp-password.php";
          }
        })


      })

      

    })

  </script>



</body>
</html>