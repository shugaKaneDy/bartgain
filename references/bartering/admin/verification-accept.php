<?php
  require_once '../dbcon.php';
  session_start();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verification_id = (int)$_POST['verification_id']; // Sanitize input

    try {
      // Start the transaction
      $conn->beginTransaction();

      // Fetch the verification and user information
      $query = "SELECT * FROM verifications WHERE verification_id = :verification_id";
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':verification_id', $verification_id, PDO::PARAM_INT);
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_OBJ);
      $verificationInfo = $stmt->fetch();

      if ($verificationInfo) {
        // Update the verification status
        $updateVerificationQuery = "UPDATE verifications SET verification_status = 'accept' WHERE verification_id = :verification_id";
        $updateVerificationStmt = $conn->prepare($updateVerificationQuery);
        $updateVerificationStmt->bindParam(':verification_id', $verification_id, PDO::PARAM_INT);
        $updateVerificationStmt->execute();

        // Update the user's age and verified status
        $updateUserQuery = "UPDATE users SET birth_date = :birthDate, verified = 'Y' WHERE user_id = :user_id";
        $updateUserStmt = $conn->prepare($updateUserQuery);
        $updateUserStmt->bindParam(':birthDate', $verificationInfo->verification_birth_date, PDO::PARAM_STR);
        $updateUserStmt->bindParam(':user_id', $verificationInfo->user_id, PDO::PARAM_INT);
        $updateUserStmt->execute();

        notifyUserOfVerificationAcceptance($_SESSION["user_details"]["user_id"], $verification_id);

        // Commit the transaction
        $conn->commit();

        echo "<p>Verification status updated and user information verified successfully.</p>";

        $_SESSION["message"] = [
          "status" => "success",
          "title" => "Verified Successfully"
        ];
        header("Location: verification.php");
      } else {
        echo "<p>No verification found for the given ID.</p>";
      }
    } catch (PDOException $e) {
      // Roll back the transaction if something failed
      $conn->rollBack();
      echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
  }
?>