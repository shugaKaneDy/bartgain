<?php
include("navbar.php");
include("connect.php");
?>

<?php
  $userId = $_SESSION['user_id'];
  $userFullname = $_SESSION['fullname'];
  $userAddress = $_SESSION['address'];
?>

<?php
  if (isset($_POST['add_to_cart'])) {
    $userId = $_POST['user_id'];
    $userFullname = $_POST['user_fullname'];
    $userAdress = $_SESSION['address'];
    $productName = $_POST['title'];
    $pricePerQuantity = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Insert into orders table
    $query = "INSERT INTO orders (user_id, user_fullname, product_name, price_per_quantity, quantity, total_price, address, status, date)
              VALUES ('$userId', '$userFullname', '$productName', '$pricePerQuantity', '$quantity', '$pricePerQuantity' * '$quantity', '$userAddress', 'Cart', NOW())";

    if (mysqli_query($conn, $query)) {
      // Success message
      echo "<script>alert('Product added to cart successfully!');</script>";
    } else {
      // Error message
      echo "<script>alert('Error adding product to cart: " . mysqli_error($conn) . "');</script>";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Navbar Demo</title>
    <!-- Bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-...YOUR_SHA_INTEGRITY..." crossorigin="anonymous" />

    <!-- This is for table -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>


    <!-- css include -->
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/table-style.css">

    <style>
      .my-main {
        margin-top: 150px;
      }
      .btn-square {
        border-radius: 0px;
      }
    </style>
</head>
<body>

  <main class="container my-main">
    <h2 class="fw-bold text-center mb-5">Pick Your Favorites</h2>
    <div class="container bg-light py-5 rounded">
      <div class="row">

        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-header ps-3 py-2">
              <p class="card-title m-0 fw-bold">Your Cart</p>
            </div>
            <div class="card-body">

              <?php
              // Query orders for the user
              $cart_query = "SELECT *
                            FROM orders
                            WHERE user_id = '$userId' AND status = 'Cart' ORDER BY order_id DESC";
              $cart_result = mysqli_query($conn, $cart_query);

              while ($row = mysqli_fetch_assoc($cart_result)) {
                echo "<div class='container border mb-2'>";
                echo "<div class='row p-2'>";
                echo "<div class='col-10'>" . $row['product_name'] . "</div>";
                echo "<div class='col-2'><a href = 'cart-delete.php?id=".$row['order_id']."' class='btn btn-danger fw-bold' style='padding: 5px 10px 5px 10px;'><i class='fas fa-trash-al t'>x</i></a></div>";
                echo "</div>";
                echo "<div class='row bg-light'>";
                echo "<div class='col-10'>" . $row['price_per_quantity'] . "</div>";
                echo "<div class='col-2'>" . $row['quantity'] . "</div>";
                echo "</div>";
                echo "</div>";
              }
              ?>

            </div>
            <div class="card-footer">
              <a href="check-out.php" class="btn btn-success btn-square w-100">Check Out</a>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="card">
            <div class="card-header ps-3 py-2 d-flex justify-content-between align-items-center">
              <p class="card-title m-0 fw-bold">Menu</p>
              <input type="text" id="search-input" class="form-control" placeholder="Search items..." style="width: 400px;">
            </div>
            <div class="card-body menu">
            <p class="no-data-found text-muted text-center">No menu items found.</p>
              <?php
              $query = "SELECT * FROM dishes";
              $result = mysqli_query($conn, $query);

              while ($row = mysqli_fetch_assoc($result)) {
                echo "<form method='post' action='shops.php' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='user_id' value='".$userId."'>";
                echo "<input type='hidden' name='user_fullname' value='".$userFullname."'>";
                echo "<div class='row mb-4'>";
                echo "<div class='col-md-2'>";
                echo "<img src='admin/my-upload/" . $row['img'] . "' style = 'width: 70px; height: 100px;'>";
                echo "</div>";
                echo "<div class='col-md-7'>";
                echo "<p class='fw-bold'>" . $row['title'] . "</p>";
                echo "<p style='font-size: 12px;'>" . $row['slogan'] . "</p>";
                echo "<input type='hidden' name='title' value='" . $row['title'] . "'>";
                echo "</div>";
                echo "<div class='col-md-3'>";
                echo "<label for='quantity'>₱" . $row['price'] . "</label>";
                echo "<input type='hidden' name='price' value='" . $row['price'] . "'>";
                echo "<input type='number' name='quantity' style='width: 100px;' placeholder='quantity' value = '1' min = '1'>";
                echo "<button type='submit' class='btn btn-success btn-square btn-sm mt-2' name='add_to_cart'>Add to Cart</button>";
                echo "</div>";
                echo "</div>";
                echo "</form>";
              }
              ?>
            </div>
            <div class="card-footer">
            </div>
          </div>
        </div>

      </div>

    </div>
  </main>

  <?php
  include("footer.html");
  ?>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
  $(document).ready(function () {
        $('#dtHorizontalExample').DataTable({
            "scrollX": true
        });
        $('.dataTables_length').addClass('bs-select');
    });


    $(document).ready(function () {
    // Function to filter and sort items
    function filterAndSortItems(searchTerm) {
        // Convert search term to lowercase for case-insensitive matching
        searchTerm = searchTerm.toLowerCase();

        var itemsFound = false; // Flag to track if any items are found

        // Loop through each item in the menu
        $(".menu .row").each(function () {
            var itemName = $(this).find(".fw-bold").text().toLowerCase(); // Get item name
            var itemSlogan = $(this).find("p").eq(1).text().toLowerCase(); // Get item slogan

            // Check if item name or slogan contains the search term
            if (itemName.includes(searchTerm) || itemSlogan.includes(searchTerm)) {
                $(this).show(); // Show the item
                itemsFound = true; // Set flag to true since at least one item is found
            } else {
                $(this).hide(); // Hide the item if it doesn't match the search term
            }
        });

        // Display "no data found" message if no items are found
        if (!itemsFound) {
            $(".no-data-found").show();
        } else {
            $(".no-data-found").hide();
        }
    }

    // Event listener for input field changes
    $("#search-input").on("input", function () {
        var searchTerm = $(this).val(); // Get the value of the input field
        filterAndSortItems(searchTerm); // Call the filterAndSortItems function with the search term
    });

    // Call filterAndSortItems function initially to show all items
    filterAndSortItems("");
});
</script>

</body>
</html>
