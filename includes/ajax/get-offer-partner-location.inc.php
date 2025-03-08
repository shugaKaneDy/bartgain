<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();

  $offerRandomId = $_GET["offer_random_id"];

  $currentTime  = date("Y-m-d H:i:s");

  echo $offerRandomId;
  


}

?>