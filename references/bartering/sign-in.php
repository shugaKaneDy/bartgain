<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in</title>

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
    }
  </style>
</head>
<body class="p-1 pt-3 p-md-5 bg-light-subtle">

  <div class="container">
    <div class="container d-none d-md-flex justify-content-between mb-3">
      <div class="d-flex gap-2">
        <img src="B.png" class="img-fluid" alt="" style="width: 30px; height: 30px;">
        <a class="navbar-brand fs-4 text-success fw-bold" href="index.php">BartGain</a>
      </div>
      <a class="text-decoration-none text-dark" href="sign-up.php">Not a member of BartGain? <span class="ms-3 fw-bold text-success"> Sign up</span></a>
    </div>
    <div class="container">
      <div class="row shadow-sm rounded p-2 bg-white">
        <div class="col-12 border-end col-md-6 p-5 d-none d-md-block">
          <img src="graphics/barter.png" alt="" class="img-fluid">
        </div>
        <div class="col-12 col-md-6 p-1 pb-5 p-md-5">
          <div class="d-flex d-md-none gap-2 mt-3 mb-5 justify-content-center">
            <img src="B.png" class="img-fluid" alt="" style="width: 30px; height: 30px;">
            <a class="navbar-brand fs-4 text-success fw-bold" href="#">BartGain</a>
          </div>
          <p class="fw-bold">Enter your login credentials</p>
          <form id="signInForm">
            <div class="form-floating mb-3">
              <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com">
              <label for="email">Email address</label>
            </div>
            <div class="form-floating">
              <input name="password" type="password" class="form-control" id="password" placeholder="Password">
              <label for="floatingPassword">Password</label>
            </div>
            <input type="hidden" class="address" id="address" name="address"/>
            <button id="btnSignIn" class="btn btn-success mt-3 mb-5 rounded-5 px-5">Sign in</button>
          </form>
          <a class="d-md-none text-decoration-none text-dark pb-5" href="sign-up.php">Not a member of BartGain? <span class="fw-bold text-success"> Sign up</span></a>
        </div>
      </div>
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

    // Function to find coordinates and update form fields
    function findMyCoordinates(callback) {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
          const bdcAPI = `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${position.coords.latitude}&longitude=${position.coords.longitude}`;
          getAPI(bdcAPI, callback);
        }, (err) => {
          alert(err.message);
          callback();
        });
      } else {
        alert("Geolocation is not supported by the browser");
        callback();
      }
    }

    // Function to get API data and update form fields
    function getAPI(bdcAPI, callback) {
      const http = new XMLHttpRequest();
      http.open("GET", bdcAPI);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
          const results = JSON.parse(this.responseText);
          $('#address').val(results.city + ', ' + results.localityInfo.administrative[2].name);
          callback();
        }
      };
    }

    $(document).on('click', '#btnSignIn', function(e) {
      e.preventDefault();

      // Show spinner and disable button
      $(this).addClass('disabled').html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing in...');

      findMyCoordinates(function() {
        let formData = $('#signInForm').serializeArray();
        $.ajax({
          method: 'POST',
          url: "include/ajax/authentication.php?function=sign-in",
          data: formData,
          dataType: "JSON",
          beforeSend: function () {
            // Optional: Add loading spinner or disable button
            $("#btnSignIn").addClass("disabled").html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing in...');
          }
        }).done(function (data) {
          if (data.status === 'success') {
            window.location.href = 'index.php';
            return;
          }

          if (data.status === 'question') {
            Swal.fire({
              icon: data.status,
              title: data.title,
              confirmButtonColor: "#3085d6",
              confirmButtonText: "Verify email!"
            }).then((result) =>{
              if (result.isConfirmed) {
                window.location.href="email-verification.php";
                return;
              }
            });
          }

          if (data.status === 'error') {
            Swal.fire({
              icon: data.status,
              title: data.title,
              showConfirmButton: true
            });
          }

          $("#btnSignIn").removeClass("disabled").html("Sign in");
        });
      });
    });

  </script>

</body>
</html>