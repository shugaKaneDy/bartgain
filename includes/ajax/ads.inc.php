<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $ad_type = $_POST['ad_type'];

  insertQuery(
    $pdo,
    "INSERT INTO ads
    (
      ad_type,
      ad_amount,
      ad_created_at
    )
    VALUES
    (
      :type,
      :amount,
      :createdAt
    )",
    [
      ":type" => $ad_type,
      ":amount" => rand(10,100),
      ":createdAt" => $currentTime
    ]
  );
  

  

}

?>