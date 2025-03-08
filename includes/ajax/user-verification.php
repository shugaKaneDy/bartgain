<?php
function errorResponse($message) {

  $respond = [
    'status' => 'error',
    'title' => $message
  ];
  echo json_encode($respond);
  exit;
}

function successResponse($message) {

  $respond = [
    'status' => 'success',
    'title' => $message
  ];
  echo json_encode($respond);
  exit;
}

function selectQuery($pdo, $query, $data) {

  $stmt = $pdo->prepare($query);
  $stmt->execute($data);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return $results;
}

function selectQueryFetch($pdo, $query, $data) {

  $stmt = $pdo->prepare($query);
  $stmt->execute($data);
  $results = $stmt->fetch(PDO::FETCH_ASSOC);

  return $results;
}

function updateQuery($pdo, $query, $data, $withResponse = false) {

  $stmt = $pdo->prepare($query);
  $query_execute = $stmt->execute($data);

  if($withResponse) {

    if($query_execute) {
  
      successResponse("User verified successfully");
    } else {
  
      errorResponse("Failed to verify User");
    }
  }


}

function insertQuery($pdo, $query, $data, $withResponse = false) {

  $stmt = $pdo->prepare($query);
  $queryExecute = $stmt->execute($data);

  if($withResponse) {
    if ($queryExecute) {

      successResponse("Account created successfully");
    } else {
  
      errorResponse("Account not registered");
    }
  }

  
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();

  $function = $_GET["function"];
  $currentTime  = date("Y-m-d H:i:s");

  if($function == 'verify') {

    $lastname = htmlspecialchars($_POST['lastname']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $middlename = htmlspecialchars($_POST['middlename']);

    $fullname = $firstname . " " . $middlename . " " . $lastname;
    $address = htmlspecialchars($_POST['address']) ;
    $primary_id = htmlspecialchars($_POST['primary_id']) ;
    $supporting_document = htmlspecialchars($_POST['supporting_document']) ;
    $birthDate = $_POST['birthDate'];
    $capturedImageData = $_POST['captured_image_data'];
    $percentage = $_POST['percentage'];

    $date = date('Y-m-d');
    $rand = rand(10000, 99999);

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token_id_verification']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_id_verification']) {
      errorResponse("Invalid CSRF token");
    }
  

    // Validate required fields
    if (empty($lastname) || empty($firstname) || empty($address) || empty($primary_id) || empty($supporting_document)) {
      errorResponse("Please fill in all required fields, including Primary ID and Supporting Document.");
    }

    // Validate file uploads
    if (empty($_FILES['uploadId']['tmp_name'])) {
      errorResponse("Please upload the ID picture.");
    }

    // Validate file uploads
    if (empty($_FILES['uploadBackId']['tmp_name'])) {
      errorResponse("Please upload the Back ID picture.");
    }

    if (empty($_FILES['supportDocPic']['tmp_name'])) {
      errorResponse("Please upload the Supporting Document picture.");
    }

    // print_r($_POST);
    // print_r($_FILES);
    // exit;

   

    // Save captured image
    $captureFolderPath = '../../captured-images/';
    $imageParts = explode(";base64,", $capturedImageData);
    $imageTypeAux = explode("image/", $imageParts[0]);
    $imageType = $imageTypeAux[1];
    $imageBase64 = base64_decode($imageParts[1]);
    $captureFileName = $date . '-' . $rand . '.png';
    $captureFilePath = $captureFolderPath . $captureFileName;
    file_put_contents($captureFilePath, $imageBase64);

    // Save ID picture
    $idPicture = $_FILES['uploadId'];
    $idFileName =  $date . '-' . $rand . basename($idPicture['name']);
    move_uploaded_file($idPicture['tmp_name'], "../../id-uploads/$idFileName");

    // Save Back ID Picture
    $idBackPicture = $_FILES['uploadBackId'];
    $idBackFileName =  $date . '-' . $rand . basename($idBackPicture['name']);
    move_uploaded_file($idBackPicture['tmp_name'], "../../id-back-uploads/$idBackFileName");

    // Save SupportDoc picture
    $supDocPicture = $_FILES['supportDocPic'];
    $supDocFileName =  $date . '-' . $rand . basename($supDocPicture['name']);
    move_uploaded_file($supDocPicture['tmp_name'], "../../sup-doc-uploads/$supDocFileName");

    // print_r($_POST);
    // print_r($_FILES);
    // exit;


    try {

      $pdo->beginTransaction();

      insertQuery(
        $pdo,
        "INSERT INTO verification
        (
            verification_random_id,
            verification_user_id,
            verification_percentage,
            verification_lastname,
            verification_firstname,
            verification_middlename,
            verification_fullname,
            verification_birth_date,
            verification_address,
            verification_id_uploads,
            verification_back_id_uploads,
            verification_card_type,
            verification_sup_doc,
            verification_sup_doc_type,
            verification_capture_image,
            verification_created_at
        )
        VALUES
        (
            :verificationRandomId,
            :verificationUserId,
            :verificationPercentage,
            :verificationLastname,
            :verificationFirstname,
            :verificationMiddlename,
            :verificationFullname,
            :verificationBirthDate,
            :verificationAddress,
            :verificationIdUploads,
            :verificationBackIdUploads,
            :verificationCardType,
            :verificationSupDoc,
            :verificationSupDocType,
            :verificationCaptureImage,
            :verificationCreatedAt
        )",
        [
            ':verificationRandomId' => uniqid(),
            ':verificationUserId' => $_SESSION['user_details']['user_id'],
            ':verificationPercentage' => $percentage,
            ':verificationLastname' => $lastname,
            ':verificationFirstname' => $firstname,
            ':verificationMiddlename' => $middlename,
            ':verificationFullname' => $fullname,
            ':verificationBirthDate' => $birthDate,
            ':verificationAddress' => $address,
            ':verificationIdUploads' => $idFileName, // file path or URL for uploaded IDs
            ':verificationBackIdUploads' => $idBackFileName, // file path or URL for uploaded IDs
            ':verificationCardType' => $primary_id, // file path or URL for uploaded IDs
            ':verificationSupDoc' => $supDocFileName, // file path or URL for uploaded IDs
            ':verificationSupDocType' => $supporting_document, // file path or URL for uploaded IDs
            ':verificationCaptureImage' => $captureFileName, // file path or URL for captured image
            ':verificationCreatedAt' => $currentTime // current timestamp
        ]
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
          ":actType" => "verification",
          ":description" => "Verification submitted with fullname: $fullname. Date submitted: $currentTime",
          ":ipAdd" => $_SERVER['REMOTE_ADDR'],
          ":device" => $_SERVER['HTTP_USER_AGENT'],
          ":createdAt" => $currentTime
        ]
      );

      // Commit the transaction if both queries succeed
      $pdo->commit();

      unset($_SESSION['csrf_token_id_verification']);

      successResponse("Your verification has been successfully submitted! Our team is currently reviewing your information. Please allow up to 24-48 hours for the verification process. You’ll receive a notification once it’s complete. Thank you for being a valued member of Bartgain!");


    } catch (PDOException $e) {

      $pdo->rollBack();
      errorResponse("Transaction failed: " . $e->getMessage());
    }

  }

}