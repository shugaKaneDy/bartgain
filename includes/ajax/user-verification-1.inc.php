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

    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $birthDate = $_POST['birthDate'];
    $_SESSION['user_details']['email'];

    print_r($_POST);
    print_r($_FILES);
    exit;

    try {

      $pdo->beginTransaction();

      updateQuery(
        $pdo,
        "UPDATE users SET fullname = :fullname, address = :address, birth_date = :birthDate, verified = :verified WHERE user_id = :user_id",
        [
                ":fullname" => $fullname,
                ":address" => $address,
                ":birthDate" => $birthDate,
                ":user_id" => $_SESSION['user_details']['user_id'], 
                ":verified" => "Y", 
              ]
      );

      insertQuery(
        $pdo,
        "INSERT INTO verified_user (user_id, verification_date) VALUES (:user_id, :verification_date)",
        [
                "user_id" => $_SESSION['user_details']['user_id'], 
                "verification_date" => $currentTime,
              ]
      );

      // Commit the transaction if both queries succeed
      $pdo->commit();

      successResponse("User Verified Successfully");

    } catch (PDOException $e) {

      $pdo->rollBack();
      errorResponse("Transaction failed: " . $e->getMessage());
    }

  }

}