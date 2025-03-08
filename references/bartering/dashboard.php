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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
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

  

  <div class="container">
    <h3>Dashboard</h3>
    <div class="container py-3 bg-white rounded shadow-sm">
      <p class="text-muted">Overview</p>
      <div class="row">

        <div class="col-6 col-md-4 mb-3">
          <a href="add-item.php" class="text-dark text-decoration-none">
            <div class="border px-4 py-3 rounded">
              <div class="d-flex justify-content-between">
                <p class="fw-bold">Item Listed</p>
                <div>
                  <i class="bi bi-box h4"></i>
                </div>
              </div>
              <p class="h3">
                <?php
                  $query = "SELECT COUNT(item_id) as item_num FROM items WHERE item_user_id = $userId AND item_status = 'available'";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetch  (PDO::FETCH_OBJ);

                  echo $result->item_num;
                ?>
              </p>
            </div>
          </a>
        </div>

        <div class="col-6 col-md-4 mb-3">
          <a href="offers.php" class="text-dark text-decoration-none">
            <div class="border px-4 py-3 rounded">
              <div class="d-flex justify-content-between">
                <p class="fw-bold">Pending Offers</p>
                <div>
                  <i class="bi bi-tag h4"></i>
                </div>
              </div>
              <p class="h3">
                <?php
                  $query = "SELECT COUNT(offer_id) as offer_num FROM offers WHERE r_receiver_id = $userId AND offer_status = 'pending'";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetch  (PDO::FETCH_OBJ);

                  echo $result->offer_num;
                ?>
              </p>
            </div>
          </a>
        </div>

        <div class="col-6 col-md-4 mb-3">
          <a href="messages-proposals.php" class="text-dark text-decoration-none">
            <div class="border px-4 py-3 rounded">
              <div class="d-flex justify-content-between">
                <p class="fw-bold">Pending Proposals</p>
                <div>
                  <i class="bi bi-gift h4"></i>
                </div>
              </div>
              <p class="h3">
                <?php
                  $query = "SELECT COUNT(offer_id) as proposal_num FROM offers WHERE sender_id = $userId AND offer_status = 'pending'";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetch  (PDO::FETCH_OBJ);

                  echo $result->proposal_num;
                ?>
              </p>
            </div>
          </a>
        </div>

        <div class="col-6 col-md-4 mb-3">
          <a href="meet-up.php" class="text-dark text-decoration-none">
            <div class="border px-4 py-3 rounded">
              <div class="d-flex justify-content-between">
                <p class="fw-bold">On Going Meet-up</p>
                <div>
                  <i class="bi bi-geo-alt h4"></i>
                </div>
              </div>
              <p class="h3">
                <?php
                  $query = "SELECT COUNT(meet_up_id) as meet_up_num FROM meet_up WHERE (sender_id = $userId OR receiver_id = $userId) AND meet_up_status = 'on-going'";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetch  (PDO::FETCH_OBJ);

                  echo $result->meet_up_num;
                ?>
              </p>
            </div>
          </a>
        </div>

        <div class="col-6 col-md-4 mb-3">
          <a href="ratings.php" class="text-dark text-decoration-none">
            <div class="border px-4 py-3 rounded">
              <div class="d-flex justify-content-between">
                <p class="fw-bold">To Rate</p>
                <div>
                  <i class="bi bi-star h4"></i>
                </div>
              </div>
              <p class="h3">
                <?php
                  $query = "SELECT COUNT(rate_id) as rate_num FROM ratings WHERE rate_your_id = $userId AND rate_status = 'pending'";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetch  (PDO::FETCH_OBJ);

                  echo $result->rate_num;
                ?>
              </p>
            </div>
          </a>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <!-- update location -->
<?php
  if(isset($_SESSION['user_details'])) {
    ?>
      <script src="update-location.js"></script>
    <?php
  }
?>  

  <script>
    $('.forFilterToggle').on('click', function() {
      $('.side-search').toggleClass('d-none');
    });
  </script>

</body>
</html>


  
