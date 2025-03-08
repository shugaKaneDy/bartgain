<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';
  
  $totalPendingVerification = selectQueryFetch(
    $pdo,
    "SELECT COUNT(verification_id) as total_pending FROM verification
    WHERE verification_status = :verificationStatus",
    [
      ":verificationStatus" => "pending"
    ]
  );

  $totalVerifiedUser = selectQueryFetch(
    $pdo,
    "SELECT COUNT(user_id) as total_verified FROM users
    WHERE verified = :verified",
    [
      ":verified" => 'Y'
    ]
  );

  $pendingVerifications = selectQuery(
    $pdo,
    "SELECT * FROM verification
    WHERE verification_status = :verificationStatus
    ORDER BY verification_id DESC",
    [
      ":verificationStatus" => "pending"
    ]
  );

  $historyVerifications = selectQuery(
    $pdo,
    "SELECT * FROM verification
    ORDER BY verification_id DESC",
    []
  );


  // print_r($salesOvertime);
  // exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Verification</title>

  <?php
    include('layouts/top-link.php');
  ?>
  <link rel="stylesheet" href="css/verification.css">
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
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <h1>VERIFICATION</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <!-- Overview -->
        <div class="row">
          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-check"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Verified User</span>
                <span class="info-box-number"><?= $totalVerifiedUser['total_verified'] ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-clock"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number"><?= $totalPendingVerification['total_pending'] ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- ./ Overview -->

        <!-- Tables Pending Verification -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-warning">
                <div class="card-title">Pending Verification</div>
              </div>
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Fullname</th>
                      <th>ID Upload</th>
                      <th>Captured Img</th>
                      <th>Accuracy</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($pendingVerifications as $pendingVerification): ?>
                      <tr>
                        <td><?= $pendingVerification['verification_random_id'] ?></td>
                        <td><?= $pendingVerification['verification_fullname'] ?></td>
                        <td>
                          <img src="../id-uploads/<?= $pendingVerification['verification_id_uploads'] ?>" alt="" class="verification-id-img rounded">
                        </td>
                        <td>
                          <img src="../captured-images/<?= $pendingVerification['verification_capture_image'] ?>" alt="" class="verification-captured-img rounded">
                        </td>
                        <td>
                          <?php if($pendingVerification['verification_percentage'] > 50): ?>
                            <p class="badge badge-success">
                              <?= number_format($pendingVerification['verification_percentage'], 2) ?>%
                            </p>
                          <?php else: ?>
                            <p class="badge badge-warning">
                              <?= number_format($pendingVerification['verification_percentage'], 2) ?>%
                            </p>
                          <?php endif ?>
                        </td>
                        <td><?= date("M d, Y", strtotime($pendingVerification['verification_created_at']))?></td>
                        <td>
                          <a href="verification-view.php?v_id=<?= $pendingVerification['verification_random_id'] ?>" class="btn btn-sm btn-info">view</a>
                        </td>
                      </tr>
                    <?php endforeach?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- ./Tables Pending Verification -->

        <!-- Tables History Verification -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-secondary">
                <div class="card-title">History</div>
              </div>
              <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Fullname</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($historyVerifications as $historyVerification): ?>
                      <tr>
                        <td><?= $historyVerification['verification_random_id'] ?></td>
                        <td><?= $historyVerification['verification_fullname'] ?></td>
                        <td>
                          <?php if($historyVerification['verification_status'] == "pending"):?>
                            <p class="badge badge-warning">
                              <?= $historyVerification['verification_status'] ?>
                            </p>
                          <?php elseif($historyVerification['verification_status'] == "accepted"):?>
                            <p class="badge badge-success">
                              <?= $historyVerification['verification_status'] ?>
                            </p>
                          <?php else:?>
                            <p class="badge badge-danger">
                              <?= $historyVerification['verification_status'] ?>
                            </p>
                          <?php endif?>
                        </td>
                        <td><?= date("M d, Y", strtotime($historyVerification['verification_created_at']))?></td>
                        <td>
                          <a href="verification-view.php?v_id=<?= $historyVerification['verification_random_id'] ?>" class="btn btn-sm btn-info">view</a>
                        </td>
                      </tr>
                    <?php endforeach?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- ./Tables History Verification -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

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
  $('#example2').DataTable({
    "paging": true,
    "ordering": false,
    "responsive": true,
    "buttons": ["copy", "csv", "excel", "pdf", "print"]
  }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

  $('#example3').DataTable({
    "paging": true,
    "ordering": false,
    "responsive": true,
    "buttons": ["copy", "csv", "excel", "pdf", "print"]
  }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
</script>

</body>
</html>
