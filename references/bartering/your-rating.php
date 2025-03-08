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

  $myInfoQuery = "SELECT * FROM users
            WHERE user_id = $userId";
  $myInfoStmt = $conn->query($myInfoQuery);
  $myInfoStmt->setFetchMode(PDO::FETCH_OBJ);
  $myInfoResult = $myInfoStmt->fetch();

  $totalRating = 0;
    if($myInfoResult->user_rate_count == 0) {
      $totalRating = 0;
    } else {
      $totalRating = $myInfoResult->user_rating / $myInfoResult->user_rate_count;
    }
  $totalRating = round($totalRating, 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ratings</title>
  <link rel="icon" href="B.png">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- rsponsive table -->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" />

  <link rel="stylesheet" href="css/general.css">

  <style>
    aside {
      position: fixed;
      left: 0;
      top: 0; 
      padding-top: 100px;
      width: 380px;
      height: 100vh;
      overflow-y: auto;
      background-color: #f8f9fa;
    }
    body {
      padding-left: 400px;
    }
    .image-container {
      height: 250px;
      width: 100%;
      object-fit: cover;
    }
    .nav-link {
      color: #333;
    }
    .nav-link:hover {
      color: #007bff;
    }
    @media (max-width: 568px) {
      body {
        padding-left: 0;
        padding-top: 120px;
      }
      .image-container {
        height: 140px;
      }
      aside {
        width: 100%;
        z-index: 1;
      }
    }
  </style>
</head>
<body class="bg-light">

  <?php include 'layout/navbar.php'; ?>
  <?php include 'layout/dashboard-aside.php'; ?>
  <?php
    include "verified-authentication.php";
  ?>

  

  <div class="container mb-5">
    <h3>Your rating</h3>
    <div>
      <nav class="nav">
        <a class="nav-link text-sm" href="ratings.php">To Rate</a>
        <a class="nav-link text-sm" href="rating-history.php">Rating History</a>
        <a class="nav-link text-primary text-sm" href="your-rating.php">Your Rating</a>
      </nav>
    </div>
    <div class="container border shadow rounded py-5 px-4">
      <div class="row justify-content-center">
        <div class="col-8 col-md-4 mb-3 border border-dark rounded py-3 text-center">
          <h1><?= $totalRating ?> <i class="bi bi-star-fill text-warning"><span class="text-normal">ratings</span></i> </h1>
          <p class="m-0"><?= $myInfoResult->user_rate_count ?> person rated</p>
        </div>
      </div>
      <div class="table-responsive border p-4 border rounded">
      <table id="myTable" class="table table-striped">
        <thead>
          <th>Rating information</th>
        </thead>
        <tbody>
          <?php
          
            $query = "SELECT * FROM ratings
                      WHERE rate_partner_id = $userId
                      AND rate_status != 'pending'
                      ORDER BY rate_id DESC";
            $stmt = $conn->query($query);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $results = $stmt->fetchAll();
  
            // print_r($results);
  
            foreach ($results as $row) {
              ?>
                <tr>
                  <td>
                    <div>
                      <p class="m-0 text-sm">rating id: <?= $row->rate_id ?></p>
                      <p class="h5">User Id: <?= $row->rate_your_id ?></p>
                      <p class="m-0">Rate: <?= $row->rate_ratings ?> <i class="bi bi-star-fill text-warning"></i> </p>
                      <p class="m-0 text-normal">Comments: <?= $row->rate_feedback ?></p>
                    </div>
                  </td>
                </tr>
              <?php
            }
          ?>
        </tbody>
      </table>
      </div>
    </div>
  </div>
  

<!-- resposinve data table -->
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="js/plugins/sweetalert2/swal.js"></script>

<!-- update location -->
<?php
  if(isset($_SESSION['user_details'])) {
    ?>
      <script src="update-location.js"></script>
    <?php
  }
?>  

<script>

  $(document).ready( function () {
    $('#myTable').DataTable({
      responsive: true,
      ordering: false
    });
  });

  $('.forFilterToggle').on('click', function() {
    $('.side-search').toggleClass('d-none');
  });
</script>

</body>
</html>

