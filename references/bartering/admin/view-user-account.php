<?php
require_once '../dbcon.php';
session_start();

$userId = null;
$result = null;

// Check if userId is provided via GET or POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["userId"])) {
    $userId = $_POST["userId"];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["userId"])) {
    $userId = $_GET["userId"];
}

// Prepare and execute SQL query using prepared statement
if ($userId !== null) {
    $query = "SELECT user_id, role_id, fullname, email, email_verification, verified, user_status, created_at 
              FROM users 
              WHERE user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
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
  <title>View User Account</title>
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
      <h3>View User Account</h3>
      <div class="border p-4 shadow rounded">
        <?php if ($result): ?>
          <div class="mb-3">
            <strong>User ID:</strong> <?= $result['user_id'] ?>
          </div>
          <div class="mb-3">
            <strong>Role ID:</strong> <?= $result['role_id'] ?>
          </div>
          <div class="mb-3">
            <strong>Full Name:</strong> <?= $result['fullname'] ?>
          </div>
          <div class="mb-3">
            <strong>Email:</strong> <?= $result['email'] ?>
          </div>
          <div class="mb-3">
            <strong>Email Verification:</strong> <?= $result['email_verification'] ?>
          </div>
          <div class="mb-3">
            <strong>Verified:</strong> <?= $result['verified'] ?>
          </div>
          <div class="mb-3">
            <strong>User Status:</strong> <?= $result['user_status'] ?>
          </div>
          <div class="mb-3">
            <strong>Created At:</strong> <?= $result['created_at'] ?>
          </div>
        <?php else: ?>
          <p>No user found with ID: <?= $userId ?></p>
        <?php endif; ?>
        <div>
          <button class="btn btn-danger btn-sm">Disable Account</button>
        </div>
      </div>
    </div>
  </main>

  <!-- Bottom Links -->
  <?php include("layout/bottom-link.php"); ?>

  <!-- Include any additional scripts here if needed -->

</body>
</html>
