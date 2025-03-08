<?php
  session_start();
  require_once 'dbcon.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages</title>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Items Place</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/general.css">
  <style>
    body {
      padding-left: 330px;
      padding-right: 330px;
      padding-top: 70px;
    }
    .side-left {
      position: fixed;
      left: 0;
      top: 0;
      width: 330px;
      bottom: 0;
      padding-top: 70px;
    }
    .side-right {
      position: fixed;
      right: 0;
      top: 0;
      width: 330px;
      bottom: 0;
      padding-top: 70px;
      overflow-y: auto;
    }
    .main-massage {
      height: calc(100vh - 70px);
    }
    .message-body {
      height: 70vh;
      overflow-y: auto;
    }
    .image-cover {
      height: 60px; /* Fixed height */
      width: 100px;  /* Fixed width */
      object-fit: cover; /* Adjust image aspect ratio */
    } 

    @media (max-width: 568px) {
      body {
        padding-left: 0;
        padding-right: 0;
        padding-top: 100px;
      }
      .image-container {
        height: 140px;
      }

      .main-massage {
        height: calc(100vh - 100px);
      }

      .side-left, .side-right {
        padding-top: 100px;
        width: 100%;
      }

    }
  </style>
</head>
<body>
  <?php include 'layout/navbar.php'; ?>

  <div class="side-left border bg-white d-none d-md-block px-2">
    <h5 class="fw-bold py-3">Chats</h5>
    <div class="mb-3">
      <a href="#" class="btn btn-success btn-sm rounded-pill px-5">Offers</a>
      <a href="#" class="btn btn-outline-success btn-sm rounded-pill px-5">Proposal</a>
    </div>

    <div class="bg-light rounded" style="max-height: 70vh; overflow-y: auto;">
      
      <a href="" class="text-decoration-none text-dark">

        <div class="px-2 py-2 bg-primary-subtle rounded">
          <div class="row">
            <div class="col-5 d-flex flex-column align-items-center border-end">
              <img src="item-photo/laptop.jpg" alt="Profile Picture" class="rounded" style="width: 90px; height: 70px; object-fit: cover;">
              <p class="m-0 text-xs fw-bold text-center">Test Item lang </p>
            </div>
            <div class="col-7">
              <div class="d-flex gap-1">
                <img src="profile-picture/colet.jpg" alt="Profile Picture" class="rounded-circle" style="width: 30px; height: 30px;">
                <div class="">
                  <p class="fw-bold m-0 text-xs">Ryan Carl Ballero</p>
                  <p class="fw-bold m-0 text-xs">4.3 <i class="bi bi-star-fill text-warning"></i></p>
                </div>
              </div>
              <p class="title m-0 text-sm">Relief Goods</p>
              <p class="date-applied m-0 text-xs text-muted">10/26/2023 10:30:11</p>
              <p class="status m-0 text-muted text-sm">Pending</p>
            </div>
          </div>
        </div>
      </a>

      <a href="" class="text-decoration-none text-dark position-relative">

        <!-- notificaiton -->
        <div class="px-2 py-2 rounded">
          <div class="row">
            <div class="col-5 d-flex flex-column align-items-center border-end">
              <img src="item-photo/laptop.jpg" alt="Profile Picture" class="rounded" style="width: 90px; height: 70px; object-fit: cover;">
              <p class="m-0 text-xs fw-bold text-center">Test Item lang </p>
            </div>
            <div class="col-7">
              <div class="d-flex gap-1">
                <img src="profile-picture/profile.jpg" alt="Profile Picture" class="rounded-circle" style="width: 30px; height: 30px;">
                <div class="">
                  <p class="fw-bold m-0 text-xs">Sherwin Eguna</p>
                  <p class="fw-bold m-0 text-xs">3.3 <i class="bi bi-star-fill text-warning"></i></p>
                </div>
              </div>
              <p class="title m-0 text-sm">Relief Goods</p>
              <p class="date-applied m-0 text-xs text-muted">10/26/2023 10:30:11</p>
              <p class="status m-0 text-muted text-sm">Pending</p>
            </div>
          </div>
        </div>
      </a>

      <?php
        for($i = 0; $i < 5; $i++ ) {
          ?>
            <a href="" class="text-decoration-none text-dark position-relative">
              <!-- notificaiton -->
              <div class="px-2 py-2 rounded">
                <div class="row">
                  <div class="col-5 d-flex flex-column align-items-center border-end">
                    <img src="item-photo/laptop.jpg" alt="Profile Picture" class="rounded" style="width: 90px; height: 70px; object-fit: cover;">
                    <p class="m-0 text-xs fw-bold text-center">Test Item lang </p>
                  </div>
                  <div class="col-7">
                    <div class="d-flex gap-1">
                      <img src="profile-picture/default.jpg" alt="Profile Picture" class="rounded-circle" style="width: 30px; height: 30px;">
                      <div class="">
                        <p class="fw-bold m-0 text-xs">Sherwin Eguna</p>
                        <p class="fw-bold m-0 text-xs">3.3 <i class="bi bi-star-fill text-warning"></i></p>
                      </div>
                    </div>
                    <p class="title m-0 text-sm">Relief Goods</p>
                    <p class="date-applied m-0 text-xs text-muted">10/26/2023 10:30:11</p>
                    <p class="status m-0 text-muted text-sm">Pending</p>
                  </div>
                </div>
              </div>
            </a>
          <?php
        }
      ?>

      
    </div>
  </div>

  <div class="side-right bg-white border px-2 d-none d-md-block">
    <h5 class="text-center my-3">Make Plan</h5>


    <div class="p-1 pt-2">
      <div class="border p-2 rounded mb-4">
        <p class="text-sm fw-bold">Ryan's Proposal</p>
        <div class="d-flex gap-2 mb-3">
          <img src="offer-item-photo/6675f7ddd8bc5-sword.jpg" class="image-cover rounded" alt="">
          <div>
            <p class="text-sm fw-bold m-0">Relief Goods</p>
            <p class="text-sm m-0">Relief Goods worth 500</p>
            <p class="text-sm m-0">Condition: New</p>
          </div>
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
        <button class="btn btn-sm btn-secondary mb-3">Update</button>
        <div class="d-flex gap-3">
          <button class="btn btn-sm btn-primary w-100">Accept</button>
          <button class="btn btn-sm btn-danger w-100">Reject</button>
        </div>
      </div>
    </div>
  </div>

  <main class="fluid border main-massage">

    <div class="message-top border-bottom px-3 py-2">
      <div class="d-flex gap-3 align-items-center">
        <img src="item-photo/laptop.jpg" alt="Profile Picture" class="rounded-circle" style="width: 50px; height: 50px;">
        <div class="">
          <p class="fw-bold m-0 text-sm">Test Item Lang</p>
          <p class="fw-bold m-0 text-sm">Ryan Ballero</p>
        </div>
      </div>
    </div>

    <div class="message-body px-3 pt-5">
      <?php
       for ($i = 0; $i < 2; $i++) {
        ?>
          <div class="d-flex justify-content-start mb-2 gap-2 w-100">
            <div class="d-flex align-items-end gap-3">
              <img src="profile-picture/colet.jpg" alt="Profile Picture" class="rounded-circle" style="width: 30px; height: 30px;">
            </div>
            <div class="w-50 ">
              <p class="text-xs text-muted m-0">Ryan</p>
              <div class="border border-secondary p-1 rounded bg-light text-xs">Lorem ipsum dolor sit amet consectetur adipisicing elit</div>
              <p class="text-xs text-muted m-0">10/26/2023 10:30:11</p>
            </div>
          </div>
          <div class="d-flex justify-content-end gap-2 mb-2 w-100">
            <div class="w-50 ">
              <div class=" border border-primary p-1 rounded bg-primary-subtle text-xs">Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>
              <p class="text-xs text-muted m-0">10/26/2023 10:30:11</p>
            </div>
          </div>
        <?php
       }
      ?>
      
    </div>
    <div class="border-top">
      <form action="">
        <div class="d-flex">
          <input type="text" class="form-control">
          <button class="btn btn-info btn-sm px-4"><i class="bi bi-send"></i></button>
        </div>
      </form>
    </div>
  </main>
  

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>

<script>
  $('.forSide-left').on('click', function() {
    $('.side-right').addClass('d-none');
    $('.side-left').toggleClass('d-none');
  });

  $('.forSide-right').on('click', function() {
    $('.side-left').addClass('d-none');
    $('.side-right').toggleClass('d-none');
  });
</script>
</body>
</html>