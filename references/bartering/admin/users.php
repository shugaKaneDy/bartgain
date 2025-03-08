<?php
  require_once '../dbcon.php';
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users</title>
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
      <h3 >User Accounts</h3>
      <div>
        <nav class="nav">
          <a class="nav-link text-sm" href="users.php">User Accounts</a>
          <a class="nav-link text-dark text-sm" href="admin-accounts.php">Admin Accounts</a>
          <a class="nav-link text-dark text-sm" href="add-user.php">Add User</a>
        </nav>
      </div>
      <div class="table-responsive border p-4 border shadow rounded">
        <table id="myTable" class="w-100 table table-striped">
          <thead>
            <th>User Id</th>
            <th>Email</th>
            <th>Verified</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
          </thead>
          <tbody>
            <?php
              $query = "SELECT * FROM users WHERE role_id = 1 ORDER BY user_id DESC";
              $stmt = $conn->query($query);
              $stmt->setFetchMode(PDO::FETCH_OBJ);
              $result = $stmt->fetchAll();

              foreach ($result as $row) {
                ?>
                <tr>
                  <td><?= $row->user_id ?></td>
                  <td><?= $row->email ?></td>
                  <td><?= $row->verified ?></td>
                  <td><?= $row->user_status ?></td>
                  <td><?= $row->created_at ?></td>
                  <td>
                    <form action="view-user-account.php" method="post">
                      <input type="hidden" name="userId" value="<?= $row->user_id ?>">
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