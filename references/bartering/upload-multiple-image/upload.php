// upload.php
<?php
if (!empty($_FILES['images']['name'][0])) {
    $uploadDirectory = 'uploads/';
    foreach ($_FILES['images']['name'] as $key => $name) {
        $tmpName = $_FILES['images']['tmp_name'][$key];
        $fileName = basename($name);
        $targetFile = $uploadDirectory . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($tmpName, $targetFile)) {
            echo json_encode(['success' => true, 'file' => $targetFile]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error uploading file']);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No files uploaded']);
}
?>
