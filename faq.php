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

  $faqs = selectQuery(
    $pdo,
    "SELECT * FROM faqs",
    [

    ]
  );

  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQs</title>

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
        <div class="col-12 col-md-10">
          <h3>FAQs</h3>
          <div class="card">
            <div class="card-body">
              <h4 class="m-0">Frequently asked questions</h4>
              <p class="text-muted mb-4">Stuck on something? We're here to help with all your questions and answers in one piece</p>
              <div class="input-group mb-4">
                <span class="input-group-text" id="search">
                  <i class="bi bi-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control my-input" placeholder="Search" aria-label="Search Offers" aria-describedby="search">
              </div>
              <div class="faq-content">
                <div class="row">
                  <?php foreach($faqs as $faq): ?>
                    <div class="col-12 col-md-6 mb-3 faq-item" faq-name="<?= $faq['faq_question'] ?>">
                      <div class="border border-success border-2 border-start-0 border-end-0 bg-light p-2">
                        <p class="h5 fw-bold"><?= $faq['faq_question'] ?></p>
                        <p class="m-0"><?= $faq['faq_answer'] ?></p>
                      </div>
                    </div>
                  <?php endforeach ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>

  </main>


  <?php
    require_once 'layouts/bottom-link.php';
  ?>


  <script>
    $(document).ready(function() {

      $('#searchInput').on('input', function() {
        // Get the value from the search input and convert it to lowercase
        var searchValue = $(this).val().toLowerCase();

        // Loop through all menu items
        $('.faq-item').each(function() {
          // Get the name of the current menu item (stored in the data-name attribute)
          var faqName = $(this).attr('faq-name').toLowerCase();

          // Check if the item name includes the search value
          if (faqName.includes(searchValue)) {
            // If it matches, show the item
            $(this).show();
          } else {
            // Otherwise, hide it
            $(this).hide();
          }
        });
      });

    })

    
  </script>

</body>
</html>