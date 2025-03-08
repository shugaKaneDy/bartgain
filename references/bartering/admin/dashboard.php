<?php
require_once '../dbcon.php'; // Adjust path as per your file structure
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="icon" href="../B.png">
  
  <!-- Top Links -->
  <?php include("layout/top-link.php"); ?>

  <!-- Styles -->
  <?php include("layout/style.php"); ?>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <!-- Navbar -->
  <?php include("layout/navbar.php"); ?>

  <!-- Sidebar -->
  <?php include("layout/sidebar.php"); ?>

  <!-- Main content -->
  <main class="main-content pt-3">
    <div class="container main-title mb-5">
      <h3>Meet Ups</h3>
      <div class="border p-4 border shadow-sm rounded">
        <h4>Overview</h4>
        <div class="row">

          <!-- User Accounts -->
          <div class="col-12 col-md-4 mb-3">
            <a href="users.php" class="text-dark text-decoration-none">
              <div class="border px-4 py-3 rounded">
                <div class="d-flex justify-content-between">
                  <p class="fw-bold">User Accounts</p>
                  <div>
                    <i class="bi bi-person h4"></i>
                  </div>
                </div>
                <p class="h3">
                  <?php
                    $user_count_query = "SELECT COUNT(user_id) as user_num FROM users WHERE role_id = 1";
                    $stmt = $conn->prepare($user_count_query);
                    $stmt->execute();
                    $user_result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo $user_result['user_num'];
                  ?>
                </p>
              </div>
            </a>
          </div>

          <!-- Admin Accounts -->
          <div class="col-12 col-md-4 mb-3">
            <a href="admin-accounts.php" class="text-dark text-decoration-none">
              <div class="border px-4 py-3 rounded">
                <div class="d-flex justify-content-between">
                  <p class="fw-bold">Admin Accounts</p>
                  <div>
                    <i class="bi bi-person-gear h4"></i>
                  </div>
                </div>
                <p class="h3">
                  <?php
                    $admin_count_query = "SELECT COUNT(user_id) as admin_num FROM users WHERE role_id = 2";
                    $stmt = $conn->prepare($admin_count_query);
                    $stmt->execute();
                    $admin_result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo $admin_result['admin_num'];
                  ?>
                </p>
              </div>
            </a>
          </div>

          <!-- Pending Verifications -->
          <div class="col-12 col-md-4 mb-3">
            <a href="verification.php" class="text-dark text-decoration-none">
              <div class="border px-4 py-3 rounded">
                <div class="d-flex justify-content-between">
                  <p class="fw-bold">Pending Verifications</p>
                  <div>
                    <i class="bi bi-person-check h4"></i>
                  </div>
                </div>
                <p class="h3">
                  <?php
                    $pending_verif_query = "SELECT COUNT(verification_id) as pending_verif_num FROM verifications WHERE verification_status = 'pending'";
                    $stmt = $conn->prepare($pending_verif_query);
                    $stmt->execute();
                    $verif_result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo $verif_result['pending_verif_num'];
                  ?>
                </p>
              </div>
            </a>
          </div>

          <!-- Items -->
          <div class="col-12 col-md-4 mb-3">
            <a href="items.php" class="text-dark text-decoration-none">
              <div class="border px-4 py-3 rounded">
                <div class="d-flex justify-content-between">
                  <p class="fw-bold">Items</p>
                  <div>
                    <i class="bi bi-bag h4"></i>
                  </div>
                </div>
                <p class="h3">
                  <?php
                    $item_count_query = "SELECT COUNT(item_id) as item_num FROM items";
                    $stmt = $conn->prepare($item_count_query);
                    $stmt->execute();
                    $item_result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo $item_result['item_num'];
                  ?>
                </p>
              </div>
            </a>
          </div>

          <!-- Offers -->
          <div class="col-12 col-md-4 mb-3">
            <a href="offers.php" class="text-dark text-decoration-none">
              <div class="border px-4 py-3 rounded">
                <div class="d-flex justify-content-between">
                  <p class="fw-bold">Offers</p>
                  <div>
                    <i class="bi bi-gift h4"></i>
                  </div>
                </div>
                <p class="h3">
                  <?php
                    $offer_count_query = "SELECT COUNT(offer_id) as offer_num FROM offers";
                    $stmt = $conn->prepare($offer_count_query);
                    $stmt->execute();
                    $offer_result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo $offer_result['offer_num'];
                  ?>
                </p>
              </div>
            </a>
          </div>

          <!-- Meet Ups -->
          <div class="col-12 col-md-4 mb-3">
            <a href="meet-ups.php" class="text-dark text-decoration-none">
              <div class="border px-4 py-3 rounded">
                <div class="d-flex justify-content-between">
                  <p class="fw-bold">Meet Ups</p>
                  <div>
                    <i class="bi bi-geo-alt h4"></i>
                  </div>
                </div>
                <p class="h3">
                  <?php
                    $meetup_count_query = "SELECT COUNT(meet_up_id) as meet_num FROM meet_up";
                    $stmt = $conn->prepare($meetup_count_query);
                    $stmt->execute();
                    $meetup_result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo $meetup_result['meet_num'];
                  ?>
                </p>
              </div>
            </a>
          </div>

        </div> <!-- End of row -->

        <!-- User Registration Graph -->
        <h4>User Registration Graph</h4>
        <div class="card mb-4">
          <div class="card-body">
            <canvas id="userRegistrationChart" width="100%" height="40"></canvas>
          </div>
        </div>

      </div> <!-- End of border -->
    </div> <!-- End of container -->
  </main>

  <!-- Bottom Links -->
  <?php include("layout/bottom-link.php"); ?>
  
  <!-- JavaScript for User Registration Chart -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var ctx = document.getElementById('userRegistrationChart').getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [], // Initialize with empty array for labels
          datasets: [{
            label: 'User Registrations',
            data: [], // Initialize with empty array for data
            backgroundColor: 'rgba(40, 167, 69, 0.5)', 
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      // Fetch data for chart
      fetchUserDataForChart();

      // Function to fetch user registration data
      function fetchUserDataForChart() {
        fetch('fetch-registration-data.php') // Adjust URL as per your file structure
          .then(response => response.json())
          .then(data => {
            // Update chart labels and data
            myChart.data.labels = data.map(entry => entry.month); // Assuming 'month' is the key for months
            myChart.data.datasets[0].data = data.map(entry => entry.registrations); // Assuming 'registrations' is the key for registrations
            myChart.update(); // Update chart
          })
          .catch(error => {
            console.error('Error fetching data:', error);
          });
      }
    });
  </script>

</body>
</html>
