<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();

  $function = $_GET["function"];
  $currentTime  = date("Y-m-d H:i:s");

  if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token_add_item']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_add_item']) {
    errorResponse("Invalid CSRF token");
  }

  

  // print_r($_POST);
  // exit;

  // echo "<pre>";
  // print_r($_FILES['itemUrlPicture']['name'][0]);
  // echo "</pre>";

  $title = htmlspecialchars($_POST['title']) ;
  $category = htmlspecialchars($_POST['category']) ;
  $condition = htmlspecialchars($_POST['condition']) ;
  $swapOption = htmlspecialchars($_POST['swapOption']) ;
  $description = htmlspecialchars($_POST['description']) ;
  $estimatedValue = $_POST['estimatedValue'];
  // $fileName = implode(',', $_FILES['itemUrlPicture']['name']);
  // echo $fileName;

  // echo $_FILES['itemUrlPicture']['name'][0];
  // echo $_FILES['itemUrlPicture']['tmp_name'][0];

  $allowed = [
    'jpg',
    'jpeg',
    'png',
    'webp',
    'mp4',
    'webm',
    'ogg',
  ];

  $itemsResults = selectQuery(
    $pdo,
    "SELECT * FROM items 
    WHERE item_user_id = :user_id
    AND item_status IN ('pending', 'available')
    ORDER BY item_created_at DESC",
    [
        ":user_id" => $_SESSION['user_details']['user_id'],
    ]
  );
  $countItems = count($itemsResults);
  // print_r($countItems);
  // exit;

  /* Premium Validation */
  if($countItems >= 5 && $_SESSION['user_details']['role_id'] == 2) {

  } else {
    if($_SESSION['user_details']['user_is_prem'] == "Yes") {
      if($countItems >= 5 && $_SESSION['user_details']['role_id'] == 1) {
        errorResponse("You already listed 5 items");
      }
    } else {
      if($countItems >= 2 && $_SESSION['user_details']['role_id'] == 1) {
        errorResponse("You already listed 2 items. Buy premium to list 5 items!");
      }
    }
  }
  
  // empty file
  if(empty($_FILES['itemUrlPicture']['name'][0])) {
    
    errorResponse("Add at least 1 Picture/Video");
  }
  
  // fill up fields
  if(empty($title) || empty($description) || empty($estimatedValue)) {

    errorResponse("Please fill out all the fields!!!");
  }

  $total = count($_FILES['itemUrlPicture']['name']);

  // number of items
  if($total > 2) {

    errorResponse("You can only upload 2 files");
  }

  // size and file extension
  for ($i = 0; $i < $total; $i++) {

    $fileSizeKb = (int)getSize($_FILES['itemUrlPicture']['size'][$i]);
    $fileNameValidation = $_FILES['itemUrlPicture']['name'][$i];
    $extFileNameValidation = explode('.', $fileNameValidation);
    $extFileNameValidation = end($extFileNameValidation);

    if($fileSizeKb > 15000) {

      errorResponse("File Size must not exeed to 15mb");
    }

    if(!in_array($extFileNameValidation, $allowed)) {

      errorResponse("File submitted not allowed");
    }
  }

  $uploadedFiles = [];

  for($i = 0; $i < $total; $i++) {

    $date = date('Y-m-d');
    $rand = rand(10000, 99999);

    $uniqueName = $date . '-' . $rand . '-' . $_FILES['itemUrlPicture']['name'][$i];
    move_uploaded_file($_FILES['itemUrlPicture']['tmp_name'][$i], "../../item-uploads/$uniqueName");
    $uploadedFiles[] = $uniqueName;
  }

  // echo "<pre>";
  // print_r($uploadedFiles);
  // echo "</pre>";

  insertQueryItem(
    $pdo,
    "INSERT INTO items (item_random_id, item_user_id, item_title, item_est_val, item_url_file, item_category, item_swap_option, item_condition, item_description, item_lng, item_lat, item_current_location, item_status, item_created_at) VALUES (:itemRandomId, :itemUserId, :itemTitle, :itemEstimatedValue, :itemUrlFile, :itemCategory, :itemSwapOption, :itemCondition, :itemDescription, :itemLng, :itemLat, :itemCurrentLocation, :itemStatus, :itemCreatedAt)",
    [
      "itemRandomId" => rand(10000000, 99999999),
      ":itemUserId" => $_SESSION['user_details']['user_id'],
      ":itemTitle" => $title,
      ":itemEstimatedValue" => $estimatedValue,
      ":itemUrlFile" => implode(',', $uploadedFiles),
      ":itemCategory" => $category,
      ":itemSwapOption" => $swapOption,
      ":itemCondition" => $condition,
      ":itemDescription" => $description,
      ":itemLng" => $_SESSION['user_details']['lng'],
      ":itemLat" => $_SESSION['user_details']['lat'],
      ":itemCurrentLocation" => $_SESSION['user_details']['current_location'],
      ":itemStatus" => "available",
      ":itemCreatedAt" => $currentTime,
    ],
  );

  insertQuery(
    $pdo,
    "INSERT INTO activity_logs
    (
      act_log_user_id,
      act_log_act_type,
      act_log_description,
      act_log_ip_add,
      act_log_device,
      act_log_created_at
    )
    VALUES
    (
      :userId,
      :actType,
      :description,
      :ipAdd,
      :device,
      :createdAt
    )
    ",
    [
      ":userId" => $_SESSION["user_details"]["user_id"],
      ":actType" => "add item",
      ":description" => "You add an item: " . $title,
      ":ipAdd" => $_SERVER['REMOTE_ADDR'],
      ":device" => $_SERVER['HTTP_USER_AGENT'],
      ":createdAt" => $currentTime
    ]
  );

  unset($_SESSION['csrf_token_add_item']);

  successResponse("Item Uploaded Successfully");

}