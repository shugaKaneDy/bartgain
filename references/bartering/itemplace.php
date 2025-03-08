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

  $userVerify = $_SESSION["user_details"]["verified"];

  function distance($lat1, $lng1, $lat2, $lng2) {
    $earth_radius = 6371; // Radius of the earth in kilometers
    $dlat = deg2rad($lat2 - $lat1);
    $dlng = deg2rad($lng2 - $lng1);
    $a = sin($dlat / 2) * sin($dlat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlng / 2) * sin($dlng / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earth_radius * $c;
    return $distance;
  }

  $query = "SELECT * FROM items INNER JOIN users ON items.item_user_id = users.user_id WHERE items.item_user_id != $userId AND items.item_status = 'available' ORDER BY items.item_id DESC";
  $stmt = $conn->query($query);
  $stmt->setFetchMode(PDO::FETCH_OBJ);
  $result = $stmt->fetchAll();
  $nearestArray = [];


  foreach ($result as $row) {
    $distance_km = distance($_SESSION["user_details"]["lat"], $_SESSION["user_details"]["lng"], $row->lat, $row->lng);
    $distance_km = round($distance_km, 1);

    $nearestArray[$row->user_id] = $distance_km;
  }
  asort($nearestArray);

  $firstKeyNearestArray = key($nearestArray);
  $firstValueNearestArray = $nearestArray[$firstKeyNearestArray];
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

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
  <link rel="stylesheet" href="css/itemplace.css">

  
</head>
<body class="bg-white">
  <?php
    include 'layout/navbar.php';
  ?>

  <h2 class="text-center fw-bold mb-5">
    Item Feed
  </h2>
  <section class="container-md">
    <div class="row">
      <div class="col-5 d-none d-md-block">
        <div class="card mb-5 border border-success-subtle">
          <h5 class="card-header">Suggestions <i class="text-warning bi bi-lightbulb-fill"></i></h5>
          <div class="card-body">
            <div class="mt-3">
              <p class="h6"><span class="bg-warning text-white px-3 py-1 rounded">Nearest:</span></p>

              <?php
                $nearestQuery = "SELECT * FROM items INNER JOIN users ON items.item_user_id = users.user_id WHERE users.user_id = $firstKeyNearestArray AND item_status = 'available' ORDER BY items.item_id DESC LIMIT 1";
                $stmt = $conn->query($nearestQuery);
                $stmt->setFetchMode(PDO::FETCH_OBJ);
                $nearestResult = $stmt->fetch();
                // print_r($nearestResult);
                $imgURls = explode(",", $nearestResult->item_url_picture);

                $nearestTotalRating = 0;
                if($nearestResult->user_rate_count == 0) {
                  $nearestTotalRating = 0;
                } else {
                  $nearestTotalRating = $nearestResult->user_rating / $nearestResult->user_rate_count;
                }
                $nearestTotalRating = round($nearestTotalRating, 1);

                ?>
                  <div class="border rounded p-2 mb-3">
                    <div class="d-flex gap-3 mb-3">
                      <img src="profile-picture/<?= $nearestResult->profile_picture ? $nearestResult->profile_picture : "default.jpg" ?>" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
                      <div class="d-flex flex-column">
                        <p class="fw-bold m-0"><?= $nearestResult->fullname ?></p>
                        <p class="fw-bold m-0"><?= $nearestTotalRating ?> <i class="bi bi-star-fill text-warning"></i></p>
                      </div>
                    </div>
                    <p class="text-muted text-sm"><?= $nearestResult->item_created_at ?></p>
                    <p class="fw-bold fs-5 m-0"><?= $nearestResult->item_title ?></p>
                    <p class="fs-sm"><?= $nearestResult->item_description ?></p>
                    <p class="text-success fw-bold fs-sm"><?= $nearestResult->item_swap_option ?></p>
                    <p class="text-muted m-0">Category: <?= $nearestResult->item_category ?></p>
                    <p class="text-muted">Condition: <?= $nearestResult->item_condition ?></p>
                    <div class="d-flex gap-5">
                      <p class="text-muted"><i class="bi bi-geo-alt text-success"></i><?= $nearestResult->address ?></p>
                      <p class="text-muted"><?= $firstValueNearestArray ?> Km</p>
                    </div>
                    <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-md" >
                      <img src="item-photo/<?= $imgURls ? $imgURls[0] : ""?>" alt="" class="rounded img-md">
                    </div>
                    <div class="my-2 d-flex gap-2">
                      <button class="btn btn-sm btn-light border"><i class="bi bi-heart text-success"></i></button>
                      <form action="make-offer.php" method="post">
                        <input type="hidden" name="item_id" value="<?= $nearestResult->item_id ?>" >
                        <?php
                          if($userVerify == "N") {
                            ?>
                              <p class="btn btn-sm btn-success m-0" id="verifyUser">Make Offer</p>
                            <?php
                          } else {
                            ?>
                              <button class="btn btn-sm btn-success">Make Offer</button>
                            <?php
                          }
                        ?>
                      </form>
                    </div>
                  </div>
                <?php
              ?>
            </div>

            
          </div>
        </div>

        
      </div>
      <div class="col-12 col-md-7 mb-5">
        <table id="myTable" class="table border rounded w-100">
          <thead class="thead table-success">
            <th class="text-center">Items</th>
          </thead>
          <tbody id="myTbody">
          <?php
            

            foreach ($result as $row) {
              $distance_km = distance($_SESSION["user_details"]["lat"], $_SESSION["user_details"]["lng"], $row->lat, $row->lng);
              $distance_km = round($distance_km, 1);
              $imageUrls = explode(",", $row->item_url_picture);

              $totalRating = 0;
              if($row->user_rate_count == 0) {
                $totalRating = 0;
              } else {
                $totalRating = $row->user_rating / $row->user_rate_count;
              }
              $totalRating = round($totalRating, 1);
              ?>
                <tr>
                  <td>
                    <div class="w-100 mt-3">
                    <div class="d-flex gap-3 mb-3">
                      <img src="profile-picture/<?= $row->profile_picture ? $row->profile_picture : "default.jpg" ?>" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
                      <div class="d-flex flex-column">
                        <p class="fw-bold m-0"><?= $row->fullname ?></p>
                        <p class="fw-bold m-0"><?= $totalRating ?> <i class="bi bi-star-fill text-warning"></i></p>
                      </div>
                    </div>
                      <p class="text-muted text-sm"><?= $row->item_created_at ?></p>
                      <p class="fw-bold fs-4 m-0"><?= $row->item_title ?></p>
                      <p><?= $row->item_description ?></p>
                      <p class="text-success fw-bold"><?= $row->item_swap_option ?></p>
                      <p class="text-muted m-0">Category: <?= $row->item_category ?></p>
                      <p class="text-muted">Condition: <?= $row->item_condition ?></p>
                      <div class="d-flex gap-5">
                        <p class="text-muted"><i class="bi bi-geo-alt text-success"></i> <?= $row->address ?></p>
                        <p class="text-muted"><?= $distance_km ?> Km</p>
                      </div>
                      <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-lg">
                        <img src="item-photo/<?= $imageUrls ? $imageUrls[0] : ""?>" alt="" class="rounded img-lg">
                      </div>
                      <div class="my-3 d-flex align-items-center gap-2">
                        <button class="btn btn-light border"><i class="bi bi-heart text-success"></i></button>
                        <form action="make-offer.php" method="post">
                          <input type="hidden" name="item_id" value="<?= $row->item_id ?>" >
                          <?php
                            if($userVerify == "N") {
                              ?>
                                <p class="btn btn-sm btn-success m-0" id="verifyUser">Make Offer</p>
                              <?php
                            } else {
                              ?>
                                <button class="btn btn-sm btn-success">Make Offer</button>
                              <?php
                            }
                          ?>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php
            }
          ?>
          
          </tbody>
        </table>
      </div>
      <div class="col-12 d-md-none">
        <div class="card mb-5 border border-success-subtle">
          <h5 class="card-header">Suggestions <i class="text-warning bi bi-lightbulb-fill"></i></h5>
          <div class="card-body">
            <div class="mt-3">
              <p class="h6"><span class="bg-warning text-white px-3 py-1 rounded">Nearest:</span></p>
              <?php
                ?>
                  <div class="border rounded p-2 mb-3">
                    <div class="d-flex gap-3 mb-4">
                      <img src="profile-picture/colet.jpg" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
                      <div class="d-flex flex-column">
                        <p class="fw-bold m-0"><?= $nearestResult->fullname ?></p>
                        <p class="fw-bold m-0">4.3 <i class="bi bi-star-fill text-warning"></i></p>
                      </div>
                    </div>
                    <p class="fw-bold fs-5 m-0"><?= $nearestResult->item_title ?></p>
                    <p class="fs-sm"><?= $nearestResult->item_description ?></p>
                    <p class="text-success fw-bold fs-sm"><?= $nearestResult->item_swap_option ?></p>
                    <p class="text-muted m-0">Category: <?= $nearestResult->item_category ?></p>
                    <p class="text-muted">Condition: <?= $nearestResult->item_condition ?></p>
                    <div class="d-flex gap-5">
                      <p class="text-muted"><i class="bi bi-geo-alt text-success"></i><?= $nearestResult->address ?></p>
                      <p class="text-muted"><?= $firstValueNearestArray ?> Km</p>
                    </div>
                    <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-md" >
                      <img src="item-photo/sonys.jpg" alt="" class="rounded img-md">
                    </div>
                    <div class="my-2">
                      <button class="btn btn-sm btn-light border"><i class="bi bi-heart text-success"></i></button>
                      <button class="btn btn-sm btn-success">Make Offer</button>
                    </div>
                  </div>
                <?php
              ?>
            </div>

          </div>
        </div>

        
      </div>
    </div>
  </section>

  

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
      "ordering": false, // Disable ordering (sorting) for the entire table
      "dom": '<"top"f>rt<"bottom"lp><"clear">'
    });
  });

  $('#myCategory').on('change', function() {
    $('#searchForm').submit();
  });

  $('#filterBtn').on('click', function() {
    $('#filterCard').slideToggle();
  });

  function sendOffer(value) {
    console.log(value);
  }

  $('#verifyUser').on('click', function() {
    Swal.fire({
      title: "Ooops! You need to verify first",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Verify Now",
      cancelButtonText: "Okay"
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        window.location.href = "verification.php";
      } else if (result.isDenied) {
        Swal.fire("Changes are not saved", "", "info");
      }
    });
  });

</script>


</body>
</html>
