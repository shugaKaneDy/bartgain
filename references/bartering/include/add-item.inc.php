<?php
session_start();
require_once "../dbcon.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION["user_details"]["user_id"];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $itemCondition = $_POST['itemCondition'];
    $swapOption = $_POST['swapOption'];
    $description = $_POST['description'];

    $uploadedImages = [];

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['images']['name'][$key];
        $temp_name = $_FILES['images']['tmp_name'][$key];

        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            $unique_name = uniqid('img_', true) . '.' . pathinfo($file_name, PATHINFO_EXTENSION);
            $folder = '../item-photo/' . $unique_name;

            if (move_uploaded_file($temp_name, $folder)) {
                $uploadedImages[] = $unique_name;
            }
        }
    }

    if (!empty($uploadedImages)) {
        // Join the filenames into a comma-separated string
        $imageUrls = implode(',', $uploadedImages);
        $thisDate = date('Y-m-d H:i:s');

        $query = "INSERT INTO items (item_user_id, item_title, item_url_picture, item_category, item_condition, item_swap_option, item_description, item_created_at) 
                  VALUES (:userId, :title, :url_picture, :category, :itemCondition, :swapOption, :description, :thisDate)";
        $stmt = $conn->prepare($query);

        $data = [
            ":userId" => $userId,
            ":title" => $title,
            ":url_picture" => $imageUrls,
            ":category" => $category,
            ":itemCondition" => $itemCondition,
            ":swapOption" => $swapOption,
            ":description" => $description,
            ":thisDate" => $thisDate,
        ];

        $query_execute = $stmt->execute($data);

        if ($query_execute) {
            $_SESSION['message'] = [
                "status" => "success",
                "title" => "Inserted Successfully",
            ];
            header("Location: ../items.php");
            exit(0);
        } else {
            $_SESSION['message'] = [
                "status" => "error",
                "title" => "Not Inserted",
            ];
            header("Location: ../add-item.php");
            exit(0);
        }
    } else {
        $_SESSION['message'] = [
            "status" => "error",
            "title" => "Failed to upload images.",
        ];
        header("Location: ../add-item.php");
        exit(0);
    }
}

?>
