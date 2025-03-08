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


  $query = "SELECT * FROM items INNER JOIN users ON items.item_user_id = users.user_id WHERE items.item_user_id = $userId AND items.item_status != 'deleted'  ORDER BY items.item_id DESC";
  $stmt = $conn->query($query);
  $stmt->setFetchMode(PDO::FETCH_OBJ);
  $result = $stmt->fetchAll();
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Item</title>
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
  <link rel="stylesheet" href="css/itemplace.css">
</head>
<body class="bg-white">
  <?php
    include 'layout/navbar.php';
  ?>
  <?php
    include "verified-authentication.php";
  ?>

  <h2 class="text-center fw-bold mb-5">
    Add Item
  </h2>
  <section class="container-md">
    <div class="row">
      <div class="col-12 col-md-5">
        <div class="card mb-5 border border-success-subtle">
          <h5 class="card-header">Add Item <i class="text-success bi bi-plus"></i></h5>
          <div class="card-body">
            <form id="addItemForm" action="include/add-item.inc.php" enctype="multipart/form-data" method="post">
              <div class="form-group position-relative mb-3">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" id="title" placeholder="Enter item title">
              </div>
              <div class="form-group position-relative mb-3">
                <label for="formFile" class="form-label">Upload Image</label>
                <input class="form-control" type="file" id="formFile" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
                <!-- <img id="imagePreview" src="" alt="Image Preview" style="display:none; margin-top:10px; max-width:100%;"> -->
                <div id="imagePreviewContainer" style="display:flex; flex-wrap: wrap; margin-top: 10px;">
                  <!-- Previews will be appended here -->
                </div>
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
                  <option value="Books">Books</option>
                  <option value="Movies and Music">Movies and Music</option>
                  <option value="Tools and Equipment">Tools and Equipment</option>
                  <option value="Sports and Outdoors">Sports and Outdoors</option>
                  <option value="Health and Beauty">Health and Beauty</option>
                  <option value="Home and Garden">Home and Garden</option>
                  <option value="Office Supplies">Office Supplies</option>
                  <option value="Pet Supplies">Pet Supplies</option>
                  <option value="Baby and Kids">Baby and Kids</option>
                  <option value="Collectibles">Collectibles</option>
                  <option value="Jewelry and Watches">Jewelry and Watches</option>
                  <option value="Musical Instruments">Musical Instruments</option>
                  <option value="Arts and Crafts">Arts and Crafts</option>
                  <option value="Computers and Accessories">Computers and Accessories</option>
                  <option value="Photography">Photography</option>
                  <option value="Video Games">Video Games</option>
                  <option value="Travel and Luggage">Travel and Luggage</option>
                  <option value="Building Materials">Building Materials</option>
                  <option value="Gardening Tools">Gardening Tools</option>
                  <option value="Safety and Security">Safety and Security</option>
                  <option value="Seasonal Items">Seasonal Items</option>
                  <option value="Gift Cards and Vouchers">Gift Cards and Vouchers</option>
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
              <div class="form-group position-relative mb-3">
                <label for="swapOption">Swap Option</label>
                <select class="form-control" name="swapOption" id="swapOption">
                  <option value="Swap">Swap</option>
                  <option value="Donation">Donation</option>
                </select>
              </div>
              <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter item description"></textarea>
              </div>
              <?php
                if($userVerify == "N") {
                  ?>
                    <p class="btn btn-primary m-0" id="verifyUser">Add Item</p>
                  <?php
                } else {
                  ?>
                    <button type="button" id="addItemBtn" class="btn btn-primary">Add Item</button>
                  <?php
                }
              ?>
            </form>
          </div>
        </div>

        
      </div>
      <div class="col-12 col-md-7 mb-5">
        <table id="myTable" class="table border rounded w-100">
          <thead class="thead table-success">
            <th class="text-center">Your Items</th>
          </thead>
          <tbody id="myTbody">
          <?php
            foreach ($result as $row) {
              $imageUrls = explode(',', $row->item_url_picture);
              ?>
                <tr>
                  <td>
                    <div class="w-100 mt-3">
                      <p class="m-0 text-sm text-muted"><?= $row->item_created_at ?></p>
                      <p class="fw-bold fs-4 m-0"><?= $row->item_title ?></p>
                      <p><?= $row->item_description ?></p>
                      <p class="text-success fw-bold"><?= $row->item_swap_option ?></p>
                      <p class="text-muted m-0">Category: <?= $row->item_category ?></p>
                      <p class="text-muted">Condition: <?= $row->item_condition ?></p>
                      <p class="text-muted">Status: <?= $row->item_status ?></p>
                      <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-lg">
                        <img src="item-photo/<?= $imageUrls ? $imageUrls[0] : ""?>" alt="" class="rounded img-lg">
                      </div>
                      <div class="my-3 d-flex gap-2">
                        <form action="include/delete-item.inc.php" method="post">
                          <input type="hidden" name="item_id" id="item_id" value="<?= $row->item_id ?>" >
                          <!-- <button class="btn btn-sm btn-danger border">Delete</button> -->
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

  // image preview

  /* function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
      const output = document.getElementById('imagePreview');
      output.src = reader.result;
      output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
  } */

  function previewImages(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('imagePreviewContainer');
    previewContainer.innerHTML = ""; // Clear any existing previews

    Array.from(files).forEach(file => {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.style.maxWidth = '100px';
        img.style.maxHeight = '100px';
        img.style.margin = '5px';
        img.style.display = 'block';
        img.classList.add('img-thumbnail');
        previewContainer.appendChild(img);
      };
      reader.readAsDataURL(file);
    });
  }



  $(document).ready(function() {
    $('#addItemBtn').on('click', function() {
      Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to add this item?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, add it!',
        cancelButtonText: 'No, cancel!'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#addItemForm').submit();
        } else {
          Swal.fire("Item not added", "", "info");
        }
      });
    });
  });

</script>


</body>
</html>
