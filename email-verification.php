<?php
  session_start();

  if (isset($_SESSION['time'])) {

    $remainingTime = $_SESSION['time'] - time();
  }
  

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Verification</title>

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
          Verify OTP
        </h4>
        <form id="verificationForm">
          <div class="mb-3">
            <input id="otpCode" name="otpCode" type="text" class="form-control my-input" placeholder="Enter OTP code">
          </div>
          <div class="mb-3 d-flex align-items-center justify-content-between">
            <p class="m-0 my-small-text">
              Time Remaining: 
              <span class="fw-bold timer"></span>
            </p>
            <button class="resendOtpBtn text-decoration-underline text-danger btn my-small-text">
              Resend OTP
            </button>
          </div>
          <div>
            <button id="submitBtn" type="button" class="btn btn-success w-100">Submit</button>
          </div>
        </form>
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



    let timerLeft = <?php echo json_encode($remainingTime); ?> ?? 180;
    let spinner = $(".spinner-overlay");

    let timerInterval = setInterval(() => {
      if(timerLeft > 0) {
        
        // Convert seconds to MM:SS format
        let minutes = Math.floor(timerLeft / 60);
        let seconds = timerLeft % 60;

        $(".timer").html(minutes.toString().padStart(2, '0') + ":" + seconds.toString().padStart(2, '0'));

        timerLeft--; // Decrease timeLeft
      } else {

        clearInterval(timerInterval); // Stop the timer when it reaches 0

        // Show SweetAlert popup for Resend OTP
        Swal.fire({
          icon: 'info',
          title: 'Resend OTP',
          text: 'You can now resend the OTP.',
          confirmButtonText: 'Resend'
        }).then((result) => {
          if (result.isConfirmed) {
            // Logic to resend OTP can be added here
            // location.reload();
            $.ajax({
              method: "POST",
              url: "includes/ajax/authentication.php?function=resend",
              beforeSend: function() {
                spinner.removeClass("d-none");
              },
              success: function() {
                location.reload();
              }
            });
          }
        });

      }
    }, 1000);

    $(document).on("click", "#submitBtn", function(e) {

      e.preventDefault();
      
      let otpCode = $("#otpCode").val();

      $.ajax({
        url: "includes/ajax/authentication.php?function=email-verification",
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
              window.location.href="signin.php";
            }
          });
        }
      });

    });

    $(document).on('click', ".resendOtpBtn", function(e) {

      e.preventDefault();

      $.ajax({
        method: "POST",
        url: "includes/ajax/authentication.php?function=resend",
        beforeSend: function() {
          spinner.removeClass("d-none");
        },
        success: function() {
          location.reload();
        }
      });
      
    });


  </script>



</body>
</html>