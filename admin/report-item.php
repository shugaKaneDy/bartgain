<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once 'includes/ajax/functions.php';
  require_once 'includes/admin-validation.php';

  $currentTime  = date("Y-m-d H:i:s");

  $reports = selectQuery(
    $pdo,
    "SELECT r.report_item_id, r.report_category, COUNT(*) AS report_count, MAX(r.report_created_at) AS last_report_created_at, i.*
    FROM report_items r
    INNER JOIN items i ON r.report_item_id = i.item_id
    WHERE r.report_review = 0
    AND i.item_status != 'deleted'
    GROUP BY r.report_item_id, r.report_category, i.item_id
    HAVING report_count >= 2 AND report_count < 3",
    []
  );

  $reportsAutoDelete = selectQuery(
    $pdo,
    "SELECT r.report_item_id, r.report_category, COUNT(*) AS report_count, MAX(r.report_created_at) AS last_report_created_at, i.*
    FROM report_items r
    INNER JOIN items i ON r.report_item_id = i.item_id
    WHERE r.report_review = 0
    GROUP BY r.report_item_id, r.report_category, i.item_id
    HAVING report_count >= 3",
    []
  );

  // print_r($reportsAutoDelete);
  // exit;

  foreach ($reports as $report) {

    $query = "UPDATE items SET item_flagged = 1
      WHERE item_id = :itemId
      AND item_flagged = 0";
    $stmt = $pdo->prepare($query);

    $data = [
      ":itemId" => $report['report_item_id']
    ];

    $query_execute = $stmt->execute($data);

    if ($stmt->rowCount() > 0) {
      // INSERT QUERY
      insertQuery(
        $pdo,
        "INSERT INTO action_items
        (
          action_item_id,
          action_type,
          action_description,
          action_created_at
        )
        VALUES
        (
          :itemId,
          :type,
          :description,
          :createdAt
        )",
        [
          ":itemId" => $report['report_item_id'],
          ":type" => "flagged",
          ":description" => $report['report_category'],
          ":createdAt" => $currentTime
        ]
      );

      // INSERT NOTIFICATIONS
      insertQuery(

        $pdo,
        "INSERT INTO user_notifications (user_notification_user_id, user_notification_by_user_id, user_notification_message, user_notification_type, user_notification_created_at)
        VALUES (:userNotificationUserId, :userNotificationByUserId, :userNotificationMessage, :userNotificationType, :userNotificationCreatedAt)",
          [
            ":userNotificationUserId" => $report['item_user_id'],
            ":userNotificationByUserId" => $_SESSION['user_details']['user_id'],
            ":userNotificationMessage" => "Your item : " . $report['item_title'] . " item id: " . $report['item_random_id'] . " was flagged as " . $report['report_category'],
            ":userNotificationType" => "item flagged",
            ":userNotificationCreatedAt" => $currentTime,
          ]
      );
    }

  }

  foreach($reportsAutoDelete as $reportAutoDelete) {
    $deleteQuery = "UPDATE items SET item_status = 'deleted'
    WHERE item_id = :itemId
    AND item_status != 'deleted'";
    $deleteStmt = $pdo->prepare($deleteQuery);
    $deleteData = [
      ":itemId" => $reportAutoDelete['report_item_id']
    ];

    $delete_query_execute = $deleteStmt->execute($deleteData);
    if ($deleteStmt->rowCount() > 0) {

      // INSERT QUERY
      insertQuery(
        $pdo,
        "INSERT INTO action_items
        (
          action_item_id,
          action_type,
          action_description,
          action_created_at
        )
        VALUES
        (
          :itemId,
          :type,
          :description,
          :createdAt
        )",
        [
          ":itemId" => $reportAutoDelete['report_item_id'],
          ":type" => "deleted automatically",
          ":description" => $reportAutoDelete['report_category'],
          ":createdAt" => $currentTime
        ]
      );

      // INSERT NOTIFICATIONS
      insertQuery(

        $pdo,
        "INSERT INTO user_notifications (user_notification_user_id, user_notification_by_user_id, user_notification_message, user_notification_type, user_notification_created_at)
        VALUES (:userNotificationUserId, :userNotificationByUserId, :userNotificationMessage, :userNotificationType, :userNotificationCreatedAt)",
          [
            ":userNotificationUserId" => $reportAutoDelete['item_user_id'],
            ":userNotificationByUserId" => $_SESSION['user_details']['user_id'],
            ":userNotificationMessage" => "Your item : " . $reportAutoDelete['item_title'] . " item id: " . $reportAutoDelete['item_random_id'] . " has been removed due to a violation of community standards under the category: " . $reportAutoDelete['report_category'],
            ":userNotificationType" => "item deleted",
            ":userNotificationCreatedAt" => $currentTime,
          ]
      );
    }
  }

  // Actions
  $actions = selectQuery(
    $pdo,
    "SELECT * FROM action_items
    INNER JOIN items ON action_items.action_item_id = items.item_id
    ORDER BY action_items.action_id DESC",
    []
  );

  // print_r($actions);

  $totalReports = count($reports);
  

  



  // print_r($reports);
  // exit;

  

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
            <h1>Reported Items</h1>
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
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-gift"></i></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Reported Items</span>
                <span class="info-box-number"><?= $totalReports ?></span>
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
                <div class="card-title">Flagged Reports</div>
              </div>
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Category</th>
                      <th>Report Count</th>
                      <th>Last Reported</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($reports as $report): ?>
                      <tr>
                        <td>
                          <?= $report['item_random_id'] ?>
                        </td>
                        <td>
                          <?= $report['report_category'] ?>
                        </td>
                        <td>
                          <?= $report['report_count'] ?>
                        </td>
                        <td>
                          <?= date("M d, Y h:i A", strtotime($report['last_report_created_at']))?>
                        </td>
                        <td>
                          <a href="item-view.php?item_id=<?= $report['item_random_id'] ?>" target="_blank" class="btn btn-sm btn-light">View</a>
                          <button class="btn btn-sm btn-success mr-1 mb-1">Approve</button>
                          <button class="btn btn-sm btn-danger mr-1 mb-1">Delete</button>
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

        <!-- Tables Action  -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-danger">
                <div class="card-title">Actions</div>
              </div>
              <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Type</th>
                      <th>Description</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($actions as $action): ?>
                      <tr>
                        <td>
                          <?= $action['item_random_id'] ?>
                        </td>
                        <td>
                          <?php if($action['action_type'] == 'flagged'): ?>
                            <span class="badge badge-warning">
                              <?= $action['action_type'] ?>
                            </span>
                          <?php else: ?>
                            <span class="badge badge-danger">
                              <?= $action['action_type'] ?>
                            </span>
                          <?php endif ?>
                          
                        </td>
                        <td>
                          
                          <?= $action['action_description'] ?>
                        </td>
                        <td>
                          <?= date("M d, Y h:i A", strtotime($action['action_created_at']))?>
                        </td>
                        <td>
                          <a href="item-view.php?item_id=<?= $action['item_random_id'] ?>" target="_blank" class="btn btn-sm btn-light">View</a>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- ./Tables Action -->

        
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

    $('#example3').DataTable({
      "paging": true,
      "ordering": false,
      "responsive": true,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
  });
</script>

</body>
</html>
