<?php
  session_start();
  require_once 'dbcon.php';

  function distance($lat1, $lng1, $lat2, $lng2) {
    $earth_radius = 6371; // Radius of the earth in kilometers
    $dlat = deg2rad($lat2 - $lat1);
    $dlng = deg2rad($lng2 - $lng1);
    $a = sin($dlat / 2) * sin($dlat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlng / 2) * sin($dlng / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earth_radius * $c;
    return $distance;
  }

  // Check if item_id is provided via GET
  if (isset($_GET['offer_id'])) {
    $offer_id = $_GET['offer_id'];

    // Query to fetch item details based on item_id
    $query = "SELECT * FROM offers INNER JOIN users ON offers.sender_id = users.user_id WHERE offers.offer_id = :offer_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':offer_id', $offer_id, PDO::PARAM_INT);
    $stmt->execute();
    $offer = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if item found
    if ($offer) {
      // Prepare JSON response
      header('Content-Type: application/json');
      $distance_km = distance($_SESSION["user_details"]["lat"], $_SESSION["user_details"]["lng"], $offer["lat"], $offer["lng"]);
      $offer["distance_km"] = round($distance_km, 1);
      echo json_encode($offer);
      exit;
    } else {
      // Handle case where item is not found
      echo json_encode(['error' => 'Item not found']);
      exit;
    }
  } else {
    // Handle case where item_id is not provided
    echo json_encode(['error' => 'No item_id provided']);
    exit;
  }
?>