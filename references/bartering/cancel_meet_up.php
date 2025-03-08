<?php
session_start();
require_once 'dbcon.php';

// Check if user is logged in
if (empty($_SESSION["user_details"])) {
    ?>
    <script>
        alert("You must login first");
        window.location.href = "sign-in.php"
    </script>
    <?php
    die();
}

// Get logged-in user ID
$userId = $_SESSION["user_details"]["user_id"];

// Get offer ID from the URL parameter
if (isset($_POST["meetUpId"])) {
    $meetUpId = $_POST["meetUpId"];
    $userReason = $_POST["userReason"];
    $cancelReason = $_POST["cancelReason"];

} else {
    // Handle case where offerId is not provided
    ?>
    <script>
        alert("Offer ID not provided");
        window.location.href = "messages-offers.php";
    </script>
    <?php
    die();
}

try {
    // Begin transaction
    $conn->beginTransaction();

    // Update offer status to 'accepted'
    $updateQuery = "UPDATE meet_up SET meet_up_status = 'cancelled', $userReason = :cancelReason WHERE meet_up_id = :meetUpId";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':cancelReason', $cancelReason, PDO::PARAM_STR);
    $stmt->bindParam(':meetUpId', $meetUpId, PDO::PARAM_INT);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    // Success message

    // echo "Meet up cancelled successfully";

    echo "<script>alert('Meet-up cancelled successfully');</script>";
    echo "<script>window.location.href = 'meet-up-information.php?meetUpId=$meetUpId';</script>";
   
} catch (PDOException $e) {
    // Rollback transaction on error
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}


