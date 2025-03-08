<?php
session_start();
require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $qrCode = $_POST['qrCode'];

    try {
        // Start the transaction
        $conn->beginTransaction();

        // Select the meet up details
        $selectQuery = "SELECT * FROM meet_up WHERE qrCode = :qrCode";
        $selectStmt = $conn->prepare($selectQuery);
        $selectStmt->bindParam(':qrCode', $qrCode);
        $selectStmt->execute();
        $selectResult = $selectStmt->fetch(PDO::FETCH_OBJ);

        if (!$selectResult) {
            throw new Exception("Meet up not found.");
        }

        // Select the offer details
        $offerQuery = "SELECT * FROM offers WHERE offer_id = :offer_id";
        $offerStmt = $conn->prepare($offerQuery);
        $offerStmt->bindParam(':offer_id', $selectResult->offer_id);
        $offerStmt->execute();
        $offerResult = $offerStmt->fetch(PDO::FETCH_OBJ);

        if (!$offerResult) {
            throw new Exception("Offer not found.");
        }

        // Select the item details
        $itemQuery = "SELECT * FROM items WHERE item_id = :item_id";
        $itemStmt = $conn->prepare($itemQuery);
        $itemStmt->bindParam(':item_id', $offerResult->item_id);
        $itemStmt->execute();
        $itemResult = $itemStmt->fetch(PDO::FETCH_OBJ);

        if (!$itemResult) {
            throw new Exception("Item not found.");
        }

        // Update the item status to "completed"
        $updateItemQuery = "UPDATE items SET item_status = 'completed' WHERE item_id = :item_id";
        $updateItemStmt = $conn->prepare($updateItemQuery);
        $updateItemStmt->bindParam(':item_id', $itemResult->item_id);
        $updateItemStmt->execute();

        // Update the meet_up status to "completed"
        $updateMeetUpQuery = "UPDATE meet_up SET meet_up_status = 'completed' WHERE meet_up_id = :meet_up_id";
        $updateMeetUpStmt = $conn->prepare($updateMeetUpQuery);
        $updateMeetUpStmt->bindParam(':meet_up_id', $selectResult->meet_up_id);
        $updateMeetUpStmt->execute();

        $thisDate = date("Y-m-d H:i:s");

        // Insert rating for sender
        $insertRatingQuery = "INSERT INTO ratings (meet_up_id, rate_your_id, rate_partner_id, rate_status, rate_created_at) 
                            VALUES (:meet_up_id, :rate_your_id, :rate_partner_id, 'pending', :rate_created_at)";
        $insertRatingStmtSender = $conn->prepare($insertRatingQuery);
        $insertRatingStmtSender->bindParam(':meet_up_id', $selectResult->meet_up_id);
        $insertRatingStmtSender->bindParam(':rate_your_id', $selectResult->sender_id);
        $insertRatingStmtSender->bindParam(':rate_partner_id', $selectResult->receiver_id);
        $insertRatingStmtSender->bindParam(':rate_created_at', $thisDate);
        $insertRatingStmtSender->execute();

        // Insert rating for receiver
        $insertRatingStmtReceiver = $conn->prepare($insertRatingQuery);
        $insertRatingStmtReceiver->bindParam(':meet_up_id', $selectResult->meet_up_id);
        $insertRatingStmtReceiver->bindParam(':rate_your_id', $selectResult->receiver_id);
        $insertRatingStmtReceiver->bindParam(':rate_partner_id', $selectResult->sender_id);
        $insertRatingStmtReceiver->bindParam(':rate_created_at', $thisDate);
        $insertRatingStmtReceiver->execute();

        // Commit the transaction
        $conn->commit();

        echo "Transaction completed successfully.";
        header("Location: meet-up-information.php?meetUpId=" . $selectResult->meet_up_id);
        exit;
    } catch (Exception $e) {
        // Rollback the transaction if something failed
        $conn->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}
?>
