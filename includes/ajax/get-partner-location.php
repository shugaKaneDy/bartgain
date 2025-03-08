<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");
  

  // print_r($_POST);

  $partnerId = $_POST['partnerId'];

  $partnerInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM users
    WHERE user_random_id = :partnerId",
    [
      "partnerId" => $partnerId
    ]
  );

  // print_r($partnerInfo);
  $respond = [
    'lng' => $partnerInfo['lng'],
    'lat' => $partnerInfo['lat'],
    'myLat' => $_SESSION['user_details']['lat'],
    'myLng' => $_SESSION['user_details']['lng'],
  ];
  echo json_encode($respond);
  exit;

  


}

?>