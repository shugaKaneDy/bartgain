<?php
  require_once '../dbcon.php';
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Offers</title>
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
      <h3 >Offers</h3>
      <div class="table-responsive border p-4 border shadow rounded">
        <table id="myTable" class="w-100 table table-striped">
          <thead>
            <th>Offer Id</th>
            <th>Item Id</th>
            <th>Sender Id</th>
            <th>Title</th>
            <th>Receiver ID</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
          </thead>
          <tbody>
            <?php
              $query = "SELECT * FROM offers ORDER BY offer_id DESC";
              $stmt = $conn->query($query);
              $stmt->setFetchMode(PDO::FETCH_OBJ);
              $result = $stmt->fetchAll();

              foreach ($result as $row) {
                ?>
                <tr>
                  <td><?= $row->offer_id ?></td>
                  <td><?= $row->item_id ?></td>
                  <td><?= $row->sender_id ?></td>
                  <td><?= $row->offer_title ?></td>
                  <td><?= $row->r_receiver_id ?></td>
                  <td><?= $row->offer_status ?></td>
                  <td><?= $row->offer_created_at ?></td>
                  <td>
                    <form action="view-offer-information.php" method="post">
                      <input type="hidden" name="offerId" value="<?= $row->offer_id ?>">
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