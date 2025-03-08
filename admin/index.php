<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';

  $totalUser = selectQueryFetch(
    $pdo,
    "SELECT COUNT(user_id) as total_user FROM users
    WHERE user_status = :userStatus",
    [
      ":userStatus" => "active"
    ]
  );

  $totalItemsListed = selectQueryFetch(
    $pdo,
    "SELECT COUNT(item_id) as total_item FROM items",
    [
    ]
  );

  $totalSales = selectQueryFetch(
    $pdo,
    "SELECT SUM(payment_amount) as total_sales FROM payments
    WHERE payment_status = :paymentStatus",
    [
      ":paymentStatus" => "paid",
    ]
  );


  $usersOvertime = selectQuery(
    $pdo,
    "SELECT DATE_FORMAT(user_created_at, '%Y-%m') as month, COUNT(user_id) as registrations 
    FROM users 
    GROUP BY DATE_FORMAT(user_created_at, '%Y-%m') 
    ORDER BY month",
    [
      
    ]
  );

  $salesOvertime = selectQuery(
    $pdo,
    "SELECT DATE_FORMAT(payment_paid_at, '%Y-%m') as month, SUM(payment_amount) as paids 
    FROM payments
    WHERE payment_status = :paymentStatus
    GROUP BY DATE_FORMAT(payment_created_at, '%Y-%m') 
    ORDER BY month",
    [
      ":paymentStatus" => "paid",
    ]
  );

  // print_r($salesOvertime);
  // exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Dashboard</title>

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
            <h1>Dashboard</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <!-- Overview -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= $totalItemsListed['total_item'] ?></h3>

                  <p>Item Listed</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="items.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>₱<?= number_format($totalSales['total_sales'],2) ?></h3>

                  <p>Total Sales</p>
                </div>
                <div class="icon">
                  <i class="fas fa-money-bill-wave-alt"></i>
                </div>
                <a href="revenue.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?= $totalUser['total_user'] ?></h3>

                  <p>Total Users</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>10</h3>

                  <p>Reported</p>
                </div>
                <div class="icon">
                  <i class="fas fa-flag"></i>
                </div>
                <a href="report-item.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        <!-- ./ Overview -->

        <!-- Graphs -->
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Registered User</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg"><?= $totalUser['total_user'] ?></span>
                    <span>Users Over Time</span>
                  </p>
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="visitors-chart" height="200"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Sales</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg">₱<?= $totalSales['total_sales'] ?></span>
                  </p>
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="sales-chart" height="200"></canvas>
                </div>

              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- ./Graphs -->
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
  $(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  var $salesChart = $('#sales-chart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      // labels: ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      labels: [
        <?php foreach($salesOvertime as $saleOvertime):?>
          <?= json_encode(strtoupper(date("M", strtotime($saleOvertime['month'])))) ?>,
        <?php endforeach?>
      ],
      datasets: [
        {
          backgroundColor: '#27a844',
          borderColor: '#27a844',
          data: [
            <?php foreach($salesOvertime as $saleOvertime):?>
              <?= json_encode($saleOvertime['paids']) ?>,
            <?php endforeach?>
          ]
        },
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return '₱' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })

  var $visitorsChart = $('#visitors-chart')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels:[
      <?php foreach($usersOvertime as $userOvertime):?>
        <?= json_encode(strtoupper(date("M", strtotime($userOvertime['month'])))) ?>,
      <?php endforeach?>
      ],
      datasets: [{
        type: 'line',
        data: [
          <?php foreach($usersOvertime as $userOvertime):?>
            <?= json_encode($userOvertime['registrations']) ?>,
          <?php endforeach?>
        ],
        backgroundColor: 'transparent',
        borderColor: '#27a844',
        pointBorderColor: '#27a844',
        pointBackgroundColor: '#27a844',
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })

})

</script>
</body>
</html>
