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

  if(isset($_GET["offerId"])) {
    $offer_id = $_GET["offerId"];
    echo $offer_id;
  }

  $today = date("Y-m-d\TH:i");
  


  $query = "SELECT * FROM messages WHERE offer_id = $offer_id";
  $stmt = $conn->query($query);
  $stmt->setFetchMode(PDO::FETCH_OBJ);
  $result = $stmt->fetchAll();



  // print_r($result);
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Offer Messages</title>

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

  <style>
    .image-cover {
      height: 60px; /* Fixed height */
      width: 100px;  /* Fixed width */
      object-fit: cover; /* Adjust image aspect ratio */
    }
  </style>
</head>
<body class="bg-white">
  <?php
    include 'layout/navbar.php';
  ?>

  <h2 class="text-center fw-bold mb-5">
    Offer Message
  </h2>
  <section class="container-md">
    <div class="row">
      <div class="col-12 col-md-4">
        <div class="card mb-5 border border-success-subtle">
          <h5 class="card-header">Your Item <i class="text-success bi bi-plus"></i></h5>
          <div class="card-body">
            <div class="p-1 pt-2">
              <div class="d-flex gap-2 mb-3">
                <img src="offer-item-photo/6676166f24950-laptop.jpg" class="image-cover rounded" alt="">
                <div>
                  <p class="text-sm fw-bold m-0">Laptop</p>
                  <p class="text-sm m-0">2nd hand laptop</p>
                  <p class="text-sm m-0">Condition: Very Good</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4 mb-2">
        <div class="card mb-5 border border-success-subtle">
          <h5 class="card-header"> Messages<i class="text-success bi bi-plus"></i></h5>
          <div class="card-body" style="max-height: 70vh; overflow-y:auto;">
            <div class="d-flex justify-content-start mb-2 w-100">
              <div class="w-50 border border-secondary p-1 rounded bg-light">Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>
            </div>
            <div class="d-flex justify-content-end mb-2 w-100">
              <div class="w-50 border border-primary p-1 rounded bg-primary-subtle">Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>
            </div>
            <div class="d-flex justify-content-start mb-2 w-100">
              <div class="w-50 border border-secondary p-1 rounded bg-light">Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>
            </div>
            <div class="d-flex justify-content-end mb-2 w-100">
              <div class="w-50 border border-primary p-1 rounded bg-primary-subtle">Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>
            </div>
            <div class="d-flex justify-content-end mb-2 w-100">
              <div class="w-50 border border-primary p-1 rounded bg-primary-subtle">Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>
            </div>
          </div>
          <div class="card-footer">
            <div class="input-group flex-nowrap">
              <input type="text" class="form-control" placeholder="Message..." aria-label="Username" aria-describedby="addon-wrapping">
              <button class="input-group-text btn btn-info" id="addon-wrapping"><i class="bi bi-send"></i></button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="card mb-5 border border-success-subtle">
          <h5 class="card-header">Make Plan <i class="text-success bi bi-calendar-check"></i></h5>
          <div class="card-body">
           <div class="p-1 pt-2">
            <p class="text-sm fw-bold">Proposal</p>
            <div class="d-flex gap-2 mb-3">
              <img src="offer-item-photo/6676166f24950-laptop.jpg" class="image-cover rounded" alt="">
              <div>
                <p class="text-sm fw-bold m-0">Laptop</p>
                <p class="text-sm m-0">Perfect na laptop</p>
                <p class="text-sm m-0">Condition: Good</p>
              </div>
            </div>
            <div class="mb-2">
              <label for="">Meet-up Place</label>
              <input type="text" class="form-control" value="Walter Mart Dasma">
            </div>
            <div class="mb-2">
              <label for="">Date and Time</label>
              <input type="datetime-local" min="<?= $today ?>" class="form-control">
            </div>
            <div>
              <button class="btn btn-sm btn-secondary">Update</button>
              <button class="btn btn-sm btn-primary">Accept</button>
              <button class="btn btn-sm btn-danger">Reject</button>
            </div>
            
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

  <?php if(isset($_SESSION['message'])): ?>
    Swal.fire({
      icon: '<?= $_SESSION["message"]["status"] ?>',
      title: '<?= $_SESSION["message"]["title"] ?>',
      showConfirmButton: true
    });
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>


</script>


</body>
</html>
