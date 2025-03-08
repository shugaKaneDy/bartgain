<?php
  session_start();
  require_once "dbcon.php";

  if(!empty($_SESSION["user_details"])) {
    $userId = $_SESSION["user_details"]["user_id"];
  } else {
    ?>
      <script>
        alert("You must login first");
        window.location.href = "sign-in.php"
      </script>
    <?php
    die();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verification</title>

  <link rel="icon" href="B.png">

  <!-- Required library for webcam -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.24/webcam.js"></script>

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

  <!-- General CSS -->
  <!-- <link rel="stylesheet" href="css/general.css"> -->

  <style>
    

    @media (max-width: 756px) {
      .after_capture_frame {
      width: 100%;
      max-width: 180px;
      height: 250px;
      }
    }

  </style>

</head> 
<body class="p-1 pt-3 p-md-5 bg-light-subtle">



  <div class="container">
    <div class="container d-flex justify-content-between mb-3">
      <div class="d-flex gap-2">
        <img src="B.png" class="img-fluid" alt="" style="width: 30px; height: 30px;">
        <a class="navbar-brand fs-4 text-success fw-bold" href="index.php">BartGain</a>
      </div>
      <a href="itemplace.php">Browse Item</a>
    </div>
    <div class="container mb-3">
      <a href="verification.php">Verification</a>
      <a href="verification-history.php">Verification History</a>
    </div>
    <?php
      $query = "SELECT * FROM verifications WHERE user_id = $userId AND verification_status = 'pending'";
      $stmt = $conn->prepare($query);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_OBJ);

      if($result) {
        ?>
          <div class="container shadow-sm rounded p-2 bg-white">
            <h4>Current Pending Request</h4>
            <div class="mb-3">
              <div class="border rounded shadow-sm px-3 py-3">
                <p class="m-0">Verification id: <?= $result->verification_id ?></p>
                <p class="m-0">Application Date: <?= $result->verification_created_at ?></p>
                <p class="m-0">Birth Date: <?= $result->verification_birth_date ?></p>
                <div class="row mt-5">
                  <div class="col-12 col-md-6 mb-5">
                    <p class="m-0">Your captured image</p>
                    <img src="<?= $result->capture_image_path ?>" alt="" style="max-width: 300px; max-height: 150px;">
                  </div>
                  <div class="col-12 col-md-6 mb-5">
                    <p class="m-0">Your Valid Id</p>
                    <img src="<?= $result->id_picture_path ?>" alt="" style="max-width: 300px; max-height: 150px;">
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php
      } else {
        ?>
          <div class="container shadow-sm rounded p-2 bg-white">
            <div class="d-flex d-md-none gap-2 mt-3 mb-5 justify-content-center">
              <div class="rounded-circle bg-success" style="height: 30px; width: 30px;"></div>
              <a class="navbar-brand fs-4 text-success fw-bold" href="#">BartGain</a>
            </div>
            <h2 class="text-center fw-bold my-3 ">Verification</h2>
            <form id="signInForm" enctype="multipart/form-data" method="post" action="user/save_verification.php">
              <div class="row ">
                <div class="col-12 border-end col-md-6 pb-5 p-md-5">
                  <p class="fw-bold">Complete the form</p>
                  <div class="form-floating mb-3">
                    <input name="birthDate" type="date" class="form-control" id="birthDate" placeholder="Birth date" required>
                    <label for="birthDate">Date of Birth</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input name="id_picture" type="file" class="form-control" id="id_picture" placeholder="Upload ID Picture" required>
                    <label for="id_picture">Upload ID Picture</label>
                  </div>
                </div>
                <div class="col-12 col-md-6 p-1 p-md-5">
                  <div class="p-1" align="center">
                    <p class="fw-bold m-0">Capture live photo</p>
                    <div id="my_camera" class="pre_capture_frame"></div>
                    <input type="hidden" name="captured_image_data" id="captured_image_data">
                    <button type="button" class="btn btn-light border my-3" onClick="take_snapshot()"><i class="bi bi-camera"></i></button>
                  </div>
                  <div id="results" align="center">
                    <img style="width: 350px;" class="after_capture_frame" src="image_placeholder.jpg" />
                  </div>
                  <button type="submit" class="btn btn-success mt-3 px-5">Submit</button>
                </div>
                <a class="d-md-none my-5" href="itemplace.php">Browse Item</a>
              </div>
            </form>
          </div>
        <?php
      }
    ?>

  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

  <?php
    if($result) {
      ?>
        <script>
          $(document).ready( function () {
            $('#myTable'). DataTable();
          });
        </script>
      <?php
    } else {
      ?>
        <script>
          // Configure a few settings and attach camera 350x287
          
          var isMobile = window.innerWidth <= 768;
          if (isMobile) {
            Webcam.set({
              width: 180,
              height: 250,
              image_format: 'jpeg',
              jpeg_quality: 90
            });
          } else {
            Webcam.set({
              width: 350,
              height: 287,
              image_format: 'jpeg',
              jpeg_quality: 90
            });
          }

          Webcam.attach('#my_camera');
        
          function take_snapshot() {
            Webcam.snap(function(data_uri) {
              document.getElementById('results').innerHTML = 
              '<img class="after_capture_frame" src="'+data_uri+'"/>';
              $("#captured_image_data").val(data_uri);
            });    
          }

          $("#signInForm").on("submit", function(event) {
            event.preventDefault(); // Prevent the default form submission
            var formData = new FormData(this);
            var base64data = $("#captured_image_data").val();

            if (base64data === "") {
              alert("Please Capture an image");
              return;
            }

            formData.append('captured_image_data', base64data);

            $.ajax({
              type: "POST",
              url: "capture_image_upload.php",
              data: formData,
              processData: false,
              contentType: false,
              success: function(response) {
                alert("Apllication successful. Wait for the admin to verify your acount");
                window.location.href = "index.php";
              }
            });
          });
        </script>
      <?php
    }
  ?>

</body>
</html>