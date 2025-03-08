<?php
session_start();
require_once '../includes/dbh.inc.php';
require_once '../functions.php';
require_once 'includes/admin-validation.php';

// Get start_date and end_date from the query parameters
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Initialize the base query
$query = "SELECT * FROM activity_logs
          INNER JOIN users ON activity_logs.act_log_user_id = users.user_id";

// Initialize the parameters array
$params = [];

// Add conditions for date filtering if provided
if (!empty($startDate) && !empty($endDate)) {
    $query .= " WHERE act_log_created_at BETWEEN :start_date AND :end_date";
    $params = [
        ':start_date' => $startDate,
        ':end_date' => $endDate
    ];
} elseif (!empty($startDate)) {
    $query .= " WHERE act_log_created_at >= :start_date";
    $params = [
        ':start_date' => $startDate
    ];
} elseif (!empty($endDate)) {
    $query .= " WHERE act_log_created_at <= :end_date";
    $params = [
        ':end_date' => $endDate
    ];
}

// Fetch activity logs with the date filter
$activityLogs = selectQuery($pdo, $query, $params);
$totalActivityLogs = count($activityLogs);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Activity Logs</title>

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
            <h1>ACTIVITY LOGS</h1>
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
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clock"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Acitivity Logs</span>
                <span class="info-box-number"><?= $totalActivityLogs ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          
        </div>
        <!-- ./ Overview -->

        <!-- Date Filter Form -->
        <form method="GET" class="mb-3">
          <div class="row">
            <div class="col-md-3">
              <label for="start_date">Start Date</label>
              <input type="date" class="form-control" id="start_date" name="start_date" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>">
            </div>
            <div class="col-md-3">
              <label for="end_date">End Date</label>
              <input type="date" class="form-control" id="end_date" name="end_date" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>">
            </div>
            <div class="col-md-3">
              <button type="submit" class="btn btn-primary" style="margin-top: 30px;">Filter</button>
              <a href="activity-logs.php" class="btn btn-secondary" style="margin-top: 30px;">Reset</a>
            </div>
          </div>
        </form>

        <!-- Tables -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>User ID</th>
                      <th>Type</th>
                      <th>Description</th>
                      <th>Date</th>
                      <th>IP</th>
                      <th>Device</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($activityLogs as $activityLog): ?>
                      <tr>
                        <td>#<?= $activityLog['user_random_id'] ?></td>
                        <td><?= $activityLog['act_log_act_type'] ?></td>
                        <td><?= $activityLog['act_log_description'] ?></td>
                        <td><?= $activityLog['act_log_created_at'] ?></td>
                        <td><?= $activityLog['act_log_ip_add'] ?></td>
                        <td><?= $activityLog['act_log_device'] ?></td>
                        
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- ./Tables -->

        
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
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
  });
</script>

</body>
</html>
