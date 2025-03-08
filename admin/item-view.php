<?php
session_start();
require_once '../includes/dbh.inc.php';
require_once '../functions.php';
require_once 'includes/admin-validation.php';

$item_id = $_GET['item_id'];

$selectedItem = selectQueryFetch(
  $pdo,
  "SELECT * FROM items
  INNER JOIN users ON items.item_user_id = users.user_id
  WHERE items.item_random_id = :id",
  [
    ":id" => $item_id,
  ]
);

// print_r($selectedItem);
// exit;

$UrlFiles = explode(',' , $selectedItem['item_url_file']);
$firstFile = $UrlFiles[0];



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
            <h1>ITEM VIEW</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">

        <!-- Tables -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <a href="items.php" class="btn btn-sm btn-light">
                  <i class="fas fa-arrow-left"></i> Back
                </a>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-6 mb-3">
                    <div class="d-flex justify-content-center align-items-center">
                      <img src="../item-uploads/<?= $firstFile ?>" alt="" class="img-fluid rounded border border-dark">
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <h5>Details</h5>
                    <p class="m-0">Title: <span class="text-muted"><?= $selectedItem['item_title'] ?></span></p>
                    <p class="m-0">ID: #<span class="text-muted"><?= $selectedItem['item_random_id'] ?></span></p>
                    <p class="m-0">Status: <span class="text-muted"><?= $selectedItem['item_status'] ?></span></p>
                    <p class="m-0">Created: <span class="text-muted"><?= $selectedItem['item_created_at'] ?></span></p>
                    <p class="m-0">Option: <span class="text-muted"><?= $selectedItem['item_swap_option'] ?></span></p>
                    <p class="m-0">Category: <span class="text-muted"><?= $selectedItem['item_category'] ?></span></p>
                    <p class="m-0">Condition: <span class="text-muted"><?= $selectedItem['item_condition'] ?></span></p>
                    <p class="m-0">Est Value: <span class="text-muted"><?= $selectedItem['item_est_val'] ?></span></p>
                    <p class="m-0">Location: <span class="text-muted"><?= $selectedItem['item_current_location'] ?></span></p>
                    <p class="m-0">Description: <span class="text-muted"><?= $selectedItem['item_description'] ?></span></p>
                  </div>
                </div>
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
