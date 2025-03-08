<?php
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'functions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/notifications.css">


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
        <div class="col-12 col-md-9">
          <h3>Notifications</h3>
          <div class="p-2 bg-white rounded shadow-sm border">
            <div class="d-flex justify-content-end gap-2 mb-3">
              <a href="#" class="link link-success small-text">Mark all as unread</a>
            </div>
            <div class="notif-container">

            </div>
          </div>

          <!-- Loading spinner at the bottom -->
          <div id="loadingSpinner" class="text-center my-3" style="display: none;">
              <div class="spinner-border text-success" role="status">
                  <span class="visually-hidden">Loading...</span>
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

      const notifContainer = $('.notif-container');

      let isLoading = false; // Prevent multiple requests
      let currentPage = 1;

      function notifications(page)  {
        $.ajax({
          method: 'GET',
          url: "includes/ajax/notifications.inc.php?function=nearest",
          data: { 
            page: page
          }
        }).done(res => {
          notifContainer.append(res); // Append new items instead of replacing

          // Hide the loading spinner once data is loaded
          $('#loadingSpinner').hide();
          isLoading = false; // Reset loading state

          currentPage++; // Increment page for the next request
        });
      }

      notifications(currentPage);

      // Lazy load on scroll
      $(window).on('scroll', function () {
          if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
              isLoading = true; // Set loading state to true
              $('#loadingSpinner').show(); // Show the loading spinner
              notifications(currentPage); // Load next page when near the bottom
          }
      });

      $(document).on('click', '.notificationInfo', function() {

        let notifId = $(this).data('notif-id'); // Retrieve the value of data-notif-id
        let notifHref = $(this).data('href'); // Retrieve the value of data-notif-id
        console.log(notifId); // Output: 123
        console.log(notifHref); // Output: 123

        $.ajax({
          method: 'POST',
          url: "includes/ajax/notification-update.inc.php",
          data: {
            notifId: notifId
          },
          dataType: "JSON",
        }).done(function (res) {
          if(res.status == 'success') {
            window.location.href = notifHref;
          }
        })

      });


    })

    
  </script>

</body>
</html>