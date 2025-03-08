<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  if(!isset($_SESSION['user_details'])) {
    exit;
  }

  $searchTerm = $_POST['searchTerm'] ?? '';
  
  if(strlen($searchTerm) < 3) {
    exit;
  }

  $searchResults = selectQuery(
    $pdo,
    "SELECT * FROM items WHERE item_title LIKE :searchTerm
    AND item_status = 'available'",
    [
      ':searchTerm' => '%' . $searchTerm . '%',
    ]
  );

  if($searchResults) {
    foreach ($searchResults as $searchResult) {
      ?>
        <div>
          <button data-value="<?= $searchResult['item_title'] ?>" class="text-start btnSearchClick btn btn-light border-0 w-100 border rounded-0">
            <?= $searchResult['item_title'] ?>
          </button>
        </div>
      <?php
    }
  } else {
    ?>
    <div class="p-2">
      <p class="m-0 text-muted">No result</p>
    </div>
    <?php
  }




}

?>