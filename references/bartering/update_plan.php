<?php
session_start();
require_once 'dbcon.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you are receiving offerId, offerLat, offerLng, offerMeetUpPlace, and offerDateTime from the form

    $function = $_GET["function"];

    if ($function == "offer") {
        $offerId = $_POST['offerId'];
        $offerLat = $_POST['offerLat'];
        $offerLng = $_POST['offerLng'];
        $offerMeetUpPlace = $_POST['offerMeetUpPlace'];
        $offerDateTime = $_POST['offerDateTime'];

        $thisDate = date("Y-m-d H:i:s");

        // Perform any validation or processing as needed
        // For example, update the offer with the new information
        $updateQuery = "UPDATE offers SET offer_meet_up_place = :offerMeetUpPlace, offer_date_time_meet = :offerDateTime, offer_lat = :offerLat, offer_lng = :offerLng WHERE offer_id = :offerId";
        $selectQuery = "SELECT * FROM offers WHERE offer_id = :offerId";
        $insertQuery = "INSERT INTO messages (offer_id, sender_user_id, receiver_user_id, receiver_message, message_type, message_created_at)
                        VALUES (:offerId, :senderUserId, :receiverUserId, :receiverMessage, 'update', :messageCreated)";

        try {
            // Start a transaction
            $conn->beginTransaction();

            // Update offer details
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->execute([
                'offerMeetUpPlace' => $offerMeetUpPlace,
                'offerDateTime' => $offerDateTime,
                'offerLat' => $offerLat,
                'offerLng' => $offerLng,
                'offerId' => $offerId,
            ]);

            // Check if any rows were affected
            if ($updateStmt->rowCount() > 0) {
                // Fetch updated offer details
                $selectStmt = $conn->prepare($selectQuery);
                $selectStmt->execute(['offerId' => $offerId]);
                $offerDetails = $selectStmt->fetch(PDO::FETCH_ASSOC);

                // Insert message into messages table
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->execute([
                    'offerId' => $offerId,
                    'senderUserId' => $offerDetails['sender_id'],
                    'receiverUserId' => $offerDetails['r_receiver_id'],
                    'receiverMessage' => "Meet up: $offerMeetUpPlace, date and time: $offerDateTime",
                    'messageCreated' => $thisDate,
                ]);

                // Check if message was successfully inserted
                if ($insertStmt->rowCount() > 0) {
                    // Commit the transaction if everything is successful
                    $conn->commit();
                    $response = ['status' => 'success', 'message' => 'Plan updated successfully.'];
                } else {
                    // Rollback the transaction if the message insertion fails
                    $conn->rollback();
                    $response = ['status' => 'error', 'message' => 'Failed to insert message.'];
                }
            } else {
                // Rollback the transaction if the update operation fails
                $conn->rollback();
                $response = ['status' => 'error', 'message' => 'No changes made or unauthorized access.'];
            }
        } catch (PDOException $e) {
            // Rollback the transaction on any database error
            $conn->rollback();
            $response = ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    if ($function == 'proposal') {
        $offerId = $_POST['offerId'];
        $offerLat = $_POST['offerLat'];
        $offerLng = $_POST['offerLng'];
        $offerMeetUpPlace = $_POST['offerMeetUpPlace'];
        $offerDateTime = $_POST['offerDateTime'];

        $thisDate = date("Y-m-d H:i:s");

        // Perform any validation or processing as needed
        // For example, update the offer with the new information
        $updateQuery = "UPDATE offers SET offer_meet_up_place = :offerMeetUpPlace, offer_date_time_meet = :offerDateTime, offer_lat = :offerLat, offer_lng = :offerLng WHERE offer_id = :offerId";
        $selectQuery = "SELECT * FROM offers WHERE offer_id = :offerId";
        $insertQuery = "INSERT INTO messages (offer_id, sender_user_id, receiver_user_id, sender_message, message_type, message_created_at)
                        VALUES (:offerId, :senderUserId, :receiverUserId, :senderMessage, 'update', :messageCreated)";

        try {
            // Start a transaction
            $conn->beginTransaction();

            // Update offer details
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->execute([
                'offerMeetUpPlace' => $offerMeetUpPlace,
                'offerDateTime' => $offerDateTime,
                'offerLat' => $offerLat,
                'offerLng' => $offerLng,
                'offerId' => $offerId,
            ]);

            // Check if any rows were affected
            if ($updateStmt->rowCount() > 0) {
                // Fetch updated offer details
                $selectStmt = $conn->prepare($selectQuery);
                $selectStmt->execute(['offerId' => $offerId]);
                $offerDetails = $selectStmt->fetch(PDO::FETCH_ASSOC);

                // Insert message into messages table
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->execute([
                    'offerId' => $offerId,
                    'senderUserId' => $offerDetails['sender_id'],
                    'receiverUserId' => $offerDetails['r_receiver_id'],
                    'senderMessage' => "Meet up: $offerMeetUpPlace, date and time: $offerDateTime",
                    ':messageCreated' => $thisDate
                ]);

                // Check if message was successfully inserted
                if ($insertStmt->rowCount() > 0) {
                    // Commit the transaction if everything is successful
                    $conn->commit();
                    $response = ['status' => 'success', 'message' => 'Plan updated successfully.'];
                } else {
                    // Rollback the transaction if the message insertion fails
                    $conn->rollback();
                    $response = ['status' => 'error', 'message' => 'Failed to insert message.'];
                }
            } else {
                // Rollback the transaction if the update operation fails
                $conn->rollback();
                $response = ['status' => 'error', 'message' => 'No changes made or unauthorized access.'];
            }
        } catch (PDOException $e) {
            // Rollback the transaction on any database error
            $conn->rollback();
            $response = ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    
} else {
    // If request method is not POST, return an error response
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
