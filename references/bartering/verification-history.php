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
    <div class="container shadow-sm rounded p-2 bg-white">
      <h4>Verification History</h4>
      <div class="container mb-3">
        <div class="table-responsive border p-4 border shadow-sm rounded">
          <table id="myTable" class="w-100 table table-striped">
            <thead>
              <th>Verificaiton Id</th>
              <th>Verification Application</th>
              <th>Status</th>
              <th>Action</th>
            </thead>
            <tbody>
              <?php
                $allVerificationQuery = "SELECT * FROM verifications WHERE user_id = $userId ORDER BY verification_id DESC";
                $allVerificationStmt = $conn->prepare($allVerificationQuery);
                $allVerificationStmt->execute();
                $allVerificationResults = $allVerificationStmt->fetchAll(PDO::FETCH_OBJ);
                
                foreach($allVerificationResults as $row) {
                  ?>
                    <tr>
                      <td><?= $row->verification_id ?></td>
                      <td><?= $row->verification_created_at ?></td>
                      <td><?= $row->verification_status ?></td>
                      <td>
                        <button type="button" class="viewModal btn btn-sm btn-secondary" data-id="<?= $row->verification_id ?>">View</button>
                      </td>
                    </tr>

                  <?php
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Verification Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modalBody">
          <!-- Modal content will be dynamically populated here -->
        </div>
      </div>
    </div>
  </div>



  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready( function () {
      $('#myTable'). DataTable({
        ordering: false
      });

      // View Modal click handler
      $(".viewModal").on("click", function() {
        var verificationId = $(this).data("id");

        // AJAX request to fetch verification details
        $.ajax({
          url: 'get_verification_details.php', // Replace with your PHP script to fetch verification details
          method: 'POST',
          data: { verificationId: verificationId },
          success: function(response) {
            $('#modalBody').html(response); // Update modal body with fetched data
            $('#viewModal').modal('show'); // Show the modal
          },
          error: function(xhr, status, error) {
            console.error('Error fetching verification details');
            console.log(xhr.responseText);
          }
        });
      });

    });

    $(".viewModal").on("click", function () {
      console.log($(this).val());
    });
  </script>

</body>
</html>