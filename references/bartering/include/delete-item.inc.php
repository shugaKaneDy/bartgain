<?php
session_start();
require_once "../dbcon.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_id'];

    // Fetch the item details to get the image filename
    $query = "SELECT url_picture FROM items WHERE item_id = :itemId AND user_id = :userId";
    $stmt = $conn->prepare($query);
    $stmt->execute([':itemId' => $itemId, ':userId' => $_SESSION["user_details"]["user_id"]]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $imageFile = $item['url_picture'];

        // Delete the image file from the directory
        $filePath = "../item-photo/" . $imageFile;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the item from the database
        $deleteQuery = "DELETE FROM items WHERE item_id = :itemId AND user_id = :userId";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteResult = $deleteStmt->execute([':itemId' => $itemId, ':userId' => $_SESSION["user_details"]["user_id"]]);

        // Set the session message based on the result
        if ($deleteResult) {
            $_SESSION['message'] = [
                "status" => "success",
                "title" => "Item deleted successfully.",
            ];
        } else {
            $_SESSION['message'] = [
                "status" => "error",
                "title" => "Failed to delete item.",
            ];
        }
    } else {
        $_SESSION['message'] = [
            "status" => "error",
            "title" => "Item not found.",
        ];
    }

    header("Location: ../add-item.php");
    exit(0);
} else {
    $_SESSION['message'] = [
        "status" => "error",
        "title" => "Invalid request method.",
    ];
    header("Location: ../add-item.php");
    exit(0);
}
?>
