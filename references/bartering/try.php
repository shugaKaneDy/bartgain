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

  <style>
    body {
      padding-top: 100px;
      font-family: Poppins;
    }
    

    .div-img-lg {
      max-width: 500px;
      height: 300px;
    }
    .img-lg {
      max-width: 480px;
      max-height: 280px;
    }
    .div-img-md {
      max-width: 450px;
      height: 220px;
    }
    .img-md {
      max-width: 330px;
      max-height: 200px;
    }

    /* Target the search box */
    .dataTables_filter {
        margin-bottom: 20px; /* Adjust the margin as needed */
    }

    .dataTables_filter label {
        font-weight: bold; /* Make the label bold */
        font-size: 14px; /* Adjust the font size */
    }
    .dataTables_filter input {
        border: 1px solid #ccc; /* Add border */
        padding: 5px; /* Add padding */
        font-size: 14px; /* Adjust the font size */
        width: 50px; /* Set a fixed width */
    }


    @media (max-width: 576px) {
      body {
        padding-top: 80px;
      }
      .div-img-lg, .div-img-md {
        max-width: 300px;
        height: 250px;
      }
      .img-lg, .img-md {
        max-width: 270px;
        max-height: 200px;
      }
      .dataTables_filter label {
        font-size: 10px; /* Adjust the font size */
      }
    }
  </style>
  
