<?php
  session_start();
  require_once 'dbcon.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>

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
    .card .card-img-top {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    .card-body .star-rating {
      color: #ffcc00;
    }
    .text-sm {
      font-size: 0.825em;
    }
  </style>
</head>
<body class="bg-white">
  <?php
    include 'layout/navbar.php';
  ?>

  <section class="container w-100  d-flex flex-column justify-content-center align-items-center mb-3" style="height: 400px;">
    <h1 class="fw-bolder text-center d-md-none">TRADE, DONATE YOUR STUFF ANYTIME, ANYWHERE.</h1>
    <h1 class="d-none d-md-block text-center fw-bolder">TRADE, DONATE YOUR STUFF</h1>
    <h1 class="d-none d-md-block text-center fw-bolder">ANYTIME, ANYWHERE.</h1>
    <p class="text-muted fs-4">Barter for a Better Tomorrow !</p>
    <a href="itemplace.php" class="btn btn-success">Find your trading partner <i class="bi bi-arrow-right"></i></a>
  </section>
  <section class="container d-flex justify-content-center flex-column flex-md-row justify-content-md-between align-items-center mb-5">
    <div>
      <p class="m-0 fw-bold">DAILY DISCOVER</p>
    </div>
    <div class="text-center">
      <a href="itemplace.php?category=Electronics" class="btn btn-outline-success btn-sm mb-2">Electronics</a>
      <a href="itemplace.php?category=Furniture" class="btn btn-outline-success btn-sm mb-2">Furniture</a>
      <a href="itemplace.php?category=Appliances" class="btn btn-outline-success btn-sm mb-2">Appliances</a>
      <a href="itemplace.php?category=Clothing and Accessories" class="btn btn-outline-success btn-sm mb-2">Clothing and Accessories</a>
      <a href="itemplace.php?category=Toys and Games" class="btn btn-outline-success btn-sm mb-2">Toys and Games</a>
    </div>
    <div>
      <a class="text-decoration-none text-dark h6" href="">VIEW ALL <i class="bi bi-arrow-right"></i></a>
    </div>
  </section>
  <section class="container">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

      <?php
        $query = "SELECT * FROM items INNER JOIN users ON items.item_user_id = users.user_id ORDER BY items.item_id DESC LIMIT 8";


        $stmt = $conn->prepare($query);
      
        // Execute the query
        $stmt->execute();

        // Fetch all rows as associative array
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        foreach ($results as $row) {
          $imgURls = explode(",", $row["item_url_picture"]);
          $totalRating = 0;
          if($row["user_rate_count"] == 0) {
            $totalRating = 0;
          } else {
            $totalRating = $row["user_rating"] / $row["user_rate_count"];
          }
          $totalRating = round($totalRating, 1);
          
          ?>
            <div class="col">
              <a class="text-decoration-none" href="itemplace.php">
              <div class="card h-100 p-2 border-0 shadow bg-white">
                <img src="item-photo/<?= $imgURls ? $imgURls[0] : "item-photo/laptop.jpg" ?>" class="card-img-top" alt="...">
                <div class="card-body">
                  <div class="d-flex gap-3 mb-2">
                    <img src="profile-picture/default.jpg" alt="Profile Picture" class="rounded-circle" style="width: 30px; height: 30px;">
                    <div class="">
                      <p class="fw-bold m-0 text-sm"><?= $row["fullname"] ?></p>
                      <p class="fw-bold m-0 text-sm"><?= $totalRating ?> <i class="bi bi-star-fill text-warning"></i></p>
                    </div>
                  </div>
                  <p class="text-success m-0 fw-bold fs-sm"><?= $row["item_swap_option"] ?></p>
                  <p class="fs-sm"><?= $row["item_title"] ?></p>
                  <div class="d-flex gap-2">
                    <p class="text-muted text-sm"><i class="bi bi-geo-alt text-success"></i><?= $row["address"] ?></p>
                  </div>
                </div>
              </div>
              </a>
            </div>
          <?php
        }
      ?>
      
    </div>
  </section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- update location -->
<?php
  if(isset($_SESSION['user_details'])) {
    ?>
      <script src="update-location.js"></script>
    <?php
  }
?>

<script>

  $('#myCategory').on('change', function() {
    $('#searchForm').submit();
  });

  $('#filterBtn').on('click', function() {
    $('#filterCard').slideToggle();
  });
</script>


</body>
</html>
