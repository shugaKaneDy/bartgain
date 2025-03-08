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

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ratingId = $_POST["ratingId"];
    $query = "SELECT * FROM ratings
              WHERE rate_id = $ratingId";
    $stmt = $conn->query($query);
    $stmt->setFetchMode(PDO::FETCH_OBJ);
    $results = $stmt->fetch();

  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rate User</title>
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
    <h3>Rate User</h3>
    <div class="container border rounded shadow-sm bg-white py-5 px-4">
      <div class="row justify-content-center">
        <a href="meet-up-information.php?meetUpId=<?= $results->meet_up_id ?>" target="_blank" class="text-center">See details</a>
        <div class="col-12 col-md-6 border rounded py-3">
          <form id="rateUserForm" action="submit-rating.php" method="POST">
            <div class="mb-3">
              <label for="rating" class="form-label">Rating</label>
              <div class="form-check">
                <input type="radio" class="form-check-input" name="rating" id="rating1" value="1" required>
                <label class="form-check-label" for="rating1">1</label>
              </div>
              <div class="form-check">
                <input type="radio" class="form-check-input" name="rating" id="rating2" value="2" required>
                <label class="form-check-label" for="rating2">2</label>
              </div>
              <div class="form-check">
                <input type="radio" class="form-check-input" name="rating" id="rating3" value="3" required>
                <label class="form-check-label" for="rating3">3</label>
              </div>
              <div class="form-check">
                <input type="radio" class="form-check-input" name="rating" id="rating4" value="4" required>
                <label class="form-check-label" for="rating4">4</label>
              </div>
              <div class="form-check">
                <input type="radio" class="form-check-input" name="rating" id="rating5" value="5" required>
                <label class="form-check-label" for="rating5">5</label>
              </div>
            </div>
            <div class="mb-3">
              <label for="comments" class="form-label">Comments</label>
              <textarea class="form-control" id="comments" name="comments" rows="3" required></textarea>
            </div>
            <input type="hidden" id="ratingId" name="ratingId" value="<?= $ratingId ?>">
            <button type="submit" class="btn btn-primary">Submit Rating</button>
          </form>
        </div>
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

