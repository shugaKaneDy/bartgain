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

// Add date conditions if start and/or end dates are provided
if ($startDate && $endDate) {
    $dateCondition .= " AND items.item_created_at BETWEEN :start_date AND :end_date";
    $queryParams[':start_date'] = $startDate;
    $queryParams[':end_date'] = $endDate;
} elseif ($startDate) {
    $dateCondition .= " AND items.item_created_at >= :start_date";
    $queryParams[':start_date'] = $startDate;
} elseif ($endDate) {
    $dateCondition .= " AND items.item_created_at <= :end_date";
    $queryParams[':end_date'] = $endDate;
}

// Fetch items with the applied date filter
$items = selectQuery(
    $pdo,
    "SELECT * FROM items WHERE 1=1 $dateCondition ORDER BY item_id DESC",
    $queryParams
);

$totalItems = count($items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Items</title>

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
            <h1>ITEMS</h1>
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
                <span class="info-box-number"><?= $totalItems ?></span>
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
              <a href="items.php" class="btn btn-secondary" style="margin-top: 30px;">Reset</a>
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
                    <?php foreach($items as $item): ?>
                      <tr>
                        <td><?= $item['item_random_id'] ?></td>
                        <td><?= $item['item_title'] ?></td>
                        <td>
                          <?php if($item['item_status'] == 'pending'): ?>
                            <span class="badge badge-secondary">
                              <?= $item['item_status'] ?>
                            </span>
                          <?php elseif($item['item_status'] == 'available' || $item['item_status'] == 'completed'): ?>
                            <span class="badge badge-success">
                              <?= $item['item_status'] ?>
                            </span>
                          <?php else: ?>
                            <span class="badge badge-danger">
                              <?= $item['item_status'] ?>
                            </span>
                          <?php endif ?>
                          
                        </td>
                        <td><?= $item['item_created_at'] ?></td>
                        <td>
                          <a href="item-view.php?item_id=<?= $item['item_random_id'] ?>" class="btn btn-sm btn-light">View</a>
                          <button class="btn btn-sm btn-outline-danger deleteBtn"
                          itemAction="deleted"
                          itemId="<?= $item['item_random_id'] ?>"
                          >
                            delete
                          </button>

                          <button class="btn btn-sm btn-success border availability"
                          itemAction="available"
                          itemId="<?= $item['item_random_id'] ?>"
                          >
                            available
                          </button>
                          <button class="btn btn-sm btn-light border me-1 mb-2 availability"
                          itemAction="pending"
                          itemId="<?= $item['item_random_id'] ?>"
                          >
                            pending
                          </button>
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

    $(document).on('click', ".deleteBtn", function() {
      let itemId = $(this).attr("itemId");
      let itemAction = $(this).attr("itemAction");
      console.log(itemAction);
      console.log(itemId);


      Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete this item?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
      }).then(result => {
        if(result.isConfirmed) {
          $.ajax({
            method: 'POST',
            url: "../includes/ajax/mark-as.inc.php",
            data: {
              itemId : itemId,
              itemAction : itemAction
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
                  location.reload();
                }
              });
            }

          })
        }
      })

    })

    $(document).on('click', ".availability", function() {
        let itemId = $(this).attr("itemId");
        let itemAction = $(this).attr("itemAction");

        Swal.fire({
          title: 'Are you sure?',
          text: `Do you want to mark this as ${itemAction}?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "../includes/ajax/mark-as.inc.php",
              data: {
                itemId : itemId,
                itemAction : itemAction
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
                    location.reload();
                  }
                });
              }

            })
          }
        })

      })

  });
</script>

</body>
</html>
