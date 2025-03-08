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
  <title>Meet Up</title>
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
    <h3>Meet-up</h3>
    <div>
      <nav class="nav">
        <a class="nav-link text-primary text-sm" href="meet-up.php">On-going MeetUp</a>
        <a class="nav-link text-sm" href="meet-up-history.php">Meet-up History</a>
      </nav>
    </div>
    <div class="table-responsive border p-4 border shadow rounded">
    <table id="myTable" class="table table-striped">
      <thead>
        <th>Id</th>
        <th>Role</th>
        <th>Meet up user Id</th>
        <th>Meet Up Place</th>
        <th>Date Time</th>
        <th>Status</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php
          if (isset($_GET["offerId"])) {
            $offerId = $_GET["offerId"];
            $query = "SELECT * FROM meet_up
                      WHERE (meet_up.sender_id = $userId OR meet_up.receiver_id = $userId)
                      AND meet_up.offer_id = $offerId
                      ORDER BY meet_up_id DESC";
          } else {
            $query = "SELECT * FROM meet_up
                      WHERE (meet_up.sender_id = $userId
                      OR meet_up.receiver_id = $userId)
                      AND meet_up_status = 'on-going'
                      ORDER BY meet_up_id DESC";
          }
          $stmt = $conn->query($query);
          $stmt->setFetchMode(PDO::FETCH_OBJ);
          $results = $stmt->fetchAll();

          // print_r($results);

          foreach ($results as $row) {
            ?>
              <tr>
                <td class="text-sm"><?= $row->meet_up_id ?></td>
                <td class="text-sm"><?= $row->receiver_id == $userId ? "Receiver": "Sender" ?></td>
                <td class="text-sm"><?= $row->receiver_id == $userId ? $row->sender_id: $row->receiver_id ?></td>
                <td class="text-sm"><?= $row->meet_up_place?></td>
                <td class="text-sm"><?= $row->meet_up_date?></td>
                <td class="text-sm"><span class="badge bg-success"><?= $row->meet_up_status?></span></td>
                <td class="text-sm">
                  <a href="meet-up-information.php?meetUpId=<?= $row->meet_up_id ?>" class="btn btn-sm btn-light border">View</a>
                </td>
              </tr>
            <?php
          }
        ?>
      </tbody>
    </table>
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

