<?php
session_start();
require 'database_connection.php'; // Assuming you have a file to handle DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $birthDate = $_POST['birthDate'];
  $capturedImageData = $_POST['captured_image_data'];
  $user_id = $_SESSION["user_details"]["user_id"];

  // Save captured image
  $captureFolderPath = 'capture_images/';
  $imageParts = explode(";base64,", $capturedImageData);
  $imageTypeAux = explode("image/", $imageParts[0]);
  $imageType = $imageTypeAux[1];
  $imageBase64 = base64_decode($imageParts[1]);
  $captureFileName = uniqid() . '.png';
  $captureFilePath = $captureFolderPath . $captureFileName;
  file_put_contents($captureFilePath, $imageBase64);

  // Save ID picture
  $idFolderPath = 'verification-capture-image/';
  $idPicture = $_FILES['id_picture'];
  $idFileName = uniqid() . '_' . basename($idPicture['name']);
  $idFilePath = $idFolderPath . $idFileName;
  move_uploaded_file($idPicture['tmp_name'], $idFilePath);

  $thisDate = date('Y-m-d H:i:s');

  // Insert data into database
  $stmt = $conn->prepare("INSERT INTO verifications (verification_birth_date, id_picture_path, user_id, capture_image_path, verification_created_at) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("ssiss", $birthDate, $idFilePath, $user_id, $captureFilePath, $thisDate);

  if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Verification data saved successfully."]);
  } else {
    echo json_encode(["status" => "error", "message" => "Error saving verification data."]);
  }

  $stmt->close();
  $conn->close();
}
?>
