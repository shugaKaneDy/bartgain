<?php
  require_once '../dbcon.php';
  session_start();
  $id = $_POST["verification_id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Verification</title>
  <link rel="icon" href="../B.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- SweetAlert2 -->
  <script src="../js/plugins/sweetalert2/swal.js"></script>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

  <section>
    <div class="container mt-5 border rounded shadow p-3">
      <a class="text-decoration-none text-secondary" href="verification.php"><i class="bi bi-arrow-left"></i></a>
      <h4 class="text-center">View Verification Details</h4>
      <?php
        $query = "SELECT * FROM verifications INNER JOIN users ON verifications.user_id = users.user_id WHERE verification_id = $id";
        $stmt = $conn->query($query);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $result = $stmt->fetch();
      ?>
      <div class="mb-3">
        <p class="m-0">Verification Id: <?= $result->verification_id ?></p>
        <p class="m-0">Fullname: <?= $result->fullname ?></p>
        <p class="m-0">Birth Date: <?= $result->verification_birth_date ?></p>
        <p class="m-0">Address: <?= $result->address ?></p>
        <p class="m-0">Status: <?= $result->verification_status ?></p>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <p class="m-0">Valid Id</p>
          <img src="../<?= $result->id_picture_path ?>" alt="" style="max-width: 300px; max-height: 200px"/>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <p class="m-0">Captured Image</p>
          <img src="../<?= $result->capture_image_path ?>" alt="" style="max-width: 300px; max-height: 200px">
        </div>
      </div>
      <?php
        if ($result->verification_status == "pending"){
          ?>
            <div class="d-flex gap-3 mt-2">
              <form id="acceptForm" action="verification-accept.php" method="post">
                <input type="hidden" name="verification_id" value="<?= $id ?>">
                <button type="button" id="acceptButton"  class="btn btn-primary">Accept</button>
              </form>
              <button id="rejectButton" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
            </div>
          <?php
        } else {

        }
      ?>
    </div>
  </section>

  <!-- Reject Modal -->
  <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rejectModalLabel">Reason for Rejection</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="confirmRejectForm" action="verification-reject.php" method="post">
            <input type="hidden" name="verification_id" value="<?= $id ?>">
            <div class="mb-3">
              <label for="rejectReason" class="form-label">Reason</label>
              <textarea class="form-control" id="rejectReason" name="reject_reason" rows="3" required></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="confirmRejectButton" type="button" class="btn btn-danger">Reject</button>
        </div>
      </div>
    </div>
  </div>

  <!-- SweetAlert2 Script and Initialization -->
  <script>
    // Wait for the document to be fully loaded
    $(document).ready(function() {
      // Handle reject button click
      $('#rejectButton').click(function() {
        $('#rejectModal').modal('show'); // Show the reject modal
      });

      // Handle confirm reject button click inside modal
      $('#confirmRejectButton').click(function() {
        // Show SweetAlert2 confirmation
        Swal.fire({
          title: 'Are you sure?',
          text: 'You are about to reject this verification request. Please provide a reason below.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Reject',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            // If confirmed, submit the form
            $('#confirmRejectForm').submit();
          }
        });
      });

      // Handle accept button click
      $('#acceptButton').click(function() {
        // Show SweetAlert2 confirmation
        Swal.fire({
          title: 'Accept Verification?',
          text: 'Are you sure you want to accept this verification request?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Accept'
        }).then((result) => {
          if (result.isConfirmed) {
            // Submit the accept form
            $('#acceptForm').submit();
          }
        });
      });

    });
  </script>

</body>
</html>
