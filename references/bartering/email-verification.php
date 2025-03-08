<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Verification</title>
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
  <style>
    body {
      font-family: Poppins;
      height: 100vh;
    }
    .myVerification {
      width: 50%;
    }
    @media (max-width: 768px) {
      .myVerification {
        width: 100%;
      }
    }
  </style>


</head>
<body class="bg-light-subtle mx-3 d-flex justify-content-center align-items-center gap-5">

  <div class="container myVerification p-3 bg-white rounded shadow">
    <div class="mx-auto d-flex justify-content-center gap-1 mb-5">
      <!-- <div class="rounded-circle bg-success" style="height: 30px; width: 30px;"></div> -->
      <img src="B.png" class="img-fluid" alt="" style="width: 30px; height: 30px;">
      <a class="navbar-brand fs-4 text-success fw-bold" href="#">BartGain</a>
    </div>
    <h3 class="text-center">Verification</h3>
    <hr>
    <div class="bg-light px-3 py-4">
      <form id="verificationForm">
        <div class="mb-3">
          <label for="otpCode">OTP Code</label>
          <input id="otpCode" name="otpCode" type="text" class="form-control" placeholder="Enter OTP code">
        </div>
        <div>
          <button id="verifyBtn" type="button" class="btn btn-success">Verify</button>
          <button id="cancelBtn" type="button" class="btn btn-secondary">Cancel</button>
        </div>
      </form>
    </div>
  </div>
  

<!-- jQuery library from CDNJS (version 3.7.1) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- jQuery library from jQuery CDN (version 3.7.0) - This is redundant, as the previous script includes a newer version -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<!-- DataTables library from CDN DataTables (version 1.13.4) -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- DataTables Bootstrap 5 integration from CDN DataTables (version 1.13.4) -->
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="js/plugins/sweetalert2/swal.js"></script>

<script>
  $(document).ready(function() {
    $("#verifyBtn").on("click", function(e) {
      e.preventDefault();

      var otpCode = $("#otpCode").val();

      $.ajax({
        url: "include/ajax/authentication.php?function=email-verification",
        method: "POST",
        data: {otpCode: otpCode},
        dataType: "json",
        beforeSend: function () {
          // Optional: Add loading spinner or disable button
        }
      }).done(function(data) {
        if(data.status == "error") {
          Swal.fire({
            icon: data.status,
            title: data.title,
            showConfirmButton: true
          });
        } else {
          Swal.fire({
            icon: data.status,
            title: data.title,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sign in now!"
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href="sign-in.php";
            }
          });
        }
      });

    });


    $("#cancelBtn").on("click", function(e) {
      e.preventDefault();
      Swal.fire({
        icon: "question",
        title: "Are you sure you don't want to verify your email?",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No"
      }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'sign-up.php'; // Redirect to sign-up.php
        }
      });

    });

  });
</script>


</body>
</html>