<?php
session_start();
require_once '../includes/dbh.inc.php';
require_once '../functions.php';
require_once 'includes/admin-validation.php';

// Get the start and end date from the form if available
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Build the WHERE clause dynamically based on the date range
$dateCondition = "WHERE payments.payment_type = 'boost' 
                  AND payments.payment_status = 'paid'";

if ($startDate && $endDate) {
    $dateCondition .= " AND payments.payment_created_at BETWEEN :start_date AND :end_date";
} elseif ($startDate) {
    $dateCondition .= " AND payments.payment_created_at >= :start_date";
} elseif ($endDate) {
    $dateCondition .= " AND payments.payment_created_at <= :end_date";
}

// Prepare the query with dynamic date filtering
$sql = "SELECT * FROM payments
        INNER JOIN users ON payments.payment_user_id = users.user_id
        $dateCondition
        ORDER BY payments.payment_id DESC";

// Execute the query with parameters if date range is provided
$params = [];
if ($startDate && $endDate) {
    $params = ['start_date' => $startDate, 'end_date' => $endDate];
} elseif ($startDate) {
    $params = ['start_date' => $startDate];
} elseif ($endDate) {
    $params = ['end_date' => $endDate];
}

$boosts = selectQuery($pdo, $sql, $params);
$totalBoostAmount = 0;
foreach ($boosts as $boost) {
  $totalBoostAmount += $boost['payment_amount'];
}
// Total count after applying filters
$totalBoost = count($boosts);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Sale Boost</title>

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
            <h1>BOOST SALE</h1>
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
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-rocket"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Boosts Availed</span>
                <span class="info-box-number"><?= $totalBoost ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-bill-wave-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Boost Sale</span>
                <span class="info-box-number font-weight-bold text-success">₱ <?= $totalBoostAmount ?></span>
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
              <a href="sale-boost.php" class="btn btn-secondary" style="margin-top: 30px;">Reset</a>
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
                      <th>Name</th>
                      <th>Days</th>
                      <th>Amount</th>
                      <th>Type</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($boosts as $boost): ?>
                      <tr>
                        <td><?= $boost['fullname'] ?></td>
                        <td><?= $boost['payment_num'] ?> days</td>
                        <td class="text-success font-weight-bold">₱ <?= number_format($boost['payment_amount'],2) ?></td>
                        <td>
                          <?php if($boost['payment_paid_type'] == 'gcash'): ?>
                            <span class="badge badge-primary">
                              <?= $boost['payment_paid_type'] ?>
                            </span>
                          <?php else: ?>
                            <span class="badge badge-secondary">
                              <?= $boost['payment_paid_type'] ?>
                            </span>
                          <?php endif ?>
                        </td>
                        <td><?= date("M d, Y", strtotime($boost['payment_created_at'])) ?></td>
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
