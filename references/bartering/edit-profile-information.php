<?php
session_start();
require_once 'dbcon.php';

if (!empty($_SESSION["user_details"])) {
  $userId = $_SESSION["user_details"]["user_id"];
} else {
  echo "<script>
          alert('You must login first');
          window.location.href = 'sign-in.php';
        </script>";
  die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $contact = $_POST['contact'];
  $emergencyContact = $_POST['emergencyContact'];

  $query = "UPDATE users SET user_contact = :contact, user_emergency_contact = :emergencyContact WHERE user_id = :userId";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':contact', $contact);
  $stmt->bindParam(':emergencyContact', $emergencyContact);
  $stmt->bindParam(':userId', $userId);
  if ($stmt->execute()) {
    echo "<script>
            alert('Profile updated successfully');
            window.location.href = 'profile.php';
          </script>";
  } else {
    echo "<script>
            alert('Profile update failed');
            window.location.href = 'profile-edit.php';
          </script>";
  }
}
?>
