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

  $item_id = $_POST["item_id"];


  $query = "SELECT * FROM items INNER JOIN users ON items.user_id = users.user_id WHERE items.item_id = $item_id  ORDER BY items.item_id DESC";
  $stmt = $conn->query($query);
  $stmt->setFetchMode(PDO::FETCH_OBJ);
  $result = $stmt->fetch();
 
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
    Make Offer
  </h2>
  <?php if(isset($_SESSION['message'])): ?>
    <?= print_r($_SESSION["message"]) ?>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>
  <?= $item_id ?>
  <section class="container-md">
    <div class="row">
      <div class="col-12 col-md-5">
        <div class="card mb-5 border border-success-subtle">
          <h5 class="card-header">Add Item <i class="text-success bi bi-plus"></i></h5>
          <div class="card-body">
            <div class="d-flex gap-3 mb-3">
              <img src="profile-picture/<?= $result->profile_picture ? $result->profile_picture : "default.jpg" ?>" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
              <div class="d-flex flex-column">
                <p class="fw-bold m-0"><?= $result->fullname ?></p>
                <p class="fw-bold m-0">4.3 <i class="bi bi-star-fill text-warning"></i></p>
              </div>
            </div>
            <p class="fw-bold fs-5 m-0"><?= $result->title ?></p>
            <p class="text-success fw-bold fs-sm"><?= $result->swap_option ?></p>
          </div>
          <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-md" >
            <img src="item-photo/<?= $result->url_picture ? $result->url_picture : ""?>" alt="" class="rounded img-md">
          </div>
        </div>

        
      </div>
      <div class="col-12 col-md-7 mb-5">
        <div class="card mb-5 border border-success-subtle">
          <h5 class="card-header">Offer <i class="text-success bi bi-plus"></i></h5>
          <div class="card-body">
            <form action="include/make-offer.inc.php" enctype="multipart/form-data" method="post">
                <input type="hidden" name="item_id" value="<?= $item_id ?>">
                <div class="form-group position-relative mb-3">
                  <label for="title">Title</label>
                  <input type="text" name="title" class="form-control" id="title" placeholder="Enter item title">
                </div>
                <div class="form-group position-relative mb-3">
                  <label for="formFile" class="form-label">Upload Image</label>
                  <input class="form-control" type="file" id="formFile" name="image">
                </div>
                <div class="form-group position-relative mb-3">
                  <label for="category">Category</label>
                  <select class="form-control" name="category" id="category">
                    <option value="Electronics">Electronics</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Appliance">Appliance</option>
                    <option value="Clothing and Accessories">Clothing and Accessories</option>
                    <option value="Toys and Games">Toys and Games</option>
                    <option value="Foods">Foods</option>
                  </select>
                </div>
                <div class="form-group position-relative mb-3">
                  <label for="itemCondition">Item Condition</label>
                  <select class="form-control" name="itemCondition" id="itemCondition">
                    <option value="Very Good">Very Good</option>
                    <option value="Good">Good</option>
                    <option value="Fair">Fair</option>
                    <option value="Bad">Bad</option>
                    <option value="Very Bad">Very Bad</option>
                  </select>
                </div>
                <div class="form-group mb-3">
                  <label for="description">Description</label>
                  <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter item description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Make Offer</button>
            </form>
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
