<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $allowed = [
    'jpg',
    'jpeg',
    'png',
  ];


  // print_r($_FILES);
  // exit;

   // empty file
   if(empty($_FILES['profilePicture']['name'][0])) {
    
    errorResponse("Add at least 1 Picture/Video");
  }

  $fileNameValidation = $_FILES['profilePicture']['name'];
  $extFileNameValidation = explode('.', $fileNameValidation);
  $extFileNameValidation = end($extFileNameValidation);

  if(!in_array($extFileNameValidation, $allowed)) {

    errorResponse("File submitted not allowed");
  }

  $date = date('Y-m-d');
  $rand = rand(10000, 99999);

  $uniqueName = $date . '-' . $rand . '-' . $_FILES['profilePicture']['name'];
  move_uploaded_file($_FILES['profilePicture']['tmp_name'], "../../profile-uploads/$uniqueName");

  

  
  try {
    
    $pdo->beginTransaction();

    $profileResult = selectQueryFetch(
      $pdo,
      "SELECT profile_picture FROM users WHERE user_id = :userId",
      [
        ":userId" => $_SESSION['user_details']['user_id']
      ]
    );

    if ($profileResult && !empty($profileResult['profile_picture'])) {
      $previousPicture = $profileResult['profile_picture'];
      $previousFilePath = "../../profile-uploads/$previousPicture";

      // Check if the file exists before attempting to delete
      if (file_exists($previousFilePath)) {
          unlink($previousFilePath);
      }
    }

    updateQuery(
      $pdo,
      "UPDATE users SET
      profile_picture = :profilePicture
      WHERE user_id = :userId",
      [
        ":profilePicture" => $uniqueName,
        ":userId" => $_SESSION['user_details']['user_id'],
      ]
    );
    
    $pdo->commit();
    $_SESSION['user_details']['profile_picture'] = $uniqueName;
    successResponse("File uploaded successfully");


  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }



  
  
  

}