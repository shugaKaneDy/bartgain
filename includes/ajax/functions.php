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

function getSize($size) {

  $kbSize = $size/1024;
  $formatSize = number_format($kbSize, 2). ' KB';
  return $formatSize;
}

function insertQueryItem($pdo, $query, $data, $withResponse = false, $message = "") {

  $stmt = $pdo->prepare($query);
  $queryExecute = $stmt->execute($data);

  if($withResponse) {
    if ($queryExecute) {

      successResponse($message);
    } else {
  
      $message = "Can't uplaod item";
      errorResponse($message);
    }
  }
}

