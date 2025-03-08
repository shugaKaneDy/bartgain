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
if (isset($_GET["offerId"])) {
    $offerId = $_GET["offerId"];
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
    $updateQuery = "UPDATE offers SET offer_status = 'accepted' WHERE offer_id = :offerId";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':offerId', $offerId, PDO::PARAM_INT);
    $stmt->execute();

    // Select offer details
    $selectQuery = "SELECT * FROM offers WHERE offer_id = :offerId";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bindParam(':offerId', $offerId, PDO::PARAM_INT);
    $stmt->execute();
    $offerDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    $updateItemQuery = "UPDATE items SET item_status = :itemStatus WHERE item_id = :itemId";
    $updateItemStmt = $conn->prepare($updateItemQuery);
    $updateItemStatus = "pending";
    $updateItemStmt->bindParam(':itemStatus', $updateItemStatus, PDO::PARAM_STR);
    $updateItemStmt->bindParam(':itemId', $offerDetails["item_id"], PDO::PARAM_INT);
    $updateItemStmt->execute();

    // Generate random QR code (example)
    $qrCode = generateRandomString(); // You need to define this function
    $thisDate = date("Y-m-d H:i:s");

    // Insert into meet_up table
    $insertQuery = "INSERT INTO meet_up (offer_id, meet_up_place, meet_up_date, meet_up_lng, meet_up_lat, sender_id, qrCode, receiver_id, created_at)
                    VALUES (:offerId, :meetUpPlace, :meetUpDate, :meetUpLng, :meetUpLat, :senderId, :qrCode, :receiverId, :createdAt)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bindParam(':offerId', $offerId, PDO::PARAM_INT);
    $stmt->bindParam(':meetUpPlace', $offerDetails['offer_meet_up_place'], PDO::PARAM_STR);
    $stmt->bindParam(':meetUpDate', $offerDetails['offer_date_time_meet'], PDO::PARAM_STR);
    $stmt->bindParam(':meetUpLng', $offerDetails['offer_lng'], PDO::PARAM_STR);
    $stmt->bindParam(':meetUpLat', $offerDetails['offer_lat'], PDO::PARAM_STR);
    $stmt->bindParam(':senderId', $offerDetails['sender_id'], PDO::PARAM_INT);
    $stmt->bindParam(':qrCode', $qrCode, PDO::PARAM_STR);
    $stmt->bindParam(':receiverId', $offerDetails['r_receiver_id'], PDO::PARAM_INT);
    $stmt->bindParam(':createdAt', $thisDate, PDO::PARAM_STR);
    $stmt->execute();

    notifyUserOfOfferAcceptance($offerDetails["sender_id"], $offerId);

    // Commit transaction
    $conn->commit();

    // Success message
    echo "<script>alert('Offer accepted successfully');</script>";
    echo "<script>window.location.href = 'messages-offers.php?offerId=$offerId';</script>";

} catch (PDOException $e) {
    // Rollback transaction on error
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}

// Function to generate random QR code (example function)
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
