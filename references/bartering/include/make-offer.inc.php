<?php
session_start();
require_once "../dbcon.php";
$sessionId = $_SESSION["user_details"]["user_id"]; // Assuming this is a placeholder for a dynamically retrieved session ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $title = $_POST['title'];
    $file_name = $_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];
    $unique_file_name = uniqid() . '-' . $file_name; // Make the file name unique
    $folder = '../offer-item-photo/' . $unique_file_name; // Update the folder path

    $category = $_POST['category'];
    $itemCondition = $_POST['itemCondition'];
    $description = $_POST['description'];

    $query = "SELECT * FROM items WHERE item_id = :item_id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':item_id' => $item_id]);
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    $thisDate = date('Y-m-d H:i:s');

    // Check if the file was uploaded without errors
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Move the uploaded file to the specified folder
        if (move_uploaded_file($temp_name, $folder)) {
            // Prepare the SQL query to insert the item data
            $query = "INSERT INTO offers (item_id, sender_id, offer_title, offer_url_picture, offer_category, offer_item_condition, offer_description, r_receiver_id, offer_created_at, offer_updated_at) 
            VALUES (:item_id, :sender_id, :offer_title, :offer_url_picture, :offer_category, :offer_item_condition, :offer_description, :r_receiver_id, :offerCreated, :offerUpdated)";

            $stmt = $conn->prepare($query);

            // Bind the data to the query parameters
            $data = [
                ":item_id" => $item_id,
                ":sender_id" => $sessionId, // Assuming the sender_id is the current session ID
                ":offer_title" => $title,
                ":offer_url_picture" => $unique_file_name, // Use the unique file name
                ":offer_category" => $category,
                ":offer_item_condition" => $itemCondition,
                ":offer_description" => $description,
                ":r_receiver_id" => $result->item_user_id ?? null,
                ":offerCreated" => $thisDate,
                ":offerUpdated" => $thisDate
            ];

            // Execute the query
            $query_execute = $stmt->execute($data);

            // Execute the query
            $query_execute = $stmt->execute($data);

            // Check if the query was executed successfully
            if ($query_execute) {
                // Get the last inserted offer ID
                $offer_id = $conn->lastInsertId();

                // Insert a new message
                $query = "INSERT INTO messages (offer_id, sender_user_id, receiver_user_id, sender_message, message_type, message_created_at) 
                          VALUES (:offer_id, :sender_user_id, :receiver_user_id, :sender_message, :message_type, :messageCreated)";
                $stmt = $conn->prepare($query);

                $message_data = [
                    ":offer_id" => $offer_id,
                    ":sender_user_id" => $sessionId,
                    ":receiver_user_id" => $result->item_user_id,
                    ":sender_message" => $title,
                    ":message_type" => "title",
                    ":messageCreated" => $thisDate,
                ];

                $stmt->execute($message_data);

                $_SESSION['message'] = "Inserted Successfully";
                header("Location: ../messages-proposals.php?offerId=" . $offer_id);
                exit(0);
            } else {
                $_SESSION['message'] = "Not Inserted";
                exit(0);
            }
        } else {
            $_SESSION['message'] = "Failed to move uploaded image.";
            exit(0);
        }
    } else {
        // Handle file upload errors
        switch ($_FILES['image']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the maximum file size limit.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by a PHP extension.";
                break;
            default:
                $message = "Unknown upload error.";
                break;
        }
        $_SESSION['message'] = $message;
        exit(0);
    }
}
?>
