<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';

  $tickets = selectQuery(
    $pdo,
    "SELECT * FROM tickets
    INNER JOIN users ON tickets.ticket_user_id = users.user_id
    ORDER BY tickets.ticket_id DESC",
    []
  );

  $totalTickets = count($tickets);

  // print_r($tickets);

  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Tickets</title>

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
            <h1>Tickets</h1>
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
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-ticket-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Tickets</span>
                <span class="info-box-number"><?= $totalTickets ?></span>
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
                      <th>Subject</th>
                      <th>ID</th>
                      <th>Created</th>
                      <th>Name</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($tickets as $ticket): ?>
                      <tr>
                        <td>
                          <a href="ticket-view.php?t_id=<?= $ticket['ticket_random_id'] ?>" class="link link-success">
                            <?= $ticket['ticket_subject'] ?>
                          </a>
                        </td>
                        <td>
                          #<?= $ticket['ticket_random_id'] ?>
                        </td>
                        <td>
                          <?= date("M d, Y", strtotime($ticket['ticket_created_at']))?>
                        </td>
                        <td>
                          <?= $ticket['fullname'] ?>
                        </td>
                        <td>
                          <?php if($ticket['ticket_status'] == "open"): ?>
                            <span class="badge badge-success">open</span>
                          <?php else: ?>
                            <span class="badge badge-danger">closed</span>
                          <?php endif ?>
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


  })
</script>

</body>
</html>
