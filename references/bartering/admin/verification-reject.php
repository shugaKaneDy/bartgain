<?php
  require_once '../dbcon.php';
  session_start();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verification_id = (int)$_POST['verification_id']; // Sanitize input
    $verificationReason = $_POST['reject_reason'];

    try {
      // Update the verification status to 'rejected'
      $updateVerificationQuery = "UPDATE verifications SET verification_status = 'rejected', verification_reject_reason = :verificationReason WHERE verification_id = :verification_id";
      $updateVerificationStmt = $conn->prepare($updateVerificationQuery);
      $updateVerificationStmt->bindParam(':verification_id', $verification_id, PDO::PARAM_INT);
      $updateVerificationStmt->bindParam(':verificationReason', $verificationReason, PDO::PARAM_STR);
      $updateVerificationStmt->execute();

      notifyUserOfVerificationRejection($_SESSION["user_details"]["user_id"], $verification_id, $verificationReason);

      // Set success message and redirect to verification page
      $_SESSION["message"] = [
        "status" => "success",  // 'error' icon for rejection
        "title" => "Verification Rejected"
      ];
      header("Location: verification.php");
      exit();
    } catch (PDOException $e) {
      // Handle error
      echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
  }
?>
