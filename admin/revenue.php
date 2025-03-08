<?php
session_start();
require_once '../includes/dbh.inc.php';
require_once '../functions.php';
require_once 'includes/admin-validation.php';

// Get the start and end date from the form if available
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Define query parameters for filtering
$dateConditionsExpenses = '';
$dateConditionsPayments = '';
$params = [];

if (!empty($startDate)) {
    $dateConditionsExpenses .= " AND expense_created_at >= :start_date";
    $dateConditionsPayments .= " AND payment_created_at >= :start_date";
    $params['start_date'] = $startDate;
}
if (!empty($endDate)) {
    $dateConditionsExpenses .= " AND expense_created_at <= :end_date";
    $dateConditionsPayments .= " AND payment_created_at <= :end_date";
    $params['end_date'] = $endDate;
}

// Fetch filtered and ordered data
$revenues = selectQuery(
    $pdo,
    "SELECT 
        'expense' AS type,
        expense_amount AS amount,
        expense_created_at AS date
    FROM expenses
    WHERE 1=1 $dateConditionsExpenses

    UNION ALL

    SELECT 
        payment_type AS type, -- 'boost' or 'premium'
        payment_amount AS amount,
        payment_created_at AS date
    FROM payments
    WHERE payment_status = 'paid' $dateConditionsPayments

    ORDER BY date DESC",
    $params
);

// Initialize totals
$totalSales = 0;
$totalExpense = 0;
$totalRevenue = 0;

// Calculate the totals
foreach ($revenues as $revenue) {
    if ($revenue['type'] == 'expense') {
        $totalExpense += $revenue['amount']; // Subtract expenses
    } else {
        $totalSales += $revenue['amount']; // Add revenue (sales)
    }
}

// Calculate totalSales (Revenue - Expenses)
$totalRevenue = $totalSales - $totalExpense;


// echo "<pre>";
// print_r($revenues);
// echo "</pre>";
// exit;
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Revenue</title>

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
            <h1>REVENUE</h1>
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
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-bill-wave-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Sales</span>
                <span class="info-box-number">₱ <?= $totalSales ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          
          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-wallet"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Expense</span>
                <span class="info-box-number">₱ <?= $totalExpense ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-coins"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Revenue</span>
                <span class="info-box-number font-weight-bold">₱ <?= $totalRevenue ?></span>
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
              <a href="revenue.php" class="btn btn-secondary" style="margin-top: 30px;">Reset</a>
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
                      <th>Type</th>
                      <th>Amount</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($revenues as $revenue): ?>
                        <?php if($revenue['type'] == 'expense'): ?>
                          <tr>
                            <td>
                              <span class="badge badge-danger">
                                <?= $revenue['type'] ?>
                              </span>
                            </td>
                            <td class="text-danger font-weight-bold">
                              ₱ -<?= $revenue['amount'] ?>
                            </td>
                            <td>
                              <?= date("M d, Y", strtotime($revenue['date'])) ?>
                            </td>
                          </tr>
                        <?php else: ?>
                          <tr>
                            <td>
                              <span class="badge badge-success">
                                <?= $revenue['type'] ?>
                              </span>
                            </td>
                            <td class="text-success font-weight-bold">
                              ₱ +<?= $revenue['amount'] ?>
                            </td>
                            <td>
                              <?= date("M d, Y", strtotime($revenue['date'])) ?>
                            </td>
                          </tr>
                        <?php endif ?>
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
