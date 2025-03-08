<?php
  require_once '../dbcon.php';
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verification</title>
  <link rel="icon" href="../B.png">
  <!-- Top Links -->
  <?php
    include("layout/top-link.php");
  ?>

  <!-- Style -->
  <?php
    include("layout/style.php");
  ?>
</head>
<body>

  <!-- navbar -->
  <?php
    include("layout/navbar.php");
  ?>

  <!-- sidebar -->
  <?php
    include("layout/sidebar.php");
  ?>

  <!-- Main content -->
  <main class="main-content pt-3">
    <div class="container main-title mb-5">
      <h3 >Verification</h3>
      <div>
        <nav class="nav">
          <a class="nav-link text-dark text-sm" href="verification.php">Pending Verification</a>
          <a class="nav-link text-sm" href="verification-history.php">Verification History</a>
        </nav>
      <div class="table-responsive border p-4 border shadow rounded">
        <table id="myTable" class="w-100 table table-striped">
          <thead>
            <th>Verification Id</th>
            <th>Fullname</th>
            <th>Birth Date</th>
            <th>Verification Application</th>
            <th>Status</th>
            <th>Action</th>
          </thead>
          <tbody>
            <?php
              $query = "SELECT * FROM verifications INNER JOIN users ON verifications.user_id = users.user_id ORDER BY verification_id DESC";
              $stmt = $conn->query($query);
              $stmt->setFetchMode(PDO::FETCH_OBJ);
              $result = $stmt->fetchAll();

              foreach ($result as $row) {
                ?>
                <tr>
                  <td><?= $row->verification_id ?></td>
                  <td><?= $row->fullname ?></td>
                  <td><?= $row->verification_birth_date ?></td>
                  <td><?= $row->created_at ?></td>
                  <td><?= $row->verification_status ?></td>
                  <td>
                    <form action="view-verification.php" method="post">
                      <input type="hidden" name="verification_id" value="<?= $row->verification_id ?>">
                      <button class="btn btn-sm btn-secondary">View</button>
                    </form>
                  </td>
                </tr>
                <?php
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Bottom Links -->
  <?php
    include("layout/bottom-link.php");
  ?>


<script src="../js/plugins/sweetalert2/swal.js"></script>
<script>
  $(document).ready( function () {
    $('#myTable'). DataTable({
      ordering: false
    });
  });

  

  <?php if(isset($_SESSION['message'])): ?>
    Swal.fire({
      icon: '<?= $_SESSION["message"]["status"] ?>',
      title: '<?= $_SESSION["message"]["title"] ?>',
      showConfirmButton: true
    });
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  
</script>
  
</body>
</html>