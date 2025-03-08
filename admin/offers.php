<?php
session_start();
require_once '../includes/dbh.inc.php';
require_once '../functions.php';
require_once 'includes/admin-validation.php';

// Get the start and end date from the form if available
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

$dateCondition = '';
$queryParams = [];

// Add date filter conditions
if ($startDate && $endDate) {
    $dateCondition .= " AND offers.offer_created_at BETWEEN :start_date AND :end_date";
    $queryParams[':start_date'] = $startDate;
    $queryParams[':end_date'] = $endDate;
} elseif ($startDate) {
    $dateCondition .= " AND offers.offer_created_at >= :start_date";
    $queryParams[':start_date'] = $startDate;
} elseif ($endDate) {
    $dateCondition .= " AND offers.offer_created_at <= :end_date";
    $queryParams[':end_date'] = $endDate;
}

// Fetch offers with date filter
$offers = selectQuery(
    $pdo,
    "SELECT * FROM offers
    INNER JOIN users ON offers.offer_user_id = users.user_id
    WHERE 1=1 $dateCondition
    ORDER BY offers.offer_created_at DESC",
    $queryParams
);

$totalOffers = count($offers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Offers</title>

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
            <h1>OFFERS</h1>
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
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-bag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Itens</span>
                <span class="info-box-number"><?= $totalOffers ?></span>
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
              <a href="offers.php" class="btn btn-secondary" style="margin-top: 30px;">Reset</a>
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
                      <th>ID</th>
                      <th>Title</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($offers as $offer): ?>
                      <tr>
                        <td><?= $offer['offer_random_id'] ?></td>
                        <td><?= $offer['offer_title'] ?></td>
                        <td>
                          <?php if($offer['offer_status'] == 'pending'): ?>
                            <span class="badge badge-secondary">
                              <?= $offer['offer_status'] ?>
                            </span>
                          <?php elseif($offer['offer_status'] == 'accepted'): ?>
                            <span class="badge badge-success">
                              <?= $offer['offer_status'] ?>
                            </span>
                          <?php else: ?>
                            <span class="badge badge-danger">
                              <?= $offer['offer_status'] ?>
                            </span>
                          <?php endif ?>
                        </td>
                        <td><?= $offer['offer_created_at'] ?></td>
                        <td>
                          <a href="offer-view.php?offer_id=<?= $offer['offer_random_id'] ?>" class="btn btn-sm btn-light">View</a>
                        </td>
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
