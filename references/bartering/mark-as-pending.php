<?php

session_start();
require_once 'dbcon.php';

if(!empty($_SESSION["user_details"])) {
  $userId = $_SESSION["user_details"]["user_id"];
} else {
  ?>
    <script>
      alert("You must login first");
      window.location.href = "sign-in.php"
    </script>
  <?php
  die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $itemId = $_POST["itemId"];
  echo $itemId;

  $query = "UPDATE items set item_status = 'pending' WHERE item_id = :itemId";
  $stmt = $conn->prepare($query);
  $data = [
    ":itemId" => $itemId
  ];
  $stmt->execute($data);

  header("Location: item-listing.php");

} else {
  echo "You are not eligible to enter this page";
  die();
}