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
if (isset($_POST["offerId"])) {
    $offerId = $_POST["offerId"];
    $rejectReason = $_POST["rejectReason"];
    $status = $_POST["status"];

    /* echo $offerId;
    echo $rejectReason; */
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
    $updateQuery = "UPDATE offers SET offer_status = :status, offer_cancelled_rejected_reason = :rejectReason WHERE offer_id = :offerId";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':rejectReason', $rejectReason, PDO::PARAM_STR);
    $stmt->bindParam(':offerId', $offerId, PDO::PARAM_INT);
    $stmt->execute();

    // Select offer details
    $selectQuery = "SELECT * FROM offers WHERE offer_id = :offerId";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bindParam(':offerId', $offerId, PDO::PARAM_INT);
    $stmt->execute();
    $offerDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if($status == "rejected") {
        notifyUserOfOfferRejection($offerDetails["sender_id"], $offerId);
    } else {
        notifyUserOfOfferCancellation($offerDetails["r_receiver_id"], $offerId);
    }





    // Commit transaction
    $conn->commit();

    // Success message

    if ($status == "rejected") {
        echo "<script>alert('Offer rejected successfully');</script>";
        echo "<script>window.location.href = 'messages-offers.php?offerId=$offerId';</script>";
    } else {
        echo "<script>alert('Offer cancelled successfully');</script>";
        echo "<script>window.location.href = 'messages-proposals.php?offerId=$offerId';</script>";
    }

} catch (PDOException $e) {
    // Rollback transaction on error
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}


