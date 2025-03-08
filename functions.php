<?php

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