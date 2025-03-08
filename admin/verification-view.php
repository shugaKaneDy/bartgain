<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';

  if(isset($_GET['v_id'])) {

    if(empty($_GET['v_id'])) {
      header("Location: verification.php");
    } else {
      $vId = $_GET['v_id'];
      $verificationInfo = selectQueryFetch(
        $pdo,
        "SELECT * FROM verification
        WHERE verification_random_id = :vId",
        [
          ":vId" => $vId
        ]
      );
    }

  } else {
    header("Location: verification.php");
  }

  $rejectReasonsForVerification = [
    "Blurry or unclear ID photo.",
    "Invalid ID type.",
    "Expired ID.",
    "Mismatch between ID and selfie.",
    "Missing required information on ID.",
    "Tampered or fake ID.",
    "Duplicate submission.",
    "Other."
];


  // print_r($salesOvertime);
  // exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | View Verification</title>

  <?php
    include('layouts/top-link.php');
  ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake rounded" src="../assets/logo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php
    include('layouts/nav.php');
  ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
    include('layouts/aside.php');
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">View Verification</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="verification.php">Verification</a></li>
                <li class="breadcrumb-item active">View</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <!-- Overview -->
         <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="card-title">Information</div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-4 mb-3 d-flex justify-content-center flex-column align-items-center">
                    <div>
                      <p class="m-0 font-weight-bold">
                        Front ID
                      </p>
                      <img src="../id-uploads/<?= $verificationInfo['verification_id_uploads'] ?>" alt="" class="img-fluid rounded">
                    </div>
                    <div>
                      <p class="m-0 font-weight-bold">
                        Back ID
                      </p>
                      <img src="../id-back-uploads/<?= $verificationInfo['verification_back_id_uploads'] ?>" alt="" class="img-fluid rounded">
                    </div>
                  </div>
                  <div class="col-12 col-md-8 mb-3 border p-2 rounded bg-light">
                    <p class="h5 font-weight-bold">
                      ID Type: 
                      <span class="font-weight-normal">
                        <?= $verificationInfo['verification_card_type'] ?>
                      </span>
                    </p>
                    <p class="font-weight-bold m-0">
                      Status: 
                      <?php if($verificationInfo['verification_status'] == "pending"): ?>
                        <span class="text-warning">
                          <?= $verificationInfo['verification_status'] ?>
                        </span>
                      <?php elseif($verificationInfo['verification_status'] == "accepted"): ?>
                        <span class="text-success">
                          <?= $verificationInfo['verification_status'] ?>
                        </span>
                      <?php else: ?>
                        <span class="text-danger">
                          <?= $verificationInfo['verification_status'] ?>
                        </span>
                      <?php endif ?>
                    </p>
                    <?php if($verificationInfo['verification_status'] == "rejected"): ?>
                      <p class="font-weight-bold m-0">
                        Reject Reason: 
                        <span class="font-weight-normal text-danger">
                          <?= $verificationInfo['verification_reject_reason'] ?>
                        </span>
                      </p>
                    <?php endif ?>
                    <p class="font-weight-bold m-0">
                      Last Name: 
                      <span class="font-weight-normal text-muted">
                        <?= $verificationInfo['verification_lastname'] ?>
                      </span>
                    </p>
                    <p class="font-weight-bold m-0">
                      First Name: 
                      <span class="font-weight-normal text-muted">
                        <?= $verificationInfo['verification_firstname'] ?>
                      </span>
                    </p>
                    <p class="font-weight-bold m-0">
                      Middle Name: 
                      <span class="font-weight-normal text-muted">
                        <?= $verificationInfo['verification_middlename'] ?>
                      </span>
                    </p>
                    <p class="font-weight-bold m-0">
                      Birth Date: 
                      <span class="font-weight-normal text-muted">
                        <?= date("M d, Y", strtotime($verificationInfo['verification_birth_date']))?>
                      </span>
                    </p>
                    <p class="font-weight-bold m-0">
                      Address: 
                      <span class="font-weight-normal text-muted">
                        <?= $verificationInfo['verification_address'] ?>
                      </span>
                    </p>
                    <p class="font-weight-bold m-0">
                      Date Submitted: 
                      <span class="font-weight-normal text-muted">
                        <?= date("M d, Y", strtotime($verificationInfo['verification_created_at']))?>
                      </span>
                    </p>
                  </div>
                  <div class="col-12 col-md-4 mb-3 d-flex justify-content-center align-items-center">
                    <img src="../sup-doc-uploads/<?= $verificationInfo['verification_sup_doc'] ?>" alt="" class="img-fluid rounded">
                  </div>
                  <div class="col-12 col-md-8 mb-3 d-flex flex-column justify-content-center align-items-center bg-light rounded">
                      <p class="h5 font-weight-bold text-muted m-0">Supporting Document:</p>
                      <p><?= $verificationInfo['verification_sup_doc_type'] ?></p>
                  </div>
                  <div class="col-12 col-md-4 mb-3 d-flex justify-content-center align-items-center">
                    <img src="../captured-images/<?= $verificationInfo['verification_capture_image'] ?>" alt="" class="img-fluid rounded">
                  </div>
                  <div class="col-12 col-md-8 mb-3 d-flex flex-column justify-content-center align-items-center bg-light rounded">
                      <p class="h4 font-weight-bold text-muted m-0">ACCURACY:</p>
                      <?php if($verificationInfo['verification_percentage'] > 80):?>
                        <p class="font-weight-bold text-success m-0" style="font-size: 3rem">
                          <?= number_format($verificationInfo['verification_percentage'],2) ?>%
                        </p>
                      <?php else:?>
                        <p class="font-weight-bold text-warning m-0" style="font-size: 3rem">
                          <?= number_format($verificationInfo['verification_percentage'],2) ?>%
                        </p>
                      <?php endif?>
                  </div>
                </div>
              </div>
              <?php if($verificationInfo['verification_status'] == 'pending'): ?>
                <div class="card-footer">
                  <div class="d-flex justify-content-center justify-content-md-end">
                    <button class="btn btn-danger px-5 mx-2"
                    data-toggle="modal" data-target="#exampleModal"
                    >Reject</button>
                    <button id="acceptVerificationButton" class="btn btn-success px-5 mx-2">Accept</button>
                  </div>
                </div>
              <?php endif ?>
            </div>
          </div>
         </div>
        <!-- ./ Overview -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Reject Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reject Verification</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="rejectForm">
              <input type="hidden" name="vId" value="<?= $vId ?>">
              
              <div class="form-group mb-3">
                  <label for="rejectReasonSelect">Reject Reason</label>
                  <select class="form-control select2" name="rejectReason" id="rejectReasonSelect" style="width: 100%;">
                      <?php foreach ($rejectReasonsForVerification as $reason): ?>
                          <option value="<?= htmlspecialchars($reason) ?>"><?= htmlspecialchars($reason) ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
              
              <div class="form-group mb-3 otherReasonDiv" style="display: none;">
                  <label for="otherRejectReason">Other Reason</label>
                  <textarea class="form-control" name="otherRejectReason" id="otherRejectReason" rows="3" placeholder="Specify your reason"></textarea>
              </div>
          </form>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button id="rejectVerificationBtn" type="button" class="btn btn-danger">Reject</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php
 include("layouts/bottom-link.php")
