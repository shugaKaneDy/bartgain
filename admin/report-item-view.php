<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';

  if(isset($_GET['ru_id'])) {
    if(!empty($_GET['ru_id'])) {
      $ru_id = $_GET['ru_id'];
      $reportUserInfo = selectQueryFetch(
        $pdo,
        "SELECT * FROM reports
        WHERE report_random_id = :reportId
        AND report_type = :reportType",
        [
          ":reportId" => $ru_id,
          ":reportType" => "user",
        ]
      );

      if(empty($reportUserInfo)) {
        header("location: report-user.php");
        exit;
      }
    } else {
      header("location: report-user.php");
      exit;
    }
  } else {
    header("location: report-user.php");
    exit;
  }

  $reportedByInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM users WHERE
    user_id = :userId",
    [
      ":userId" => $reportUserInfo['report_by_user_id'],
    ]
  );

  $reportedUserInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM users WHERE
    user_id = :userId",
    [
      ":userId" => $reportUserInfo['report_user_id'],
    ]
  );

  $actionReasonsForUser = [
    "Engaged in discriminatory or hateful language.",
    "Participated in violent behavior or made threats.",
    "Violated the platform's rules and policies.",
    "Repeatedly reported for similar behavior.",
    "Insufficient evidence to support the claims.",
    "Received a warning regarding inappropriate behavior.",
    "Other."
  ];


  // print_r($reportUserInfo);
  // exit;


  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Report Item View</title>

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
            <h1>Report View</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="d-flex justify-content-between">
                  <div>
                    <a href="report-user.php" class="btn btn-sm btn-light">
                      <i class="fas fa-arrow-left"></i> Back
                    </a>
                  </div>
                  <?php if($reportUserInfo['report_status'] == "pending"): ?>
                    <div>
                      <button id="dismissBtn" class="btn btn-sm btn-danger">
                        Dismiss
                      </button>
                    </div>
                  <?php endif ?>
                </div>
              </div>
              <div class="card-body">
                <div class="row justify-content-center">
                  <div class="col-12 col-md-6 mb-3">
                    <div class="card shadow-0 shadow-none">
                      <div class="card-header">
                        <h5 class="card-title">Details</h5>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-4">
                            <p class="text-muted">Reported User</p>
                          </div>
                          <div class="col-8">
                            <p><?= $reportedUserInfo['fullname'] ?></p>
                          </div>
                          <div class="col-4">
                            <p class="text-muted">Reported By</p>
                          </div>
                          <div class="col-8">
                            <p><?= $reportedByInfo['fullname'] ?></p>
                          </div>
                          <div class="col-4">
                            <p class="text-muted">Created</p>
                          </div>
                          <div class="col-8">
                            <p class="small-text"><?= date("M d, Y h:i A", strtotime($reportUserInfo['report_created_at'])) ?></p>
                          </div>

                          <div class="col-12 border"></div>

                          <div class="col-4">
                            <p class="text-muted small-text">Id</p>
                          </div>
                          <div class="col-8">
                            <p class="small-text"><?= $reportUserInfo['report_random_id'] ?></p>
                          </div>
                          <div class="col-4">
                            <p class="text-muted small-text">Status</p>
                          </div>
                          <div class="col-8">
                            <?php if($reportUserInfo['report_status'] == "pending"): ?>
                              <span class="badge badge-secondary">pending</span>
                            <?php elseif($reportUserInfo['report_status'] == "resolved"): ?>
                              <span class="badge badge-success">resolved</span>
                            <?php else: ?>
                              <span class="badge badge-danger">dismissed</span>
                            <?php endif ?>
                          </div>
                          <div class="col-4">
                            <p class="text-muted small-text">Category</p>
                          </div>
                          <div class="col-8">
                            <p class="small-text"><?= $reportUserInfo['report_category'] ?></p>
                          </div>
                          <div class="col-4">
                            <p class="text-muted small-text">Reason</p>
                          </div>
                          <div class="col-8">
                            <p class="small-text"><?= $reportUserInfo['report_reason'] ?></p>
                          </div>

                          <div class="col-4">
                            <p class="text-muted small-text">Photos</p>
                          </div>
                          <div class="col-8">
                            <?php
                              if(!empty($reportUserInfo['report_photos'])) {
                                $urlFiles = explode(",", $reportUserInfo["report_photos"]);
                                $total = count($urlFiles);

                                for($i = 0; $i < $total; $i++) {
                                  ?>
                                  <div>
                                    <a href="../report-uploads/<?= $urlFiles[$i] ?>"
                                    target="_blank"
                                    class="link link-success small-text">
                                      <i class="text-secondary fas fa-paperclip"></i> <?= $urlFiles[$i] ?>
                                    </a>
                                  </div>
                                  <?php
                                }
                              }
                            ?>
                          </div>





                        </div>
                      </div>
                    </div>
                  </div>
                  <?php if($reportUserInfo['report_status'] == "pending"): ?>
                    <div class="col-12 col-md-6">
                      <div class="card">
                        <div class="card-header">
                          <h5 class="card-title text-center">Action</h5>
                        </div>
                        <div class="card-body">
                          <form id="resolveForm">
                            <input type="hidden" name="ru_id" value="<?= $ru_id ?>">
                            <div class="form-group mb-3">
                              <label>Action Reason</label>
                              <select class="form-control select2" name="actionReason" id="actionReason" style="width: 100%;">
                                <?php foreach ($actionReasonsForUser as $reason): ?>
                                    <option value="<?= htmlspecialchars($reason) ?>"><?= htmlspecialchars($reason) ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                            <!-- /.form-group -->
  
                            <div class="form-group mb-3 otherReasonDiv" style="display: none;">
                              <label>Other Reason</label>
                              <input type="text" class="form-control" name="otherReason" id="otherReason" placeholder="Specify your reason">
                            </div>
                            <!-- /.form-group -->
  
                            <div class="d-flex justify-content-end">
                              <button type="button" class="btn btn-success" id="resolveBtn">
                                Resolve
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                      
                    </div>
                  <?php endif ?>
                </div>
               
              </div>
            </div>
          </div>
        </div>
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
    
    

  })
</script>

</body>
</html>
