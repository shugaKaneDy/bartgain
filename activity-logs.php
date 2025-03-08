<?php
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'functions.php';

  if(!isset($_SESSION['user_details'])) {
    header("location: logout.php");
    exit;
  }
  if($_SESSION['user_details']['verified'] == "N") {
    header("location: itemplace.php");
    exit;
  }

  $selectedLogs = selectQuery(
    $pdo,
    "SELECT * FROM activity_logs
    WHERE act_log_user_id = :userId
    ORDER BY act_log_id DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
    ]
  );

  // print_r($selectedLogs);
  // exit;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity Logs</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/meet-up.css">


</head>
<body class="bg-light">

  <!-- nav -->
  <?php
    include "layouts/nav.php"
  ?>


  <!-- Add item -->
  <?php
    include "layouts/add-div.php"
  ?>

  <!-- Offcanvas -->
  <?php
    include "layouts/aside.php"
  ?>

  <!-- pre load -->
  <?php
    include "layouts/preload.php"
  ?>

  <main>
    <div class="container-xl">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8">
          <h3>Activity Logs</h3>
          <div class="px-3 py-4 bg-white rounded border shadow-sm">
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Description</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($selectedLogs as $selectedLog): ?>
                  <tr>
                    <td class="small-text"><?= $selectedLog['act_log_act_type'] ?></td>
                    <td class="small-text"><?= $selectedLog['act_log_description'] ?></td>
                    <td class="small-text"><?= date("M d, Y", strtotime($selectedLog['act_log_created_at'])) ?></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </main>

  <?php
    require_once 'layouts/bottom-link.php';
  ?>


  <script>
    $(document).ready(function() {

    })
  </script>

</body>
</html>