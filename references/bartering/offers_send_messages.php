<?php

session_start();
require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $getOfferId = $_POST['messageOfferId'];
    $receiverMessage = $_POST['receiverMessage'];

    $query = "SELECT * FROM offers WHERE offer_id = :offer_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':offer_id', $getOfferId, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_OBJ);
    $result = $stmt->fetch();

    $thisDate = date('Y-m-d H:i:s');

    if ($result) {
        $insertQuery = "INSERT INTO messages (offer_id, sender_user_id, receiver_user_id, receiver_message, message_created_at) 
                        VALUES (:offer_id, :sender_user_id, :receiver_user_id, :receiver_message, :messageCreated)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bindParam(':offer_id', $getOfferId, PDO::PARAM_INT);
        $insertStmt->bindParam(':sender_user_id', $result->sender_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':receiver_user_id', $result->r_receiver_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':receiver_message', $receiverMessage, PDO::PARAM_STR);
        $insertStmt->bindParam(':messageCreated', $thisDate, PDO::PARAM_STR);

        if ($insertStmt->execute()) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to insert message'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Offer not found'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}