?>

<script>
  $(document).ready(function() {
    $('.select2').select2();

    $('#rejectReasonSelect').on('change', function () {
      if ($(this).val() === "Other.") {
          $('.otherReasonDiv').show(); // Show the custom input field
      } else {
          $('.otherReasonDiv').hide(); // Hide the custom input field
          $('#otherRejectReason').val(''); // Clear the textarea when hidden
      }
    });

    

    $(document).on('click', '#acceptVerificationButton', function(e) {
      Swal.fire({
        title: "Are you sure?",
        text: "You want to accept this verification?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#27a844",
        confirmButtonText: "Yes!"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: 'POST',
            url: "includes/ajax/verification-accept.inc.php",
            data: {
              vId: <?= json_encode($vId) ?>
            },
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
                  window.location.href = 'verification.php';
                }
              });
            }

          })
        }
      });
    })

    $(document).on('click', '#rejectVerificationBtn', function(e) {
      Swal.fire({
        title: "Are you sure?",
        text: "You want to reject this verification?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3546",
        confirmButtonText: "Yes, reject it!"
      }).then((result) => {
        if (result.isConfirmed) {
          let formData = $('#rejectForm').serializeArray();
          $.ajax({
            method: 'POST',
            url: "includes/ajax/verification-reject.inc.php",
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
                  window.location.href = 'verification.php';
                }
              });
            }

          })
        }
      });
    })
  })
</script>

</body>
</html>
