<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");
  
  /* session validation */
  if(!isset($_SESSION['user_details'])) {
    exit;
  }

  // print_r($_POST);

  $lat = htmlspecialchars($_POST['lat']);
  $lon = htmlspecialchars($_POST['lon']);
  $location = htmlspecialchars($_POST['location']);

  $locationLower = strtolower($location);
  // $location = "vnsfnesufnuesfes";


  if (strpos($locationLower, 'cavite') == false) {
    unset($_SESSION['user_details']);
    errorResponse("This application is limited to Cavite only.");
  } else {
    updateQuery(
      $pdo,
      "UPDATE users SET lat = :lat, lng = :lon, current_location = :currentLocation
      WHERE user_id = :userId",
      [
        ":lat" => $lat,
        ":lon" => $lon,
        ":currentLocation" => $location,
        ":userId" => $_SESSION['user_details']['user_id'],
      ]
    );

    $_SESSION['user_details']['current_location'] = $location;
    $_SESSION['user_details']['lat'] = $lat;
    $_SESSION['user_details']['lng'] = $lon;
    successResponse("Welcome");
  }

  
}

?>