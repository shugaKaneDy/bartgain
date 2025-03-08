<?php
require_once '../dbcon.php';
session_start();

$itemId = null;
$result = null;

// Check if itemId is provided via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["itemId"])) {
    $itemId = $_POST["itemId"];

    // Prepare and execute SQL query using prepared statement
    $query = "SELECT * FROM items WHERE item_id = :item_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $imgURls = explode(",", $result->item_url_picture);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Item Information</title>
  <link rel="icon" href="../B.png">
  <!-- Top Links -->
  <?php include("layout/top-link.php"); ?>
  <!-- Style -->
  <?php include("layout/style.php"); ?>
</head>
<body>
  <!-- navbar -->
  <?php include("layout/navbar.php"); ?>
  <!-- sidebar -->
  <?php include("layout/sidebar.php"); ?>
  <!-- Main content -->
  <main class="main-content pt-3">
    <div class="container main-title mb-5">
      <h3>View Item Information</h3>
      <div class="row">
        <!-- Left Column: Item ID and Picture -->
        <div class="col-md-6">
          <div class="border p-4 shadow rounded mb-4">
            <h4>Item Details</h4>
            <?php if ($result): ?>
              <p><strong>Item ID:</strong> <?= $result->item_id ?></p>
              <img src="../item-photo/<?= $imgURls[0] ?>" alt="Item Picture" class="img-fluid mb-3">
            <?php else: ?>
              <p>No item found with ID: <?= $itemId ?></p>
            <?php endif; ?>
          </div>
        </div>
        <!-- Right Column: User ID, Title, Description, Condition, Swap Option -->
        <div class="col-md-6">
          <div class="border p-4 shadow rounded mb-4">
            <h4>Item Information</h4>
            <?php if ($result): ?>
              <p><strong>User ID:</strong> <?= $result->item_user_id ?></p>
              <p><strong>Title:</strong> <?= $result->item_title ?></p>
              <p><strong>Description:</strong> <?= $result->item_description ?></p>
              <p><strong>Condition:</strong> <?= $result->item_condition ?></p>
              <p><strong>Swap Option:</strong> <?= $result->item_swap_option ?></p>
            <?php endif; ?>
            <div class="mt-3">
              <button class="btn btn-success btn-sm">Restore</button>
              <button class="btn btn-danger btn-sm">Remove</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- Bottom Links -->
  <?php include("layout/bottom-link.php"); ?>
  <script src="../js/plugins/sweetalert2/swal.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable({
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