</head>
<body class="bg-light-subtle">
  <nav class="navbar navbar-expand-lg bg-white fixed-top border-bottom shadow-sm">
    <div class="container-fluid">
      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-white fs-4 fw-bold" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample"><i class="bi bi-filter-left"></i></a>
        <div class="rounded-circle bg-success" style="height: 30px; width: 30px;"></div>
        <a class="navbar-brand text-success fw-bold" href="#">BartGain</a>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav d-flex align-items-md-center">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="try.php">Item Place</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="#">Add Item</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="#">Offers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="#">Favorites</a>
          </li>
          <li class="nav-item">
            <div class="dropdown-center">
              <button class="btn btn-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="profile-picture/profile.jpg" alt="Profile Picture" class="rounded-circle img-thumbnail" style="width: 40px; height: 40px;">
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="#">Logout</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasExampleLabel">Filter</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="p-3">
      <div class="card-body">
        <div>
          <form id="searchForm" action="try.php" method="POST">
            <label class="form-label" for="">Category: </label>
            <select name="category" class="form-control" id="myCategory">
              <option value="" disabled selected >Select category</option>
              <option value="Electronics">Electronics</option>
              <option value="Furniture">Furniture</option>
            </select>
          </form>
        </div>
      </div>
    </div>
  </div>


  <h2 class="text-center fw-bold mb-5">
    Item Place
  </h2>

  <section class="container-md">
    <div class="row">
      <div class="col-5 d-none d-md-block">
        <div class="card mb-5 border border-success-subtle">
          <h5 class="card-header">Best Matches <i class="text-warning bi bi-lightbulb-fill"></i></h5>
          <div class="card-body">
            <div class="mt-3">
              <p class="h6"><span class="bg-warning text-white px-3 py-1 rounded">Nearest:</span></p>

              <div class="border rounded p-2 mb-3">
                <div class="d-flex gap-3 mb-4">
                  <img src="profile-picture/colet.jpg" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
                  <div class="d-flex flex-column">
                    <p class="fw-bold m-0">Colete Bergara Macalin</p>
                    <p class="fw-bold m-0">4.3 <i class="bi bi-star-fill text-warning"></i></p>
                  </div>
                </div>
                <p class="fw-bold fs-5 m-0">Sonny</p>
                <p class="fs-sm">This is a detailed description of the iPhone 12 Pro Max. It includes information about its features, specifications, condition, and any other relevant details.</p>
                <p class="text-success fw-bold fs-sm">Swap</p>
                <p class="text-muted m-0">Category: Electronics</p>
                <p class="text-muted">Condition: Fair</p>
                <div class="d-flex gap-5">
                  <p class="text-muted"><i class="bi bi-geo-alt text-success"></i> Dasmarinas, Cavite</p>
                  <p class="text-muted">0.2 Km</p>
                </div>
                <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-md" >
                  <img src="item-photo/sonys.jpg" alt="" class="rounded img-md">
                </div>
                <div class="my-2">
                  <button class="btn btn-sm btn-light border"><i class="bi bi-heart text-success"></i></button>
                  <button class="btn btn-sm btn-success">Make Offer</button>
                </div>
              </div>
            </div>

            <div class="mt-3">
              <p class="h6"><span class="bg-warning text-white px-3 py-1 rounded">Ratings:</span></p>

              <div class="border rounded p-2 mb-3">
                <div class="d-flex gap-3 mb-4">
                  <img src="profile-picture/colets.jpg" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
                  <div class="d-flex flex-column">
                    <p class="fw-bold m-0">Colete Bernado Batumbakal</p>
                    <p class="fw-bold m-0">5.0 <i class="bi bi-star-fill text-warning"></i></p>
                  </div>
                </div>
                <p class="fw-bold fs-5 m-0">Sonny</p>
                <p class="fs-sm">This is the best earphone</p>
                <p class="text-success fw-bold fs-sm">Swap</p>
                <p class="text-muted m-0">Category: Electronics</p>
                <p class="text-muted">Condition: Good</p>
                <div class="d-flex gap-5">
                  <p class="text-muted"><i class="bi bi-geo-alt text-success"></i> Dasmarinas, Cavite</p>
                  <p class="text-muted">0.2 Km</p>
                </div>
                <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-md">
                  <img src="item-photo/Apple-Airpods.jpg" alt="" class="rounded img-md">
                </div>
                <div class="my-2">
                  <button class="btn btn-sm btn-light border"><i class="bi bi-heart text-success"></i></button>
                  <button class="btn btn-sm btn-success">Make Offer</button>
                </div>
              </div>
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
          <tr>
            <td>
              <div class="w-100 mt-3">
              <div class="d-flex gap-3 mb-4">
                <img src="profile-picture/profile.jpg" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
                <div class="d-flex flex-column">
                  <p class="fw-bold m-0">Kane Gerickson B. Tagay</p>
                  <p class="fw-bold m-0">5.0 <i class="bi bi-star-fill text-warning"></i></p>
                </div>
              </div>
                <p class="fw-bold fs-4 m-0">Cellphone</p>
                <p>This is a detailed description of the iPhone 12 Pro Max. It includes information about its features, specifications, condition, and any other relevant details.</p>
                <p class="text-success fw-bold">Swap</p>
                <p class="text-muted m-0">Category: Electronics</p>
                <p class="text-muted">Condition: Like new</p>
                <div class="d-flex gap-5">
                  <p class="text-muted"><i class="bi bi-geo-alt text-success"></i> Dasmarinas, Cavite</p>
                  <p class="text-muted">0.5 Km</p>
                </div>
                <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-lg">
                  <img src="item-photo/laptop.jpg" alt="" class="rounded img-lg">
                </div>
                <div class="my-3">
                  <button class="btn btn-light border"><i class="bi bi-heart text-success"></i></button>
                  <button class="btn btn-success">Make Offer</button>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="w-100 mt-3">
              <div class="d-flex gap-3 mb-4">
                <img src="profile-picture/profile.jpg" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
                <div class="d-flex flex-column">
                  <p class="fw-bold m-0">Kane Gerickson B. Tagay</p>
                  <p class="fw-bold m-0">5.0 <i class="bi bi-star-fill text-warning"></i></p>
                </div>
              </div>
                <p class="fw-bold fs-4 m-0">Cellphone</p>
                <p>This is a detailed description of the iPhone 12 Pro Max. It includes information about its features, specifications, condition, and any other relevant details.</p>
                <p class="text-success fw-bold">Swap </p>
                <p class="text-muted m-0">Category: Electronics</p>
                <p class="text-muted">Condition: Like new</p>
                <div class="d-flex gap-5">
                  <p class="text-muted"><i class="bi bi-geo-alt text-success"></i> Dasmarinas, Cavite</p>
                  <p class="text-muted">0.5 Km</p>
                </div>
                <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-lg">
                  <img src="item-photo/bgl.png" alt="" class="rounded img-lg">
                </div>
                <div class="my-3">
                  <button class="btn btn-light border"><i class="bi bi-heart text-success"></i></button>
                  <button class="btn btn-success">Make Offer</button>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div>
                <p>Sherwin Eguna</p>
                <p>Swap</p>
                <p>Matt</p>
                <p>Category: Furniture</p>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div>
                <p>Billy Joshua</p>
                <p>Swap</p>
                <p>Matt</p>
                <p>Category: Furniture</p>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div>
                <p>Mark Cuevas</p>
                <p>Swap</p>
                <p>Matt</p>
                <p>Category: Furniture</p>
              </div>
            </td>
          </tr>
          
          </tbody>
        </table>
      </div>
      <div class="col-12 d-md-none">
        <div class="card mb-5 border border-success-subtle">
          <h5 class="card-header">Best Matches <i class="text-success bi bi-lightbulb-fill"></i></h5>
          <div class="card-body">
            <div class="mt-3">
              <p class="h6"><span class="bg-warning text-white px-3 py-1 rounded">Nearest:</span></p>
              <div class="border rounded p-2 mb-3">
                <div class="d-flex gap-3 mb-4">
                  <img src="profile-picture/colet.jpg" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
                  <div class="d-flex flex-column">
                    <p class="fw-bold m-0">Colete Bergara Macalin</p>
                    <p class="fw-bold m-0">4.3 <i class="bi bi-star-fill text-warning"></i></p>
                  </div>
                </div>
                <p class="fw-bold fs-5 m-0">Sonny</p>
                <p class="fs-sm">This is a detailed description of the iPhone 12 Pro Max. It includes information about its features, specifications, condition, and any other relevant details.</p>
                <p class="text-success fw-bold fs-sm">Swap</p>
                <p class="text-muted m-0">Category: Electronics</p>
                <p class="text-muted">Condition: Fair</p>
                <div class="d-flex gap-5">
                  <p class="text-muted"><i class="bi bi-geo-alt text-success"></i> Dasmarinas, Cavite</p>
                  <p class="text-muted">0.2 Km</p>
                </div>
                <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-md" >
                  <img src="item-photo/sonys.jpg" alt="" class="rounded img-md">
                </div>
                <div class="my-2">
                  <button class="btn btn-sm btn-light border"><i class="bi bi-heart text-success"></i></button>
                  <button class="btn btn-sm btn-success">Make Offer</button>
                </div>
              </div>
            </div>

            <div class="mt-3">
              <p class="h6"><span class="bg-warning text-white px-3 py-1 rounded">Ratings:</span></p>
              <div class="border rounded p-2 mb-3">
                <div class="d-flex gap-3 mb-4">
                  <img src="profile-picture/colets.jpg" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
                  <div class="d-flex flex-column">
                    <p class="fw-bold m-0">Colete Bernado Batumbakal</p>
                    <p class="fw-bold m-0">5.0 <i class="bi bi-star-fill text-warning"></i></p>
                  </div>
                </div>
                <p class="fw-bold fs-5 m-0">Sonny</p>
                <p class="fs-sm">This is the best earphone</p>
                <p class="text-success fw-bold fs-sm">Swap</p>
                <p class="text-muted m-0">Category: Electronics</p>
                <p class="text-muted">Condition: Good</p>
                <div class="d-flex gap-5">
                  <p class="text-muted"><i class="bi bi-geo-alt text-success"></i> Dasmarinas, Cavite</p>
                  <p class="text-muted">0.2 Km</p>
                </div>
                <div class="bg-secondary-subtle rounded d-flex justify-content-center align-items-center mx-auto div-img-md">
                  <img src="item-photo/Apple-Airpods.jpg" alt="" class="rounded img-md">
                </div>
                <div class="my-2">
                  <button class="btn btn-sm btn-light border"><i class="bi bi-heart text-success"></i></button>
                  <button class="btn btn-sm btn-success">Make Offer</button>
                </div>
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

<script>
  $(document).ready( function () {
    $('#myTable').DataTable({
      "ordering": false, // Disable ordering (sorting) for the entire table
      "dom": '<"top"f>rt<"bottom"lp><"clear">'
    });
  });

  /* function display() {
    $.ajax({
      url: 'table-content.php',
      type: 'GET',
      success: function(response) {
          $('#myTbody').html(response);
      },
      error: function(xhr, status, error) {
          console.error('Error: ' + error);
      }
    });
  } */

 /*  $('#myCategory').on('change', function() {
    console.log($(this).val());
    $('#myTbody').empty();
    if($(this).val() === "Electronics") {
      $.ajax({
        url: 'electronics.php',
        type: 'GET',
        success: function(response) {
            $('#myTbody').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error: ' + error);
        }
      });
    } else {
      $.ajax({
        url: 'furniture.php',
        type: 'GET',
        success: function(response) {
            $('#myTbody').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error: ' + error);
        }
      });
    }
  }); */

  $('#myCategory').on('change', function() {
    $('#searchForm').submit();
  });

  $('#filterBtn').on('click', function() {
    $('#filterCard').slideToggle();
  });


  
</script>
</body>
</html>