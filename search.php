<?php
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'functions.php';

  if(!isset($_SESSION['user_details'])) {
    header("location: logout.php");
    exit;
  }
  if($_SESSION['user_details']['verified'] == "N") {
    header("location: itemplace.php");
    exit;
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/meet-up.css">


</head>
<body class="bg-light">

  <!-- nav -->
  <?php
    include "layouts/nav.php"
  ?>


  <!-- Add item -->
  <?php
    include "layouts/add-div.php"
  ?>

  <!-- Offcanvas -->
  <?php
    include "layouts/aside.php"
  ?>

  <!-- pre load -->
  <?php
    include "layouts/preload.php"
  ?>

  <main>
    <div class="container-xl">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8">
          <h3 class="text-center">Search</h3>
          <div class="input-group">
            <input 
              type="text" 
              id="mySearchInput" 
              class="form-control my-input" 
              placeholder="Search" 
              aria-label="Search Item" 
              aria-describedby="searchButton"
            >
            <button 
              class="btn btn-outline-success" 
              type="button" 
              id="searchButton"
            >
              <i class="bi bi-search"></i>
            </button>
          </div>
          <div id="searchResults" class="mt-2 bg-white p-2 shadow-sm"></div>
        </div>
      </div>
    </div>

  </main>

  

  <?php
    require_once 'layouts/bottom-link.php';
  ?>


  <script>
    $(document).ready(function() {
      $('#mySearchInput').on('keyup', function() {
        let searchTerm = $(this).val();

        $.ajax({
          url: 'includes/ajax/item-search.inc.php',
          type: 'POST',
          data: { searchTerm: searchTerm },
          success: function(data) {
            $('#searchResults').html(data);
          },
        });
      });

      $(document).on('click', '.btnSearchClick', function() {
        let btnVal = $(this).data("value");
        $('#mySearchInput').val(btnVal);
        console.log(btnVal);
      })

      $(document).on('click', '#searchButton', function() {
        let searchVal = $('#mySearchInput').val();
        console.log(searchVal);
        window.location.href = `itemplace-search.php?search=${searchVal}`;
      })

    })

    
  </script>

</body>
</html>