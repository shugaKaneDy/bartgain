<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';

  $totalUserReported = selectQueryFetch(
    $pdo,
    "SELECT COUNT(report_id) as total_user_report FROM reports
    WHERE report_status = :reportStatus
    AND report_type = :reportType",
    [
      ":reportStatus" => "pending",
      ":reportType" => "user"
    ]
  );

  $reportedUsers = selectQuery(
    $pdo,
    "SELECT * FROM reports
    INNER JOIN users ON reports.report_user_id = users.user_id
    WHERE report_type = 'user'
    ORDER BY report_id DESC",
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
  <title>Adm | Reported User</title>

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
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <h1>Reported User</h1>
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

          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user"></i></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pending User Reported</span>
                <span class="info-box-number"><?= $totalUserReported['total_user_report'] ?></span>
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
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Status</th>
                      <th>Created</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($reportedUsers as $reportedUser): ?>
                      <tr>
                        <td>
                          <a href="report-user-view.php?ru_id=<?= $reportedUser['report_random_id'] ?>">
                            <?= $reportedUser['report_random_id'] ?>
                          </a>
                        </td>
                        <td>
                          <?= $reportedUser['fullname'] ?>
                        </td>
                        <td>
                          <?= $reportedUser['report_category'] ?>
                        </td>
                        <td>
                          <?php if($reportedUser['report_status'] == "pending"): ?>
                            <span class="badge badge-secondary">pending</span>
                          <?php elseif($reportedUser['report_status'] == "resolved"): ?>
                            <span class="badge badge-success">resolved</span>
                          <?php else: ?>
                            <span class="badge badge-danger">dismissed</span>
                          <?php endif ?>
                        </td>
                        <td>
                          <?= date("M d, Y", strtotime($reportedUser['report_created_at']))?>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- ./Tables Pending Verification -->

        
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
  $(document).ready(function() {

    $('#example2').DataTable({
      "paging": true,
      "ordering": false,
      "responsive": true,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
  });
</script>

</body>
</html>
