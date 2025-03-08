<?php
require_once '../dbcon.php';
session_start();

$offerId = null;
$result = null;
$imgUrls = [];

// Check if offerId is provided via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["offerId"])) {
    $offerId = $_POST["offerId"];

    // Prepare and execute SQL query using prepared statement
    $query = "SELECT * FROM offers WHERE offer_id = :offer_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':offer_id', $offerId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        // Split offer_url_picture into array of URLs
        if ($result && isset($result->offer_url_picture)) {
            $imgUrls = explode(",", $result->offer_url_picture);
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Offer Information</title>
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
      <h3>View Offer Information</h3>
      <div class="row">
        <!-- Left Column: Offer ID and Pictures -->
        <div class="col-md-6">
          <div class="border p-4 shadow rounded mb-4">
            <h4>Offer Details</h4>
            <?php if ($result): ?>
              <p><strong>Offer ID:</strong> <?= $result->offer_id ?></p>
              <?php foreach ($imgUrls as $imgUrl): ?>
                <img src="../offer-item-photo/<?= $imgUrl ?>" alt="Offer Image" class="img-fluid mb-3">
              <?php endforeach; ?>
            <?php else: ?>
              <p>No offer found with ID: <?= $offerId ?></p>
            <?php endif; ?>
          </div>
        </div>
        <!-- Right Column: Offer Title, Description -->
        <div class="col-md-6">
          <div class="border p-4 shadow rounded mb-4">
            <h4>Offer Information</h4>
            <?php if ($result): ?>
              <p><strong>Item Id:</strong> <?= $result->item_id ?></p>
              <p><strong>Title:</strong> <?= $result->offer_title ?></p>
              <p><strong>Description:</strong> <?= $result->offer_description ?></p>
              <!-- Add more fields as needed -->
            <?php endif; ?>
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
