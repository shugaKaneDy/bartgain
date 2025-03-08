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
  if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    // Query to fetch item details based on item_id
    $query = "SELECT * FROM items INNER JOIN users ON items.item_user_id = users.user_id WHERE item_id = :item_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if item found
    if ($item) {
      // Prepare JSON response
      header('Content-Type: application/json');
      $distance_km = distance($_SESSION["user_details"]["lat"], $_SESSION["user_details"]["lng"], $item["lat"], $item["lng"]);
      $item["distance_km"] = round($distance_km, 1);

      $totalRating = 0;
      if($item["user_rate_count"] == 0) {
        $totalRating = 0;
      } else {
        $totalRating = $item["user_rating"] / $item["user_rate_count"];
      }
      $totalRating = round($totalRating, 1);
      $item["totalRating"] = $totalRating;

      echo json_encode($item);
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